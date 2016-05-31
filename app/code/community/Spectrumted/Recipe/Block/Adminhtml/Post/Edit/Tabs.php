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
class Spectrumted_Recipe_Block_Adminhtml_Post_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
   public function __construct()
   {
        parent::__construct();
        $this->setId('spectrumted_recipe_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle('<img src="' . $this->getSkinUrl('images/spectrumted/recipe/spectrum-ted.png') . '"/><br />'.Mage::helper('spectrumted_recipe')->__('Post'));
        $this->setHeader(Mage::helper('spectrumted_recipe')->__('Post'));
   }


   protected function _beforeToHtml()
   {
        $this->addTab('form_section', array(
        			  'label'     => Mage::helper('spectrumted_recipe')->__('Post Information'),
        			  'title'     => Mage::helper('spectrumted_recipe')->__('Post Information'),
         			  'content'   => $this->getLayout()->createBlock('spectrumted_recipe/adminhtml_post_edit_tab_form')
                                                ->toHtml(),
        ));
       $this->addTab('content_section', array(
           'label'     => Mage::helper('spectrumted_recipe')->__('Content'),
           'title'     => Mage::helper('spectrumted_recipe')->__('Content'),
           'content'   => $this->getLayout()->createBlock('spectrumted_recipe/adminhtml_post_edit_tab_content')
                   ->toHtml(),
       ));
        $this->addTab('publishing_section', array(
        			  'label'     => Mage::helper('spectrumted_recipe')->__('Publishing Configuration'),
        			  'title'     => Mage::helper('spectrumted_recipe')->__('Publishing Configuration'),
         			  'content'   => $this->getLayout()->createBlock('spectrumted_recipe/adminhtml_post_edit_tab_publishing')
                                                ->toHtml(),
        ));
       $this->addTab('design_section', array(
           'label'     => Mage::helper('spectrumted_recipe')->__('Design'),
           'title'     => Mage::helper('spectrumted_recipe')->__('Design'),
           'content'   => $this->getLayout()->createBlock('spectrumted_recipe/adminhtml_post_edit_tab_design')
                   ->toHtml(),
       ));
        $this->addTab('meta_section', array(
        			  'label'     => Mage::helper('spectrumted_recipe')->__('Meta Data'),
        			  'title'     => Mage::helper('spectrumted_recipe')->__('Meta Data'),
         			  'content'   => $this->getLayout()->createBlock('spectrumted_recipe/adminhtml_post_edit_tab_meta')
                                                ->toHtml(),
        ));

       return parent::_beforeToHtml();
   }
     public function getPost()
    {
        return $this->getData('current_post');
    }

} 