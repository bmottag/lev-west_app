<?php
/**
 * @var \CodeIgniter\Router\RouteCollection $routes
 */

$routes->group('forceaccount', ['namespace' => 'App\Modules\Forceaccount\Controllers'], function ($routes) {
    $routes->get('/',                                        'Forceaccount::index');
    $routes->get('add_forceaccount/(:any)',                  'Forceaccount::add_forceaccount/$1');
    $routes->get('add_forceaccount',                         'Forceaccount::add_forceaccount');
    $routes->post('save_forceaccount',                       'Forceaccount::save_forceaccount');
    $routes->post('update_forceaccount',                     'Forceaccount::update_forceaccount');
    $routes->post('cargarModalPersonal',                     'Forceaccount::cargarModalPersonal');
    $routes->post('save/(:segment)',                         'Forceaccount::save/$1');
    $routes->get('deleteRecord/(:segment)/(:num)/(:num)/(:segment)', 'Forceaccount::deleteRecord/$1/$2/$3/$4');
    $routes->post('cargarModalMaterials',                    'Forceaccount::cargarModalMaterials');
    $routes->post('cargarModalEquipment',                    'Forceaccount::cargarModalEquipment');
    $routes->post('truckList',                               'Forceaccount::truckList');
    $routes->post('companyList',                             'Forceaccount::companyList');
    $routes->post('cargarModalOcasional',                    'Forceaccount::cargarModalOcasional');
    $routes->match(['GET', 'POST'], 'search/(:any)',         'Forceaccount::search/$1');
    $routes->match(['GET', 'POST'], 'search',               'Forceaccount::search');
    $routes->get('view_forceaccount/(:num)',                  'Forceaccount::view_forceaccount/$1');
    $routes->post('save_rate',                               'Forceaccount::save_rate');
    $routes->post('save_hour',                               'Forceaccount::save_hour');
    $routes->get('email/(:num)',                             'Forceaccount::email/$1');
    $routes->post('save_signature',                 'Forceaccount::save_signature');
    $routes->post('save_forceaccount_and_send_email',        'Forceaccount::save_forceaccount_and_send_email');
    $routes->post('save_forceaccount_state',                 'Forceaccount::save_forceaccount_state');
    $routes->get('wo_by_state/(:num)/(:any)',                'Forceaccount::wo_by_state/$1/$2');
    $routes->get('wo_by_state/(:num)',                       'Forceaccount::wo_by_state/$1');
    $routes->get('generaForceAccountPDF/(:num)',             'Forceaccount::generaForceAccountPDF/$1');
    $routes->get('generaWorkOrderXLS/(:num)/(:any)/(:any)',  'Forceaccount::generaWorkOrderXLS/$1/$2/$3');
    $routes->get('generaWorkOrderXLS/(:num)',                'Forceaccount::generaWorkOrderXLS/$1');
    $routes->match(['GET', 'POST'], 'search_income',        'Forceaccount::search_income');
    $routes->get('foreman_view/(:num)',                      'Forceaccount::foreman_view/$1');
    $routes->post('foremanInfo',                             'Forceaccount::foremanInfo');
    $routes->get('sendSMSForeman/(:num)',                    'Forceaccount::sendSMSForeman/$1');
    $routes->post('load_prices_wo',                         'Forceaccount::load_prices_wo');
    $routes->post('cargarModalReceipts',                    'Forceaccount::cargarModalReceipts');
    $routes->post('update_receipt',                         'Forceaccount::update_receipt');
    $routes->post('load_markup_wo',                         'Forceaccount::load_markup_wo');
    $routes->post('update_wo_state',                        'Forceaccount::update_wo_state');
    $routes->post('cargarModalExpense',                     'Forceaccount::cargarModalExpense');
    $routes->post('recalculate_expenses',                   'Forceaccount::recalculate_expenses');
    $routes->post('attachmentList',                         'Forceaccount::attachmentList');
    $routes->match(['GET', 'POST'], 'log/(:any)',           'Forceaccount::log/$1');
    $routes->match(['GET', 'POST'], 'log',                  'Forceaccount::log');
    $routes->get('forceaccount_expenses/(:num)',            'Forceaccount::forceaccount_expenses/$1');
    $routes->post('save_fa_expenses',                       'Forceaccount::save_fa_expenses');
    $routes->get('deleteRecordExpenses/(:segment)/(:num)/(:num)/(:num)', 'Forceaccount::deleteRecordExpenses/$1/$2/$3/$4');
});
