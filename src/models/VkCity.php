<?php

namespace skeeks\cms\vkDatabase\models;

use Yii;

/**
 * This is the model class for table "{{%vk_city}}".
 *
 * @property integer $id
 * @property integer $country_id
 * @property integer $region_id
 * @property integer $vk_id
 * @property string $name
 * @property string $area_name
 * @property string $region_name
 *
 * @property VkRegion $region
 * @property VkCountry $country
 * @property VkSchool[] $vkSchools
 */
class VkCity extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%vk_city}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['country_id', 'vk_id'], 'required'],
            [['country_id', 'region_id', 'vk_id'], 'integer'],
            [['name', 'area_name', 'region_name'], 'string', 'max' => 255],
            [['vk_id'], 'unique'],
            [['region_id'], 'exist', 'skipOnError' => true, 'targetClass' => VkRegion::className(), 'targetAttribute' => ['region_id' => 'id']],
            [['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => VkCountry::className(), 'targetAttribute' => ['country_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('skeeks/vkDatabase', 'ID'),
            'country_id' => Yii::t('skeeks/vkDatabase', 'Country ID'),
            'region_id' => Yii::t('skeeks/vkDatabase', 'Region ID'),
            'vk_id' => Yii::t('skeeks/vkDatabase', 'Vk ID'),
            'name' => Yii::t('skeeks/vkDatabase', 'Name'),
            'area_name' => Yii::t('skeeks/vkDatabase', 'Area Name'),
            'region_name' => Yii::t('skeeks/vkDatabase', 'Region Name'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegion()
    {
        return $this->hasOne(VkRegion::className(), ['id' => 'region_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(VkCountry::className(), ['id' => 'country_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVkSchools()
    {
        return $this->hasMany(VkSchool::className(), ['city_id' => 'id']);
    }
}