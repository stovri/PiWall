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
      <img src="/images/Laura.png" alt="Average Day of Laura Running"
		style="width:450px;height:300px;">
      <p>Make sure that your uploaded file name is not containing any special characters and or white spaces! You can convert your video to gif or mp4 format with this tool. You can also cut the duration of your video.</p>
    </div>
     
    <form method="post" action="" enctype="multipart/form-data">
      <div id="form-contents">
      <label for="video_file">Video to Edit:</label> </br>
      <input id="video_file" type="file" name="user_video1" value=""></br>
      <!--<label for="extension">Convert to:</label></br>
          <select name="extension" id="extension">
          <option value="none">Default</option>
          <option value="gif">gif</option>
          <option value="mp4">mp4</option>
           You can add other format here 
          </select></br>-->
         
      
      <label for="start_from_sec">Start From Second:</label></br>
     

		<select id= "start_from_sec" name="start_from_sec">

		<option value="00">0</option>

		<option value="01">1</option>

		<option value="02">2</option>
		
		<option value="11">11</option>
			</select>
      </br>
      <label for="start_from_min">Start From Minute:</label></br>
     

		<select id= "start_from_min" name="start_from_min">

		<option value="00">0</option>

		<option value="01">1</option>

		<option value="02">2</option>
			</select>
      </br>
      <label for="start_from_hour">Start From Hour:</label></br>
     

		<select id= "start_from_hour" name="start_from_hour">

		<option value="00">0</option>

		<option value="01">1</option>

		<option value="02">2</option>
			</select>
      </br>
      
      
      <label for="length">Length:</label></br>
      <input type="text" name="length" id="length" value="" placeholder="example: 10"> seconds
      </br>
       <label for="video_file">Video to Add:</label> </br>
      <input id="video_file" type="file" name="user_video2" value=""></br>
      
      <input type="submit" name="submit" value="Edit">
      </div>	
      What is your Gender?

		<select name="formGender">

		<option value="">Select...</option>

		<option value="M">Male</option>

		<option value="F">Female</option>
			</select>
    </form>
<?php	
include("Namefile.php");
include("Edit.php");
//include("/includes/PHP-FFMpeg-master");

//$ffmpeg = FFMpeg\FFMpeg::create();
$input_dir = dirname(__FILE__). "/input";
$output_dir = dirname(__FILE__). "/output";
$counter = 1;
/*if (file_exists($_FILES["user_video2"]["tmp_name"])){
	$counter = 2; 
}*/

if(isset($_POST["submit"])) {
	var_dump($_POST);	
		var_dump($_FILES);	
		
$numOn = 1;
 for ($k=0;$k<$counter;$k++){ 
  if(file_exists($_FILES["user_video"."$numOn"]["tmp_name"])){
    $h = new Namefile($_FILES["user_video"."$numOn"]);
    echo "Hello, " . $h->getvideo_file();
   
    if($h->moveIt()) {
		$edit= new Edit();
		$edit->catchPost();
		$edit->render($h->getvideo_file(), $h->getUploadtime(), $h->getFileName());	
      // delete uploaded file from input folder to reserve disk space
      unlink($h->getvideo_file());
      $fileName = $edit->getOutputForLink();
   
      echo "<span>Edit Finished:</span>";
      echo "<a href='http://$_SERVER[HTTP_HOST]"."/output/$fileName"."'>Download</a>";
    }
  }
  else {
    echo "<h3>No file was uploaded!</h3>";
  }
}
}

?>
</div>
</body>
</html>
