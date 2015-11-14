<?php namespace App\Classes;

use Config;

/**
 * @author 
 * @copyright 2015
 */
class Dropzone
{
	private $uploadDir;
	private $animalDir;
	private $errors;
	private $allowedExt;

	public function __construct($animal_num)
	{
		$this->uploadDir = Config::get('rescue.upload_path');
		$this->animalDir = $this->uploadDir . DIRECTORY_SEPARATOR . str_pad($animal_num, 6, '0', STR_PAD_LEFT) . DIRECTORY_SEPARATOR;
		$this->errors = array();
	}
	
	public function route() 
	{
		$action = 'upload';
		if (isset($_GET['action'])) {
			$action = $_GET['action'];
		}
		//routing for different tasks
		switch ($action) {
			case 'upload':
				if (!empty($_FILES)) {
					$this->upload();
				}
				break;
			case 'remove':
				$filename = $_GET['name'];
				removeFile($filename);
				break;
			case 'show':
				showFiles();
				break;
		}
	}
	
	public function upload()
	{
		foreach($_FILES as $file ){
			$file_name = $file['name'];
			$file_size = $file['size'];
			$file_tmp = $file['tmp_name'];
			$file_type=  $file['type'];	
			if($file_size > 2097152){
				$this->errors[] = 'File size must be less than 2 MB';
			}

			// Create animal directory if it doesn't exists
			if (!file_exists($this->animalDir)) {
				mkdir($this->animalDir, 0777, true);
			}
			if(empty($this->errors)==true){
			   if(is_dir($this->animalDir . $file_name) == false){
					move_uploaded_file($file_tmp, $this->animalDir . $file_name);
			   }
			} else {
					print_r($this->errors);
			}
		}
	}
	
	public function showFiles()
	{
		$result = array();
		$files = scandir($this->animalDir);
		if (false !== $files) {
			foreach ($files as $file) {
				//ignore current and parent folder indicator
				if ('.' != $file && '..' != $file) {
					$obj['name'] = $file;
					$obj['size'] = filesize($this->animalDir . $file);
					$result[] = $obj;
				}
			}
		}
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo "showFiles" . $files;
		echo json_encode($result);
	}
	
	public function removeFile()
	{
		$targetFile = $this->animalDir . $fileName;
		//remove only when file exists
		if (file_exists($targetFile)) {
			unlink($targetFile);
		}
	}
}
