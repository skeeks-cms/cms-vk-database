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
        //$vk = \Yii::$app->vkApi;
        //print_r($vk->post('database.getCountries'));die;

        //print_r(file_get_contents("https://api.vk.com/method/database.getCountries?need_all=1&v=5.62"));die;

        $apiUrl = "https://api.vk.com/method/database.getCountries?need_all=1&v=5.62&count=300";


        $client = new Client([
            /*'requestConfig' => [
                'format' => Client::FORMAT_JSON
            ]*/
        ]);
        $httpRequest = $client->createRequest()
                            ->setMethod("GET")
                            ->setUrl($apiUrl)
                            //->addHeaders(['Content-type' => 'application/json'])
                            ->addHeaders(['accept-language' => 'ru-RU,ru;q=0.8,en-US;q=0.6,en;q=0.4'])
                            ->setOptions([
                                'timeout' => 10
                            ]);
        $httpResponse       = $httpRequest->send();

        print_r($httpResponse->data);die;

        $this->stdout("test");
    }
}
