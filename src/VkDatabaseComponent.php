<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link https://skeeks.com/
 * @copyright (c) 2010 SkeekS
 * @date 27.02.2017
 */
namespace skeeks\cms\vkDatabase;
use yii\base\Component;
use yii\helpers\ArrayHelper;
use yii\httpclient\Client;

/**
 * Class VkDatabaseComponent
 * @package skeeks\cms\vkDatabase
 */
class VkDatabaseComponent extends Component
{
    /**
     * @var string
     */
    public $vkApiVersion = '5.62';

    /**
     * @var string
     */
    public $vkBaseApiUrl = 'https://api.vk.com/method/';

    /**
     * @param $method
     * @param $data
     *
     * @return $this
     */
    public function createApiRequest($method, $data)
    {
        $data = ArrayHelper::merge([
            'v' => $this->vkApiVersion
        ], (array) $data);

        $apiUrl = "{$this->vkBaseApiUrl}{$method}?" . http_build_query($data);

        $client = new Client();
        $httpRequest = $client->createRequest()
                ->setMethod("GET")
                ->setUrl($apiUrl)
                ->addHeaders(['accept-language' => 'ru-RU,ru;q=0.8,en-US;q=0.6,en;q=0.4'])
                ->setOptions([
                    'timeout' => 10
                ]);

        return $httpRequest;
    }

    /**
     * @see https://vk.com/dev/database.getCitiesById
     * @param array $vk_ids
     *
     * @return array
     */
    public function getCitiesById($vk_ids = [])
    {
        $httpResponse = $this->createApiRequest('database.getCitiesById', [
                            'city_ids' => implode(',', $vk_ids)
                        ])->send();

        return (array) $httpResponse->data;
    }

    /**
     * @see https://vk.com/dev/database.getStreetsById
     * @param array $vk_ids
     *
     * @return array
     */
    public function getStreetsById($vk_ids = [])
    {
        $httpResponse = $this->createApiRequest('database.getStreetsById', [
                            'street_ids' => implode(',', $vk_ids)
                        ])->send();

        return (array) $httpResponse->data;
    }

    /**
     * @see https://vk.com/dev/database.getSchools
     * @param array $request
     *
     * @return array
     */
    public function getSchools($request = [])
    {
        $httpResponse = $this->createApiRequest('database.getSchools', $request)->send();
        return (array) $httpResponse->data;
    }
}
