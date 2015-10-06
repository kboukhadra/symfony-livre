<?php

namespace Sdz\BlogBundle\Entity;


use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * ArticleRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ArticleRepository extends EntityRepository
{

    public function getArticles($nombreParPage,$page)
    {


        if($page < 1){
            throw new \InvalidArgumentException('L\'argument $page ne peut etre inférieur à 1 valeur: "'.$page.'"');
        }


        $query = $this->createQueryBuilder('a')//equivaut à SELECT article FROM SdzBlogBundle:Article
            //on joint sur l'attribut image2
            ->leftJoin('a.image', 'i')//LEFT JOINT table1 qui a.image avec alias i
            ->addSelect('i') //puis  on sélectionne également l'entité jointe, via un addSelect()
            // on joint sur l'attribut categorie
            ->leftJoin('a.categories', 'c')
            ->addSelect('c')
            ->orderBy('a.date', 'DESC')
            ->getQuery();


        // on définit l'article à partir duquel commencer la liste
         $query->setFirstResult(($page-1)*$nombreParPage)

             //ainsi que le nombre d'article à afficher
               ->setMaxResults($nombreParPage);

             return new Paginator($query) ;

    }


    public function getSelectList(){
        $qb = $this->createQueryBuilder('a')
            ->where('a.publication = 0'); // on filtre sur l'attribut publication
        return $qb ;
    }


    public function quatreDernier(){

        $liste=$this->findBy(null, array('date' => 'desc'), 4, 0);

        return $liste ;

    }






}