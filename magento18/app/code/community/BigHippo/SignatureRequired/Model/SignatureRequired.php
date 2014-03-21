<?php
class BigHippo_SignatureRequired_Model_SignatureRequired extends Varien_Object{
	
	public static function getAmount(){
		return Mage::getStoreConfig("checkout/bighippo_signaturerequired/amount0");
	}
	
	public static function getLabel() {
		return Mage::getStoreConfig("checkout/bighippo_signaturerequired/label0");		
	}
	
	public static function getCode() {
		return 'signaturerequired';		
	}
	
	public static function canApply(){
		$dataSession = Mage::getSingleton('checkout/session')->getData();
		if(isset($dataSession['signaturerequired_flag'])){
			return $dataSession['signaturerequired_flag'];
		}
		return false; 
	}
	
}