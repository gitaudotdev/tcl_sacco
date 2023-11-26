<?php

class FileManager{

	public $startDirectory = './HRM';
	public $includeUrl = false;
	public $directoryUrl = 'http://localhost/sacco_system/folders/admin';
	public $showSubDirectories = true;
	public $openLinksInNewTab = true;
	public $showThumbnails = true;
	public $enableDirectoryCreation = true;
	public $enableUploads = true;
	public $enableMultiFileUploads = true;
	public $overwriteOnUpload = false;
	public $enableFileDeletion = true;
	public $enableDirectoryDeletion = true;
	public $allowedUploadMimeTypes = array(
		'image/jpeg',
		'image/gif',
		'image/png',
		'image/bmp',
		'audio/mpeg',
		'audio/mp3',
		'audio/mp4',
		'audio/x-aac',
		'audio/x-aiff',
		'audio/x-ms-wma',
		'audio/midi',
		'audio/ogg',
		'video/ogg',
		'video/webm',
		'video/quicktime',
		'video/x-msvideo',
		'video/x-flv',
		'video/h261',
		'video/h263',
		'video/h264',
		'video/jpeg',
		'text/plain',
		'text/html',
		'text/css',
		'text/csv',
		'text/calendar',
		'application/pdf',
		'application/x-pdf',
		'application/vnd.openxmlformats-officedocument.wordprocessingml.document', // MS Word (modern)
		'application/msword',
		'application/vnd.ms-excel',
		'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', // MS Excel (modern)
		'application/zip',
		'application/x-tar'
	);
	public $enableUnzipping = true;
	public $deleteZipAfterUploading = false;
	public $enableTheme = true;
	public $passwordProtect = false;
	public $password = 'password';
	public $enableIpWhitelist = false;
	public $ipWhitelist = array(
		'127.0.0.1'
	);
	public $ignoredFileExtensions = array(
		'php',
		'ini',
		'exe',
		'apk',
		'vbs',
		'app',
		'sql',
	);
	public $ignoredFileNames = array(
		'.htaccess',
		'.DS_Store',
		'Thumbs.db',
	);
	public $ignoredDirectories = array(

	);
	public $ignoreDotFiles = true;
	public $ignoreDotDirectories = true;
	private $__previewMimeTypes = array(
		'image/gif',
		'image/jpeg',
		'image/png',
		'image/bmp'
	);

	private $__currentDirectory = null;

	private $__fileList = array();

	private $__directoryList = array();

	private $__debug = true;

	public $sortBy = 'name';

	public $sortableFields = array(
		'name',
		'size',
		'modified'
	);

	private $__sortOrder = 'asc';

	public function __construct() {
		define('DS', '/');
	}

	public function run() {
		if ($this->enableIpWhitelist) {
			$this->__ipWhitelistCheck();
		}

		$this->__currentDirectory = $this->startDirectory;

		// Sorting
		if (isset($_GET['order']) && in_array($_GET['order'], $this->sortableFields)) {
			$this->sortBy = $_GET['order'];
		}

		if(isset($_GET['sort']) && ($_GET['sort'] == 'asc' || $_GET['sort'] == 'desc')) {
			$this->__sortOrder = $_GET['sort'];
		}

		if (isset($_GET['dir'])){
			if(isset($_GET['delete']) && $this->enableDirectoryDeletion) {
				$this->deleteDirectory();
			}
			$this->__currentDirectory = $_GET['dir'];
			return $this->__display();
		} elseif (isset($_GET['preview'])) {
			$this->__generatePreview($_GET['preview']);
		} else {
			return $this->__display();
		}
	}

	public function upload() {
		$files = $this->__formatUploadArray($_FILES['upload']);
		if($this->enableUploads) {
			if($this->enableMultiFileUploads) {
				foreach($files as $file){
					$status = $this->__processUpload($file);
				}
			}else{
				$file = $files[0];
				$status = $this->__processUpload($file);
			}
			return $status;
		}
		return false;
	}

	private function __formatUploadArray($files) {
		$fileAry = array();
		$fileCount = count($files['name']);
		$fileKeys = array_keys($files);
		for ($i = 0; $i < $fileCount; $i++) {
			foreach ($fileKeys as $key) {
				$fileAry[$i][$key] = $files[$key][$i];
			}
		}
		return $fileAry;
	}

