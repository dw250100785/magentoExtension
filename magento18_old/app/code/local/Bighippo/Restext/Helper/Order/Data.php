<?php

class Bighippo_Restext_Helper_Order_Data extends Mage_Core_Helper_Abstract
{
	public function getOrderById($order_id){
        return $this->getOrderDataById($order_id);
    }

    public function getItemTaxes($item){
        $taxes = Mage::helper('weee')->getApplied($item);
        $taxes_array = array();
        $total_taxes = 0;
        $index = 0;
        foreach($taxes as $tax){
            $tax_array = array(
                            "index" => $index,
                            "label" => $tax['title'],
                            "amount" => $tax['row_amount_incl_tax']);
            $index++;
            array_push($taxes_array, $tax_array);
        }
        array_push($taxes_array, array("totalTaxes" => $total_taxes));
        return $taxes_array;
    }

    public function getOrderTotals($order){
        
        $quoteId = $order->getQuoteId();
        $quote   = Mage::getModel('sales/quote')->load($quoteId);
        $totals  = $quote->getTotals();

        $totals_array = array();
        $index = 0;
        foreach($totals as $_code =>  $total)
        {    
            $total_array = array(
                            "index" => $index,
                            "label" => $total->getCode(),
                            "amount" => $total->getValue()/**/
                            );
            $index++;
            array_push($totals_array, $total_array);   
        }
        return $totals_array;
    }

    public function getOrderDataById($order_id){
            $order = Mage::getModel('sales/order')->load($order_id);
            
            $subtotal = 0;
            $taxes = 0; 
            
            $items_array = array();
            $items = $order->getItemsCollection();

            foreach ($items as $item) {
                $subtotal += $item->getQtyOrdered()*$item->getPrice();
                $taxes += $subtotal*$item->getData('tax_percent');    
                array_push($items_array, $this->getItemData($item));
            }

            $order_array = array (
                            "id" => $order->getId(),
                            "realOrderId" => $order->getRealOrderId(),
                            "date" => Mage::helper('core')->formatDate($order->getCreatedAtStoreDate()),
                            "orderSubtotal" => $subtotal,
                            "shippingAmount" => $order->getShippingAmount(),
                            "shippingTaxAmount" => $order->getShippingTaxAmount(),
                            "orderTotal" => $order->getGrandTotal(),
                            "paymentMethod" => $order->getPayment()->getMethod(),
                            "status" => $order->getStatusLabel(),
                            "shippingAddress" => array(
                                                "addressId" => $order->getShippingAddress()->getId(),
                                                "addressName" => $order->getShippingAddress()->getName(),
                                                "addressCity" => $order->getShippingAddress()->getCity(),
                                                "addressRegionId" => $order->getShippingAddress()->getRegionId(),
                                                "addressRegion" => $order->getShippingAddress()->getRegion(),
                                                "addressRegionCode" => $order->getShippingAddress()->getRegionCode(),
                                                "addressCountry" => $order->getShippingAddress()->getCountry(),
                                                "addressTelephone" => $order->getShippingAddress()->getTelephone(),
                                                "addressStreet1" => $order->getShippingAddress()->getStreet1(),
                                                "addressStreet2" => $order->getShippingAddress()->getStreet2(),
                                                "addressStreet3" => $order->getShippingAddress()->getStreet3(),
                                                "addressStreet4" => $order->getShippingAddress()->getStreet4()
                                                ),
                            "billingAddress" => array(
                                                "addressId" => $order->getBillingAddress()->getId(),
                                                "addressName" => $order->getBillingAddress()->getName(),
                                                "addressCity" => $order->getBillingAddress()->getCity(),
                                                "addressRegionId" => $order->getBillingAddress()->getRegionId(),
                                                "addressRegion" => $order->getBillingAddress()->getRegion(),
                                                "addressRegionCode" => $order->getBillingAddress()->getRegionCode(),
                                                "addressCountry" => $order->getBillingAddress()->getCountry(),
                                                "addressTelephone" => $order->getBillingAddress()->getTelephone(),
                                                "addressStreet1" => $order->getBillingAddress()->getStreet1(),
                                                "addressStreet2" => $order->getBillingAddress()->getStreet2(),
                                                "addressStreet3" => $order->getBillingAddress()->getStreet3(),
                                                "addressStreet4" => $order->getBillingAddress()->getStreet4()
                                                ),
                            "items" => $items_array,
                            "totals" => $this->getOrderTotals($order)
                );
            return $order_array;
    }

    public function getItemData($item){
            $subtotal = $item->getQtyOrdered()*$item->getPrice();
            $taxes = $subtotal*$item->getData('tax_percent');
            $item_array = array(
                            "id" => $item->getId(),
                            "name" => $item->getName(), 
                            "Sku" => $item->getSku(),
                            "price" => $item->getPrice(),
                            "qtyOrdered" => $item->getQtyOrdered(),
                            "qtyShipped" => $item->getQtyShipped(),
                            "qtyCanceled" => $item->getQtyCanceled(),
                            "qtyRefunded" => $item->getQtyRefunded(),
                            "subtotal" => $subtotal,
                            "taxPercent" => $item->getData('tax_percent'),
                            "taxes" => $this->getItemTaxes($item)
                );
            
         return $item_array;
    }

    public function getOrders(){
        
        $orders = Mage::getResourceModel('sales/order_collection')
            ->addAttributeToSelect('*')
            ->joinAttribute('shipping_firstname', 'order_address/firstname', 'shipping_address_id', null, 'left')
            ->joinAttribute('shipping_lastname', 'order_address/lastname', 'shipping_address_id', null, 'left')
            ->addAttributeToFilter('customer_id', Mage::getSingleton('customer/session')->getCustomer()->getId())
            ->addAttributeToFilter('state', array('in' => Mage::getSingleton('sales/order_config')->getVisibleOnFrontStates()))
            ->addAttributeToSort('created_at', 'desc')/**/
            ->load()
        ;

        $orders_array = array();

        foreach($orders as $order){
                array_push($orders_array, $this->getOrderDataById($order->getId()));    
        }


        return $orders_array;
    }

}