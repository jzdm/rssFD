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

class FeedAbstract
{
    protected $logger;
    protected $name;
    protected $feedURL;
    protected $fileList = [];
    protected $installed = false;
    protected $dlFolder;


    public function install($dlMainFolder) {
        $this->dlFolder = $dlMainFolder . DIRECTORY_SEPARATOR . $this->name;
        
        if(is_writeable($this->dlFolder)) {
            $this->logger->debug(get_class($this) .": Downloadfolder writable.\t[ok]");
            $this->installed = true;
        }
        else {
            if(!mkdir($this->dlFolder)) {
                $this->logger->error(get_class($this) .": Downloadfolder could't be created.\t[error]");
            }
            else {
                $this->logger->debug(get_class($this) .": Downloadfolder writable.\t[ok]");
                $this->installed = true;
            }
        }
    }
    
    public function is_installed() {
        return $this->installed;
    }
    
    public function download() {
        foreach($this->fileList as $file) {
            $fullname = $this->dlFolder . DIRECTORY_SEPARATOR . $file["filename"];
            
            if(is_file($fullname)) {
                $this->logger->info(get_class($this) .": ". $file["filename"] ." already exists. skip.");
                continue;
            }
            $ch = curl_init($file["url"]);
            $fh = fopen($fullname,"w");
            if($fh) {
                curl_setopt($ch, CURLOPT_FILE, $fh);
                curl_setopt($ch, CURLOPT_TIMEOUT, 3600);
		if(curl_exec($ch)) {
                    $this->logger->info(get_class($this) .": ". $file["filename"] ." downloaded.");
                }
                else {
                    $this->logger->info(get_class($this) .": error while downloading ". $file["filename"] .".");
                }
            }
            else {
                $this->logger->warning(get_class($this) .": ". $file["filename"] ." cloudn't be written.");
            }
            curl_close($ch);
        } // end foreach
    }
    
}
