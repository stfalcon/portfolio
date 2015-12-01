<?php
namespace Stfalcon\ReCaptchaBundle\Validator\Constraints;

use Buzz\Browser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ValidCaptchaValidator extends ConstraintValidator
{
    /**
     * @var string
     */
    private $siteKey;

    /**
     * @var string
     */
    private $secret;

    /**
     * @var Browser
     */
    private $buzz;

    /**
     * @var Request
     */
    private $request;

    /**
     * @param Request    $request
     * @param Browser    $buzz
     * @param string     $siteKey
     * @param string     $secret
     */
    function __construct(Request $request, Browser $buzz, $siteKey, $secret)
    {
        $this->buzz = $buzz;
        $this->siteKey = $siteKey;
        $this->secret = $secret;
        $this->request = $request;
    }

    /**
     * Checks if the passed value is valid.
     *
     * @param mixed $value The value that should be validated
     * @param Constraint $constraint The constraint for the validation
     *
     * @api
     */
    public function validate($value, Constraint $constraint)
    {
        $reCaptchaResponse = $this->request->request->get('g-recaptcha-response');

        if (empty($reCaptchaResponse)) {
            $this->context->addViolation(
                $constraint->message
            );

            return;
        }

        $response = $this->buzz->submit(
            'https://www.google.com/recaptcha/api/siteverify',
            [
                'secret' => $this->secret,
                'response' => $reCaptchaResponse,
                'remoteip' => $this->request->getClientIp()
            ]
        );
        $reCaptchaValidationResponse = json_decode($response->getContent());

        if (true !== $reCaptchaValidationResponse->success) {
            $this->context->addViolation(
                $constraint->message
            );
        }
    }
}