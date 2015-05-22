# Sypex Geo plugin for Wordpress

This plug-in to determine the location by ip using Sypex Geo. 
This plugin use Sypex Geo API in local database version.
The plugin allows you to select a local database or use HTTP-request to sypexgeo.net database, select language of country names (EN/RU)

Usage
-----
* *To specify a list of countries:* `[GeoCountry in=Belarus,Russia]Hello Belarus,Russia![/GeoCountry]`
* *To specify a list of regions:* `[GeoRegion in=Moscow]Hello Moscow Region![/GeoRegion]`
* *To specify a list of cities:*  `[GeoCity in=Minsk,Brest,Kiev]Hello Minsk,Brest,Kiev![/GeoCity]`
* *If you want exclude some countries (regions, cities) use "out":* `[GeoRegion out=Minsk,Brest]Hello all, except Minsk,Brest![/GeoRegion]`
 
  *Example:* `Добро пожаловать в WordPress. Это ваша первая запись. Отредактируйте или удалите её, затем пишите! Наши контакты: 
[GeoCountry in=Belarus]+375295552255[/GeoCountry][GeoCountry out=Belarus]+388475552255[/GeoCountry]`

Installation 
-----
1. Upload `wp-sypexgeo` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
