<?php
class BigHippo_SignatureRequired_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function format($amount){
		return Mage::helper('signaturerequired')->__('Signature required');
	}
}