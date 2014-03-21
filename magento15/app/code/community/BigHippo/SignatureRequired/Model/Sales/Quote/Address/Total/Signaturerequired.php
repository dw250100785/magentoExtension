<?php
class BigHippo_SignatureRequired_Model_Sales_Quote_Address_Total_Signaturerequired extends Mage_Sales_Model_Quote_Address_Total_Abstract {

	public function collect(Mage_Sales_Model_Quote_Address $address)
	{
		parent::collect($address);

		$this->_setAmount(0);
		$this->_setBaseAmount(0);

		$items = $this->_getAddressItems($address);
		if (!count($items)) {
			return $this; //this makes only address type shipping to come through
		}

		$quote = $address->getQuote();
		
		if(BigHippo_SignatureRequired_Model_SignatureRequired::canApply()){
			$exist_amount = $quote->getSignaturerequiredAmount();
			$fee = BigHippo_SignatureRequired_Model_SignatureRequired::getAmount();
			$balance = $fee - $exist_amount;

			$quote->setSignaturerequiredFlag(true);
			$address->setSignaturerequiredAmount($balance);
			$address->setBaseSignaturerequiredAmount($balance);
			$address->setGrandTotal($address->getGrandTotal() + $address->getSignaturerequiredAmount());
			$address->setBaseGrandTotal($address->getBaseGrandTotal() + $address->getBaseSignaturerequiredAmount());
		}
		else {
			$quote->setSignaturerequiredFlag(false);
			$address->setSignaturerequiredAmount(0);
			$address->setBaseSignaturerequiredAmount(0);
		}
	}

	public function fetch(Mage_Sales_Model_Quote_Address $address)
	{
		$amt = $address->getSignaturerequiredAmount();
		$dataSession = Mage::getSingleton('checkout/session')->getData();
		if(isset($dataSession['signaturerequired_flag']) && $dataSession['signaturerequired_flag']){
			$address->addTotal(array(
					'code'  => BigHippo_SignatureRequired_Model_SignatureRequired::getCode(),
					'title' => BigHippo_SignatureRequired_Model_SignatureRequired::getLabel(),
					'value' => $amt
			)); 
		}
		return $this;
	}
}