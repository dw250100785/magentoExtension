<?php
class BigHippo_HistoryOrderSuggestions_Helper_Data extends Mage_Core_Helper_Abstract
{
	const XML_GROUP_ATTRIBUTE = "bighippo_historyordersuggestions/local/attribute";
	const XML_GROUP_RANDOM = "bighippo_historyordersuggestions/local/random";
	const XML_GROUP_DEFAULT_PRODUCT_ID = "bighippo_historyordersuggestions/local/default_productid";
	const XML_GROUP_DEFAULT_STATIC_BLOCK = "bighippo_historyordersuggestions/local/default_staticblock";
	
	public function getGroupAttribute() {
		return Mage::getStoreConfig(self::XML_GROUP_ATTRIBUTE);
	}

	public function isGroupRandom() {
		return Mage::getStoreConfig(self::XML_GROUP_RANDOM);
	}
	
	public function getDefaultProductId() {
		return Mage::getStoreConfig(self::XML_GROUP_DEFAULT_PRODUCT_ID);
	}
	
	public function getDefaultStaticBlock() {
		return Mage::getStoreConfig(self::XML_GROUP_DEFAULT_STATIC_BLOCK);
	}

}