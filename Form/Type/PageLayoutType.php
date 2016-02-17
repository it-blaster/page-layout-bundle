<?php

namespace Etfostra\PageLayoutBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PageLayoutType extends AbstractType
{

    protected $grid_settings;
    protected $properties;

    public function __construct(array $grid_settings, array $properties)
    {
        $this->grid_settings = $grid_settings;
        $this->properties = $properties;
    }

    public function getName()
    {
        return 'page_layout';
    }

    public function getParent()
    {
        return 'hidden';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'properties' => $this->properties,
            'grid_settings' => $this->grid_settings
        ));
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['properties'] = $options['properties'];
        $view->vars['grid_settings'] = $options['grid_settings'];
    }
}