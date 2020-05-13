<?php
namespace App\Helper;

use App\WordPress\WordPress;

/**
 * @version v1
 *
 * Render function
 */
class Render
{
    use WordPress;

    /**
     * Loads a view
     * @param  string $view
     * @param  object $data
     * @param  object $args
     * @example $this->loadView('grid', $data);
     * @return string Compiled view
     */
    public function view($view, $data = null, $args = null)
    {
        ob_start();
        include $this->getTemplateDirectory() . "/Theme/View/" . $view . ".php";
        $view = ob_get_contents();
        ob_end_clean();
        return $view;
    }
}
