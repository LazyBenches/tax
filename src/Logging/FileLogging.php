<?php
/**
 * Created by PhpStorm.
 * Email:jwy226@qq.com
 * User: LazyBench
 * Date: 2020/7/16
 * Time: 11:36
 */

namespace LazyBench\Tax\Logging;

class FileLogging implements LoggingInterface
{
    protected $logFile;

    public function __construct($config)
    {
        $this->logFile = $config['filePath'];
    }

    /**
     * Author:LazyBench
     *
     * @param $handle
     */
    public function handle(array $handle)
    {
        error_log(json_encode($handle), 3, $this->logFile);
    }
}