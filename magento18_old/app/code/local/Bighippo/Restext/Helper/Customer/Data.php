<?php

class Bighippo_Restext_Helper_Customer_Data extends Mage_Core_Helper_Abstract
{
	public function isLogged(){
        $customer = Mage::helper('customer');
        if(!$customer->isLoggedIn())
            return false;
        else
            return true;
    }


    public function isCustomerLogged(){    
        $array_response = array('state' => $this->isLogged());
        return $array_response;
    }

    public function getUserData(){
        if($this->isLogged()){
            $customer = Mage::helper("customer")->getCustomer();
            $array_addresses = $this->getAdditionalAddresses($customer->getAdditionalAddresses());
            $array_customer = array(
                                "state" => true,
                                "id" => $customer->getId(),
                                "name" => $customer->getName(),
                                "email" => $customer->getEmail(),
                                "telephone" => $customer->getTelephone(),
                                "primaryBillingAddress" => array(
                                                        "name" => $customer->getPrimaryBillingAddress()->getName(),
                                                        "id" => $customer->getPrimaryBillingAddress()->getId(),
                                                        "city" => $customer->getPrimaryBillingAddress()->getCity(),
                                                        "regionId" => $customer->getPrimaryBillingAddress()->getRegionId(), 
                                                        "region" => $customer->getPrimaryBillingAddress()->getRegion(),
                                                        "regionCode" => $customer->getPrimaryBillingAddress()->getRegionCode(),
                                                        "country" => $customer->getPrimaryBillingAddress()->getCountry(),        
                                                        "telephone" => $customer->getPrimaryBillingAddress()->getTelephone(),
                                                        "street1" => $customer->getPrimaryBillingAddress()->getStreet1(),
                                                        "street2" => $customer->getPrimaryBillingAddress()->getStreet2(),
                                                        "street3" => $customer->getPrimaryBillingAddress()->getStreet3(),
                                                        "street4" => $customer->getPrimaryBillingAddress()->getStreet4()
                                                        
                                                        ),
                                "primaryShippingAddress" => array(
                                                        "name" => $customer->getPrimaryShippingAddress()->getName(),
                                                        "id" => $customer->getPrimaryShippingAddress()->getId(),
                                                        "city" => $customer->getPrimaryShippingAddress()->getCity(),
                                                        "regionId" => $customer->getPrimaryShippingAddress()->getRegionId(), 
                                                        "region" => $customer->getPrimaryShippingAddress()->getRegion(),
                                                        "regionCode" => $customer->getPrimaryShippingAddress()->getRegionCode(),
                                                        "country" => $customer->getPrimaryShippingAddress()->getCountry(),        
                                                        "telephone" => $customer->getPrimaryShippingAddress()->getTelephone(),
                                                        "street1" => $customer->getPrimaryShippingAddress()->getStreet1(),
                                                        "street2" => $customer->getPrimaryShippingAddress()->getStreet2(),
                                                        "street3" => $customer->getPrimaryShippingAddress()->getStreet3(),
                                                        "street4" => $customer->getPrimaryShippingAddress()->getStreet4()
                                                        
                                                        ),
                                "additionalAddresses" => $array_addresses
                                );     
        }
        else{
            $array_customer = array("state" => false);
        }
        return $array_customer;
    }

    public function getAdditionalAddresses($pAddsses){
        
        $array_addresses = array();
        foreach ($pAddsses as $address){
            $array_addres = array(
                                    "name" => $address->getName(),
                                    "id" => $address->getId(),
                                    "city" => $address->getCity(),
                                    "regionId" => $address->getRegionId(), 
                                    "region" => $address->getRegion(),
                                    "regionCode" => $address->getRegionCode(),  
                                    "country" => $address->getCountry(),        
                                    "telephone" => $address->getTelephone(),
                                    "street1" => $address->getStreet1(),
                                    "street2" => $address->getStreet2(),
                                    "street3" => $address->getStreet3(),
                                    "street4" => $address->getStreet4()                                                             
                );
        array_push($array_addresses, $array_addres); 
        }
        return $array_addresses;
    }

