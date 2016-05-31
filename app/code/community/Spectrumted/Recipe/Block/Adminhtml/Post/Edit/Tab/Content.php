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
class Spectrumted_Recipe_Block_Adminhtml_Post_Edit_Tab_Content extends Mage_Adminhtml_Block_Widget_Form //Spectrumted_Recipe_Block_Adminhtml_Form { {
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

        $form->setFieldNameSuffix('content_data');

        $isElementDisabled = false;

        $wysiwygConfig = Mage::getSingleton('cms/wysiwyg_config')->getConfig(
            array('tab_id' => $this->getTabId())
        );

        $useSummaryField =$fieldset->addField('use_summary', 'select', array(
            'label' => Mage::helper('spectrumted_recipe')->__('Use Summary'),
            'index' => 'use_summary',
            'name' => 'use_summary',
            'note'      => 'If not, you can use a WordPress style <&#33;--more--> break tag in content. <br/> Be sure to hide the WYSIWYG Editor and edit in HTML for this to work.',
            'values' => array(
                array(
                    'value' => '1',
                    'label' => Mage::helper('spectrumted_recipe')->__('True'),
                ),
                array(
                    'value' => '0',
                    'label' => Mage::helper('spectrumted_recipe')->__('False')
                )
            ),
        ));
        $summaryField = $fieldset->addField('summary', 'editor', array(
            'name'      => 'summary',
            'style'     => 'min-width:615px;',
            'required'  => false,
            'label'     => Mage::helper('cms')->__('Summary'),
            'title'     => Mage::helper('cms')->__('Summary'),
            'disabled'  => $isElementDisabled,
            'config'    => $wysiwygConfig
        ));

        $contentRec = $fieldset->addField('content_html', 'editor', array(
            'name'      => 'content_html',
            'style'     => 'min-width:615px;',
           // 'style'     => 'height:36em;',
            'required'  => true,
            'label'     => Mage::helper('cms')->__('Ingredients'),
            'title'     => Mage::helper('cms')->__('Ingredients'),
            'disabled'  => $isElementDisabled,
            'config'    => $wysiwygConfig,
        ));
		
		$maju = $fieldset->addField('description', 'editor', array(
            'name'      => 'description',
            'style'     => 'min-width:615px;',
           // 'style'     => 'height:36em;',
            'required'  => false,
            'label'     => Mage::helper('cms')->__('description'),
            'title'     => Mage::helper('cms')->__('How to make recipe'),
            'disabled'  => $isElementDisabled,
            'config'    => $wysiwygConfig,
        ));
		
		
		$fieldset->addField('image', 'image', array(

          'label'     => Mage::helper('cms')->__('Upload Image'),

          'required'  => false,

          'name'      => 'image',

		));



        $this->setChild('form_after', $this->getLayout()->createBlock('adminhtml/widget_form_element_dependence')
                ->addFieldMap($useSummaryField->getHtmlId(), $useSummaryField->getName(),$maju->getName())
                ->addFieldMap($summaryField->getHtmlId(), $summaryField->getName(),$maju->getName())
                ->addFieldDependence(
                    $summaryField->getName(),
                    $useSummaryField->getName(),
                    '1',
					$maju->getName()
                )

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
		return array(
			'store_ids'    => array(Mage_Catalog_Model_Abstract::DEFAULT_STORE_ID),
			'category_ids' => array(0),
			'status'	   => Spectrumted_Recipe_Model_Post::STATUS_DRAFT
		);
	}

}