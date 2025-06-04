<?php

namespace App\Http\Controllers;

class Controller
{

    protected $data = [];
    protected $perPage = 12;
    public function __construct() {}
    protected function loadTheme($view, $data = [])
    {
        return view('themes' . env('APP_THEME', '.tokoonline') . '/' . $view, $data);
    }
}
