<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Auth');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);
$routes->match(['get', 'post'], 'api/(:any)', 'Api::proxy/$1');
// $routes->get('/', 'Auth::index');
// $routes->get('Dashboard', 'Dashboard::index');
// $routes->post('Auth/prosesLogin', 'Auth::prosesLogin');

