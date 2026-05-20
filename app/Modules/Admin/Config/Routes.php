<?php
/**
 * @var \CodeIgniter\Router\RouteCollection $routes
 */

$routes->group('admin', ['namespace' => 'App\Modules\Admin\Controllers'], function($routes){
    $routes->get('employee/(:any)', 'Admin::employee/$1');
    $routes->get('userCertificates/(:any)', 'Admin::userCertificates/$1');
    $routes->get('change_password/(:any)', 'Admin::change_password/$1');
    $routes->get('material', 'Admin::material');
    $routes->get('company', 'Admin::company');
    $routes->get('hazard', 'Admin::hazard');
    $routes->get('job/(:any)', 'Admin::job/$1');
    $routes->get('vehicle/(:any)', 'Admin::vehicle/$1');
    $routes->get('photo/(:any)', 'Admin::photo/$1');
    $routes->get('employeeType', 'Admin::employeeType');
    $routes->get('hazardActivity', 'Admin::hazardActivity');
    $routes->get('notifications', 'Admin::notifications');
    $routes->get('attachments/(:any)', 'Admin::attachments/$1');
    $routes->get('tags', 'Admin::tags');
    $routes->get('employeeSettings', 'Admin::employeeSettings');
    $routes->get('certifications_check', 'Admin::certifications_check');

    $routes->post('cargar-modal-employee', 'Admin::cargarModalEmployee');
    $routes->post('save_employee', 'Admin::save_employee');
    $routes->post('cargar-modal-user-certificate', 'Admin::cargarModalUserCertificate');
    $routes->post('save_employee_certificate', 'Admin::save_employee_certificate');
    $routes->post('update_user_certificate', 'Admin::update_user_certificate');
    $routes->post('delete_user_certificate', 'Admin::delete_user_certificate');
    $routes->post('update_password', 'Admin::update_password');
    $routes->post('cargar-modal-certificate', 'Admin::cargarModalCertificate');
    $routes->post('save_certificate', 'Admin::save_certificate');
    $routes->post('cargar-modal-material', 'Admin::cargarModalMaterial');
    $routes->post('save_material', 'Admin::save_material');
    $routes->post('cargar-modal-company', 'Admin::cargarModalCompany');
    $routes->post('save_company', 'Admin::save_company');
    $routes->post('cargar-modal-hazard', 'Admin::cargarModalHazard');
    $routes->post('save_hazard', 'Admin::save_hazard');
    $routes->post('cargar-modal-job', 'Admin::cargarModalJob');
    $routes->post('save_job', 'Admin::save_job');
    $routes->post('cargar-modal-vehicle', 'Admin::cargarModalVehicle');
    $routes->post('save_vehicle', 'Admin::save_vehicle');
    $routes->post('do_upload/(:any)/(:num)/', 'Admin::do_upload/$1/$2');
    $routes->post('cargar-modal-employee-type', 'Admin::cargarModalEmployeeType');
    $routes->post('save_employee_type', 'Admin::save_employee_type');
    $routes->post('cargar-modal-hazard-activity', 'Admin::cargarModalHazardActivity');
    $routes->post('save_hazard_activity', 'Admin::save_hazard_activity');
    $routes->post('cargar-modal-notification', 'Admin::cargarModalNotification');
    $routes->post('save_notifications', 'Admin::save_notifications');
    $routes->post('cargar-modal-attachments', 'Admin::cargarModalAttachments');
    $routes->post('save_attachments', 'Admin::save_attachments');
    $routes->post('equipmentList', 'Admin::equipmentList');
    $routes->post('update_status', 'Admin::update_status');
    $routes->post('cargarModalTag', 'Admin::cargarModalTag');
    $routes->post('save_tag', 'Admin::save_tag');
    $routes->post('update_employee_rate', 'Admin::update_employee_rate');

    $routes->match(['GET', 'POST'], 'certificate', 'Admin::certificate');
});
