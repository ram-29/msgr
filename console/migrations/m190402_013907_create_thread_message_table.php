<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%thread_message}}`.
 */
class m190402_013907_create_thread_message_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->safeDown();

        $tables = Yii::$app->db->schema->getTableNames();
        $dbType = $this->db->driverName;
        $tableOptions_mysql = "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        $tableOptions_mssql = "";
        $tableOptions_pgsql = "";
        $tableOptions_sqlite = "";
        /* MYSQL */
        if (!in_array('thread_message', $tables))  { 
        if ($dbType == "mysql") {
            $this->createTable('{{%thread_message}}', [
                'id' => 'CHAR(36) NOT NULL',
                0 => 'PRIMARY KEY (`id`)',
                'thread_id' => 'CHAR(36) NOT NULL',
                'member_id' => 'CHAR(36) NOT NULL',
                'type' => 'ENUM(\'MSG\',\'NOTIF\') NOT NULL DEFAULT \'MSG\'',
                'text' => 'LONGTEXT NULL',
                'file' => 'TEXT NULL',
                'file_name' => 'TEXT NULL',
                'file_type' => 'TEXT NULL',
                'created_at' => 'DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ',
                'deleted_by' => 'TEXT NULL',
            ], $tableOptions_mysql);
        }
        }
         
         
        $this->createIndex('idx_thread_id_5454_00','thread_message','thread_id',0);
        $this->createIndex('idx_member_id_5454_01','thread_message','member_id',0);
         
        $this->execute('SET foreign_key_checks = 0');
        $this->addForeignKey('fk_member_545_00','{{%thread_message}}', 'member_id', '{{%member}}', 'id', 'CASCADE', 'CASCADE' );
        $this->addForeignKey('fk_thread_545_01','{{%thread_message}}', 'thread_id', '{{%thread}}', 'id', 'CASCADE', 'CASCADE' );
        $this->execute('SET foreign_key_checks = 1;');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190402_013907_create_thread_message_table cannot be reverted.\n";

        $this->execute('SET foreign_key_checks = 0');
        $this->execute('DROP TABLE IF EXISTS `thread_message`');
        $this->execute('SET foreign_key_checks = 1;');
    }
}
