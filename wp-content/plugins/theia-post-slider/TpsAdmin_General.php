<?php

/*
 * Copyright 2012-2015, Theia Post Slider, WeCodePixels, http://wecodepixels.com
 */

class TpsAdmin_General {
	public $showPreview = true;

	public function echoPage() {
		?>
		<form method="post" action="options.php">
			<?php settings_fields( 'tps_options_general' ); ?>
			<?php $options = get_option( 'tps_general' ); ?>

			<h3><?php _e( "General Settings", 'theia-post-slider' ); ?></h3>
			<table class="form-table">
				<tr valign="top">
					<th scope="row">
						<label><?php _e( "Theme:", 'theia-post-slider' ); ?></label>
					</th>
					<td>
						<label>
							<input type="radio"
							       id="tps_theme_type_font"
							       name="tps_general[theme_type]"
							       value="font" <?php echo $options['theme_type'] == 'font' ? 'checked' : ''; ?>
							       onchange="updateSlider()">
							Vector icons
							<p class="description">
								Awesome font vector icons. Retina-ready. Can be highly customized.
							</p>
						</label>
						<br>
						<table class="form-table">
							<tr valign="top">
								<th scope="row">
									Icons:
								</th>
								<td>
									<select id="tps_theme_font_name"
									        name="tps_general[theme_font_name]"
									        onchange="updateSlider()">
										<?php
										$icons = TpsOptions::get_font_icons();
										foreach ( $icons as $name => $icon ) {
											$displayName = ucwords( str_replace( '-', ' ', $name ) );
											echo '<option data-leftClass="' . $icon['leftClass'] . '"  data-rightClass="' . $icon['rightClass'] . '" value="' . $name . '"' . ( $name == $options['theme_font_name'] ? ' selected' : '' ) . '>&#x' . $icon['leftCode'] . '; &#x' . $icon['rightCode'] . '; ' . $displayName . '</option>' . "\n";
										}
										?>
									</select>
									<input type="hidden"
									       id="tps_theme_font_leftClass"
									       name="tps_general[theme_font_leftClass]"
									       value="<?php echo $options['theme_font_leftClass']; ?>">
									<input type="hidden"
									       id="tps_theme_font_rightClass"
									       name="tps_general[theme_font_rightClass]"
									       value="<?php echo $options['theme_font_rightClass']; ?>">
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									Color:
								</th>
								<td>
									<input type="text"
									       id="tps_theme_font_color"
									       name="tps_general[theme_font_color]"
									       value="<?php echo $options['theme_font_color']; ?>">
									<script>
										jQuery(document).ready(function ($) {
											$('#tps_theme_font_color').wpColorPicker({
												change: function () {
													updateSlider();
												}
											});
										});
									</script>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									Size (px):
								</th>
								<td>
									<input type="number"
									       class="small-text"
									       id="tps_theme_font_size"
									       name="tps_general[theme_font_size]"
									       value="<?php echo htmlentities( $options['theme_font_size'] ); ?>"
									       onchange="updateSlider()">
								</td>
							</tr>
						</table>
						<br>

						<p></p>
						<br>

						<label>
							<input type="radio"
							       id="tps_theme_type_classic"
							       name="tps_general[theme_type]"
							       value="classic" <?php echo $options['theme_type'] == 'classic' ? 'checked' : ''; ?>
							       onchange="updateSlider()">
							Classic theme
							<p class="description">
								Lots of themes to choose from. Plenty of variations.
							</p>
						</label>
						<br>
						<select id="tps_theme_classic_name" name="tps_general[theme]" onchange="updateSlider()">
							<?php
							foreach ( TpsOptions::get_themes() as $key => $value ) {
								$output = '<option value="' . $key . '"' . ( $key == $options['theme'] ? ' selected' : '' ) . '>' . $value . '</option>' . "\n";
								echo $output;
							}
							?>
						</select>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="tps_transition_effect"><?php _e( "Transition effect:", 'theia-post-slider' ); ?></label>
					</th>
					<td>
						<select id="tps_transition_effect"
						        name="tps_general[transition_effect]"
						        onchange="updateSlider()">
							<?php
							foreach ( TpsOptions::get_transition_effects() as $key => $value ) {
								$output = '<option value="' . $key . '"' . ( $key == $options['transition_effect'] ? ' selected' : '' ) . '>' . $value . '</option>' . "\n";
								echo $output;
							}
							?>
						</select>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="tps_transition_speed"><?php _e( "Transition duration (ms):", 'theia-post-slider' ); ?></label>
					</th>
					<td>
						<input type="number"
						       class="small-text"
						       id="tps_transition_speed"
						       name="tps_general[transition_speed]"
						       value="<?php echo htmlentities( $options['transition_speed'] ); ?>"
						       onchange="updateSlider()" />
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

				// Update theme type options.
				var themeType = $('#tps_theme_type_font').is(':checked') ? 'font' : 'classic';
				$('#tps_theme_font_name').attr('disabled', themeType != 'font');
				$('#tps_theme_font_size').attr('disabled', themeType != 'font');
				$('#tps_theme_classic_name').attr('disabled', themeType != 'classic');

				// Update font codes.
				var selectedFont = $('#tps_theme_font_name option:selected');
				$('#tps_theme_font_leftClass').attr('value', selectedFont.attr('data-leftClass'));
				$('#tps_theme_font_rightClass').attr('value', selectedFont.attr('data-rightClass'));

				// Update CSS file.
				var css = $('#theiaPostSlider-css');
				var val = themeType == 'font' ? 'base.css' : $('#tps_theme_classic_name').val();
				var href = '<?php echo TPS_PLUGINS_URL . 'css/' ?>' + val + '?ver=<?php echo TPS_VERSION ?>';
				if (css.attr('href') != href) {
					css.attr('href', href);
				}

				if (typeof(slider) != 'undefined') {
					// Update transition.
					slider.setTransition({
						'effect': $('#tps_transition_effect').val(),
						'speed': parseInt($('#tps_transition_speed').val())
					});

					// Update buttons.
					slider.options.themeType = themeType;
					slider.options.prevFontIcon = '<span aria-hidden="true" class="' + selectedFont.attr('data-leftClass') + '"></span>';
					slider.options.nextFontIcon = '<span aria-hidden="true" class="' + selectedFont.attr('data-rightClass') + '"></span>';
					slider.updateNavigationBars();

					if (themeType == 'font') {
						$('.theiaPostSlider_nav').addClass('fontTheme');
						$('.theiaPostSlider_nav ._prev ._2 span, .theiaPostSlider_nav ._next ._2 span').css({
							'font-size': $('#tps_theme_font_size').val() + 'px',
							'line-height': $('#tps_theme_font_size').val() + 'px'
						});
						$('.theiaPostSlider_nav ._prev, .theiaPostSlider_nav ._next').css({
							'color': $('#tps_theme_font_color').val()
						});
					} else {
						$('.theiaPostSlider_nav').removeClass('fontTheme');
						$('.theiaPostSlider_nav ._prev ._2 span, .theiaPostSlider_nav ._next ._2 span').css({
							'font-size': 'inherit',
							'line-height': 'inherit'
						});
						$('.theiaPostSlider_nav ._prev, .theiaPostSlider_nav ._next').css({
							'color': $('#tps_theme_font_color').val()
						});
					}
				}
			}

			jQuery(document).bind('theiaPostSlider.changeSlide', function (event, slideIndex) {
				updateSlider();
			});

			updateSlider();
		</script>
	<?php
	}
}
