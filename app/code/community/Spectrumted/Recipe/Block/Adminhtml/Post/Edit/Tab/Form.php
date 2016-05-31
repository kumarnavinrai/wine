<?php

/**
 * Spectrumted (Neo Industries Pty Ltd)
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to Neo Industries Pty LTD Non-Distributable Software Modification License (NDSML)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.spectrumted.com/legal/licenses/NDSM.html
 * If the license is not included with the package or for any other reason, 
 * you did not receive your licence please send an email to 
 * license@spectrumted.com so we can send you a copy immediately.
 *
 * This software comes with no warrenty of any kind. By Using this software, the user agrees to hold 
 * Neo Industries Pty Ltd harmless of any damage it may cause.
 *
 * @category    modules
 * @module      Spectrumted_Recipe
 * @copyright   Copyright (c) 2011 Neo Industries Pty Ltd (http://www.spectrumted.com)
 * @license     http://www.spectrumted.com/  Non-Distributable Software Modification License(NDSML 1.0)
 */
class Spectrumted_Recipe_Block_Adminhtml_Post_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form //Spectrumted_Recipe_Block_Adminhtml_Form { {
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('main_form_tab');
        $this->setTabId('main_form_tab');
    }

    protected function _prepareForm() {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $form->setDataObject(Mage::registry('current_post'));
        $fieldset = $form->addFieldset('spectrumted_recipe_form', array('legend' => Mage::helper('spectrumted_recipe')->__('Post Configuration')));

        $form->setFieldNameSuffix('spectrumted_recipe');

        $fieldset->addField('title', 'text', array(
            'label' => Mage::helper('spectrumted_recipe')->__('Post Title'),
            'class' => 'required-entry',
            'name' => 'title',
            'required' => true,
            'index' => 'title',
        ));

        $fieldset->addField('cms_identifier', 'text', array(
            'label' => Mage::helper('spectrumted_recipe')->__('URL Key'),
            'class' => 'required-entry',
            'name' => 'cms_identifier',
            'required' => true,
            'index' => 'cms_identifier',
			'note'	=> Mage::helper('spectrumted_recipe')->__('This will be appended to the end of the url  <br/> eg. if  "my-post" was entered it could be found at http://www.youstore.com.au/my-post')
        )); 
        $fieldset->addField('status', 'select', array(
            'label' => Mage::helper('spectrumted_recipe')->__('Status'),
            'index' => 'status',
            'name' => 'status',
            'values' => Mage::getSingleton('spectrumted_recipe/attribute_source_status')->getAllOptions()
        ));
        if (Mage::getStoreConfig(Spectrumted_Recipe_Helper_Data::XPATH_CUSTOMER_GROUP_FILTERING)) {
            $customerGroupOptions = Mage::getResourceModel('customer/group_collection')->toOptionArray();
            array_unshift($customerGroupOptions, array('value' => "", 'label' => '--All Customer Groups--'));
            $fieldset->addField('customer_group_ids', 'multiselect', array(
                'name'      => 'customer_group_ids[]',
                'label'     => Mage::helper('catalogrule')->__('Customer Groups'),
                'title'     => Mage::helper('catalogrule')->__('Customer Groups'),
                'required'  => false,
                'values'    => $customerGroupOptions,
                'note'      => 'Hold CTRL to select multiple'
            ));
        }
        else{
            $fieldset->addField('customer_group_ids', 'hidden', array(
                'name' => 'customer_group_ids[]',
                'value' => ''
            ));
        }
        $isElementDisabled = false;
        
        //$renderer = $this->getLayout()->createBlock('adminhtml/widget_form_renderer_fieldset_element')
        //                              ->setTemplate('spectrumted/recipe/edit/form/renderer/tag.phtml');
        //$tags_field =  $fieldset->addField('tag_ids', 'text', array(
        //    'name'      => 'tag_ids',
        //    'label'     => Mage::helper('cms')->__('Tags'),
        //    'title'     => Mage::helper('cms')->__('Tags'),
        //    'disabled'  => $isElementDisabled,
        //    'json_list' => "[ 'test', 'another' ]"
        //));
        //$tags_field->setRenderer($renderer);



        if (!Mage::app()->isSingleStoreMode()) {
            $fieldset->addField('store_ids', 'multiselect', array(
                'name' => 'store_ids[]',
                'label' => Mage::helper('cms')->__('Store View'),
                'title' => Mage::helper('cms')->__('Store View'),
                'required' => true,
                'values' => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
                'disabled' => $isElementDisabled,
                 'note'      => 'Hold CTRL to select multiple'
                ));
        } else {
            $fieldset->addField('store_ids', 'hidden', array(
                'name' => 'store_ids[]',
                'value' => Mage::app()->getStore(true)->getId()
            ));
        }
        //TODO: replace the categories
        $fieldset->addField('category_ids', 'multiselect', array(
            'name' => 'category_ids[]',
            'label' => Mage::helper('spectrumted_recipe')->__('Categories'),
            'title' => Mage::helper('spectrumted_recipe')->__('Categories'),
            'required' => true,
			'class' => 'required-entry',
			'values' => Mage::helper('spectrumted_recipe')->getCategoryOptionValues(true),
            'disabled' => $isElementDisabled,
            'index' => 'category_ids',
             'note'      => 'Hold CTRL to select multiple'
        ));
        
        $wysiwygConfig = Mage::getSingleton('cms/wysiwyg_config')->getConfig(
            array('tab_id' => $this->getTabId())
        );

        if (Mage::getSingleton('adminhtml/session')->getRecipePostData()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getRecipePostData());
            Mage::getSingleton('adminhtml/session')->setRecipePostData(null);
		} elseif (Mage::registry('current_post')) {
			$data =  (!Mage::registry('current_post')->getId())? $this->getDefaultValues() : Mage::registry('current_post')->getData();
			$form->setValues($data);
		}

        return parent::_prepareForm();

    }
	
	function getDefaultValues(){
        $defaultValues = array(
            'store_ids'    => array(Mage_Catalog_Model_Abstract::DEFAULT_STORE_ID),
            'category_ids' => array(0),
            'status'	   => Spectrumted_Recipe_Model_Post::STATUS_DRAFT
        );
        if (Mage::app()->isSingleStoreMode()){
            $defaultValues['store_ids'] = Mage_Catalog_Model_Abstract::DEFAULT_STORE_ID;
        }
		return $defaultValues;
	}

}