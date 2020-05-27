<?php
/**
 * Created by PhpStorm.
 * User: weskempferjr
 * Date: 2020-05-27
 * Time: 12:48
 */

class Usc_Utilities_Shortcodes {


	private $settings;

	private static $shortcodes = array(
		'shortcode_demo'
	);


	/*
	 * Constructor
	 */

	public function __construct() {
		// $this->settings = new Usc_Utilities_Settings();
	}

	public static function get_shortcodes() {
		return self::$shortcodes;
	}

	public function register_shortcodes() {

		foreach ( self::$shortcodes as $shortcode ) {
			add_shortcode( $shortcode, array( $this, $shortcode ) );
		}

	}

	public function shortcode_demo( $atts ) {

		/** @var $string_1 string */
		/** @var $string_2  string */

		$atts_actual = shortcode_atts(
			array(
				'string_1'  => 'Default String 1',
				'string_2'  => 'Default String 2',
			),
			$atts );

		extract( $atts_actual );

		$demo_content = 'Short demo attributes are ' . $string_1 . ' and ' . $string_2;

		return $demo_content;


	}





}