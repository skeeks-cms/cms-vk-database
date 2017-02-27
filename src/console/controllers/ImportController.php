<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link https://skeeks.com/
 * @copyright (c) 2010 SkeekS
 * @date 27.02.2017
 */
namespace skeeks\cms\vkDatabase\console\controllers;
use yii\console\Controller;
use yii\helpers\Console;
use yii\httpclient\Client;

/**
 * Import data from vk
 */
class ImportController extends Controller
{
    /**
     * Import countries
     */
    public function actionCountries()
    {
        $vk = \Yii::$app->vkApi;
        print_r($vk->get('database.getCountries'));die;

        print_r(file_get_contents("https://api.vk.com/method/database.getCountries?need_all=1&v=5.62"));die;

        $apiUrl = "https://api.vk.com/method/database.getCountries?need_all=1&v=5.62";


        $client = new Client([
            'requestConfig' => [
                'format' => Client::FORMAT_JSON
            ]
        ]);
        $httpRequest = $client->createRequest()
                            ->setMethod("GET")
                            ->setUrl($apiUrl)
                            //->addHeaders(['Content-type' => 'application/json'])
                            //->addHeaders(['user-agent' => 'JSON-RPC PHP Client'])
                            ->setOptions([
                                'timeout' => 10
                            ]);
        $httpResponse       = $httpRequest->send();

        print_r($httpResponse->getData());die;

        $this->stdout("test");
    }
}
