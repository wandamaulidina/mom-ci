<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Backend');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Backend::index');
$routes->get('/dashboard', 'Backend::index');
$routes->get('/home', 'Backend::home');
$routes->get('/company', 'Backend::company');
$routes->get('/division', 'Backend::division');
$routes->get('/position', 'Backend::position');
//COMPANY
$routes->post('/master/company/submit', 'Backend::submit_company');
$routes->post('/master/company/edit', 'Backend::edit_company');
$routes->post('/master/company/list', 'Backend::list_company');
$routes->post('/master/company/delete', 'Backend::delete_company');
$routes->post('/master/company/get', 'Backend::get_company');
//DIVISION
$routes->post('/master/division/submit', 'Backend::submit_division');
$routes->post('/master/division/edit', 'Backend::edit_division');
$routes->post('/master/division/list', 'Backend::list_division');
$routes->post('/master/division/delete', 'Backend::delete_division');
$routes->post('/master/division/get', 'Backend::get_division');
$routes->get('/master/division/data', 'Backend::data_division');
//POSITION
$routes->get('/master/position/data', 'Backend::data_division');
$routes->post('/master/division/submit', 'Backend::submit_division');
$routes->post('/master/division/edit', 'Backend::edit_division');
$routes->post('/master/division/list', 'Backend::list_division');
$routes->post('/master/division/delete', 'Backend::delete_division');
$routes->post('/master/division/get', 'Backend::get_division');
$routes->get('/master/division/data', 'Backend::data_division');
/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
