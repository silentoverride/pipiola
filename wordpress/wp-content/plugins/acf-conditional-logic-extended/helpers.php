<?php

/**
 * acfcle_get_view
 * 
 * This function will load in a file from the 'admin/views' folder and allow variables to be passed through.
 *
 *  @param string $view_path
 *  @param array  $view_args
 *
 *  @return void
 */
function acfcle_get_view($view_path = '', $view_args = array())
{
    // allow view file name shortcut
    if (substr($view_path, -4) !== '.php') {
        $view_path = acfcle_get_path("includes/admin/views/{$view_path}.php");
    }

    // include
    if (file_exists($view_path)) {
        // Use `EXTR_SKIP` here to prevent `$view_path` from being accidentally/maliciously overridden.
        extract($view_args, EXTR_SKIP);
        include $view_path;
    }
}

/**
 * acfcle_get_path
 *
 * Returns the plugin path to a specified file.
 *
 * @param   string $filename The specified file.
 * @return  string
 */
function acfcle_get_path($filename = '')
{
    return ACFCLE_PATH . ltrim($filename, '/');
}

/**
 * acfcle_get_url
 *
 * Returns the plugin url to a specified file.
 *
 * @param	string $filename The specified file.
 * @return	string
 */
function acfcle_get_url($filename = '')
{
    return ACFCLE_URL . ltrim($filename, '/');
}
