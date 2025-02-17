<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TenantSeeder extends Seeder
{
    public function run()
    {
        $this->db->query("INSERT INTO `tenants` 
        (`code`, `name`, `balance`, `withdrawn`, `created_at`, `updated_at`, `deleted_at`) VALUES
        ('PC.104',	'PC Cililin',	0.00,	0.00,	'2025-01-31 11:19:26',	NULL,	NULL),
        ('PC.105',	'PC Batujajar',	0.00,	0.00,	'2025-01-31 11:19:26',	NULL,	NULL),
        ('PC.107',	'PC Lembang',	0.00,	0.00,	'2025-01-31 11:19:26',	NULL,	NULL),
        ('PC.110',	'PC Cikalong Wetan',	0.00,	0.00,	'2025-01-31 11:19:26',	NULL,	NULL),
        ('PC.135',	'PC Ngamprah',	0.00,	0.00,	'2025-01-31 11:19:26',	NULL,	NULL),
        ('PC.152',	'PC Cihampelas',	0.00,	0.00,	'2025-01-31 11:19:26',	NULL,	NULL),
        ('PC.159',	'PC Cipeundeuy',	0.00,	0.00,	'2025-01-31 11:19:26',	NULL,	NULL),
        ('PC.172',	'PC Gununghalu',	0.00,	0.00,	'2025-01-31 11:19:26',	NULL,	NULL),
        ('PC.198',	'PC Rongga',	0.00,	0.00,	'2025-01-31 11:19:26',	NULL,	NULL),
        ('PC.26',	'PC Padalarang',	0.00,	0.00,	'2025-01-31 11:19:26',	NULL,	NULL),
        ('PC.31',	'PC Cipatat',	0.00,	0.00,	'2025-01-31 11:19:26',	NULL,	NULL),
        ('PD-26',	'PD Kabupaten Bandung Barat',	0.00,	0.00,	'2025-01-31 11:20:03',	NULL,	NULL),
        ('PP',	'PP Pemuda Persatuan Islam',	0.00,	0.00,	'2025-01-31 11:20:27',	NULL,	NULL),
        ('PW-1',	'PW Jawa Barat',	0.00,	0.00,	'2025-01-31 11:20:18',	NULL,	NULL);");
    }
}
