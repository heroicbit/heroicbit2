<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// $routes->get('/', 'Home::index');

service('auth')->routes($routes);

// Override all non match uri to Heroic Page
$routes->match(['get', 'post'], '(:any)', '\Heroic\Controllers\Page::index');