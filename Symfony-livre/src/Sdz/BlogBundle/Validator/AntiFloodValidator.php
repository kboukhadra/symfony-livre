<?php

namespace Sdz\BlogBundle\Validator ;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator ;

class AntiFloodValidator extends ConstraintValidator {

    private $request;
    private $em;
    // les argument déclaré dans le service arrive dans le constructeur on dois les enregistrer dans l'objet pour
    // s"enservir
    public function __construct(Request $request, EntityManager $em){

        $this->$request = $request;
        $this->$em = $em;
    }


    //alors c est logiquement  le contenu de l'attribut $contenu qui sera injecté en tant que argument $value
    //par ijection

    public function validate($value , Constraint $constraint){

            // on récupere l'ip de celui qui poqte le message
            $ip = $this->request->server->get("REMOTE_ADDR");
        // on vérifie que cette ip  a deja posté un message il y a moins de 15 secondes
        $isFlood = $this->em->getRepository("SdzBlogBundle:Commentaire")->isFlood($ip, 15);

        if(strlen($value) < 10){
            //cette ligne déclenche l'erreur et on appelle le message
            $this->context->addViolation($constraint->message) ;
        }
    }



}