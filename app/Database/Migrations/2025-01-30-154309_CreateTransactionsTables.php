<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreateTransactionsTables extends Migration
{
    public function up()
    {
        // Table transactions
        $this->forge->addField([
            'id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true
            ],
            'code' => [
                'type' => 'VARCHAR',
                'constraint' => 16,
                'null' => false,
                'unique' => true
            ],
            'type' => [
                'type' => "ENUM('income', 'withdrawal')",
                'default' => 'income',
                'null' => false
            ],
            'status' => [
                'type' => "ENUM('pending', 'paid', 'refunded')",
                'default' => 'pending',
                'null' => false
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'tenant_code' => [
                'type' => 'VARCHAR',
                'constraint' => 6,
                'null' => true
            ],
            'user_id' => [
                'type' => 'INT',
                'null' => true
            ],
            'amount_items' => [
                'type' => 'DECIMAL',
                'constraint' => '12',
                'null' => false
            ],
            'shipping_fee' => [
                'type' => 'DECIMAL',
                'constraint' => '12',
                'default' => 0.00,
                'null' => true
            ],
            'discount' => [
                'type' => 'DECIMAL',
                'constraint' => '12',
                'default' => 0.00,
                'null' => true
            ],
            'subtotal' => [
                'type' => 'DECIMAL',
                'constraint' => '12',
                'default' => 0.00,
                'null' => false
            ],
            'tax' => [
                'type' => 'DECIMAL',
                'constraint' => '12',
                'default' => 0.00,
                'null' => true
            ],
            'payment_fee' => [
                'type' => 'DECIMAL',
                'constraint' => '12',
                'default' => 0.00,
                'null' => true
            ],
            'total' => [
                'type' => 'DECIMAL',
                'constraint' => '12',
                'null' => false
            ],
            'payment_method' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true
            ],
            'payment_link' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
                'default' => new RawSql('current_timestamp()')
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true
            ]
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addKey('tenant_id');
        $this->forge->createTable('transactions');

        // Table transaction_items
        $this->forge->addField([
            'id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true
            ],
            'transaction_id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => false
            ],
            'item_id' => [
                'type' => 'INT',
                'null' => false
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false
            ],
            'type' => [
                'type' => 'VARCHAR',
                'constraint' => 25,
                'null' => false
            ],
            'price' => [
                'type' => 'DECIMAL',
                'constraint' => '12',
                'null' => false
            ],
            'quantity' => [
                'type' => 'INT',
                'unsigned' => true,
                'default' => 1,
                'null' => false
            ],
            'subtotal' => [
                'type' => 'DECIMAL',
                'constraint' => '12',
                'null' => false
            ],
            'meta' => [
                'type' => 'TEXT',
                'null' => true
            ],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('transaction_items');

        // Table transaction_logs
        $this->forge->addField([
            'id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true
            ],
            'transaction_id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => false
            ],
            'event' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true
            ],
            'data' => [
                'type' => 'JSON',
                'null' => true
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
                'default' => new RawSql('current_timestamp()')
            ]
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('transaction_logs');

        // Table transaction_webhook_logs
        $this->forge->addField([
            'id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true
            ],
            'code' => [
                'type' => 'varchar',
                'constraint' => 16,
                'null' => false
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
                'default' => new RawSql('current_timestamp()')
            ],
            'payload' => [
                'type' => 'JSON',
                'null' => true
            ],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('transaction_webhook_logs');
    }

    public function down()
    {
        $this->forge->dropTable('transaction_webhook_logs');
        $this->forge->dropTable('transaction_logs');
        $this->forge->dropTable('transaction_items');
        $this->forge->dropTable('transactions');
    }
}
