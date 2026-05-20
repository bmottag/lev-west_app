<?php
/**
 * @var \CodeIgniter\Router\RouteCollection $routes
 */

$routes->group('inspection', ['namespace' => 'App\Modules\Inspection\Controllers'], function ($routes) {
    $routes->get('/',                                    'Inspection::index');
    $routes->get('search_vehicle',                       'Inspection::search_vehicle');
    $routes->post('vehicleInfo',                         'Inspection::vehicleInfo');
    $routes->get('set_vehicle/(:num)',                   'Inspection::set_vehicle/$1');

    $routes->get('add_daily_inspection/(:any)',          'Inspection::add_daily_inspection/$1');
    $routes->get('add_daily_inspection',                 'Inspection::add_daily_inspection');
    $routes->post('save_daily_inspection',               'Inspection::save_daily_inspection');

    $routes->get('add_heavy_inspection/(:any)',          'Inspection::add_heavy_inspection/$1');
    $routes->get('add_heavy_inspection',                 'Inspection::add_heavy_inspection');
    $routes->post('save_heavy_inspection',               'Inspection::save_heavy_inspection');

    $routes->get('add_generator_inspection/(:any)',      'Inspection::add_generator_inspection/$1');
    $routes->get('add_generator_inspection',             'Inspection::add_generator_inspection');
    $routes->post('save_generator_inspection',           'Inspection::save_generator_inspection');

    $routes->get('add_sweeper_inspection/(:any)',        'Inspection::add_sweeper_inspection/$1');
    $routes->get('add_sweeper_inspection',               'Inspection::add_sweeper_inspection');
    $routes->post('save_sweeper_inspection',             'Inspection::save_sweeper_inspection');

    $routes->get('add_hydrovac_inspection/(:any)',       'Inspection::add_hydrovac_inspection/$1');
    $routes->get('add_hydrovac_inspection',              'Inspection::add_hydrovac_inspection');
    $routes->post('save_hydrovac_inspection',            'Inspection::save_hydrovac_inspection');

    $routes->get('add_watertruck_inspection/(:any)',     'Inspection::add_watertruck_inspection/$1');
    $routes->get('add_watertruck_inspection',            'Inspection::add_watertruck_inspection');
    $routes->post('save_watertruck_inspection',          'Inspection::save_watertruck_inspection');

});
