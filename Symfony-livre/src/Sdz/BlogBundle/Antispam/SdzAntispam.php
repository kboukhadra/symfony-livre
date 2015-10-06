<?php

namespace Sdz\BlogBundle\Antispam;
    class SdzAntispam
    {

        protected  $mailer;
        protected  $locale;
        protected  $nbForSpam;

        /**
         * SdzAntispam constructor.
         * @param $mailer
         * @param $locale
         * @param $nbForSpam
         */
        public function __construct(\Swift_Mailer $mailer, $locale, $nbForSpam)
        {
            $this->mailer = $mailer;
            $this->locale = $locale;
            $this->nbForSpam = (int) $nbForSpam;
        }


        /*
         * on va vérifier que le texte est considéré comme spam à partir
         * 3 liens ou adresse email dans son contenu
         */

        public function isSpam($text){
            return($this->countLinks($text) + $this->countMails($text))> $this->nbForSpam;
        }

        /*
         * On compte les Url les lien dans le texte
         */

        public function countLinks($text){
            $motif = "((http:\/\/|https:\/\/)?(www.)?(([a-zA-Z0-9-]){2,}\.){1,4}([a-zA-Z]){2,6}(\/([a-zA-Z-_\/\.0-9#:?=&;,]*)?)?)" ;

            preg_match_all($motif,$text,$out);
            return count($out[0]);

        }

        public function countMails($text){
            $motif = '#[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}#i' ;

            preg_match_all($motif,$text,$out);
            return count($out[0]);

        }



}