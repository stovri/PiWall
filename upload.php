<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Edit Video</title>
  <meta name="keyword" content="video to gif, video shortener">
  <meta name="description" content="Convert video to gif or cut it out to shorter length">
  <link rel="stylesheet" type="text/css" href="style.css">
  </head>
  <body>
    <div id="main-area">
    <div id="header">
      <h1>Please upload your video to edit it!</h1>
      <p>Make sure that your uploaded file name is not containing any special characters and or white spaces!</p>
    </div>
     
    <form method="post" action="" enctype="multipart/form-data">
      <div id="form-contents">
      <label for="video_file">Video to Edit:</label> </br>
      <input id="video_file" type="file" name="user_video" value=""></br>
      <input type="submit" name="submit" value="upload">
    </form>
    <?php
	require '/home/pi/vendor/autoload.php';
    if(isset($_POST["submit"])) {	
	
    
    include("Namefile.php");
    $input_dir = dirname(__FILE__). "/input";
    $h = new Namefile($_FILES["user_video"]);
    if($h->moveIt()) {
		$ffmpeg = FFMpeg\FFMpeg::create();
		$video = $ffmpeg->open($h->getvideo_file());
		
		//			$ffmpeg->getFFMpegDriver()->listen(new \Alchemy\BinaryDriver\Listeners\DebugListener());
//$ffmpeg->getFFMpegDriver()->on('debug', function ($message) {
  //  echo $message."\n";
//});
		
		$frame = $video->frame(FFMpeg\Coordinate\TimeCode::fromSeconds(1));
		$frame->save($h->getStillName());
		$video
			->gif(FFMpeg\Coordinate\TimeCode::fromSeconds(2), new FFMpeg\Coordinate\Dimension(640, 480), 3)
			->save($h->getGIFName());
		$ffprobe = FFMpeg\FFProbe::create();
		$h->setDuration($ffprobe
			->format($h->getvideo_file()) // extracts file informations
			->get('duration')); 
		$h->setWidth($ffprobe
			->format($h->getvideo_file()) // extracts file informations
			->get('width')); 
		$h->setHeight($ffprobe
			->format($h->getvideo_file()) // extracts file informations
			->get('height')); 

		$h->myAdmin();
	}
}

    ?>

</div>
</body>
</html>
