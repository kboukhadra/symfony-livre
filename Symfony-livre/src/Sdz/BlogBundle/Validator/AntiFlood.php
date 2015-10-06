<?php
namespace Sdz\BlogBundle\Validator ;

use Symfony\Component\Validator\Constraint ;

/**
 *      on met @Annotation pour que cette contrainte spoit dispo dans les autres classes
 * @Annotation
 */
class AntiFlood extends Constraint
{
    //une contrainte Xxxx demande à se faire valider par le valiodateur XxxxValidator
    // donc son validateur est AntiFloodValidator

    public $message =" Vous avez deja posté un message il y a moins de 15 min.Merci d'attendre un peu";


    //on modifie la contrainte pour qu'elle demande de se faire valider par notre service d'alias sdzblog_antiflood
    //et non pas simplement par AntifloodValidator il suffit de rajouter un méthode validateBy

    public function validateBy(){
        return 'sdzblog_antiflood';//ici on fait appel à l'alias du serveice
    }
}