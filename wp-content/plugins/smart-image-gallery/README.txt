=== Smart Image Gallery ===
Contributors: codepeople
Donate link: http://wordpress.dwbooster.com/galleries/photoshow-advanced
Tags: gallery, image, image gallery, album, photo, photoalbum, photogallery, pictures, Flickr, Picasa, Instagram, Facebook, Google Images, cloud, cloud servers,Google,Post,posts,page,images,admin,shortcode,plugin,sidebar,widget
Requires at least: 3.0.5
Tested up to: 4.5
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Smart Image Gallery allows to insert images, and pictures, in your blog, directly from the WordPress media library, or eternal images repositories...

== Description ==

Smart Image Gallery allows to insert images, and pictures, in your blog, directly from the WordPress media library, or eternal images repositories (like: Flickr, Picasa, Instagram, Facebook or Google Images ). The images are searched, and inserted, from the article edition, without importing them to the WordPress media library. All you need to do is pick the search criteria, and the plugin will search in all available sources, allowing select the images that most closely match the blog's subject.

Smart Image Gallery allows insert the selected images by separated, or as part of a gallery. It is possible select between different galleries designs, included with the plugin.

**Features**

*  Insert images, or image galleries, in the blog's articles
*  The easiest way to find images related with the articles
*  Allows search for images in the WordPress media library, or external images repositories
*  Allows to display the images separately or as part of a gallery
*  Reduces the bandwidth consumption of your server using images hosted in external servers
*  Allows insert the gallery in the website's sidebar.

The base plugin, available for free from the WordPress Plugin Directory, has all the features needed for search, and insert, images in the website's articles.

**Premium Features**

*  Includes a wide number of images repositories (like:WordPress media library, Flickr, Picasa, Instagram, Facebook and Googel Images)
*  Includes additional galleries designs (like: carousel and Classic Gallery )

**Demo of Premium Version of Plugin**

[http://demos.net-factor.com/smart-image-gallery/wp-login.php](http://demos.net-factor.com/smart-image-gallery/wp-login.php "Click to access the Administration Area demo")

[http://demos.net-factor.com/smart-image-gallery/](http://demos.net-factor.com/smart-image-gallery/ "Click to access the Public Page")


If you want more information about this plugin, or another one, visits my website:

[http://wordpress.dwbooster.com](http://wordpress.dwbooster.com "CodePeople WordPress Repository")

**Requirements:**

The requirements depend of the images repository, some repositories require an API Key, or Client ID

== Installation ==

**To install Smart Image Gallery, follow these steps:**

1.	Download and unzip the plugin
2.	Upload the smart-image-gallery/ directory to the /wp-content/plugins/ directory
3.	Activates the plugin through the Plugins menu in WordPress
4.	Go to the setting section of plugin, and activates the images repositories

Another method to install the plugin:

1. Go to the plugins section in WordPress
2. Press the  "Add New" button, at top of page
3. Press the "upload" link, and selects the zipped file with the plugin's code
4. Finally, install and activate the plugin

== Using Smart Image Gallery ==

Smart Image Gallery is an easy and intuitive plugin. Before search, and insertion of the images in the articles, will be necessary activate the images repositories. Go to the settings page of plugin ( from the menu option "Settings > Smart Image Gallery" ), and activate the available images repositories (the number of images repositories depends of plugin's version installed on your WordPress ). Each image sources has its own settings options ( for example: Flickr requires an API Key, and Instagram a Client ID)

After activate the images repositories, go to the article, and press the Smart Image Gallery icon over the content's editor. The action opens an popup window with a search box. Type the search criteria and press the "search" button, or simply press the carriage return key. The plugin displays the images available for each images repository. If the resulting images are not appropriated for the article, the plugin displays an "+" button at right of each repository, to get additional images with the same search criteria. Each image has associated a checkbox to be selected and inserted in the article. One time have decided the images to insert, go to the galleries design tab, and select the gallery to display (the available galleries, and its options, depend of plugin's version installed in your WordPress )

The plugin inserts a shortcode in the article, doing easy remove an image, or modify the gallery's settings.

== Frequently Asked Questions ==

= Q: Why to use external repositories? =

A: The external repositories like a Flickr, Picasa, Instagram, etc. include a big number of photos and images available for non-commercial purposes, furthermore, the external repositories reduce the bandwidth consumption from your web server.

= Q: How modify the gallery design? =

A: Each gallery has its own resources: javascript, css files and images, in its own directory. So, will be required edit only the corresponding gallery's styles.

= Q: Is possible remove a image from the gallery? =

A: The plugin inserts a shortcode in the article, so will be required remove the image's data from the shortcode, and save the article changes.

= Q: Are free the images selected with Smart Image Gallery? =

A: Smart Image Gallery search images in general. You should check copyright of images. If you click on the image, will be loaded the original page where the image is published.

= Q: Could be restricted the resulting images to an user? =

A: This feature is only available for Flickr. Go to the settings page of plugin, and set the username for Flickr library.

If you require more information, please visit our FAQ page in:

http://wordpress.dwbooster.com/faq/photoshow-advanced

= Q: How to include all images in a Flickr Album? = 

A: Tick the checkbox: "Search by album Id" in the Flickr settings section (Accessible through the menu option: Settings/Smart Image Gallery), and then in the insertion process of the images in the pages or posts, enter the album's id as the search criteria. The action returns only the images belonging to the specified album.

== Screenshots ==

1. Images inserted by separated 
2. Swapping images gallery
3. Carousel gallery
4. Classic gallery
5. Plugin's settings
6. Smart Image Gallery button from the blogs edition section
7. Interface for images selection
8. Interface for gallery settings
9. Smart Image Gallery shortcode

== Changelog ==

= 1.0 =

* First version released.

= 1.0.1 =

* Improves the plugin documentation.
* Fixes an issue with the selection of Flickr images.
* Corrects some issues with the jQuery framework included with the latest versions of WordPress.
* Re-implements multiple features of the plugin. 

= 1.0.2 =

* Uses the classes constructor of PHP5 in the Widgets creation

= 1.0.3 =

* Reimplements the Google Module, because the ajax.googleapis.com has been closed.

= 1.0.4 =

* Modifies some deprecated jQuery functions.

= 1.0.5 =

* Modifies the images repositories.

== Upgrade Notice ==

= 1.0.5 =

Important note: If you are using the Professional version don't update via the WP dashboard but using your personal update link. Contact us if you need further information: http://wordpress.dwbooster.com/support