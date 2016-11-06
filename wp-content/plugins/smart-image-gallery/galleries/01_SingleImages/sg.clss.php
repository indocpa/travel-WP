<?php 

if( !class_exists( 'SingleGalleryClass' ) )
{
	class SingleGalleryClass
	{
		var $text_domain;
		
		function __construct( $config )
		{
			$this->text_domain = $config[ 'text_domain' ];
		}
		
		// Displays gallery settings
		function settings()
		{	
			return '<div><input type="checkbox" name="caption" /> '.__( 'Display Media Captions', $this->text_domain ).'</div>
					<div><input type="checkbox" name="author" /> '.__( 'Display Media Authors', $this->text_domain ).'</div>';
					
		} // End settings
	} // End Class
}
?>