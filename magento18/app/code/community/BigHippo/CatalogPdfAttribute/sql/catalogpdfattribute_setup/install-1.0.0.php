<?php

$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */

$installer->startSetup();

$attrCode = 'pdf_file';
$attrGroupName = 'General';
$attrLabel = 'Pdf Document';
$attrNote = '';

$objCatalogEavSetup = Mage::getResourceModel('catalog/eav_mysql4_setup', 'core_setup');
$attrIdTest = $objCatalogEavSetup->getAttributeId(Mage_Catalog_Model_Product::ENTITY, $attrCode);

if ($attrIdTest === false) {
    $objCatalogEavSetup->addAttribute(Mage_Catalog_Model_Product::ENTITY, $attrCode, array(
        'group' => $attrGroupName,
        'sort_order' => 7,
        'type' => 'varchar',
        'frontend' => '',
        //'backend_model' => 'catalogpdfattribute/attribute_backend_file',
        'backend' => 'catalogpdfattribute/attribute_backend_file',
        'label' => $attrLabel,
        'note' => $attrNote,
        'input' => 'file',
        'class' => '',
        'source' => '',
        'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'visible' => true,
        'required' => false,
        'user_defined' => true,
        'default' => '0',
        'visible_on_front' => false,
        'unique' => false,
        'is_configurable' => false,
        'used_for_promo_rules' => true
    ));
}

$installer->endSetup();