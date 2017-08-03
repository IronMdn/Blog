<?php
/**
 * Created by Formateur
 * Date: 06/07/2017
 * Time: 16:45
 */

namespace Controller\Admin;

use Controller\ControllerAbstract;
use Entity\Article;
use Entity\Category;


/**
 * Class ArticleController
 * @package Controller\Admin
 */
class ArticleController extends ControllerAbstract
{
    public function listAction()
    {
        $articles = $this->app['article.repository']->findAll();

        return $this->render(
            'admin/article/list.html.twig',
            ['articles' => $articles]
        );
    }

    public function editAction($id = null)
    {
        $categories = $this->app['category.repository']->findAll();

        if (!empty($id)) {
            $article = $this->app['article.repository']->find($id);
        } else {
            $article = new Article();
            $article->setCategory(new Category());
            $article->setAuthor($this->app['user.manager']->getUser());
        }

        if (!empty($_POST)) {
            $article
                ->setTitle($_POST['title'])
                ->setHeader($_POST['header'])
                ->setContent($_POST['content'])
            ;

            $article->getCategory()->setId($_POST['category']);

            if (empty($_POST['title'])) {
                $errors['title'] = 'Le titre est obligatoire';
            }

            if (empty($_POST['header'])) {
                $errors['header'] = "L'entête est obligatoire";
            }

            if (empty($_POST['content'])) {
                $errors['content'] = 'Le contenu est obligatoire';
            }

            if (empty($_POST['category'])) {
                $errors['category'] = 'La rubrique est obligatoire';
            }

            if (empty($errors)) {
                $this->app['article.repository']->save($article);
                $this->addFlashMessage("L'article est enregistré");

                return $this->redirectRoute('admin_articles');
            } else {
                $message = '<b>Le Formulaire contient des erreurs</b>';
                $message .= '<br>' . implode('<br>', $errors);
                $this->addFlashMessage($message, 'error');
            }
        }

        return $this->render(
            'admin/article/edit.html.twig',
            [
                'article' => $article,
                'categories' => $categories
            ]
        );
    }

    public function deleteAction($id)
    {
        $article = $this->app['article.repository']->find($id);

        $this->app['article.repository']->delete($article);

        $this->addFlashMessage("L'article est supprimé");

        return $this->redirectRoute('admin_articles');
    }
}
