<?php

/**
 * Spectrumted Austrlia (Neo Industries Pty Ltd)
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
class Spectrumted_Recipe_Block_Adminhtml_Category_Edit_Tab_Form 
extends Mage_Adminhtml_Block_Widget_Form {

    protected $attributeCollection;

    protected function _prepareLayout() {
        parent::_prepareLayout();
        if (Mage::helper('catalog')->isModuleEnabled('Mage_Cms')) {
            if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
                $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
            }
        }
    }

    protected function _prepareForm() {
        $form = new Varien_Data_Form(
                        array(
                            'id' => 'edit_form',
                            'action' => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'), 
                                                                        'store' => $this->getRequest()->getParam('store'))),
                            'method' => 'post',
                            'enctype' => 'multipart/form-data',
                        )
        );



        $form->setDataObject(Mage::registry('current_category'));
        $fieldset = $form->addFieldset('general_form', array('legend' => Mage::helper('spectrumted_recipe')->__('Item information')));


         $fieldset->addField('name', 'text', array(
          'label'     => Mage::helper('spectrumted_recipe')->__('Name'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'name',
          ));
         $fieldset->addField('cms_identifier', 'text', array(
          'label'     => Mage::helper('spectrumted_recipe')->__('URL Key'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'cms_identifier',
          ));
         $fieldset->addField('summary', 'textarea', array(
          'label'     => Mage::helper('spectrumted_recipe')->__('Summary'),
          'required'  => false,
          'name'      => 'summary',
          'note'      => 'Not seen in the frontend publicly. Visible in RSS Feed.'
          ));
         
          $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('spectrumted_recipe')->__('Status'),
          'name'      => 'status',
          'values'    => array(
            array(
            'value'     => 1,
            'label'     => Mage::helper('spectrumted_recipe')->__('Active'),
            ),
            array(
            'value'     => 0,
            'label'     => Mage::helper('spectrumted_recipe')->__('Inactive'),
            ),
          ),
          ));

        $isElementDisabled = false;
        if (!Mage::app()->isSingleStoreMode()) {
            $fieldset->addField('store_ids', 'multiselect', array(
                'name' => 'store_ids[]',
                'label' => Mage::helper('adminhtml')->__('Store View'),
                'title' => Mage::helper('adminhtml')->__('Store View'),
                'required' => true,
                'values' => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
                'disabled' => $isElementDisabled
            ));
        } else {
            $fieldset->addField('store_ids', 'hidden', array(
                'name' => 'store_ids[]',
                'value' => Mage::app()->getStore(true)->getId()
            ));

        }

        $form->addValues(Mage::registry('current_category')->getData());
        $form->setFieldNameSuffix('category');
        if (Mage::getSingleton('adminhtml/session')->getRecipeCategoryData()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getRecipeCategoryData());
            Mage::getSingleton('adminhtml/session')->setRecipeCategoryData(null);
        }

        $this->setForm($form);
        return parent::_prepareForm();
    }

}