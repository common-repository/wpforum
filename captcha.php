<?php
global $wpdb, $table_prefix;
$root = dirname(dirname(dirname(dirname(__FILE__))));
if (file_exists($root.'/wp-load.php')) {
	// WP 2.6
	require_once($root.'/wp-load.php');
	} else {
	// before WP 2.6
	require_once($root.'/wp-config.php');
	}

$table_captcha = $table_prefix."forum_captcha";
$cnum = $wpdb->get_var("SELECT `code` FROM $table_captcha WHERE `id` = " . (int)$_GET['captcha_id']);


$acceptedChars = 'ABCEFGHJKMNPRSTVWXYZ123456789';
$stringlength = 5;
$contrast = 60;
$num_polygons = 3; // Number of triangles to draw.  0 = none
$num_ellipses = 6;  // Number of ellipses to draw.  0 = none
$num_lines = 0;  // Number of lines to draw.  0 = none
$num_dots = 0;  // Number of dots to draw.  0 = none
$min_thickness = 2;  // Minimum thickness in pixels of lines
$max_thickness = 8;  // Maximum thickness in pixles of lines
$min_radius = 5;  // Minimum radius in pixels of ellipses
$max_radius = 15;  // Maximum radius in pixels of ellipses
$object_alpha = 100; // How opaque should the obscuring objects be. 0 is opaque, 127 is transparent.


/*------------------------------------------------*/
$min_thickness = max(1,$min_thickness);
$max_thickness = min(20,$max_thickness);
$min_radius *= 3;// Make radii into height/width
$max_radius *= 3;// Make radii into height/width
$contrast = 255 * ($contrast / 100.0);
$o_contrast = 1.3 * $contrast;
$width = 20 * imagefontwidth (5);
$height = 4 * imagefontheight (5);
$image = imagecreatetruecolor ($width, $height);
imagealphablending($image, true);
if ($white_bg == 'true') {
$white = imagecolorallocatealpha($image,255,255,255,0);
imagefilledrectangle($image, 0, 0, $width - 1, $height - 1, $white);
	} else $black = imagecolorallocatealpha($image,0,0,0,0);

$rotated = imagecreatetruecolor (70, 70);
$x = 0;

for ($i = 0; $i < $stringlength; $i++) {
	$buffer = imagecreatetruecolor (20, 20);
	$buffer2 = imagecreatetruecolor (40, 40);
	
	// Get a random color
	$red = mt_rand(0,255);
	$green = mt_rand(0,255);
	$blue = 255 - sqrt($red * $red + $green * $green);
	$color = imagecolorallocate ($buffer, $red, $green, $blue);
	
	// Create character
	imagestring($buffer, 5, 0, 0, $cnum{$i}, $color);
	
	// Resize character
	imagecopyresized ($buffer2, $buffer, 0, 0, 0, 0, 25 + mt_rand(0,12), 25 + mt_rand(0,12), 20, 20);
	
	// Rotate characters a little
	if (function_exists('imagerotate'))
		$rotated = imagerotate($buffer2, mt_rand(-25, 25),imagecolorallocatealpha($buffer2,0,0,0,0));
	else
		imagecopymerge ($rotated, $buffer2, 15, 15, 0, 0, 40, 40, 100); 
	imagecolortransparent ($rotated, imagecolorallocatealpha($rotated,0,0,0,0));
	
	// Move characters around a little
	$y = mt_rand(1, 3);
	$x += mt_rand(2, 6); 
	imagecopymerge ($image, $rotated, $x, $y, 0, 0, 40, 40, 100);
	$x += 22;

	imagedestroy ($buffer); 
	imagedestroy ($buffer2); 
}

imagedestroy ($rotated);
if ($num_polygons > 0) for ($i = 0; $i < $num_polygons; $i++) {
	$vertices = array (
		mt_rand(-0.25*$width,$width*1.25),mt_rand(-0.25*$width,$width*1.25),
		mt_rand(-0.25*$width,$width*1.25),mt_rand(-0.25*$width,$width*1.25),
		mt_rand(-0.25*$width,$width*1.25),mt_rand(-0.25*$width,$width*1.25)
	);
	$color = imagecolorallocatealpha ($image, mt_rand(0,$o_contrast), mt_rand(0,$o_contrast), mt_rand(0,$o_contrast), $object_alpha);
	imagefilledpolygon($image, $vertices, 3, $color);  
}

if ($num_ellipses > 0) for ($i = 0; $i < $num_ellipses; $i++) {
	$x1 = mt_rand(0,$width);
	$y1 = mt_rand(0,$height);
	$color = imagecolorallocatealpha ($image, mt_rand(0,$o_contrast), mt_rand(0,$o_contrast), mt_rand(0,$o_contrast), $object_alpha);
//	$color = imagecolorallocate($image, mt_rand(0,$o_contrast), mt_rand(0,$o_contrast), mt_rand(0,$o_contrast));
	imagefilledellipse($image, $x1, $y1, mt_rand($min_radius,$max_radius), mt_rand($min_radius,$max_radius), $color);  
}

if ($num_lines > 0) for ($i = 0; $i < $num_lines; $i++) {
	$x1 = mt_rand(-$width*0.25,$width*1.25);
	$y1 = mt_rand(-$height*0.25,$height*1.25);
	$x2 = mt_rand(-$width*0.25,$width*1.25);
	$y2 = mt_rand(-$height*0.25,$height*1.25);
	$color = imagecolorallocatealpha ($image, mt_rand(0,$o_contrast), mt_rand(0,$o_contrast), mt_rand(0,$o_contrast), $object_alpha);
	imagesetthickness ($image, mt_rand($min_thickness,$max_thickness));
	imageline($image, $x1, $y1, $x2, $y2 , $color);  
}

if ($num_dots > 0) for ($i = 0; $i < $num_dots; $i++) {
	$x1 = mt_rand(0,$width);
	$y1 = mt_rand(0,$height);
	$color = imagecolorallocatealpha ($image, mt_rand(0,$o_contrast), mt_rand(0,$o_contrast), mt_rand(0,$o_contrast),$object_alpha);
	imagesetpixel($image, $x1, $y1, $color);
}

header('Content-type: image/png');
imagepng($image);
imagedestroy($image);
  
?>