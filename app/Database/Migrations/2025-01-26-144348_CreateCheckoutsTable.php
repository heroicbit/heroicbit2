<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreateCheckoutsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'code' => [
                'type' => 'VARCHAR',
                'constraint' => 16,
                'null' => false
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'paid'],
                'default' => 'pending',
                'null' => false
            ],
            'tenant_code' => [
                'type' => 'varchar',
                'constraint' => 6,
                'null' => true
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true
            ],
            'guest_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true
            ],
            'guest_contact' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true
            ],
            'guest_address' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'items' => [
                'type' => 'LONGTEXT',
                'null' => false
            ],
            'item_amount' => [
                'type' => 'BIGINT',
                'null' => false
            ],
            'payment_method' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
                'null' => false
            ],
            'payment_fee' => [
                'type' => 'INT',
                'null' => false,
                'default' => 0
            ],
            'total' => [
                'type' => 'BIGINT',
                'null' => false
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => false,
                'default' => new RawSql('current_timestamp()'),
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

        $this->forge->addKey('code', true); // Primary key
        $this->forge->addKey('user_id');
        $this->forge->addKey('tenant_code');

        $this->forge->createTable('checkouts');
    }

    public function down()
    {
        $this->forge->dropTable('checkouts');
    }
}
