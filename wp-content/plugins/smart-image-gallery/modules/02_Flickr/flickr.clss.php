<?php 

if( !class_exists( 'FlickrClass' ) )
{
	class FlickrClass
	{
		var $text_domain;
		
		function __construct( $config )
		{
			$this->text_domain = $config[ 'text_domain' ];
		}
		
		// Displays and manages the settings section for Flickr account
		function settings()
		{	
			$mssg = '';
			if( isset( $_REQUEST[ 'photoshow_flickr_settings' ] ) )
			{
				$mssg .= '<div class="updated"><p><strong>'.__( "Flickr Settings Updated", $this->text_domain ).'</strong></p></div>';
				
				$photoshow_flickr_active = ( isset( $_POST[ 'photoshow_flickr_active' ] ) ) ? true : false;
				
				update_option( 'photoshow_flickr_active', $photoshow_flickr_active );
				update_option( 'photoshow_flickr_api_key', $_POST[ 'photoshow_flickr_api_key' ] );
				update_option( 'photoshow_flickr_username', trim( $_POST[ 'photoshow_flickr_username' ] ) );
				update_option( 'photoshow_flickr_search_by_album', ( !empty($_POST[ 'photoshow_flickr_search_by_album' ]) ) ? true : false );
				
				if( empty( $_POST[ 'photoshow_flickr_api_key' ] ) && $photoshow_flickr_active )
				{
					$mssg .= '<div class="error"><p><strong>'.__( "The Flickr API Key is required for searching on Flickr", $this->text_domain ).'</strong></p></div>';
				}
			}
			
			$photoshow_flickr_active = get_option( 'photoshow_flickr_active' );
			$photoshow_flickr_api_key = get_option( 'photoshow_flickr_api_key' );
			$photoshow_flickr_username = get_option( 'photoshow_flickr_username' );
			$photoshow_flickr_search_by_album = get_option( 'photoshow_flickr_search_by_album' );
			$photoshow_flickr_api_key = ( $photoshow_flickr_api_key === false ) ? '' : $photoshow_flickr_api_key;
			$photoshow_flickr_username = ( $photoshow_flickr_username === false ) ? '' : $photoshow_flickr_username;
			
			?>
			<div class="postbox">
				<input type="hidden" id="photoshow_flickr_settings" name="photoshow_flickr_settings" value="1" />
				<h3 class='hndle' style="padding:5px;"><span><?php _e( 'Flickr Settings', $this->text_domain ); ?></span></h3>
				<div class="inside">
					<?php print $mssg; ?>
					<table class="form-table">
						<tbody>
							<tr valign="top">
								<th scope="row">
									<label for="photoshow_flickr_active"><?php _e( 'Search in Flickr', $this->text_domain ); ?></label>
								</th>
								<td>
									<input type="checkbox" id="photoshow_flickr_active" name="photoshow_flickr_active" <?php if( $photoshow_flickr_active ) echo "CHECKED"; ?> />
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label for="photoshow_flickr_api_key"><?php _e( 'Enter the Flickr API Key', $this->text_domain ); ?></label>
								</th>
								<td>
									<input type="text" id="photoshow_flickr_api_key" name="photoshow_flickr_api_key" value="<?php echo $photoshow_flickr_api_key; ?>" /><br />
									<p><a href="http://www.flickr.com/services/apps/create/apply" target="_blank" style="text-decoration:none;"><?php _e( 'If you do not have a Flickr API Key, click here', $this->text_domain ); ?></a></p>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label for="photoshow_flickr_username"><?php _e( 'To restrict the results to an user, enter its username', $this->text_domain ); ?></label>
								</th>
								<td>
									<input type="text" id="photoshow_flickr_username" name="photoshow_flickr_username" value="<?php echo $photoshow_flickr_username; ?>" /><br />
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label for="photoshow_flickr_search_by_album"><?php _e( 'Search by album Id', $this->text_domain ); ?></label>
								</th>
								<td>
									<input type="checkbox" id="photoshow_flickr_search_by_album" name="photoshow_flickr_search_by_album" <?php echo (($photoshow_flickr_search_by_album) ? 'CHECKED' : ''); ?> /> <span><?php _e("Selecting this option should be entered as the search criteria in the insertion process, the album's ID.", $this->text_domain); ?></span>
									
								</td>
							</tr>
							
						</tbody>
					</table>
				</div>
			</div>	
			<?php
		} // End settings
		
		function get( $terms, $from, $length )
		{
			$output = new stdClass;
			$output->results = array();
			$key = get_option( 'photoshow_flickr_api_key' );
			if( $key === false || empty( $key ) )
			{
				return $output;
			}
			
			$photoshow_flickr_username = get_option( 'photoshow_flickr_username' );
			$photoshow_flickr_search_by_album = get_option( 'photoshow_flickr_search_by_album' );
			
			if( !empty( $photoshow_flickr_search_by_album ) )
			{
				$yql_query = 'select * from flickr.photos.info(0,'.$length.') where api_key="'.$key.'" and photo_id in ( SELECT id FROM flickr.photosets.photos('.$from.','.$length.') WHERE photoset_id="'.$terms.'" and api_key="'.$key.'")';
			}
			else
			{
				if( !empty( $photoshow_flickr_username ) )
				{
					$yql_query = 'select * from flickr.photos.info(0,'.$length.') where api_key="'.$key.'" and photo_id in(select id from flickr.photos.search('.$from.','.$length.') where api_key="'.$key.'" and ( text="'.$terms.'" or title LIKE "%'.$terms.'%" ) and user_id in ( select id from flickr.people.findbyusername where api_key="'.$key.'" and username="'.$photoshow_flickr_username.'" ) )';
				}
				else
				{
					$yql_query = 'select * from flickr.photos.info(0,'.$length.') where api_key="'.$key.'" and photo_id in(select id from flickr.photos.search('.$from.','.$length.') where api_key="'.$key.'" and license=4 and text="'.$terms.'")';
				}
			}	
	 		$response = wp_remote_get( "https://query.yahooapis.com/v1/public/yql?q=".urlencode( $yql_query )."&format=json", array('timeout' => 120) );
	 		if( !is_wp_error( $response ) )
			{
				// Create the output
				$phpObj =  json_decode( $response[ 'body' ] );
				$results = @$phpObj->query->results;
				
				if( !is_null( $results ) )
				{
					// The list of images
					$list = array();
					$photos = $results->photo;
					if( !empty( $photos ) )
					{	
						if( is_object( $photos ) ){ $photos = array( $photos ); }		
						if( is_array( $photos ) )
						{	
							foreach( $photos as $p )
							{
								$image = new stdClass();
								$image->url = 'http://farm'.$p->farm.'.static.flickr.com/'.$p->server.'/'.$p->id.'_'.$p->secret.'.jpg';
								$image->origin = ( ( !empty( $p->urls ) && !empty( $p->urls->url ) && !empty( $p->urls->url->content ) ) ?  $p->urls->url->content : '' );
								$image->title = ( ( !empty( $p->title ) ) ? utf8_encode( $p->title ) : '' );
								$image->author = ( ( !empty( $p->owner ) ) ? utf8_encode( ( ( !empty( $p->owner->realname ) ) ? $p->owner->realname :  $p->owner->username ) ) : '' );
								$list[] = $image;
							}
						}	
					}
					$output->results = $list;
				}
			}
			else
			{
				$output->error = __( $response->get_error_message(), $this->text_domain );
			}
			
			return $output;
			
		} // End get
		
		function isActive()
		{
			return get_option( 'photoshow_flickr_active' ) && get_option( 'photoshow_flickr_api_key' );
		} // End isActive
	} // End Class
}
?>
