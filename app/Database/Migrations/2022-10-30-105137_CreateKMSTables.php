<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateKMSTables extends Migration
{
    public function up()
    {
        $db = db_connect();
        $db->query("CREATE TABLE `kms_posts` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `title` varchar(255) NOT NULL,
            `slug` varchar(255) NOT NULL,
            `content` longtext NOT NULL,
            `access_mode` enum('internal','public') NOT NULL DEFAULT 'internal',
            `cover` varchar(255) DEFAULT NULL,
            `owner` int(11) DEFAULT NULL,
            `status` enum('draft','publish','archived') NOT NULL DEFAULT 'draft',
            `verify_period` tinyint(4) NOT NULL DEFAULT 0,
            `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` timestamp NULL DEFAULT NULL,
            `deleted_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

        $db->query("CREATE TABLE `kms_post_topics` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `post_id` int(11) NOT NULL,
            `topic_id` int(11) NOT NULL,
            `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` timestamp NULL DEFAULT NULL,
            `deleted_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`),
            KEY `post_id` (`post_id`),
            KEY `topic_id` (`topic_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        
        $db->query("CREATE TABLE `kms_topics` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `title` varchar(255) NOT NULL,
            `slug` varchar(255) NOT NULL,
            `parent_id` int(11) DEFAULT NULL,
            `description` text DEFAULT NULL,
            `access_mode` enum('open','public','private','secret') NOT NULL DEFAULT 'open',
            `cover` varchar(255) DEFAULT NULL,
            `owner` int(11) DEFAULT NULL,
            `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` timestamp NULL DEFAULT NULL,
            `deleted_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    }

    public function down()
    {
        $db = db_connect();
        $db->query("DROP TABLE `kms_posts`;");
        $db->query("DROP TABLE `kms_topics`;");
        $db->query("DROP TABLE `kms_post_topics`;");
    }
}
