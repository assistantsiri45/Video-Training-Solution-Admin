<?php

if (!function_exists('mv_upload')) {
    function mv_upload($file, $path, $file_prefix = null)
    {
        $name = mv_unique_name($file_prefix) . '-' . $file->getClientOriginalName();
        $file->move($path, $name);
        return $path . '/' . $name;
    }
}


if (!function_exists('mv_unique_name')) {
    function mv_unique_name($file_prefix)
    {
        if (!isset($file_prefix)) {
            $file_prefix = env('APP_NAME');
            $file_prefix .= '-';
        }
        $unique_string = uniqid($file_prefix, true);
        $str = "1234567890abcdefghijklmnopqrstuvwxyz()$";
        $rand_string = substr(str_shuffle($str), 0, 8);
        $unique_string .= $rand_string;
        return $unique_string;
    }
}

if (!function_exists('mv_activate_menu')) {
    function mv_activate_menu($url)
    {
        if (str_is("$url", url()->current()) || str_is("$url/*", url()->current())) {
            return 'active';
        }
        return "inactive";
    }
}

if (!function_exists('mv_image')) {
    function mv_image($value)
    {
        if (isset($value))
            return asset($value);
        else
            return asset('backend/img/no-image.png');
    }
}


