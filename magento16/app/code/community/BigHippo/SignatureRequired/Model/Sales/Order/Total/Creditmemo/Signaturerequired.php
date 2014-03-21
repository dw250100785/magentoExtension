<?php
class BigHippo_SignatureRequired_Model_Sales_Order_Total_Creditmemo_Signaturerequired extends Mage_Sales_Model_Order_Creditmemo_Total_Abstract
{
	public function collect(Mage_Sales_Model_Order_Creditmemo $creditmemo)
	{
		$order = $creditmemo->getOrder();
		$feeAmountLeft = $order->getSignaturerequiredAmountInvoiced() - $order->getSignaturerequiredAmountRefunded();
		$basefeeAmountLeft = $order->getBaseSignaturerequiredAmountInvoiced() - $order->getBaseSignaturerequiredAmountRefunded();
		if ($basefeeAmountLeft >= 0) {
			$creditmemo->setGrandTotal($creditmemo->getGrandTotal() + $feeAmountLeft);
			$creditmemo->setBaseGrandTotal($creditmemo->getBaseGrandTotal() + $basefeeAmountLeft);
			$creditmemo->setSignaturerequiredAmount($feeAmountLeft);
			$creditmemo->setBaseSignaturerequiredAmount($basefeeAmountLeft);
		}
		return $this;
	}
}
