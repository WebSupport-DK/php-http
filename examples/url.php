<?php

// load class
require_once '../src/Url.php';

// use class
use Datalaere\PHPHttp\Url;

// redirect with header location to url
Url::redirect($location='http://test.com/home');

// redirect back to previous page with javascript 
Url::redirectBack();
