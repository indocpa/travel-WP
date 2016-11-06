<?php

/*
 * Copyright 2012-2015, Theia Post Slider, WeCodePixels, http://wecodepixels.com
 */

class TpsAdmin_About {
	public $showPreview = false;

	public function echoPage() {
		?>

		<h3>About</h3>
		<table class="theiaPostSlider_adminAboutTable">
			<tr>
				<td>
					<p>
						<a href="http://wecodepixels.com/theia-post-slider-for-wordpress/?utm_source=theia-post-slider-for-wordpress"
						   target="_blank"><b>Theia Post Slider</b></a> version <b><?php echo TPS_VERSION; ?></b>
					</p>

					<p>
						Developed by <a href="http://wecodepixels.com/?utm_source=theia-post-slider-for-wordpress" target="_blank">WeCodePixels</a>
					</p>

					<p>
						<a href="http://wecodepixels.com/products/?utm_source=theia-post-slider-for-wordpress"
						   class="button"
						   target="_blank">
							See our other plugins
						</a>
					</p>
				</td>
				<td>
					<a class="theiaPostSlider_adminLogo"
					   href="http://wecodepixels.com/?utm_source=theia-post-slider-for-wordpress"
					   target="_blank"><img src="<?php echo plugins_url( '/images/wecodepixels-logo.png', __FILE__ ); ?>"></a>
				</td>
			</tr>
		</table>
		<br>

		<h3>Credits</h3>
		Many thanks go out to the following:
		<ul>
			<li>
				<a href="http://www.doublejdesign.co.uk/products-page/icons/super-mono-icons/">Super Mono Icons</a>
				by
				<a href="http://www.doublejdesign.co.uk/">Double-J Design</a>
			</li>
			<li>
				<a href="http://p.yusukekamiyamane.com/">Fugue Icons</a>
				by
				<a href="http://yusukekamiyamane.com/">Yusuke Kamiyamane</a>
			</li>
			<li>
				<a href="http://www.brightmix.com/blog/brightmix-icon-set-free-for-all/">Brightmix icon set</a>
				by
				<a href="http://www.brightmix.com">Brightmix</a>
			</li>
			<li>
				<a href="http://freebiesbooth.com/hand-drawn-web-icons">Hand Drawn Web icons</a>
				by
				<a href="http://highonpixels.com/">Pawel Kadysz</a>
			</li>
			<li>
				<a href="http://icondock.com/free/20-free-marker-style-icons">20 Free Marker-Style Icons</a>
				by
				<a href="http://icondock.com">IconDock</a>
			</li>
			<li>
				<a href="http://taytel.deviantart.com/art/ORB-Icons-87934875">ORB Icons</a>
				by
				<a href="http://taytel.deviantart.com">~taytel</a>
			</li>
			<li>
				<a href="http://www.visualpharm.com/must_have_icon_set/">Must Have Icon Set</a>
				by
				<a href="http://www.visualpharm.com">VisualPharm</a>
			</li>
			<li>
				<a href="http://github.com/balupton/History.js/">The History.js project</a>
			</li>
			<li>
				<a href="http://jquery.com/">The jQuery.js project</a>
			</li>
			<li>
				Arrow designed by <a href="http://www.thenounproject.com/sapi">Stefan Parnarov</a>
				from the <a href="http://www.thenounproject.com">Noun Project</a>
			</li>
			<li>
				Arrow designed by <a href="http://www.thenounproject.com/shailendra007">Shailendra Chouhan</a>
				from the <a href="http://www.thenounproject.com">Noun Project</a>
			</li>
			<li>
				Arrow designed by <a href="http://www.thenounproject.com/rajputrajesh448">Rajesh Rajput</a>
				from the <a href="http://www.thenounproject.com">Noun Project</a>
			</li>
			<li>
				Arrow designed by <a href="http://www.thenounproject.com/chrisburton">Chris Burton</a>
				from the <a href="http://www.thenounproject.com">Noun Project</a>
			</li>
			<li>
				Arrow designed by <a href="http://www.thenounproject.com/MisterPixel">Mister Pixel</a>
				from the <a href="http://www.thenounproject.com">Noun Project</a>
			</li>
			<li>
				Arrow designed by <a href="http://www.thenounproject.com/winthropite">Mike Jewett</a>
				from the <a href="http://www.thenounproject.com">Noun Project</a>
			</li>
			<li>
				Left and Right designed by <a href="http://www.thenounproject.com/cengizsari">Cengiz SARI</a>
				from the <a href="http://www.thenounproject.com">Noun Project</a>
			</li>
			<li>
				Left and Right designed by <a href="http://www.thenounproject.com/desbenoit">Desbenoit</a>
				from the <a href="http://www.thenounproject.com">Noun Project</a>
			</li>
		</ul>
	<?php
	}
}
