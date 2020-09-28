<?php

namespace CiraPress\Options;

class API 
{
    /**
     * Parent instance.
     *
     * @since 1.0.0
     * @access private
     * @var string
     */
    private $parent;

    /**
     * Class constructer.
     * 
     * @since 1.0.0
     * @access public
     * 
     * @param array $parent The parent instance.
     * 
     * @return void
     */
    public function __construct($parent)
    {
        $this->parent = $parent;
    }

    /**
     * Add a section
     * 
     * @since 1.0.0
     * @access public
     * 
     * @param array $args Section arguments.
     * 
     * @return void
     */
    public function add_section($args)
    {
        do_action('co/add/section/before', $args);
        
        $args = wp_parse_args($args, [
            'title' => null,
            'description' => null,
            'fields' => []
        ]);

        $this->parent->args['sections'][] = $args;

        do_action('co/add/section/after', $args);
    }

    /**
     * Add a field
     * 
     * @since 1.0.0
     * @access public
     * 
     * @param array $args Field arguments.
     * 
     * @return void
     */
    public function add_field($args)
    {
        do_action('co/add/field/before', $args);

        $args = wp_parse_args($args, [
            'type' => 'text',
            'title' => null,
            'description' => null,
            'priority' => 10,
            'default' => null,
        ]);
        
        $this->parent->args['fields'][] = $args;

        foreach ($this->parent->args['sections'] as &$section) {
            if ($section['id'] === $args['section']) {
                $section['fields'][] = $args;
            }
        }

        do_action('co/add/field/after', $args);
    }

    /**
     * Add a link to the menu
     * 
     * @since 1.0.0
     * @access public
     * 
     * @param array $args Menu item arguments.
     * 
     * @return void
     */
    public function add_link($args)
    {
        do_action('co/add/link/before', $args);

        $args = wp_parse_args($args, [
            'type' => 'section',
            'title' => null,
            'icon' => 'ri-settings-4-fill',
            'priority' => 10,
        ]);

        $this->parent->args['menu'][] = $args;

        do_action('co/add/link/after', $args);
    }
}