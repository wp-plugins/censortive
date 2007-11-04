<?php
/*
    Dynamic Text Image Generator
	Original Script	By Stewart Rosenberger
    http://www.stewartspeak.com/headings/    
	Modifications By Ryan McLaughlin for Censortive Wordpress Plugin
	http://www.daobydesign.com/blog/censortive
	
    This script generates PNG images of text, written in
    the font/size that you specify. These PNG images are passed
    back to the browser. Optionally, they can be cached for later use. 
    If a cached image is found, a new image will not be generated,
    and the existing copy will be sent to the browser.
*/

/*---------------------------------------------------------------------------
   For basic usage, you SHOULD NOT need to edit anything here.
   If you need to further customize this script's abilities, make sure you
   are familiar with PHP and its image handling capabilities.
---------------------------------------------------------------------------*/

/* These are passed via variables set in WordPress' backend. Don't edit this, edit in the WP Options menu. */
$font_file = 'fonts/' . $_GET['font'];
$font_size = $_GET['fsize'];
$font_color = '#' . $_GET['fcolor'];
$background_color = '#' . $_GET['bgcol'];
$transparent_background = $_GET['trans'];
$cache_images = $_GET['cache'];
$cache_folder = $_GET['cachef'];


$mime_type = 'image/png' ;
$extension = '.png' ;
$send_buffer_size = 4096 ;

// check for GD support
if(!function_exists('ImageCreate'))
    fatal_error('Error: Server does not support PHP image generation') ;

// clean up text
	if(empty($_GET['code']))
		fatal_error('Error: No text specified.') ;
		
	$code = $_GET['code'] ;
	if(get_magic_quotes_gpc())
		$code = stripslashes($code) ;
	$code = javascript_to_html($code) ;

	$datFile = "codeword.dat";
	$fh = fopen($datFile, 'r');
	$theData = fread($fh, filesize($datFile));
	fclose($fh);

	$g = 0;
	$sl = strlen($theData);
	while ($sl > 0) { //This chops up the inported data into 'fake' and 'real'.
		$g = $g + 1;
		$comloc = strpos($theData,',');
		$codewords[$g] = substr($theData, 0, $comloc); 	
		$theData = substr($theData, $comloc + 1, $sl);
		$sl = strlen($theData);

		$eqloc = strpos($codewords[$g],'=');
		$cwlen = strlen($codewords[$g]);
		$fake[$g] = trim(substr($codewords[$g], 0, $eqloc));
		$real[$g] = trim(substr($codewords[$g], $eqloc+1,$cwlen));
		}
	$text=$code; //Just in case it wasn't put in the data file.
	for ($i = 1; $i <= count($fake); $i++) {
		if ($code == $fake[$i]) {
			$text = $real[$i]; //changes what the image text should be.
		}
	}

// look for cached copy, send if it exists
$hash = md5(basename($font_file) . $font_size . $font_color .
            $background_color . $transparent_background . $text) ;
$cache_filename = $cache_folder . '/' . $hash . $extension ;
if($cache_images && ($file = @fopen($cache_filename,'rb')))
{
    header('Content-type: ' . $mime_type) ;
    while(!feof($file))
        print(($buffer = fread($file,$send_buffer_size))) ;
    fclose($file) ;
    exit ;
}

// check font availability
$font_found = is_readable($font_file) ;
if(!$font_found)
{
    fatal_error('Error: The server is missing the specified font.') ;
}

// create image
$background_rgb = hex_to_rgb($background_color) ;
$font_rgb = hex_to_rgb($font_color) ;
$dip = get_dip($font_file,$font_size) ;
$box = @ImageTTFBBox($font_size,0,$font_file,$text) ;
$image = @ImageCreate(abs($box[2]-$box[0]),abs($box[5]-$dip)) ;
if(!$image || !$box)
{
    fatal_error('Error: The server could not create this heading image.') ;
}

// allocate colors and draw text
$background_color = @ImageColorAllocate($image,$background_rgb['red'],
    $background_rgb['green'],$background_rgb['blue']) ;
$font_color = ImageColorAllocate($image,$font_rgb['red'],
    $font_rgb['green'],$font_rgb['blue']) ;   
ImageTTFText($image,$font_size,0,-$box[0],abs($box[5]-$box[3])-$box[1],
    $font_color,$font_file,$text) ;

// set transparency
if($transparent_background)
    ImageColorTransparent($image,$background_color) ;

header('Content-type: ' . $mime_type) ;
ImagePNG($image) ;

// save copy of image for cache
if($cache_images)
{
    @ImagePNG($image,$cache_filename) ;
}

ImageDestroy($image) ;
exit ;


/*
	try to determine the "dip" (pixels dropped below baseline) of this
	font for this size.
*/
function get_dip($font,$size)
{
	$test_chars = 'abcdefghijklmnopqrstuvwxyz' .
			      'ABCDEFGHIJKLMNOPQRSTUVWXYZ' .
				  '1234567890' .
				  '!@#$%^&*()\'"\\/;.,`~<>[]{}-+_-=' ;
	$box = @ImageTTFBBox($size,0,$font,$test_chars) ;
	return $box[3] ;
}


/*
    attempt to create an image containing the error message given. 
    if this works, the image is sent to the browser. if not, an error
    is logged, and passed back to the browser as a 500 code instead.
*/
function fatal_error($message)
{
    // send an image
    if(function_exists('ImageCreate'))
    {
        $width = ImageFontWidth(5) * strlen($message) + 10 ;
        $height = ImageFontHeight(5) + 10 ;
        if($image = ImageCreate($width,$height))
        {
            $background = ImageColorAllocate($image,255,255,255) ;
            $text_color = ImageColorAllocate($image,0,0,0) ;
            ImageString($image,5,5,5,$message,$text_color) ;    
            header('Content-type: image/png') ;
            ImagePNG($image) ;
            ImageDestroy($image) ;
            exit ;
        }
    }

    // send 500 code
    header("HTTP/1.0 500 Internal Server Error") ;
    print($message) ;
    exit ;
}


/* 
    decode an HTML hex-code into an array of R,G, and B values.
    accepts these formats: (case insensitive) #ffffff, ffffff, #fff, fff 
*/    
function hex_to_rgb($hex)
{
    // remove '#'
    if(substr($hex,0,1) == '#')
        $hex = substr($hex,1) ;

    // expand short form ('fff') color
    if(strlen($hex) == 3)
    {
        $hex = substr($hex,0,1) . substr($hex,0,1) .
               substr($hex,1,1) . substr($hex,1,1) .
               substr($hex,2,1) . substr($hex,2,1) ;
    }

    if(strlen($hex) != 6)
        fatal_error('Error: Invalid color "'.$hex.'"') ;

    // convert
    $rgb['red'] = hexdec(substr($hex,0,2)) ;
    $rgb['green'] = hexdec(substr($hex,2,2)) ;
    $rgb['blue'] = hexdec(substr($hex,4,2)) ;

    return $rgb ;
}


/*
    convert embedded, javascript unicode characters into embedded HTML
    entities. (e.g. '%u2018' => '&#8216;'). returns the converted string.
*/
function javascript_to_html($text)
{
    $matches = null ;
    preg_match_all('/%u([0-9A-F]{4})/i',$text,$matches) ;
    if(!empty($matches)) for($i=0;$i<sizeof($matches[0]);$i++)
        $text = str_replace($matches[0][$i],
                            '&#'.hexdec($matches[1][$i]).';',$text) ;

    return $text ;
}

?>