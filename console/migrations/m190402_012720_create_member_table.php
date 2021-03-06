<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%member}}`.
 */
class m190402_012720_create_member_table extends Migration
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
        if (!in_array('member', $tables))  { 
        if ($dbType == "mysql") {
            $this->createTable('{{%member}}', [
                'id' => 'CHAR(36) NOT NULL',
                0 => 'PRIMARY KEY (`id`)',
                'intranet_id' => 'INT(11) NULL',
                'name' => 'VARCHAR(200) NOT NULL',
                'sex' => 'ENUM(\'M\',\'F\') NOT NULL DEFAULT \'M\'',
                'status' => 'ENUM(\'ACTIVE\',\'INACTIVE\') NOT NULL DEFAULT \'ACTIVE\'',
                'joined_at' => 'DATETIME NULL DEFAULT CURRENT_TIMESTAMP ',
                'logged_at' => 'DATETIME NULL DEFAULT CURRENT_TIMESTAMP ',
                'username' => 'VARCHAR(200) NULL',
                'gravatar' => 'VARCHAR(200) NULL',
                'email' => 'VARCHAR(200) NULL',
                'mobile_phone' => 'VARCHAR(200) NULL',
                'office' => 'VARCHAR(200) NULL',
            ], $tableOptions_mysql);
        }
        }        
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190402_012720_create_member_table cannot be reverted.\n";

        $this->execute('SET foreign_key_checks = 0');
        $this->execute('DROP TABLE IF EXISTS `member`');
        $this->execute('SET foreign_key_checks = 1;');
    }
}
