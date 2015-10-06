<?php

namespace Sdz\BlogBundle\Controller;

use Sdz\BlogBundle\Form\ArticleEditType;
use Sdz\BlogBundle\Form\ArticleType;
use Sdz\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sdz\BlogBundle\Entity\Article;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Response;

use JMS\SecurityExtraBundle\Annotation\Secure;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class BlogController extends Controller
{
    public function indexAction($page)
    {
// On ne sait pas combien de pages il y a
// Mais on sait qu'une page doit être supérieure ou égale à 1
// Bien sûr pour le moment on ne se sert pas (encore !) de cette variable
        if ($page < 1) {
// On déclenche une exception NotFoundHttpException
// Cela va afficher la page d'erreur 404
// On pourra la personnaliser plus tard
            throw $this->createNotFoundException('Page inexistante (page = ' . $page . ')');
        }


// Pour récupérer la liste de tous les articles : on utilise findAll()
        $articles = $this->getDoctrine()
            ->getManager()
            ->getRepository('SdzBlogBundle:Article')
            ->getArticles(3, $page);//3 articles par page

        //recupere le service
        $security = $this->container->get('security.context');
        //récupere la session de sécurité courante
        $token = $security->getToken();
        //récupere l'utlisateur courant
        $user = $token->getUser();
        $session = $this->getRequest()->getSession();
        $session->set('user', $user);


// L'appel de la vue ne change pas
        return $this->render('SdzBlogBundle:Blog:index.html.twig', array(
            'articles' => $articles,
            'page' => $page,
            'nombrePage' => ceil(count($articles) / 3),

        ));
    }

    public function voirAction(Article $article)
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->get('request');

        $liste_articleCompetence = $this->getDoctrine()
            ->getManager()
            ->getRepository('SdzBlogBundle:ArticleCompetence')
            ->findByArticle($article->getId());


        return $this->render('SdzBlogBundle:Blog:voir.html.twig', array(
            'article' => $article,

            'liste_articleCompetence' => $liste_articleCompetence,


        ));
    }

    /**
     * @Secure(roles="ROLE_AUTEUR")
     */

    public function ajouterAction()
    {
        $em = $this->getDoctrine()->getmanager();
        $repo = $em->getRepository('SdzBlogBundle:Article');
        $querybuiler = $repo->getSelectList();

        //on crée l'objet Article
        $article = new Article();

        $form = $this->createForm(new ArticleType(), $article);


        $request = $this->get('request');
        $handle = $form->handleRequest($request);
        // lorque on soumet le formulaire s'en rend handleRequest compte de la soumission et il va écrire les données dans
        //l'objet $article


        // on vérifie que les valeurs sont correct en faisant appel au validator
        if ($form->isValid()) {


            //on l'enregistre notre objet $article dans la base de donnée
            $em = $this->getDoctrine()->getmanager();
            $em->persist($article);
            $em->flush();
            $this->get('session')->getFlashBag()->add('info', 'Article bien enregistré');

            // on redirige vers l'affichage de notre objer crée
            return $this->redirect($this->generateUrl('sdzblog_voir', array(
                'slug' => $article->getSlug(),

            )));
        }
        // Si on n'est pas en POST, alors on affiche le formulaire
        // on passe la methode createView du formulaire à la vue afin qu'elle puisse  afficher le formulaire tout seul
        return $this->render('SdzBlogBundle:Blog:ajouter.html.twig',
            array('form' => $form->createView()

            ));
    }

    public function modifierAction(Article $article)
    {

// Ici, on s'occupera de la création et de la gestion du formulaire

        $form = $this->createForm(new ArticleEditType(), $article);


        $request = $this->get('request');
        $form->handleRequest($request);
        // lorque on soumet le formulaire s'en rend compte de la soumission et il va écrire les données dans
        //l'objet $article

        // on vérifie que les valeurs sont correct en faisant appel au validator
        if ($form->isValid()) {

            //on l'enregistre notre objet $article dans la base de donnée
            $em = $this->getDoctrine()->getmanager();
            $em->persist($article);
            $em->flush();

            // on définit un message de modification
            $this->get('session')->getFlashBag()->add('info', 'Article bien modifié');

            // on redirige vers l'affichage de notre objer crée vers la route
            return $this->redirect($this->generateUrl('sdzblog_voir', array(
                'slug' => $article->getSlug(),

            )));
        }


        return $this->render('SdzBlogBundle:Blog:modifier.html.twig', array(
            'article' => $article,
            'form' => $form->createView()
        ));
    }

    public function supprimerAction(Article $article)
    {
        // on crée un formilaire vide, qui ne contiendra que le champ CSRF cela permet
        //la supression de cette article par cette faille CSRF
        $form = $this->createFormBuilder()->getForm();


        $request = $this->get('request');


        if ($request->getMethod() == 'POST') {

            // on vérifie que les valeurs sont correct en faisant appel au validator
            if ($form->handleRequest($request)->isValid()) {
                //on supprime
                $em = $this->getDoctrine()->getManager();
                $em->remove($article);
                $em->flush();
                //un message de suppression
                $this->get('session')->getFlashBag()->add('info', 'Article bien supprimé');
                // Puis on redirige vers l'accueil
                return $this->redirect($this->generateUrl('sdzblog_accueil'));
            }


        }
        //s'il y a une erreur on dirige vers
        // Si la requête est en GET, on affiche une page de confirmation avant de supprimer
        return $this->render('SdzBlogBundle:Blog:supprimer.html.twig', array(
            'article' => $article,
            'form' => $form->createView()

        ));
    }

    public function menuAction($nombre)
    {
        $liste = $this->getDoctrine()
            ->getManager()
            ->getRepository('SdzBlogBundle:Article')
            ->findBy(
                array(),          // Pas de critère
                array('date' => 'desc'), // On trie par date décroissante
                $nombre,         // On sélectionne $nombre articles
                0                // À partir du premier
            );

        return $this->render('SdzBlogBundle:Blog:menu.html.twig', array(
            'liste_articles' => $liste // C'est ici tout l'intérêt : le contrôleur passe les variables nécessaires au template !
        ));
    }


    public function ChangeRoleAction()
    {
        dump( __DIR__);
        die;


    }


}