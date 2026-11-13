<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');
$routes->get('catalogo', 'Catalog\CatalogController::index');
$routes->get('catalogo/preview/(:num)', 'Catalog\CatalogController::preview/$1');
$routes->get('catalogo/(:segment)', 'Catalog\CatalogController::show/$1');

service('auth')->routes($routes);
