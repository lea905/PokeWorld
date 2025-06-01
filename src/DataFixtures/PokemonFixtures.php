<?php
namespace App\DataFixtures;

use App\Entity\Pokemon;
use App\Entity\Type;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

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

    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 1025; $i++) {
            try {
                // Récupérer les données du Pokémon
                $response = $this->client->request('GET', "https://pokeapi.co/api/v2/pokemon/$i");
                $data = $response->toArray();

                // Récupérer les données species
                $speciesResponse = $this->client->request('GET', "https://pokeapi.co/api/v2/pokemon-species/$i");
                $speciesData = $speciesResponse->toArray();

                // Nom japonais
                $nomJaponais = null;
                foreach ($speciesData['names'] as $name) {
                    if ($name['language']['name'] === 'ja') {
                        $nomJaponais = $name['name'];
                        break;
                    }
                }

                // Description en français
                $description = '';
                foreach ($speciesData['flavor_text_entries'] as $entry) {
                    if ($entry['language']['name'] === 'fr') {
                        $description = str_replace(["\n", "\f"], ' ', $entry['flavor_text']);
                        break;
                    }
                }

                // Nom français
                $nomFrancais = $data['name'];
                foreach ($speciesData['names'] as $nameEntry) {
                    if ($nameEntry['language']['name'] === 'fr') {
                        $nomFrancais = $nameEntry['name'];
                        break;
                    }
                }

                // Génération
                $generationName = $speciesData['generation']['name'] ?? 'generation-i';
                $romanNumber = str_replace('generation-', '', $generationName);
                $generation = $this->romanToDecimal($romanNumber);

                $pokemon = new Pokemon();
                $pokemon->setNom($nomFrancais);
                $pokemon->setNumeroPokedex($data['id']);
                $pokemon->setGeneration($generation);
                $pokemon->setNomJaponais($nomJaponais ?? $data['name']);
                $pokemon->setDescription($description);
                $pokemon->setMegaEvolutionPossible(false);
                $pokemon->setDynamaxPossible(false);

                // Types
                $type1 = $this->getReference('type_' . $data['types'][0]['type']['name'], Type::class);
                $pokemon->setType1($type1);

                if (isset($data['types'][1])) {
                    $type2 = $this->getReference('type_' . $data['types'][1]['type']['name'], Type::class);
                    $pokemon->setType2($type2);
                }

                // Image
                $pokemon->setImagePrincipale($data['sprites']['front_default'] ?? null);

                // Statistiques
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

                // Flush et clear par lots (tous les 50 Pokémon)
                if ($i % 50 === 0) {
                    $manager->flush();
                    $manager->clear(); // Libère la mémoire
                }
            } catch (\Exception $e) {
                // Loguer l'erreur pour ne pas arrêter l'exécution
                error_log("Erreur pour Pokémon $i : " . $e->getMessage());
                continue;
            }
        }

        // Flush final pour les Pokémon restants
        $manager->flush();
        $manager->clear();
    }

    public function getDependencies(): array
    {
        return [
            TypeFixtures::class,
        ];
    }
}