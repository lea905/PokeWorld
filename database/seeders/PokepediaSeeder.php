<?php

namespace Database\Seeders;

use App\Models\Dresseur;
use App\Models\Organisation;
use App\Models\Pokemon;
use App\Models\Team;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Applique les données vérifiées sur Poképédia (database/data/pokepedia.php) :
 * informations des dresseurs et équipes complètes, avec niveaux et jeu d'origine.
 *
 * Peut être relancé sans risque : php artisan db:seed --class=PokepediaSeeder
 */
class PokepediaSeeder extends Seeder
{
    /** Anciennes équipes non vérifiées (ajoutées de mémoire), remplacées par les équipes Poképédia. */
    private const ANCIENNES_EQUIPES = [
        'Équipe de Giovanni', "Équipe d'Amos", 'Équipe du Sbire', 'Équipe de Arthur', 'Équipe de Max',
        'Équipe de Hélio', 'Équipe de Sacha', 'Équipe de Pierre', "Équipe d'Ondine",
    ];

    public function run(): void
    {
        foreach (require database_path('data/organisations.php') as $nom => $organisation) {
            Organisation::where('nom', $nom)->update(['but' => $organisation['but']]);
        }

        $donnees = require database_path('data/pokepedia.php');

        foreach ($donnees as $personnage => $fiche) {
            $dresseur = Dresseur::whereIn('prenom', $fiche['prenoms'])->first();

            if (! $dresseur) {
                $this->command?->warn("{$personnage} : dresseur absent de la base, ignoré.");

                continue;
            }

            DB::transaction(function () use ($dresseur, $fiche, $personnage) {
                $dresseur->fill($fiche['infos'])->save();

                $dresseur->teams()->whereIn('nom', self::ANCIENNES_EQUIPES)->delete();

                foreach ($fiche['equipes'] as [$nom, $pokemons]) {
                    $this->creerEquipe($dresseur, $nom, $pokemons, $personnage);
                }
            });
        }
    }

    /**
     * Crée (ou remet à jour) une équipe et sa composition, dans l'ordre donné.
     *
     * @param  array<int, array{0: string, 1: int|null}|int>  $pokemons  [nom, niveau] ou numéro du Pokédex
     */
    private function creerEquipe(Dresseur $dresseur, string $nom, array $pokemons, string $personnage): void
    {
        $lignes = [];

        foreach ($pokemons as $position => $pokemon) {
            [$recherche, $niveau] = is_array($pokemon) ? $pokemon : [$pokemon, null];

            $id = Pokemon::query()
                ->without(['typePrincipal', 'typeSecondaire'])
                ->where(is_int($recherche) ? 'numero_pokedex' : 'nom', $recherche)
                ->value('id_pokemon');

            if (! $id) {
                $this->command?->warn("{$personnage} : « {$recherche} » absent de la base, équipe « {$nom} » ignorée.");

                return; // import partiel de la PokéAPI : on ne crée pas une équipe incomplète
            }

            $lignes[] = ['pokemon_id' => $id, 'niveau' => $niveau, 'position' => $position + 1];
        }

        $team = Team::firstOrCreate(['nom' => $nom, 'dresseur_id' => $dresseur->id]);

        // La composition est entièrement réécrite : les données Poképédia font foi
        DB::table('team_pokemon')->where('team_id', $team->id)->delete();
        DB::table('team_pokemon')->insert(array_map(fn ($ligne) => $ligne + ['team_id' => $team->id], $lignes));
    }
}
