<?php

$installer = $this;
$installer->startSetup();

$setup = new Mage_Eav_Model_Entity_Setup('core_setup');

$entityTypeId     = $setup->getEntityTypeId('customer');
$attributeSetId   = $setup->getDefaultAttributeSetId($entityTypeId);
$attributeGroupId = $setup->getDefaultAttributeGroupId($entityTypeId, $attributeSetId);

$setup->addAttribute('customer', 'manufacturer_customer', array(
    'input'         => 'text',
    'group'			=> 'Default',
    'type'          => 'int',
    'input'			=> 'select',
    'source'        => 'providerstocks/attribute_manufacturer',
    'label'         => 'Manufacturer',
    'visible'       => 1,
    'required'      => 0,
    'user_defined' 	=> 1,
));

$setup->addAttributeToGroup(
	$entityTypeId,
	$attributeSetId,
	$attributeGroupId,
	'manufacturer_customer',
	'999'  //sort_order
);

$oAttribute = Mage::getSingleton('eav/config')->getAttribute('customer', 'manufacturer_customer');
$oAttribute->setData('used_in_forms', array('adminhtml_customer'));
$oAttribute->save();

$setup->endSetup();