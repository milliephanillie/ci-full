<?php
/**
 *
 * Name: Global configuration file
 * Description: File that consists all theme global configuration
 *
 * @author pebas
 * @package Pebas
 * @version 1.0.0
 */

namespace Lisfinity;

use ArrayAccess;
use RuntimeException;

if ( ! class_exists( 'Configuration' ) ) :
	/**
	 * Pebas_Config class
	 */
	class Configuration {
		/**
		 * Class instance var
		 * ------------------
		 *
		 * @var null
		 */
		protected static $_instance = null;

		/**
		 * Variable for assigning theme name to it
		 * ---------------------------------------
		 *
		 * @var string
		 */
		public static $theme_name;

		/**
		 * Class instance function
		 * -----------------------
		 *
		 * @return null| _Config
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		/**
		 * Config cache.
		 *
		 * @var array|null
		 */
		protected $config = null;

		/**
		 * Config constructor.
		 */
		public function __construct() {

			// get theme information from style.css.
			$theme = wp_get_theme();

			// get info of parent theme if using child theme.
			$theme = $theme->parent() ? $theme->parent() : $theme;

			self::$theme_name = sanitize_file_name( strtolower( $theme->name ) );

			// define theme version.
			if ( ! defined( 'PBS_THEME_VERSION' ) ) {
				define( 'PBS_THEME_VERSION', $theme->version );
			}
			// define root server path of the parent theme.
			if ( ! defined( 'PBS_THEME' ) ) {
				define( 'PBS_THEME', get_parent_theme_file_path() . '/' );
			}
			// define root server path of the child theme.
			if ( ! defined( 'PBS_THEME_CHILD' ) ) {
				define( 'PBS_THEME_CHILD', get_stylesheet_directory() . '/' );
			}
			// define http url of the loaded parent theme.
			if ( ! defined( 'PBS_THEME_URL' ) ) {
				define( 'PBS_THEME_URL', get_template_directory_uri() . '/' );
			}
			// define http or url of the loaded child theme.
			if ( ! defined( 'PBS_THEME_URL_CHILD' ) ) {
				define( 'PBS_THEME_URL_CHILD', get_stylesheet_directory_uri() . '/' );
			}
			// define name of the currently loaded theme.
			if ( ! defined( 'PBS_THEME_NAME' ) ) {
				define( 'PBS_THEME_NAME', self::$theme_name, $theme['title'] );
			}
			// define home website of the theme.
			if ( ! defined( 'PBS_WEBSITE' ) ) {
				define( 'PBS_WEBSITE', $theme['author_uri'] );
			}

		}

		/**
		 * Load the config.
		 */
		protected function load() {
			global $wp_filesystem;
			$file = PBS_THEME . 'config.json';

			if ( ! file_exists( $file ) ) {
				throw new RuntimeException( 'The required theme config.json file is missing.' );
			}

			require_once( ABSPATH . '/wp-admin/includes/file.php' );
			WP_Filesystem();
			$contents = $wp_filesystem->get_contents( $file );
			$config   = json_decode( $contents, true );

			$json_error = json_last_error();

			if ( $json_error !== JSON_ERROR_NONE ) {
				throw new RuntimeException( 'The required theme config.json file is not valid JSON (error code ' . $json_error . ').' );
			}

			return $config;
		}

		/**
		 * Get the entire config array.
		 *
		 * @return array
		 */
		protected function getAll() {
			if ( $this->config === null ) {
				$this->config = $this->load();
			}

			return $this->config;
		}

		/**
		 * Get a config value.
		 *
		 * @param string $key
		 * @param mixed $default
		 *
		 * @return mixed
		 */
		public function get( $key, $default = null ) {
			return static::getArrayItem( $this->getAll(), $key, $default );
		}

		/**
		 * Determine whether the given value is array accessible.
		 *
		 * @param mixed $value
		 *
		 * @return bool
		 */
		public static function accessible( $value ) {
			return is_array( $value ) || $value instanceof ArrayAccess;
		}

		/**
		 * Determine if the given key exists in the provided array.
		 *
		 * @param \ArrayAccess|array $array
		 * @param string|int $key
		 *
		 * @return bool
		 */
		public static function exists( $array, $key ) {
			if ( $array instanceof ArrayAccess ) {
				return $array->offsetExists( $key );
			}

			return array_key_exists( $key, $array );
		}

		/**
		 * Get an item from an array using "dot" notation.
		 *
		 * @param \ArrayAccess|array $array
		 * @param string $key
		 * @param mixed $default
		 *
		 * @return mixed
		 */
		public static function getArrayItem( $array, $key, $default = null ) {
			if ( ! static::accessible( $array ) ) {
				return $default;
			}

			if ( is_null( $key ) ) {
				return $array;
			}

			if ( static::exists( $array, $key ) ) {
				return $array[ $key ];
			}

			foreach ( explode( '.', $key ) as $segment ) {
				if ( static::accessible( $array ) && static::exists( $array, $segment ) ) {
					$array = $array[ $segment ];
				} else {
					return $default;
				}
			}

			return $array;
		}

	}
endif;
