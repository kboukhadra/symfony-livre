<?php

namespace Sdz\BlogBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
//annotation de validation
use Symfony\Component\Validator\Constraints as Assert;

//on rajoute ce use pour le contexte
use Symfony\Component\Validator\ExecutionContextInterface ;

//on ajoute la contrainte
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

use Sdz\BlogBundle\Validator\AntiFlood ;


/**
 * Article
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Sdz\BlogBundle\Entity\ArticleRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @Assert\Callback(methods={"contenuValide"})
 *
 * @UniqueEntity(fields="titre",message="Cet article existe deja avec ce titre")
 */
class Article
{

    /**
     *
     */
    public function __construct()
    {
        // par défaut la date de l'article est aujourd'hui
        $this->date = new \DateTime();
        $this->dateEdition = new \DateTime();
        //$this->publication = true;
        $this->categories = new ArrayCollection();
        $this->commentaires = new ArrayCollection();

    }

    /**
     *
     * @Gedmo\Slug(fields ={"titre"})
     * @ORM\Column(length=128, unique=true)
     */

    private $slug;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_edition", type="datetime")
     */
    private $dateEdition;


    /**
     * @ORM\OneToMany(targetEntity="Sdz\BlogBundle\Entity\Commentaire", mappedBy="article" )
     */
    private $commentaires;

    /**
     * @ORM\OneToOne(targetEntity="Sdz\BlogBundle\Entity\Image", cascade={"persist","remove"})
     * @ORM\JoinColumn(nullable=true)
     * @Assert\Valid()
     *
     */
    private $image;

    /**
     * @ORM\ManyToMany(targetEntity="Sdz\BlogBundle\Entity\Categorie" ,cascade={"persist"})
     */
    private $categories;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="nbCommentaire", type="integer",nullable=true)
     *
     */
    private $nbCommentaire;


    /**
     * @ORM\Column(name="publication", type="boolean")
     *
     */
    private $publication;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     * @Assert\DateTime()
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=255,unique=true)
     * @Assert\Length(
     *      min = "5",
     *      max = "50",
     *      minMessage = "Votre nom doit faire au moins {{ limit }} caractères",
     *      maxMessage = "Votre nom ne peut pas être plus long que {{ limit }} caractères"
     * )
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="auteur", type="string", length=255)
     * @Assert\Length( min = "5",minMessage = "Votre nom de l'auteur doit faire au moins {{ limit }} caractères" )
     */
    private $auteur;

    /**
     * @var string
     * @ORM\Column(name="contenu", type="text")
     * @Assert\NotBlank()
     */
    private $contenu;


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
     * Set date
     *
     * @param \DateTime $date
     * @return Article
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set titre
     *
     * @param string $titre
     * @return Article
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set auteur
     *
     * @param string $auteur
     * @return Article
     */
    public function setAuteur($auteur)
    {
        $this->auteur = $auteur;

        return $this;
    }

    /**
     * Get auteur
     *
     * @return string
     */
    public function getAuteur()
    {
        return $this->auteur;
    }

    /**
     * Set contenu
     *
     * @param string $contenu
     * @return Article
     */
    public function setContenu($contenu)
    {
        $this->contenu = $contenu;

        return $this;
    }

    /**
     * Get contenu
     *
     * @return string
     */
    public function getContenu()
    {
        return $this->contenu;
    }

    /**
     * Set publication
     *
     * @param boolean $publication
     * @return Article
     */
    public function setPublication($publication)
    {
        $this->publication = $publication;

        return $this;
    }

    /**
     * Get publication
     *
     * @return boolean
     */
    public function getPublication()
    {
        return $this->publication;
    }


    /**
     * Set image
     *
     * @param \Sdz\BlogBundle\Entity\Image $image
     * @return Article
     */
    public function setImage(\Sdz\BlogBundle\Entity\Image $image = null)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return \Sdz\BlogBundle\Entity\Image
     */
    public function getImage()
    {
        return $this->image;
    }


    /**
     * Add categories
     *
     * @param \Sdz\BlogBundle\Entity\Categorie $categories
     * @return Article
     */
    public function addCategory(\Sdz\BlogBundle\Entity\Categorie $categories)
    {
        $this->categories[] = $categories;

        return $this;
    }

    /**
     * Remove categories
     *
     * @param \Sdz\BlogBundle\Entity\Categorie $categories
     */
    public function removeCategory(\Sdz\BlogBundle\Entity\Categorie $categories)
    {
        $this->categories->removeElement($categories);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Add commentaires
     *
     * @param \Sdz\BlogBundle\Entity\Commentaire $commentaires
     * @return Article
     */
    public function addCommentaire(\Sdz\BlogBundle\Entity\Commentaire $commentaires)
    {
        $this->commentaires[] = $commentaires;
        $commentaires->setArticle($this);


        return $this;
    }

    /**
     * Remove commentaires
     *
     * @param \Sdz\BlogBundle\Entity\Commentaire $commentaires
     */
    public function removeCommentaire(\Sdz\BlogBundle\Entity\Commentaire $commentaires)
    {
        $this->commentaires->removeElement($commentaires);


    }

    /**
     * Get commentaires
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCommentaires()
    {
        return $this->commentaires;
    }


    /**
     * Set dateEdition
     *
     * @param \DateTime $dateEdition
     * @return Article
     */
    public function setDateEdition($dateEdition)
    {
        $this->dateEdition = $dateEdition;

        return $this;
    }

    /**
     * Get dateEdition
     *
     * @return \DateTime
     */
    public function getDateEdition()
    {
        return $this->dateEdition;
    }

    /**
     * @ORM\PreRemove
     */
    public function updateDate()
    {
        //à la date d'aujoourd'hui
        $this->setDateEdition(new \DateTime());
    }


    /**
     * Set nbCommentaire
     *
     * @param integer $nbCommentaire
     * @return Article
     */
    public function setNbCommentaire($nbCommentaire)
    {
        $this->nbCommentaire = $nbCommentaire;

        return $this;
    }

    /**
     * Get nbCommentaire
     *
     * @return integer
     */
    public function getNbCommentaire()
    {
        return $this->nbCommentaire;
    }


    /**
     * Set slug
     *
     * @param string $slug
     * @return Article
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }


    public function contenuValide(ExecutionContextInterface $context){
        $mot_interdit =array('échec','abandon');

        //on vérifie que le contenu ne contient contient pas l'un de ses mots
        if(preg_match('#'.implode('|',$mot_interdit).'#',$this->getContenu())){
            //si la regle est violé on définit l'erreur et le message
            //1er argument quelle attribut l'arreur concerne
            //2eme arguments le message d'erreur

            $context ->addViolationAt('contenu' ,'contenu invalide car il contient un mot interdit',array(),null);

        }
    }
}
