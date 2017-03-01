<?php

namespace skeeks\cms\vkDatabase\models;

use Yii;

/**
 * This is the model class for table "{{%vk_region}}".
 *
 * @property integer $id
 * @property integer $country_id
 * @property integer $vk_id
 * @property string $name
 *
 * @property VkCity[] $vkCities
 * @property VkCountry $country
 */
class VkRegion extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%vk_region}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['country_id', 'vk_id'], 'required'],
            [['country_id', 'vk_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['vk_id'], 'unique'],
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
            'vk_id' => Yii::t('skeeks/vkDatabase', 'Vk ID'),
            'name' => Yii::t('skeeks/vkDatabase', 'Name'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVkCities()
    {
        return $this->hasMany(VkCity::className(), ['region_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(VkCountry::className(), ['id' => 'country_id']);
    }
}