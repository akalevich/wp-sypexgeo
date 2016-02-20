<?php
	/*
	Plugin Name: WP SypexGeo
	Description: Sypex Geo plugin for Wordpress
	Version: 2.0
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
	
	define("GEOTARGETING_COUNTY", "GeoCountry");
	define("GEOTARGETING_REGION", "GeoRegion");
	define("GEOTARGETING_CITY", "GeoCity");

    if (!defined( 'GEOTARGETING_PLUGIN_URL' ) ) {
        define( 'GEOTARGETING_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
    }
	if (!defined( 'GEOTARGETING_PLUGIN_CSS_URL' )) {
	    define( 'GEOTARGETING_PLUGIN_CSS_URL', GEOTARGETING_PLUGIN_URL . 'css/' );
    }
    if (!defined( 'GEOTARGETING_PLUGIN_IMAGES_URL' )) {
        define( 'GEOTARGETING_PLUGIN_IMAGES_URL', GEOTARGETING_PLUGIN_URL . 'images/' );
    }
    if (!defined( 'GEOTARGETING_PLUGIN_JS_URL' )) {
        define( 'GEOTARGETING_PLUGIN_JS_URL', GEOTARGETING_PLUGIN_URL . 'js/' );
    }

    // Add Shortcode
    add_shortcode('GeoCity', 'shortcode_geo');
    add_shortcode('GeoRegion', 'shortcode_geo');
    add_shortcode('GeoCountry', 'shortcode_geo');
    add_shortcode('geotext', 'shortcode_geoText');

    // кнопка в редакторе
    require_once(dirname(__FILE__) . "/wp-sypexgeo-button.php");

	$language = get_option('sgeo_language');
	if ($language == 'en') {
		define("NAME", "name_en");
	} elseif ($language == 'ru') {
		define("NAME", "name_ru");
	}

	function getIpData() {
	    // Достаем текущий город/регион
        $base_type = get_option('sgeo_dbase');
        if ($base_type == 'loc') {
            $ipdata = getLocInfo();
        } elseif ($base_type == 'rm') {
            $ipdata = getRemInfo();
        } elseif ($base_type == 'query') {
            $ipdata = getQueryInfo();
        }

        return $ipdata;
	}

	/**
	 * Add Shortcode for WP
	 */
    function shortcode_geo($atts, $content = null, $tag) {
        $ipdata = getIpData();

        if ($tag === 'GeoCity') {
            $type = 'city';
        } elseif ($tag === 'GeoRegion') {
            $type = 'region';
        } else {
            $type = 'country';
        }

        if (isset($atts['in'])) {
            $isCurrent = strpos(strtolower($atts['in']), $ipdata[$type]);
        } else {
            $isCurrent = strpos(strtolower($atts['out']), $ipdata[$type]) === false;
        }

        if (isset($content) && $isCurrent !== false) {
            if (has_shortcode($content, 'GeoCity') || has_shortcode($content, 'GeoRegion') || has_shortcode($content, 'GeoCountry') || has_shortcode($content, 'geotext')) {
                return do_shortcode($content);
            }

            return trim($content);
        }

        return null;
    }

    function shortcode_geoText($atts, $content = null) {
        $ipdata = getIpData();

        if (isset($atts['in'])) {
            $isCurrentCity = strpos($atts['in'], $ipdata['city']);
        } else {
            $isCurrentCity = strpos($atts['out'], $ipdata['city']) === false;
        }

        if (isset($atts['text']) && $isCurrentCity !== false) {
            return $atts['text'];
        }

        return null;
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

		return $data;
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

	function getQueryInfo() {
        $data = array();

        if (isset($_GET['city'])) {
            $city = $_GET['city'];
        } elseif (isset($_COOKIE['city'])) {
            $city = $_COOKIE['city'];
        }

        if (isset($_GET['region'])) {
            $region = $_GET['region'];
        } elseif (isset($_COOKIE['region'])) {
            $region = $_COOKIE['region'];
        }

        if (isset($_GET['country'])) {
            $country = $_GET['country'];
        } elseif (isset($_COOKIE['country'])) {
            $country = $_COOKIE['country'];
        }

        $data['city'] = strtolower($city);
        $data['region'] = strtolower($region);
        $data['country'] = strtolower($country);

        return $data;
    }
	
	register_activation_hook(__FILE__, 'wp_sypexgeo_activation');
	register_deactivation_hook(__FILE__, 'wp_sypexgeo_deactivation');
	 
	function wp_sypexgeo_activation() {
		 update_option('sgeo_language', 'en');
		 update_option('sgeo_dbase', 'loc');
	}
	 
	function wp_sypexgeo_deactivation() {
	    
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
