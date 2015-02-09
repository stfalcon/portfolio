<?php

namespace Stfalcon\RedirectBundle\Entity\Types;

use Fresh\Bundle\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

/**
 * Enum order status type
 */
class RedirectCodeType extends AbstractEnumType
{
    /**
     * @var string
     */
    protected $name = 'RedirectCodeType';

    const CODE_301 = '301';
    const CODE_302 = '302';

    /**
     * @var array
     */
    public static $choices = array(
        self::CODE_301  => '301',
        self::CODE_302 => '302',
    );

    /**
     * Get values for the Enum field
     *
     * @return array
     */
    public static function getChoices()
    {
        return self::$choices;
    }

    /**
     * Get values
     *
     * @static
     * @return array
     */
    public static function getValues()
    {
        return array_keys(self::$choices);
    }

    /**
     * Get readable block type
     *
     * @param string $key Key of array
     *
     * @static
     * @return mixed
     */
    public static function getReadableValue($key)
    {
        return isset(self::$choices[$key])
            ? self::$choices[$key]
            : false;
    }
}
