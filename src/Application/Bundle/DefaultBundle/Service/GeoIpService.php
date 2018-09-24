<?php
namespace Application\Bundle\DefaultBundle\Service;

use GeoIp2\Database\Reader;
use GeoIp2\Exception\AddressNotFoundException;

/**
 * Class GeoIpService
 */
class GeoIpService extends Reader
{
    /**
     * @var array
     */
    private $localeByCountry = [
        'UA'    => 'ru',
        'RU'    => 'ru',
        'UZ'    => 'ru',
        'TM'    => 'ru',
        'TJ'    => 'ru',
        'MD'    => 'ru',
        'KG'    => 'ru',
        'KZ'    => 'ru',
        'BY'    => 'ru',
        'AM'    => 'ru',
        'AZ'    => 'ru',
        'DE'    => 'de',
        'AT'    => 'de',
        'CH'    => 'de',
        'LI'    => 'de',
    ];

    /**
     * @param string $databasePath
     * @param array  $locales
     */
    public function __construct($databasePath, array $locales = [])
    {
        parent::__construct($databasePath, $locales);
    }

    /**
     * @param string $ip
     *
     * @return string
     */
    public function getCountryByIp($ip)
    {
        try {
            $country = $this->country($ip);

            return $country->country->name;
        } catch (AddressNotFoundException $e) {
        }

        return 'not defined';
    }
}