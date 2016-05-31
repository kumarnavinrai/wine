<?php

$installer = $this;

$installer->startSetup();

$setup = new Mage_Eav_Model_Entity_Setup('core_setup');
 
$setup->addAttribute('catalog_product', 'product_shipping', array(
        'group'             => 'Shipping Method',
		'label'             => 'Select Shipping',
		'note'              => '',
        'type'              => 'varchar', //backend_type
		'input'             => 'multiselect', //frontend_input
		'frontend_class' => '',
		'source' => 'eav/entity_attribute_coption', //this is custom model which has been created in - C:\wamp\www\mag2\app\code\core\Mage\Eav\Model\Entity\Attribute
        'backend'           => 'eav/entity_attribute_backend_array', //this is the backend model essential for multiselect other wise oher attributes like select got saved easyly
        'frontend'          => '',
        'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE,
		'required'          => true,
        'visible_on_front'  => false,
        'apply_to'          => 'simple',
        'is_configurable'   => false,
        'used_in_product_listing' => false,
        'sort_order'        => 6,
    ));
 

$installer->endSetup();

/*
$installer = $this;
 
$installer->startSetup();
 
$installer->addAttribute('catalog_product', 'attribute_code', array(
 'type' => 'int',
 'backend' => '',
 'frontend' => '',
 'label' => 'attribute_label',
 'input' => 'text',
 'class' => '',
 'source' => '',
 'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
 'visible' => false,
 'required' => false,
 'user_defined' => false,
 'default' => '',
 'searchable' => false,
 'filterable' => false,
 'comparable' => false,
 'visible_on_front' => false,
 'unique' => false,
 'apply_to' => '',
 'is_configurable' => false
));
 
$installer->endSetup();

*/