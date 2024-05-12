<?php

namespace App\DataFixtures;

use App\Entity\Avatar;
use App\Entity\Badge;
use App\Entity\Organization;
use App\Entity\Player;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $faker = Factory::create();

        // Création utilisateur
        $user = new User();
        $user->setEmail("demo@infinitequiz.fr");
        $user->setPassword(password_hash("azerty", PASSWORD_DEFAULT));

        $manager->persist($user);

        // Création des avatars
        $avatars = [];
        foreach (['Avatar1', 'Avatar2', 'Avatar3', 'Avatar4'] as $avatarName) {
            $avatar = new Avatar();
            $avatar->setName($avatarName);
            $manager->persist($avatar);
            $avatars[] = $avatar;
        }

        // Création des badges
        $badges = [];
        foreach (['Badge1', 'Badge2', 'Badge3', 'Badge4'] as $badgeName) {
            $badge = new Badge();
            $badge->setName($badgeName);
            $manager->persist($badge);
            $badges[] = $badge;
        }

        // Création des organisations
        $organizations = [];
        foreach (['Orga1', 'Orga2', 'Orga3', 'orga4'] as $organizationName) {
            $organization = new Organization();
            $organization->setName($organizationName);
            $manager->persist($organization);
            $organizations[] = $organization;
        }

        // Création des joueurs
        $players = [];
        foreach (['Player1', 'Player2', 'Player3', 'Player4'] as $playerName) {
            $player = new Player();
            $player->setPseudo($playerName);
            $player->setUser($faker->random_int(0, 1));
            $manager->persist($player);
            $players[] = $player;
        }



        $manager->flush();
    }
}
