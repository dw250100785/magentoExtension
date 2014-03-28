<?php
    
class Bighippo_Restext_CatalogController extends Mage_Core_Controller_Front_Action {

    protected $server;

    //WEB SERVICE FUNCTIONS
    public function indexAction() {
        $this->server = new Zend_Rest_Server();
        $this->server->setClass('Bighippo_Restext_CatalogController');
        $this->server->handle();
        exit;
    }

    public function getPostData(){
        $postdata = file_get_contents("php://input");
        return json_decode($postdata);
    }

    public function getCatalogHelper(){
            return Mage::helper("restext_catalog");
    }

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
        $query = $postdata->{'query'};
        $collection = $this->getCatalogHelper()->getSuggestCollection($query);
        return json_encode($collection);
    }
}