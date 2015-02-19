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

// $this->logger->debug(get_class($this) .": msg");
 
class FeedMaintainer
{
    private $logger;
    private $basedir;
    private $dlFolderName = "downloads";
    private $dlFolderPath;
    private $feeds = [];
    
    public function __construct($basedir, $logger, $altDlFolder = "") {
        $this->basedir = $basedir;
        $this->logger = $logger;
        
        //$this->logger->debug(get_class($this) .": konstruiere.");
        
        if(strlen($altDlFolder) == 0) {
            $this->dlFolderPath = $this->basedir . DIRECTORY_SEPARATOR . $this->dlFolderName;
            $this->logger->debug(get_class($this) .": default download folder.");
        }
        else {
            $this->dlFolderPath = $altDlFolder;
            $this->logger->debug(get_class($this) .": different download folder: ". $this->dlFolderPath);
        }
        
        //check the installation
        if(!$this->install()) {
            throw new Exception("Feedverwaltung konnte nicht geladen werden. Siehe Fehlerlog.");
        }
        $this->logger->debug(get_class($this) .": installation done.\t[ok]");
        
        //load the plugins into array
        $this->logger->debug(get_class($this) .": Load Plugins...");
        $count = 0;
        foreach(glob($this->basedir . DIRECTORY_SEPARATOR ."plugins". DIRECTORY_SEPARATOR ."*.php") as $plugin) {
            $classname = basename($plugin,".php");
            $feed = new $classname($this->logger);
            
            $feed->install($this->dlFolderPath);
            if($feed->is_installed()) {
                array_push($this->feeds, $feed);
                $count++;
            }
            else {
                $this->logger->warning(get_class($this) .": Plugin '". $classname ."' couln't be loaded.\t[warning]");
            }
            
        }
        $this->logger->debug(get_class($this) .": $count Plugin(s) loaded.\t[ok]");
    }
    
    private function install() {
        $error = false;
        $this->logger->debug(get_class($this) .": check install...");
        
        // do some checks
        
        //downloadfolder
        if(is_writable($this->dlFolderPath)) {
            $this->logger->debug(get_class($this) .": Downloadfolder\t[ok]");
        }
        else {
            if(!mkdir($this->dlFolderPath)) {
                $error = true;
                $this->logger->error(get_class($this) .": Downloadfolder couldn't be created.\t[error]");
            }
            else {
                $this->logger->debug(get_class($this) .": Downloadfolder created\t[ok]");
                if(is_writable($this->dlFolderPath)) {
                    $this->logger->debug(get_class($this) .": Downloadfolder writable\t[ok]");
                }
                else {
                    $error = true;
                    $this->logger->error(get_class($this) .": Downloadfolder not writable.\t[error]");
                }
            }
        }
        
        // PHP functions
        if(!function_exists('curl_init')) {
            $error = true;
            $this->logger->error(get_class($this) ." PHP-Plugin cURL is not active.\t[error]");
        }
        
        return !$error;
    }
    
    public function loadFeeds() {
        foreach($this->feeds as $feed) {
            $feed->loadFileList();
            $feed->download();
        }
    }
}
