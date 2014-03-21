<?php
/**
 * Catalog category file attribute backend model
 *
 */
class BigHippo_ProviderStocks_Model_Attribute_Manufacturer extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{
    /**
     * Retrieve all options array
     *
     * @return array
     */
    public function getAllOptions()
    {
        if (is_null($this->_options)) {
            $this->_options = array();
			$this->_options[] = array(
                    'label' => '',
                    'value' =>  ''
                );
	 	  	$attribute = Mage::getSingleton('eav/config')->getAttribute('catalog_product', 'manufacturer');
			if ($attribute->usesSource()) {
			    $options = $attribute->getSource()->getAllOptions(false);
				foreach($options as $option) {
					$this->_options[] = array(
		                    'label' => $option['label'],
		                    'value' =>  $option['value']
		                );
				}
			}
        }
        return $this->_options;
    }

}
