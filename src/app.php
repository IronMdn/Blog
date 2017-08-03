<?php

use Controller\Admin\ArticleController;
use Controller\Admin\CategoryController as AdminCategoryController;
use Controller\CategoryController;
use Controller\IndexController;
use Controller\UserController;
use Repository\ArticleRepository;
use Repository\CategoryRepository;
use Repository\UserRepository;
use Service\UserManager;
use Silex\Application;
use Silex\Provider\AssetServiceProvider;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\HttpFragmentServiceProvider;

$app = new Application();
$app->register(new ServiceControllerServiceProvider());
$app->register(new AssetServiceProvider());
$app->register(new TwigServiceProvider());
$app->register(new HttpFragmentServiceProvider());
$app['twig'] = $app->extend('twig', function ($twig, $app) {
    // pour accéder au UserManager dans les templates twig
    $twig->addGlobal('user_manager', $app['user.manager']);

    return $twig;
});

// Ajout de Doctrine DBAL
// nécessite l'import par composer avec la commande :
// composer require "doctrine/dbal:~2.2"
$app->register(
    new DoctrineServiceProvider(),
    [
        'db.options' => [
            'driver' => 'pdo_mysql',
            'host' => 'localhost',
            'dbname' => 'silex_blog',
            'user' => 'root',
            'password' => '',
            'charset' => 'utf8'
        ]
    ]
);

// pour pouvoir utiliser le gestionnaire de session de Symfony
// rien à ajouter par composer
$app->register(new SessionServiceProvider());

$app['user.manager'] = function () use ($app) {
    return new UserManager($app['session']);
};

/* Déclaration des contrôleurs en service */

$app['index.controller'] = function () use ($app) {
    return new IndexController($app);
};

$app['user.controller'] = function () use ($app) {
    return new UserController($app);
};

$app['category.controller'] = function () use ($app) {
    return new CategoryController($app);
};

$app['admin.category.controller'] = function () use ($app) {
    return new AdminCategoryController($app);
};

$app['admin.article.controller'] = function () use ($app) {
    return new ArticleController($app);
};

/* Déclaration des repositories en service */

$app['category.repository'] = function () use ($app) {
    return new CategoryRepository($app['db']);
};

$app['article.repository'] = function () use ($app) {
    return new ArticleRepository($app['db']);
};

$app['user.repository'] = function () use ($app) {
    return new UserRepository($app['db']);
};

return $app;
