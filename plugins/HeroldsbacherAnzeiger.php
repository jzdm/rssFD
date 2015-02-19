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
 
class HeroldsbacherAnzeiger extends FeedAbstract
{
    public function __construct($logger) {
        $this->logger = $logger;
        $this->name = "Heroldsbacher Anzeiger"; // must be filename compliant!
        $this->feedURL = "http://www.heroldsbach.de/rathaus/amtsblatt/feed/RSS";
        
        $this->logger->debug(get_class($this) .": loading.");
    }
    
    public function loadFileList() {
        $this->logger->debug(get_class($this) .": Lade Dateiliste.");
        
        if(!($xmlPage = file_get_contents($this->feedURL)))
        {
            $this->logger->error($this->name .": Konnte Feed nicht laden");
            return false;
        }
        
        $xmlParse = simplexml_load_string($xmlPage);
        $count = 0;
	while(is_object($xmlParse->item[$count])) {
            //$this->logger->debug($this->name .": Lade Item Nr ". $count);
            
            $title = $xmlParse->item[$count]->title->__toString();
            $link = $xmlParse->item[$count]->link->__toString();

            /* Dateititel aufbereiten */
            $filename = explode(" - ",str_replace(" (Amtsblatt)","",mb_substr($title,4,NULL,"UTF-8")));
                /*
                $filename[0] beinhaltet Woche
                $filename[1] beinhaltet Datum/Jahr

                wird gleich gemacht:
                $filename[2] beinhaltet Timestamp wenn Datum
                $filename[3] beinhaltet Jahr
                */
            if(mb_strlen($filename[1],"UTF-8") < 5) { // nur Jahr
                array_push($filename,"");
                array_push($filename,$filename[1]);
            }
            else { //Datum
                array_push($filename,strtotime($filename[1]));
                array_push($filename,date("Y", $filename[2]));
            }
            $name = "Amtsblatt_Heroldsbach_". $filename[3] ."-". $filename[0] .".pdf";
            
            // add to fileList
            array_push($this->fileList,[
                "title" =>$title,
                "url" => $link,
                "filename" => $name
            ]);
            
            $count++;
        }
        $this->logger->debug(get_class($this) .": Dateiliste geladen. ". $count ." Elemente.");
    }
}
