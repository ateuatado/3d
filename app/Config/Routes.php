<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');
$routes->get('catalogo', 'Catalog\CatalogController::index');
$routes->get('catalogo/preview/(:num)', 'Catalog\CatalogController::preview/$1');
$routes->get('catalogo/(:segment)', 'Catalog\CatalogController::show/$1');
$routes->group('admin', ['filter' => 'permission:admin.access'], static function ($routes): void {
    $routes->get('catalogo', 'Admin\CatalogAdminController::index');
    $routes->get('catalogo/novo', 'Admin\CatalogAdminController::new', ['filter' => 'permission:catalog.manage']);
    $routes->post('catalogo', 'Admin\CatalogAdminController::create', ['filter' => 'permission:catalog.manage']);
    $routes->get('catalogo/(:num)/editar', 'Admin\CatalogAdminController::edit/$1', ['filter' => 'permission:catalog.manage']);
    $routes->post('catalogo/(:num)', 'Admin\CatalogAdminController::update/$1', ['filter' => 'permission:catalog.manage']);
    $routes->post('catalogo/(:num)/arquivar', 'Admin\CatalogAdminController::archive/$1', ['filter' => 'permission:catalog.manage']);
    $routes->get('administradores', 'Admin\AdminUsersController::index', ['filter' => 'permission:users.manage-admins']);
    $routes->get('administradores/novo', 'Admin\AdminUsersController::new', ['filter' => 'permission:users.manage-admins']);
    $routes->post('administradores', 'Admin\AdminUsersController::create', ['filter' => 'permission:users.manage-admins']);
});
$routes->group('admin', ['filter' => 'permission:admin.access'], static function ($routes): void {
    $routes->get('catalogo', 'Admin\CatalogAdminController::index');
    $routes->get('catalogo/novo', 'Admin\CatalogAdminController::new', ['filter' => 'permission:catalog.manage']);
    $routes->post('catalogo', 'Admin\CatalogAdminController::create', ['filter' => 'permission:catalog.manage']);
});

service('auth')->routes($routes);
