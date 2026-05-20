<?php
/**
 * @var \CodeIgniter\Router\RouteCollection $routes
 */

$routes->group('serviceorder', ['namespace' => 'App\Modules\Serviceorder\Controllers'], function ($routes) {
    $routes->get('index/(:any)/(:any)/(:any)',         'Serviceorder::index/$1/$2/$3');
    $routes->get('index/(:any)/(:any)',         'Serviceorder::index/$1/$2');
    $routes->get('index/(:any)',                'Serviceorder::index/$1');
    $routes->get('/',                          'Serviceorder::index');
    $routes->get('parts_by_store',             'Serviceorder::parts_by_store');
    $routes->get('generateSOReportPDF/(:num)', 'Serviceorder::generateSOReportPDF/$1');
    $routes->get('maintenance_check',          'Serviceorder::maintenance_check');

    $routes->post('cargarModalServiceOrder',            'Serviceorder::cargarModalServiceOrder');
    $routes->post('cargarModalPreventiveMaintenance',   'Serviceorder::cargarModalPreventiveMaintenance');
    $routes->post('cargarModalCorrectiveMaintenance',   'Serviceorder::cargarModalCorrectiveMaintenance');
    $routes->post('cargarModalParts',                   'Serviceorder::cargarModalParts');
    $routes->post('cargarModalShopParts',               'Serviceorder::cargarModalShopParts');
    $routes->post('save_service_order',                 'Serviceorder::save_service_order');
    $routes->post('save_preventive_maintenance',        'Serviceorder::save_preventive_maintenance');
    $routes->post('save_corrective_maintenance',        'Serviceorder::save_corrective_maintenance');
    $routes->post('save_chat',                          'Serviceorder::save_chat');
    $routes->post('save_parts',                         'Serviceorder::save_parts');
    $routes->post('save_shop_parts',                    'Serviceorder::save_shop_parts');
    $routes->post('equipmentList',                      'Serviceorder::equipmentList');
    $routes->post('serviceOrderList',                   'Serviceorder::serviceOrderList');
    $routes->post('equipmentDetail',                    'Serviceorder::equipmentDetail');
    $routes->post('expenses',                           'Serviceorder::expenses');
    $routes->post('expensesByEquipment',                'Serviceorder::expensesByEquipment');
    $routes->post('equipmentListForPartsShop',          'Serviceorder::equipmentListForPartsShop');
});
