<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePesantren extends Migration
{
    public function up()
    {
        $db = db_connect();
        $db->query("CREATE TABLE `pesantren` (
            `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
            `nama_pesantren` varchar(255) NOT NULL,
            `kode_pesantren` varchar(255) NOT NULL,
            `database` varchar(255) NOT NULL,
            `date_join` datetime DEFAULT NULL,
            `status` enum('pending','active','expired') NOT NULL DEFAULT 'pending',
            `enabled_modules` text DEFAULT NULL,
            `expired_at` datetime DEFAULT NULL,
            `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
            `updated_at` datetime DEFAULT NULL,
            `deleted_at` datetime DEFAULT NULL,
            PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");
    }

    public function down()
    {
        $db = db_connect();
        $db->query("DROP TABLE `pesantren`;");
    }
}
