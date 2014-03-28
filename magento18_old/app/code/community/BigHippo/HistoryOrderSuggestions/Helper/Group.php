<?php
class BigHippo_HistoryOrderSuggestions_Helper_Group extends Mage_Core_Helper_Abstract
{

	public function getAvailableGroups($purchasedItems) {
		$groups = $this->getAllGroups();
		$groupAttribute = Mage::helper('bighippo_historyordersuggestions')->getGroupAttribute();

		foreach($purchasedItems as $item) {
			foreach($groups as $k => $v) {
				if($v['value'] == $item->getProduct()->getData($groupAttribute)) {
					unset($groups[$k]);					
				}
			}
		}
		
		return $groups;
		
	}
	
	public function getAllGroups() {
		$groups = array();
		$groupAttribute = Mage::helper('bighippo_historyordersuggestions')->getGroupAttribute();
		
		$attribute = Mage::getModel('catalog/resource_eav_attribute')
										->loadByCode(Mage_Catalog_Model_Product::ENTITY, $groupAttribute);
		if ($attribute->usesSource()) {
	    	$groups = $attribute->getSource()->getAllOptions(false);
		}
		return $groups;
	}


}