<?php

namespace Xgc\AdminLTEBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('XgcAdminLTEBundle:Default:index.html.twig');
    }
}
