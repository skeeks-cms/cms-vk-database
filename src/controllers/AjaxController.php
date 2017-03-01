<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link https://skeeks.com/
 * @copyright (c) 2010 SkeekS
 * @date 01.03.2017
 */
namespace skeeks\cms\vkDatabase\controllers;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\httpclient\Client;
use yii\web\Controller;

/**
 * Class AjaxController
 * @package skeeks\cms\vkDatabase\controllers
 */
class AjaxController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [

            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'find-city'     => ['post', 'get'],
                    'find-schools'  => ['post'],
                ],
            ],
        ];
    }


    /**
     * @return mixed
     */
    public function actionFindCity()
    {
        $term = \Yii::$app->request->get('term');

        if (!$country_id = \Yii::$app->request->get('country_id'))
        {
            $country_id = 1; //Россия
        }

        $apiUrl = "https://api.vk.com/method/database.getCities?country_id={$country_id}&need_all=1&v=5.62&count=100&q={$term}";

        $client = new Client();
        $httpRequest = $client->createRequest()
                ->setMethod("GET")
                ->setUrl($apiUrl)
                ->addHeaders(['accept-language' => 'ru-RU,ru;q=0.8,en-US;q=0.6,en;q=0.4'])
                ->setOptions([
                    'timeout' => 10
                ]);
        $httpResponse       = $httpRequest->send();


        if (!$httpResponse->data)
        {
            return Json::encode([]);
        }

        $items = ArrayHelper::getValue($httpResponse->data, 'response.items');

        if ($items)
        {
            return Json::encode($items);
        }

        return Json::encode([]);
    }

    /**
     * @return mixed
     */
    public function actionFindSchools()
    {
        $vkCityId = \Yii::$app->request->post('city_id');
        $apiUrl = "https://api.vk.com/method/database.getSchools?city_id={$vkCityId}&v=5.62&count=10000";

        $client = new Client();
        $httpRequest = $client->createRequest()
                ->setMethod("GET")
                ->setUrl($apiUrl)
                ->addHeaders(['accept-language' => 'ru-RU,ru;q=0.8,en-US;q=0.6,en;q=0.4'])
                ->setOptions([
                    'timeout' => 10
                            ]);
        $httpResponse       = $httpRequest->send();

        $items = ArrayHelper::getValue($httpResponse->data, 'response.items');

        if ($items)
        {
            $items = ArrayHelper::map($items, 'id', 'title');
            return Json::encode($items);
        }

        return Json::encode([]);
    }


}
