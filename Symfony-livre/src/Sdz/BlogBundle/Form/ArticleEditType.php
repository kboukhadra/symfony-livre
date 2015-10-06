<?php

namespace Sdz\BlogBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ArticleEditType extends ArticleType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        parent::buildForm($builder,$options);
        $builder->remove('date');



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
        return 'sdz_blogbundle_article_edit';
    }
}
