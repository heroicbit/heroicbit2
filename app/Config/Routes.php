<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// $routes->get('/', 'Home::index');

// Override all non match uri to Heroic Page
$routes->match(['get', 'post'], '(:any)', '\Heroic\Controllers\Page::index');