<?php 
global $galleries;
$galleries[ 'carousel' ] = array(
	'title' => 'Carousel Images',
	'class_name' => 'CarouselGalleryClass',
	'class_path' => 'cg.clss.php',
	'javascript_admin' => array( 'cg.admin.js' ),
	'javascript_public' => array( 'jquery.mousewheel.js', 'jcarousellite_1.0.1.min.js', 'cg.public.js' ),
	'styles_public' => array( 'cg.public.css' )
); 

?>