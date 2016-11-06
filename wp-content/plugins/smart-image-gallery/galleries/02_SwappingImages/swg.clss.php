<?php 

if( !class_exists( 'SwappingGalleryClass' ) )
{
	class SwappingGalleryClass
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
					<div><input type="text" name="timeout" /> '.__( 'Timeout in seconds', $this->text_domain ).'</div>
					<div><input type="checkbox" name="caption" /> '.__( 'Display Media Captions', $this->text_domain ).'</div>
					<div><input type="checkbox" name="author" /> '.__( 'Display Media Authors', $this->text_domain ).'</div>';
					
		} // End settings
		
		// Generate the public code of gallery
		function gallery( $obj )
		{
			$id = 'gallery'.md5( microtime() );
			$obj->container = $id;
			return '<div id="'.$id.'" class="swapping-gallery" ></div>
					<script>
						jQuery( function($){
							if( typeof window[ "SwappingGallery" ] != "undefined" )
							{
								SwappingGallery( '.json_encode( $obj ).' );
							}
						} );
					</script>';
		} // End gallery
		
	} // End Class
}
?>