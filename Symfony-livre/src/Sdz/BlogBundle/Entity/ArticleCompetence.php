<?php

namespace Sdz\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

//annotation de validation
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 */
class ArticleCompetence
{
    /**
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Article")
     */
    private $article;

    /**
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Competence")
     */
    private $competence;

    /**
     *
     * @ORM\Column()
     */
    private $niveau;




    /**
     * Set niveau
     *
     * @param string $niveau
     * @return ArticleCompetence
     */
    public function setNiveau($niveau)
    {
        $this->niveau = $niveau;

        return $this;
    }

    /**
     * Get niveau
     *
     * @return string 
     */
    public function getNiveau()
    {
        return $this->niveau;
    }

    /**
     * Set article
     *
     * @param \Sdz\BlogBundle\Entity\Article $article
     * @return ArticleCompetence
     */
    public function setArticle(\Sdz\BlogBundle\Entity\Article $article)
    {
        $this->article = $article;

        return $this;
    }

    /**
     * Get article
     *
     * @return \Sdz\BlogBundle\Entity\Article 
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * Set competence
     *
     * @param \Sdz\BlogBundle\Entity\Competence $competence
     * @return ArticleCompetence
     */
    public function setCompetence(\Sdz\BlogBundle\Entity\Competence $competence)
    {
        $this->competence = $competence;

        return $this;
    }

    /**
     * Get competence
     *
     * @return \Sdz\BlogBundle\Entity\Competence 
     */
    public function getCompetence()
    {
        return $this->competence;
    }
}
