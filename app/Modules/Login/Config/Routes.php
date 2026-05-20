<?php

/**
 * @var \CodeIgniter\Router\RouteCollection $routes
 */

$routes->group('login', ['namespace' => 'App\Modules\Login\Controllers'], function($routes){
    $routes->get('index/(:any)/(:any)', 'Login::index/$1/$2');
    $routes->get('', 'Login::index');
    $routes->post('validateUser', 'Login::validateUser');
    $routes->get('logout', 'Login::logout');
});