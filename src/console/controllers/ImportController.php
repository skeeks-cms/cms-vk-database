<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link https://skeeks.com/
 * @copyright (c) 2010 SkeekS
 * @date 27.02.2017
 */
namespace skeeks\cms\vkDatabase\console\controllers;
use skeeks\cms\vkDatabase\models\VkCity;
use skeeks\cms\vkDatabase\models\VkCountry;
use skeeks\cms\vkDatabase\models\VkRegion;
use skeeks\cms\vkDatabase\models\VkSchool;
use yii\console\Controller;
use yii\helpers\ArrayHelper;
use yii\helpers\Console;
use yii\helpers\Json;
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
        $apiUrl = "https://api.vk.com/method/database.getCountries?need_all=1&v=5.62&count=300";

        $this->stdout("Request to: " . $apiUrl . "\n", Console::BOLD);

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
            $this->stdout("Not response\n", Console::FG_RED);
        }

        $items = ArrayHelper::getValue($httpResponse->data, 'response.items');
        foreach ($items as $item)
        {
            $id = ArrayHelper::getValue($item, 'id');
            $title = ArrayHelper::getValue($item, 'title');

            $this->stdout("\t{$id}  {$title}\n");

            $vkCountry = VkCountry::find()->where(['vk_id' => $id])->one();
            if ($vkCountry)
            {
                $this->stdout("\t\t - exist\n", Console::FG_YELLOW);
            }

            $vkCountry = new VkCountry();

            $vkCountry->vk_id   = $id;
            $vkCountry->name    = $title;

            if ($vkCountry->save())
            {
                $this->stdout("\t\t - added\n", Console::FG_GREEN);
            } else
            {
                $this->stdout("\t\t - error\n", Console::FG_RED);
            }
        }
    }


    public function actionRegions($vkId = 1)
    {
        $vkCountry = VkCountry::find()->where(['vk_id' => $vkId])->one();
        if (!$vkCountry)
        {
            $this->stdout("Country not found\n", Console::FG_RED);
        }





        $apiUrl = "https://api.vk.com/method/database.getRegions?country_id={$vkId}&v=5.62&count=1000";

        $this->stdout("Request to: " . $apiUrl . "\n", Console::BOLD);

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
            $this->stdout("Not response\n", Console::FG_RED);
        }

        $items = ArrayHelper::getValue($httpResponse->data, 'response.items');
        foreach ($items as $item)
        {
            $id = ArrayHelper::getValue($item, 'id');
            $title = ArrayHelper::getValue($item, 'title');

            $this->stdout("\t{$id}  {$title}\n");

            $vkRegion = VkRegion::find()->where(['vk_id' => $id])->one();
            if ($vkRegion)
            {
                $this->stdout("\t\t - exist\n", Console::FG_YELLOW);
            }

            $vkRegion = new VkRegion();

            $vkRegion->country_id   = $vkCountry->id;
            $vkRegion->vk_id        = $id;
            $vkRegion->name         = $title;

            if ($vkRegion->save())
            {
                $this->stdout("\t\t - added\n", Console::FG_GREEN);
            } else
            {
                $errors = Json::encode($vkRegion->errors);
                $this->stdout("\t\t - error: {$errors}\n", Console::FG_RED);
            }
        }
    }

    public function actionCities($vkId = 1)
    {
        /**
         * @var $vkCountry VkCountry
         */
        $vkCountry = VkCountry::find()->where(['vk_id' => $vkId])->one();
        if (!$vkCountry)
        {
            $this->stdout("Country not found\n", Console::FG_RED);
        }

        if (!$vkCountry->vkRegions)
        {
            $this->stdout("Regions not found\n", Console::FG_RED);
        }


        foreach ($vkCountry->vkRegions as $vkRegion)
        {
            $this->stdout("Region: {$vkRegion->name}\n", Console::BOLD);

            $vkRegionId = $vkRegion->vk_id;


            $apiUrl = "https://api.vk.com/method/database.getCities?country_id={$vkId}&need_all=1&region_id={$vkRegionId}&v=5.62&count=1000";

            $this->stdout("Request to: " . $apiUrl . "\n", Console::BOLD);

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
                $this->stdout("Not response\n", Console::FG_RED);
            }

            $totalItems = ArrayHelper::getValue($httpResponse->data, 'response.count');
            $this->stdout("\t Total: {$totalItems}\n", Console::FG_YELLOW);

            $items = ArrayHelper::getValue($httpResponse->data, 'response.items');
            foreach ($items as $item)
            {
                $id = ArrayHelper::getValue($item, 'id');
                $title = ArrayHelper::getValue($item, 'title');
                $area = ArrayHelper::getValue($item, 'area');
                $region = ArrayHelper::getValue($item, 'region');

                $this->stdout("\t{$id}  {$title}\n");

                $vkCity = VkCity::find()->where(['vk_id' => $id])->one();
                if ($vkCity)
                {
                    $this->stdout("\t\t - exist\n", Console::FG_YELLOW);
                }

                $vkCity = new VkCity();

                $vkCity->region_id   = $vkRegion->id;
                $vkCity->country_id   = $vkCountry->id;
                $vkCity->vk_id        = $id;
                $vkCity->name         = $title;
                $vkCity->area_name         = $area;
                $vkCity->region_name         = $region;

                if ($vkCity->save())
                {
                    $this->stdout("\t\t - added\n", Console::FG_GREEN);
                } else
                {
                    $errors = Json::encode($vkCity->errors);
                    $this->stdout("\t\t - error: {$errors}\n", Console::FG_RED);
                }
            }

            sleep(1);
        }
    }






    public function actionSchools($vkId = 1)
    {
        /**
         * @var $vkCountry VkCountry
         */
        $vkCountry = VkCountry::find()->where(['vk_id' => $vkId])->one();
        if (!$vkCountry)
        {
            $this->stdout("Country not found\n", Console::FG_RED);
        }




        foreach (VkCity::find()->where(['country_id' => $vkCountry->id])->each(100) as $vkCity)
        {
            $this->stdout("City: {$vkCity->name}\n", Console::BOLD);

            $vkCityId = $vkCity->vk_id;


            $apiUrl = "https://api.vk.com/method/database.getSchools?city_id={$vkCityId}&v=5.62&count=10000";

            $this->stdout("Request to: " . $apiUrl . "\n", Console::BOLD);

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
                $this->stdout("Not response\n", Console::FG_RED);
            }

            $totalItems = ArrayHelper::getValue($httpResponse->data, 'response.count');
            $this->stdout("\t Total: {$totalItems}\n", Console::FG_YELLOW);

            $items = ArrayHelper::getValue($httpResponse->data, 'response.items');
            foreach ($items as $item)
            {
                $id = ArrayHelper::getValue($item, 'id');
                $title = ArrayHelper::getValue($item, 'title');
                $area = ArrayHelper::getValue($item, 'area');
                $region = ArrayHelper::getValue($item, 'region');

                $this->stdout("\t{$id}  {$title}\n");

                $vkSchool = VkSchool::find()->where(['vk_id' => $id])->one();
                if ($vkSchool)
                {
                    $this->stdout("\t\t - exist\n", Console::FG_YELLOW);
                }

                $vkSchool = new VkSchool();

                $vkSchool->city_id      = $vkCity->id;
                $vkSchool->vk_id        = $id;
                $vkSchool->name         = $title;

                if ($vkSchool->save())
                {
                    $this->stdout("\t\t - added\n", Console::FG_GREEN);
                } else
                {
                    $errors = Json::encode($vkSchool->errors);
                    $this->stdout("\t\t - error: {$errors}\n", Console::FG_RED);
                }
            }

            sleep(1);
        }
    }
}
