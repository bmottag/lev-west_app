<?php
/**
 * @var \CodeIgniter\Router\RouteCollection $routes
 */

$routes->group('incidences', ['namespace' => 'App\Modules\Incidences\Controllers'], function($routes){
    $routes->get('near_miss/(:num)', 'Incidences::near_miss/$1');
    $routes->get('add_near_miss/(:num)/(:any)', 'Incidences::add_near_miss/$1/$2');
    $routes->get('add_near_miss/(:num)', 'Incidences::add_near_miss/$1');
    $routes->get('incident/(:num)', 'Incidences::incident/$1');
    $routes->get('add_incident/(:num)/(:any)', 'Incidences::add_incident/$1/$2');
    $routes->get('add_incident/(:num)', 'Incidences::add_incident/$1');
    $routes->get('generaPDF/(:num)/(:num)', 'Incidences::generaPDF/$1/$2');
    $routes->get('sendSMSIncidencesPersons/(:num)/(:num)', 'Incidences::sendSMSIncidencesPersons/$1/$2');
    $routes->get('review_incident/(:num)', 'Incidences::review_incident/$1');

    $routes->get(
        'delete-incident-person/(:num)/(:num)/(:num)/(:num)', 
        'Incidences::deleteIncidentPersonInvolved/$1/$2/$3/$4'
    );

    $routes->post('save_near_miss', 'Incidences::save_near_miss');
    $routes->post('save_person_involved', 'Incidences::save_person_involved');
    $routes->post('save_signature_person_involved', 'Incidences::save_signature_person_involved');
    $routes->post('save_signature', 'Incidences::save_signature');
    $routes->post('save_incident', 'Incidences::save_incident');
});
