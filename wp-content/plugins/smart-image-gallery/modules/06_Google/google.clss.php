<?php 

if( !class_exists( 'GoogleClass' ) )
{
	class GoogleClass
	{
		var $text_domain;
		
		function __construct( $config )
		{
			$this->text_domain = $config[ 'text_domain' ];
		}
		
		// Displays and manages the settings section for Google search
		function settings()
		{	
			$mssg = '';
			if( isset( $_REQUEST[ 'photoshow_google_settings' ] ) )
			{
				$photoshow_google_active = ( isset( $_POST[ 'photoshow_google_active' ] ) ) ? true : false;
				$photoshow_google_cx = ( !empty( $_POST[ 'photoshow_google_cx' ] ) ) ? trim( $_POST[ 'photoshow_google_cx' ] ) : '';
				$photoshow_google_api_key = ( !empty( $_POST[ 'photoshow_google_api_key' ] ) ) ? trim( $_POST[ 'photoshow_google_api_key' ] ) : '';
				
				update_option( 'photoshow_google_active', $photoshow_google_active );
				update_option( 'photoshow_google_cx', $photoshow_google_cx );
				update_option( 'photoshow_google_api_key', $photoshow_google_api_key );
				
				$mssg .= '<div class="updated"><p><strong>'.__( "Google Search Settings Updated", $this->text_domain ).'</strong></p></div>';
			}	
			$photoshow_google_active = get_option( 'photoshow_google_active' );
			$photoshow_google_cx = get_option( 'photoshow_google_cx', '' );
			$photoshow_google_api_key = get_option( 'photoshow_google_api_key', '' );
			
			
			?>
			<div class="postbox">
				<input type="hidden" id="photoshow_google_settings" name="photoshow_google_settings" value="1" />
				<h3 class='hndle' style="padding:5px;"><span><?php _e( 'Google Search Settings', $this->text_domain ); ?></span></h3>
				<div class="inside">
					<?php print $mssg; ?>
					<table class="form-table">
						<tbody>
							<tr valign="top">
								<th scope="row">
									<label for="photoshow_google_active"><?php _e( 'Search in Google', $this->text_domain ); ?></label>
								</th>
								<td>
									<input type="checkbox" id="photoshow_google_active" name="photoshow_google_active" <?php if( $photoshow_google_active ) echo "CHECKED"; ?> />
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label for="photoshow_google_api_key"><?php _e( 'Enter the Google API Key', $this->text_domain ); ?></label>
								</th>
								<td>
									<input type="text" id="photoshow_google_api_key" name="photoshow_google_api_key" value="<?php echo $photoshow_google_api_key; ?>" /><br />
									<p><a href="https://console.developers.google.com" target="_blank" style="text-decoration:none;"><?php _e( 'If you do not have a Google API Key, click here', $this->text_domain ); ?></a></p>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label for="photoshow_google_cx"><?php _e( 'The custom search engine ID to scope this search query (cx)', $this->text_domain ); ?></label>
								</th>
								<td>
									<input type="text" id="photoshow_google_cx" name="photoshow_google_cx" value="<?php echo $photoshow_google_cx; ?>" /><br />
									<p><a href="https://cse.google.com/cse/" target="_blank" style="text-decoration:none;"><?php _e( 'If you do not have a custom search engine ID, click here', $this->text_domain ); ?></a></p>
								</td>
							</tr>
						</tbody>
					</table>
					<p>
					<?php 
						_e(
							'The Picasa (or Google Photos) and Facebook modules have been removed because Google has modified its API, and now it is possible integrate both services with the Google Module. Configure the custom search engine for searching in the entire web (the option: <b>Search the entire web but emphasize included sites</b>)', 
							$this->text_domain 
						); 
					?>
					</p>
					<p>
					<?php 
						_e(
							'and then for searching terms in a specific platform, use the <b>"site"</b> attribute, with the website\'s domain in the search box. For example, to get "Dogs" images from Facebook, use the searching term:<br><br><b>dogs site:facebook.com</b><br><br>And for searching "Dogs" pictures in Picasa, use the searching term:<br><br><b>dogs site:picasaweb.google.com</b>', 
							$this->text_domain 
						); 
					?>
					</p>
				</div>
			</div>	
			<?php
			
		} // End settings
		
		
		function get( $terms, $from, $length )
		{ 	
			$cx = get_option( 'photoshow_google_cx', '' );
			$api_key = get_option( 'photoshow_google_api_key', '' );
			
			
			// $length parameter is maintained for compatibility only
			$output = new stdClass;
			$output->results = array();
			$from++;
			$parameters = "&cx=".urlencode($cx)."&searchType=image";
			
			if( preg_match( '/(site\s*\:\s*[^\s]+)/i', $terms, $matchs) )
			{
				$terms = str_replace( $matchs[1], '', $terms );
				preg_match( '/site\s*\:\s*([^\s]+)/i', $matchs[1], $matchs1);
				$parameters .= "&siteSearch=".urlencode($matchs1[1])."&siteSearchFilter=i"; 	
			}
			$parameters = "q=".urlencode( trim( $terms ) ).$parameters."&key=".$api_key."&start=".$from;
			$response = wp_remote_get( "https://www.googleapis.com/customsearch/v1?".$parameters, array('timeout' => 120) );
			if( !is_wp_error( $response ) )
			{
				
				// Create the output
				$phpObj =  json_decode( $response[ 'body' ] );
				if( !is_null( $phpObj ) && !empty( $phpObj->items ) )
				{
					$list = array();
					try
					{
						$items  = $phpObj->items;
						if( !empty( $items ) && is_array( $items ) )
						{	
							foreach( $items as $item )
							{
								try{
									$item = (array)$item;
									$image = new stdClass();
									$image->title  = ( !empty( $item[ 'title' ] ) ) ? utf8_encode( $item[ 'title' ] ) : '';
									$image->url = ( !empty( $item[ 'link'  ] ) ) ? $item[ 'link' ] : '';
									$image->origin = '';
									
									if( isset( $item[ 'image' ] ) )
									{
										$item[ 'image' ] = (array)$item[ 'image' ];
										if( isset( $item[ 'image' ]['contextLink'] ) ) $image->origin = $item[ 'image' ]['contextLink'];
									}
									$list[] = $image;
								}
								catch( Exception $e){}
							}
						}
					}
					catch( Exception $e){}
					$output->results = $list;
				}
				else
				{
					$output->error = __( 'There are no images associated to the search terms', $this->text_domain );
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
			return get_option( 'photoshow_google_active' );
		} // End isActive
		
	} // End Class
}
?>