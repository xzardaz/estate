<?php

/**
 * /application/core/XZ_Loader.php
 *
 */
class MY_Loader extends CI_Loader {
    public function template($template_name, $vars = array(), $return = FALSE)
    {
        $content  = $this->view('head', $vars, $return);
        $content .= $this->view($template_name, $vars, $return);
        $content .= $this->view('foot', $vars, $return);

        if ($return)
        {
            return $content;
        }
    }
}
