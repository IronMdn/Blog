<?php
/**
 * Created by Formateur
 * Date: 05/07/2017
 * Time: 14:43
 */

namespace Controller\Admin;

use Controller\ControllerAbstract;
use Entity\Category;


/**
 * Class CategoryController
 * @package Controller\Admin
 */
class CategoryController extends ControllerAbstract
{
    public function listAction()
    {
        $categories = $this->app['category.repository']->findAll();

        return $this->render(
            'admin/category/list.html.twig',
            ['categories' => $categories]
        );
    }

    public function editAction($id = null)
    {
        if (!is_null($id)) { // modification
            $category = $this->app['category.repository']->find($id);
        } else { // création
            $category = new Category();
        }

        $errors = [];

        if (!empty($_POST)) {

            $category->setName($_POST['name']);

            if (empty($_POST['name'])) {
                $errors['name'] = 'Le nom est obligatoire';
            } elseif (strlen($_POST['name']) > 20) {
                $errors['name'] = 'Le nom ne doit pas faire plus de 20 caractères';
            }

            if (empty($errors)) {
                $this->app['category.repository']->save($category);
                $this->addFlashMessage('La rubrique est enregistrée');

                return $this->redirectRoute('admin_categories');
            } else {
                $message = '<b>Le Formulaire contient des erreurs</b>';
                $message .= '<br>' . implode('<br>', $errors);
                $this->addFlashMessage($message, 'error');
            }
        }

        return $this->render(
            'admin/category/edit.html.twig',
            [
                'category' => $category
            ]
        );
    }

    public function deleteAction($id)
    {
        $category = $this->app['category.repository']->find($id);

        $this->app['category.repository']->delete($category);

        $this->addFlashMessage('La rubrique est supprimée');

        return $this->redirectRoute('admin_categories');
    }
}
