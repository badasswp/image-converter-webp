# Changelog

## 1.2.0
* Resolve issue with undefined array keys in Main service.
* Serve WebP images in WP media library.
* Refactor Form & Option classes.
* Make strings translatable across plugin.
* Fix failing tests, add new tests.
* Update README notes.
* Tested up to WP 6.7.1.

## 1.1.2
* Refactor Admin page, make extensible with new classes.
* Add new custom filter `icfw_form_fields`.
* Add new Log error option in Admin page.
* Update translation files.
* Update Unit Tests.
* Update README notes.

## 1.1.1
* Ensure WP_Error is passed and returned to Hook.
* Rename hooks across codebase to use `icfw` prefix.
* Implement Kernel interface.
* Fix bugs & failing tests.
* Update README notes.

## 1.1.0
* Major code base refactor.
* Add more **Settings** options to **Settings** page.
* Update language translations.
* Fix bugs & linting issues.
* Update README notes.

## 1.0.5
* Add language translation.
* Add error logging capabilities to **Settings** page.
* Add more Unit tests, Code coverage.
* Fix bugs & linting issues.
* Update README notes.

## 1.0.4
* Add more Unit tests, Code coverage.
* Fix bugs & linting issues.
* Fix nonce related problems with settings page.
* Update plugin folder name, file & text domain.
* Update build, deploy-ignore listing.
* README and change logs.

## 1.0.3
* Update Plugin display name to __Image Converter for WebP__.
* Update README notes and change logs.
* Update version numbers.
* Add more Unit tests & Code coverage.

## 1.0.2
* Add `icfw_delete` and `icfw_metadata_delete` hooks.
* Add Settings page for plugin options.
* Add WebP field on WP attachment modal.
* Add new class methods.
* Fix Bugs and Linting issues within class methods.
* Add more Unit tests & Code coverage.
* Update README notes.

## 1.0.1
* Refactor hook `icfw_convert` to placement within convert public method.
* Fix bugs within `Plugin` class methods.
* Add more Unit tests & Code coverage.
* Update README notes.

## 1.0.0 (Initial Release)
* WebP image conversion for any type of image.
* Custom Hooks - `icfw_options`, `icfw_convert`, `icfw_attachment_html`, `icfw_thumbnail_html`.
* Unit Tests coverage.
* Tested up to WP 6.5.3.
