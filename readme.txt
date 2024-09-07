=== Image Converter for WebP ===
Contributors: badasswp
Tags: webp, image, convert, jpeg, png.
Requires at least: 4.0
Tested up to: 6.6.1
Stable tag: 1.1.2
Requires PHP: 7.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Convert your WordPress JPG/PNG images to WebP formats.

== Installation ==

1. Go to 'Plugins > Add New' on your WordPress admin dashboard.
2. Search for 'Image Converter for WebP' plugin from the official WordPress plugin repository.
3. Click 'Install Now' and then 'Activate'.

== Description ==

As an internet user, you already know images can be the difference between a great website experience and a terrible one! Think about how often you've landed on a website and hit the back button because the home page was too busy or the banner image was taking so much time to load due to its size.

You may not realize it, but imagery is a large part of it. This plugin helps take care of all those concerns, by converting your WordPress images to WebP format during page load so that your site loads extremely fast, without any disruptions or downtime.

== Screenshots ==

1. Generated WebP Image - Convert your images both on upload and page load easily.
2. Options Page - Configure your plugin options here.
3. Attachment Modal - See Converted WebP image path here.

== Changelog ==

= 1.1.2 =
* Refactor Admin page, make extensible with new classes.
* Add new custom filter `icfw_form_fields`.
* Add new Log error option in Admin page.
* Update translation files.
* Update Unit Tests.
* Update README notes.

= 1.1.1 =
* Ensure WP_Error is passed and returned to Hook.
* Rename hooks across codebase to use `icfw` prefix.
* Implement Kernel interface.
* Fix bugs & failing tests.
* Update README notes.

= 1.1.0 =
* Major code base refactor.
* Add more **Settings** options to **Settings** page.
* Update language translations.
* Fix bugs & linting issues.
* Update README notes.

= 1.0.5 =
* Add language translation.
* Add error logging capabilities to **Settings** page.
* Add more Unit tests, Code coverage.
* Fix bugs & linting issues.
* Update README notes.

= 1.0.4 =
* Add more Unit tests, Code coverage.
* Fix bugs & linting issues.
* Fix nonce related problems with settings page.
* Update plugin folder name, file & text domain.
* Update build, deploy-ignore listing.
* README and change logs.

= 1.0.3 =
* Update Plugin display name to Image Converter for WebP.
* Update README and change logs.
* Update version numbers.
* Add more Unit tests & Code coverage.

= 1.0.2 =
* Add `icfw_delete` and `icfw_metadata_delete` hooks.
* Add Settings page for plugin options.
* Add WebP field on WP attachment modal.
* Add new class methods.
* Fix Bugs and Linting issues within class methods.
* Add more Unit tests & Code coverage.
* Update README notes.

= 1.0.1 =
* Refactor hook icfw_convert to placement within convert public method.
* Add more Unit tests & Code coverage.
* Update README notes.

= 1.0.0 =
* Initial release
* WebP image conversion for any type of image.
* Custom Hooks - icfw_options, icfw_convert, icfw_attachment_html, icfw_thumbnail_html.
* Unit Tests coverage.
* Tested up to WP 6.5.3.

== Contribute ==

If you'd like to contribute to the development of this plugin, you can find it on [GitHub](https://github.com/badasswp/image-converter-webp).
