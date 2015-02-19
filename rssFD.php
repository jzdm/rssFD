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

require_once(__DIR__ . DIRECTORY_SEPARATOR ."includes". DIRECTORY_SEPARATOR ."loader.php");

$error = false;
try {
    $feeds = new FeedMaintainer(BASEDIR, $logger,DOWNLOAD_FOLDER);
} catch  (Exception $e) {
    $error = true;
}

if($error) {
    $logger->error("Feedmaintainer couldn't be loaded. See log.");
    exit;
}

// everything ok, go on...

$logger->debug("Main: FeedMaintainer loaded.\t[ok]");

$feeds->loadFeeds();