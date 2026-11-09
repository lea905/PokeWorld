<?php

namespace Database\Seeders;

use App\Models\Type;
use App\Models\TypeRelation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

/**
 * Importe les types depuis la PokéAPI (noms français) et leurs relations d'efficacité.
 */
class TypeSeeder extends Seeder
{
    /** @var array<string, Type> Types indexés par leur nom anglais (clé PokéAPI). */
    public static array $types = [];

    public function run(): void
    {
        $results = Http::get('https://pokeapi.co/api/v2/type')->throw()->json('results');

        $details = [];

        foreach ($results as $typeData) {
            $detail = Http::get($typeData['url'])->throw()->json();
            $details[$typeData['name']] = $detail;

            $nomFrancais = collect($detail['names'])
                ->firstWhere('language.name', 'fr')['name'] ?? $typeData['name'];

            self::$types[$typeData['name']] = Type::create(['nom' => $nomFrancais]);
        }

        foreach ($details as $typeName => $detail) {
            $attaquant = self::$types[$typeName];
            $relations = $detail['damage_relations'];

            $this->createRelations($attaquant, $relations['double_damage_to'], 2.0);
            $this->createRelations($attaquant, $relations['half_damage_to'], 0.5);
            $this->createRelations($attaquant, $relations['no_damage_to'], 0.0);
        }

        $this->command?->info(count(self::$types).' types importés.');
    }

    private function createRelations(Type $attaquant, array $cibles, float $efficacite): void
    {
        foreach ($cibles as $cible) {
            if (! isset(self::$types[$cible['name']])) {
                continue; // type non géré
            }

            TypeRelation::create([
                'idTypeAttaquant' => $attaquant->id,
                'idTypeDefenseur' => self::$types[$cible['name']]->id,
                'efficacite' => $efficacite,
            ]);
        }
    }
}
