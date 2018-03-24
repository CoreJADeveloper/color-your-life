<?php
/**
 * Plugin Name: Color Your Life
 * Description: Color Your Life
 * Version: 1.0.0
 * Author: Tauhidul Alam
 */

//Check if the file is directly accessed
if (!defined('ABSPATH')) {
    exit;
}

final class colorYourLife
{
    //Implements singleton pattern to stop creating duplicate object instance
    protected static $_instance = null;

    /**
     * The static function which instantiate constructor function of ngContactForm class
     *
     * @since 1.0.0
     * @return object
     */
    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function __construct()
    {
        $this->_define();
        $this->_require();
        $this->_actions();
    }

    /**
     * Define some constant variables to use within whole plugin
     *
     * @since 1.0.0
     */
    private function _define()
    {
        define('CYL_PLUGIN_PATH', plugin_dir_path(__FILE__));
        define('CYL_PLUGIN_URL', plugins_url('/', __FILE__));
    }

    /**
     * Requires necessary php files
     *
     * @since 1.0.0
     */
    private function _require()
    {
    }

    /**
     * Implements all necessary action/filter hooks
     *
     * @since 1.0.0
     */
    private function _actions()
    {
        add_action('init', array($this, 'add_short_code'));
        add_action('wp_enqueue_scripts', array($this, 'cyl_enqueue_script'));
    }

    /**
     * Enqueue all necessary scripts and styles for short code page
     *
     * @since 1.0.0
     */
    public function cyl_enqueue_script(){
        wp_enqueue_script('jquery');
//        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_style('cyl_front_style', CYL_PLUGIN_URL. 'assets/css/style.css');
//        wp_enqueue_script('cyl_iris', CYL_PLUGIN_URL. 'assets/js/iris.js' );

        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script(
            'iris',
            admin_url( 'js/iris.min.js' ),
            array( 'jquery-ui-draggable', 'jquery-ui-slider', 'jquery-touch-punch' ),
            false,
            1
        );
        wp_enqueue_script(
            'wp-color-picker',
            admin_url( 'js/color-picker.min.js' ),
            array( 'iris' ),
            false,
            1
        );
        $colorpicker_l10n = array(
            'clear' => __( 'Clear' ),
            'defaultString' => __( 'Default' ),
            'pick' => __( 'Select Color' ),
            'current' => __( 'Current Color' ),
        );
        wp_localize_script( 'wp-color-picker', 'wpColorPickerL10n', $colorpicker_l10n );

        wp_enqueue_script('cyl_front_script', CYL_PLUGIN_URL. 'assets/js/script.js', array( 'wp-color-picker' ));
    }

    /**
     * Include a template
     *
     * @since 1.1.0
     * @param string $template_name
     * @param mixed $args
     * @param string $template_path
     * @param string $default_path
     */
    private function cyl_locate_template_part($template_name, $args = array(), $template_path = '', $default_path = '')
    {
        if (isset($args) && is_array($args)) :
            extract($args);
        endif;

        $template_file = $this->cyl_locate_template($template_name, $template_path, $default_path);

        include $template_file;
    }

    /**
     * Process file location to include
     *
     * @since 1.1.0
     * @param string $template_name
     * @param string $template_path
     * @param string $default_path
     * @return string
     */
    private function cyl_locate_template($template_name, $template_path = '', $default_path = '')
    {
        if (!$template_path) :
            $template_path = 'templates/';
        endif;

        if (!$default_path) :
            $default_path = CYL_PLUGIN_PATH . 'templates/';
        endif;

        $template = locate_template(array(
            $template_path . $template_name,
            $template_name
        ));

        if (!$template) :
            $template = $default_path . $template_name;
        endif;

        return apply_filters('cyl_contact_form_locate_template',
            $template, $template_name, $template_path, $default_path);
    }

    /**
     * Create short code - colorYourLife
     *
     * @since 1.0.0
     */
    public function add_short_code()
    {
        add_shortcode('colorYourLife', array($this, 'generate_short_code_content'));
    }

    /**
     * Generates short code content
     *
     * @since 1.1.0
     * @param mixed $atts
     * @return string
     */
    public function generate_short_code_content($atts)
    {
        $short_code_atts = shortcode_atts(array(), $atts);

        ob_start();

        $this->cyl_locate_template_part('shortcode/colorYourLife.php', []);

        return ob_get_clean();
    }
}

/**
 * Instantiate static function of main class
 *
 * @since 1.0.0
 */
function color_your_life()
{
    return colorYourLife::instance();
}

$GLOBALS['color_your_life'] = color_your_life();