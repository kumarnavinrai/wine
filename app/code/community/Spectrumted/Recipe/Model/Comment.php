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
class Spectrumted_Recipe_Model_Comment extends  Spectrumted_Recipe_Model_Abstract_Store {
    
    const ENTITY = "spectrumted_recipe_comment";
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_NEEDS_APPROVAL = 2;
    
    public function _construct() {
        $this->_init('spectrumted_recipe/comment');
    }
    
    public function getStatusesToOptionArray(){
        return array(
            array('value' => self::STATUS_INACTIVE,
                  'label' =>  "Inactive"),
            array('value' => self::STATUS_ACTIVE,
                  'label' =>  "Active"),
            array('value' => self::STATUS_NEEDS_APPROVAL,
                  'label' =>  "Needs Approval"),
            );
        
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
        return $this->_parseHtml(parent::getSummary());
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
    
    function getCategoryIds(){
        $categoryIds = parent::getCategoryIds();
        if (is_string($categoryIds)){
            //convert to array;
            $categoryIds = array_filter(explode(",", $categoryIds));
        }
        return $categoryIds;
    }
    
    function save(){
        if ($this->getCategoryIds() != NULL && is_array($this->getCategoryIds())){
            $this->setCategoryIds(implode(",", $this->getCategoryIds()));
        }
        parent::save();
    }
    
    function getReadMoreUrl(){
        $params = array('id' => $this->getId());
        if ($this->getCurrentCategoryId()){
            $params['category_id'] = $this->getCurrentCategoryId();
        }
        return Mage::getSingleton('core/url')->getUrl('spectrumted_recipe/post/index', $params);
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
    
}
?>