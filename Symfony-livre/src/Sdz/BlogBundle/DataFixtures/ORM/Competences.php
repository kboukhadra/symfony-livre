<?php

namespace Sdz\BlogBundle\DataFixtures\ORM ;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Sdz\BlogBundle\Entity\Competence;


class Competences implements FixtureInterface
{

    public function load(ObjectManager $manager){
        $noms =array(
            'Doctrine',
            'Formulaire',
            'Twig',
            'Jquery',
        );

        foreach($noms as $nom){
            $competence = new Competence();
            $competence->setNom($nom);

            //on le persiste
            $manager->persist($competence) ;

        }

        // on déclenche l'enregistrement
        $manager->flush();

    }
}