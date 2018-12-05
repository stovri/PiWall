<?php
if (!isset ($NAME_FILE_PHP_FILE)){
	$NAME_FILE_PHP_FILE=0; 
}
if ($NAME_FILE_PHP_FILE!=1){
	$NAME_FILE_PHP_FILE=1; 
	include("/var/www/html/includes/Authorization.php");
	include("/var/www/html//includes/connectClass.php");
class Namefile {
 
	private $tmp_name;
	private $file_type;
	private $file;
	private $video_file;
	private $gif_file;
	private $still_file;
	private $duration;
	private $width;
	private $height;
	private $db;

 
	function __construct($theFile) {
		$this->db=new Connect("tiger","Tigers17");
		$this->duration=0;
		$this->width=0;
		$this->height=0;
			// create special string from date to ensure filename is unique
		$date = date("Y-m-d H:i:s");
		$uploadtime = strtotime($date);
		$input_dir = dirname(__FILE__). "/input";
		$gif_dir = dirname(__FILE__). "/gif";
		$still_dir = dirname(__FILE__). "/img";
		$this->tmp_name = $theFile["tmp_name"];
		// file name with extension
		$file = $theFile["name"];
		// name without extension
		$file_name = pathinfo($file, PATHINFO_FILENAME);
		$tmp= explode('.', $theFile["name"]);
		$ext = end($tmp);
		
		$this->video_file = $input_dir . "/" . $uploadtime .".". $ext;
		$this->gif_file = $gif_dir . "/" . $uploadtime .".gif";
		$this->still_file = $still_dir . "/" . $uploadtime .".jpeg";
	}
	function moveIt()
	{			
			// put file to input directory to make it easier to be processed with ffmpeg
			$moved = move_uploaded_file($this->tmp_name, $this->video_file);
			return $moved;
	}
	
	function myAdmin(){
		if(isset($_POST["submit"])) {
   
            $sql = "INSERT INTO VideoFiles ".
               "(File, Still, Gif, ResX, ResY, Seconds) "."VALUES ".
               "('$this->video_file','$this->still_file','$this->gif_file','$this->width','$this->height','$this->duration')";
               echo $sql;
            $retval = $this->db->runQuery($sql);
  		}
	}
 
	function getvideo_file() {
		return $this->video_file;
	}
	function getUploadtime(){
		return $this->uploadtime;
	}
	function getFileName(){
		return $this->file_name;
	}
	function getStillName(){
		return $this->still_file;
	}
	function getGIFName(){
		return $this->gif_file;
	}
	function setDuration($dur){
		$this->duration=$dur;
	}
	function setWidth($wid){
		$this->width=$wid;
	}
	function setHeight($hei){
		$this->height=$hei;
	}


 } 
}
?>
