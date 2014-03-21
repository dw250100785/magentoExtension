<?php
class Bighippo_Restext_Model_Jsonoutput extends Mage_Core_Model_Abstract
{
    public function getRandomProducts($maxCount = 5)
    {
        $randProducts = array();
        $allProducts = array();
        $productCollection = Mage::getModel('catalog/product')
            ->getCollection()
            ->addAttributeToSelect('*')
            ->getItems();
        foreach ($productCollection as $id => $data)
        {
            $allProducts[] = $data;
        }
        $productIds = array_keys($allProducts);
        $totalProductIds = count($productIds);
        for ($i=0; $i<$maxCount; $i++)
        {
            $randIndex = rand(0,$totalProductIds);
            $randProductId = $productIds[$randIndex];
             $randProducts[] = $allProducts[$randProductId];
        }
        return $randProducts;
    }
}
?>