<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%thread}}`.
 */
class m190402_012918_create_thread_table extends Migration
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
        if (!in_array('thread', $tables))  { 
        if ($dbType == "mysql") {
            $this->createTable('{{%thread}}', [
                'id' => 'CHAR(36) NOT NULL',
                0 => 'PRIMARY KEY (`id`)',
                'type' => 'ENUM(\'SIMPLE\',\'GROUP\') NOT NULL DEFAULT \'SIMPLE\'',
                'created_at' => 'DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ',
            ], $tableOptions_mysql);
        }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190402_012918_create_thread_table cannot be reverted.\n";

        $this->execute('SET foreign_key_checks = 0');
        $this->execute('DROP TABLE IF EXISTS `thread`');
        $this->execute('SET foreign_key_checks = 1;');
    }
}
