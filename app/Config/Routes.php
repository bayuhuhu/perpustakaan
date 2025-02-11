<?php

namespace Config;

use CodeIgniter\Router\RouteCollection;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
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
$routes->get('/', 'Home::book');
$routes->get('books/(:any)', 'Home::detail/$1');
$routes->get('absensi_member', 'Home::absensiMember');
$routes->post('absensi_member/create', 'Home::absensiMemberCreate');
$routes->get('register-member/(:any)/print', 'Home::print/$1');
$routes->resource('register-member', ['controller' => 'Home']);
$routes->get('loans/member/search', 'MembersLoansController::loans');
$routes->get('loans/books/search', 'MembersLoansController::searchBook');
$routes->get('/loans/result', 'MembersLoansController::result');
$routes->post('loans/books/new', 'MembersLoansController::new');
$routes->resource('loans', ['controller' => 'MembersLoansController']);

$routes->get('returns/new/search', 'MembersReturnsController::searchLoan');
$routes->resource('returns', ['controller' => 'MembersReturnsController']);
// $routes->get('/book', 'Home::book');



service('auth')->routes($routes);

$routes->group('admin', ['filter' => 'session'], static function (RouteCollection $routes) {
    $routes->get('/', 'Dashboard\DashboardController');
    $routes->get('dashboard', 'Dashboard\DashboardController::dashboard');
    $routes->get('laporan_peminjaman', 'Dashboard\DashboardController::laporan_peminjaman');
    $routes->get('cetak_laporan_peminjaman', 'Dashboard\DashboardController::cetak_laporan_peminjaman');
    $routes->get('absensi_member', 'Dashboard\DashboardController::absensi_member');
    $routes->get('members/(:any)/print', 'Members\MembersController::print/$1');
    $routes->resource('members', ['controller' => 'Members\MembersController']);
    $routes->resource('books', ['controller' => 'Books\BooksController']);
    $routes->resource('categories', ['controller' => 'Books\CategoriesController']);
    $routes->resource('racks', ['controller' => 'Books\RacksController']);

    $routes->get('loans/new/members/search', 'Loans\LoansController::searchMember');
    $routes->get('loans/new/books/search', 'Loans\LoansController::searchBook');
    $routes->post('loans/new', 'Loans\LoansController::new');
    $routes->resource('loans', ['controller' => 'Loans\LoansController']);

    $routes->get('returns/new/search', 'Loans\ReturnsController::searchLoan');
    $routes->resource('returns', ['controller' => 'Loans\ReturnsController']);

    $routes->get('fines/returns/search', 'Loans\FinesController::searchReturn');
    $routes->get('fines/pay/(:any)', 'Loans\FinesController::pay/$1');
    $routes->resource('fines', ['controller' => 'Loans\FinesController']);
   

    $routes->group('users', ['filter' => 'group:superadmin'], static function (RouteCollection $routes) {
        $routes->get('new', 'Users\RegisterController::index');
        $routes->post('', 'Users\RegisterController::registerAction');
    });
    $routes->resource('users', ['controller' => 'Users\UsersController', 'filter' => 'group:superadmin']);
});




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
