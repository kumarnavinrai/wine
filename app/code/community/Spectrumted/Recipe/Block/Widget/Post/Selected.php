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
class Spectrumted_Recipe_Block_Widget_Post_Selected
extends Spectrumted_Recipe_Block_Post_Detail
implements Mage_Widget_Block_Interface
{	
    function _construct(){
        //$this->setTemplate('spectrumted/recipe/widget/post/list_block.phtml');
        $this->setTemplate('spectrumted/recipe/post/detail.phtml');
        //$this->addData(array(
        //    'cache_lifetime' => 1500,
        //    'cache_tags' => array(Spectrumted_Recipe_Model_Post::CACHE_TAG, Spectrumted_Recipe_Model_Category::CACHE_TAG),
        //));
    }

    /**
     * Returns if Drafts are permitted
     * @return mixed
     */
    protected function _showDrafts() {
        return Mage::helper('spectrumted_recipe')->isIpPermitted();
    }

    /**
     * Returns the render of the html of the block
     *
     * @return string
     */
    public function _toHtml() {
        //setTemplate is run on construct, the data 'template' doesn't get retrieved in 'getTemplate'
        //so we have to do the following before render.

        $this->_collection = Mage::getModel('spectrumted_recipe/post')
            ->getCollection()
            ->addStoreFilter()
            ->addStatusFilter(Spectrumted_Recipe_Model_Post::STATUS_ACTIVE)
            ->addPublishFilter()
            ->setPostOrder();
        if ($this->_showDrafts()){
            $this->_collection->addStatusFilter(Spectrumted_Recipe_Model_Post::STATUS_DRAFT);
        }
        if ((int)$this->getData('post_id') > 0){
            $this->_collection->addFieldToFilter('entity_id', $this->getData('post_id'));
        }
        if (!($this->_collection->getFirstItem() && $this->_collection->getFirstItem()->getId())){
            return '';
        }
        $this->setData('post_id',$this->_collection->getFirstItem()->getId());
        Mage::register('current_post',  $this->_collection->getFirstItem());

        if ($this->getData('template')){
            $this->setTemplate($this->getData('template'));
        }
        return parent::_toHtml();
    }

}