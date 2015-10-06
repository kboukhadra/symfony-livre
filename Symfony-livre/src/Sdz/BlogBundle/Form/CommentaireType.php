<?php
/**
 * Created by PhpStorm.
 * User: Khalid_Mac
 * Date: 29/09/15
 * Time: 11:19
 */

namespace Sdz\BlogBundle\Form;


class CommentaireType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('auteur', 'text')
            ->add('contenu', 'textarea');

    }



    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        // on dit à symfony sur quelle objet construire  le formulaire
        $resolver->setDefaults(array(
            'data_class' => 'Sdz\BlogBundle\Entity\Commentaire'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sdz_blogbundle_commentaire';
    }






    }