    //LOGIN FUNCTIONS
    public function login($login, $password)
    {
        $status = false;
        if ($this->isLogged()) {
            return array("status" => "logged in");
        }
        $session = Mage::getSingleton('customer/session');
         if (!empty($login) && !empty($password)) {
            try {
                 $session->login($login, $password);
                if ($this->isLogged()) {
                     $status = true;
                 }
             }
            catch (Exception $e) {
                 $status = false;
             }
        } 
        else {
            $status = false;
        }
        return array("status" => $status);
    }

    public function logout()
    {   if($this->isLogged()){
            Mage::getSingleton('customer/session')->logout();
            return array("status" => true);
        }
        else
            return array("status" => false);
    }

    public function forgotPassword($email)
    {
        $status = false;
        $token = null;
        $id = null;

        $getSession = Mage::getSingleton('customer/session');
        if ($email) {
            if (!Zend_Validate::is($email, 'EmailAddress')) {
                $status = false;
            }
            else{
                $customer = Mage::getModel('customer/customer')
                    ->setWebsiteId(Mage::app()->getStore()->getWebsiteId())
                    ->loadByEmail($email);

                if ($customer->getId()) {
                    try {
                        $newResetPasswordLinkToken =  Mage::helper('customer')->generateResetPasswordLinkToken();
                        $customer->changeResetPasswordLinkToken($newResetPasswordLinkToken);
                        $customer->sendPasswordResetConfirmationEmail();
                        $status = true;
                        $token = $newResetPasswordLinkToken;
                        $id = $customer->getId();
                    } 
                    catch (Exception $exception) {
                        $status = false;
                    }
                }
            } 
        }
        else {
            $status = false;
        }
        return array("status" => $status, "token" => $token, "id" => $id);
    }


    public function resetPassword($id, $token, $password)
    {
        $customerId = $id;
        $resetPasswordLinkToken = $token;
        $passwordConfirmation = $password;
        $status = false;
        try {
            if($this->_validateResetPasswordLinkToken($customerId, $resetPasswordLinkToken)){         
                if (iconv_strlen($password) <= 0) {
                    $status = false;
                }
                $customer = Mage::getModel('customer/customer')->load($customerId);
                $customer->setPassword($password);
                $customer->setConfirmation($passwordConfirmation);
                $validationErrorMessages = $customer->validate();
                if (is_array($validationErrorMessages)) {
                    $errorMessages = array_merge($errorMessages, $validationErrorMessages);
                }
                if (!empty($errorMessages)) {
                    $status = false;
                }
                else{
                    try {
                        $customer->setRpToken(null);
                        $customer->setRpTokenCreatedAt(null);
                        $customer->setConfirmation(null);
                        $customer->save();
                        $status = true;
                        } 
                    catch (Exception $exception) {
                        $status = false;
                    }
                }
            }
        } 
        catch (Exception $exception) {
            $status = false;
        }
        return array("status" => $status);
    }

    protected function _validateResetPasswordLinkToken($customerId, $resetPasswordLinkToken)
    {
        if ($customerId < 0) {
            return false;
        }
        $customer = Mage::getModel('customer/customer')->load($customerId);
        if (!$customer || !$customer->getId()) {
            return $customerId;
        }
        $customerToken = $customer->getRpToken();
        if (strcmp($customerToken, $resetPasswordLinkToken) != 0 || $customer->isResetPasswordLinkTokenExpired()) {
            return false;
        }
        return true;
    }


    public function saveCustomer($customer_data)
    {   
        $customer = Mage::getModel('customer/customer');
        if(!$this->isLogged()){
            $customer->setWebsiteId(Mage::app()->getWebsite()->getId());
            $customer->setFirstname($customer_data->{'firstName'});
            $customer->setLastname($customer_data->{'lastName'});
            $customer->setEmail($customer_data->{'email'});
            $customer->setPassword($customer_data->{'password'});
            $customer->setIsSubscribed($customer_data->{'isSubscribed'});
            try {
                $customer->save();
                $customer->setConfirmation(null);
                $customer->save();
                Mage::getSingleton('customer/session')->loginById($customer->getId());
                $status = true;
            }
            catch (Exception $ex) {
                $status = false;
            }
       }
       return array("status" => $status);
    }

}