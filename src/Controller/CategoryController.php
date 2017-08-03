<?php
/**
 * Created by Formateur
 * Date: 07/07/2017
 * Time: 17:17
 */

namespace Controller;


/**
 * Class CategoryController
 * @package Controller
 */
class CategoryController extends ControllerAbstract
{
    public function menuAction()
    {
        $categories = $this->app['category.repository']->findAll();

        return $this->render(
            'category/menu.html.twig',
            ['categories' => $categories]
        );
    }
}
