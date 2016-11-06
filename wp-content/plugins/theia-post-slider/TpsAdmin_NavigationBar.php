<?php

/*
 * Copyright 2012-2015, Theia Post Slider, WeCodePixels, http://wecodepixels.com
 */

class TpsAdmin_NavigationBar {
	public $showPreview = true;

	public function echoPage() {
		?>
		<form method="post" action="options.php">
			<?php settings_fields( 'tps_options_nav' ); ?>
			<?php $options = get_option( 'tps_nav' ); ?>

			<h3><?php _e( "Navigation Bar Settings", 'theia-post-slider' ); ?></h3>
			<table class="form-table">
				<tr valign="top">
					<th scope="row">
						<label for="tps_navigation_text"><?php _e( "Navigation text:", 'theia-post-slider' ); ?></label>
					</th>
					<td>
						<input type="text"
						       id="tps_navigation_text"
						       name="tps_nav[navigation_text]"
						       value="<?php echo htmlentities($options['navigation_text']); ?>"
						       class="regular-text"
						       onchange="updateSlider()" />

						<p class="description">Variables: <b>%{currentSlide}</b> and <b>%{totalSlides}</b></p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="tps_helper_text"><?php _e( "Helper text:", 'theia-post-slider' ); ?></label>
					</th>
					<td>
						<input type="text"
						       id="tps_helper_text"
						       name="tps_nav[helper_text]"
						       value="<?php echo htmlentities($options['helper_text']); ?>"
						       class="regular-text"
						       onchange="updateSlider()" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="tps_prev_button_text"><?php _e( "Previous button text:", 'theia-post-slider' ); ?></label>
					</th>
					<td>
						<input type="text"
						       id="tps_prev_button_text"
						       name="tps_nav[prev_text]"
						       value="<?php echo htmlentities($options['prev_text']); ?>"
						       class="regular-text"
						       onchange="updateSlider()" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="tps_next_button_text"><?php _e( "Next button text:", 'theia-post-slider' ); ?></label>
					</th>
					<td>
						<input type="text"
						       id="tps_next_button_text"
						       name="tps_nav[next_text]"
						       value="<?php echo htmlentities($options['next_text']); ?>"
						       class="regular-text"
						       onchange="updateSlider()" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="tps_button_width"><?php _e( "Button width (px):", 'theia-post-slider' ); ?></label>
					</th>
					<td>
						<input type="number"
						       class="small-text"
						       id="tps_button_width"
						       name="tps_nav[button_width]"
						       value="<?php echo htmlentities($options['button_width']); ?>"
						       onchange="updateSlider()" />

						<p class="description">
							Only works for classic themes. Use this if you want both buttons to have the same width.
							Insert "0" for no fixed width.
						</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="tps_nav_horizontal_position"><?php _e( "Horizontal position:", 'theia-post-slider' ); ?></label>
					</th>
					<td>
						<select id="tps_nav_horizontal_position"
						        name="tps_nav[nav_horizontal_position]"
						        onchange="updateSlider()">
							<?php
							foreach ( TpsOptions::get_button_horizontal_positions() as $key => $value ) {
								$output = '<option value="' . $key . '"' . ( $key == $options['nav_horizontal_position'] ? ' selected' : '' ) . '>' . $value . '</option>' . "\n";
								echo $output;
							}
							?>
						</select>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="tps_nav_vertical_position"><?php _e( "Vertical position:", 'theia-post-slider' ); ?></label>
					</th>
					<td>
						<select id="tps_nav_vertical_position"
						        name="tps_nav[nav_vertical_position]"
						        onchange="updateSlider()">
							<?php
							foreach ( TpsOptions::get_button_vertical_positions() as $key => $value ) {
								$output = '<option value="' . $key . '"' . ( $key == $options['nav_vertical_position'] ? ' selected' : '' ) . '>' . $value . '</option>' . "\n";
								echo $output;
							}
							?>
						</select>
					</td>
				</tr>
			</table>
			<br>

			<h3><?php _e( "Post Navigation", 'theia-post-slider' ); ?></h3>
			<table class="form-table">
				<tr valign="top">
					<th scope="row">
						<label><?php _e( "Button behavior:", 'theia-post-slider' ); ?></label>
					</th>
					<td>
						<label>
							<input id="tps_standard_navigation"
							       type="radio"
							       name="tps_nav[button_behaviour]"
							       onclick="updateButtonBehaviour();"
							       value="standard" <?php echo $options['button_behaviour'] === 'standard' ? ' checked' : '' ?>>
							Standard
						</label>

						<p class="description">
							The "previous" button on the <b>first</b> slide will be disabled, as well as the "next" button on the <b>last</b> slide.
						</p>
						<br>

						<label>
							<input id="tps_loop_slides"
							       type="radio"
							       name="tps_nav[button_behaviour]"
							       onclick="updateButtonBehaviour();"
							       value="loop" <?php echo $options['button_behaviour'] === 'loop' ? ' checked' : '' ?>>
							Loop through the post's slides
						</label>

						<p class="description">
							Clicking the "previous" button on the <b>first</b> slide will lead you to the <b>last</b> slide of your post,
							and clicking the "next" button on the <b>last</b> slide will lead you to the <b>first</b> slide of your post.
						</p>
						<br>

						<label>
							<input id="tps_post_navigation"
							       type="radio"
							       name="tps_nav[button_behaviour]"
							       onclick="updateButtonBehaviour();"
							       value="post" <?php echo $options['button_behaviour'] === 'post' ? ' checked' : '' ?>>
							Enable additional navigation between posts
						</label>

						<p class="description">
							Clicking the "previous" button on the <b>first</b> slide will open the previous <b>older</b>
							post, and clicking the "next" button on the <b>last</b> slide will open the next
							<b>newer</b> post.
						</p>
						<br>
						<input type="hidden" name="tps_nav[tps_post_navigation_inverse]" value="false">
						<label>
							<input id="tps_post_navigation_inverse"
							       type="checkbox"
							       name="tps_nav[post_navigation_inverse]"
							       value="true"<?php echo $options['post_navigation_inverse'] ? ' checked' : '' ?>>
							Inverse post navigation
						</label>

						<p class="description">
							Swap the buttons so that the "next" button on the <b>last</b> slide will open the next <b>older</b>
							post, instead of the <b>newer</b> one.
						</p>
						<br>
						<input type="hidden" name="tps_nav[tps_post_navigation_same_category]" value="false">
						<label>
							<input id="tps_post_navigation_same_category"
							       type="checkbox"
							       name="tps_nav[post_navigation_same_category]"
							       value="true"<?php echo $options['post_navigation_same_category'] ? ' checked' : '' ?>>
							Only for posts from the same category
						</label>

						<p class="description">
							The "previous" and "next" buttons will only navigate through posts of the same category.
						</p>
						<br>
						<input type="hidden" name="tps_nav[disable_keyboard_shortcuts]" value="false">
						<label>
							<input type="checkbox"
							       name="tps_nav[disable_keyboard_shortcuts]"
							       value="true"<?php echo $options['disable_keyboard_shortcuts'] ? ' checked' : '' ?>>
							Disable keyboard shortcuts
						</label>

						<p class="description">
							The "previous" and "next" buttons will only navigate through posts of the same category.
						</p>
						<br>
				        <input type="hidden" name="tps_nav[scroll_after_refresh]" value="false">
						<label>
							<input type="checkbox"
							       name="tps_nav[scroll_after_refresh]"
							       value="true"<?php echo $options['scroll_after_refresh'] ? ' checked' : '' ?>>
							Scroll to content after a refresh
						</label>

						<p class="description">
							If navigating to another slide or post causes a page refresh, then automatically scroll the window to the top of that new silde's content.
						</p>

					</td>

				</tr>

				<tr valign="top">
					<th scope="row">
						<label><?php _e( "Padding between the Content and the Top of the page:", 'theia-post-slider' ); ?></label>
					</th>
					<td>
						<input type="number" step="0.1"
							       name="tps_nav[scroll_top_offset]"
							       value="<?php echo htmlentities( $options['scroll_top_offset'] ); ?>">
						<p class="description">
							Set the number of pixels that you want to have between the top of your page and the content of your posts' pages.
						</p>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row">
						<label for="tps_prev_post_button_text"><?php _e( "Previous button text:", 'theia-post-slider' ); ?></label>
					</th>
					<td>
						<input type="text"
						       id="tps_prev_post_button_text"
						       name="tps_nav[prev_text_post]"
						       value="<?php echo htmlentities($options['prev_text_post']); ?>"
						       class="regular-text"
						       onchange="updateSlider()" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="tps_next_post_button_text"><?php _e( "Next button text:", 'theia-post-slider' ); ?></label>
					</th>
					<td>
						<input type="text"
						       id="tps_next_post_button_text"
						       name="tps_nav[next_text_post]"
						       value="<?php echo htmlentities($options['next_text_post']); ?>"
						       class="regular-text"
						       onchange="updateSlider()" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="tps_post_button_width"><?php _e( "Button width (px):", 'theia-post-slider' ); ?></label>
					</th>
					<td>
						<input type="number"
						       class="small-text"
						       id="tps_post_button_width"
						       name="tps_nav[button_width_post]"
						       value="<?php echo $options['button_width_post']; ?>"
						       onchange="updateSlider()" />

						<p class="description">
							Only works for classic themes. Use this if you want both buttons to have the same width.
							Insert "0" for no fixed width.
						</p>
					</td>
				</tr>
			</table>

			<p class="submit">
				<input type="submit"
				       class="button-primary"
				       value="<?php _e( 'Save All Changes', 'theia-post-slider' ) ?>" />
			</p>
		</form>
		<script type="text/javascript">
			function updateSlider() {
				var $ = jQuery;

				// Update navigation text
				slider.setNavText($('#tps_navigation_text').val());

				// Update title text
				slider.setTitleText($('#tps_helper_text').val());

				// Update button text
				slider.options.prevText = $('#tps_prev_button_text').val();
				slider.options.nextText = $('#tps_next_button_text').val();
				slider.updateNavigationBars();

				// Update button width
				var width = parseInt($('#tps_button_width').val());
				$('.theiaPostSlider_nav ._2').css('width', width > 0 ? width : '');

				// Update horizontal position
				$('#tps_nav_upper, #tps_nav_lower')
					.removeClass('_left _center _center_full _right')
					.addClass('_' + $('#tps_nav_horizontal_position').val());

				// Update vertical position
				$('#tps_nav_upper').toggle(['top_and_bottom', 'top'].indexOf($('#tps_nav_vertical_position').val()) != -1);
				$('#tps_nav_lower').toggle(['top_and_bottom', 'bottom'].indexOf($('#tps_nav_vertical_position').val()) != -1);
			}

			function updatePostNavigation() {
				var $ = jQuery,
					enabled = $('#tps_post_navigation').attr('checked') == 'checked';
				$('#tps_prev_post_button_text, #tps_next_post_button_text, #tps_post_button_width').attr('readonly', !enabled);
			}

			function updateButtonBehaviour() {
				var $ = jQuery,
					value = $('input[name="tps_nav[button_behaviour]"]:checked').val();
				$('#tps_post_navigation_inverse').attr('disabled', value != 'post');
				$('#tps_post_navigation_same_category').attr('disabled', value != 'post');
			}

			updatePostNavigation();
			updateButtonBehaviour();
		</script>
	<?php
	}
}
