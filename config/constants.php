<?php

//GLOBALs
define("SITE_URL", $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST']);
define('BASE_DIR', dirname(__DIR__));

//DIRs
const CONFIG_DIR = BASE_DIR . '/config';
const VIEW_DIR = BASE_DIR . '/App/Views/';

//URLs
const ASSETS_URI = SITE_URL . '/assets/';
