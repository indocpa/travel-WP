<?php 

if( !class_exists( 'CarouselGalleryClass' ) )
{
	class CarouselGalleryClass
	{
		var $text_domain;
		
		function __construct( $config )
		{
			$this->text_domain = $config[ 'text_domain' ];
		}
		
		// Displays gallery settings
		function settings()
		{	
			return '<div><input type="text" name="width" /> '.__( 'Width', $this->text_domain ).'</div>
					<div><input type="text" name="height" /> '.__( 'Height', $this->text_domain ).'</div>
					<div><input type="text" name="visible" value="3" /> '.__( 'Images Visible At The Same Time', $this->text_domain ).'</div>
					<div><input type="checkbox" name="vertical" /> '.__( 'Display Vertical Carousel', $this->text_domain ).'</div>
					<div><input type="checkbox" name="nextprevbtn" CHECKED /> '.__( 'Display Next Previous Buttons', $this->text_domain ).'</div>
					<div><input type="checkbox" name="gobtn" /> '.__( 'Display Go To Buttons', $this->text_domain ).'</div>
					<div><input type="checkbox" name="customcolors" /> '.__( 'Display Go To Buttons With Custom Colors', $this->text_domain ).'</div>
					<div><input type="checkbox" name="mousewheel" /> '.__( 'Use Mouse Wheel Control. The option will be applied only if autoplay is unchecked', $this->text_domain ).'</div>
					<div><input type="checkbox" name="autoplay" /> '.__( 'Autoplay', $this->text_domain ).'</div>
					<div><input type="checkbox" name="caption" /> '.__( 'Display Media Captions', $this->text_domain ).'</div>
					<div><input type="checkbox" name="author" /> '.__( 'Display Media Authors', $this->text_domain ).'</div>';
					
		} // End settings
		
		// Generate the public code of gallery
		function gallery( $obj )
		{
			$id = 'gallery'.md5( microtime() );
			$obj->container = $id;
			return '<div id="'.$id.'"  class="carousel-gallery" ></div>
					<script>
						jQuery( function($){
							if( typeof window[ "CarouselGallery" ] != "undefined" )
							{
								CarouselGallery( '.json_encode( $obj ).' );
							}
						} );
					</script>';
		} // End gallery
		
	} // End Class
}
?>