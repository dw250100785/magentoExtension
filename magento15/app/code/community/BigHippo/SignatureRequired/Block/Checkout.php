<?php
/**
 * BigHippo from unexpected it
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.unexpectedit.com/LICENSE-M1.txt
 *
 * @category   SignatureRequired
 * @package    BigHippo_SignatureRequired
 * @copyright  Copyright (c) 2003-2016 unexpected it Co. (http://www.unexpectedit.com)
 * @license    http://www.unexpectedit.com/LICENSE-M1.txt
 */ 
class BigHippo_SignatureRequired_Block_Checkout extends Mage_Core_Block_Template
{

    function isEnabled() {
        return Mage::getStoreConfig("checkout/bighippo_signaturerequired/enabled");
    }
	
	function getSession() {
		return Mage::getSingleton('checkout/session');	
	}
	
	function isSignatureRequiredSelected() {
		$dataSession = $this->getSession()->getData();
		if (isset($dataSession['signaturerequired_flag'])) {
			return $dataSession['signaturerequired_flag'];
		}
		return false;
	}
	
	function isFreeShippingSelected() {
		$shippingMethod = $this->getSession()->getQuote()->getShippingAddress()->getShippingMethod();
		if($shippingMethod == 'freeshipping_freeshipping'){
			return true;
		}	
		return false;
	}

}
