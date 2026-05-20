<?php
/**
 * @var \CodeIgniter\Router\RouteCollection $routes
 */

$routes->group('dayoff', ['namespace' => 'App\Modules\Dayoff\Controllers'], function($routes){
    $routes->get('', 'Dayoff::index');
    $routes->get('newDayoffList', 'Dayoff::newDayoffList');
    $routes->get('approvedDayoffList', 'Dayoff::approvedDayoffList');
    $routes->get('deniedDayoffList', 'Dayoff::deniedDayoffList');

    $routes->post('cargar-modal', 'Dayoff::cargarModal');
    $routes->post('save_dayoff', 'Dayoff::save_dayoff');
    $routes->post('cargar-modal-approved', 'Dayoff::cargarModalApproved');
    $routes->post('save_approved', 'Dayoff::save_approved');
});
