<?php

namespace Application\PortfolioBundle\Menu;

use Knplabs\MenuBundle\MenuItem;

class MainMenuItem extends MenuItem
{

    /**
     * Renders the anchor tag for this menu item.
     *
     * If no uri is specified, or if the uri fails to generate, the
     * label will be output.
     *
     * @return string
     */
    public function renderLabel()
    {
        $label = parent::renderLabel();
        $uri = $this->getUri();

        if (!$uri) {
            return $label;
        }

        return sprintf('<span></span>%s', $label);
    }

}