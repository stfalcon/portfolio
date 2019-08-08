<?php

namespace Application\Bundle\DefaultBundle\Service;

use Application\Bundle\DefaultBundle\Controller\PriceAdminController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * PriceService.
 */
class PriceService
{
    private $config;
    private $uploadCsvFile;

    /**
     * @param array  $config
     * @param string $uploadCsvFile
     */
    public function __construct($config, $uploadCsvFile)
    {
        $this->config = $config;
        $this->uploadCsvFile = $uploadCsvFile;
    }

    /**
     * @param array  $content
     * @param string $type
     *
     * @return array
     *
     * @throws BadRequestHttpException
     * @throws \Exception
     */
    public function preparePrice(array $content, $type)
    {
        if (!isset($content['platform'], $content['order'])) {
            throw new BadRequestHttpException();
        }

        if (!\in_array($type, ['web', 'app'], true)) {
            throw new BadRequestHttpException('Bat type parameter!');
        }

        $resultFileName = PriceAdminController::JSON_RESULT_APP_FILE_NAME;
        if ('web' === $type) {
            $resultFileName = PriceAdminController::JSON_RESULT_WEB_FILE_NAME;
        }

        $platform = $content['platform'];

        $path = $this->config['web_root'].$this->uploadCsvFile;
        try {
            $priceJson = \file_get_contents($path.'/'.$resultFileName);
        } catch (\Exception $e) {
            throw $e;
        }

        $price = \json_decode($priceJson, true);
        $resultPrice = [];
        foreach ($price as $item) {
            if (isset($item['price'][$platform])) {
                $resultPrice[$item['name']] = [
                    'title' => $item['title'],
                    'price' => $item['price'][$platform],
                    'total' => \array_sum($item['price'][$platform]),
                ];
            }
        }
        $orderList = [];
        foreach ($content['order'] as $key => $order) {
            if (isset($resultPrice[$order['name']])) {
                $orderList[$order['name']] = $resultPrice[$order['name']];
            }
        }
        $content['order'] = $orderList;

        $total = 0;
        foreach ($content['order'] as $order) {
            $total += $order['total'];
        }
        $content['total'] = $total;

        return $content;
    }
}
