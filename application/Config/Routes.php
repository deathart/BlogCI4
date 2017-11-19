<?php namespace Config;

/**
 * --------------------------------------------------------------------
 * URI Routing
 * --------------------------------------------------------------------
 * This file lets you re-map URI requests to specific controller functions.
 *
 * Typically there is a one-to-one relationship between a URL string
 * and its corresponding controller class/method. The segments in a
 * URL normally follow this pattern:
 *
 *    example.com/class/method/id
 *
 * In some instances, however, you may want to remap this relationship
 * so that a different class/function is called than the one
 * corresponding to the URL.
 *
 */

// Create a new instance of our RouteCollection class.
$routes = Services::routes(true);

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(BASEPATH.'Config/Routes.php')) {
    require BASEPATH.'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 * The RouteCollection object allows you to modify the way that the
 * Router works, by acting as a holder for it's configuration settings.
 * The following methods can be called on the object to modify
 * the default operations.
 *
 *    $routes->defaultNamespace()
 *
 * Modifies the namespace that is added to a controller if it doesn't
 * already have one. By default this is the global namespace (\).
 *
 *    $routes->defaultController()
 *
 * Changes the name of the class used as a controller when the route
 * points to a folder instead of a class.
 *
 *    $routes->defaultMethod()
 *
 * Assigns the method inside the controller that is ran when the
 * Router is unable to determine the appropriate method to run.
 *
 *    $routes->setAutoRoute()
 *
 * Determines whether the Router will attempt to match URIs to
 * Controllers when no specific route has been defined. If false,
 * only routes that have been defined here will be available.
 */
$routes->setDefaultNamespace('App\Controllers\Blog');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override('App\Controllers\Blog\Errors::show_404');
$routes->setAutoRoute(false);
$routes->discoverLocal(false);

/**
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->add('/', 'Home::index', ['namespace' => 'App\Controllers\Blog']);
$routes->add('home', 'Home::index', ['namespace' => 'App\Controllers\Blog']);

/*
 * Route for catégorie
 */
$routes->add('cat', 'Cat::index', ['namespace' => 'App\Controllers\Blog']);
$routes->add('cat/(:any)', 'Cat::View/$1', ['namespace' => 'App\Controllers\Blog']);

/*
 * Route for Contact
 */
$routes->add('contact', 'Contact::index', ['namespace' => 'App\Controllers\Blog']);
$routes->add('contact/add', 'Contact::addContact', ['namespace' => 'App\Controllers\Blog\Ajax']);

/*
 * Route for about
 */
$routes->add('about', 'About::index', ['namespace' => 'App\Controllers\Blog']);

/*
 * Route for article
 */
$routes->add('article', 'Article::index', ['namespace' => 'App\Controllers\Blog']);
$routes->add('article/(:any)', 'Article::View/$1', ['namespace' => 'App\Controllers\Blog']);

/*
 * Route for comments
 */
$routes->add('comments/add', 'Comments::AddComments', ['namespace' => 'App\Controllers\Blog\Ajax']);
$routes->add('comments/checkcaptcha', 'Comments::checkcaptcha', ['namespace' => 'App\Controllers\Blog\Ajax']);

/*
 * Route for tags
 */
$routes->add('tags/(:any)', 'Tags::index/$1', ['namespace' => 'App\Controllers\Blog']);

/*
 * Route for search
 */
$routes->add('search', 'Search::index', ['namespace' => 'App\Controllers\Blog']);

/*
 * Route for cookies
 */
$routes->add('cookies', 'Cookies::index', ['namespace' => 'App\Controllers\Blog']);

/*
 * Route for Admin (group)
 */
$routes->group('admin', function ($routes) {
    $routes->add('/', 'Home::index', ['namespace' => 'App\Controllers\Admin']);
    $routes->add('home', 'Home::index', ['namespace' => 'App\Controllers\Admin']);

    $routes->group('auth', function ($routes) {
        $routes->add('login', 'Auth::Login', ['namespace' => 'App\Controllers\Admin']);
        $routes->add('logout', 'Auth::Logout', ['namespace' => 'App\Controllers\Admin']);
        $routes->add('login_ajax', 'Auth::login_ajax', ['namespace' => 'App\Controllers\Admin']);
    });

    $routes->group('article', function ($routes) {
        $routes->add('/', 'Article::index', ['namespace' => 'App\Controllers\Admin']);
        $routes->add('add', 'Article::add', ['namespace' => 'App\Controllers\Admin']);
        $routes->add('edit/(:num)/(:num)', 'Article::edit/$1/$2', ['namespace' => 'App\Controllers\Admin']);
        $routes->add('list/(:num)', 'Article::list/$1', ['namespace' => 'App\Controllers\Admin']);
    });

    $routes->group('cat', function ($routes) {
        $routes->add('/', 'Cat::index', ['namespace' => 'App\Controllers\Admin']);
    });

    $routes->group('comments', function ($routes) {
        $routes->add('/', 'Comments::index', ['namespace' => 'App\Controllers\Admin']);
        $routes->add('wait', 'Comments::wait', ['namespace' => 'App\Controllers\Admin']);
        $routes->add('ok', 'Comments::ok', ['namespace' => 'App\Controllers\Admin']);
        $routes->add('no', 'Comments::no', ['namespace' => 'App\Controllers\Admin']);
    });

    $routes->group('media', function ($routes) {
        $routes->add('/', 'Media::index', ['namespace' => 'App\Controllers\Admin']);
        $routes->add('add', 'Media::add', ['namespace' => 'App\Controllers\Admin']);
    });

    $routes->group('users', function ($routes) {
        $routes->add('/', 'Users::index', ['namespace' => 'App\Controllers\Admin']);
        $routes->add('liste', 'Users::liste', ['namespace' => 'App\Controllers\Admin']);
        $routes->add('add', 'Users::add', ['namespace' => 'App\Controllers\Admin']);
        $routes->add('log', 'Users::log', ['namespace' => 'App\Controllers\Admin']);
    });

    $routes->group('contact', function ($routes) {
        $routes->add('/', 'Contact::index', ['namespace' => 'App\Controllers\Admin']);
        $routes->add('new', 'Contact::new', ['namespace' => 'App\Controllers\Admin']);
        $routes->add('finish', 'Contact::finish', ['namespace' => 'App\Controllers\Admin']);
        $routes->add('rep/(:num)', 'Contact::rep/$1', ['namespace' => 'App\Controllers\Admin']);
    });

    $routes->group('config', function ($routes) {
        $routes->add('/', 'Config::index', ['namespace' => 'App\Controllers\Admin']);
        $routes->add('params', 'Config::params', ['namespace' => 'App\Controllers\Admin']);
        $routes->add('task', 'Config::task', ['namespace' => 'App\Controllers\Admin']);
        $routes->add('db', 'Config::db', ['namespace' => 'App\Controllers\Admin']);
        $routes->add('cache', 'Config::cache', ['namespace' => 'App\Controllers\Admin']);
        $routes->add('log', 'Config::log', ['namespace' => 'App\Controllers\Admin']);
    });

    $routes->group('ajax', function ($routes) {
        $routes->group('article', function ($routes) {
            $routes->add('add', 'Article::add', ['namespace' => 'App\Controllers\Admin\Ajax']);
            $routes->add('edit', 'Article::edit', ['namespace' => 'App\Controllers\Admin\Ajax']);
        });

        $routes->group('cat', function ($routes) {
            $routes->add('updatetitle', 'Cat::UpdateTitle', ['namespace' => 'App\Controllers\Admin\Ajax']);
            $routes->add('updatecontent', 'Cat::UpdateContent', ['namespace' => 'App\Controllers\Admin\Ajax']);
            $routes->add('add', 'Cat::Add', ['namespace' => 'App\Controllers\Admin\Ajax']);
        });

        $routes->group('comments', function ($routes) {
            $routes->add('valide', 'Comments::valide', ['namespace' => 'App\Controllers\Admin\Ajax']);
            $routes->add('refuse', 'Comments::refuse', ['namespace' => 'App\Controllers\Admin\Ajax']);
        });

        $routes->add('upload', 'Upload::index', ['namespace' => 'App\Controllers\Admin\Ajax']);

        $routes->group('contact', function ($routes) {
            $routes->add('rep', 'Contact::rep', ['namespace' => 'App\Controllers\Admin\Ajax']);
            $routes->add('markedview', 'Contact::markedview', ['namespace' => 'App\Controllers\Admin\Ajax']);
            $routes->add('del', 'Contact::del', ['namespace' => 'App\Controllers\Admin\Ajax']);
        });

        $routes->group('config', function ($routes) {
            $routes->add('updateparams', 'Config::UpdateParams', ['namespace' => 'App\Controllers\Admin\Ajax']);
            $routes->add('delparams', 'Config::DelParams', ['namespace' => 'App\Controllers\Admin\Ajax']);
        });
    });
});
