<?php

/**
 * @var \CodeIgniter\Router\RouteCollection $routes
 */

$routes->group('acs', ['namespace' => 'App\Modules\Acs\Controllers'], function ($routes) {
    $routes->get('view_acs/(:num)',              'Acs::view_acs/$1');
    $routes->get('income',                       'Acs::income');
    $routes->get('reportPDF/(:num)',             'Acs::reportPDF/$1');
    $routes->get('generaACSXLS/(:num)',          'Acs::generaACSXLS/$1');
    $routes->get('deleteACSRecord/(:any)/(:num)/(:num)/(:any)', 'Acs::deleteACSRecord/$1/$2/$3/$4');

    $routes->post('save_info_acs_personal',      'Acs::save_info_acs_personal');
    $routes->post('save_info_acs_materials',     'Acs::save_info_acs_materials');
    $routes->post('save_info_acs_receipt',       'Acs::save_info_acs_receipt');
    $routes->post('save_info_acs_equipment',     'Acs::save_info_acs_equipment');
    $routes->post('save_info_acs_ocasional',     'Acs::save_info_acs_ocasional');
    $routes->post('save/(:segment)',             'Acs::save/$1');

    $routes->post('cargarModalPersonalACS',      'Acs::cargarModalPersonalACS');
    $routes->post('cargarModalMaterialsACS',     'Acs::cargarModalMaterialsACS');
    $routes->post('cargarModalEquipmentACS',     'Acs::cargarModalEquipmentACS');
    $routes->post('cargarModalOcasionalACS',     'Acs::cargarModalOcasionalACS');
    $routes->post('cargarModalReceiptsACS',      'Acs::cargarModalReceiptsACS');
});
