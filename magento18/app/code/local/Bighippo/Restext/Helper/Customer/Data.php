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
}