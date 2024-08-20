<?php

namespace Heroic\Config;

use CodeIgniter\Config\BaseConfig;

class Theme extends BaseConfig
{
    public string $frontendTheme = 'mobilekit';
    public string $adminTheme = 'admin';

    public string $frontendThemePath;
    public string $adminThemePath;

    public string $frontendThemeURL;
    public string $adminThemeURL;

    public function __construct()
    {
        $this->frontendThemePath = 'template/'.$this->frontendTheme.'/';
        $this->adminThemePath = 'template/'.$this->adminTheme.'/';

        $this->frontendThemeURL = base_url($this->frontendTheme.'/');
        $this->adminThemeURL = base_url($this->adminTheme.'/');
    }
}
