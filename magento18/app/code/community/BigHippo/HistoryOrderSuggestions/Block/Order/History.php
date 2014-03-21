<?php
/**
 * Sales order history block
 *
 * @category   Mage
 * @package    Mage_Sales
 * @author      Magento Core Team <core@magentocommerce.com>
 */

class BigHippo_HistoryOrderSuggestions_Block_Order_History extends Mage_Core_Block_Template
{
	const GROUP_SELECTION_GROUP = 'recommendation_group';
	
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('bighippo/historyordersuggestions/order/history.phtml');

		$orders = Mage::helper('bighippo_historyordersuggestions/order')->getAllItems();

        $this->setOrders($orders);

        Mage::app()->getFrontController()->getAction()->getLayout()->getBlock('root')->setHeaderTitle(Mage::helper('sales')->__('History Order Suggestions'));
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        $pager = $this->getLayout()->createBlock('page/html_pager', 'sales.order.history.pager')
            ->setCollection($this->getOrders());
        $this->setChild('pager', $pager);
        $this->getOrders()->load();
        return $this;
    }

    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    public function getOrderViewUrl($order)
    {
        return $this->getUrl('*/*/view', array('order_id' => $order->getId()));
    }

    public function getProductViewUrl($item)
    {
        return $this->getUrl('catalog/product/view', array('id' => $item->getProductId()));
    }
    
    public function getBackUrl()
    {
        return $this->getUrl('customer/account/');
    }
	
	public function getProductSuggestion() {
		$items = Mage::helper('bighippo_historyordersuggestions/order')->getAllItems();
		$availableGroups = Mage::helper('bighippo_historyordersuggestions/group')->getAvailableGroups($items);

		// Select from group
		for($i=0;$i<count($availableGroups); $i++) {
			$groupIndex = $i;
			if(Mage::helper('bighippo_historyordersuggestions')->isGroupRandom()) {
				$groupIndex = array_rand($availableGroups);
			}
			$productGroup = $availableGroups[$groupIndex];
			$product = $this->selectProduct($productGroup);
			if($product && $product->getId()) {
				return $product;
			}
		}

		// Use default product id
		$productId = Mage::helper('bighippo_historyordersuggestions')->getDefaultProductId();
		if($productId) {
			return Mage::getModel('catalog/product')->load($productId);
		}
		 
		return null;
	}
	
	public function getSuggestionBlock() {
		return Mage::helper('bighippo_historyordersuggestions')->getDefaultStaticBlock();
	}
	
	private function selectProduct($productGroup) {
		$groupAttribute = Mage::helper('bighippo_historyordersuggestions')->getGroupAttribute();
		$collection = Mage::getModel('catalog/product')->getCollection();
		$collection->addAttributeToSelect('id');
		$collection->addAttributeToFilter($groupAttribute, $productGroup);
		Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($collection);
		$productIds = $collection->getAllIds();

		if(count($productIds)) {
			$productId = array_rand($productIds);
			return Mage::getModel('catalog/product')->load($productIds[$productId]);			
		}
	}
		

}
