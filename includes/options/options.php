<?php
/**
 * Creates an interactive wordpress options panel.
 *
 * @package    CiraPress
 * @subpackage Framework
 * @copyright  Copyright (c) 2020, CiraPress
 * @license    https://opensource.org/licenses/MIT
 * @since      1.0
 */

namespace CiraPress;

class Options 
{
    /**
     * Version, used for cache-busting.
     *
     * @since 1.0.0
     * @access private
     * @var string
     */
    private $version = '1.0.0';

    /**
     * Directory URL.
     *
     * @since 1.0.0
     * @access private
     * @var string|null
     */
    private $url;

    /**
     * Directory path.
     *
     * @since 1.0.0
     * @access private
     * @var string|null
     */
    private $path;

    /**
     * AJAX Class.
     *
     * @since 1.0.0
     * @access public
     * @var object
     */
    public $AJAX;

    /**
     * API Class.
     *
     * @since 1.0.0
     * @access public
     * @var object
     */
    public $API;

    /**
     * Translated strings for localization.
     *
     * @since 1.0.0
     * @access public
     * @var array
     */
    public $translation = [];
    
    /**
     * Current inctance arguments.
     *
     * @since 1.0.0
     * @access public
     * @var array
     */
    public $args = [];

    /**
     * Class constructer.
     * 
     * @since 1.0.0
     * @access public
     * 
     * @param array $args Current inctance arguments.
     * 
     * @return void
     */
    public function __construct($args)
    {
        $this->path = trailingslashit(str_replace('\\', '/', dirname( __FILE__ )));
        $this->url = site_url(str_replace(str_replace('\\', '/', ABSPATH ), '', $this->path));

        // Require files
        require_once $this->path . 'ajax.php';
        require_once $this->path . 'api.php';

        // Instance args
        $this->args = wp_parse_args($args, [
            'sections' => [],
            'menu' => [],
            'capability' => 'manage_options',
            'menu_icon' => '',
            'menu_position' => 99,            
        ]);

        // Translation file
        $this->translation = include $this->path . "/translation.php";
                
        // Enqueue assets
        if ($this->in_view()) {
            add_action('admin_enqueue_scripts', [$this, "styles"]);
            add_action('admin_enqueue_scripts', [$this, "scripts"]);
            add_action('admin_enqueue_scripts', [$this, "app_state"]);
        }
        
        $this->AJAX = new Options\AJAX($this);
        $this->API = new Options\API($this);
    }

    /**
     * Determine if settings page is currently in view.
     *
     * @since 1.0.0
     * @access public 
     * @return boolean
     */
    public function in_view()
    {            
        return $GLOBALS['pagenow'] === 'admin.php' && isset($_GET['page']) && $_GET['page'] === $this->args['id'];
    }

    /**
     * Enqueue scripts.
     *
     * @since 1.0.0
     * @access public 
     * @return void
     */
    public function scripts()
    {
        wp_enqueue_editor();
        wp_enqueue_media();
        
        wp_enqueue_script('co', $this->url . "app/dist/bundle.js", ['jquery', 'wp-tinymce', 'jquery-ui-sortable'], $this->version, true);
    }

    /**
     * Enqueue styles.
     *
     * @since 1.0.0
     * @access public 
     * @return void
     */
    public function styles()
    {
        if (file_exists($this->path . "app/dist/bundle.css")) {
            wp_enqueue_style('co', $this->url . "app/dist/bundle.css");
        }
    }

    /**
     * Pass the initial state to Svelte.
     *
     * @since 1.0.0
     * @access public 
     * @return void
     */
    public function app_state()
    {
        $data = [
            'id' => $this->args['id'],
            'menu' => $this->args['menu'],
            'sections' => $this->args['sections'],
            'values' => $this->get_values(),
            'errors' => $this->get_errors(),
            'defaults' => $this->get_defaults(),
            'translation' => $this->translation,
            'nonce' => wp_create_nonce('co'),
        ];

        wp_localize_script('co', 'CFStore', $data);
    }

    /**
     * Get default values.
     * 
     * @since 1.0.0
     * @access public 
     * @return array
     */
    public function get_defaults()
    {
        $defaults = [];

        foreach ($this->args['sections'] as $section) {
            $defaults[$section['id']] = [];

            foreach ($section['fields'] as $field) {
                if(isset($field['default'])){
                    $defaults[$section['id']][$field['id']] = $field['default'];
                } else {
                    $defaults[$section['id']][$field['id']] = null;
                }
            }
        }

        return $defaults;
    }

    /**
     * Get saved values.
     * 
     * @since 1.0.0
     * @access public 
     * @return array
     */
    public function get_values()
    {
        $values = get_option($this->args['id']);
        $defaults = $this->get_defaults();

        if ($values) return wp_unslash(wp_parse_args($values , $defaults));

        return $defaults;
    }

    /**
     * Validate the values.
     * 
     * @since 1.0.0
     * @access public 
     * 
     * @param array $values An array of values to compare against.
     * 
     * @return array
     */
    public function get_errors($values = NULL)
    {
        $errors = [];
        is_array($values) ?: $values = $this->get_values();

        foreach ($this->args['sections'] as $section) {
            foreach ($section['fields'] as $field) {
                if (isset($field['validate'])) {
                    $value = $values[$section['id']][$field['id']];
                    $result = call_user_func($field['validate'], $value);
                    
                    if( ! empty($result) ) {
                        $errors[$section['id']][$field['id']] = $result;
                    }
                }
            }
        }

        return $errors;
    }

    /**
     * Sanitize the values.
     * 
     * @since 1.0.0
     * @access public
     * 
     * @param array $values An array of values to sanitize.
     * 
     * @return array
     */
    public function sanitize($values)
    {
        $types = [
            'text' => 'sanitize_text_field',
            'textarea' => 'sanitize_textarea_field',
            'editor' => 'wp_filter_post_kses',
            'color' => 'sanitize_text_field',
            'image' => 'sanitize_url',
            'icon' => 'sanitize_html_class',
            'select' => 'sanitize_key',
            'radio-image' => 'sanitize_key',
            'switch' => function ($val) {
                return filter_var($val, FILTER_VALIDATE_BOOLEAN);
            },
            'repeater' => function ($val) {
                return $val;
            },
        ];

        foreach ($values as $sectionID => &$fields) {
            foreach ($fields as $id => &$value) {
                $section = $this->args['sections'][array_search($sectionID, array_column($this->args['sections'], 'id'))];
                $field = $section['fields'][array_search($id, array_column($section['fields'], 'id'))];
                $sanitize = isset($field['sanitize']) ? $field['sanitize'] : $types[$field['type']];

                if ($sanitize) {
                    $value = call_user_func($sanitize, $value);
                }
            }
        }
        
        return $values;
    }

    /**
     * Add an element to the instance.
     * 
     * @since 1.0.0
     * @access public
     * @api
     * 
     * @param array $type Element type.
     * @param array $args Element Arguments.
     * 
     * @return void
     */
    public function add($type, $args)
    {
        switch ($type) {
            case 'field':
                $this->API->add_field($args);
                break;

            case 'section':
                $this->API->add_section($args);
                break;

            case 'link':
                $this->API->add_link($args);
                break;

            default:
                $this->API->add_field($args);
                break;
        }
    }
}