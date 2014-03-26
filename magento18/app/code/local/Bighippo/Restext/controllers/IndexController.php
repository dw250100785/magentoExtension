<?php

class Bighippo_Restext_IndexController extends Mage_Core_Controller_Front_Action {

    protected $server;
    protected $request = null;
    protected $rawBody;

    //WEB SERVICE FUNCTIONS
    public function indexAction() {
        $this->server = new Zend_Rest_Server();
        $this->server->setClass('Bighippo_Restext_IndexController');
        $this->server->handle();
        exit;
    }

    public function getPostData(){
        $postdata = file_get_contents("php://input");
        return json_decode($postdata);
    }

    //HELPERS SECTION//

    public function getBasicsHelper(){
        return Mage::helper("restext_basics");
    }

    //BASICS
    public function getStores(){
        return json_encode($this->getBasicsHelper()->getStores());
    }

    public function getPopularTags(){
        return json_encode($this->getBasicsHelper()->getPopularTags());
    }

    public function getAllTags(){
        return json_encode($this->getBasicsHelper()->getAllTags());
    }

    public function getTagById($tag_id){
        return json_encode($this->getBasicsHelper()->getTagById($tag_id));
    }

    public function getSettings(){
        return json_encode($this->getBasicsHelper()->getSettings());
    }

    public function getStoreId(){
        $store_array = array("id" => $this->getBasicsHelper()->getStoreId());
        return json_encode($store_array);
    }

}