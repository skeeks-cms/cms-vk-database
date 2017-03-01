<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 28.08.2015
 */
use yii\db\Schema;
use yii\db\Migration;

class m170227_140601_create_table__vk_city extends Migration
{
    public function safeUp()
    {
        $tableName  = 'vk_city';
        $tableExist = $this->db->getTableSchema($tableName, true);
        if ($tableExist)
        {
            return true;
        }

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable($tableName, [
            'id'                    => $this->primaryKey(),

            'vk_country_id'            => $this->integer()->notNull(),
            'vk_region_id'             => $this->integer(),

            'vk_id'                 => $this->integer()->notNull()->unique(),

            'name'                  => $this->string(255),
            'area_name'             => $this->string(255),
            'region_name'           => $this->string(255),

        ], $tableOptions);


        $this->createIndex('name', $tableName, 'name');

        $this->addCommentOnTable($tableName, '');

        $this->addForeignKey(
            "{$tableName}__vk_country_id", $tableName,
            'vk_country_id', '{{%vk_country}}', 'vk_id', 'CASCADE', 'CASCADE'
        );

        $this->addForeignKey(
            "{$tableName}__vk_region_id", $tableName,
            'vk_region_id', '{{%vk_region}}', 'vk_id', 'SET NULL', 'SET NULL'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey("{$tableName}__vk_country_id", $tableName);
        $this->dropForeignKey("{$tableName}__vk_region_id", $tableName);
        $this->dropTable($tableName);
    }
}