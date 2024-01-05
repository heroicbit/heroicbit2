<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

service('auth')->routes($routes);
// $routes->setDefaultNamespace('App\Controllers');
// $routes->setDefaultController('App\Controllers\Page');
// $routes->setDefaultMethod('index');
// $routes->setTranslateURIDashes(false);
// $routes->set404Override('App\Controllers\Page::index');
// $routes->setAutoRoute(true);