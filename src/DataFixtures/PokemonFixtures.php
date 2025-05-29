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

    public function load(ObjectManager $manager): void
    {

        for ($i = 1; $i <= 151; $i++) {
            // Récupérer les données du Pokémon
            $response = $this->client->request('GET', "https://pokeapi.co/api/v2/pokemon/$i");
            $data = $response->toArray();

            // Récupérer le nom japonais depuis l'endpoint pokemon-species
            $speciesResponse = $this->client->request('GET', "https://pokeapi.co/api/v2/pokemon-species/$i");
            $speciesData = $speciesResponse->toArray();
            $nomJaponais = null;
            foreach ($speciesData['names'] as $name) {
                if ($name['language']['name'] === 'ja') {
                    $nomJaponais = $name['name'];
                    break;
                }
            }

            $pokemon = new Pokemon();
            $pokemon->setNom($data['name']);
            $pokemon->setNumeroPokedex($data['id']);
            $pokemon->setGeneration(1);

            // Définir le nom japonais
            $pokemon->setNomJaponais($nomJaponais ?? $data['name']);

            $type1 = $this->getReference('type_' . $data['types'][0]['type']['name'], Type::class);
            $pokemon->setType1($type1);

            if (isset($data['types'][1])) {
                $type2 = $this->getReference('type_' . $data['types'][1]['type']['name'], Type::class);
                $pokemon->setType2($type2);
            }

            $pokemon->setImagePrincipale($data['sprites']['front_default'] ?? null);
            $pokemon->setDescription('');
            $pokemon->setMegaEvolutionPossible(false);
            $pokemon->setDynamaxPossible(false);

            // Statistiques simples
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
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            TypeFixtures::class,
        ];
    }
}