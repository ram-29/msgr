<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%thread_global_config}}`.
 */
class m190402_013018_create_thread_global_config_table extends Migration
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
        if (!in_array('thread_global_config', $tables))  { 
        if ($dbType == "mysql") {
            $this->createTable('{{%thread_global_config}}', [
                'id' => 'CHAR(36) NOT NULL',
                0 => 'PRIMARY KEY (`id`)',
                'name' => 'VARCHAR(200) NOT NULL',
                'color' => 'VARCHAR(200) NOT NULL DEFAULT \'#2196f3\'',
                'emoji' => 'VARCHAR(200) NOT NULL DEFAULT \':thumbsup:\'',
                'picx' => 'VARCHAR(200) NULL',
            ], $tableOptions_mysql);
        }
        }
         
         
        $this->execute('SET foreign_key_checks = 0');
        $this->addForeignKey('fk_thread_2556_00','{{%thread_global_config}}', 'id', '{{%thread}}', 'id', 'CASCADE', 'CASCADE' );
        $this->execute('SET foreign_key_checks = 1;');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190402_013018_create_thread_global_config_table cannot be reverted.\n";

        $this->execute('SET foreign_key_checks = 0');
        $this->execute('DROP TABLE IF EXISTS `thread_global_config`');
        $this->execute('SET foreign_key_checks = 1;');
    }
}
