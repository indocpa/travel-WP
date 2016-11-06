<?php 

if( !class_exists( 'InstragramClass' ) )
{
	class InstagramClass
	{
		var $text_domain;
		
		function __construct( $config )
		{
			$this->text_domain = $config[ 'text_domain' ];
		}
		
		public static function get_token()
		{
			if( isset( $_GET[ 'smig_access_token' ] ) )
			{
				?>
				<script>
				if(window.location.hash) 
				{
					var hash = window.location.hash;
					window.opener.photoshow_instagram_set_access_token( hash.split( '=' )[1] );
				}
				</script>
				<?php
				exit;
			}	
		}
		
		// Displays and manages the settings section for Instagram account
		function settings()
		{	
			$mssg = '';
			if( isset( $_REQUEST[ 'photoshow_instagram_settings' ] ) )
			{
				$mssg .= '<div class="updated"><p><strong>'.__( "Instagram Settings Updated", $this->text_domain ).'</strong></p></div>';
				
				$photoshow_instagram_active = ( isset( $_POST[ 'photoshow_instagram_active' ] ) ) ? true : false;
				
				update_option( 'photoshow_instagram_active', $photoshow_instagram_active );
				update_option( 'photoshow_instagram_client_id', $_POST[ 'photoshow_instagram_client_id' ] );
				update_option( 'photoshow_instagram_access_token', $_POST[ 'photoshow_instagram_access_token' ] );
				
				if( ( empty( $_POST[ 'photoshow_instagram_client_id' ] ) || empty( $_POST[ 'photoshow_instagram_access_token' ] )) && $photoshow_instagram_active )
				{
					$mssg .= '<div class="error"><p><strong>'.__( "The Instagram Client ID and Access Token are required for searching on Instagram", $this->text_domain ).'</strong></p></div>';
				}
			}
			
			$photoshow_instagram_active 		= get_option( 'photoshow_instagram_active' );
			$photoshow_instagram_client_id 		= get_option( 'photoshow_instagram_client_id' );
			$photoshow_instagram_access_token 	= get_option( 'photoshow_instagram_access_token' );
			
			?>
			<div class="postbox">
				<input type="hidden" id="photoshow_instagram_settings" name="photoshow_instagram_settings" value="1" />
				<h3 class='hndle' style="padding:5px;"><span><?php _e( 'Instagram Settings', $this->text_domain ); ?></span></h3>
				<div class="inside">
					<?php print $mssg; ?>
					<table class="form-table">
						<tbody>
							<tr valign="top">
								<th scope="row">
									<label for="photoshow_instagram_active"><?php _e( 'Search in Instagram', $this->text_domain ); ?></label>
								</th>
								<td>
									<input type="checkbox" id="photoshow_instagram_active" name="photoshow_instagram_active" <?php if( $photoshow_instagram_active ) echo "CHECKED"; ?> />
								</td>
							</tr>
							<tr>
								<td colspan="2">
								Enter the URL: <span style="color:red;font-weight:bold;"><?php print photoshow_get_site_url(); ?>?smig_access_token=1</span> as the "Valid Redirect URIs" in the Client ID definition
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label for="photoshow_instagram_client_id"><?php _e( 'Enter the Instagram Client ID', $this->text_domain ); ?></label>
								</th>
								<td>
									<input type="text" id="photoshow_instagram_client_id" name="photoshow_instagram_client_id" value="<?php echo $photoshow_instagram_client_id; ?>" /><input type="button" value="Get the Token Access" onclick="photoshow_instagram_get_access_token()" /><br />
									<p><a href="http://instagram.com/developer/clients/register/" target="_blank" style="text-decoration:none;"><?php _e( 'If you do not have a Instagram Client ID, click here', $this->text_domain ); ?></a></p>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label for="photoshow_instagram_access_token"><?php _e( 'Enter the Instagram Access Token', $this->text_domain ); ?></label>
								</th>
								<td>
									<input type="text" id="photoshow_instagram_access_token" name="photoshow_instagram_access_token" value="<?php echo $photoshow_instagram_access_token; ?>" style="width:80%;" /></p>
								</td>
							</tr>
							
						</tbody>
					</table>
				</div>
			</div>	
			<script>
				function photoshow_instagram_get_access_token()
				{
					var $  = jQuery,
						ci = $.trim( $( '#photoshow_instagram_client_id' ).val() ),
						ru = encodeURIComponent( '<?php print photoshow_get_site_url(); ?>?smig_access_token=1' );
						
					if( ci == '' )
					{
						alert( '<?php echo esc_js(__( 'The Client ID is required', $this->text_domain )); ?>' );
					}
					else
					{
						photoshow_access_token_window = window.open( 'https://instagram.com/oauth/authorize/?scope=public_content&client_id='+ci+'&redirect_uri='+ru+'&response_type=token' );
					}	
				}
				function photoshow_instagram_set_access_token( access_token )
				{
					var $  = jQuery;
					if( typeof access_token != 'undefined' ) 
					{
						$( '#photoshow_instagram_access_token' ).val( $.trim( access_token ) );	
					}
					
					if( typeof photoshow_access_token_window != 'undefined' )
					{
						photoshow_access_token_window.close();
					}	
				}
				
			</script>
			<?php
			
		} // End settings
		
		function get( $terms, $from, $length )
		{	

			$output = new stdClass;
			$output->results = array();
			
			$client_id 		= get_option( 'photoshow_instagram_client_id' );
			$access_token 	= get_option( 'photoshow_instagram_access_token' );
			
			if( empty( $client_id ) || empty( $access_token ) )
			{
				return $output;
			}
			
			if( strpos( $terms, ' ' ) !== false )
			{
				$output->error = __( 'Instagram search for only one term', $this->text_domain );
				return $output;
			}

			$url = "";
			
			if( $from )
			{
				if( !empty( $_SESSION[ 'instagram_next' ] ) )
				{
					$url = $_SESSION[ 'instagram_next' ]."&count=".$length;
				}
				else
				{
					$url = "https://api.instagram.com/v1/tags/".urlencode( $terms )."/media/recent?client_id=".$client_id.'&count='.$length.'&access_token='.$access_token;
				}
			}
			else
			{
				$url = "https://api.instagram.com/v1/tags/".urlencode( $terms )."/media/recent?client_id=".$client_id.'&count='.$length.'&access_token='.$access_token;
			}
		
			$response = wp_remote_get( $url, array('timeout' => 120) );
			
			if( !is_wp_error( $response ) )
			{
				// Create the output
				$phpObj =  json_decode( $response[ 'body' ] );
				
				if( !is_null( $phpObj ) )
				{
					$list = array();
					try
					{
						if( isset( $phpObj->pagination ) && !empty( $phpObj->pagination->next_url ) )
						{
							$_SESSION[ 'instagram_next' ] =  $phpObj->pagination->next_url;
						}
						
						$results = $phpObj->data;
						if( !empty( $results ) && is_array( $results ) )
						{	
							foreach( $results as $entry )
							{
								if( !empty( $entry->images ) )
								{
									$image = new stdClass();
									$image->url = $entry->images->standard_resolution->url;
									$image->author = ( !empty( $entry->user ) ) ? utf8_encode( $entry->user->full_name ) : '';
									$image->title = ( !empty( $entry->caption ) ) ? utf8_encode( $entry->caption->text ) : '';
									$image->origin = $entry->link;
									$list[] = $image;
								}
							}
						}
					}
					catch( Exception $e ){}
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
			return get_option( 'photoshow_instagram_active' );
		} // End isActive
		
	} // End Class
}

add_action( 'init', array( 'InstagramClass', 'get_token' ), 1 );
?>