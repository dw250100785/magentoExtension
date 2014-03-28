<?php
    
class Bighippo_Restext_CustomerController extends Mage_Core_Controller_Front_Action {

    protected $server;

    //WEB SERVICE FUNCTIONS
    public function indexAction() {
        $this->server = new Zend_Rest_Server();
        $this->server->setClass('Bighippo_Restext_CustomerController');
        $this->server->handle();
        exit;
    }

    public function getPostData(){
        $postdata = file_get_contents("php://input");
        return json_decode($postdata);
    }

    public function getCustomerHelper(){
        return Mage::helper("restext_customer");
    }

    public function getWishlistHelper(){
        return Mage::helper("restext_wishlist");
    }

    public function getNewsletterHelper(){
        return Mage::helper("restext_newsletter");
    }

    //CUSTOMER FUNCTIONS
    public function isCustomerLogged(){    
        return json_encode($this->getCustomerHelper()->isCustomerLogged());
    }

    public function getUserData(){
        return json_encode($this->getCustomerHelper()->getUserData());
    }

    //LOGIN FUNCTIONS
    public function login(){
        $postdata = $this->getPostData();
        $login = $postdata->{'username'};
        $password = $postdata->{'password'};
        $result = $this->getCustomerHelper()->login($login, $password);
        return json_encode($result);
    }

    public function logout(){
        return json_encode($this->getCustomerHelper()->logout());
    }

    public function forgotPassword(){
        $postdata = $this->getPostData();
        $email = $postdata->{'email'};
        $result = $this->getCustomerHelper()->forgotPassword($email);
        return json_encode($result);
    }

    public function resetPassword(){
        $postdata = $this->getPostData();
        $id = $postdata->{'id'};
        $token = $postdata->{'token'};
        $password = $postdata->{'password'};
        $result = $this->getCustomerHelper()->resetPassword($id, $token, $password);
        return json_encode($result);
    }

    public function createCustomer(){
        $postdata = $this->getPostData();
        $result = $this->getCustomerHelper()->saveCustomer($postdata);
        return json_encode($result);
        //return json_encode(array("isSubs" => $postdata->{'isSubscribed'}));//json_encode($result);
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