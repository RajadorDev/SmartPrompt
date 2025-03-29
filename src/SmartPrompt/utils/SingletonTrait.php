<?php

declare (strict_types=1);

/***
 *   
 * Rajador Developer
 * 
 * ▒█▀▀█ ░█▀▀█ ░░░▒█ ░█▀▀█ ▒█▀▀▄ ▒█▀▀▀█ ▒█▀▀█ 
 * ▒█▄▄▀ ▒█▄▄█ ░▄░▒█ ▒█▄▄█ ▒█░▒█ ▒█░░▒█ ▒█▄▄▀ 
 * ▒█░▒█ ▒█░▒█ ▒█▄▄█ ▒█░▒█ ▒█▄▄▀ ▒█▄▄▄█ ▒█░▒█
 * 
 * GitHub: https://github.com/RajadorDev
 * 
 * Discord: rajadortv
 * 
 * 
**/

namespace SmartPrompt\utils;

use RuntimeException;

trait SingletonTrait 
{

    /** @var self */
    private static $instance = null;

    /**
     * Set instance
     *
     * @param self $instance
     * @return void
     */
    private static function setInstance(self $instance)
    {
        self::$instance = $instance;
    }

    /**
     * Get object instance
     *
     * @return self
     */
    public static function getInstance() : self 
    {
        if (self::$instance)
        {
            return self::$instance;
        }
        throw new RuntimeException('Trying to acess the instance before the inicialization');
    }

}