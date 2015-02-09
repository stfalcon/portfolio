<?php

namespace Stfalcon\RedirectBundle\Tests\Validator\Constraints;

use Stfalcon\RedirectBundle\Validator\Constraints\UniversalUrlValidator;

class UniversalUrlValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    public $constraint;
    /**
     * @var UniversalUrlValidator
     */
    public $validator;
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    public $context;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        // Mocking dependencies
        $this->constraint = $this
            ->getMockBuilder('Stfalcon\RedirectBundle\Validator\Constraints\UniversalUrl')
            ->getMock(array());
        $this->context    = $this
            ->getMockBuilder('Symfony\Component\Validator\ExecutionContext')
            ->disableOriginalConstructor()
            ->getMock(array('addViolation'));

        $this->validator = new UniversalUrlValidator();
        $this->validator->initialize($this->context);
    }

    public function provideValidGlobal()
    {
        return array(
            array('http://www.google.com/'),
            array('http://google.com/'),
            array('https://www.google.com/'),
        );
    }

    public function provideValidLocal()
    {
        return array(
            array('/dir/page'),
            array('/dir/page.php'),
            array('/dir/page.php?param1=test'),
        );
    }

    public function provideValidUri()
    {
        return array_merge($this->provideValidGlobal(), $this->provideValidLocal());
    }

    public function provideInvalidUri()
    {
        return array(
            array('test'),
            array('http:///www.google.com/'),
            array('?param=value'),
            array('http://www.google.com/tes^'),
        );
    }

    /**
     * @dataProvider provideValidUri
     */
    public function testValidUrl($uri)
    {
        $this->context
            ->expects($this->never())
            ->method('addViolation');

        $this->validator->validate($uri, $this->constraint);
    }

    /**
     * @dataProvider provideInvalidUri
     */
    public function testInvalidUrl($uri)
    {
        $this->context
            ->expects($this->once())
            ->method('addViolation');

        $this->validator->validate($uri, $this->constraint);
    }

    /**
     * @dataProvider provideValidLocal
     */
    public function testLocalPathWithDisabledLocalOption($uri)
    {
        $this->context
            ->expects($this->once())
            ->method('addViolation');
        $this->constraint->local = false;

        $this->validator->validate($uri, $this->constraint);
    }

    /**
     * @dataProvider provideValidLocal
     */
    public function testLocalPathWithDisabledGlobalOption($uri)
    {
        $this->context
            ->expects($this->never())
            ->method('addViolation');
        $this->constraint->local  = true;
        $this->constraint->global = false;

        $this->validator->validate($uri, $this->constraint);
    }

    /**
     * @dataProvider provideValidGlobal
     */
    public function testGlobalPathWithDisabledGlobalOption($uri)
    {
        $this->context
            ->expects($this->once())
            ->method('addViolation');
        $this->constraint->global = false;

        $this->validator->validate($uri, $this->constraint);
    }

    /**
     * @dataProvider provideInvalidUri
     */
    public function testErrorMessage($uri)
    {
        $this
            ->context
            ->expects($this->once())
            ->method('addViolation')
            ->with($this->anything(), $this->anything());
        $this->validator->validate($uri, $this->constraint);
    }
}