<?php
require_once '/includes/connectClass.php';
require_once '/home/pi/vendor/autoload.php';
/** 
 * @author Rick Stover
 * This code will have empty functions, which are meant as suggestions as to what
 *  a file handler class would be.
 */
class VideoHandler
{

    // the ID of the file in the database
    private $id;

    // directory location of the converted video
    private $video_file;
    // directory location of the uploaded video
    private $input_video_file;
    
    // directory location of the converted gif
    private $gif_file;

    // directory location of the still frame
    private $still_file;

    // url of the converted video
    private $video_file_url;

    // url of the converted gif
    private $gif_file_url;

    // url of the still frame
    private $still_file_url;

    // the x resolution of the video
    private $width;

    // the y resolution of the video
    private $height;

    // length of the video
    private $duration;

    // instance of the Connect class that will handle SQL Queries
    private $db;
    
    // name of the table storing the file information
    private $table;

    /**
     * The empty constructor should provide default values for the file and
     * initialize the ffmpeg and db objects.
     */
    public function __construct()
    {
        $url=HTTP_TYPE."://".HTTP_ROOT.substr(__DIR__, strlen($_SERVER['DOCUMENT_ROOT'])).'/';
        $dir=$_SERVER['DOCUMENT_ROOT'];
        $this->table="VideoFiles";
        $this->id=0;
        $this->duration=0;
        $this->width=0;
        $this->height=0;
        $this->gif_file=$dir.DIRECTORY_SEPARATOR."/gif/";
        $this->gif_file_url=$url."gif/";
        $this->still_file=$dir.DIRECTORY_SEPARATOR."img/";
        $this->still_file_url=$url."still/";
        $this->video_file=$dir.DIRECTORY_SEPARATOR."output/";
        $this->input_video_file=$dir.DIRECTORY_SEPARATOR."input/";
        $this->video_file_url=$url."output/";
        $this->db=new Connect("tiger","Tigers17");
        
        // TODO - Insert your constructor code here
    }

    /**
     * The catchFile method should catch a single file from the $_FILE associative
     * array.
     * It will then do the following:
     * -move the temporary file to the input folder
     * -validate the file - only videos here!
     * -convert the input video to h.264
     * -create the GIF
     * -create the still
     * -insert new row into database
     *
     * @param
     *            string id - the key of the $_FILE assoc array relating to the form
     *            id of the submitted file. Used in $_FILE[$id]["tmp_name"], etc.
     */
    public function catchFile($id)
    {
        $tmp_name-$_FILES[$id]["tmp_name"];
        // file name with extension
        $file = $theFile["name"];
        // name without extension
        $file_name = pathinfo($file, PATHINFO_FILENAME);
        $tmp = explode('.', $theFile["name"]);
        $ext = end($tmp);
        $date = date("Y-m-d H:i:s");
        $uploadtime = strtotime($date);
        $this->input_video_file.=$uploadtime.$ext;
        $this->video_file .= $uploadtime . ".mp4";
        $this->gif_file .= $uploadtime . ".gif";
        $this->still_file .= $uploadtime . ".jpeg";
        if($this->moveTmp($tmp_name)){
            if(isValid()){
                $this->convertVideoFormat();
                $this->loadFromFile();
                $this->createGIF();
                $this->createStill();
                $this->insertVideo();
            }
            else{
                $this->removeVideo($this->input_video_file);
            }
        }
    }

    /**
     * isValid uses ffprobe to get codec_type of uploaded video, checks
     *  the codec type. If it is video, return true. Else, delete the 
     *  file and return false.
     */
    public function isValid()
    {
        // TODO - Insert your validation code here
        $ffprobe = FFMpeg\FFProbe::create();
        $codec=$ffprobe
        ->streams($this->video_file) // extracts streams informations
        ->videos() // filters video streams
        ->first() // returns the first video stream
        ->get('codec_type'); // returns the codec_type property
        if($codec=='video')
            return true;
        return false;
    }

    /**
     * This uses the move_uploaded_file to put the temporary file in the 
     * input directory for conversion.
     */
    public function moveTmp($tmp_name)
    {
        $moved = move_uploaded_file($tmp_name, $this->input_video_file);
        return $moved;
    }

