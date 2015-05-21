=== WP-Sypexgeo ===
Contributors: Alex Kalevich
Tags: geotargeting
Requires at least: 1.5
Tested up to: 4.2.2
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==

Sypex Geo plugin for Wordpress

Sypex Geo - product for location by IP address. Received IP-address, Sypex Geo provides information about the location of the visitor - a country, region, city, geographic coordinates.
This plugin use Sypex Geo API in local database version.
The plugin allows you to select a local database or use HTTP-request to sypexgeo.net database, select language of country names (EN/RU)

== Installation ==

1. Upload `wp-sypexgeo` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.

== Usage ==

To specify a list of countries: [GeoCountry in=Belarus,Russia]Hello Belarus,Russia![/GeoCountry] 
To specify a list of regions: [GeoRegion in=Moscow]Hello Moscow Region![/GeoRegion]
To specify a list of cities: [GeoCity in=Minsk,Brest]Hello Minsk,Brest![/GeoCity]
If you want exclude some countries (regions, cities) use "out": [GeoRegion out=Minsk,Brest]Hello all, except Minsk,Brest![/GeoRegion]

== Changelog ==

= 0.4 =
* Added geotargeting using remote database

= 0.3 =
* Added language of country names
* Added options page

= 0.2 =
* Added geotargeting using local database

= 0.1 =
* Initial release
