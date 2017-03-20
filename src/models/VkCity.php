<?php

namespace skeeks\cms\vkDatabase\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "vk_city".
 *
 * @property integer $id
 * @property integer $vk_country_id
 * @property integer $vk_region_id
 * @property integer $vk_id
 * @property string $name
 * @property string $area_name
 * @property string $region_name
 *
 * @property VkRegion $vkRegion
 * @property VkCountry $vkCountry
 * @property VkSchool[] $vkSchools
 */
class VkCity extends \yii\db\ActiveRecord
{
    /**
     * @param $vk_id
     *
     * @return null|static|VkCity
     */
    static public function getOneFromApi($vk_id)
    {
        if ($model = static::findOne(['vk_id' => $vk_id]))
        {
            return $model;
        }

        $data = \Yii::$app->vkDatabase->getCitiesById([$vk_id]);

        if (!$data)
        {
            return null;
        }

        $data = ArrayHelper::getValue($data, 'response.0');

        if (!$data)
        {
            return null;
        }

        $model = new static([
            'vk_id' => ArrayHelper::getValue($data, 'id'),
            'name' => ArrayHelper::getValue($data, 'title'),
        ]);

        return $model;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vk_city';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vk_country_id', 'vk_id'], 'required'],
            [['vk_country_id', 'vk_region_id', 'vk_id'], 'integer'],
            [['name', 'area_name', 'region_name'], 'string', 'max' => 255],
            [['vk_id'], 'unique'],
            [['vk_region_id'], 'exist', 'skipOnError' => true, 'targetClass' => VkRegion::className(), 'targetAttribute' => ['vk_region_id' => 'vk_id']],
            [['vk_country_id'], 'exist', 'skipOnError' => true, 'targetClass' => VkCountry::className(), 'targetAttribute' => ['vk_country_id' => 'vk_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'vk_country_id' => 'Vk Country ID',
            'vk_region_id' => 'Vk Region ID',
            'vk_id' => 'Vk ID',
            'name' => 'Name',
            'area_name' => 'Area Name',
            'region_name' => 'Region Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVkRegion()
    {
        return $this->hasOne(VkRegion::className(), ['vk_id' => 'vk_region_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVkCountry()
    {
        return $this->hasOne(VkCountry::className(), ['vk_id' => 'vk_country_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVkSchools()
    {
        return $this->hasMany(VkSchool::className(), ['vk_city_id' => 'vk_id']);
    }
}