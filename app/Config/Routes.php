<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Routing\RouteCollection;
use Config\Services;

// ----------------------------------------------
// Load the system's routing file FIRST
// ----------------------------------------------
$routes = Services::routes();

if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

// ----------------------------------------------
// Default Routing Setup
// ----------------------------------------------
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

// ----------------------------------------------
// CUSTOM ROUTES
// ----------------------------------------------
$routes->get('/', 'Home::index');

// ----------------------------------------------
// Environment Routes (optional)
// ----------------------------------------------
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
