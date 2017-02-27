<?php

namespace skeeks\cms\vkDatabase\models;

use Yii;

/**
 * This is the model class for table "{{%vk_school}}".
 *
 * @property integer $id
 * @property integer $city_id
 * @property integer $vk_id
 * @property string $name
 *
 * @property VkCity $city
 */
class VkSchool extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%vk_school}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['city_id', 'vk_id'], 'required'],
            [['city_id', 'vk_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['vk_id'], 'unique'],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => VkCity::className(), 'targetAttribute' => ['city_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('skeeks/vkDatabase', 'ID'),
            'city_id' => Yii::t('skeeks/vkDatabase', 'City ID'),
            'vk_id' => Yii::t('skeeks/vkDatabase', 'Vk ID'),
            'name' => Yii::t('skeeks/vkDatabase', 'Name'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(VkCity::className(), ['id' => 'city_id']);
    }
}