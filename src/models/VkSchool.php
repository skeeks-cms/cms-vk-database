<?php

namespace skeeks\cms\vkDatabase\models;

use Yii;

/**
 * This is the model class for table "vk_school".
 *
 * @property integer $id
 * @property integer $vk_city_id
 * @property integer $vk_id
 * @property string $name
 *
 * @property VkCity $vkCity
 */
class VkSchool extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vk_school';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vk_city_id', 'vk_id'], 'required'],
            [['vk_city_id', 'vk_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['vk_id'], 'unique'],
            [['vk_city_id'], 'exist', 'skipOnError' => true, 'targetClass' => VkCity::className(), 'targetAttribute' => ['vk_city_id' => 'vk_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'vk_city_id' => 'Vk City ID',
            'vk_id' => 'Vk ID',
            'name' => 'Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVkCity()
    {
        return $this->hasOne(VkCity::className(), ['vk_id' => 'vk_city_id']);
    }
}