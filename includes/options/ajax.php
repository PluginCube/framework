<?php

namespace PluginCube\Options;

class AJAX 
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

        add_action('wp_ajax_co', [$this, 'request']);
    }

    /**
     * Process the requests.
     * 
     * @since 1.0.0
     * @access public
     * @return void
     */
    public function request()
    {        
        if ( ! check_ajax_referer('co', 'security')) {
            wp_die();
        }

        if ( ! current_user_can($this->parent->args['capability'])) {
            wp_die();
        }
        
        $method = isset($_REQUEST['method']) ? $_REQUEST['method'] : false;
        $data = isset($_REQUEST['data']) ? $_REQUEST['data'] : false;

        if ($method && method_exists($this, $method)) {
            $this->$method($data);
        }
    }

    /**
     * Respond.
     * 
     * @since 1.0.0
     * @access public
     * 
     * @param bool $success Indicate the request was processed successfully.
     * @param string|null $message Optional message to pass back with json the respons.
     * @param array $data Optional data to pass back with json the respons.
     * 
     * @return void
     */
    public function respond($success, $data = [])
    {
        if (wp_doing_ajax()) {
            if ($success) {
                wp_send_json_success($data);
            } else {
                wp_send_json_error($data);
            }
        }

        // for internal use
        return ['success' => $success, 'data' => $data];
    }

    /**
     * Save & update values.
     * 
     * @since 1.0.0
     * @access public
     * 
     * @param array $data Request data.
     * 
     * @return void
     */
    public function save($data)
    {
        $errors = $this->parent->get_errors($data['values']);
        
        if (! empty($errors)) {
            $this->respond(false, [
                'values' => $data['values'],
                'errors' => $errors
            ]);    
        } else {
            $values = $this->parent->sanitize($data['values']);
            update_option($this->parent->args['id'], $values);

            $this->respond(true, [
                'values' => $values,
                'errors' => $errors
            ]);    
        }
    }
}