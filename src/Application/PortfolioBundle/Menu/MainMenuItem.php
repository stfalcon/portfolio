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
    public function renderLink()
    {
        $label = $this->renderLabel();
        $uri = $this->getUri();

        return 2222;
        if (!$uri) {
            return $label;
        }

        return sprintf('<a href="%s"><span></span>%s</a>', $uri, $label);
    }

}