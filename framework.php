<?php

namespace PluginCube;

# Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

class Framework 
{

    /**
     * Framework inctance arguments.
     *
     * @since 1.0.0
     * @access public
     * @var array
     */
    public $args = [];

    /**
     * Options instance.
     *
     * @since 1.0.0
     * @access public
     * @var object|null
     */
    public $options;

    /**
     * Freemius instance.
     *
     * @since 1.0.0
     * @access public
     * @var object|null
     */
    public $freemius;

    /**
     * Class constructer.
     * 
     * @since 1.0.0
     * @access public
     * @return void
     */
    public function __construct($args)
    {
        $this->path = trailingslashit(str_replace('\\', '/', dirname( __FILE__ )));
        $this->url = site_url(str_replace(str_replace('\\', '/', ABSPATH ), '', $this->path));

        require_once $this->path . 'freemius/start.php';
        require_once $this->path . 'options/options.php';

        $this->args = $args;

        $this->init_freemius();
        $this->init_options();

        add_action('admin_menu', [$this, "add_admin_page"]);
        add_action('admin_enqueue_scripts', [$this, "styles"]);
    }

    /**
     * Add admin page.
     * 
     * @since 1.0.0
     * @access public
     * @return void
     */
    public function add_admin_page()
    {
        add_menu_page(
            $this->args['title'],
            $this->args['title'],
            'manage_options',
            $this->args['slug'],
            [$this, 'render_page'],
            $this->args['icon'],
            99
        );
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
        if ( isset($_GET['page']) && strpos($_GET['page'], $this->args['slug']) !== false ) {
            wp_enqueue_style('plugincube-framework', $this->url . "assets/admin.css");
		}
    }

    /**
     * Render settings page.
     *
     * @since 1.0.0
     * @access public 
     * @return void
     */
    public function render_page()
    {
        ?>
            <div class="wrap fs-section fs-full-size-wrapper">
                <h2 class="nav-tab-wrapper">
                    <a href="#" class="nav-tab fs-tab nav-tab-active home">
                        Settings
                    </a>
                </h2>
                <div id="co"></div>
            </div>
        <?php
    }

    /**
     * Initialze freemius.
     * 
     * @since 1.0.0
     * @access public
     * @return void
     */
    public function init_freemius()
    {
        $this->freemius = fs_dynamic_init([
            'id'                  => $this->args['id'],
            'slug'                => $this->args['slug'],
            'type'                => 'plugin',
            'is_premium'          => true,
            'premium_suffix'      => 'pro',
            'has_premium_version' => true,
            'has_addons'          => false,
            'has_paid_plans'      => true,
            'public_key'          => $this->args['public_key'],
            'navigation'          => 'tabs',
            'menu'                => array(
                'slug'          => $this->args['slug'],
                'support'       => false,
            ),
        ]);

        $this->change_pricing_page();
        $this->change_tabs_icon();
    }
    
    /**
     * Change pricing page.
     * 
     * @since 1.0.0
     * @access public
     * @return void
     */
    public function change_pricing_page()
    {
        $this->freemius->add_filter( 'freemius_pricing_js_path', function () {
            return $this->path . "assets/pricing-page/dist/freemius-pricing.js";
        });
    }

    /**
     * Change tabs icon.
     * 
     * @since 1.0.0
     * @access public
     * @return void
     */
    public function change_tabs_icon()
    {
        $this->freemius->override_i18n([
            'symbol_arrow-right' => '',
            'symbol_arrow-left' => ''
        ]);
    }

    /**
     * Initialze options.
     * 
     * @since 1.0.0
     * @access public
     * @return void
     */
    public function init_options()
    {
        $this->options = new Options([
            'id' => sanitize_key($this->args['slug']),
        ]);
    }
}
