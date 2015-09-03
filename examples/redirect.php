<?php

// load class
require_once '../Redirect.php';

// use class
use thom855j\PHPHttp\Redirect;

// redirect with header location to url
Redirect::to($location='http://test.com/home');

// redirect back to previous page with javascript 
Redirect::back();
