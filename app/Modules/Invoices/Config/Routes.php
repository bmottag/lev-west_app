<?php

/**
 * @var \CodeIgniter\Router\RouteCollection $routes
 */

$routes->group('invoices', ['namespace' => 'App\Modules\Invoices\Controllers'], function ($routes) {
    $routes->get('/',                            'Invoices::index');
    $routes->post('/',                           'Invoices::index');
    $routes->get('add_invoice',                  'Invoices::add_invoice');
    $routes->get('add_invoice/(:segment)',       'Invoices::add_invoice/$1');
    $routes->post('save_invoice',                'Invoices::save_invoice');
    $routes->post('save_item',                   'Invoices::save_item');
    $routes->post('save_all',                    'Invoices::save_all');
    $routes->get('delete_item/(:num)/(:num)',    'Invoices::delete_item/$1/$2');
    $routes->get('delete_payment/(:num)/(:num)', 'Invoices::delete_payment/$1/$2');
    $routes->post('claim_list',                  'Invoices::claimList');
    $routes->post('wo_list',                     'Invoices::woList');
    $routes->post('cargar_modal_items',          'Invoices::cargarModalItems');
    $routes->get('genera_invoice_pdf/(:num)',    'Invoices::generaInvoicePDF/$1');
    $routes->post('upload_file/(:num)',          'Invoices::upload_file/$1');
    $routes->post('add_payment/(:num)',          'Invoices::add_payment/$1');
    $routes->get('send_invoice_email/(:num)',    'Invoices::sendInvoiceEmail/$1');
    $routes->post('woList',                      'Invoices::woList');
});
