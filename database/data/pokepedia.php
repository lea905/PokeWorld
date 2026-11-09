<?php

/**
 * Données des dresseurs vérifiées sur Poképédia (https://www.pokepedia.fr), le 09/11/2026.
 *
 * - « equipes » : combats de l'histoire des jeux principaux (jeux d'origine, remakes et
 *   Team Rainbow Rocket). Les installations de combat (Tour de Combat, Maison de Combat,
 *   Tournoi des Champions…), Pokémon Stadium, GO et Masters ne sont pas repris.
 * - Les équipes identiques d'une version à l'autre sont regroupées en une seule.
 * - Pokémon : [nom français, niveau]. Pour Sacha (dessin animé), numéros du Pokédex national
 *   des Pokémon utilisés pendant chaque Ligue, sans niveau.
 * - Les descriptions sont des résumés rédigés à partir des articles cités.
 */
$rougeBleu = 'Pokémon Rouge et Bleu';
$jaune = 'Pokémon Jaune';
$rfvf = 'Pokémon Rouge Feu et Vert Feuille';
$letsGo = "Pokémon : Let's Go, Pikachu et Let's Go, Évoli";
$oac = 'Pokémon Or, Argent et Cristal';
$hgss = 'Pokémon Or HeartGold et Argent SoulSilver';
$usul = 'Pokémon Ultra-Soleil et Ultra-Lune';

