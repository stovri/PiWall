<?php

/** 
 * @author Rick Stover
 * This code will have empty functions, which are meant as suggestions as to what
 *  a file handler class would be.
 */
class VideoHandler
{

    /**
     * Suggested variables:
     * $id - the ID of the file in the database
     * $video_file - directory location of the converted video
     * $gif_file - directory location of the converted gif
     * $still_file - directory location of the still frame
     * $video_file_url - url of the converted video
     * $gif_file_url - url of the converted gif
     * $still_file_url - url of the still frame
     * $width- the x resolution of the video
     * $height - the y resolution of the video
     * $duration - length of the video
     * $ffmpeg - instance of the ffmpeg class to perform operations
     * $db - instance of the Connect class that will handle SQL Queries
     */
 
    // TODO - Insert your code here
    
    /**
     * The empty constructor should provide default values for the file and 
     * initialize the ffmpeg and db objects.
     */
    public function __construct()
    {
        
        // TODO - Insert your code here
    }
    
    /**
     * The catchFile method should catch a single file from the $_FILE associative
     * array. It will then do the following:
     *  -move the temporary file to the input folder
     *  -validate the file - only videos here!
     *  -convert the input video to h.264
     *  -create the GIF
     *  -create the still
     *  -insert new row into database
     *  
     * @param string id - the key of the $_FILE assoc array relating to the form
     * id of the submitted file. Used in $_FILE[$id]["tmp_name"], etc.
     */
    public function catchFile($id){
        // TODO - Insert your code here
    }
    
    /**
     * isValid should use ffprobe to get codec of uploaded video. Saw this on the 
     * manual page:
     * $ffprobe = FFMpeg\FFProbe::create();
     * $codec=$ffprobe
     *  ->streams($full_video_path) // extracts streams informations
     *  ->videos()                      // filters video streams
     *  ->first()                       // returns the first video stream
     *  ->get('codec_name');            // returns the codec_name property
     *  Check the codec against the list of good codecs. If it is good, return 
     *  true. Else, delete the file and return false.
     */
    public function isValid(){
        // TODO - Insert your code here
    }
    
    /**
     * This should be the same as NameFile->moveIt()
     */
    public function moveTmp(){
        
    }
    
    /**
     * convertVideo should only convert the video from whatever format it was into
     * an mp4 file using $ffmpeg.
     */
    public function convertVideo(){
        
    }
    
    /**
     * createGIF should only convert the video into a GIF file using $ffmpeg.
     */
    public function createGIF(){
        
    }
    
    /**
     * createStill should only convert the video into a still using $ffmpeg.
     */
    public function createStill(){
        
    }
    
    /**
     * insertVideo should generate an SQL QUery based on the stored file 
     * information, then insert it into the database using $db.
     */
    public function insertVideo(){
        
    }
    
    /**
     * loadFromAssoc should load the file information from an associative
     * array, like the result for an SQL query.
     * @param assoc_array $assoc is the result of a database query
     */
    public function loadFromAssoc($assoc){
        
    }
    
    /**
     * loadFromID should load the file information from an SQL quert based on
     * the file ID.
     */
    public function loadFromID(){
        
    }
    
    /**
     * displayEditCell should return a table cell that contains a GIF of the file,
     * sized appropriately, and links to the edit page for the file
     */
    public function displayEditCell(){
        
    }
    
    /**
     * displayDeleteCell should return a table cell that contains a GIF of the file,
     * sized appropriately, and links to the delete page for the file
     */
    public function displayDeleteCell(){
        
    }
}

