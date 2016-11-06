<?php 

if( !class_exists( 'ClassicGalleryClass' ) )
{
	class ClassicGalleryClass
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
					<div><input type="checkbox" name="autoplay" /> '.__( 'Autoplay', $this->text_domain ).'</div>
					<div><input type="checkbox" name="caption" /> '.__( 'Display Media Captions', $this->text_domain ).'</div>
					<div><input type="checkbox" name="author" /> '.__( 'Display Media Authors', $this->text_domain ).'</div>
					<div><input type="checkbox" name="thumbnails" /> '.__( 'Display Thumbnails', $this->text_domain ).'</div>
					<div><input type="text" name="thumbnail_width" /> '.__( 'Thumbnail Width', $this->text_domain ).'</div>
					<div><input type="text" name="thumbnail_height" /> '.__( 'Thumbnail Height', $this->text_domain ).'</div>
					<div><input type="checkbox" name="vertical" /> '.__( 'Display Thumbnail List Vertical', $this->text_domain ).'</div>';
					
		} // End settings
		
		// Generate the public code of gallery
		function gallery( $obj )
		{
			$id = 'gallery'.md5( microtime() );
			$obj->container = $id;
			return '<div id="'.$id.'" class="classic-gallery"></div>
					<script>
						jQuery( function($){
							if( typeof window[ "ClassicGallery" ] != "undefined" )
							{
								ClassicGallery( '.json_encode( $obj ).' );
							}
						} );
					</script>';
		} // End gallery
		
	} // End Class
}
?>