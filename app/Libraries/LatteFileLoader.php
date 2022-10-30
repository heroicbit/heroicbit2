<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace App\libraries;

use Latte;


/**
 * Template loader.
 */
class LatteFileLoader extends Latte\Loaders\FileLoader
{
    use Latte\Strict;

    private $module;

    /**
     * Returns template source code.
     */
    public function getContent($fileName): string
    {
        $viewPaths = [
            APPPATH . 'Views/',
        ];

        foreach ($viewPaths as $viewPath) {
            if (file_exists($viewPath . $fileName)) {
                $fileName = $viewPath . $fileName;
                break;
            }
        }

        if (!is_file($fileName)) {
            throw new Latte\RuntimeException("Missing template file '$fileName'.");
        } elseif ($this->isExpired($fileName, time())) {
            if (@touch($fileName) === false) {
                trigger_error("File's modification time is in the future. Cannot update it: " . error_get_last()['message'], E_USER_WARNING);
            }
        }
        
        return file_get_contents($fileName);
    }
}
