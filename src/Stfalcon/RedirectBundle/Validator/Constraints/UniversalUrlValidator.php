<?php

namespace Stfalcon\RedirectBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\UrlValidator;

/**
 * Extends basic URL validator to support URLs with query string and local pathes
 */
class UniversalUrlValidator extends UrlValidator
{
    /**
     * {@inheritDoc}
     *
     * @param string     $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        $startWithProtocol = preg_match('/^[a-z]{3,9}\:\/\//i', $value);
        $fullyValidUri     = $startWithProtocol && preg_match('/
            (?:[a-z0-9\-\.]+\.[a-z0-9]+)
            (?:\/[a-z0-9\.\-\/\$\_=\?]*)?$
        /ix', $value);
        $validLocalPath    = preg_match('/^\/[a-z0-9\.\-\/\$\_=\?\&]+$/i', $value);

        if ($constraint->global && $fullyValidUri) {
            list($uriValue) = explode('?', $value);

            return parent::validate($uriValue, $constraint);
        }

        if (!$constraint->local || !$validLocalPath) {
            $this->context->addViolation($constraint->message, array('{{ value }}' => $value));
        }
    }
}
