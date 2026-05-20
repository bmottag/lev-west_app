<?php
/**
 * @var \CodeIgniter\Router\RouteCollection $routes
 */

$routes->group('template', ['namespace' => 'App\Modules\Template\Controllers'], function ($routes) {
    $routes->get('templates',                                            'Template::templates');
    $routes->post('cargarModalTemplate',                                 'Template::cargarModalTemplate');
    $routes->post('save_template',                                       'Template::save_template');
    $routes->get('use_template/(:segment)',                              'Template::use_template/$1');
    $routes->get('add_workers_template/(:num)',                          'Template::add_workers_template/$1');
    $routes->post('save_temlate_workers',                                'Template::save_temlate_workers');
    $routes->post('save_signature',                                      'Template::save_signature');
    $routes->get('deleteTemplateWorker/(:num)/(:num)',                   'Template::deleteTemplateWorker/$1/$2');
    $routes->post('save_one_worker',                                     'Template::save_one_worker');
    $routes->get('generaTemplatePDF/(:num)',                             'Template::generaTemplatePDF/$1');
    $routes->get('valves',                                               'Template::valves');
    $routes->post('cargarModalValve',                                    'Template::cargarModalValve');
    $routes->post('save_valve',                                          'Template::save_valve');
});
