<?php
if (! isset($NAME_FILE_PHP_FILE)) {
    $NAME_FILE_PHP_FILE = 0;
}
if ($NAME_FILE_PHP_FILE != 1) {
    $NAME_FILE_PHP_FILE = 1;
    include ("/var/www/html/includes/Authorization.php");
    include ("/var/www/html/includes/connectClass.php");
    /****
     * 
     * @author Kaylee Flanary
     * This class will deal with loading the file information to be stored in the database.
     * It will also move the video file from the temporary folder into its final location.
     * 
     * @todo Make this class handle file conversion to reduce the possibility of errors. We 
     * also should have it load the file information from the database.
     * 
     */
    class Namefile
    {

        private $tmp_name;    //the name of the temporary file

        private $file_type;   //not used?

        private $file;        //uploaded file name with the extension

        private $video_file;  //absolute path to converted file name with extension

        private $gif_file;    //absolute path to gif 

        private $still_file;  //absolute path to still

        private $duration;    //duration of the video

        private $width;       //x resolution of the video, in pixels

        private $height;      //y resolution of the video, in pixels

        private $db;          //instance of the connect class, used to run SQL Queries

        /**************************
         * 
         * @param assoc array $theFile: the result of a file form submission.
         * This constructor will take a submitted file and create new file 
         * location names to be used by FFMPEG. We probably should move the methods
         * that create these files into this class.
         * 
         */
        function __construct($theFile)
        {
            $this->db = new Connect("tiger", "Tigers17");
            $this->duration = 0;
            $this->width = 0;
            $this->height = 0;
            // create special string from date to ensure filename is unique
            $date = date("Y-m-d H:i:s");
            $uploadtime = strtotime($date);
            $input_dir = dirname(__FILE__) . "/input";
            $gif_dir = dirname(__FILE__) . "/gif";
            $still_dir = dirname(__FILE__) . "/img";
            $this->tmp_name = $theFile["tmp_name"];
            // file name with extension
            $file = $theFile["name"];
            // name without extension
            $file_name = pathinfo($file, PATHINFO_FILENAME);
            $tmp = explode('.', $theFile["name"]);
            $ext = end($tmp);
            
            $this->video_file = $input_dir . "/" . $uploadtime . "." . $ext;
            $this->gif_file = $gif_dir . "/" . $uploadtime . ".gif";
            $this->still_file = $still_dir . "/" . $uploadtime . ".jpeg";
        }

        /***
         * moveIt will move the uploaded file from the temporary file location to the 
         * input folder so it can be convereted.
         * @return boolean true if the move was successful, false if it failed to move.
         */
        function moveIt()
        {
            // put file to input directory to make it easier to be processed with ffmpeg
            $moved = move_uploaded_file($this->tmp_name, $this->video_file);
            return $moved;
        }

        /*****
         * myAdmin will create an SQL query to insert new file information into the
         * VideoFiles table. It will then run the query. Not sure why it needs to check
         * the $_POST associative array.
         * 
         * @todo make this not require a form submission. 
         */
        function myAdmin()
        {
            if (isset($_POST["submit"])) {
                
                $sql = "INSERT INTO VideoFiles " . "(File, Still, Gif, ResX, ResY, Seconds) " . "VALUES " . "('$this->video_file','$this->still_file','$this->gif_file','$this->width','$this->height','$this->duration')";
                echo $sql;
                $retval = $this->db->runQuery($sql);
            }
        }

        function getvideo_file()
        {
            return $this->video_file;
        }

        function getUploadtime()
        {
            return $this->uploadtime;
        }

        function getFileName()
        {
            return $this->file_name;
        }

        function getStillName()
        {
            return $this->still_file;
        }

        function getGIFName()
        {
            return $this->gif_file;
        }

        function setDuration($dur)
        {
            $this->duration = $dur;
        }

        function setWidth($wid)
        {
            $this->width = $wid;
        }

        function setHeight($hei)
        {
            $this->height = $hei;
        }
    }
}
?>
