<?php
	/*
	Plugin Name: WP SypexGeo
	Description: Sypex Geo plugin for Wordpress
	Version: 1.0
	Author: Alex Kalevich
	Plugin URI: https://github.com/akalevich/wp-sypexgeo
	*/

	// Сurrent directory
	define('WO-SYPEXGEO_DIR', dirname(__FILE__));

	// Path to Geo Class
	define("GEO_CLASS", dirname(__FILE__) . "/include/SxGeo.php");
	// Path to Data File
	define("GEO_DATA", dirname(__FILE__) . "/include/SxGeoCity.dat");
	define("GEO_ADMIN", dirname(__FILE__) . "/wp-sypexgeo-admin.php");

	add_filter('the_content', 'geotargeting_filter');
	add_filter('the_content_rss', 'geotargeting_filter');
	add_filter('the_excerpt', 'geotargeting_filter');
	add_filter('the_excerpt_rss', 'geotargeting_filter');
	define("GEOTARGETING_COUNTY", "GeoCountry");
	define("GEOTARGETING_REGION", "GeoRegion");
	define("GEOTARGETING_CITY", "GeoCity");

	$language = get_option('sgeo_language');
	if ($language == 'en') {
		define("NAME", "name_en");
	} elseif ($language == 'ru') {
		define("NAME", "name_ru");
	}

	function geotargeting_filter($s) {

		//parse Country
		preg_match_all("#\[" . GEOTARGETING_COUNTY . "\s*(in|out)=([^\]]+)\](.*?)\[/" . GEOTARGETING_COUNTY . "\]#isu", $s, $country);

		//parse Country
		preg_match_all("#\[" . GEOTARGETING_REGION . "\s*(in|out)=([^\]]+)\](.*?)\[/" . GEOTARGETING_REGION . "\]#isu", $s, $region);

		//parse Country
		preg_match_all("#\[" . GEOTARGETING_CITY . "\s*(in|out)=([^\]]+)\](.*?)\[/" . GEOTARGETING_CITY . "\]#isu", $s, $city);

		if (empty($country) && empty($region) && empty($city)) {
			return $s;
		}

		$base_type = get_option('sgeo_dbase');
		if ($base_type == 'loc') {
			$ipdata = getLocInfo();
		} elseif ($base_type == 'rm') {
			$ipdata = getRemInfo();
		}

		if (!empty($country)) {
			foreach ($country[0] as $i => $raw) {
				$type = strtolower($country[1][$i]);
				$countries = strtolower(trim(str_replace(array("\"", "'", "\n", "\r", "\t", " "), "", $country[2][$i])));
				$content = $country[3][$i];
				$countries = explode(",", $countries);
				$replacement = "";
				if ((($type == "in") && in_array($ipdata['country'], $countries)) || (($type == "out") && !in_array($ipdata['country'], $countries))) {
					$replacement = $content;
				}
				$s = str_replace($raw, $replacement, $s);
			}
		}

		if (!empty($region)) {
			foreach ($region[0] as $i => $raw) {
				$type = strtolower($region[1][$i]);
				$regions = strtolower(trim(str_replace(array("\"", "'", "\n", "\r", "\t"), "", $region[2][$i])));
				$content = $region[3][$i];
				$regions = explode(",", $regions);
				$replacement = "";
				if ((($type == "in") && in_array($ipdata['region'], $regions)) || (($type == "out") && !in_array($ipdata['region'], $regions))) {
					$replacement = $content;
				}
				$s = str_replace($raw, $replacement, $s);
			}
		}

		if (!empty($city)) {
			foreach ($city[0] as $i => $raw) {
				$type = strtolower($city[1][$i]);
				$cities = strtolower(trim(str_replace(array("\"", "'", "\n", "\r", "\t", " "), "", $city[2][$i])));
				$content = $city[3][$i];
				$cities = explode(",", $cities);
				$replacement = "";
				if ((($type == "in") && in_array($ipdata['city'], $cities)) || (($type == "out") && !in_array($ipdata['city'], $cities))) {
					$replacement = $content;
				}
				$s = str_replace($raw, $replacement, $s);
			}
		}

		return $s;
	}

	function sgeo_options_page() {
		include(GEO_ADMIN);
	}

	function sgeo_add_menu() {
		add_options_page('WP-Sypexgeo', 'WP-Sypexgeo', 8, __FILE__, 'sgeo_options_page');
	}

	add_action('admin_menu', 'sgeo_add_menu');

	function getLocInfo() {
		require_once(GEO_CLASS);

		$SxGeo = new SxGeo(GEO_DATA);
		$ip = $_SERVER['REMOTE_ADDR'];
		$ipinfo = $SxGeo->getCityFull($ip);

		$data = array();
		$data['country'] = strtolower($ipinfo['country'][NAME]);
		$data['region'] = strtolower($ipinfo['region'][NAME]);
		$data['city'] = strtolower($ipinfo['city'][NAME]);

		return $res;
	}

	function getRemInfo() {
		$ip = $_SERVER['REMOTE_ADDR'];
		$jres = file_get_contents('http://api.sypexgeo.net/json/' . $ip);
		$res = json_decode($jres);
		$data = array();

		if (isset($res->city)) {
			$data['city'] = strtolower($res->city->{NAME});
		}
		if (isset($res->region)) {
			$data['region'] = strtolower($res->region->{NAME});
		}
		if (isset($res->country)) {
			$data['country'] = strtolower($res->country->{NAME});
		}

		return $data;
	}

	/*  Copyright 2015  Alex Kalevich  (email: r_alex_b@tut.by)

		This program is free software; you can redistribute it and/or modify
		it under the terms of the GNU General Public License as published by
		the Free Software Foundation; either version 2 of the License, or
		(at your option) any later version.

		This program is distributed in the hope that it will be useful,
		but WITHOUT ANY WARRANTY; without even the implied warranty of
		MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
		GNU General Public License for more details.

		You should have received a copy of the GNU General Public License
		along with this program; if not, write to the Free Software
		Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
	*/
?>
