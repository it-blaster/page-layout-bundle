<?php

namespace Etfostra\PageLayoutBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class PageLayoutType
 * @package Etfostra\PageLayoutBundle\Form\Type
 */
class PageLayoutType extends AbstractType
{

    /**
     * @var array
     */
    protected $grid_settings;
    /**
     * @var array
     */
    protected $properties;

    /**
     * PageLayoutType constructor.
     * @param array $grid_settings
     * @param array $properties
     */
    public function __construct(array $grid_settings, array $properties)
    {
        $this->grid_settings = $grid_settings;
        $this->properties = $properties;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'page_layout';
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return 'hidden';
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'properties' => $this->properties,
            'grid_settings' => $this->grid_settings,
            'choices'   => array(),
        ));
    }

    /**
     * @param FormView $view
     * @param FormInterface $form
     * @param array $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['properties'] = $options['properties'];
        $view->vars['grid_settings'] = $options['grid_settings'];
        $view->vars['choices'] = $options['choices'];
    }
}