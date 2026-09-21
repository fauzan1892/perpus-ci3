<?php defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('perpus_upload_config'))
{
    /**
     * Return a restrictive, reusable CodeIgniter upload configuration.
     */
    function perpus_upload_config($relative_path, $allowed_types, $max_size = 2048)
    {
        $relative_path = trim(str_replace('\\', '/', $relative_path), '/').'/';
        $upload_path = FCPATH.$relative_path;

        if (!is_dir($upload_path))
        {
            @mkdir($upload_path, 0750, TRUE);
        }

        return array(
            'upload_path'      => $upload_path,
            'allowed_types'    => $allowed_types,
            'max_size'         => $max_size,
            'encrypt_name'     => TRUE,
            'detect_mime'      => TRUE,
            'file_ext_tolower' => TRUE,
            'mod_mime_fix'     => TRUE,
            'remove_spaces'    => TRUE,
            'xss_clean'        => TRUE,
        );
    }
}
