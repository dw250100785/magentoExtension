<?php
class BigHippo_HistoryOrderSuggestions_Model_Config_Attributes
{
    public function toOptionArray() {
		$attributes = Mage::getSingleton('eav/config')->getEntityType(Mage_Catalog_Model_Product::ENTITY)->getAttributeCollection();

		$results = array();
		foreach($attributes as $attribute){
			if($attribute->getFrontendLabel()) {
				$results[$attribute->getAttributeCode()] = $attribute->getFrontendLabel();
			}
		}
		
		return $results;
	}		
}