return [
    'Giovanni' => [
        'prenoms' => ['Giovanni'],
        'source' => 'https://www.pokepedia.fr/Giovanni',
        'infos' => [
            'ville_natale' => 'Jadielle',
            'region' => 'Kanto',
            'description' => "Chef de la Team Rocket et Champion de l'Arène de Jadielle, spécialiste du type Sol. "
                ."Il dirige ses Sbires depuis le repaire caché de Céladopole et ne s'intéresse aux Pokémon que pour la puissance qu'ils lui apportent, "
                .'tout en sachant se montrer digne dans la défaite.',
            'ambition' => 'Conquérir le monde grâce à la Team Rocket.',
        ],
        'equipes' => [
            ['Repaire Rocket — Pokémon Rouge, Bleu, Rouge Feu et Vert Feuille', [['Onix', 25], ['Rhinocorne', 24], ['Kangourex', 29]]],
            ["Repaire Rocket — {$jaune}", [['Onix', 25], ['Rhinocorne', 24], ['Persian', 29]]],
            ["Repaire Rocket — {$letsGo}", [['Persian', 35], ['Rhinocorne', 35]]],
            ['Sylphe SARL, Safrania — Pokémon Rouge, Bleu, Rouge Feu et Vert Feuille', [['Nidorino', 37], ['Kangourex', 35], ['Rhinocorne', 37], ['Nidoqueen', 41]]],
            ["Sylphe SARL, Safrania — {$jaune}", [['Nidorino', 37], ['Persian', 35], ['Rhinocorne', 37], ['Nidoqueen', 41]]],
            ["Sylphe SARL, Safrania — {$letsGo}", [['Persian', 39], ['Rhinocorne', 39], ['Nidoqueen', 39]]],
            ["Arène de Jadielle — {$rougeBleu}", [['Rhinocorne', 45], ['Nidoking', 45], ['Triopikeur', 42], ['Rhinoféros', 50], ['Nidoqueen', 44]]],
            ["Arène de Jadielle — {$jaune}", [['Triopikeur', 50], ['Nidoking', 55], ['Persian', 53], ['Rhinoféros', 55], ['Nidoqueen', 53]]],
            ["Arène de Jadielle — {$rfvf}", [['Rhinocorne', 45], ['Nidoking', 45], ['Triopikeur', 42], ['Rhinocorne', 50], ['Nidoqueen', 44]]],
            ["Arène de Jadielle — {$letsGo}", [['Triopikeur', 49], ['Nidoking', 49], ['Nidoqueen', 49], ['Rhinoféros', 50]]],
            ["Événement Celebi — {$hgss}", [['Nidoking', 42], ['Kangourex', 40], ['Corboss', 43], ['Nidoqueen', 46]]],
            ["Château Rocket — {$usul}", [['Triopikeur', 68], ['Rhinastoc', 68], ['Nidoqueen', 68], ['Mewtwo', 70], ['Nidoking', 68]]],
        ],
    ],

    'Amos' => [
        'prenoms' => ['Amos'],
        'source' => 'https://www.pokepedia.fr/Amos',
        'infos' => [
            'ville_natale' => 'Inconnue',
            'region' => 'Johto',
            'description' => "Commandant de la Team Rocket et l'un des instigateurs de sa résurrection : "
                .'il dirige le quatuor qui mène l’organisation en attendant le retour de Giovanni, '
                .'jusqu’à l’invasion de la Tour Radio de Doublonville.',
            'ambition' => 'Faire revenir Giovanni à la tête d’une Team Rocket reconstituée.',
        ],
        'equipes' => [
            ["Tour Radio de Doublonville — {$oac}", [['Malosse', 33], ['Smogo', 33], ['Démolosse', 35]]],
            ["Entrepôt Rocket — {$rfvf} (identité de l'Admin supposée)", [['Nosferalto', 53], ['Smogogo', 54], ['Démolosse', 55]]],
            ["Tour Radio de Doublonville — {$hgss}", [['Malosse', 35], ['Smogo', 35], ['Démolosse', 38]]],
            ["Repaire Rocket — {$letsGo}", [['Smogogo', 33], ['Nosferalto', 33]]],
            ["Sylphe SARL, Safrania — {$letsGo}", [['Électrode', 37], ['Grotadmorv', 34], ['Nosferalto', 37], ['Rattatac', 34], ['Smogogo', 37]]],
            ["Repaire Rocket, revanche quotidienne — {$letsGo}", [['Électrode', 54], ['Magmar', 54], ['Nosferalto', 54], ['Smogogo', 54]]],
        ],
    ],

    'Sbire' => [
        'prenoms' => ['Sbire H', 'Sbire'],
        'source' => 'https://www.pokepedia.fr/Sbire_de_la_Team_Rocket',
        'infos' => [
            'description' => 'Membre de base de la Team Rocket, chargé de rafler un maximum de Pokémon pour Giovanni. '
                .'Les Sbires utilisent principalement des Pokémon de type Poison.',
            'ambition' => 'Servir Giovanni et voler des Pokémon.',
        ],
        // Les Sbires n'ont pas d'équipe fixe : Pokémon qu'ils utilisent dans toutes les versions de Kanto
        'equipes' => [
            ['Pokémon courants des Sbires de la Team Rocket', [['Rattata', null], ['Abo', null], ['Smogo', null]]],
        ],
    ],

    'Arthur' => [
        'prenoms' => ['Arthur'],
        'source' => 'https://www.pokepedia.fr/Arthur_(Team_Aqua)',
        'infos' => [
            'ville_natale' => 'Inconnue',
            'region' => 'Hoenn',
            'description' => 'Leader de la Team Aqua. Dans Pokémon Saphir, Émeraude et Saphir Alpha, il cherche à réveiller Kyogre '
                .'au risque de mettre toute la région en danger ; dans Rubis et Rubis Oméga, il devient l’allié du joueur contre la Team Magma.',
            'ambition' => 'Étendre les mers et les océans en réveillant Kyogre.',
        ],
        'equipes' => [
            ['Mont Chimnée — Pokémon Saphir', [['Grahyèna', 24], ['Nosferalto', 24], ['Sharpedo', 25]]],
            ['Caverne Fondmer — Pokémon Saphir et Émeraude', [['Grahyèna', 41], ['Nostenfer', 41], ['Sharpedo', 43]]],
            ['Mont Chimnée — Pokémon Saphir Alpha', [['Grahyèna', 25], ['Nosferalto', 25], ['Sharpedo', 27]]],
            ['Caverne Fondmer — Pokémon Saphir Alpha', [['Grahyèna', 41], ['Grotadmorv', 41], ['Nostenfer', 41], ['Sharpedo', 43]]],
            ["Château Rocket — {$usul}", [['Grahyèna', 64], ['Sharpedo', 64], ['Nostenfer', 64], ['Kyogre', 66], ['Grotadmorv', 64]]],
        ],
    ],

    'Max' => [
        'prenoms' => ['Max'],
        'source' => 'https://www.pokepedia.fr/Max_(Team_Magma)',
        'infos' => [
            'ville_natale' => 'Inconnue',
            'region' => 'Hoenn',
            'description' => 'Leader de la Team Magma. Dans Pokémon Rubis, Émeraude et Rubis Oméga, il cherche à réveiller Groudon '
                .'au risque de mettre toute la région en danger ; dans Saphir et Saphir Alpha, il devient l’allié du joueur contre la Team Aqua.',
            'ambition' => 'Étendre les terres émergées en réveillant Groudon.',
        ],
        'equipes' => [
            ['Mont Chimnée — Pokémon Rubis', [['Grahyèna', 24], ['Nosferalto', 24], ['Camérupt', 25]]],
            ['Caverne Fondmer — Pokémon Rubis', [['Grahyèna', 41], ['Nostenfer', 41], ['Camérupt', 43]]],
            ['Mont Chimnée — Pokémon Émeraude', [['Grahyèna', 24], ['Nosferapti', 24], ['Camérupt', 25]]],
            ['Planque Magma — Pokémon Émeraude', [['Grahyèna', 37], ['Nostenfer', 38], ['Camérupt', 39]]],
            ['Centre Spatial, combat en duo — Pokémon Émeraude', [['Grahyèna', 42], ['Nostenfer', 43], ['Camérupt', 44]]],
            ['Mont Chimnée — Pokémon Rubis Oméga', [['Grahyèna', 25], ['Nosferalto', 25], ['Camérupt', 27]]],
            ['Caverne Fondmer — Pokémon Rubis Oméga', [['Grahyèna', 41], ['Nostenfer', 41], ['Smogogo', 41], ['Camérupt', 43]]],
            ["Château Rocket — {$usul}", [['Grahyèna', 64], ['Camérupt', 64], ['Nostenfer', 64], ['Groudon', 66], ['Smogogo', 64]]],
        ],
    ],

    'Hélio' => [
        'prenoms' => ['Hélio'],
        'source' => 'https://www.pokepedia.fr/Hélio',
        'infos' => [
            'ville_natale' => 'Rivamar',
            'region' => 'Sinnoh',
            'description' => 'Leader de la Team Galaxie, aussi intelligent que froid, persuadé que l’esprit et les émotions sont un fléau. '
                .'Excellent orateur, il manipule ses subordonnés pour servir son projet de nouveau monde.',
            'ambition' => 'Créer un nouveau monde débarrassé de l’esprit et des émotions.',
        ],
        'equipes' => [
            ['Célestia — Pokémon Platine', [['Farfuret', 34], ['Nosferalto', 34], ['Cornèbre', 36]]],
            ['Centre Galaxie de Voilaroc — Pokémon Diamant, Perle, Diamant Étincelant et Perle Scintillante', [['Cornèbre', 40], ['Nosferalto', 40], ['Farfuret', 43]]],
            ['Centre Galaxie de Voilaroc — Pokémon Platine', [['Farfuret', 44], ['Nostenfer', 44], ['Corboss', 46]]],
            ['Colonnes Lances — Pokémon Diamant, Perle, Diamant Étincelant et Perle Scintillante', [['Corboss', 45], ['Léviator', 45], ['Nostenfer', 46], ['Dimoret', 48]]],
            ['Monde Distorsion — Pokémon Platine', [['Démolosse', 45], ['Léviator', 46], ['Nostenfer', 46], ['Dimoret', 48], ['Corboss', 47]]],
            ['Château Rocket — Pokémon Ultra-Soleil', [['Démolosse', 65], ['Dimoret', 65], ['Corboss', 65], ['Dialga', 67], ['Nostenfer', 65]]],
            ['Château Rocket — Pokémon Ultra-Lune', [['Démolosse', 65], ['Dimoret', 65], ['Corboss', 65], ['Palkia', 67], ['Nostenfer', 65]]],
        ],
    ],

    'Pierre' => [
        'prenoms' => ['Pierre'],
        'source' => 'https://www.pokepedia.fr/Pierre_(Kanto)',
        'infos' => [
            'ville_natale' => 'Argenta',
            'region' => 'Kanto',
            'description' => "Champion de l'Arène d'Argenta, spécialiste du type Roche. Sérieux et fier de sa volonté, il remet le Badge Roche, "
                .'premier des huit Badges de Kanto. Son passe-temps favori : déterrer des fossiles au Mont Sélénite.',
        ],
        'equipes' => [
            ["Arène d'Argenta — Pokémon Rouge, Bleu, Rouge Feu et Vert Feuille", [['Racaillou', 12], ['Onix', 14]]],
            ["Arène d'Argenta — {$jaune}", [['Racaillou', 10], ['Onix', 12]]],
            ["Arène d'Argenta — {$oac}", [['Gravalanch', 41], ['Kabutops', 42], ['Rhinocorne', 41], ['Onix', 44], ['Amonistar', 42]]],
            ["Arène d'Argenta — {$hgss}", [['Gravalanch', 51], ['Kabutops', 52], ['Rhinocorne', 51], ['Onix', 54], ['Amonistar', 53]]],
            ["Revanche — {$hgss}", [['Grolem', 55], ['Kabutops', 55], ['Relicanth', 54], ['Charkos', 57], ['Amonistar', 56], ['Onix', 61]]],
            ["Arène d'Argenta — {$letsGo}", [['Racaillou', 11], ['Onix', 12]]],
            ["Revanche quotidienne — {$letsGo}", [['Onix', 56], ['Ptéra', 56], ['Kabutops', 56], ['Grolem', 57], ['Amonistar', 56]]],
        ],
    ],

    'Ondine' => [
        'prenoms' => ['Ondine'],
        'source' => 'https://www.pokepedia.fr/Ondine',
        'infos' => [
            'ville_natale' => 'Azuria',
            'region' => 'Kanto',
            'description' => "Championne de l'Arène d'Azuria, spécialiste du type Eau. Elle remet le Badge Cascade ; "
                .'son Pokémon de prédilection est Staross.',
        ],
        'equipes' => [
            ["Arène d'Azuria — Pokémon Rouge, Bleu, Jaune, Rouge Feu et Vert Feuille", [['Stari', 18], ['Staross', 21]]],
            ["Arène d'Azuria — {$oac}", [['Akwakwak', 42], ['Staross', 47], ['Maraiste', 42], ['Lokhlass', 44]]],
            ["Arène d'Azuria — {$hgss}", [['Akwakwak', 49], ['Staross', 54], ['Maraiste', 49], ['Lokhlass', 52]]],
            ["Revanche — {$hgss}", [['Staross', 60], ['Lanturn', 54], ['Maraiste', 56], ['Mustéflott', 54], ['Lokhlass', 56], ['Milobellus', 60]]],
            ["Arène d'Azuria — {$letsGo}", [['Psykokwak', 18], ['Staross', 19]]],
            ["Revanche quotidienne — {$letsGo}", [['Akwakwak', 56], ['Aquali', 56], ['Staross', 56], ['Léviator', 57], ['Lamantine', 56]]],
        ],
    ],

    'Sacha' => [
        'prenoms' => ['Sacha'],
        'source' => 'https://www.pokepedia.fr/Sacha_Ketchum',
        'infos' => [
            'ville_natale' => 'Bourg Palette',
            'region' => 'Kanto',
            'description' => 'Héros du dessin animé Pokémon, la série, toujours accompagné de son Pikachu. Persévérant, généreux et doté '
                .'d’un fort sens de la justice, il est devenu Maître de la Ligue d’Alola puis Monarque du Tournoi du Couronnement Mondial.',
            'ambition' => 'Devenir le plus grand Maître Pokémon au monde.',
        ],
        // Dessin animé : Pokémon utilisés pendant chaque Ligue (numéros du Pokédex national)
        'equipes' => [
            ['Ligue Indigo, Kanto (dessin animé)', [98, 99, 7, 25, 1, 89, 6]],
            ['Ligue Orange, Archipel Orange (dessin animé)', [25, 1, 6, 7, 128, 131]],
            ['Conférence Argentée, Johto (dessin animé)', [25, 7, 158, 231, 1, 155, 6, 89, 128, 143, 214, 153, 164]],
            ["Conférence d'Éternara, Hoenn (dessin animé)", [25, 341, 324, 253, 362, 277]],
            ["Conférence de l'Île du Lys dans la Vallée, Sinnoh (dessin animé)", [214, 156, 398, 143, 164, 232, 443, 25, 389, 392, 418, 472, 277, 254, 324]],
            ['Conférence de Verteresse, Unys (dessin animé)', [25, 559, 536, 542, 553, 521, 501, 499, 495, 525]],
            ["Conférence d'Illumis, Kalos (dessin animé)", [658, 701, 25, 663, 706, 715]],
            ['Conférence de Manalo, Alola (dessin animé)', [25, 808, 722, 726, 745, 809, 727, 804]],
        ],
    ],
];
