<?php

use yii\db\Migration;

/**
 * Class m190422_012537_add_user_name
 */
class m190422_012537_add_user_name extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->addColumn('member', 'username', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190422_012537_add_user_name cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190422_012537_add_user_name cannot be reverted.\n";

        return false;
    }
    */
}
