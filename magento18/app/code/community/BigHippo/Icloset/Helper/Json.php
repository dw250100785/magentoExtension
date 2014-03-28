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
 * @category   iCloset
 * @package    BigHippo_iCloset
 * @copyright  Copyright (c) 2003-2016 unexpected it Co. (http://www.unexpectedit.com)
 * @license    http://www.unexpectedit.com/LICENSE-M1.txt
 */ 
class BigHippo_Icloset_Helper_Json extends Mage_Core_Helper_Abstract
{

    /**
     * Return an Array ready to use in Json
     */
    public function category($category) {
        $data = Array();
        $data["category_id"] = $category->getId();
        $data["name"] = $category->getName();
            
        return $data;
    }

    /**
     * Return an Array ready to use in Json
     */
    public function product($product) {
        $data = Array();
        $data["sku"] = $product->getSku();

        $data["name"] = $product->getName();
        $data["category_ids"] = $product->getCategoryIds();

        $data["description"] = $product->getDescription();
        $data["short_description"] = $product->getShortDescription();

        $data["price"] = $product->getFinalPrice();

        //Images
        $galleryData = $product->getData("media_gallery");
        if(count($galleryData["images"])) {
            $image = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).'catalog/product'.$galleryData["images"][1]["file"];
            $data["image_play"] = $image;
            $image = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).'catalog/product'.$galleryData["images"][0]["file"];
            $data["image_small"] = $image;
        }
        return $data;
    }


}