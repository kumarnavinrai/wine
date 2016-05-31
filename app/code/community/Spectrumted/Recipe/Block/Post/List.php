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
class Spectrumted_Recipe_Block_Post_List extends Mage_Core_Block_Template implements Mage_Widget_Block_Interface {

    function _construct() {
        $this->setTemplate('spectrumted/recipe/post/list.phtml');
        $this->setSummaryBlockType('spectrumted_recipe/post_summary');
        $this->setSummaryTemplate('spectrumted/recipe/post/summary.phtml');
        //$this->addData(array(
        //    'cache_lifetime' => 1500,
        //    'cache_tags' => array(Spectrumted_Recipe_Model_Post::CACHE_TAG, Spectrumted_Recipe_Model_Category::CACHE_TAG),
        //));
    }

    //public function getCacheKeyInfo()
    //{
    //    $cacheKeyInfo = array(
    //            'BLOCK_TPL',
    //            Mage::app()->getStore()->getCode(),
    //            $this->getTemplateFile(),
    //            'template' => $this->getTemplate()
    //        );
    //     //if (Mage::registry('post')) {
    //     //   $cacheKeyInfo[] = Spectrumted_Recipe_Model_Post::CACHE_TAG . $this->getRequest()->getParam('id');
    //    //}
    //    if ($this->getRequest()->getParam('id') && $this->getRequest()->getControllerName() == "category") {
    //         $cacheKeyInfo[] = Spectrumted_Recipe_Model_Category::CACHE_TAG . $this->getRequest()->getParam('id');
    //     } 
    //     $cacheKeyInfo[] = Mage::getSingleton('customer/session')->getCustomerGroupId();
    //     return $cacheKeyInfo;
    //}  

    private $_collection;

    function getUseCustomerPreferences() {
        return filter_var($this->getData('use_customer_preferences'), FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Prepare the post collection for list display
     * @return type 
     */
    function _prepareCollection() {

        $this->_collection = Mage::getModel('spectrumted_recipe/post')
                ->getCollection()
                ->addStoreFilter()
                ->addStatusFilter(Spectrumted_Recipe_Model_Post::STATUS_ACTIVE)
                ->addPublishFilter()
                ->setPostOrder();
        if ($this->_showDrafts()) {
            $this->_collection->addStatusFilter(Spectrumted_Recipe_Model_Post::STATUS_DRAFT);
        }

        $session = Mage::getSingleton('customer/session');
        if (Mage::getStoreConfig(Spectrumted_Recipe_Helper_Data::XPATH_CUSTOMER_GROUP_FILTERING)) {
            $this->_collection->addCustomerGroupFilter($session->getCustomerGroupId());
        }
        if ($this->getCategoryId()) {
            $this->_collection->addCategoryFilter($this->getCategoryId());
        } 
        elseif ($this->getUseCustomerPreferences()) {
            if ($session->isLoggedIn() && $session->getCustomer()->getDefaultRecipeCategoryIds()) {
                try {
                    $userChosenIds = explode(",", $session->getCustomer()->getDefaultRecipeCategoryIds());
                    if (count($userChosenIds)) {
                        $this->_collection->addCategoryFilter($userChosenIds);
                    }
                } catch (Exception $ex) {
                    Mage::log($ex->getTraceAsString(), null, "spectrumted_recipe.log");
                }
            }
        }
        return $this->_collection;
    }

    function _showDrafts() {
        return Mage::helper('spectrumted_recipe')->isIpPermitted();
    }

    function getCollection() {
        if ($this->_collection == null) {
            $this->_prepareCollection();
        }
        return $this->_collection;
    }

    function _prepareLayout() {

        $pager = $this->getLayout()->createBlock('page/html_pager', 'custom.pager');
        $pager->setAvailableLimit(array(5 => 5, 10 => 10, 20 => 20, 'all' => 'all'));

        $this->setChild('pager', $pager);
        parent::_prepareLayout();
    }

    function _toHtml() {
        $this->getChild('pager')->setCollection($this->getCollection());
        return parent::_toHtml();
    }

    public function getPagerHtml() {
        return $this->getChildHtml('pager');
    }

    public function getCategory(){
        if (!Mage::registry('current_recipe_category')){
            $this->_initCategory($this->getCategoryId());
        }
        return Mage::registry('current_recipe_category');
    }

    protected function _initCategory($id = NULL){
        if ($id == NULL){
            $data = $this->getRequest()->getParams();
            $id = $data['id'];
        }
        $category = Mage::getModel('spectrumted_recipe/category');
        if ($id != NULL){
            $category->load($id);
            Mage::register('current_recipe_category', $category);
        }
        return $category;
    }

    function getSummaryBlock($post) {
        if (!$this->getLayout()->getBlock('post_summary_' . $post->getId())) {
            $post->setCurrentCategoryId($this->getCategoryId());
            $post_summary = $this->getLayout()->createBlock($this->getSummaryBlockType(), 'post_summary_' . $post->getId())
                    ->setTemplate($this->getSummaryTemplate())
                    ->setPost($post);
            $this->getLayout()->getBlock('post_summary_' . $post->getId());
        }
        return $this->getLayout()->getBlock('post_summary_' . $post->getId());
    }

}