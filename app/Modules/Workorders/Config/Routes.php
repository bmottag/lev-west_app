<?php
/**
 * @var \CodeIgniter\Router\RouteCollection $routes
 */

$routes->group('workorders', ['namespace' => 'App\Modules\Workorders\Controllers'], function ($routes) {
    $routes->get('/',                              'Workorders::index');
    $routes->get('add_workorder/(:any)',           'Workorders::add_workorder/$1');
    $routes->get('add_workorder',                  'Workorders::add_workorder');
    $routes->post('save_workorder',                'Workorders::save_workorder');
    $routes->post('update_workorder',              'Workorders::update_workorder');
    $routes->post('save/(:any)',                   'Workorders::save/$1');
    $routes->get('deleteRecord/(:any)/(:any)/(:any)/(:any)', 'Workorders::deleteRecord/$1/$2/$3/$4');
    $routes->post('cargarModalPersonal',           'Workorders::cargarModalPersonal');
    $routes->post('cargarModalMaterials',          'Workorders::cargarModalMaterials');
    $routes->post('cargarModalEquipment',          'Workorders::cargarModalEquipment');
    $routes->post('cargarModalOcasional',          'Workorders::cargarModalOcasional');
    $routes->post('cargarModalReceipts',           'Workorders::cargarModalReceipts');
    $routes->post('cargarModalExpense',            'Workorders::cargarModalExpense');
    $routes->post('truckList',                     'Workorders::truckList');
    $routes->post('companyList',                   'Workorders::companyList');
    $routes->post('attachmentList',                'Workorders::attachmentList');
    $routes->get('search/(:any)',                  'Workorders::search/$1');
    $routes->post('search',                        'Workorders::search');
    $routes->get('search',                         'Workorders::search');
    $routes->get('view_workorder/(:num)',           'Workorders::view_workorder/$1');
    $routes->post('save_rate',                     'Workorders::save_rate');
    $routes->post('save_hour',                     'Workorders::save_hour');
    $routes->get('email/(:num)',                   'Workorders::email/$1');
    $routes->post('save_signature',                 'Workorders::save_signature');
    $routes->post('save_workorder_and_send_email', 'Workorders::save_workorder_and_send_email');
    $routes->post('save_workorder_state',          'Workorders::save_workorder_state');
    $routes->get('wo_by_state/(:any)/(:any)',      'Workorders::wo_by_state/$1/$2');
    $routes->get('wo_by_state/(:any)',             'Workorders::wo_by_state/$1');
    $routes->get('generaWorkOrderPDF/(:num)',      'Workorders::generaWorkOrderPDF/$1');
    $routes->get('generaWorkOrderXLS/(:any)',      'Workorders::generaWorkOrderXLS/$1');
    $routes->get('search_income',                  'Workorders::search_income');
    $routes->post('search_income',                 'Workorders::search_income');
    $routes->get('foreman_view/(:num)',            'Workorders::foreman_view/$1');
    $routes->post('foremanInfo',                   'Workorders::foremanInfo');
    $routes->get('sendSMSForeman/(:num)',          'Workorders::sendSMSForeman/$1');
    $routes->post('load_prices_wo',               'Workorders::load_prices_wo');
    $routes->post('update_receipt',               'Workorders::update_receipt');
    $routes->post('load_markup_wo',               'Workorders::load_markup_wo');
    $routes->post('update_wo_state',              'Workorders::update_wo_state');
    $routes->post('recalculate_expenses',         'Workorders::recalculate_expenses');
    $routes->get('log/(:any)',                    'Workorders::log/$1');
    $routes->post('log',                          'Workorders::log');
    $routes->get('log',                           'Workorders::log');
    $routes->get('workorder_expenses/(:num)',     'Workorders::workorder_expenses/$1');
    $routes->post('save_wo_expenses',             'Workorders::save_wo_expenses');
    $routes->get('deleteRecordExpenses/(:any)/(:any)/(:any)/(:any)', 'Workorders::deleteRecordExpenses/$1/$2/$3/$4');
    $routes->get('subcontractor_invoice',         'Workorders::subcontractor_invoice');
    $routes->get('add_invoice/(:any)',            'Workorders::add_invoice/$1');
    $routes->get('add_invoice',                   'Workorders::add_invoice');
    $routes->post('save_subcontractor_invoice',   'Workorders::save_subcontractor_invoice');
    $routes->get('subcontractor_invoices/(:num)',            'Workorders::subcontractor_invoices/$1');
    $routes->post('save_subcontractor_invoices',   'Workorders::save_subcontractor_invoices');
});
