# Readme #
## rssFD - rss File Downloader ##

### 1. About ###
rssFD is a software to automatically download files from a rss feed. It's written in PHP and can be run from the console/shell.

The software is written by jzdm \[ <mail@jzdm.de> - <https://jzdm.de/> \] and is licensed under the *Creative Commons Attribution-ShareAlike 4.0 International License* (visit <http://creativecommons.org/licenses/by-sa/4.0/>).

### 2. How to use ###
Open shell in installation directory and simply call `php rssFD.php`. The software then will check for all available plugins, initiate them, extract a file list from each rss feed und finally downloads them into the given folder (see configuration).

To automatically update the feed, create a cronjob which runs rssFD once a day / week or whatever you need.

### 3. Configuration ###
Edit the `config.php` file.

At the moment you can change:

* **Setting:** `DEBUG_LEVEL`  
  **Possible Values:**  
  &nbsp;&nbsp;&nbsp;&nbsp;`LogLevel::EMERGENCY`  
  &nbsp;&nbsp;&nbsp;&nbsp;`LogLevel::ALERT`  
  &nbsp;&nbsp;&nbsp;&nbsp;`LogLevel::CRITICAL`  
  &nbsp;&nbsp;&nbsp;&nbsp;`LogLevel::ERROR`  
  &nbsp;&nbsp;&nbsp;&nbsp;`LogLevel::WARNING`  
  &nbsp;&nbsp;&nbsp;&nbsp;`LogLevel::NOTICE`  
  &nbsp;&nbsp;&nbsp;&nbsp;`LogLevel::INFO`  
  &nbsp;&nbsp;&nbsp;&nbsp;`LogLevel::DEBUG`  
  **Default:** `LogLevel::INFO`  
  **Desrciption:** Here you can change the verbosity of the software. `DEBUG` is just needed in development. Usualy the software returns what files are written or already exist in the `INFO` level.

* **Setting:** `DOWNLOAD_FOLDER`  
  **Possible Values:**  
  &nbsp;&nbsp;&nbsp;&nbsp;any legal folder path on your system.  
  **Default:** `""`  
  **Desrciption:** Downloads will be saved in subfolders of that directory. If left empty, it will be the `rssFD/downloads` folder. rssFD will create a subfolder for every 'Plugin' and will save its files into that folder.  Of course the user running rssFD needs write permission to the folder. 

### 4. Plugins ###
'Plugins' are little PHP code snippets in the `plugins` directory. Everyplugin takes care of a rss feed. It downloads the rss, extracts all filenames and saves these information into the `$fileList` array.

**To write your own Plugin:**  

Create `MyRssFeed.php` in `rssFD/plugins/`.

Write a new PHP class:  
`class MyRssFeed extends FeedAbstract {}`

The needed Methods are

- ```
public __construct($logger) {  
	$this->logger = $logger;  
	$this->name = "My Fancy RSS";  
	$this->feedURL = "http://domain.tld/my.rss";  
}```
- ```public function loadFileList() {}```

The constructor is complete as it is here. `$this->name` will be the subfolder where the files are written to.

In `loadFileList()` you need to download `$this->feedURL` and extract all files. Write them into `$this->fileList` as an array. Every `$this->fileList[]`-element needs the keys `title`, `url` and `filename`.  
Example:  
```
array_push($this->fileList,[  
	"title" =>$title,  
	"url" => $link,  
	"filename" => $name  
]);```

To learn more, examine `rssFD/plugins/HeroldsbacherAnzeiger.php`.