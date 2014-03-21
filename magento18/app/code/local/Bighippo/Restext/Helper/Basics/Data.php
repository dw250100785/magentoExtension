<?php

class Bighippo_Restext_Helper_Basics_Data extends Mage_Core_Helper_Abstract
{
    public function getStores(){
        $stores = Mage::app()->getStores();
        $stores_array = array();
        foreach ($stores as $store) {
            $store_array = array(
                            "id" => $store->getId(),
                            "name" => $store->getName()
                );
            array_push($stores_array, $store_array);
        }

        return $stores_array;

    }

    public function getPopularTags(){
        $tags = Mage::getModel('tag/tag')->getPopularCollection()
                ->joinFields(Mage::app()->getStore()->getId())
                ->limit(20)
                ->load()
                ->getItems();

        $tags_array = array();
        
        foreach ($tags as $tag) {
            array_push($tags_array, $this->getTagArray($tag));
        }

        return $tags_array;
    }

    public function getAllTags(){
        $tags = Mage::getModel('tag/tag')->getPopularCollection()
                ->joinFields(Mage::app()->getStore()->getId())
                ->load()
                ->getItems();

        $tags_array = array();
        
        foreach ($tags as $tag) {
            array_push($tags_array, $this->getTagArray($tag));
        }

        return $tags_array;
    }


    public function getTagArray($tag){

        $tag_array = array(
                        "id" => $tag->getId(),
                        "name" => $tag->getName(),
                        "popularity" => $tag->getPopularity(),
                        "related_products_id" => $tag->getRelatedProductIds()
                );
        return $tag_array;
    }


    public function getTagArrayWithProducts($tag){
        
        $product_ids = $tag->getRelatedProductIds();
        $array_products = array();
        for($i=0; $i<count($product_ids); $i++) {
            $product = Mage::getModel("catalog/product")->load($product_ids[$i]);
            array_push($array_products, Mage::helper("restext_catalog")->getProductArray($product));     
        }

        $tag_array = array(
                        "id" => $tag->getId(),
                        "name" => $tag->getName(),
                        "popularity" => $tag->getPopularity(),
                        "related_products" => $array_products
                );
        return $tag_array;
    }


    public function getTagById($tag_id){
        $tags =  Mage::getModel('tag/tag')->getPopularCollection()
                ->joinFields(Mage::app()->getStore()->getId());

        $tags_array = array();
        
        foreach ($tags as $tag) {
            if($tag->getTagId()==$tag_id)
                {   
                    array_push($tags_array, $this->getTagArrayWithProducts($tag));
                    break;
                }
        }
        return $tags_array;
    }

    public function getCountries(){
        $countries = Mage::getResourceModel('directory/country_collection')
                    ->loadData()
                    ->toOptionArray(false);
        $countries_array = array();
        foreach($countries as $country){
            $country_array = array(
                             "value" => $country['value'], 
                             "label" => $country['label'],
                             "regions" => $this->getRegions($country['value'])   
                );
            array_push($countries_array, $country_array);
        }
        return $countries_array;
    }

    public function getRegions($countryCode){
        $regions = Mage::getModel('directory/region_api')->items($countryCode);
        $regions_array = array();
        foreach($regions as $region){
            $region_array = array(
                             "value" => $region['code'], 
                             "label" => $region['name']
                );
            array_push($regions_array, $region_array);
        }/**/
        return $regions_array;
    }


    public function getSettings(){
         $currency = Mage::app()->getLocale()->currency(Mage::app()->getStore()->getCurrentCurrencyCode());
         $countries = 
         $settings_array = array(
                            "currency" => $currency->getSymbol(),
                            "countries" => $this->getCountries()/*,
                            */
            );
         return $settings_array;
    }
}