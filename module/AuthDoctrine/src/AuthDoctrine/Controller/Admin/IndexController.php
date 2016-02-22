<?php
/**
 * Created by PhpStorm.
 * User: Max
 * Date: 16/02/2016
 * Time: 22:07
 */

namespace AuthDoctrine\Controller\Admin;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel();
    }
}