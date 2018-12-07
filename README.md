# Section Menus Shortcode #

Provides a shortcode that automatically generates a menu for each section on your page with a particular selector.


## Description ##

Provides a shortcode that automatically generates a menu for each section on your page with a particular selector. This menu system is meant to be used with Bootstrap 4 or the [Athena Framework](https://ucf.github.io/Athena-Framework/).

Note: jQuery is *required* for the JavaScript included with this plugin to work.

## Installation ##

### Manual Installation ###
1. Upload the plugin files (unzipped) to the `/wp-content/plugins` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the "Plugins" screen in WordPress

### WP CLI Installation ###
1. `$ wp plugin install --activate https://github.com/UCF/Section-Menus-Shortcode/archive/master.zip`.  See [WP-CLI Docs](http://wp-cli.org/commands/plugin/install/) for more command options.


## Changelog ##

### 1.0.4 ###
Bug Fix:
* Updated mobile menu to close when menu item is clicked

### 1.0.3 ###
Enhancements:
* Added affix offset calculations that account for the UCF Header

Bug Fixes:
* Added fix for "jump" when the navbar affixes
* Fixed bug where two nav links could be highlighted at the same time

### 1.0.2 ###
* Added "Skip to Section" text to the mobile nav toggle button for clarity
* Fixed minor PHP notice

### 1.0.1 ###
* Updated mobile styles and bug fix

### 1.0.0 ###
* Initial release


## Upgrade Notice ##

n/a


## Installation Requirements ##

None


## Development & Contributing ##

NOTE: this plugin's readme.md file is automatically generated.  Please only make modifications to the readme.txt file, and make sure the `gulp readme` command has been run before committing readme changes.
