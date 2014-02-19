<?php

namespace SmartProject\ProjectBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProjectType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add('name')
          ->add('description', null, array('required' => false))
          ->add('tags', 'tag', array('required' => false))
          ->add('externalId', null, array('required' => false))
          ->add('redmineProject', 'entity', array(
                  'class' => 'SmartProjectProjectBundle:Redmine\Project',
                  'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('rp')
                          ->orderBy('rp.name', 'ASC');
                    },
                  'property' => 'name',
                  'required' => false,
              ))
          ->add('website', null, array('required' => false));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'SmartProject\ProjectBundle\Entity\Project'
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'smartproject_projectbundle_project';
    }
}
