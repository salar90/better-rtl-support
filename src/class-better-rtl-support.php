<?php
/**
 * Main plugin class.
 *
 * @package Better_RTL_Support
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Loads the RTL font and theme fixes, and the Appearance → RTL Settings page.
 *
 * Was `better_RTL_support` before 3.0; PHP class names are case-insensitive, so that name still works.
 */
class Better_RTL_Support {

	const OPTION = 'brtls_settings';
	const PAGE   = 'rtl-settings';

	/**
	 * Templates (parent theme slugs) that have a stylesheet in assets/themes/.
	 */
	const THEME_FIXES = array( 'hueman', 'twentyseventeen', 'twentysixteen' );

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->assign_hooks();
	}

	/**
	 * Registers all hooks.
	 */
	public function assign_hooks() {
		add_action( 'init', array( $this, 'load_textdomain' ) );

		add_filter( 'body_class', array( $this, 'add_body_classes' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ), 50 );
		add_action( 'enqueue_block_assets', array( $this, 'enqueue_editor_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_filter( 'wp_theme_json_data_theme', array( $this, 'filter_theme_json' ) );

		if ( is_admin() ) {
			add_action( 'admin_menu', array( $this, 'admin_menu' ) );
			add_action( 'admin_init', array( $this, 'settings_init' ) );
			add_filter( 'plugin_action_links_' . plugin_basename( BRTLS_FILE ), array( $this, 'plugin_action_links' ) );
		}
	}

	/**
	 * Loads bundled translations (wordpress.org language packs take precedence).
	 */
	public function load_textdomain() {
		load_plugin_textdomain( 'better-rtl-support', false, dirname( plugin_basename( BRTLS_FILE ) ) . '/languages' );
	}

	/**
	 * Default settings. They match the 2.x behaviour, plus the font in the block editor.
	 *
	 * @return array
	 */
	public static function defaults() {
		return array(
			'font'        => 'vazirmatn', // 'vazirmatn' or 'none' (keep the theme's fonts).
			'font_site'   => 1,
			'font_editor' => 1,
			'font_admin'  => 0,
			'theme_fixes' => 1,
		);
	}

	/**
	 * Saved settings merged over the defaults.
	 *
	 * @return array
	 */
	public function get_settings() {
		$saved = get_option( self::OPTION, array() );
		return array_merge( self::defaults(), is_array( $saved ) ? $saved : array() );
	}

	/**
	 * Sanitizes the settings form.
	 *
	 * @param mixed $input Raw option value.
	 * @return array
	 */
	public function sanitize_settings( $input ) {
		$input = is_array( $input ) ? $input : array();
		$out   = array(
			'font' => ( isset( $input['font'] ) && 'none' === $input['font'] ) ? 'none' : 'vazirmatn',
		);
		foreach ( array( 'font_site', 'font_editor', 'font_admin', 'theme_fixes' ) as $key ) {
			$out[ $key ] = empty( $input[ $key ] ) ? 0 : 1;
		}
		return $out;
	}

	/**
	 * Whether the RTL font applies in a context. Only RTL locales get it.
	 *
	 * @param string $context 'site', 'editor' or 'admin'.
	 * @return bool
	 */
	public function font_enabled( $context ) {
		$settings = $this->get_settings();
		$enabled  = is_rtl() && 'vazirmatn' === $settings['font'] && ! empty( $settings[ 'font_' . $context ] );

		/**
		 * Filters whether the RTL font is used.
		 *
		 * @since 3.0.0
		 *
		 * @param bool   $enabled Whether the font is used.
		 * @param string $context 'site', 'editor' or 'admin'.
		 */
		return (bool) apply_filters( 'brtls_font_enabled', $enabled, $context );
	}

	/**
	 * CSS font-family value used for text.
	 *
	 * @return string
	 */
	public function font_stack() {
		/**
		 * Filters the CSS font-family value used for text.
		 *
		 * @since 3.0.0
		 *
		 * @param string $stack Default "'Vazirmatn', sans-serif".
		 */
		$stack = (string) apply_filters( 'brtls_font_stack', "'Vazirmatn', sans-serif" );
		// It ends up inside a CSS declaration: keep it from closing the rule or the style tag.
		return str_replace( array( '{', '}', ';', '<', '>' ), '', $stack );
	}

	/**
	 * Whether the active theme is a block theme with font presets, which are replaced through theme.json.
	 * Classic themes (and block themes without presets) get the CSS selectors in assets/front.css instead.
	 *
	 * @return bool
	 */
	public function uses_theme_json_fonts() {
		if ( ! function_exists( 'wp_is_block_theme' ) || ! wp_is_block_theme() || ! class_exists( 'WP_Theme_JSON_Data' ) ) {
			return false;
		}
		$families = wp_get_global_settings( array( 'typography', 'fontFamilies', 'theme' ) );
		return ! empty( $families );
	}

	/**
	 * Block themes: point every text font preset of the theme at the RTL font, so the theme's
	 * own styles, block settings and the user's global style choices all keep working.
	 * Monospace presets are left alone.
	 *
	 * @param WP_Theme_JSON_Data $theme_json Theme data.
	 * @return WP_Theme_JSON_Data
	 */
	public function filter_theme_json( $theme_json ) {
		if ( ! $this->font_enabled( is_admin() ? 'editor' : 'site' ) || ! is_object( $theme_json ) || ! method_exists( $theme_json, 'get_data' ) ) {
			return $theme_json;
		}

		$data     = $theme_json->get_data();
		$families = isset( $data['settings']['typography']['fontFamilies'] ) ? $data['settings']['typography']['fontFamilies'] : array();
		// WP 6.6+ returns presets keyed by origin; older versions a plain list.
		if ( isset( $families['theme'] ) ) {
			$families = $families['theme'];
		}
		if ( empty( $families ) || ! is_array( $families ) ) {
			return $theme_json;
		}

		$stack = $this->font_stack();
		foreach ( $families as $i => $family ) {
			if ( ! is_array( $family ) ) {
				continue;
			}
			$haystack = strtolower( implode( ' ', array_map( 'strval', array_intersect_key( $family, array_flip( array( 'slug', 'name', 'fontFamily' ) ) ) ) ) );
			if ( preg_match( '/mono|code|courier/', $haystack ) ) {
				continue;
			}
			$family['fontFamily'] = $stack;
			unset( $family['fontFace'] ); // Don't download the theme's own web fonts.
			$families[ $i ] = $family;
		}

		return $theme_json->update_with(
			array(
				'version'  => isset( $data['version'] ) ? $data['version'] : 2,
				'settings' => array( 'typography' => array( 'fontFamilies' => array_values( $families ) ) ),
			)
		);
	}

	/**
	 * Enqueues the @font-face rules and the --brtls-font-text variable.
	 */
	private function enqueue_font() {
		$handle = 'brtls-fontface-vazirmatn';
		if ( wp_style_is( $handle ) ) {
			return;
		}
		wp_enqueue_style( $handle, plugins_url( 'font-faces/Vazirmatn.css', BRTLS_FILE ), array(), BRTLS_VERSION );
		wp_add_inline_style( $handle, ':root{--brtls-font-text:' . $this->font_stack() . ';}' );
	}

	/**
	 * Enqueues a stylesheet from assets/.
	 *
	 * @param string $handle Style handle.
	 * @param string $file   Path relative to assets/.
	 */
	private function enqueue_asset( $handle, $file ) {
		wp_enqueue_style( $handle, plugins_url( 'assets/' . $file, BRTLS_FILE ), array(), BRTLS_VERSION );
	}

	/**
	 * Front end: font, theme fixes, and the admin bar font when the dashboard font is on.
	 */
	public function enqueue_styles() {
		if ( ! is_rtl() ) {
			return;
		}

		if ( $this->font_enabled( 'site' ) ) {
			$this->enqueue_font();
			$this->enqueue_asset( 'brtls-styles', $this->uses_theme_json_fonts() ? 'block-theme.css' : 'front.css' );
		}

		$settings = $this->get_settings();
		$template = get_template();
		if ( ! empty( $settings['theme_fixes'] ) && in_array( $template, self::THEME_FIXES, true ) ) {
			$this->enqueue_asset( 'brtls-theme-' . $template, 'themes/' . $template . '.css' );
		}

		if ( is_admin_bar_showing() && $this->font_enabled( 'admin' ) ) {
			$this->enqueue_font();
			$this->enqueue_asset( 'brtls-admin', 'admin.css' );
		}
	}

	/**
	 * Block editor content (post and site editor). Block themes already get the font through theme.json.
	 */
	public function enqueue_editor_styles() {
		if ( ! is_admin() || ! $this->font_enabled( 'editor' ) ) {
			return;
		}
		$this->enqueue_font();
		if ( ! $this->uses_theme_json_fonts() ) {
			$this->enqueue_asset( 'brtls-editor', 'editor.css' );
		}
	}

	/**
	 * Dashboard screens and the admin bar.
	 */
	public function enqueue_admin_styles() {
		if ( ! $this->font_enabled( 'admin' ) ) {
			return;
		}
		$this->enqueue_font();
		$this->enqueue_asset( 'brtls-admin', 'admin.css' );
	}

	/**
	 * Adds Appearance → RTL Settings.
	 */
	public function admin_menu() {
		add_theme_page(
			__( 'RTL Settings', 'better-rtl-support' ),
			__( 'RTL Settings', 'better-rtl-support' ),
			'manage_options',
			self::PAGE,
			array( $this, 'settings_page' )
		);
	}

	/**
	 * Registers the option (saved through options.php, which checks the nonce and manage_options).
	 */
	public function settings_init() {
		register_setting(
			'brtls',
			self::OPTION,
			array(
				'type'              => 'array',
				'sanitize_callback' => array( $this, 'sanitize_settings' ),
				'default'           => self::defaults(),
			)
		);
	}

	/**
	 * Renders the settings page.
	 */
	public function settings_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		$settings     = $this->get_settings();
		$block_theme  = function_exists( 'wp_is_block_theme' ) && wp_is_block_theme();
		$theme_fixes  = in_array( get_template(), self::THEME_FIXES, true );
		$rtl_language = is_rtl();
		include __DIR__ . '/views/settings-page.php';
	}

	/**
	 * Adds a Settings link on the Plugins screen.
	 *
	 * @param array $links Action links.
	 * @return array
	 */
	public function plugin_action_links( $links ) {
		$url = admin_url( 'themes.php?page=' . self::PAGE );
		array_unshift( $links, '<a href="' . esc_url( $url ) . '">' . esc_html__( 'Settings', 'better-rtl-support' ) . '</a>' );
		return $links;
	}

	/**
	 * Adds a theme-<template> body class (used by the theme fixes).
	 *
	 * @param string[] $classes Body classes.
	 * @return string[]
	 */
	public function add_body_classes( $classes ) {
		$classes[] = 'theme-' . sanitize_html_class( get_template() );
		return $classes;
	}
}
