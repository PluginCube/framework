<?php

namespace CiraPress;

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
        
        if (! class_exists('Framework')) {
            require_once $this->path . 'options/options.php';
        }

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
        wp_enqueue_style('co-plugin', $this->url . "assets/admin.css");
    }

    /**
     * Render page.
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
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                            <path fill="none" d="M0 0h24v24H0z"/><path d="M9.954 2.21a9.99 9.99 0 0 1 4.091-.002A3.993 3.993 0 0 0 16 5.07a3.993 3.993 0 0 0 3.457.261A9.99 9.99 0 0 1 21.5 8.876 3.993 3.993 0 0 0 20 12c0 1.264.586 2.391 1.502 3.124a10.043 10.043 0 0 1-2.046 3.543 3.993 3.993 0 0 0-3.456.261 3.993 3.993 0 0 0-1.954 2.86 9.99 9.99 0 0 1-4.091.004A3.993 3.993 0 0 0 8 18.927a3.993 3.993 0 0 0-3.457-.26A9.99 9.99 0 0 1 2.5 15.121 3.993 3.993 0 0 0 4 11.999a3.993 3.993 0 0 0-1.502-3.124 10.043 10.043 0 0 1 2.046-3.543A3.993 3.993 0 0 0 8 5.071a3.993 3.993 0 0 0 1.954-2.86zM12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                        </svg>Settings
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
            'public_key'          => 'pk_700792c58148d25ae5da76ec8c28a',
            'is_premium'          => true,
            'premium_suffix'      => 'pro',
            'has_premium_version' => true,
            'has_addons'          => false,
            'has_paid_plans'      => true,
            'navigation'          => 'tabs',
            'menu'                => array(
                'slug'          => $this->args['slug'],
                'support'       => false,
            ),
        ]);

        $pricingSVG = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/>
            <path d="M12 2a6 6 0 0 1 6 6v1h4v2h-1.167l-.757 9.083a1 1 0 0 1-.996.917H4.92a1 1 0 0 1-.996-.917L3.166 11H2V9h4V8a6 6 0 0 1 6-6zm1 11h-2v4h2v-4zm-4 0H7v4h2v-4zm8 0h-2v4h2v-4zm-5-9a4 4 0 0 0-3.995 3.8L8 8v1h8V8a4 4 0 0 0-3.8-3.995L12 4z"/>
        </svg>';

        $contactSVG = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
            <path fill="none" d="M0 0h24v24H0z"/><path d="M7.291 20.824L2 22l1.176-5.291A9.956 9.956 0 0 1 2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10a9.956 9.956 0 0 1-4.709-1.176zM7 12a5 5 0 0 0 10 0h-2a3 3 0 0 1-6 0H7z"/>
        </svg>';

        $this->freemius->override_i18n([
            'upgrade' => $pricingSVG . \__('Upgrade', 'cira'),
            'contact-us' => $contactSVG . \__('Contact Us', 'cira'),
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
            'id' => $this->args['slug'],
        ]);
    }
}
