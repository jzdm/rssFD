<?php

/* 
 * rssFD (rssFeedDownloader)
 * by jzdm - https://jzdm.de/ - mail@jzdm.de
 * 
 * This work is licensed under the Creative Commons Attribution-ShareAlike 4.0
 * International License. To view a copy of this license,
 * visit http://creativecommons.org/licenses/by-sa/4.0/.
 * 
 */

class LogLevel
{
    const EMERGENCY = 'emergency';
    const ALERT = 'alert';
    const CRITICAL = 'critical';
    const ERROR = 'error';
    const WARNING = 'warning';
    const NOTICE = 'notice';
    const INFO = 'info';
    const DEBUG = 'debug';
}
 
class Logger
{
    private $currentLoglevel;
    
    private $logLevels = array(
        LogLevel::EMERGENCY => 0,
        LogLevel::ALERT => 1,
        LogLevel::CRITICAL => 2,
        LogLevel::ERROR => 3,
        LogLevel::WARNING => 4,
        LogLevel::NOTICE => 5,
        LogLevel::INFO => 6,
        LogLevel::DEBUG => 7
    );
    
    public function __construct($newLogLevel = LogLevel::DEBUG) {
        $this->currentLogLevel = $newLogLevel;
    }
    
    public function setLogLevel($newLogLevel) {
        $this->currentLogLevel = $newLogLevel;
    }
    
    public function logLevel() {
        //$level = $this->currentLogLevel;
        
        echo "Log Level is: [". $this->currentLogLevel ."]. As Number: '". $this->logLevels[$this->currentLogLevel] ."'\n";
    }
    
    private function writeLog($msg, $level) {
        if($this->logLevels[$level] > $this->logLevels[$this->currentLogLevel]) {
            // nothing to log, return
            return;
        }
        
        // output the log
        echo "[". strtoupper($level) ."] ". $msg ."\n";
    }
    
    public function emergency($msg) {
        $this->writeLog($msg, LogLevel::EMERGENCY);
    }
    public function alert($msg) {
        $this->writeLog($msg, LogLevel::ALERT);
    }
    public function critical($msg) {
        $this->writeLog($msg, LogLevel::CRITICAL);
    }
    public function error($msg) {
        $this->writeLog($msg, LogLevel::ERROR);
    }
    public function warning($msg) {
        $this->writeLog($msg, LogLevel::WARNING);
    }
    public function notice($msg) {
        $this->writeLog($msg, LogLevel::NOTICE);
    }
    public function info($msg) {
        $this->writeLog($msg, LogLevel::INFO);
    }
    public function debug($msg) {
        $this->writeLog($msg, LogLevel::DEBUG);
    }
}
