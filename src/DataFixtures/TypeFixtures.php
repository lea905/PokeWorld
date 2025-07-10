<?php
namespace App\DataFixtures;

use App\Entity\Type;
use App\Entity\TypeRelation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class TypeFixtures extends Fixture
{
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function load(ObjectManager $manager): void
    {
        // 1. Récupérer tous les types
        $response = $this->client->request('GET', 'https://pokeapi.co/api/v2/type');
        $data = $response->toArray();

        $types = [];

        // 2. Créer tous les types et les stocker par nom
        foreach ($data['results'] as $typeData) {
            $detailResponse = $this->client->request('GET', $typeData['url']);
            $detail = $detailResponse->toArray();

            // Nom français
            $nomFrancais = $typeData['name'];
            foreach ($detail['names'] as $nameEntry) {
                if ($nameEntry['language']['name'] === 'fr') {
                    $nomFrancais = $nameEntry['name'];
                    break;
                }
            }

            $type = new Type();
            $type->setNom($nomFrancais);
            $manager->persist($type);

            $types[$typeData['name']] = $type; // stocké par nom anglais
            $this->addReference('type_' . $typeData['name'], $type);
        }

        $manager->flush();

        // 3. Créer les relations d'attaque automatiquement
        foreach ($data['results'] as $typeData) {
            $typeName = $typeData['name'];
            $response = $this->client->request('GET', $typeData['url']);
            $detail = $response->toArray();

            $attaquant = $types[$typeName]; // Type attaquant

            $damageRelations = $detail['damage_relations'];

            $this->createRelations($manager, $attaquant, $damageRelations['double_damage_to'], $types, 2.0);
            $this->createRelations($manager, $attaquant, $damageRelations['half_damage_to'], $types, 0.5);
            $this->createRelations($manager, $attaquant, $damageRelations['no_damage_to'], $types, 0.0);
        }

        $manager->flush();
    }

    private function createRelations(ObjectManager $manager, Type $attaquant, array $cibles, array $types, float $efficacite): void
    {
        foreach ($cibles as $target) {
            $nomCible = $target['name'];
            if (!isset($types[$nomCible])) {
                continue; // type non géré
            }

            $relation = new TypeRelation();
            $relation->setTypeAttaquant($attaquant);
            $relation->setTypeDefenseur($types[$nomCible]);
            $relation->setEfficacite($efficacite);
            $manager->persist($relation);
        }
    }

}
