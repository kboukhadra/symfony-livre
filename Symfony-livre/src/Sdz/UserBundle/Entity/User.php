<?php

namespace Sdz\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use FOS\UserBundle\Model\User  as BaseUser ;

/**
 * User
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Sdz\UserBundle\Entity\UserRepository")
 */
class User extends  BaseUser
{


    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;


}
