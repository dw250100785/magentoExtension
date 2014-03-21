<?php
class BigHippo_SignatureRequired_Model_Observer{
	
	public function invoiceSaveAfter(Varien_Event_Observer $observer) {
		$invoice = $observer->getEvent()->getInvoice();
		$order = $invoice->getOrder();
		if ($order->getSignaturerequiredFlag()) {
			$order->setBaseSignaturerequiredAmountInvoiced($order->getBaseSignaturerequiredAmountInvoiced() + $invoice->getBaseSignaturerequiredAmount());
			$order->setSignaturerequiredAmountInvoiced($order->getSignaturerequiredAmountInvoiced() + $invoice->getSignaturerequiredAmount());
		}
		return $this;
	}
	
	public function creditmemoSaveAfter(Varien_Event_Observer $observer) {
		$creditmemo = $observer->getEvent()->getCreditmemo();
		$order = $creditmemo->getOrder();
		if ($order->getSignaturerequiredFlag()) {
			$order->setBaseSignaturerequiredAmountRefunded($order->getBaseSignaturerequiredAmountRefunded() + $creditmemo->getBaseSignaturerequiredAmount());
			$order->setSignaturerequiredRefunded($order->getSignaturerequiredRefunded() + $creditmemo->getSignaturerequiredAmount());
		}
		return $this;
	}
	
	public function updatePaypalTotal($evt) {
		$cart = $evt->getPaypalCart();
		$cart->updateTotal(Mage_Paypal_Model_Cart::TOTAL_SUBTOTAL,$cart->getSalesEntity()->getSignaturerequiredAmount());
	}
		
	public function quoteToOrder(Varien_Event_Observer $observer) {
		$order = $observer->getOrder();
		$quote = $observer->getQuote();

		$order->setData("signaturerequired_flag", $quote->getSignaturerequiredFlag());
	}
	
}