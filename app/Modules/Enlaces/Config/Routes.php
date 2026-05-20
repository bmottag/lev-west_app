<?php
/**
 * @var \CodeIgniter\Router\RouteCollection $routes
 */

$routes->group('enlaces', ['namespace' => 'App\Modules\Enlaces\Controllers'], function($routes){
    $routes->get('menu', 'Enlaces::menu');
    $routes->get('links', 'Enlaces::links');
    $routes->get('role_access', 'Enlaces::role_access');
    $routes->get('videos', 'Enlaces::videos');
    $routes->get('manuals', 'Enlaces::manuals');

    $routes->get('manuals_form', 'Enlaces::manuals_form');
    $routes->get('manuals_form/(:any)', 'Enlaces::manuals_form/$1');
    $routes->get('manuals_form/(:any)/(:any)', 'Enlaces::manuals_form/$1/$2');

    $routes->post('cargar-modal-menu', 'Enlaces::cargarModalMenu');
    $routes->post('save_menu', 'Enlaces::save_menu');
    $routes->post('delete_menu', 'Enlaces::delete_menu');
    $routes->post('cargar-modal-link', 'Enlaces::cargarModalLink');
    $routes->post('save_link', 'Enlaces::save_link');
    $routes->post('delete_link', 'Enlaces::delete_link');
    $routes->post('cargar-modal-role-access', 'Enlaces::cargarModalRoleAccess');
    $routes->post('save_role_access', 'Enlaces::save_role_access');
    $routes->post('linkListInfo', 'Enlaces::linkListInfo');
    $routes->post('delete_role_access', 'Enlaces::delete_role_access');
    $routes->post('cargar-modal-video-links', 'Enlaces::cargarModalVideoLinks');
    $routes->post('save_video', 'Enlaces::save_video');
    $routes->post('do_upload_manual', 'Enlaces::do_upload_manual');

});
