<?php
/*
Plugin Name: Anti Hatena Bookmark
Plugin URI: http://akimoto.jp/blog/
Description: This plugin makes URLs non-identical from Hatena Bookmark. By using this, your WordPress permalinks will be randomly mixed with capital and small letters. So case-sensitive web service like Hatena Bookmark will not be able to aggregate references from different users. Currently only supports a WordPress blog set under the root path (e.g. http://example.com/ ).
Author: Akky AKIMOTO
Version: 0.92
Requirements: PHP5
Tested With: WordPress 3.1.3
Should be compatible with: WordPress 3.x and above
Author URI: http://akimoto.jp/
*/

/*  Copyright 2011 Akky AKIMOTO (Email: $mySirname at gmail dot com)

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

require_once 'http_build_url.php';

class Anti_Hatena_Bookmark {

	public function __construct() {
		// priority 10(default), hands over 3 parameters to the filter
		add_filter('post_link', array(&$this, 'random_capitalize_permalink'), 10, 3);
	}

	protected static function replace_small_and_capital_randomly(
		$text, $ratio = 0.5) {
		$replaced = '';
		foreach(str_split($text) as $c) {
			if (ctype_alpha($c)) {
				if (rand(0, 100) > (100 * $ratio)) {
					if (ctype_lower($c)) {
						$c = strtoupper($c);
					} else {
						$c = strtolower($c);
					}
				}
			}
			$replaced .= $c;
		}
		return $replaced;
	}
	/**
	 *
	 * @param permalink original URL
	 * @param post current post object
	 * @param leavename currently do not support if it is true
	 */
	public function random_capitalize_permalink(
		$permalink, $post = null, $leavename = false) {
		$parsedItems = parse_url($permalink);
		$parsedItems['path'] = 
			self::replace_small_and_capital_randomly($parsedItems['path']);
		$filtered_permalink = http_build_url($parsedItems);
		return $filtered_permalink;
	}
}

$anti_hatena_bookmark = new Anti_Hatena_Bookmark();
