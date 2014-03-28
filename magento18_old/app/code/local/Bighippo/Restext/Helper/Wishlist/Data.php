<?php

class Bighippo_Restext_Helper_Wishlist_Data extends Mage_Core_Helper_Abstract
{
	    public function getWishItem($item){
        $item_array = array(
                        "id" => $item->getId(),
                        "comment" => $item->getDescription(),
                        "product" => Mage::helper("restext_catalog")->getProductArray($item->getProduct())
                );
        return $item_array;
    }

    public function getWishList(){
        $customer = Mage::getSingleton('customer/session')->getCustomer();
        $wishList = Mage::getModel('wishlist/wishlist')->loadByCustomer($customer);
        $wishList = $wishList->getItemCollection();
        $items_array = array();
        
        foreach($wishList as $item){
                array_push($items_array, $this->getWishItem($item));
        }
        return $items_array;
    }
}