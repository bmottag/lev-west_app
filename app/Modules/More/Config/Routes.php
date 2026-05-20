<?php
/**
 * @var \CodeIgniter\Router\RouteCollection $routes
 */

$routes->group('more', ['namespace' => 'App\Modules\More\Controllers'], function ($routes) {
    // Environmental
    $routes->get('environmental/(:num)',       'More::environmental/$1');
    $routes->get('add_environmental/(:num)',   'More::add_environmental/$1');
    $routes->get('add_environmental/(:num)/(:any)', 'More::add_environmental/$1/$2');
    $routes->post('save_environmental',        'More::save_environmental');
    $routes->post('add_signature_esi', 'More::add_signature_esi');
    $routes->get('generaEnvironmentalPDF/(:num)', 'More::generaEnvironmentalPDF/$1');

    // Confined Space
    $routes->get('confined/(:num)',            'More::confined/$1');
    $routes->get('add_confined/(:num)',        'More::add_confined/$1');
    $routes->get('add_confined/(:num)/(:any)', 'More::add_confined/$1/$2');
    $routes->post('save_confined',             'More::save_confined');
    $routes->get('confined_workers/(:num)/(:num)',  'More::confined_workers/$1/$2');
    $routes->get('workers_site/(:num)/(:num)', 'More::workers_site/$1/$2');
    $routes->get('add_workers_confined/(:num)/(:num)/(:num)', 'More::add_workers_confined/$1/$2/$3');
    $routes->post('save_confined_workers',     'More::save_confined_workers');
    $routes->get('deleteConfinedWorker/(:num)/(:num)/(:num)', 'More::deleteConfinedWorker/$1/$2/$3');
    $routes->get('deleteConfinedWorkerSite/(:num)/(:num)/(:num)', 'More::deleteConfinedWorkerSite/$1/$2/$3');
    $routes->post('confined_One_Worker',       'More::confined_One_Worker');
    $routes->post('confined_worker_site',      'More::confined_worker_site');
    $routes->post('save_signature_confined',       'More::save_signature_confined');
    $routes->post('update_confined_worker',    'More::update_confined_worker');
    $routes->get('re_testing/(:num)/(:num)',   'More::re_testing/$1/$2');
    $routes->post('cargarModalRetesting',      'More::cargarModalRetesting');
    $routes->post('save_re_testing',           'More::save_re_testing');
    $routes->get('post_entry/(:num)/(:num)',   'More::post_entry/$1/$2');
    $routes->post('save_post_entry',           'More::save_post_entry');
    $routes->get('rescue_plan/(:num)/(:any)',  'More::rescue_plan/$1/$2');
    $routes->post('save_rescue_plan',          'More::save_rescue_plan');
    $routes->get('generaConfinedPDF/(:num)',   'More::generaConfinedPDF/$1');

    // PPE Inspection
    $routes->get('ppe_inspection',             'More::ppe_inspection');
    $routes->post('cargarModalPPEInspection',  'More::cargarModalPPEInspection');
    $routes->post('save_ppe_inspection',       'More::save_ppe_inspection');
    $routes->get('add_ppe_inspection',         'More::add_ppe_inspection');
    $routes->get('add_ppe_inspection/(:any)',  'More::add_ppe_inspection/$1');
    $routes->post('save_signature_ppe',       'More::save_signature_ppe');
    $routes->get('add_workers_ppe_inspection/(:num)', 'More::add_workers_ppe_inspection/$1');
    $routes->post('save_ppe_inspection_workers', 'More::save_ppe_inspection_workers');
    $routes->post('deleteInspectionWorker',    'More::deleteInspectionWorker');
    $routes->post('updateInspection',          'More::updateInspection');
    $routes->post('add_one_worker',            'More::add_one_worker');
    $routes->get('generaPPEInspectionPDF/(:num)', 'More::generaPPEInspectionPDF/$1');

});
