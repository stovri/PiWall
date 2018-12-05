<?php
if (!isset ($EDIT_PHP_FILE)){
	$EDIT_PHP_FILE=0; 
}
if ($EDIT_PHP_FILE!=1){
require '/home/pi/vendor/autoload.php';
	$EDIT_PHP_FILE=1; 
	class Edit {
		private $start_from;
		private $length;
		private $resolution;
		private $video_x;
		private $video_y;
		private $output_dir;
		private $outputForLink;
		private $ffmpeg;
		function __construct() {
			$this->start_from=0;
			$this->length=10;
			$this->resolution=new Resolution(1080,720,1,1);
			$this->video_x=$this->resolution->getAllX();
			$this->video_y=$this->resolution->getAllY();
			$this->output_dir = dirname(__FILE__). "/output";
			$this->outputForLink ="";
			$this->ffmpeg = FFMpeg\FFMpeg::create();
		}
		function catchPost(){
			if(isset($_POST["start_from"]) && $_POST["start_from"] != ""){
			$this->start_from = $_POST["start_from"];
		  }	
		  $this->start_from = $_POST["start_from_sec"];
		   if(isset($_POST["length"]) && $_POST["length"] != ""){
			$this->length = $_POST["length"];
		  }
		}
		function render($vidfile, $uploadtime,$filename){
		  // change php working directory to where ffmpeg binary file reside
			//chdir("binaries");
			$format = new FFMpeg\Format\Video\X264('aac');
			$this->ffmpeg->getFFMpegDriver()->listen(new \Alchemy\BinaryDriver\Listeners\DebugListener());
$this->ffmpeg->getFFMpegDriver()->on('debug', function ($message) {
    echo $message."\n";
});
			$video = $this->ffmpeg->open($vidfile);
			$clip = $video->clip(FFMpeg\Coordinate\TimeCode::fromSeconds($this->start_from), FFMpeg\Coordinate\TimeCode::fromSeconds($this->length));
			$clip->filters()->resize(new FFMpeg\Coordinate\Dimension($this->video_x,$this->video_y), FFMpeg\Filters\Video\ResizeFilter::RESIZEMODE_INSET, true);
			$ext="mp4";
			$output = "$this->output_dir/$uploadtime"."_$filename.$ext";
			$clip
				->save($format, $output);	
			
			//$output = "$this->output_dir/$uploadtime"."_$filename.$ext";
			//$the_cmd="ffmpeg -t $this->length -ss $this->start_from -i $vidfile -b:v 2048k $output 2>&1";
			//$process = shell_exec($the_cmd);
			//do this before changeResolution!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
			$this->outputForLink = "$uploadtime"."_$filename.$ext";
		}
		function changeResolution($vidfile){
			$ext="mp4";
			$video = $ffmpeg->open('$vidfile');
			$video->filters()->resize(new FFMpeg\Coordinate\Dimension(1080, 720), FFMpeg\Filters\Video\ResizeFilter::RESIZEMODE_INSET, true);
			$video->save($this->outputForLink);
		}
		function getOutputForLink(){
			return $this->outputForLink;
		}
	}
}
	class Resolution {
		private $singleX;
		private $singleY;
		private $allX;
		private $allY;
		private $screenNumberX;
		private $screenNumberY;
		function __construct($length, $width, $screenNumberHorizontal, $screenNumberVertical) {
			$this->singleX=$length;
			$this->singleY=$width;
			$this->screenNumberX=$screenNumberHorizontal;
			$this->screenNumberY=$screenNumberVertical;
			$this->allX=$this->singleX*$this->screenNumberX;
			$this->allY=$this->singleY*$this->screenNumberY;
		}
		function getAllX(){
			return $this->allX;
		}
		function getAllY(){
			return $this->allY;
		}
	
}
?>
 
