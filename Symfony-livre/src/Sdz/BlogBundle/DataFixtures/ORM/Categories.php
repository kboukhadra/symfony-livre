<?php

namespace Sdz\BlogBundle\DataFixtures\ORM ;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Sdz\BlogBundle\Entity\Categorie;


class Categories implements FixtureInterface
{

    public function load(ObjectManager $manager){
        $noms =array(
            'Symfony',
            'Doctrine',
            'Tutoriel',
            'Evenement',
        );

        foreach($noms as $nom){
            $categorie = new Categorie();
            $categorie->setNom($nom);

            //on le persiste
            $manager->persist($categorie) ;

        }

        // on déclenche l'enregistrement
        $manager->flush();

    }
}