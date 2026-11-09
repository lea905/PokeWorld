<?php

namespace Database\Seeders;

use App\Models\Pokemon;
use App\Models\Type;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Importe les Pokémon depuis la PokéAPI puis relie les chaînes d'évolution.
 * Le nombre de Pokémon importés est réglable via POKEMON_SEED_LIMIT (max 1025).
 */
class PokemonSeeder extends Seeder
{
    private const ROMAN = ['i' => 1, 'ii' => 2, 'iii' => 3, 'iv' => 4, 'v' => 5, 'vi' => 6, 'vii' => 7, 'viii' => 8, 'ix' => 9];

    public function run(): void
    {
        $limit = min(1025, max(1, (int) config('pokeworld.seed_limit', 1025)));

        // Types indexés par nom anglais : fournis par TypeSeeder, sinon on les retrouve via la PokéAPI.
        $types = TypeSeeder::$types ?: $this->loadTypesByEnglishName();

        /** @var array<int, array{pokemon: Pokemon, species: array}> $pokemonsData */
        $pokemonsData = [];

        $bar = $this->command?->getOutput()->createProgressBar($limit);
        $bar?->start();

        // 1ère boucle : créer les Pokémon
        for ($i = 1; $i <= $limit; $i++) {
            try {
                $data = Http::retry(3, 500)->get("https://pokeapi.co/api/v2/pokemon/{$i}")->throw()->json();
                $species = Http::retry(3, 500)->get("https://pokeapi.co/api/v2/pokemon-species/{$i}")->throw()->json();

                $names = collect($species['names']);
                $varieties = collect($species['varieties'] ?? [])->pluck('pokemon.name')->map(fn ($n) => strtolower($n));
                $stats = collect($data['stats'])->mapWithKeys(fn ($s) => [$s['stat']['name'] => $s['base_stat']]);
                $description = collect($species['flavor_text_entries'])->firstWhere('language.name', 'fr')['flavor_text'] ?? '';
                $generation = str_replace('generation-', '', $species['generation']['name'] ?? 'generation-i');

                $pokemon = Pokemon::create([
                    'nom' => $names->firstWhere('language.name', 'fr')['name'] ?? $data['name'],
                    'nom_japonais' => $names->firstWhere('language.name', 'ja')['name'] ?? $data['name'],
                    'numero_pokedex' => $data['id'],
                    'generation' => self::ROMAN[strtolower($generation)] ?? 1,
                    'description' => str_replace(["\n", "\f"], ' ', $description),
                    'mega_evolution_possible' => $varieties->contains(fn ($n) => str_contains($n, 'mega')),
                    'dynamax_possible' => $varieties->contains(fn ($n) => str_contains($n, 'gmax') || str_contains($n, 'gigantamax')),
                    'type1' => $types[$data['types'][0]['type']['name']]->id,
                    'type2' => isset($data['types'][1]) ? $types[$data['types'][1]['type']['name']]->id : null,
                    'taille' => $data['height'] ?? null,
                    'poids' => $data['weight'] ?? null,
                    'couleur' => $species['color']['name'] ?? null,
                    'habitat' => $species['habitat']['name'] ?? null,
                    'capture_rate' => $species['capture_rate'] ?? null,
                    'base_experience' => $data['base_experience'] ?? null,
                    'is_legendary' => $species['is_legendary'] ?? false,
                    'is_mythical' => $species['is_mythical'] ?? false,
                    'gender_rate' => $species['gender_rate'] ?? null,
                    'image_principale' => $data['sprites']['front_default'] ?? '',
                    'pv' => $stats['hp'] ?? 0,
                    'attaque' => $stats['attack'] ?? 0,
                    'defense' => $stats['defense'] ?? 0,
                    'attaque_spe' => $stats['special-attack'] ?? 0,
                    'defence_spe' => $stats['special-defense'] ?? 0,
                    'vitesse' => $stats['speed'] ?? 0,
                ]);

                $pokemonsData[$data['id']] = ['pokemon' => $pokemon, 'species' => $species];
            } catch (Throwable $e) {
                Log::warning("Erreur pour le Pokémon {$i} : ".$e->getMessage());
            }

            $bar?->advance();
        }

        $bar?->finish();
        $this->command?->newLine();

        // 2ème boucle : traiter les chaînes d'évolution
        $processedChains = [];

        foreach ($pokemonsData as $i => $dataSet) {
            $chainUrl = $dataSet['species']['evolution_chain']['url'] ?? null;

            if (! $chainUrl || isset($processedChains[$chainUrl])) {
                continue;
            }

            try {
                $chain = Http::retry(3, 500)->get($chainUrl)->throw()->json('chain');
                $this->processEvolutionChain($chain, $pokemonsData);
                $processedChains[$chainUrl] = true;
            } catch (Throwable $e) {
                Log::warning("Erreur sur la chaîne d'évolution du Pokémon {$i} : ".$e->getMessage());
            }
        }

        $this->command?->info(count($pokemonsData).' Pokémon importés.');
    }

    private function processEvolutionChain(array $chain, array $pokemonsData): void
    {
        $currentId = $this->idFromSpeciesUrl($chain['species']['url']);

        foreach ($chain['evolves_to'] ?? [] as $next) {
            $nextId = $this->idFromSpeciesUrl($next['species']['url']);

            if (isset($pokemonsData[$currentId], $pokemonsData[$nextId])) {
                $pokemonsData[$nextId]['pokemon']->update([
                    'idEvolutionPrecedente' => $pokemonsData[$currentId]['pokemon']->id_pokemon,
                ]);
            }

            $this->processEvolutionChain($next, $pokemonsData);
        }
    }

    private function idFromSpeciesUrl(string $url): int
    {
        return (int) basename(rtrim($url, '/'));
    }

    /** @return array<string, Type> */
    private function loadTypesByEnglishName(): array
    {
        $byFrenchName = Type::all()->keyBy('nom');
        $types = [];

        foreach (Http::get('https://pokeapi.co/api/v2/type')->throw()->json('results') as $typeData) {
            $detail = Http::get($typeData['url'])->throw()->json();
            $nom = collect($detail['names'])->firstWhere('language.name', 'fr')['name'] ?? $typeData['name'];

            if (isset($byFrenchName[$nom])) {
                $types[$typeData['name']] = $byFrenchName[$nom];
            }
        }

        return $types;
    }
}
