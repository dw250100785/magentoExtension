<?php

class Bighippo_Restext_Helper_Newsletter_Data extends Mage_Core_Helper_Abstract
{
	public function getNewsletterStatus(){
        $email = Mage::getSingleton('customer/session')->getCustomer()->getEmail(); 
        $subscriber = Mage::getModel('newsletter/subscriber')->loadByEmail($email); 
        $status = $subscriber->isSubscribed();    

        $status = array("status" => $status);
        return $status;
    }
}