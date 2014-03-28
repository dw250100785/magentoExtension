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
class BigHippo_Icloset_WebserviceController extends Mage_Core_Controller_Front_Action
{

    public function modelAction() {
        $icloset = Mage::getModel("icloset/icloset");
        $storeId = Mage::app()->getStore()->getStoreId();
        $mode = "production";
        $device = "";
        $scope = strtolower($this->getRequest()->getParam("scope"));

        $baseUrl = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA)."images".DS;
        $xmlPath = $device . constant(get_class($icloset).'::'. "XML_PATH_PIC_".strtoupper($mode)."_MODEL_".strtoupper($scope)."1");
        $json["model"] = array("id" => "0" , "image" => $baseUrl.Mage::getStoreConfig($xmlPath, $storeId));

        header('Cache-Control: no-cache, must-revalidate');
        header('Content-type: application/json');
        header('Access-Control-Allow-Origin: *');       
        echo json_encode($json);    
    }
    
    public function categoriesAction()
    {
        $icloset = Mage::getModel("icloset/icloset");
        $storeId = Mage::app()->getStore()->getStoreId();
        $mode = "production";
        $device = "";
        $scope = strtolower($this->getRequest()->getParam("scope"));
                
        //Get parent category
        $xmlPath = constant(get_class($icloset).'::'. "XML_PATH_".strtoupper($mode)."_".strtoupper($scope)."_CATEGORYID");
        $mainCategoryId = Mage::getStoreConfig($xmlPath, $storeId);
        $mainCategory =  Mage::getModel("catalog/category")->load($mainCategoryId);

        $helper = Mage::helper("icloset/json");

        $json = Array();        
        foreach($mainCategory->getChildrenCategories() as $cat) {
            $json["categories"][] = $helper->category($cat);
        }
        
        header('Cache-Control: no-cache, must-revalidate');
        header('Content-type: application/json');       
        header('Access-Control-Allow-Origin: *');
        echo json_encode($json);    
    }
    
    public function productsAction()
    {
        $icloset = Mage::getModel("icloset/icloset");
        $storeId = Mage::app()->getStore()->getStoreId();
        $mode = "production";
        $device = "";
        $page = $this->getRequest()->getParam("page");
        $pageSize = $this->getRequest()->getParam("pageSize");
        $scope = strtolower($this->getRequest()->getParam("scope"));
        $categoryId = $this->getRequest()->getParam("categoryId");

        $json = Array();
        $products = Array();
        $total = 0;

        //Products by category
        if($categoryId != "") {
            $allProducts = Mage::helper("icloset")->loadProducts($categoryId, $page, $pageSize);
            $total = $allProducts->count();
        }       

        //Load all products in categories
        if($categoryId == "") {
            $xmlPath = constant(get_class($icloset).'::'. "XML_PATH_".strtoupper($mode)."_".strtoupper($scope)."_CATEGORYID");
            $mainCategoryId = Mage::getStoreConfig($xmlPath, $storeId);
            $mainCategory =  Mage::getModel("catalog/category")->load($mainCategoryId);
            $allProducts = Array();
            foreach($mainCategory->getChildrenCategories() as $cat) {
                 
                $productCollection = Mage::helper("icloset")->loadProducts($cat->getId(), null, null);
                $total = $total + $productCollection->count();
                
                //Load products
                foreach($productCollection as $product) {
                    $allProducts[] = $product;  
                }
                
            }
            
        }
        
        //Order
        $orderProducts = Array();
        $i = 0;
        foreach($allProducts as $product) {
            $orderProducts[$i++] = $product;   
        }
        ksort($orderProducts);
        
        //Make incremental by 1 index
        $reindexProducts = Array();
        foreach($orderProducts as $product) {
            $reindexProducts[] = $product;  
        }

        //Paginate
        $index = ($page-1) * $pageSize;
        for($i = 0; $i < $pageSize; $i++) {
            if(isset($reindexProducts[$i + $index])) {
                $products[] = $reindexProducts[$i + $index];
            }
        }

        //Get total in collection           
        $json["total"] = $total;
        
        
        $helper = Mage::helper("icloset/json");
        $json["products"] = Array();
        foreach($products as $product) {
            $product = Mage::getModel("catalog/product")->load($product->getId());
            $json["products"][] = $helper->product($product);
        }
        
        header('Cache-Control: no-cache, must-revalidate');
        header('Content-type: application/json');       
        header('Access-Control-Allow-Origin: *');
        echo json_encode($json);    
    }

	public function buyAction() {
		$session = Mage::getSingleton("core/session",  array("name"=>"frontend"));

		// Adding products to cart
		$cart = Mage::getModel("checkout/cart");
		$cart->truncate();
		$productSkus = explode(",", $session->getData("skus"));
		foreach($productSkus as $productSku) {
			if($productSku) {
				$product = Mage::getModel("catalog/product")->loadByAttribute('sku', $productSku);
				$productAdded = false;
				if($product->getTypeId() == "configurable") {
    				$conf = Mage::getModel('catalog/product_type_configurable')->setProduct($product);
					$simpleProducts = $conf->getUsedProductCollection()->addAttributeToSelect('*')->addFilterByRequiredOptions();
					foreach($simpleProducts as $simpleProduct){
						if(! $productAdded) {
							$confAttributes = $product->getTypeInstance(true)->getConfigurableAttributes($product);
							$options = array();
							$options['product'] = (int)$product->getId();
							foreach($confAttributes as $attribute) {
								$attribute = Mage::getModel('catalog/product')->getResource()->getAttribute($attribute->getAttributeId()); 
								$attributeId = $attribute->getAttributeId();
								$attributeCode = $attribute->getAttributeCode();
								$simpleProductValue = $simpleProduct->getData($attributeCode);
								$options["super_attribute"][$attributeId] = (int)$simpleProductValue;
							}
							$options['qty'] = 1;
							try {
								$cart->addProduct($product, $options);
							} catch(\Exception $e) {
								Mage::logException($e);
								die(var_dump($e));
							}
							$productAdded = true;
						}
					}
				}
				else {
					try {
					$cart->addProduct($product->getId(), $qty);                                
					} catch(\Exception $e) {
						Mage::logException($e);
								die(var_dump($e));
					}
				}
			}
		}
		$cart->save();
		
		$this->getResponse()->setRedirect(Mage::getUrl('checkout/cart'));
	}
	
}