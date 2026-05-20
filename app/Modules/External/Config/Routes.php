<?php
/**
 * @var \CodeIgniter\Router\RouteCollection $routes
 */

$routes->group('external', ['namespace' => 'App\Modules\External\Controllers'], function ($routes) {


    // SMS (estructura mantenida, envío comentado)
    $routes->get('send_sms_worker/(:num)/(:num)',           'External::sendSMSWorker/$1/$2');
    $routes->get('send_sms_flha/(:num)/(:any)',             'External::sendSMSFLHAWorker/$1/$2');
    $routes->get('send_sms_flha/(:num)',                    'External::sendSMSFLHAWorker/$1');
    $routes->get('send_sms_excavation/(:num)/(:num)',       'External::sendSMSExcavationWorker/$1/$2');
    $routes->get('send_sms_excavation/(:num)',              'External::sendSMSExcavationWorker/$1');

    // Employee
    $routes->get('new_employee/(:any)',  'External::newEmployee/$1');
    $routes->post('save_employee',       'External::saveEmployee');

    // Checkin / Checkout
    $routes->get('checkin/(:num)',           'External::checkin/$1');
    $routes->get('checkin/(:num)/(:any)',    'External::checkin/$1/$2');
    $routes->post('save_checkin',            'External::saveCheckin');
    $routes->post('cargar_modal_checkout',   'External::cargarModalCheckout');
    $routes->post('save_checkout',           'External::saveCheckout');

    // Day Off
    $routes->get('aprove_day_off/(:num)/(:num)', 'External::aproveDayOff/$1/$2');
    $routes->post('update_dayoff_status',        'External::updateDayoffStatus');
});
