<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreateTenantsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'code' => [
                'type' => 'VARCHAR',
                'constraint' => 6,
                'null' => false
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false
            ],
            'balance' => [
                'type' => 'DECIMAL',
                'constraint' => '12',
                'default' => 0.00,
                'null' => false
            ],
            'withdrawn' => [
                'type' => 'DECIMAL',
                'constraint' => '12',
                'default' => 0.00,
                'null' => false
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
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

        $this->forge->addKey('code', true); // Primary key
        $this->forge->createTable('tenants');

        $this->forge->addField([
            'id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true
            ],
            'tenant_code' => [
                'type' => 'VARCHAR',
                'constraint' => 6,
                'null' => false
            ],
            'amount' => [
                'type' => 'DECIMAL',
                'constraint' => '12',
                'null' => false
            ],
            'type' => [
                'type' => "ENUM('income', 'withdraw')",
                'null' => false
            ],
            'transaction_id' => [
                'type' => 'BIGINT',
                'null' => false
            ],
            'note' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'checksum' => [
                'type' => 'CHAR',
                'constraint' => 32,
                'null' => false
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
                'default' => new RawSql('CURRENT_TIMESTAMP')
            ]
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addKey('tenant_id');
        $this->forge->addUniqueKey(['tenant_code', 'transaction_id', 'checksum']);
        $this->forge->createTable('wallet_logs');
    }

    public function down()
    {
        $this->forge->dropTable('tenant_logs');
        $this->forge->dropTable('tenants');
    }
}