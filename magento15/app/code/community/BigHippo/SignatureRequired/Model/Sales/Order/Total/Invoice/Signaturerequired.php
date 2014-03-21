<?php
class BigHippo_SignatureRequired_Model_Sales_Order_Total_Invoice_Signaturerequired extends Mage_Sales_Model_Order_Invoice_Total_Abstract
{
	public function collect(Mage_Sales_Model_Order_Invoice $invoice)
	{
		$order = $invoice->getOrder();
		$feeAmountLeft = $order->getSignaturerequiredAmount() - $order->getSignaturerequiredAmountInvoiced();
		$baseFeeAmountLeft = $order->getBaseSignaturerequiredAmount() - $order->getBaseSignaturerequiredAmountInvoiced();
		if (abs($baseFeeAmountLeft) < $invoice->getBaseGrandTotal()) {
			$invoice->setGrandTotal($invoice->getGrandTotal() + $feeAmountLeft);
			$invoice->setBaseGrandTotal($invoice->getBaseGrandTotal() + $baseFeeAmountLeft);
		} else {
			$feeAmountLeft = $invoice->getGrandTotal() * -1;
			$baseFeeAmountLeft = $invoice->getBaseGrandTotal() * -1;

			$invoice->setGrandTotal(0);
			$invoice->setBaseGrandTotal(0);
		}

		$invoice->setSignaturerequiredAmount($feeAmountLeft);
		$invoice->setBaseSignaturerequiredAmount($baseFeeAmountLeft);
		return $this;
	}
}
