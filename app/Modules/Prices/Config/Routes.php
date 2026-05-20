<?php
/**
 * @var \CodeIgniter\Router\RouteCollection $routes
 */

$routes->group('prices', ['namespace' => 'App\Modules\Prices\Controllers'], function($routes){
    $routes->get('employeeTypeUnitPrice/(:num)', 'Prices::employeeTypeUnitPrice/$1');
    $routes->get('equipmentList/(:num)', 'Prices::equipmentList/$1');
    $routes->get('equipmentUnitPrice/(:num)/(:num)/', 'Prices::equipmentUnitPrice/$1/$2');

    $routes->post('load_employee_type', 'Prices::load_employee_type');
    $routes->post('update_employee_type_price', 'Prices::update_employee_type_price');
    $routes->post('update_equipment_price', 'Prices::update_equipment_price');
    $routes->post('load_equipment', 'Prices::load_equipment');
    $routes->post('update_job_equipment_price', 'Prices::update_job_equipment_price');
    $routes->post('update_general_employee_type_price', 'Prices::update_general_employee_type_price');
    $routes->post('update_general_material_price', 'Prices::update_general_material_price');
});
