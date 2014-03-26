<?php

class Bighippo_Restext_OrderController extends Mage_Core_Controller_Front_Action {

    protected $server;

    //WEB SERVICE FUNCTIONS
    public function indexAction() {
        $this->server = new Zend_Rest_Server();
        $this->server->setClass('Bighippo_Restext_OrderController');
        $this->server->handle();
        exit;
    }

    public function getOrderHelper(){
        return Mage::helper("restext_order");
    }

    public function getPostData(){
        $postdata = file_get_contents("php://input");
        return json_decode($postdata);
    }

     //ORDERS FUNCTIONS
    public function getOrderById($order_id){
        return json_encode($this->getOrderHelper()->getOrderById($order_id));
    }

    public function getOrders(){
        return json_encode($this->getOrderHelper()->getOrders());
    }
}