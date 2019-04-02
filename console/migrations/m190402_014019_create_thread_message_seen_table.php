<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%thread_message_seen}}`.
 */
class m190402_014019_create_thread_message_seen_table extends Migration
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
        if (!in_array('thread_message_seen', $tables))  { 
        if ($dbType == "mysql") {
            $this->createTable('{{%thread_message_seen}}', [
                'id' => 'CHAR(36) NOT NULL',
                0 => 'PRIMARY KEY (`id`)',
                'thread_message_id' => 'CHAR(36) NOT NULL',
                'member_id' => 'CHAR(36) NOT NULL',
                'seen_at' => 'DATETIME NOT NULL',
            ], $tableOptions_mysql);
        }
        }
         
         
        $this->createIndex('idx_thread_message_id_9736_00','thread_message_seen','thread_message_id',0);
        $this->createIndex('idx_member_id_9736_01','thread_message_seen','member_id',0);
         
        $this->execute('SET foreign_key_checks = 0');
        $this->addForeignKey('fk_thread_message_9733_00','{{%thread_message_seen}}', 'thread_message_id', '{{%thread_message}}', 'id', 'CASCADE', 'CASCADE' );
        $this->addForeignKey('fk_member_9733_01','{{%thread_message_seen}}', 'member_id', '{{%member}}', 'id', 'CASCADE', 'CASCADE' );
        $this->execute('SET foreign_key_checks = 1;');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190402_014019_create_thread_message_seen_table cannot be reverted.\n";

        $this->execute('SET foreign_key_checks = 0');
        $this->execute('DROP TABLE IF EXISTS `thread_message_seen`');
        $this->execute('SET foreign_key_checks = 1;');
    }
}
