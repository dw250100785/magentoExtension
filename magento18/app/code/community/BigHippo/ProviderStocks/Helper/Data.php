<?php
class BigHippo_ProviderStocks_Helper_Data extends Mage_Core_Helper_Abstract
{
	protected $_product;
	
	public function stockHtml($_product) {
		$html = "";
		if($this->isAllow($_product->getId())) {
			$html = "<span style='font-weight:bold'>";
			$html .= $this->__('Current stock: ') . $this->getProductStock($_product->getId());
			$html .="</span><br>";
		}
		return $html;
	}
	
	public function isAllow($productId) {
		if(Mage::getSingleton('customer/session')->isLoggedIn()) {
			
			if($this->getManufacturer($productId) != null) {
				if($this->getCustomerLogged()->getData("manufacturer_customer") == $this->getManufacturer($productId)) {
					return true;
				}
			}
		}
		return false;
	}
	
	private function getProduct($productId) {
		if($this->_product && $this->_product->getId() == $productId) {
			return $this->_product;
		}
		else {
			$this->_product = Mage::getModel('catalog/product')->load($productId);
			return $this->_product;
		}
	}
	
	private function getProductStock($productId) {
		$_product = $this->getProduct($productId);
		return floor($_product->getStockItem()->getQty());	
	}

	private function getCustomerLogged() {
		return Mage::getSingleton('customer/session')->getCustomer();
	}

	private function getManufacturer($productId) {
		$_product = $this->getProduct($productId);
		return $_product->getData("manufacturer");	
	}
	
	
}