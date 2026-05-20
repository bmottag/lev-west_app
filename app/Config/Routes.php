<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->setAutoRoute(false);
$routes->get('/', function() {
    return redirect()->to('/login');
});


// Cargar rutas del módulo Login
if (file_exists(APPPATH.'Modules/Login/Config/Routes.php')) {
    require APPPATH.'Modules/Login/Config/Routes.php';
}

// Cargar rutas del módulo Admin
if (file_exists(APPPATH.'Modules/Admin/Config/Routes.php')) {
    require APPPATH.'Modules/Admin/Config/Routes.php';
}

// Cargar rutas del módulo Dashboard
if (file_exists(APPPATH.'Modules/Dashboard/Config/Routes.php')) {
    require APPPATH.'Modules/Dashboard/Config/Routes.php';
}

// Cargar rutas del módulo Dayoff
if (file_exists(APPPATH.'Modules/Dayoff/Config/Routes.php')) {
    require APPPATH.'Modules/Dayoff/Config/Routes.php';
}

// Cargar rutas del módulo Enlaces
if (file_exists(APPPATH.'Modules/Enlaces/Config/Routes.php')) {
    require APPPATH.'Modules/Enlaces/Config/Routes.php';
}

// Cargar rutas del módulo Hauling
if (file_exists(APPPATH.'Modules/Hauling/Config/Routes.php')) {
    require APPPATH.'Modules/Hauling/Config/Routes.php';
}

// Cargar rutas del módulo Acs
if (file_exists(APPPATH.'Modules/Acs/Config/Routes.php')) {
    require APPPATH.'Modules/Acs/Config/Routes.php';
}

// Cargar rutas del módulo Employee
if (file_exists(APPPATH.'Modules/Employee/Config/Routes.php')) {
    require APPPATH.'Modules/Employee/Config/Routes.php';
}

// Cargar rutas del módulo External
if (file_exists(APPPATH.'Modules/External/Config/Routes.php')) {
    require APPPATH.'Modules/External/Config/Routes.php';
}

// Cargar rutas del módulo Claims
if (file_exists(APPPATH.'Modules/Claims/Config/Routes.php')) {
    require APPPATH.'Modules/Claims/Config/Routes.php';
}

// Cargar rutas del módulo Forceaccount
if (file_exists(APPPATH.'Modules/Forceaccount/Config/Routes.php')) {
    require APPPATH.'Modules/Forceaccount/Config/Routes.php';
}

// Cargar rutas del módulo Incidences
if (file_exists(APPPATH.'Modules/Incidences/Config/Routes.php')) {
    require APPPATH.'Modules/Incidences/Config/Routes.php';
}

// Cargar rutas del módulo Inspection
if (file_exists(APPPATH.'Modules/Inspection/Config/Routes.php')) {
    require APPPATH.'Modules/Inspection/Config/Routes.php';
}

// Cargar rutas del módulo Jobs
if (file_exists(APPPATH.'Modules/Jobs/Config/Routes.php')) {
    require APPPATH.'Modules/Jobs/Config/Routes.php';
}

// Cargar rutas del módulo Menu
if (file_exists(APPPATH.'Modules/Menu/Config/Routes.php')) {
    require APPPATH.'Modules/Menu/Config/Routes.php';
}

// Cargar rutas del módulo More
if (file_exists(APPPATH.'Modules/More/Config/Routes.php')) {
    require APPPATH.'Modules/More/Config/Routes.php';
}

// Cargar rutas del módulo Payroll
if (file_exists(APPPATH.'Modules/Payroll/Config/Routes.php')) {
    require APPPATH.'Modules/Payroll/Config/Routes.php';
}

// Cargar rutas del módulo Prices
if (file_exists(APPPATH.'Modules/Prices/Config/Routes.php')) {
    require APPPATH.'Modules/Prices/Config/Routes.php';
}

// Cargar rutas del módulo Programming
if (file_exists(APPPATH.'Modules/Programming/Config/Routes.php')) {
    require APPPATH.'Modules/Programming/Config/Routes.php';
}

// Cargar rutas del módulo Report
if (file_exists(APPPATH.'Modules/Report/Config/Routes.php')) {
    require APPPATH.'Modules/Report/Config/Routes.php';
}

// Cargar rutas del módulo Safety
if (file_exists(APPPATH.'Modules/Safety/Config/Routes.php')) {
    require APPPATH.'Modules/Safety/Config/Routes.php';
}

// Cargar rutas del módulo Serviceorder
if (file_exists(APPPATH.'Modules/Serviceorder/Config/Routes.php')) {
    require APPPATH.'Modules/Serviceorder/Config/Routes.php';
}

// Cargar rutas del módulo Trailers
if (file_exists(APPPATH.'Modules/Trailers/Config/Routes.php')) {
    require APPPATH.'Modules/Trailers/Config/Routes.php';
}

// Cargar rutas del módulo Template
if (file_exists(APPPATH.'Modules/Template/Config/Routes.php')) {
    require APPPATH.'Modules/Template/Config/Routes.php';
}

// Cargar rutas del módulo Workorders
if (file_exists(APPPATH.'Modules/Workorders/Config/Routes.php')) {
    require APPPATH.'Modules/Workorders/Config/Routes.php';
}

// Cargar rutas del módulo Invoices
if (file_exists(APPPATH.'Modules/Invoices/Config/Routes.php')) {
    require APPPATH.'Modules/Invoices/Config/Routes.php';
}