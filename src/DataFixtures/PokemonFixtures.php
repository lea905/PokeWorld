<?php
namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Pokemon;
use App\Entity\Type;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class PokemonFixtures extends Fixture implements DependentFixtureInterface
{
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    private function romanToDecimal(string $roman): int
    {
        $romanToNumber = [
            'i' => 1,
            'ii' => 2,
            'iii' => 3,
            'iv' => 4,
            'v' => 5,
            'vi' => 6,
            'vii' => 7,
            'viii' => 8,
            'ix' => 9,
        ];

        return $romanToNumber[strtolower($roman)] ?? 1;
    }

    private function getPokemonIdFromSpeciesUrl(string $url): int
    {
        $parts = explode('/', trim($url, '/'));
        return (int) end($parts);
    }

    public function load(ObjectManager $manager): void
    {
        $pokemonsData = [];
        $processedChains = [];

        // 1ère boucle : créer les Pokémon
        for ($i = 1; $i <= 151; $i++) {
            try {
                $response = $this->client->request('GET', "https://pokeapi.co/api/v2/pokemon/$i");
                $data = $response->toArray();

                $speciesResponse = $this->client->request('GET', "https://pokeapi.co/api/v2/pokemon-species/$i");
                $speciesData = $speciesResponse->toArray();

                $nomJaponais = null;
                foreach ($speciesData['names'] as $name) {
                    if ($name['language']['name'] === 'ja') {
                        $nomJaponais = $name['name'];
                        break;
                    }
                }

                $description = '';
                foreach ($speciesData['flavor_text_entries'] as $entry) {
                    if ($entry['language']['name'] === 'fr') {
                        $description = str_replace(["\n", "\f"], ' ', $entry['flavor_text']);
                        break;
                    }
                }

                $nomFrancais = $data['name'];
                foreach ($speciesData['names'] as $nameEntry) {
                    if ($nameEntry['language']['name'] === 'fr') {
                        $nomFrancais = $nameEntry['name'];
                        break;
                    }
                }

                $generationName = $speciesData['generation']['name'] ?? 'generation-i';
                $romanNumber = str_replace('generation-', '', $generationName);
                $generation = $this->romanToDecimal($romanNumber);

                $pokemon = new Pokemon();
                $pokemon->setNom($nomFrancais);
                $pokemon->setNumeroPokedex($data['id']);
                $pokemon->setGeneration($generation);
                $pokemon->setNomJaponais($nomJaponais ?? $data['name']);
                $pokemon->setDescription($description);

                $hasMega = false;
                $hasGmax = false;
                if (isset($speciesData['varieties'])) {
                    foreach ($speciesData['varieties'] as $variety) {
                        $varietyName = $variety['pokemon']['name'];
                        if (str_contains(strtolower($varietyName), 'mega')) {
                            $hasMega = true;
                        }
                        if (str_contains(strtolower($varietyName), 'gmax') || str_contains(strtolower($varietyName), 'gigantamax')) {
                            $hasGmax = true;
                        }
                    }
                }
                $pokemon->setMegaEvolutionPossible($hasMega);
                $pokemon->setDynamaxPossible($hasGmax);

                $type1 = $this->getReference('type_' . $data['types'][0]['type']['name'], Type::class);
                $pokemon->setType1($type1);

                if (isset($data['types'][1])) {
                    $type2 = $this->getReference('type_' . $data['types'][1]['type']['name'], Type::class);
                    $pokemon->setType2($type2);
                }

                $pokemon->setTaille($data['height'] ?? null);
                $pokemon->setPoids($data['weight'] ?? null);

                $color = $speciesData['color']['name'] ?? null;
                $pokemon->setCouleur($color);

                $habitat = $speciesData['habitat']['name'] ?? null;
                $pokemon->setHabitat($habitat);

                $pokemon->setCaptureRate($speciesData['capture_rate'] ?? null);
                $pokemon->setBaseExperience($data['base_experience'] ?? null);

                $pokemon->setIsLegendary($speciesData['is_legendary'] ?? false);
                $pokemon->setIsMythical($speciesData['is_mythical'] ?? false);

                $pokemon->setGenderRate($speciesData['gender_rate'] ?? null);

                $pokemon->setImagePrincipale($data['sprites']['front_default'] ?? null);

                foreach ($data['stats'] as $stat) {
                    switch ($stat['stat']['name']) {
                        case 'hp': $pokemon->setPv($stat['base_stat']); break;
                        case 'attack': $pokemon->setAttaque($stat['base_stat']); break;
                        case 'defense': $pokemon->setDefense($stat['base_stat']); break;
                        case 'special-attack': $pokemon->setAttaqueSpe($stat['base_stat']); break;
                        case 'special-defense': $pokemon->setDefenceSpe($stat['base_stat']); break;
                        case 'speed': $pokemon->setVitesse($stat['base_stat']); break;
                    }
                }

                $manager->persist($pokemon);
                $this->addReference('pokemon_' . $data['id'], $pokemon);
                error_log("Référence ajoutée : pokemon_" . $data['id']);

                $pokemonsData[$i] = [
                    'pokemon' => $pokemon,
                    'speciesData' => $speciesData,
                ];

            } catch (\Exception $e) {
                error_log("Erreur pour Pokémon $i : " . $e->getMessage());
                continue;
            }
        }

        $manager->flush();

        // 2ème boucle : traiter les chaînes d'évolution
        foreach ($pokemonsData as $i => $dataSet) {
            $speciesData = $dataSet['speciesData'];
            $chainUrl = $speciesData['evolution_chain']['url'] ?? null;

            if ($chainUrl && !in_array($chainUrl, $processedChains)) {
                try {
                    $evolutionResponse = $this->client->request('GET', $chainUrl);
                    $evolutionData = $evolutionResponse->toArray();
                    error_log("Traitement de la chaîne d'évolution : $chainUrl");
                    $this->processEvolutionChain($evolutionData['chain'], $manager, $pokemonsData);
                    $processedChains[] = $chainUrl;
                } catch (\Exception $e) {
                    error_log("Erreur lors de la récupération de la chaîne d'évolution pour Pokémon $i : " . $e->getMessage());
                    continue;
                }
            }
        }

        $manager->flush();
    }

    private function processEvolutionChain(array $chain, ObjectManager $manager, array $pokemonsData): void
    {
        $currentSpeciesName = $chain['species']['name'];
        $currentPokemonId = $this->getPokemonIdFromSpeciesUrl($chain['species']['url']);

        error_log("Traitement de la chaîne pour : $currentSpeciesName (ID: $currentPokemonId)");

        // Vérifier si le Pokémon existe dans pokemonsData
        if (isset($pokemonsData[$currentPokemonId])) {
            $pokemon = $pokemonsData[$currentPokemonId]['pokemon'];
            error_log("Pokémon trouvé dans pokemonsData : {$pokemon->getNom()} (ID: $currentPokemonId)");

            // Évolutions suivantes
            if (!empty($chain['evolves_to'])) {
                foreach ($chain['evolves_to'] as $nextEvolution) {
                    $nextSpeciesId = $this->getPokemonIdFromSpeciesUrl($nextEvolution['species']['url']);
                    if (isset($pokemonsData[$nextSpeciesId])) {
                        $nextPokemon = $pokemonsData[$nextSpeciesId]['pokemon'];
                        $pokemon->addEvolutionSuivante($nextPokemon);
                        error_log("Évolution suivante assignée : {$nextPokemon->getNom()} pour {$pokemon->getNom()}");
                        $manager->persist($nextPokemon);
                    } else {
                        error_log("Pokémon suivant absent dans pokemonsData : pokemon_$nextSpeciesId pour $currentSpeciesName");
                    }
                }
            }

            $manager->persist($pokemon);
        } else {
            error_log("Pokémon absent dans pokemonsData : pokemon_$currentPokemonId ($currentSpeciesName)");
        }

        // Parcourir récursivement les évolutions suivantes
        if (!empty($chain['evolves_to'])) {
            foreach ($chain['evolves_to'] as $nextEvolution) {
                $this->processEvolutionChain($nextEvolution, $manager, $pokemonsData);
            }
        }
    }

    public function getDependencies(): array
    {
        return [
            TypeFixtures::class,
        ];
    }
}