<?php
/**
 * @var \CodeIgniter\Router\RouteCollection $routes
 */

$routes->group('employee', ['namespace' => 'App\Modules\Employee\Controllers'], function($routes){
    $routes->get('profile', 'Employee::profile');

    $routes->post('do_upload', 'Employee::do_upload');
    $routes->post('save_signature', 'Employee::save_signature');
});
