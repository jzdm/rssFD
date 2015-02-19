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
 
define('BASEDIR',dirname(__DIR__));

require_once(BASEDIR . DIRECTORY_SEPARATOR ."includes". DIRECTORY_SEPARATOR ."Autoloader.php");
require_once(BASEDIR . DIRECTORY_SEPARATOR ."includes". DIRECTORY_SEPARATOR ."Logger.php");

require_once(BASEDIR . DIRECTORY_SEPARATOR ."config.php");

$logger = new Logger(DEBUG_LEVEL);
