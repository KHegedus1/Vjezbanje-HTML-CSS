<?php

class GrupaController extends AutorizacijaController
{

    private $viewDir = 
                'privatno' . DIRECTORY_SEPARATOR . 
                    'grupe' . DIRECTORY_SEPARATOR;

    public function index()
    {
       $this->view->render($this->viewDir . 'index');
    }   
}