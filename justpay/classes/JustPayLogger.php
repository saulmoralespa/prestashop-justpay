<?php


class JustPayLogger extends AbstractLogger
{
    /**
     * @param $message
     * @param $level
     */
    protected function logMessage($message, $level)
    {
        $formatted_message = '*'.$this->level_value[$level].'* '."\t".date('Y/m/d - H:i:s').': '.$message."\r\n";
        $hash = Tools::encrypt(_PS_MODULE_DIR_ . 'justpay/logs/');
        $file = dirname(__FILE__).'/../logs/justpay_'.$hash.'.log';

        return (bool)file_put_contents($file, $formatted_message, FILE_APPEND);
    }
}