<?php

namespace Etfostra\PageLayoutBundle\Services;

/**
 * Interface WidgetRenderInterface
 * @package Etfostra\PageLayoutBundle\Services
 */
interface WidgetRenderInterface
{
    /**
     * @param array $widgets
     * @return mixed
     */
    public function setWidgets(array $widgets);

    /**
     * @param $widget_id
     * @return mixed
     */
    public function getWidget($widget_id);
}
