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
class BigHippo_Icloset_Helper_Data extends Mage_Core_Helper_Abstract
{
	
    public function loadProducts($categoryId, $page = null, $pageSize = null) {
        
        $productCollection = Mage::getResourceModel('reports/product_collection')
            ->addAttributeToSelect('*')
            ->addAttributeToFilter("status", Mage_Catalog_Model_Product_Status::STATUS_ENABLED)
            ->addAttributeToSort('global_position', 'ASC');

        //Add category Filter
        $productCollection->joinField('category_id', 'catalog/category_product', 'category_id', 'product_id = entity_id', null, 'left');     
        $productCollection->addAttributeToFilter('category_id', array('in' => $categoryId)); 
        
        $productCollection->load();
        
        return $productCollection;
    }

}