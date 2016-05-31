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
class Spectrumted_Recipe_Model_Post
extends  Spectrumted_Recipe_Model_Abstract_Store
implements Spectrumted_Recipe_Model_IFrontendRoute
{
    
    const ENTITY = "spectrumted_recipe_post";
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DRAFT = 2;
    const ROUTE_ACTION_NAME = 'index';
    const ROUTE_CONTROLLER_NAME = 'post';
    const CACHE_TAG = 'spectrumted_recipe_post';

    const DEFAULT_READ_MORE_TEXT = 'More...';
    
    public function _construct() {
        $this->_init('spectrumted_recipe/post');
    }
    
    
    protected $_tags;
    protected function _loadTags(){
        if ($this->getTagIds() != NULL && !$this->_tags){
            $tagCollection = Mage::getModel('spectrumted_recipe/tag')->getCollection()->addFieldToFilter('entity_id', array('in'=> $this->getTagIds()));
            $this->_tags = array();
            foreach($tagCollection as $tag){
                $this->_tags[] = $tag->getName();
            }
        }
        return $this->_tags;
    }  
    
    private $_template_processor;
    
    private function _getProcessor(){
        if ($this->_template_processor == NULL){
            $helper = Mage::helper('cms');
            $this->_template_processor = $helper->getPageTemplateProcessor();
        }
        return $this->_template_processor;
    }
    
    private function _parseHtml($html){
        return $this->_getProcessor()->filter($html);
    }

    function getContentHtml(){
        return $this->_parseHtml(parent::getContentHtml());
    }

    function getSummary(){
        $summary = '';
        if (!$this->getUseSummary()){
            $summaryParts = explode('<!--more', parent::getContentHtml(), 2);
            $summary = $summaryParts[0];
        }
        else{
            $summary = parent::getSummary();
        }
        return $this->_parseHtml($summary);
    }

    function getTagIds(){
        $tags = parent::getTagIds();
        if (is_string($tags)){
            $tags = explode(",",$tags);
        }
        return $tags;
    }
    
    function getTags(){
        if (parent::getTags() == NULL){
            parent::setTags(implode(",",$this->_loadTags()));
        }
        return parent::getTags();
    }
	
    private $_category_collection;
    function  _prepareCategoryCollection(){

		if ($this->getCategoryIds()){
            $this->_category_collection = Mage::getModel('spectrumted_recipe/category')
                    ->getCollection()
                    ->addStoreFilter()
                    ->addIdFilter($this->getCategoryIds())
                    ->addStatusFilter(Spectrumted_Recipe_Model_Post::STATUS_ACTIVE)
                    ->setOrder('created_at', Mage_Core_Model_Mysql4_Collection_Abstract::SORT_ORDER_DESC);
       }
       else
       {
           $this->_category_collection = new Varien_Data_Collection();
       }
       return $this->_category_collection;
    }

	function getCategories(){
        if ($this->_category_collection == null){
            $this->_prepareCategoryCollection();
        }
        return $this->_category_collection;
	}
    
    function getCategoryIds(){
        $categoryIds = parent::getCategoryIds();
        if (is_string($categoryIds)){
            //convert to array;
            $categoryIds = array_filter(array_unique(explode(",", $categoryIds)));
        }
        return $categoryIds;
    }
    
    function getCustomerGroupIds(){
        $customerGroupsIds = parent::getCustomerGroupIds();
        if (is_string($customerGroupsIds)){
            //convert to array;
            $customerGroupsIds = array_filter(array_unique(explode(",", $customerGroupsIds)));
        }
        return $customerGroupsIds;
    }
    

    function _beforeSave(){
        if ($this->getCategoryIds() != NULL && is_array($this->getCategoryIds())){
            $this->setCategoryIds(implode(",", $this->getCategoryIds()));
        }
        if ($this->getCustomerGroupIds() != NULL && is_array($this->getCustomerGroupIds())){
            $this->setCustomerGroupIds(implode(",", $this->getCustomerGroupIds()));
        }
        parent::_beforeSave();
    }

    function getReadMoreText(){
        if (!parent::getData('read_more_text')){
            $readMoreText = self::DEFAULT_READ_MORE_TEXT;
            if (!$this->getUseSummary()){
                $matches = array();
                preg_match('/<!--more(.*?)-->/', parent::getContentHtml(), $matches);
                //the first match will be the whole content_html, the second match will be the interior of
                // the regex (.*?)
                if (count($matches) > 1){
                    $readMoreText = trim($matches[1]);
                }
            }
            parent::setData('read_more_text', $readMoreText);
        }
        return parent::getData('read_more_text');

    }
    
    function getReadMoreUrl($noRedirect = false){
        $params = array();
        
        $categoryCmsIdentifier = null;
        if ($category = Mage::registry('current_recipe_category')){
            $categoryCmsIdentifier = "/" . $category->getCmsIdentifier();
            $params['category_id'] = $category->getId();//$this->getCurrentCategoryId();
        }
        if (!$this->getCmsIdentifier() || $noRedirect){
            $urlKey = 'spectrumted_recipe/post/index';
            $params['id'] = $this->getId();
        }
        else{
            $urlKey = Mage::helper('spectrumted_recipe')->getFrontendName() .$categoryCmsIdentifier ."/". $this->getCmsIdentifier();
            $params = null;
        }
        $url = Mage::getModel('core/url')->getUrl($urlKey, $params);
        return $url;
    }
    

    private $_comments_collection;
    function  _prepareCommentCollection(){
       $this->_comments_collection = Mage::getModel('spectrumted_recipe/comment')
                ->getCollection()
                ->addFieldToFilter('post_id', $this->getId())
                ->addStatusFilter(Spectrumted_Recipe_Model_Post::STATUS_ACTIVE)
                ->setOrder('created_at', Mage_Core_Model_Mysql4_Collection_Abstract::SORT_ORDER_DESC);
       return $this->_comments_collection;
    }

    function getComments(){
        if ($this->_comments_collection == null){
            $this->_prepareCommentCollection();
        }
        return $this->_comments_collection;
    }
    /**
     * Resets all data in object
     * so after another load it will be complete new object
     *
     * @return Spectrumted_Recipe_Block_Post
     */
    public function reset()
    {
        $this->unsetData();
        return $this;
    }
    

    function getRouteControllerName(){
        return self::ROUTE_CONTROLLER_NAME;
    }
    function getRouteActionName(){
        return self::ROUTE_ACTION_NAME;
    }
    public function getRouteParams($id){
        return array('id' => $id);
    }
    public function checkIdentifier($identifier, $storeId){
        return $this->getResource()->checkIdentifier($identifier, $storeId);
    }
}