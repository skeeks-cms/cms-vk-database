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
use yii\data\Pagination;
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
                continue;
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


    /**
     * Import regions
     * @param int $countryVkId
     */
    public function actionRegions($countryVkId = 1)
    {
        $vkCountry = VkCountry::find()->where(['vk_id' => $countryVkId])->one();
        if (!$vkCountry)
        {
            $this->stdout("Country not found\n", Console::FG_RED);
        }





        $apiUrl = "https://api.vk.com/method/database.getRegions?country_id={$countryVkId}&v=5.62&count=1000";

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
                continue;
            }

            $vkRegion = new VkRegion();

            $vkRegion->vk_country_id   = $vkCountry->vk_id;
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

    /**
     * Import cities
     * @param int $vkId
     */
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


        /**
         * @var $vkRegion VkRegion
         */
        foreach ($vkCountry->vkRegions as $vkRegion)
        {
            $this->stdout("Region: {$vkRegion->name}\n", Console::BOLD);

            $vkRegionId = $vkRegion->vk_id;

            if ($vkRegion->getVkCities()->count() < 999)
            {
                continue;
            }



            $this->stdout("Regions has more 999 cities\n", Console::FG_YELLOW);


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

            if ($totalItems > 1000)
            {
                $pagination = new Pagination();
                $pagination->page = 1;
                $pagination->defaultPageSize = 1000;
                $pagination->pageSizeLimit = [1, 1000];
                $pagination->totalCount = $totalItems;

                $totalPages = $pagination->pageCount - 1;

                for ($i = 1; $i <= $totalPages; $i ++)
                {
                    $offset = 1000 * $i;
                    $apiUrl = "https://api.vk.com/method/database.getCities?country_id={$vkId}&need_all=1&region_id={$vkRegionId}&v=5.62&count=1000&offset=" . $offset;
                    $this->stdout("\t\t Page: {$i}\n", Console::FG_YELLOW);
                    $this->stdout("\t\t Request: {$apiUrl}\n", Console::FG_YELLOW);


                    $client = new Client();
                    $httpRequest = $client->createRequest()
                                        ->setMethod("GET")
                                        ->setUrl($apiUrl)
                                        ->addHeaders(['accept-language' => 'ru-RU,ru;q=0.8,en-US;q=0.6,en;q=0.4'])
                                        ->setOptions([
                                            'timeout' => 10
                                        ]);
                    $httpResponse       = $httpRequest->send();

                    $itemsTmp = ArrayHelper::getValue($httpResponse->data, 'response.items');
                    $items = ArrayHelper::merge($items, $itemsTmp);
                }
            }



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
                    continue;
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


        $lastSchool = VkSchool::find()
            ->orderBy(['city.vk_id' => SORT_DESC])
            ->joinWith('city as city')
            ->limit(1)
            ->one();

        /**
         * @var $vkCity VkCity
         */
        foreach (VkCity::find()->where(['country_id' => $vkCountry->id])->andWhere([">=", "vk_id", $lastSchool->city->vk_id])->orderBy(['vk_id' => SORT_ASC])->each(100) as $vkCity)
        {
            $this->stdout("City: {$vkCity->name}; vk_id = {$vkCity->vk_id}\n", Console::BOLD);

            if ($vkCity->vkSchools)
            {
                $this->stdout("\t - has schools\n", Console::FG_YELLOW);
                continue;
            }

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




            if ($totalItems > 10000)
            {
                $pagination = new Pagination();
                $pagination->page = 1;
                $pagination->defaultPageSize = 10000;
                $pagination->pageSizeLimit = [1, 10000];
                $pagination->totalCount = $totalItems;

                $totalPages = $pagination->pageCount - 1;

                for ($i = 1; $i <= $totalPages; $i ++)
                {
                    $offset = 10000 * $i;
                    $apiUrl = "https://api.vk.com/method/database.getSchools?city_id={$vkCityId}&v=5.62&count=10000&offset=" . $offset;
                    $this->stdout("\t\t Page: {$i}\n", Console::FG_YELLOW);
                    $this->stdout("\t\t Request: {$apiUrl}\n", Console::FG_YELLOW);


                    $client = new Client();
                    $httpRequest = $client->createRequest()
                                        ->setMethod("GET")
                                        ->setUrl($apiUrl)
                                        ->addHeaders(['accept-language' => 'ru-RU,ru;q=0.8,en-US;q=0.6,en;q=0.4'])
                                        ->setOptions([
                                            'timeout' => 10
                                        ]);
                    $httpResponse       = $httpRequest->send();

                    $itemsTmp = ArrayHelper::getValue($httpResponse->data, 'response.items');
                    $items = ArrayHelper::merge($items, $itemsTmp);
                }
            }

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
                    continue;
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

            usleep(300);
        }
    }
}
