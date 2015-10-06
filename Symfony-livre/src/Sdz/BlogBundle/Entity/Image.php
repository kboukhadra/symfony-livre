<?php

namespace Sdz\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

//annotation de validation
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Image
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Sdz\BlogBundle\Entity\ImageRepository")
 * @ORM\HaslifecycleCallbacks
 *
 */
class Image
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    private $tempFileNom;//on y stocke les nom des fichiers temporaire

/*
 * file est le fichier rien à avoir avec $url et $alt au début
 */
    private $file;

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }



    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255,nullable=true)
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="alt", type="string", length=255,nullable=true)
     */
    private $alt;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return Image
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set alt
     *
     * @param string $alt
     * @return Image
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;

        return $this;
    }

    /**
     * Get alt
     *
     * @return string 
     */
    public function getAlt()
    {
        return $this->alt;
    }


    /**
     * @param mixed $file
     */
    public function setFile(UploadedFile $file)
    {
        $this->file = $file;//$file en argument c'est le fichier(lion.jpg) qui va écraser l'ancien
        /* le fichier file
         -test: false
         -originalName: "lion.jpg"
         -mimeType: "image/jpeg"
         -size: 550315
         -error: 0
         *
         */

        // on verifie qu'on avait une fichier pour cette entité donc n'est null
        if(null !== $this->url){
             // quand mon fait la modification d'un fichier file on rentre la dedans
            //et tempfileNom se remplie de l'ancienne photo et on vide url et alt
            $this->tempFileNom = $this->url ;// on stocke url de la photo dans tempfile
            // on réinitialise   la valeur des attributs à null

            $this->url =null;
            $this->alt =null;
        }


    }
/////////////////////////////avant le persist////////////////////////////////////////
    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload(){

        // s'il n'y pas de fichier champ facultatif je sors
        if(null === $this->file){
            return;
        }
        // s'il ya une fichier  qui sera uploadé on charge url et alt file->guessExtension() et
        // file->getClientOriginalName()
        //le nom des fichier et son Id
        $this->url = $this->file->guessExtension();// affiche png
        $this->alt = $this->file->getClientOriginalName();//affiche italie.png


    }


/////////////////////////////apres le persist////////////////////////////////////////

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload(){
        // s'il n'y pas de fichier champ facultatif
        if(null === $this->file){
            return;
        }

      //$this est le reflet de l'image

        // si on avait un ancien fichier ,on le supprime
        if( null !== $this->tempFileNom){

            $oldFile = $this->getUploadRootDir().'/'.$this->id.'.'.$this->tempFileNom;

            if(file_exists($oldFile)){

               unlink($oldFile);//unlink efface le fichier
            }
        }

        // on déplace le fichier dans un répertoire de notre choix en conservant l'id avec la nouvelle extension
        $this->file->move(
            $this->getUploadRootDir(), // le repertoire de destination en mattant comme nom
            $this->id.'.'.$this->url //id.extension

        );




    }

//////////////////////////// avant la supression/////////////////////////////////////////////////////

    /**
     * @ORM\PreRemove()
     */
    public function preRemoveUpload(){
        //on sauvegarde temporairement le nom du fichier car il dépend de l'id
        $this->tempFileNom = $this->getUploadRootDir().'/'.$this->id.'.'.$this->url ;

    }


    //////////////////////////////////apres la supression///////////////////////////

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload(){

        //on n'a pas acces à l'id vus qu'on suprimer le fichier mais on utlise notre sauvegarde
        if(file_exists($this->tempFileNom)){
            //on supprime le fichier
            unlink($this->tempFileNom) ;
        }
    }

    public function getUploadDir(){
        //retourne le chemin relatif à l'image pour notre navigateur
        return 'uploads/img' ;
    }
////////////////////////////////////// recuperer les url pour la vue
    public function getUrlPath(){
        return $this->getUploadDir().'/'. $this->id.'.'.$this->url;
    }


    protected function getUploadRootDir(){

        //on retourne le chemin relatif vers une image pour notre code php
        // ici _DIR__ represente le fichier courant ici notre entité

        return __DIR__.'/../../../../web/'.$this->getUploadDir();//on monte de 4 web/uploads/img
        // étage

    }










}
