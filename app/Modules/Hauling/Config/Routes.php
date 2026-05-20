<?php
/**
 * @var \CodeIgniter\Router\RouteCollection $routes
 */

$routes->group('hauling', ['namespace' => 'App\Modules\Hauling\Controllers'], function($routes){
    $routes->get('add_hauling/(:num)', 'Hauling::add_hauling/$1');
    $routes->get('add_hauling', 'Hauling::add_hauling');
    $routes->get('email/(:num)', 'Hauling::email/$1');

    $routes->post('save_hauling', 'Hauling::save_hauling');
    $routes->post('companyList', 'Hauling::companyList');
    $routes->post('truckList', 'Hauling::truckList');
    $routes->post('save_hauling', 'Hauling::save_hauling');
    $routes->post('list_by_job_code', 'Hauling::list_by_job_code');
    $routes->post('woList', 'Hauling::woList');
    $routes->post('update_hauling_state', 'Hauling::update_hauling_state');
    $routes->post('save_signature', 'Hauling::save_signature');
});
