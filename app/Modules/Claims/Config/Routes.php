<?php

$routes->group('claims', ['namespace' => 'App\Modules\Claims\Controllers'], function ($routes) {
    $routes->get('(:num)',                  'Claims::index/$1');
    $routes->post('cargarModalClaim',       'Claims::cargarModalClaim');
    $routes->post('guardar_claims',         'Claims::guardar_claims');
    $routes->get('upload_apu/(:num)',       'Claims::upload_apu/$1');
    $routes->post('update_claim',           'Claims::update_claim');
    $routes->get('add_apu/(:num)/(:num)',   'Claims::add_apu/$1/$2');
    $routes->post('save_claim_apu',         'Claims::save_claim_apu');
    $routes->post('delete_wo_from_claim',   'Claims::delete_wo_from_claim');
    $routes->post('cargarModalClaimState',  'Claims::cargarModalClaimState');
    $routes->post('save_claim_state',       'Claims::save_claim_state');
    $routes->post('nextClaimNumber',        'Claims::nextClaimNumber');
    $routes->get('claim_history/(:num)',    'Claims::claim_history/$1');
    $routes->get('generaProgressreportXLS/(:num)',   'Claims::generaProgressreportXLS/$1');
});
