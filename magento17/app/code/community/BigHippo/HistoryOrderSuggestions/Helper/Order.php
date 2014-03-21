<?php
class BigHippo_HistoryOrderSuggestions_Helper_Order extends Mage_Core_Helper_Abstract
{

	public function getAllItems() {
        $orders = Mage::getResourceModel('sales/order_collection')
            ->addFieldToSelect('*')
            ->addFieldToFilter('customer_id', Mage::getSingleton('customer/session')->getCustomer()->getId())
            ->addFieldToFilter('state', array('in' => Mage::getSingleton('sales/order_config')->getVisibleOnFrontStates()))
            ->setOrder('created_at', 'desc');

		$orderIds = array();
		foreach($orders as $order) {
			$orderIds[] = $order->getId();
		}

        $orders = Mage::getResourceModel('sales/order_item_collection')
            ->addFieldToSelect('*')
			->addFieldToFilter('order_id', array('in' => $orderIds))
            ->setOrder('created_at', 'desc');
			
		return $orders;
	}

}