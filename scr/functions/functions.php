<?php

function app_location()
{
    return __DIR__ . '/../Core/';

}

function view_base_path()
{
    return $GLOBALS['view_base_path'] = resource_path('views');
}

function app_view($view, $data = [], $mergeData = [])
{
    $DirName = basename(dirname(dirname(debug_backtrace()[0]['file'])));
    return view($DirName . '.Views.' . $view, $data, $mergeData);
}

function read_dir($path)
{
    $file_list = [];
    if (!is_dir($path)) return $file_list;
    $file_name = opendir($path);
    while ($file = readdir($file_name)) {
        if ($file == '.' || $file == '..') continue;
        $new_dir_name = $path . '/' . $file;
        if (is_dir($new_dir_name)) $file_list[$file] = $new_dir_name;
    }
    return $file_list;
}