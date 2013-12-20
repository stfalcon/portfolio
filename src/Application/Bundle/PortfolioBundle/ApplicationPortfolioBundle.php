<?php

namespace Application\Bundle\PortfolioBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class ApplicationPortfolioBundle
 */
class ApplicationPortfolioBundle extends Bundle
{
    /**
     * @return string
     */
    public  function getParent()
    {
        return 'StfalconPortfolioBundle';
    }
}
