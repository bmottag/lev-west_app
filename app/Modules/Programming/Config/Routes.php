<?php
/**
 * @var \CodeIgniter\Router\RouteCollection $routes
 */

$routes->group('programming', ['namespace' => 'App\Modules\Programming\Controllers'], function ($routes) {
    $routes->get('index/(:num)',          'Programming::index/$1');
    $routes->get('index/(:num)/(:any)',   'Programming::index/$1/$2');

    $routes->get('add_programming/(:num)',        'Programming::add_programming/$1');
    $routes->get('add_programming/(:num)/(:any)', 'Programming::add_programming/$1/$2');
    $routes->post('save_programming',             'Programming::save_programming');

    $routes->get('add_programming_workers/(:num)', 'Programming::add_programming_workers/$1');
    $routes->post('save_programming_workers',      'Programming::save_programming_workers');

    $routes->post('delete_programming',      'Programming::delete_programming');
    $routes->post('update_worker',           'Programming::update_worker');
    $routes->post('generate_child_workers',  'Programming::generate_child_workers');
    $routes->get('deleteWorker/(:num)/(:num)', 'Programming::deleteWorker/$1/$2');

    $routes->get('send/(:num)', 'Programming::send/$1');

    $routes->post('save_One_Worker_programming', 'Programming::save_One_Worker_programming');

    $routes->get('flash_planning',      'Programming::flash_planning');
    $routes->post('save_flash_planning','Programming::save_flash_planning');

    $routes->post('receive_sms',              'Programming::receive_sms');
    $routes->post('clone_planning',           'Programming::clone_planning');
    $routes->post('automatic_planning_message','Programming::automatic_planning_message');

    $routes->post('loadModalMaterials', 'Programming::loadModalMaterials');
    $routes->post('save_material',      'Programming::save_material');
    $routes->get('deleteMaterial/(:num)/(:num)', 'Programming::deleteMaterial/$1/$2');
    $routes->post('updated_material',   'Programming::updated_material');

    $routes->post('cargarModalOcasional', 'Programming::cargarModalOcasional');
    $routes->post('save_ocasional',       'Programming::save_ocasional');

    $routes->post('save_hour', 'Programming::save_hour');

    $routes->get('deleteRecord/(:alpha)/(:num)/(:num)/(:any)', 'Programming::deleteRecord/$1/$2/$3/$4');

    $routes->get('verificacion/(:any)/(:any)', 'Programming::verificacion/$1/$2');
    $routes->get('verificacion/(:any)', 'Programming::verificacion/$1');
    $routes->get('verificacion', 'Programming::verificacion');

    $routes->get('verificacion_flha/(:any)/(:any)', 'Programming::verificacion_flha/$1/$2');
    $routes->get('verificacion_flha/(:any)', 'Programming::verificacion_flha/$1');
    $routes->get('verificacion_flha', 'Programming::verificacion_flha');

    $routes->get('verificacion_tool_box/(:any)/(:any)', 'Programming::verificacion_tool_box/$1/$2');
    $routes->get('verificacion_tool_box/(:any)', 'Programming::verificacion_tool_box/$1');
    $routes->get('verificacion_tool_box', 'Programming::verificacion_tool_box');

    $routes->get('automatic_planning_message', 'Programming::automatic_planning_message');

});
