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

class Autoloader {
    public static function load($klassenname) {
        $basedir = BASEDIR;
        
        $pfad1 = $basedir . DIRECTORY_SEPARATOR ."plugins". DIRECTORY_SEPARATOR . $klassenname .'.php';
        $pfad2 = $basedir . DIRECTORY_SEPARATOR ."includes". DIRECTORY_SEPARATOR . $klassenname .'.php';
        
        if (file_exists($pfad1)) {
            require_once($pfad1);
        }
        else if (file_exists($pfad2)) {
            require_once($pfad2);
        }
        else {
            return false;
        }
    }
}

spl_autoload_register(array('Autoloader', 'load'));
