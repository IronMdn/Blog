<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

//Request::setTrustedProxies(array('127.0.0.1'));

// l'url racine (/) appelle la méthode indexAction()
// de la classe Controller\IndexController
// Le contrôleur doit être déclaré en service dans app.php

/* FRONT */

$app
    ->get('/', 'index.controller:indexAction')
    ->bind('homepage') // nom de la route
;

$app
    ->get('/rubriques/menu', 'category.controller:menuAction')
    ->bind('category_menu')
;

/* Utilisateur */

$app
    ->match('/inscription', 'user.controller:registerAction')
    ->bind('register')
;

$app
    ->match('/connexion', 'user.controller:loginAction')
    ->bind('login')
;

$app
    ->get('/deconnexion', 'user.controller:logoutAction')
    ->bind('logout')
;

/* ADMIN */

// crée un groupe de routes pour la partie admin
$admin = $app['controllers_factory'];

// protection de l'accès au backoffice
$admin->before(function () use ($app) {
    if (!$app['user.manager']->isAdmin()) {
        $app->abort(403, 'Accès refusé');
    }
});

// toutes les routes définies dans le groupe $admin
// auront le préfixe /admin
$app->mount('/admin', $admin);

// le chemin de la route est /admin/rubriques
$admin
    ->get('/rubriques', 'admin.category.controller:listAction')
    ->bind('admin_categories')
;

// la route contient un formulaire en POST
// elle est définie en match() pour accepter GET et POST
$admin
    ->match('/rubrique/edition/{id}', 'admin.category.controller:editAction')
    ->value('id', null) // id est optionnel et vaut null par défaut
    ->bind('admin_category_edit')
;

$admin
    ->get('/rubrique/suppression/{id}', 'admin.category.controller:deleteAction')
    ->bind('admin_category_delete')
;

/*
 * Créer la partie admin pour les articles :
 * - Créer le contrôleur Admin\ArticleController qui hérite de ControllerAbstract
 * - le définir en service dans src/app.php
 * - y ajouter la méthode listAction() qui va rendre la vue admin/article/list.html.twig
 * - créer la vue
 * - créer la route qui pointe sur l'action
 * - ajouter un lien vers cette route dans la navbar admin
 * - créer l'entity Article et le repository ArticleRepository qui hérite de RepositoryAbstract
 * - déclarer le repository en service dans src/app.php
 * - remplir la méthode listAction() en utilisant ArticleRepository
 * - faire l'affichage en tableau HTML dans la vue
 * - ajouter les méthodes editAction() et deleteAction() avec leurs routes et la vue d'édition
 * - pour le formulaire d'édition :
 *      - input text pour title
 *      - textarea pour header et content
 */
$admin
    ->get('/articles', 'admin.article.controller:listAction')
    ->bind('admin_articles')
;

$admin
    ->match('/article/edition/{id}', 'admin.article.controller:editAction')
    ->value('id', null)
    ->bind('admin_article_edit')
;

$admin
    ->get('/article/suppression/{id}', 'admin.article.controller:deleteAction')
    ->bind('admin_article_delete')
;

$app->error(function (\Exception $e, Request $request, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    // 404.html, or 40x.html, or 4xx.html, or error.html
    $templates = array(
        'errors/'.$code.'.html.twig',
        'errors/'.substr($code, 0, 2).'x.html.twig',
        'errors/'.substr($code, 0, 1).'xx.html.twig',
        'errors/default.html.twig',
    );

    return new Response($app['twig']->resolveTemplate($templates)->render(array('code' => $code)), $code);
});
