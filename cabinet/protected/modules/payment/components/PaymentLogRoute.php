<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 31.10.13
 * Time: 9:58
 */

class PaymentLogRoute extends CFileLogRoute{

    protected function processLogs($logs) {
        $data = array();
        foreach($logs as $log) {
            if (!isset($data[$log[2]])) {
                $data[$log[2]] = '';
            }
            $data[$log[2]] .= $this->formatLogMessage($log[0],$log[1],$log[2],$log[3]);
        }
        foreach($data as $cat=>$logData) {
            $logFile=$this->getLogPath().DIRECTORY_SEPARATOR.$cat.'.log';
            if(@filesize($logFile)>$this->getMaxFileSize()*1024) {
                $this->rotateFiles();
            }
            @file_put_contents($logFile, $logData, FILE_APPEND | LOCK_EX);
        }
    }
} 