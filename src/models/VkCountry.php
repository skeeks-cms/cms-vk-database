<?php

namespace skeeks\cms\vkDatabase\models;

use Yii;

/**
 * This is the model class for table "vk_country".
 *
 * @property integer $id
 * @property integer $vk_id
 * @property string $name
 *
 * @property VkCity[] $vkCities
 * @property VkRegion[] $vkRegions
 */
class VkCountry extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vk_country';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vk_id'], 'required'],
            [['vk_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['vk_id'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'vk_id' => 'Vk ID',
            'name' => 'Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVkCities()
    {
        return $this->hasMany(VkCity::className(), ['vk_country_id' => 'vk_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVkRegions()
    {
        return $this->hasMany(VkRegion::className(), ['vk_country_id' => 'vk_id']);
    }
}