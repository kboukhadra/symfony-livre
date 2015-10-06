<?php

namespace Sdz\BlogBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ArticleType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('date', 'date')
            ->add('titre', 'text')
            ->add('auteur', 'text')
            ->add('contenu', 'textarea')
            ->add('image', new ImageType())
            //->add('publication','checkbox', array('required'=>false))
            ->add('categories','collection',array('type'=>new CategorieType(),
                                                   'allow_add'=> true,
                                                    'allow_delete'=>true

                ))
           ->add('categories','entity',array(

           'class'     => 'SdzBlogBundle:Categorie',
           'property'  =>'nom',
           'multiple'  => true,
           'expanded'  => false,
               ))

        ;


     $factory = $builder->getFormFactory();

        // on ajoute une fonction qui permet d'écouter l'evenement
        $builder->addEventListener(FormEvents::PRE_SET_DATA,// on définit l'evenenement qui nous intéressse
                                   function(FormEvent $event) use ($factory){
                // on définit une fct qui seras exécuté lors de l'evenement
                $article = $event->getData();
                //dump($article);de;
                $publi= $article->getPublication();

                if( null  === $article ){
                    return;// on sort de la fonction lorque $article vaut null
                }
                //si l'article n'est pas encore publié c est adire que $publi=== null  ou égale false c est adire
                // n'a pas encoré été coché, on
                // ajoute le champ de
                // publication
                if( $publi === null || ($publi === false) ){

                    $event->getForm()->add(
                        $factory->createNamed('publication','checkbox',null, array('required'=>false,
                            'auto_initialize'=>false))
                    );
                } else{ // sinon on le supprime
                    $event->getForm()->remove('publication');
                }
            }
        );


    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        // on dit à symfony sur quelle objet construire  le formulaire
        $resolver->setDefaults(array(
            'data_class' => 'Sdz\BlogBundle\Entity\Article'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sdz_blogbundle_article';
    }
}
