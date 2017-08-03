<?php
/**
 * Created by Formateur
 * Date: 05/07/2017
 * Time: 11:55
 */

namespace Controller;


/**
 * Class IndexController
 * @package Controller
 */
class IndexController extends ControllerAbstract
{
    public function indexAction()
    {
        return $this->render('index.html.twig');
    }
}