    /**
     * This method will remove the physical copy of the file.
     */
    public function removeVideo($filePath){
        if ( file_exists(realpath($filePath)) ) {
            if( @unlink(realpath($filePath)) !== true )
                throw new Exception('Could not delete file: ' . $this->filePath);
        }
        return true;
    }
    /**
     * convertVideo converts the video from whatever format it was into an mp4 
     * file using $ffmpeg.
     */
    public function convertVideoFormat()
    {
        $ffmpeg=FFMpeg\FFMpeg::create();
        $format = new FFMpeg\Format\Video\X264('aac');
        $video = $ffmpeg->open($this->video_file);
        /* This section of code will be useful when we are trimming and resizing. 
         * It is unneccessary when we are just changing the file format.
         * $clip = $video->clip(FFMpeg\Coordinate\TimeCode::fromSeconds(0), 
            FFMpeg\Coordinate\TimeCode::fromSeconds($this->duration));
        $clip->filters()
            ->resize(new FFMpeg\Coordinate\Dimension($this->width,$this->height), FFMpeg\Filters\Video\ResizeFilter::RESIZEMODE_INSET, true);
        $ext="mp4";
        $output = "$this->output_dir/$this->video_file";
        $clip
        ->save($format, $output);*/
        $video->save($format,$this->video_file);
        $this->removeVideo($this->input_video_file);
    }

    /**
     * createGIF should only convert the video into a GIF file using $ffmpeg.
     */
    public function createGIF()
    {
        $ffmpeg=FFMpeg\FFMpeg::create();
        $video = $ffmpeg->open($this->video_file);
        //This section of code will display debug info
        //			$ffmpeg->getFFMpegDriver()->listen(new \Alchemy\BinaryDriver\Listeners\DebugListener());
        //$ffmpeg->getFFMpegDriver()->on('debug', function ($message) {
        //  echo $message."\n";
        //});
        
        $video
        ->gif(FFMpeg\Coordinate\TimeCode::fromSeconds(0), new FFMpeg\Coordinate\Dimension(320, 240), 3)
        ->save($this->gif_file);
    }

    /**
     * createStill should only convert the video into a still using $ffmpeg.
     */
    public function createStill()
    {
        $ffmpeg=FFMpeg\FFMpeg::create();
        
        $video = $ffmpeg->open($this->video_file);
        //This section of code will display debug info        
        //			$ffmpeg->getFFMpegDriver()->listen(new \Alchemy\BinaryDriver\Listeners\DebugListener());
        //$ffmpeg->getFFMpegDriver()->on('debug', function ($message) {
        //  echo $message."\n";
        //});
        
        $frame = $video->frame(FFMpeg\Coordinate\TimeCode::fromSeconds(0));
        $frame->save($this->still_file);        
    }

    /**
     * insertVideo should generate an SQL QUery based on the stored file
     * information, then insert it into the database using $db.
     */
    public function insertVideo()
    {
        $sql="INSERT INTO ".$this->table.
            " (File, Still, Gif, RexX, ResY, Seconds) VALUES".
            " (".$this->video_file.", ".$this->still_file.", ".$this->gif_file.", ". $this->width.
            ", ".$this->height.", ".$this->duration.")";
        $this->db->runQuery($sql);
    }

    /**
     * deleteVideo should generate an SQL Query based on the file ID
     * information, then delete it into the database using $db.
     */
    public function deleteVideo()
    {
        $this->deleteVideo($this->id);
    }

    /**
     * deleteVideo(ID) should generate an SQL Query based on the provided
     * file ID, then delete it into the database using $db.
     */
    public function deleteVideo($id)
    {}

    /**
     * loadFromFile will use FFProbe and FFMpeg to load the file information
     * into the class variables.
     */
    public function loadFromFile(){
        $ffprobe = FFMpeg\FFProbe::create();
        $this->duration=$ffprobe
            ->format($this->video_file) // extracts file informations
            ->get('duration');
        $this->width=$ffprobe
            ->format($this->video_file) // extracts file informations
            ->get('width');
        $this->height=$ffprobe
            ->format($this->video_file) // extracts file informations
            ->get('height');        
    }
    /**
     * loadFromAssoc should load the file information from an associative
     * array, like the result for an SQL query.
     *
     * @param assoc_array $assoc
     *            is the result of a database query
     */
    public function loadFromAssoc($assoc)
    {}

    /**
     * loadFromID should load the file information from an SQL query based on
     * the file ID.
     * Once the query is run, it should call loadFromAssoc
     */
    public function loadFromID()
    {}

    /**
     * displayEditCell should return a table cell that contains a GIF of the file,
     * sized appropriately, and links to the edit page for the file
     */
    public function displayEditCell()
    {}

    /**
     * displayDeleteCell should return a table cell that contains a GIF of the file,
     * sized appropriately, and links to the delete page for the file
     */
    public function displayDeleteCell()
    {}

    /**
     * displayCell should display the thumbnail of the video in a table cell, and link to a
     * pop-up of the video in a new window.
     */
    public function displayCell()
    {}
}

