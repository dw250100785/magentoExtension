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
        public function getCatalogHelper(){
            return Mage::helper("restext_catalog");
        }

        public function getBasicsHelper(){
            return Mage::helper("restext_basics");
        }

        public function getCustomerHelper(){
            return Mage::helper("restext_customer");
        }

        public function getOrderHelper(){
            return Mage::helper("restext_order");
        }

        public function getWishlistHelper(){
            return Mage::helper("restext_wishlist");
        }

        public function getNewsletterHelper(){
            return Mage::helper("restext_newsletter");
        }

    //GET FUNCTIONS - CATALOG AND CATEGORIES
    public function getProducts(){
        return json_encode($this->getCatalogHelper()->getProducts()); 
    }

    public function getCategories(){
        return json_encode($this->getCatalogHelper()->getCategories());
    }

    public function getProductList($category_id){
        return json_encode($this->getCatalogHelper()->getProductList($category_id));
    }

    public function getProductView($product_id){
        return json_encode($this->getCatalogHelper()->getProductView($product_id));
    }

    public function getConfigurableAttributes($product_id){
        $product = Mage::getModel("catalog/product")->load($product_id);
        return json_encode($this->getCatalogHelper()->getConfigurableAttributes($product));
    }

    public function getProductSearch(){
        $postdata = $this->getPostData();
        $greeting = $postdata->{'saludo'}." Javier";
        return json_encode(array('greeting' => $greeting));
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

    //CUSTOMER FUNCTIONS
    public function isCustomerLogged(){    
        return json_encode($this->getCustomerHelper()->isCustomerLogged());
    }

    public function getUserData(){
        return json_encode($this->getCustomerHelper()->getUserData());
    }

    //ORDERS FUNCTIONS
    public function getOrderById($order_id){
        return json_encode($this->getOrderHelper()->getOrderById($order_id));
    }

    public function getOrders(){
        return json_encode($this->getOrderHelper()->getOrders());
    }

    //WISHLIST
    public function getWishList(){
        return json_encode($this->getWishlistHelper()->getWishList());
    }

    //NEWSLETTERS
    public function getNewsletterStatus(){
            return json_encode($this->getNewsletterHelper()->getNewsletterStatus());
    }
}