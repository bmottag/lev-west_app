<?php
/**
 * @var \CodeIgniter\Router\RouteCollection $routes
 */

$routes->group('report', ['namespace' => 'App\Modules\Report\Controllers'], function ($routes) {

    $routes->get('searchByDateRange/(:any)',  'Report::searchByDateRange/$1');
    $routes->post('searchByDateRange/(:any)', 'Report::searchByDateRange/$1');

    // PDFs - Safety
    $routes->get('generaSafetyPDF/(:any)/(:any)/(:any)',        'Report::generaSafetyPDF/$1/$2/$3');
    $routes->get('generaSafetyPDF/(:any)/(:any)/(:any)/(:any)', 'Report::generaSafetyPDF/$1/$2/$3/$4');

    // PDFs - Hauling
    $routes->get('generaHaulingPDF/(:any)/(:any)/(:any)/(:any)',        'Report::generaHaulingPDF/$1/$2/$3/$4');
    $routes->get('generaHaulingPDF/(:any)/(:any)/(:any)/(:any)/(:any)', 'Report::generaHaulingPDF/$1/$2/$3/$4/$5');

    // PDFs - Daily Inspection
    $routes->get('generaInsectionDailyPDF/(:any)/(:any)/(:any)/(:any)/(:any)',        'Report::generaInsectionDailyPDF/$1/$2/$3/$4/$5');
    $routes->get('generaInsectionDailyPDF/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)', 'Report::generaInsectionDailyPDF/$1/$2/$3/$4/$5/$6');

    // PDFs - Heavy Inspection
    $routes->get('generaInsectionHeavyPDF/(:any)/(:any)/(:any)/(:any)',        'Report::generaInsectionHeavyPDF/$1/$2/$3/$4');
    $routes->get('generaInsectionHeavyPDF/(:any)/(:any)/(:any)/(:any)/(:any)', 'Report::generaInsectionHeavyPDF/$1/$2/$3/$4/$5');

    // PDFs - Special Inspection
    $routes->get('generaInsectionSpecialPDF/(:any)/(:any)/(:any)/(:any)/(:any)',        'Report::generaInsectionSpecialPDF/$1/$2/$3/$4/$5');
    $routes->get('generaInsectionSpecialPDF/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)', 'Report::generaInsectionSpecialPDF/$1/$2/$3/$4/$5/$6');

    // PDFs - Payroll
    $routes->get('generaPayrollPDF/(:any)/(:any)/(:any)', 'Report::generaPayrollPDF/$1/$2/$3');

    // PDFs - Work Order
    $routes->get('generaWorkOrderPDF/(:num)', 'Report::generaWorkOrderPDF/$1');

    // PDFs - Check-In
    $routes->get('checkinPDF/(:any)', 'Report::checkinPDF/$1');

    // XLS - Payroll
    $routes->get('generaPayrollXLS/(:any)/(:any)/(:any)', 'Report::generaPayrollXLS/$1/$2/$3');

    // XLS - Hauling
    $routes->get('generaHaulingXLS/(:any)/(:any)/(:any)/(:any)', 'Report::generaHaulingXLS/$1/$2/$3/$4');

    // XLS - Work Order
    $routes->get('generaWorkOrderXLS/(:any)/(:any)/(:any)', 'Report::generaWorkOrderXLS/$1/$2/$3');

    // XLS - Valves
    $routes->get('valvesReport', 'Report::valvesReport');

    // Views with top-only layout
    $routes->get('botonEditHour/(:num)', 'Report::botonEditHour/$1');
    $routes->get('employeBankTime',      'Report::employeBankTime');
});
