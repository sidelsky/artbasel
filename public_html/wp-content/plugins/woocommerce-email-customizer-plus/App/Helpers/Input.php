<?php

namespace Wecp\App\Helpers;
class Input
{
    /**
     * Fetch an item from POST data with fallback to GET
     * @param $index
     * @param string $type
     * @param null $default
     * @return mixed
     */
    function post_get($index, $default = NULL, $type = "text")
    {
        return isset($_POST[$index])
            ? $this->post($index, $default, $type)
            : $this->get($index, $default, $type);
    }

    /**
     * @param $index
     * @return bool
     */
    function has_get($index)
    {
        return isset($_GET[$index]);
    }

    /**
     * @param $index
     * @return bool
     */
    function has_post($index)
    {
        return isset($_POST[$index]);
    }
    /**
     * Fetch an item from the POST array
     * @param null $index
     * @param null $default
     * @param string $type
     * @return mixed
     */
    function post($index = NULL, $default = NULL, $type = "text")
    {
        if (empty($index)) {
            return $_POST;
        }
        $data = ($this->has_post($index)) ? $_POST[$index] : $default;
        return $this->_sanitize_data($data, $type);
    }

    /**
     * sanitize data
     * @param $data
     * @param string $type
     * @return string|void
     */
    function _sanitize_data($data, $type = "text")
    {
        switch ($type) {
            case "key":
                $data = sanitize_key($data);
                break;
            case "email":
                $data = sanitize_email($data);
                break;
            case "textarea":
                $data = sanitize_textarea_field($data);
                break;
            case "color":
                $data = sanitize_hex_color($data);
                break;
            default:
            case "text":
                $data = sanitize_text_field($data);
                break;
        }
        return $data;
    }

    /**
     * Fetch an item from the GET array
     * @param null $index
     * @param null $default
     * @param string $type
     * @return mixed
     */
    function get($index = NULL, $default = NULL, $type = "text")
    {
        if (empty($index)) {
            return $_GET;
        }
        $data = ($this->has_get($index)) ? $_GET[$index] : $default;
        return $this->_sanitize_data($data, $type);
    }

    /**
     * Fetch an item from GET data with fallback to POST
     * @param $index
     * @param string $type
     * @param null $default
     * @return mixed
     */
    function get_post($index, $default = NULL, $type = 'text')
    {
        return isset($_GET[$index])
            ? $this->get($index, $default, $type)
            : $this->post($index, $default, $type);
    }
}
