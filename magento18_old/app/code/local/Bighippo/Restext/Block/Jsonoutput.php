<?php
class Bighippo_Restext_Block_Jsonoutput extends Mage_Core_Block_Template
{
	protected function _toHtml()
    {
        $randProdModel = Mage::getModel('Bighippo_Restext/Jsonouput');
        $randProducts = $randProdModel->getRandomProducts();
        $html = "<ul>";
        foreach ($randProducts as $product)
        {
            $name = $product->getName();
            $price = number_format($product->getPrice(), 2);
            $imageLink = $this->helper('catalog/image')
                ->init($product, 'thumbnail')->resize(100,100);
            $productLink = $this->helper('catalog/product')->getProductUrl($product);
            $html .= "
                <p>
                    <a href='$productLink'><img src='$imageLink' alt='$name'/></a><br/>
					$name <br/>
                     $price
                </p>";
        }
         $html .= "<ul>";
        return $html;
    }
}

?>