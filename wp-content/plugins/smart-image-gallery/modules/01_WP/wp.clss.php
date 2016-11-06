<?php 

if( !class_exists( 'WPMClass' ) )
{
	class WPMClass
	{
		var $text_domain;
		
		function __construct( $config )
		{
			$this->text_domain = $config[ 'text_domain' ];
		}
		
		// Displays and manages the settings section for internal media library
		function settings()
		{	
			?>
			<div class="postbox">
				<h3 class='hndle' style="padding:5px;"><span><?php _e( 'WordPress Media Settings', $this->text_domain ); ?></span></h3>
				<div class="inside">
					<?php _e( 'The media library of current WordPress is included by default', $this->text_domain ); ?>
				</div>
			</div>	
			<?php
		} // End settings
		
		function get( $terms, $from, $length )
		{
			global $wpdb;
			
			$output = new stdClass;
			
			// Get the attachments that include the search terms
			$posts = $wpdb->get_results( "SELECT * FROM $wpdb->posts WHERE post_type='attachment' AND post_status IN ('publish', 'inherit') AND post_mime_type LIKE 'image%' AND (post_title LIKE '%$terms%' OR post_content LIKE '%$terms%' OR post_name LIKE '%$terms%' OR post_excerpt LIKE '%$terms%') LIMIT $from,$length" );
			
			// The list of images
			$list = array();
			if( !empty( $posts ) && is_array( $posts ) )
			{	
				foreach( $posts as $post )
				{
					$image = new stdClass();
					$image->url = $post->guid;
					$image->title = ( ( !empty( $post->post_title ) ) ? $post->post_title : '' );
					$image->author = get_the_author_meta( 'display_name', $post->post_author );
					$image->origin = get_attachment_link( $post->ID );
					$list[] = $image;
				}
			}
			$output->results = $list;
			return $output;
		} // End get
		
		function isActive()
		{
			return true;
		} // End isActive
	} // End Class
}
?>