<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Theme extends BaseConfig
{
    public string $frontendTheme = 'default';
    public string $adminTheme = 'admin';

    public string $frontendThemeURL;
    public string $adminThemeURL;

    public function __construct()
    {
        $this->frontendThemeURL = base_url($this->frontendTheme.'/');
        $this->adminThemeURL = base_url($this->adminTheme.'/');
    }
}
