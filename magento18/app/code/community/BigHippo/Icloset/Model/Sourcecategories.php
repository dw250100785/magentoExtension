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
class BigHippo_Icloset_Model_Sourcecategories {
	    
	public function toOptionArray() {
		$category = Mage::getModel('catalog/category');
		$tree = $category->getTreeModel();
		$tree->load();
		$ids = $tree->getCollection()->getAllIds();
		$arr = array();
		
		foreach ($ids as $id ) {
    		$cat = Mage::getModel('catalog/category');
			$cat->load($id);
			$arr[$id] = $cat->getName();
		}
		return $arr;
	}

}
