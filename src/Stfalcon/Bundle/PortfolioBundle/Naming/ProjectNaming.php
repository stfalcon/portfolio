<?php
namespace Stfalcon\Bundle\PortfolioBundle\Naming;

use Vich\UploaderBundle\Naming\NamerInterface as NamerInterface;

/**
 * Project Naming
 */
class ProjectNaming implements NamerInterface
{
    /**
     * Generate unique name for project image
     *
     * @param object $obj   The object the upload is attached to.
     * @param string $field The name of the uploadable field to generate a name for.
     *
     * @return string The file name.
     */
    public function name($obj, $field)
    {
        return uniqid() . '.' . $obj->getImageFile()->guessExtension();
    }
}
