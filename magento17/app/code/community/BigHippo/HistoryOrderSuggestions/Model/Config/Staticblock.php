<?php
class BigHippo_HistoryOrderSuggestions_Model_Config_Staticblock
{
    protected $_options;

    public function toOptionArray()
    {
		
        if (!$this->_options) {
	    	$this->_options = array();
			$this->_options = array('' => '');
            $blocks = Mage::getResourceModel('cms/block_collection')
                ->load()
                ->toOptionArray();
			$this->_options = array_merge($this->_options, $blocks);
        }
		
        return $this->_options;
    }
}
