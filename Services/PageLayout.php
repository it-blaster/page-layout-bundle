<?php

namespace Etfostra\PageLayoutBundle\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Templating\EngineInterface;
use Tradeins\CorpBundle\Entity\Widget\Widget;

class PageLayout
{
    /** @var array */
    protected $templates;

    /** @var array */
    protected $layout_data;

    /** @var EngineInterface */
    protected $templating;

    /** @var WidgetRenderInterface|null */
    protected $widget_renderer;

    /** @var EntityManager */
    protected $doctrine_orm_entity_manager;

    protected $grid_settings = [];

    /**
     * PageLayout constructor.
     * @param array $templates
     * @param EngineInterface $templating
     * @param EntityManager $doctrine_orm_entity_manager
     * @param array $gridSettings
     * @param WidgetRenderInterface|null $widget_renderer
     */
    public function __construct(array $templates, EngineInterface $templating, EntityManager $doctrine_orm_entity_manager, array $gridSettings, WidgetRenderInterface $widget_renderer = null)
    {
        $this->templates                    = $templates;
        $this->templating                   = $templating;
        $this->widget_renderer              = $widget_renderer;
        $this->doctrine_orm_entity_manager  = $doctrine_orm_entity_manager;
        $this->grid_settings                = $gridSettings;
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function render()
    {
        $this->checkLayoutData(__FUNCTION__);

        $this->passWidgetsToRenderer($this->layout_data);

        $rows = $this->convertGridstackToBootstrap($this->getLayoutData());

        return $this->templating->render($this->templates['front_layout'], array(
            'rows'      => $rows,
            'renderer'  => $this->widget_renderer,
        ));
    }

    /**
     * @return mixed
     */
    public function getLayoutData()
    {
        $this->checkLayoutData(__FUNCTION__);

        return $this->layout_data;
    }

    /**
     * @param $layout_data
     * @return $this
     */
    public function setLayoutData($layout_data)
    {
        $layout_data = trim($layout_data);

        if (empty($layout_data)) {
            $this->layout_data = array();
        } else {
            $this->layout_data = json_decode($layout_data, true);
            $this->addObjectsToLayoutData();
        }

        return $this;
    }

    private function addObjectsToLayoutData()
    {
        if (count($this->layout_data)) {
            $layoutData = [];
            $widgetsInContainer = $this->getWidgetsInContainer();
            foreach ($this->layout_data as $ind => $item) {
                $item['in_container'] = false;
                if (strstr($item['id'], 'Widget:')) {
                    $widgetId = str_replace('Widget:', '',$item['id']);
                    $rep = $this->getEM()->getRepository('TradeinsCorpBundle:Widget\Widget');
                    /** @var Widget $widget */
                    if ($widget = $rep->find($widgetId)) {
                        $item['object'] = $widget;
                        $item['in_container'] = in_array($widget->getTypeWidget(), $widgetsInContainer);
                    }
                }
                $layoutData[$ind] = $item;
            }
        }
        $this->layout_data = $layoutData;
        dump($this->layout_data);
    }

    /**
     * @return WidgetRenderInterface
     */
    public function getWidgetRenderer()
    {
        return $this->widget_renderer;
    }

    /**
     * @param WidgetRenderInterface $widget_renderer
     * @return $this
     */
    public function setWidgetRenderer(WidgetRenderInterface $widget_renderer)
    {
        $this->widget_renderer = $widget_renderer;

        return $this;
    }

    /**
     * Doctrine Orm Entity Manager
     *
     * @return EntityManager
     */
    private function getEM()
    {
        return $this->doctrine_orm_entity_manager;
    }

    /**
     * Список алиасов виджетов в контейнере
     *
     * @return array
     */
    private function getWidgetsInContainer()
    {
        $widgetsInContainer = [];
        if (count($this->grid_settings) && isset($this->grid_settings['widgets_container'])) {
            $widgetsInContainer = $this->grid_settings['widgets_container'];
        }
        return $widgetsInContainer;
    }

    /**
     * @param $layout_data
     */
    private function passWidgetsToRenderer($layout_data)
    {
        if ($this->widget_renderer === null) return;

        $widget_keys_array = array();
        foreach ($layout_data as $box) {
            $widget_keys_array[] = $box['id'];
        }

        $this->getWidgetRenderer()->setWidgets($widget_keys_array);
    }

    /**
     * @param $caller
     * @throws \Exception
     */
    private function checkLayoutData($caller)
    {
        if ($this->layout_data === null) {
            throw new \Exception('Property layout_data must be set before using '.$caller.'(), use setLayoutData()');
        }
    }

    /**
     * @param array $gridstack
     * @return array
     */
    private function convertGridstackToBootstrap(array $gridstack)
    {
        $rows = array();
        foreach ($gridstack as $box) {
            $rows[$box['y']][] = $box;
        }

        foreach ($rows as $row_key => $row) {
            $col_cursor_point = 0;
            foreach($row as $col_key => $col) {
                $rows[$row_key][$col_key]['offset'] = $rows[$row_key][$col_key]['x'] - $col_cursor_point;

                $col_cursor_point += $rows[$row_key][$col_key]['offset'] + $rows[$row_key][$col_key]['width'];
            }
        }

        return $rows;
    }
}