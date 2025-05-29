<?php
namespace App\DataFixtures;

use App\Entity\Type;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class TypeFixtures extends Fixture
{
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client) { $this->client = $client; }

    public function load(ObjectManager $manager): void
    {
        $response = $this->client->request('GET', 'https://pokeapi.co/api/v2/type');
        $data = $response->toArray();

        foreach ($data['results'] as $typeData) {
            $type = new Type();
            $type->setNom($typeData['name']);
            $manager->persist($type);

            $this->addReference('type_' . $typeData['name'], $type);
        }
        $manager->flush();
    }
}
