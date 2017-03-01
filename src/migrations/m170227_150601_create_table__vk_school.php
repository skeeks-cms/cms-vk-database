<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 28.08.2015
 */
use yii\db\Schema;
use yii\db\Migration;

class m170227_150601_create_table__vk_school extends Migration
{
    public function safeUp()
    {
        $tableName  = 'vk_school';
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

            'vk_city_id'            => $this->integer()->notNull(),

            'vk_id'                 => $this->integer()->notNull()->unique(),
            'name'                  => $this->string(255),

        ], $tableOptions);


        $this->createIndex('name', $tableName, 'name');

        $this->addCommentOnTable($tableName, '');

        $this->addForeignKey(
            "{$tableName}__vk_city_id", $tableName,
            'vk_city_id', '{{%vk_city}}', 'vk_id', 'CASCADE', 'CASCADE'
        );

    }

    public function safeDown()
    {
        $this->dropForeignKey("{$tableName}__vk_city_id", $tableName);
        $this->dropTable($tableName);
    }
}