<?php

namespace Stfalcon\RedirectBundle\Entity\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Fresh\Bundle\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

/**
 * Enum order status type
 */
class RedirectStatusType extends AbstractEnumType
{
    /**
     * @var string
     */
    protected $name = 'RedirectStatusType';

    const ENABLED  = 'Enabled';
    const DISABLED = 'Disabled';

    /**
     * @var array
     */
    public static $choices = array(
        self::ENABLED  => 'Enabled',
        self::DISABLED => 'Disabled',
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
