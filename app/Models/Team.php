<?php

namespace App\Models;

use App\Enums\Jeu;
use Illuminate\Database\Eloquent\Casts\AsEnumCollection;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * @property Collection<int, Jeu>|null $jeux
 */
class Team extends Model
{
    /** Séparateur entre le lieu et les jeux dans le nom d'une équipe (« Arène de Jadielle — Pokémon Jaune »). */
    public const SEPARATEUR_JEUX = ' — ';

    protected $table = 'team';

    public $timestamps = false;

    protected $fillable = ['nom', 'jeux', 'dresseur_id'];

    protected function casts(): array
    {
        return ['jeux' => AsEnumCollection::of(Jeu::class)];
    }

    /** @return BelongsTo<Dresseur, $this> */
    public function dresseur(): BelongsTo
    {
        return $this->belongsTo(Dresseur::class, 'dresseur_id');
    }

    /** @return BelongsToMany<Pokemon, $this> */
    public function pokemons(): BelongsToMany
    {
        return $this->belongsToMany(Pokemon::class, 'team_pokemon', 'team_id', 'pokemon_id')
            ->withPivot(['id', 'niveau', 'position'])
            ->orderByPivot('position');
    }

    /**
     * Nom à afficher dans un onglet de jeu : le jeu y est déjà indiqué,
     * on retire donc la partie « — Pokémon … » du nom complet.
     *
     * @return Attribute<string, never>
     */
    protected function titre(): Attribute
    {
        return Attribute::get(function () {
            $nom = (string) $this->nom;

            if (! $this->jeux?->isNotEmpty() || ! str_contains($nom, self::SEPARATEUR_JEUX)) {
                return $nom;
            }

            // Une précision entre parenthèses après le jeu est conservée
            $precision = preg_match('/ (\([^)]*\))$/', $nom, $m) ? ' '.$m[1] : '';

            return Str::beforeLast($nom, self::SEPARATEUR_JEUX).$precision;
        });
    }

    /**
     * Remplace la composition par les Pokémon donnés, dans cet ordre.
     * Les niveaux déjà enregistrés pour les Pokémon conservés sont gardés.
     *
     * @param  array<int, int|string>  $pokemonIds
     */
    public function syncPokemons(array $pokemonIds): void
    {
        $this->pokemons()->sync(
            collect($pokemonIds)->values()->mapWithKeys(fn ($id, $index) => [(int) $id => ['position' => $index + 1]])->all()
        );
    }

    /**
     * Regroupe des équipes par jeu, dans l'ordre de sortie des jeux.
     * Une équipe qui couvre plusieurs jeux apparaît dans chacun d'eux ;
     * les équipes sans jeu sont réunies à la fin dans « Autres équipes ».
     *
     * @param  iterable<int, Team>  $teams
     * @return Collection<int, array{cle: string, libelle: string, teams: Collection<int, Team>}>
     */
    public static function grouperParJeu(iterable $teams): Collection
    {
        $teams = collect($teams);
        $groupes = collect();

        foreach (Jeu::cases() as $jeu) {
            $duJeu = $teams->filter(fn (Team $team) => $team->jeux?->contains($jeu))->values();

            if ($duJeu->isNotEmpty()) {
                $groupes->push(['cle' => $jeu->value, 'libelle' => $jeu->label(), 'teams' => $duJeu]);
            }
        }

        $sansJeu = $teams->filter(fn (Team $team) => ! $team->jeux?->isNotEmpty())->values();

        if ($sansJeu->isNotEmpty()) {
            $groupes->push(['cle' => 'autres', 'libelle' => 'Autres équipes', 'teams' => $sansJeu]);
        }

        return $groupes;
    }

    public function __toString(): string
    {
        return $this->nom ?? 'Team';
    }
}
