<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class SidebarMenu extends BaseConfig
{
    public array $menu = [
        [
            'url' => 'admin',
            'caption' => 'Dashboard',
            'icon' => 'fa-solid fa-gauge',
        ],
        [
            'url' => '#',
            'caption' => 'Users',
            'icon' => 'fa-solid fa-user-group',
            'children' => [
                [
                    'url' => 'admin/users',
                    'caption' => 'Users',
                    'icon' => 'fa-solid fa-users',
                    'permission' => 'users.View'
                ],
                [
                    'url' => 'admin/roles',
                    'caption' => 'Roles',
                    'icon' => 'fa-solid fa-user-tag',
                ]
            ]
        ]
    ];
}
