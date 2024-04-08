<?php

namespace App\DataFixtures;
use App\Factory\BirthdayFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
      BirthdayFactory::new()->createMany(50);
      $manager->flush();  
    }
}
