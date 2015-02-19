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
 
// this is the configuration file


define('DEBUG_LEVEL', LogLevel::INFO);
/*
 * possible values (priority in that order):
 * 
 * LogLevel::EMERGENCY,
 * LogLevel::ALERT
 * LogLevel::CRITICAL
 * LogLevel::ERROR
 * LogLevel::WARNING
 * LogLevel::NOTICE
 * LogLevel::INFO
 * LogLevel::DEBUG
 * 
 */

define('DOWNLOAD_FOLDER', "");
/*
 * if left empty, downloads will be saved into 'downloads'-subdirectory of rssFD-mainfolder.
 * must be legal foldername und must be writable for php. No / at the end!
 * 
 */