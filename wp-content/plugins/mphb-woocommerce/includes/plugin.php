<?php

namespace MPHBW;

class Plugin {

	/**
	 *
	 * @var \MPHBW\Plugin
	 */
	private static $instance = null;

	/**
	 *
	 * @var string
	 */
	private static $filepath;

	/**
	 *
	 * @var Settings\SettingsRegistry
	 */
	private $settings;

	/**
	 *
	 * @var PluginData
	 */
	private $pluginData;

	/**
	 *
	 * @var Dependencies
	 */
	private $dependencies;

	private function __construct(){
		// Do nothing.
	}

	/**
	 *
	 * @param string $filepath
	 */
	public static function setBaseFilepath( $filepath ){
		self::$filepath = $filepath;
	}

	public static function getInstance(){
		if ( !isset( self::$instance ) ) {
			self::$instance = new self();
			self::$instance->afterConstruct();
		}
		return self::$instance;
	}

	public function afterConstruct(){

		$this->pluginData	 = new PluginData( self::$filepath );
		$this->settings		 = new Settings\SettingsRegistry();
		$this->dependencies	 = new Dependencies();
		new AutoUpdater();

		add_action( 'plugins_loaded', array( $this, 'loadTextdomain' ) );
		add_action( 'plugins_loaded', array( $this, 'initGateway' ) );
	}

	public function initGateway(){
		if ( $this->dependencies->check() ) {
			new \MPHBW\WoocommerceGateway();
		}
	}

	/**
	 *
	 * @return Settings\SettingsRegistry
	 */
	public function getSettings(){
		return $this->settings;
	}

	/**
	 *
	 * @return Dependencies
	 */
	function getDependencies(){
		return $this->dependencies;
	}

	/**
	 *
	 * @return PluginData
	 */
	public function getPluginData(){
		return $this->pluginData;
	}

	public function loadTextDomain(){

		$slug = $this->pluginData->getSlug();

		$locale = mphbw_is_wp_version( '4.7', '>=' ) ? get_user_locale() : get_locale();

		$locale = apply_filters( 'plugin_locale', $locale, $slug );

		// wp-content/languages/mphb-woocommerce/mphb-woocommerce-{lang}_{country}.mo
		$customerMoFile = sprintf( '%1$s/%2$s/%2$s-%3$s.mo', WP_LANG_DIR, $slug, $locale );

		load_textdomain( $slug, $customerMoFile );

		load_plugin_textdomain( $slug, false, $slug . '/languages' );
	}

}
