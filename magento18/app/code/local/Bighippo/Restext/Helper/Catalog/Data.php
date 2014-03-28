<?php

class Bighippo_Restext_Helper_Catalog_Data extends Mage_Core_Helper_Abstract
{
    protected $_query;
    protected $_queryText;

	public function getSubCategories($category){      
        if(count($category->getChildren())>0) {
            $array_subcategories = array();
            $helper = Mage::helper('catalog/category');
            foreach ($category->getChildren() as $subcategory) {
                   if($subcategory->getIsActive()){
                           $array_subcategory = array(
                                                    "id" => $subcategory->getId(),
                                                    "name" => $subcategory->getName(),
                                                    "url" => $helper->getCategoryUrl($subcategory)
                                                    );
                            array_push($array_subcategory, $this->getSubCategories($subcategory));
                            array_push($array_subcategories, $array_subcategory);

                       }
                    } 
        }
        return $array_subcategories;
    }

    public function getCategories(){
        $helper = Mage::helper('catalog/category');
        $categories = $helper->getStoreCategories();
        $cat_array = array(); 
        if(count($categories) > 0){
            foreach($categories as $category){
                if ($category->getIsActive()){
                    $array_tmp = array(
                            "id" => $category->getId(),
                            "name" => $category->getName(),
                            "url" => $helper->getCategoryUrl($category)
                            );
                array_push($array_tmp, $this->getSubCategories($category));
                array_push($cat_array, $array_tmp);
                }  
            }
        }
        return $cat_array;
    }

    public function getProductList($category_id){
        
        $category = Mage::getModel('catalog/category')->load($category_id);
        $helper = Mage::helper('catalog/product/list');
        $layer = $this->getLayer();

        if (Mage::registry('product')) {
                $categories = Mage::registry('product')->getCategoryCollection()
                    ->setPage(1, 1)
                    ->load();
                if ($categories->count()) {
                    $helper->setCategoryId(current($categories->getIterator()));
                }
            }
        
        if ($category->getId()) {
                    $origCategory = $layer->getCurrentCategory();
                    $layer->setCurrentCategory($category);
                }

        $productCollection = $layer->getProductCollection();
        
        $result_array = array();
        if($productCollection->count()){
            foreach ($productCollection as $product){
               
                $product_array = $this->getProductArray($product);
                array_push($result_array, $product_array);
            
            }
        }

        return $result_array;
    }

    public function getProductView($product_id){
        $product = Mage::getModel("catalog/product")->load($product_id);
        $product_array = $this->getProductArray($product);
        array_push($product_array, array("attributes" => $this->getAttributes($product)));
        if($product->getAssociatedProducts()>0){
            $product_array = array_merge($product_array, array("associatedProducts" => $this->getAssociatedProducts($product)));
        }
        if($product->getData('type_id') == "configurable"){
            $conf = array("configurableAttributes" => $this->getConfigurableAttributes($product));
            $product_array = array_merge($product_array, $conf);
        }
        return $product_array;
    }

    public function getConfigurableAttributes($product){
        $attributes = $product->getTypeInstance(true)->getConfigurableAttributesAsArray($product);
        $attributes_array = array();
        foreach ($attributes as $attribute_p) {
            $i=0;
            $options_array = array();
             foreach ($attribute_p['values'] as $attribute) {
               $option_array = array(
                                       "index" => $i,
                                       "option" => $attribute['store_label']
                                    );
               $i++;
                array_push($options_array, $option_array);
              }
            $attribute_array = array($attribute_p['label'] => $options_array);
            array_push($attributes_array, $attribute_array);
        }
        return $attributes_array;
    }

    public function getAttributes($product){
        $attributes = $product->getAttributes();
        $attributes_array = array();
        foreach($attributes as $attribute){
            $attribute_array = array(
                                $attribute->getAttributeCode() => $attribute->getFrontend()->getValue($product)
                );
            array_push($attributes_array, $attribute_array);
        }
        return $attributes_array;
    }

    public function getAssociatedProducts($product){
        $assProduct = $product->getAssociatedProducts();
        $items_array = array();
        foreach($assProduct as $item){
            $_finalPriceInclTax = $this->helper('tax')->getPrice($item, $item->getFinalPrice(), true);
            $item_array = array(
                           "id" => $item->getId(),
                           "name" => $item->getName(),
                           "finalPrice" => $_finalPriceInclTax
                );
            array_push($items_array, $item_array);
        }
        return $items_array;
    }


    public function getProductArray($product){
        $entitySummary = Mage::getModel('review/review')->getEntitySummary($product, Mage::app()->getStore()->getId());
        $product_array = array(
                                "id" => $product->getId(),
                                "name" => $product->getName(),
                                "image" => $product->getImageUrl(),
                                "price" => $product->getPrice(),
                                "short_description" => $product->getShortDescription(),
                                "ratingSummary" => $product->getRatingSummary(),
                                "entitySummary" => $entitySummary,
                                "availability" => $product->isAvailable()
                                );
        return $product_array;
    }

     public function getLayer()
    {
        $layer = Mage::registry('current_layer');
        if ($layer) {
            return $layer;
        }
        return Mage::getSingleton('catalog/layer');
    }

    public function getProducts(){     
        $productCollection = Mage::getModel('catalog/product')
            ->getCollection()
            ->addAttributeToSelect('*')
            ->getItems();
        foreach ($productCollection as $id => $data)
        {
            $allProducts[] = $data;
        }
        $array_products = array();
        foreach ($allProducts as $product){
            array_push($array_products, 
                array("id" => $product->getId(),
                      "name" => $product->getName(),
                      "price" => $product->getPrice()
                ));
        }
        $result = $array_products;
        return $result;
    }

    //QUERIES

    public function getSuggestCollection($search_term)
    {
        $collection = Mage::getResourceModel('catalogsearch/search_collection');
        $text = $search_term;
        $collection->addSearchFilter($text)
            ->addStoreFilter()
            ->addMinimalPrice()
            ->addTaxPercents();

        $products_array = array();
        foreach ($collection as $product) {
            $product = Mage::getModel("catalog/product")->load($product->getId());
            if($product->getData('visibility')==4) //IT SHOWS ONLY PRODUCTS VISIBLE FROM CATALOG
            {
                $product_array = $this->getProductArray($product);
                array_push($products_array, $product_array);
            }
        }
        return $products_array;
    }

}