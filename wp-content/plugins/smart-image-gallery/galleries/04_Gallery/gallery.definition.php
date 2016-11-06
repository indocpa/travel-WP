<?php 
global $galleries;
$galleries[ 'gallery_classic' ] = array(
	'title' => 'Gallery Classic',
	'class_name' => 'ClassicGalleryClass',
	'class_path' => 'cg.clss.php',
	'javascript_admin' => array( 'cg.admin.js' ),
	'javascript_public' => array( 'jquery.mousewheel.js', 'jcarousellite_1.0.1.min.js', 'cg.public.js' ),
	'styles_public' => array( 'cg.public.css' )
); 

?>