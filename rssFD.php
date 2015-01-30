<?php
	/****************************************************
	*		rssFileDownloader (rrsFD) by jzdm			*
	*				visit https://jzdm.de/				*
	*	this software comes with absolutely no warranty	*
	*		© 2015 jzdm									*
	****************************************************/
	/*
		version 0.1 alpha	2015-01-30
	*/
	
	/* Verzeichnisse */
	$logDir		= __DIR__ ."/log";
	$downDir	= __DIR__ ."/downloads";
	
	if(!is_dir($logDir))
		if(!mkdir($logDir))
			die("Fehler: Log-Verzeichnis konnte nicht angelegt werden.\n");
	if(!is_dir($downDir))
		if(!mkdir($downDir))
			die("Fehler: Download-Verzeichnis konnte nicht angelegt werden.\n");
	
	/* Dateien */
	$logFile	= $logDir ."/output.log";
	$dbFile		= $logDir ."/lasttimes.log";
	
	if(!is_file($logFile))
		if(file_put_contents($logFile,'') === FALSE)
			die("Fehler: Log-Datei konnte nicht angelegt werden.\n");
	if(!is_file($dbFile))
		if(file_put_contents($dbFile,'') === FALSE)
			die("Fehler: Datenbank-Datei konnte nicht angelegt werden.\n");
	
	
	
	/* Einlesen des letzten Feeds */
	$lasttitle = file_get_contents($dbFile);
	if($lasttitle === FALSE) {
		echo "Fehler! lasttitle\n";
		exit;
	}
	
	/* RSS-Feed einlesen */
	if(!($xmlPage = file_get_contents("http://www.heroldsbach.de/rathaus/amtsblatt/feed/RSS")))
	{
		echo "Fehler! xmlPage\n";
		exit;
	}
	$xmlParse = simplexml_load_string($xmlPage);
	
	$cnt_items = $xmlParse->item->count();
	$count = 0;
	while(is_object($xmlParse->item[$count]) && $xmlParse->item[$count]->title != $lasttitle) {
		$title = $xmlParse->item[$count]->title;
		$link = $xmlParse->item[$count]->link;
		
		/* Dateititel aufbereiten */
		$filename = mb_substr($title,4,NULL,"UTF-8");
		$filename = str_replace(" (Amtsblatt)","",$filename);
				//echo "". $filename ."\n";
		$filename = explode(" - ",$filename);
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
		//print_r($filename);
		$name = "Amtsblatt_Heroldsbach_". $filename[3] ."-". $filename[0] .".pdf";
		
		//echo "". $name ."\n";
		
		/* beigefügte Datei runterladen */
		if(function_exists('curl_init')) {
			$ch = curl_init($link);
			if($fh = fopen($downDir ."/". $name,"w")) {
				curl_setopt($ch, CURLOPT_FILE, $fh);
				curl_setopt($ch, CURLOPT_TIMEOUT, 3600);
				if(curl_exec($ch))
					echo $name ." heruntergeladen.\n";
				else
					echo $name ." konnte nicht heruntergeladen werden.\n";
				fclose($fh);
			}
			else
				echo "Datei konnte nicht angelegt werden. Bitte Downloadverzeichnis beschreibbar machen.\n";
			
			curl_close($ch);
		}
		else {
			die("\n\nBitte PHP-Erweiterung cURL aktivieren...\n");
		}
		
		$count++;
	}
	
	/* Letzten Feed ausgeben */
	file_put_contents($dbFile,$xmlParse->item[0]->title);
	
	echo "\n";
	
	/* Remove when done! */
	//file_put_contents($dbFile,"");
?>