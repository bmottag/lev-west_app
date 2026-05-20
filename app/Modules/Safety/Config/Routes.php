<?php
/**
 * @var \CodeIgniter\Router\RouteCollection $routes
 */

$routes->group('safety', ['namespace' => 'App\Modules\Safety\Controllers'], function($routes){
    $routes->get('add_safety/(:num)/(:any)', 'Safety::add_safety/$1/$2');
    $routes->get('add_safety/(:num)', 'Safety::add_safety/$1');
    $routes->get('upload_info_safety/(:num)', 'Safety::upload_info_safety/$1');
    $routes->get('add_hazards_flha/(:num)/(:num)', 'Safety::add_hazards_flha/$1/$2');
    $routes->get('upload_workers/(:num)', 'Safety::upload_workers/$1');
    $routes->get('add_workers/(:num)', 'Safety::add_workers/$1');
    $routes->get('deleteSafetyWorker/(:num)/(:num)', 'Safety::deleteSafetyWorker/$1/$2');
    $routes->get('deleteSafetySubcontractor/(:num)/(:num)', 'Safety::deleteSafetySubcontractor/$1/$2');
    $routes->get('review_flha/(:num)', 'Safety::review_flha/$1');

    $routes->post('save_safety', 'Safety::save_safety');
    $routes->post('save_safety_hazards', 'Safety::save_safety_hazards');
    $routes->post('save_safety_workers', 'Safety::save_safety_workers');
    $routes->post('safet_One_Worker', 'Safety::safet_One_Worker');
    $routes->post('safet_subcontractor_Worker', 'Safety::safet_subcontractor_Worker');
    $routes->post('cargar-modal-employee-verification', 'Safety::cargarModalEmployeeVerification');
    $routes->post('save_signature_credentials', 'Safety::save_signature_credentials');
    $routes->post('save_worker_undestanding', 'Safety::save_worker_undestanding');
    $routes->post('save_signature', 'Safety::save_signature');
});
