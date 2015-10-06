<?php


namespace Sdz\UserBundle\DataFixtures\ORM ;




use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Sdz\UserBundle\Entity\User;

class Users implements  FixtureInterface
{
    public function load(ObjectManager $manager){
        $noms =array(
            'winzoo',
            'khalid',
            'fatima',
            'sawsane',
        );

        foreach($noms as $nom){
            $user = new User();
            $user->setUsername($nom);
            $user->setSalt('');
            $user->setRoles(array());

            //on le persiste
            $manager->persist($user) ;

        }

        // on déclenche l'enregistrement
        $manager->flush();

    }

}