	private function __processUpload($file) {
		$fileExtension = pathinfo($file['name'],PATHINFO_EXTENSION);
		$updatedFileName=date('YmdHis',time()).mt_rand().'.'.$fileExtension;
		if(isset($_GET['dir'])){
			$this->__currentDirectory = $_GET['dir'];
		}
		if(!$this->__currentDirectory) {
			$filePath = realpath($this->startDirectory);
			$folderID=$this->getFileFolderID($this->startDirectory);
		}else{
			$this->__currentDirectory = str_replace('..', '', $this->__currentDirectory);
			$this->__currentDirectory = ltrim($this->__currentDirectory, "/");
			$folderID=$this->getFileFolderID($this->__currentDirectory);
			$filePath = realpath($this->__currentDirectory);
		}
		$filePath = $filePath . DS . $updatedFileName;
		$this->recurse_chown_chgrp($filePath, "root", "root");
		if(!empty($file)){
			if(!$this->overwriteOnUpload){
				if(file_exists($filePath)){
					return 2;
				}
			}
			if(!in_array(mime_content_type($file['tmp_name']), $this->allowedUploadMimeTypes)){
				return 3;
			}
			$this->storeFile($updatedFileName,$folderID);
			$filename=$updatedFileName;
			$timestamp =date('jS M Y \a\t g:ia');
			$activity="Uploaded File: <strong>$filename</strong> on $timestamp";
			$severity="normal";
      		Logger::logUserActivity($activity,$severity);
			move_uploaded_file($file['tmp_name'],$filePath);
			if(mime_content_type($filePath) == 'application/zip' && $this->enableUnzipping && class_exists('ZipArchive')){
				$zip = new ZipArchive;
				$result = $zip->open($filePath);
				$zip->extractTo(realpath($this->__currentDirectory));
				$zip->close();
				if($this->deleteZipAfterUploading){
					unlink($filePath);
				}
			}
			return true;
		}
	}

	public function deleteFile() {
		if(isset($_GET['deleteFile'])){
			$filePath = $_GET['deleteFile'];
			// Clean file path
			$filePath = str_replace('..', '', $filePath);
			$filePath = str_replace('//', '/', $filePath);
			// Work out full file path
			if(file_exists($filePath) && is_file($filePath)){
				$timestamp =date('jS M Y \a\t g:ia');
				$activity="Deleted File : <strong>$filePath</strong>  on $timestamp";
				$severity="urgent";
	      Logger::logUserActivity($activity,$severity);
				return unlink($filePath);
			}
		}
		return false;
	}

