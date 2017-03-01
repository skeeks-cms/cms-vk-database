<?php

namespace skeeks\cms\vkDatabase\models;

use Yii;

/**
 * This is the model class for table "vk_region".
 *
 * @property integer $id
 * @property integer $vk_country_id
 * @property integer $vk_id
 * @property string $name
 *
 * @property VkCity[] $vkCities
 * @property VkCountry $vkCountry
 */
class VkRegion extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vk_region';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vk_country_id', 'vk_id'], 'required'],
            [['vk_country_id', 'vk_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['vk_id'], 'unique'],
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
            'vk_id' => 'Vk ID',
            'name' => 'Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVkCities()
    {
        return $this->hasMany(VkCity::className(), ['vk_region_id' => 'vk_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVkCountry()
    {
        return $this->hasOne(VkCountry::className(), ['vk_id' => 'vk_country_id']);
    }
}