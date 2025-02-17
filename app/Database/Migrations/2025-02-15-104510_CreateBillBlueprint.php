<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBillBlueprint extends Migration
{
    public function up()
    {
        $this->db->query("CREATE TABLE `md_bill_blueprints` (
        `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
        `title` VARCHAR(255) NOT NULL,
        `description` VARCHAR(255) NULL,
        `start_date` date NOT NULL,
        `amount` int NOT NULL,
        `target_pc` int NULL,
        `target_pd` int NULL,
        `target_pw` int NULL,
        `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
        `updated_at` datetime NULL,
        `deleted_at` datetime NULL
        );");
    }

    public function down()
    {
        $this->db->query("DROP TABLE `md_bill_blueprints`;");
    }
}
