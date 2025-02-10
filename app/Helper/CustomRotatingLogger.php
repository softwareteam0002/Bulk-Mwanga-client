<?php
/**
 * Created by PhpStorm.
 * User: Memory
 * Date: 10/09/2019
 * Time: 02:11
 */

namespace App\Helper;


use Monolog\Logger;

class CustomRotatingLogger
{

    /**
     * This class will create a custom Monolog instance.
     *
     * @param array $config
     * @return Logger
     */
    public function __invoke(array $config)
    {
        return new Logger("custom_daily",[new CustomRotatingFileHandler(
            $config['path'],
            $config['days'],
            Logger::DEBUG,
            true,
            null,
            false,
            !empty($config['max_file_size'])?$config['max_file_size']:2
        )]);
    }

}