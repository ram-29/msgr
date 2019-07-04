<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%thread_member_config}}`.
 */
class m190402_013752_create_thread_member_config_table extends Migration
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
        if (!in_array('thread_member_config', $tables))  { 
        if ($dbType == "mysql") {
            $this->createTable('{{%thread_member_config}}', [
                'id' => 'CHAR(36) NOT NULL',
                0 => 'PRIMARY KEY (`id`)',
                'thread_id' => 'CHAR(36) NOT NULL',
                'member_id' => 'CHAR(36) NOT NULL',
                'is_muted' => 'TINYINT(1) NOT NULL',
            ], $tableOptions_mysql);
        }
        }
         
         
        $this->createIndex('idx_thread_id_8094_00','thread_member_config','thread_id',0);
        $this->createIndex('idx_member_id_8094_01','thread_member_config','member_id',0);
         
        $this->execute('SET foreign_key_checks = 0');
        $this->addForeignKey('fk_member_809_00','{{%thread_member_config}}', 'member_id', '{{%member}}', 'id', 'CASCADE', 'CASCADE' );
        $this->addForeignKey('fk_thread_809_01','{{%thread_member_config}}', 'thread_id', '{{%thread}}', 'id', 'CASCADE', 'CASCADE' );
        $this->execute('SET foreign_key_checks = 1;');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190402_013752_create_thread_member_config_table cannot be reverted.\n";

        $this->execute('SET foreign_key_checks = 0');
        $this->execute('DROP TABLE IF EXISTS `thread_member_config`');
        $this->execute('SET foreign_key_checks = 1;');
    }
}
