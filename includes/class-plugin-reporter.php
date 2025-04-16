<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://github.com/notalentgeek/wp-plugin-reporter
 * @since      1.0.0
 *
 * @package    Plugin_Reporter
 * @subpackage Plugin_Reporter/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * @since      1.0.0
 * @package    Plugin_Reporter
 * @subpackage Plugin_Reporter/includes
 * @author     New Naratif Development Team <tech@newnaratif.com>
 */
class Plugin_Reporter {

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $plugin_name    The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected $version;

    /**
     * The exporter object.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Plugin_Reporter_Exporter    $exporter    The exporter object.
     */
    protected $exporter;

    /**
     * The admin object.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Plugin_Reporter_Admin    $admin    The admin object.
     */
    protected $admin;

    /**
     * The update info object.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Plugin_Reporter_Update_Info    $update_info    The update info object.
     */
    protected $update_info;

    /**
     * Define the core functionality of the plugin.
     *
     * @since    1.0.0
     */
    public function __construct() {
        if ( defined( 'PLUGIN_REPORTER_VERSION' ) ) {
            $this->version = PLUGIN_REPORTER_VERSION;
        } else {
            $this->version = '1.0.0';
        }
        $this->plugin_name = 'plugin-reporter';

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies() {
        /**
         * The class responsible for plugin update information.
         */
        require_once PLUGIN_REPORTER_PATH . 'includes/class-plugin-reporter-update-info.php';
        $this->update_info = new Plugin_Reporter_Update_Info();

        /**
         * The class responsible for exporting plugin data.
         */
        require_once PLUGIN_REPORTER_PATH . 'includes/class-plugin-reporter-exporter.php';
        $this->exporter = new Plugin_Reporter_Exporter();

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once PLUGIN_REPORTER_PATH . 'admin/class-plugin-reporter-admin.php';
        $this->admin = new Plugin_Reporter_Admin( $this->get_plugin_name(), $this->get_version(), $this->exporter, $this->update_info );
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale() {
        add_action( 'plugins_loaded', array( $this, 'load_plugin_textdomain' ) );
    }

    /**
     * Load the plugin text domain for translation.
     *
     * @since    1.0.0
     */
    public function load_plugin_textdomain() {
        load_plugin_textdomain(
            'plugin-reporter',
            false,
            dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
        );
    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks() {
        // Admin styles and scripts
        add_action( 'admin_enqueue_scripts', array( $this->admin, 'enqueue_styles' ) );
        add_action( 'admin_enqueue_scripts', array( $this->admin, 'enqueue_scripts' ) );

        // Admin menu
        add_action( 'admin_menu', array( $this->admin, 'add_plugin_admin_menu' ) );

        // Process exports
        add_action( 'admin_init', array( $this->admin, 'process_export' ) );
    }

    /**
     * Run the plugin.
     *
     * @since    1.0.0
     */
    public function run() {
        // The plugin is now running
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name() {
        return $this->plugin_name;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version() {
        return $this->version;
    }
}
