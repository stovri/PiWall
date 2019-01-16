<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Upload Video</title>
  <meta name="description" content="Upload videos to use on the piWall">
  <link rel="stylesheet" type="text/css" href="style.css">
  </head>
  <body>
    <div id="main-area">
    <div id="header">
      <h1>Please upload your video!</h1>
    </div>
    <form method="post" action="" enctype="multipart/form-data">
      <div id="form-contents">
      <label for="video_file">Video to upload:</label> </br>
      <input id="video_file" type="file" name="user_video" value=""></br>
      <input type="submit" name="submit" value="upload">
    </form>
    <?php
	require '/home/pi/vendor/autoload.php';
	require_once '/VideoHandler.php';
    if(isset($_POST["submit"])) {	
        $vidHandle = new VideoHandler();
        $message=$vidHandle->catchFile('user_video');
        echo $message;
    }
?>

</div>
</body>
</html>
