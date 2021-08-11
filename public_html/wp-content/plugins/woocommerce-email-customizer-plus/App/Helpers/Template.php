<?php

namespace Wecp\App\Helpers;

class Template
{
    protected $path, $data = array();

    function __construct()
    {
        return $this;
    }

    /**
     * set the path and data of the template
     * @param $path
     * @param $data
     * @return $this
     */
    function setData($path, $data = array())
    {
        if (!is_array($data)) {
            $data = array();
        }
        $this->path = $path;
        $this->data = $data;
        return $this;
    }

    /**
     * get the rendered template
     * @return false|string|void
     */
    function render()
    {
        return $this->processTemplate();
    }

    /**
     * print out the template
     */
    function display()
    {
        echo $this->processTemplate();
    }

    /***
     * process the template nad return content
     * @return false|string|void
     */
    function processTemplate()
    {
        if (file_exists($this->path)) {
            try {
                ob_start();
                if (!empty($this->data)) {
                    extract($this->data);
                }
                include $this->path;
                return ob_get_clean();
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        }
        return __('Template not found', WECP_TEXT_DOMAIN);
    }
}