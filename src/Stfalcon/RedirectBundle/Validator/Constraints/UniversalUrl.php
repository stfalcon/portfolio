<?php

namespace Stfalcon\RedirectBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraints\Url;

/**
 * @Annotation
 */
class UniversalUrl extends Url
{
    public $message = 'The string "{{ value }}" is not valid address';
    public $global = true;
    public $local = true;

    public function validatedBy()
    {
        return get_class($this).'Validator';
    }

}
