<?php

/*
 * Copyright 2012-2015, Theia Post Slider, WeCodePixels, http://wecodepixels.com
 */

class TpsAdmin_Dashboard {
	public $showPreview = true;

	public function echoPage() {
		?>
		<form method="post" action="options.php">
			<h3><?php _e( "Version", 'theia-post-slider' ); ?></h3>

			<p>
				You are using
				<a href="http://wecodepixels.com/theia-post-slider-for-wordpress/?utm_source=theia-post-slider-for-wordpress"
				   target="_blank"><b>Theia Post Slider</b></a>
				version <b class="theiaPostSlider_adminVersion"><?php echo TPS_VERSION; ?></b>, developed by
				<a href="http://wecodepixels.com/?utm_source=theia-post-slider-for-wordpress"
				   target="_blank"><b>WeCodePixels</b></a>.
			</p>
			<br>

			<h3><?php _e( "Support", 'theia-post-slider' ); ?></h3>

			<p>
				1. If you have any problems or questions, you should first check
				<a href="http://wecodepixels.com/theia-post-slider-for-wordpress/docs/?utm_source=theia-post-slider-for-wordpress"
				   class="button"
				   target="_blank">The
					Documentation</a>
			</p>

			<form method="post" action="options.php">
				<?php settings_fields( 'tps_options_about' ); ?>

				<p>
					2. If the plugin is misbehaving, try to <input name="tps_about[reset_global_settings_to_default]"
					                                               type="submit"
					                                               class="button"
					                                               value="Reset Global Settings to Default"
					                                               onclick="if(!confirm('Are you sure you want to reset The Global Settings to their default values?')) return false;">
					and/or <input name="tps_about[reset_all_post_settings_to_default]"
					              type="submit"
					              class="button"
					              value="Reset All Post Settings to Default"
					              onclick="if(!confirm('Are you sure you want to reset All Post Settings to their default values?')) return false;">
				</p>
			</form>
			<p>
				3. If your issue persists, please proceed to
				<a href="http://wecodepixels.com/theia-post-slider-for-wordpress/support/?utm_source=theia-post-slider-for-wordpress"
				   class="button"
				   target="_blank">Submit a Ticket</a>
			</p>
			<br>

			<h3><?php _e( "Updates and Announcements", 'theia-post-slider' ); ?></h3>
			<iframe class="theiaPostSlider_news" src="//wecodepixels.com/theia-post-slider-for-wordpress/news"></iframe>
		</form>

	<?php
	}
}
