<?php

use yii\db\Migration;

/**
 * Handles the creation of table `product`.
 */
class m180810_041854_create_product_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('product', [
            'id' => $this->primaryKey(),
            'name' => $this->string (60)->notNull (),
            'quantity' => $this->integer ()
        ]);

        if (strpos($this->db->dsn, 'first_client') !== false) {
            $this->insert ('product', [
                'name' => 'Product of First Client',
                'quantity' => 50
            ]);
        }

        if (strpos($this->db->dsn, 'second_client') !== false) {
            $this->insert ('product', [
                'name' => 'Product of Second Client',
                'quantity' => 15
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('product');
    }
}
