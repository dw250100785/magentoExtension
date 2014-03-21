<?php

class Bighippo_Restext_CatalogAPIController extends Mage_Core_Controller_Front_Action {
    
    public function init() 
    {   
        
    }

    public function indexAction() {

        $server = new Zend_Rest_Server();
        $server->setClass('Bighippo_Restext_CatalogAPIController');
        $server->handle();
        exit;
    }