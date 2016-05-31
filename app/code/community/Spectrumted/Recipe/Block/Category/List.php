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
class Spectrumted_Recipe_Block_Category_List
extends Mage_Core_Block_Template 
implements Mage_Widget_Block_Interface
{	
    function _construct(){
        $this->setTemplate('spectrumted/recipe/category/list.phtml');
        
    }
    private $_collection;
    function  _prepareCollection(){

       $this->_collection = Mage::getModel('spectrumted_recipe/category')
                ->getCollection()
                ->addStoreFilter()
                ->addStatusFilter(Spectrumted_Recipe_Model_Post::STATUS_ACTIVE)
                ->setOrder('created_at', Mage_Core_Model_Mysql4_Collection_Abstract::SORT_ORDER_DESC);
        if ($this->_showDrafts()) {
            $this->_collection->addStatusFilter(Spectrumted_Recipe_Model_Post::STATUS_DRAFT);
        }

       if ($this->getShowPopulatedOnly() || Mage::getStoreConfig(Spectrumted_Recipe_Helper_Data::XPATH_CATEGORY_SHOW_POPULATED) ){
           $this->_collection->addPopulatedOnlyFilter();
       }
       if (Mage::getStoreConfig(Spectrumted_Recipe_Helper_Data::XPATH_CONFIG_CUSTOMER_PREFERENCES_ENABLE)){
            $allCategories = Mage::getModel('spectrumted_recipe/category');
            $allCategories->setListUrl(Mage::getUrl('recipe/index/all'));
            $allCategories->setName(Mage::helper('spectrumted_recipe')->__('All Categories'));
            $this->_collection->addItem($allCategories);
       }
       return $this->_collection;
    }
    
    function _showDrafts() {
        return Mage::helper('spectrumted_recipe')->isIpPermitted();
    }

    function getCollection(){
        if ($this->_collection == null){
            $this->_prepareCollection();
        }
        return $this->_collection;
    }
    
    function _toHtml(){
        $this->_prepareCollection();
        return parent::_toHtml();
    }   
}