	public function deleteDirectory() {
		if(isset($_GET['dir'])){
			$dir = $_GET['dir'];
			// Clean dir path
			$dir = str_replace('..', '', $dir);
			$dirPath = str_replace('//', '/', $dir);
			if(file_exists($dirPath) && is_dir($dirPath)){
				$iterator = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
				$files = new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::CHILD_FIRST);
				foreach($files as $file){
					if($file->isDir()) {
						rmdir($file->getRealPath());
					}else{
						unlink($file->getRealPath());
					}
				}
				$timestamp =date('jS M Y \a\t g:ia');
				$activity="Recursively Deleted Directory : <strong>$dirPath</strong> on $timestamp";
				$severity="urgent";
	      Logger::logUserActivity($activity,$severity);
	      $deleteStatus=rmdir($dirPath);
			}else{
	      $deleteStatus=1;
			}
		}else{
			$deleteStatus=2;
		}
		return $deleteStatus;
	}

	public function createDirectory() {
		if($this->enableDirectoryCreation){
			$directoryName = $_POST['directory'];
			switch($this->restrictDuplicateFolders($_POST['directory'])){
				case 0:
				// Convert spaces
				$directoryName = str_replace(' ', '_', $directoryName);
				// Clean up formatting
				$directoryName = preg_replace('/[^\w-_]/', '', $directoryName);
				$this->storeDirectory($directoryName);
				if(isset($_GET['dir'])){
					$this->__currentDirectory = $_GET['dir'];
				}
				if(!$this->__currentDirectory){
					$filePath = realpath($this->startDirectory);
				} else {
					$this->__currentDirectory = str_replace('..', '', $this->__currentDirectory);
					$filePath = realpath($this->__currentDirectory);
				}
				$filePath = $filePath . DS . strtolower($directoryName);
				if(file_exists($filePath)){
					$folderStatus=0;
				}else{
					$timestamp =date('jS M Y \a\t g:ia');
					$activity="Created Directory : <strong>$directoryName</strong> on $timestamp";
					$severity="normal";
		      Logger::logUserActivity($activity,$severity);
					mkdir($filePath, 0777);
					$this->recurse_chown_chgrp($filePath, "root", "root");
					$folderStatus=1;
				}
				break;

				case 1:
				$folderStatus=2;
				break;
			}
		}else{
			$folderStatus=0;
		}
		return $folderStatus;
	}

	public function sortUrl($sort) {
		// Get current URL parts
		$urlParts = parse_url($_SERVER['REQUEST_URI']);
		$url = '';
		if (isset($urlParts['scheme'])) {
			$url = $urlParts['scheme'] . '://';
		}
		if (isset($urlParts['host'])) {
			$url .= $urlParts['host'];
		}
		if (isset($urlParts['path'])) {
			$url .= $urlParts['path'];
		}
		// Extract query string
		if (isset($urlParts['query'])) {
			$queryString = $urlParts['query'];
			parse_str($queryString, $queryParts);
			// work out if we're already sorting by the current heading
			if (isset($queryParts['order']) && $queryParts['order'] == $sort) {
				// Yes we are, just switch the sort option!
				if (isset($queryParts['sort'])) {
					if ($queryParts['sort'] == 'asc') {
						$queryParts['sort'] = 'desc';
					} else {
						$queryParts['sort'] = 'asc';
					}
				}
			} else {
				$queryParts['order'] = $sort;
				$queryParts['sort'] = 'asc';
			}
			// Now convert back to a string
			$queryString = http_build_query($queryParts);

			$url .= '?' . $queryString;
		} else {
			$order = 'asc';
			if ($sort == $this->sortBy) {
				$order = 'desc';
			}
			$queryString = 'order=' . $sort . '&sort=' . $order;
			$url .= '?' . $queryString;
		}
		return $url;
	}

	public function sortClass($sort) {
		$class = $sort . '_';
		if ($this->sortBy == $sort) {
			if ($this->__sortOrder == 'desc') {
				$class .= 'desc sort_desc';
			} else {
				$class .= 'asc sort_asc';
			}
		} else {
			$class = '';
		}
		return $class;
	}

	private function __ipWhitelistCheck() {
		// Get the users ip
		$userIp = $_SERVER['REMOTE_ADDR'];
		if (!in_array($userIp, $this->ipWhitelist)) {
			header('HTTP/1.0 403 Forbidden');
			die('Your IP address (' . $userIp . ') is not authorized to access this file.');
		}
	}

	private function __display() {
		if($this->__currentDirectory != '.' && !$this->__endsWith($this->__currentDirectory, DS)) {
			$this->__currentDirectory = $this->__currentDirectory . DS;
		}
		return $this->__loadDirectory($this->__currentDirectory);
	}

	private function __loadDirectory($path) {
		$files = $this->__scanDir($path);
		if (!empty($files)){
			// Strip excludes files, directories and filetypes
			$files = $this->__cleanFileList($files);
			foreach($files as $file){
				$filePath = realpath($this->__currentDirectory . DS . $file);
				if($this->__isDirectory($filePath)){
					if(!$this->includeUrl){
						$urlParts = parse_url($_SERVER['REQUEST_URI']);

						$dirUrl = '';

						if (isset($urlParts['scheme'])) {
							$dirUrl = $urlParts['scheme'] . '://';
						}

						if (isset($urlParts['host'])) {
							$dirUrl .= $urlParts['host'];
						}
						if(isset($urlParts['path'])){
							$dirUrl .= $urlParts['path'];
						}
					}else{
						$dirUrl = $this->directoryUrl;
					}

					if($this->__currentDirectory != '' && $this->__currentDirectory != '.') {
						$dirUrl .= '?dir=' . rawurlencode($this->__currentDirectory) . rawurlencode($file);
					} else {
						$dirUrl .= '?dir=' . rawurlencode($file);
					}

					if($this->siftDirectories($file) == 1){
							$this->__directoryList[$file] = array(
								'name' => rawurldecode($file),
								'path' => $filePath,
								'type' => 'dir',
								'url' => $dirUrl
							);
					}
				}else{
					if($this->siftFiles($file) == 1){
						$this->__fileList[$file] = $this->__getFileType($filePath, $this->__currentDirectory . DS . $file);
					}
				}
			}
		}

		if(!$this->showSubDirectories){
			$this->__directoryList = null;
		}

		$data = array(
			'currentPath' => $this->__currentDirectory,
			'directoryTree' => $this->__getDirectoryTree(),
			'files' => $this->__setSorting($this->__fileList),
			'directories' => $this->__directoryList,
			'requirePassword' => $this->passwordProtect,
			'enableUploads' => $this->enableUploads
		);
		return $data;
	}

	private function __setSorting($data) {
		$sortOrder = '';
		$sortBy = '';

		// Sort the files
		if($this->sortBy == 'name') {
			function compareByName($a, $b) {
				return strnatcasecmp($a['name'], $b['name']);
			}

			usort($data, 'compareByName');
			$this->soryBy = 'name';
		} elseif ($this->sortBy == 'size') {
			function compareBySize($a, $b) {
				return strnatcasecmp($a['size_bytes'], $b['size_bytes']);
			}

			usort($data, 'compareBySize');
			$this->soryBy = 'size';
		} elseif ($this->sortBy == 'modified') {
			function compareByModified($a, $b) {
				return strnatcasecmp($a['modified'], $b['modified']);
			}

			usort($data, 'compareByModified');
			$this->soryBy = 'modified';
		}elseif($this->sortBy == 'uploadedBy') {
			function compareByUploadedBy($a, $b) {
				return strnatcasecmp($a['uploadedBy'], $b['uploadedBy']);
			}
			usort($data, 'compareByUploadedBy');
			$this->soryBy = 'uploadedBy';
		}

		if ($this->__sortOrder == 'desc') {
			$data = array_reverse($data);
		}
		return $data;
	}

	private function __scanDir($dir) {
		// Prevent browsing up the directory path.

		if(strstr($dir, '../')) {
			return false;
		}

		if($dir == '/'){
			$dir = $this->startDirectory;
			$this->__currentDirectory = $dir;
		}

		$strippedDir = str_replace('/', '', $dir);

		$dir = ltrim($dir, "/");

		// Prevent listing blacklisted directories
		if(in_array($strippedDir, $this->ignoredDirectories)) {
			return false;
		}

		if(!file_exists($dir) || !is_dir($dir)){
			return false;
		}

		return scandir($dir);
	}

	private function __cleanFileList($files) {
		$this->ignoredDirectories[] = '.';
		$this->ignoredDirectories[] = '..';
		foreach ($files as $key => $file) {

			// Remove unwanted directories
			if ($this->__isDirectory(realpath($file)) && in_array($file, $this->ignoredDirectories)) {
				unset($files[$key]);
			}
			// Remove dot directories (if enables)
			if ($this->ignoreDotDirectories && substr($file, 0, 1) === '.') {
				unset($files[$key]);
			}
			// Remove unwanted files
			if (! $this->__isDirectory(realpath($file)) && in_array($file, $this->ignoredFileNames)) {
				unset($files[$key]);
			}
			// Remove unwanted file extensions
			if (! $this->__isDirectory(realpath($file))) {
				$info = pathinfo(mb_convert_encoding($file, 'UTF-8', 'UTF-8'));
				if (isset($info['extension'])) {
					$extension = $info['extension'];
					if (in_array($extension, $this->ignoredFileExtensions)) {
						unset($files[$key]);
					}
				}
				// If dot files want ignoring, do that next
				if ($this->ignoreDotFiles) {
					if (substr($file, 0, 1) == '.') {
						unset($files[$key]);
					}
				}
			}
		}
		return $files;
	}

	private function __isDirectory($file) {
		if($file == $this->__currentDirectory . DS . '.' || $file == $this->__currentDirectory . DS . '..'){
			return true;
		}
		$file = mb_convert_encoding($file, 'UTF-8', 'UTF-8');

		if(filetype($file) == 'dir') {
			return true;
		}

		return false;
	}

	/**
	 * __getFileType
	 *
	 * Returns the formatted array of file data used for thre directory listing.
	 *
	 * @param  string $filePath Full path to the file
	 * @return array   Array of data for the file
	 */
	private function __getFileType($filePath, $relativePath = null) {
		$fi = new finfo(FILEINFO_MIME_TYPE);

		if (! file_exists($filePath)) {
			return false;
		}

		$type = $fi->file($filePath);

		$filePathInfo = pathinfo($filePath);

		$fileSize = filesize($filePath);

		$fileModified = filemtime($filePath);

		$filePreview = false;

		// Check if the file type supports previews
		if ($this->__supportsPreviews($type) && $this->showThumbnails) {
			$filePreview = true;
		}

		return array(
			'name' => $filePathInfo['basename'],
			'extension' => (isset($filePathInfo['extension']) ? $filePathInfo['extension'] : null),
			'dir' => $filePathInfo['dirname'],
			'path' => $filePath,
			'relativePath' => $relativePath,
			'size' => $this->__formatSize($fileSize),
			'size_bytes' => $fileSize,
			'modified' => $fileModified,
			'type' => 'file',
			'mime' => $type,
			'url' => $this->__getUrl($filePathInfo['basename']),
			'preview' => $filePreview,
			'target' => ($this->openLinksInNewTab ? '_blank' : '_parent')
		);
	}

	private function __supportsPreviews($type) {
		if (in_array($type, $this->__previewMimeTypes)) {
			return true;
		}
		return false;
	}

	/**
	 * __getUrl
	 *
	 * Returns the url to the file.
	 *
	 * @param  string $file filename
	 * @return string   url of the file
	 */
	private function __getUrl($file) {
		if (! $this->includeUrl) {
			$dirUrl = $_SERVER['REQUEST_URI'];

			$urlParts = parse_url($_SERVER['REQUEST_URI']);

			$dirUrl = '';

			if (isset($urlParts['scheme'])) {
				$dirUrl = $urlParts['scheme'] . '://';
			}

			if (isset($urlParts['host'])) {
				$dirUrl .= $urlParts['host'];
			}

			if (isset($urlParts['path'])) {
				$dirUrl .= $urlParts['path'];
			}
		} else {
			$dirUrl = $this->directoryUrl;
		}

		if ($this->__currentDirectory != '.') {
			$dirUrl = $dirUrl . $this->__currentDirectory;
		}
		return $dirUrl . rawurlencode($file);
	}

	private function __getDirectoryTree() {
		$dirString = $this->__currentDirectory;
		$directoryTree = array();

		if(substr_count($dirString, '/') >= 0) {
			$items = explode("/", $dirString);
			$items = array_filter($items);
			$path = '';
			foreach($items as $item) {
				if($item == '.' || $item == '..') {
					continue;
				}
				$path .= rawurlencode($item) . '/';
				$directoryTree[$path] = $item;
			}
		}

		$directoryTree = array_filter($directoryTree);

		return $directoryTree;
	}

	private function __endsWith($haystack, $needle) {
		return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== false);
	}

	private function __generatePreview($filePath) {
		$file = $this->__getFileType($filePath);

		if($file['mime'] == 'image/jpeg') {
			$image = imagecreatefromjpeg($file['path']);
		} elseif ($file['mime'] == 'image/png') {
			$image = imagecreatefrompng($file['path']);
		} elseif ($file['mime'] == 'image/gif') {
			$image = imagecreatefromgif($file['path']);
		} else {
			die();
		}

		$oldX = imageSX($image);
		$oldY = imageSY($image);

		$newW = 1200;
		$newH = 1200;

		if ($oldX > $oldY) {
			$thumbW = $newW;
			$thumbH = $oldY * ($newH / $oldX);
		}
		if ($oldX < $oldY) {
			$thumbW = $oldX * ($newW / $oldY);
			$thumbH = $newH;
		}
		if ($oldX == $oldY) {
			$thumbW = $newW;
			$thumbH = $newW;
		}

		header('Content-Type: ' . $file['mime']);

		$newImg = ImageCreateTrueColor($thumbW, $thumbH);

		imagecopyresampled($newImg, $image, 0, 0, 0, 0, $thumbW, $thumbH, $oldX, $oldY);

		if ($file['mime'] == 'image/jpeg') {
			imagejpeg($newImg);
		} elseif ($file['mime'] == 'image/png') {
			imagepng($newImg);
		} elseif ($file['mime'] == 'image/gif') {
			imagegif($newImg);
		}
		imagedestroy($newImg);
		die();
	}

	private function __formatSize($bytes) {
		$units = array('B', 'KB', 'MB', 'GB', 'TB');
		$bytes = max($bytes, 0);
		$pow = floor(($bytes ? log($bytes) : 0) / log(1024));
		$pow = min($pow, count($units) - 1);
		$bytes /= pow(1024, $pow);
		return round($bytes, 2) . ' ' . $units[$pow];
	}

	private function recurse_chown_chgrp($mypath, $uid, $gid){
		if(is_dir($mypath)){
	    $d = opendir($mypath);
	    while(($file = readdir($d)) !== false) {
	        if($file != "." && $file != "..") {
	            $typepath = $mypath . "/" . $file ;
	            if(filetype ($typepath) == 'dir') {
	              recurse_chown_chgrp ($typepath, $uid, $gid);
	            }
	            chown($typepath, $uid);
	            chgrp($typepath, $gid);
	        }
	    }
		}
  } 

	private function restrictDuplicateFolders($folderName){
		$folderSql="SELECT * FROM folders WHERE name='$folderName'";
		$folders=Folders::model()->findAllBySql($folderSql);
		if(!empty($folders)){
			$restrict=1;
		}else{
			$restrict=0;
		}
		return $restrict;
	}

	private function storeDirectory($directoryName){
		$folder = new Folders;
		$folder->name=$directoryName;
		$folder->created_by=Yii::app()->user->user_id;
		$folder->integrity_hash=CommonFunctions::generateToken(50);
		$folder->save();
	}

	private function siftDirectories($folderName){
		$branchID=Yii::app()->user->user_branch;
		$userID=Yii::app()->user->user_id;
		switch(Yii::app()->user->user_level){
			case '0':
			$siftStatus=1;
			break;

			case '1':
			$folderSql="SELECT * FROM folders,profiles WHERE folders.created_by=profiles.id
			 AND folders.name='$folderName' AND profiles.branchId=$branchID";
			$folders=Folders::model()->findAllBySql($folderSql);
			if(!empty($folders)){
			  $siftStatus=1;
			}else{
			  $siftStatus=0;
			}
			break;

			case '2':
			$folderSql="SELECT * FROM folders WHERE folders.created_by=$userID
			 AND folders.name='$folderName'";
			$folders=Folders::model()->findAllBySql($folderSql);
			if(!empty($folders)){
			  $siftStatus=1;
			}else{
			  $siftStatus=0;
			}
			break;

			default:
			$siftStatus=0;
			break;
		}
		return $siftStatus;
	}

	private function storeFile($fileName,$folderID){
		$file= new Files;
		$file->folder_id=$folderID;
		$file->filename=$fileName;
		$file->created_by=Yii::app()->user->user_id;
		$file->save();
	}

	private function restrictDuplicateFiles($fileName,$folderID){
		$fileSql="SELECT * FROM files WHERE folder_id=$folderID AND filename='$fileName'";
		$file=Files::model()->findAllBySql($fileSql);
		if(!empty($file)){
			$restrictStatus=1;
		}else{
			$restrictStatus=0;
		}
		return $restrictStatus;
	}

	private function getFileFolderID($folderName){
		$folderSql="SELECT * FROM folders WHERE name='$folderName'";
		$folder=Folders::model()->findBySql($folderSql);
		if(!empty($folder)){
			$folderID=$folder->id;
		}else{
			$folderID=0;
		}
		return $folderID;
	}

	private function siftFiles($fileName){
		$branchID=Yii::app()->user->user_branch;
		$userID=Yii::app()->user->user_id;
		switch(Yii::app()->user->user_level){
			case '0':
			$siftStatus=1;
			break;

			case '1':
			$filesql="SELECT * FROM files,profiles WHERE files.created_by=profiles.id
			 AND files.filename='$fileName' AND profiles.branchId=$branchID";
			$files=Files::model()->findAllBySql($filesql);
			if(!empty($files)){
			  $siftStatus=1;
			}else{
			  $siftStatus=0;
			}
			break;

			case '2':
			$fileSql="SELECT * FROM files WHERE files.created_by=$userID
			 AND files.filename='$fileName'";
			$files=Files::model()->findAllBySql($fileSql);
			if(!empty($files)){
			  $siftStatus=1;
			}else{
			  $siftStatus=0;
			}
			break;

			default:
			$siftStatus=0;
			break;
		}
		return $siftStatus;
	}

	public function getUploadedByName($fileName){
		$fileSql="SELECT * FROM files WHERE filename='$fileName'";
		$files=Files::model()->findBySql($fileSql);
		if(!empty($files)){
			$userID=$files->created_by;
			$fullName = Profiles::model()->findByPk($userID)->ProfileFullName;  
		}else{
			$fullName="Not Found";
		}
		return $fullName;
	}
	
}