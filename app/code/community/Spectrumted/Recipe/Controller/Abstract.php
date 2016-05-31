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
abstract class Spectrumted_Recipe_Controller_Abstract extends Mage_Core_Controller_Front_Action  {

    protected function _setBlockConfig(){
        if ($this->getLayout()->getBlock('recipe.category.list')){
            $this->getLayout()->getBlock('recipe.category.list')->setShowPopulatedOnly(Mage::getStoreConfig('recipe/category/show_populated_only'));
        }
    }
    
    protected function _init(){
        $this->loadLayout();
        $this->_setBreadcrumbs();
        $this->_setBlockConfig();
        $this->_setMetaData();
    }

    protected function _setMetaData(){
        $headBlock = $this->getLayout()->getBlock('head');
        if ($metaDescription = Mage::getStoreConfig(Spectrumted_Recipe_Helper_Data::XPATH_DEFAULT_META_DESCRIPTION)){
            $headBlock->setDescription($metaDescription);
        }
        if ($metaKeywords = Mage::getStoreConfig(Spectrumted_Recipe_Helper_Data::XPATH_DEFAULT_META_KEYWORDS)){
            $headBlock->setKeywords($metaKeywords);
        }
    }

    protected function _setDefaultLayout(){
        if ($rootTemplate = Mage::getStoreConfig('recipe/frontend/default_root_template')){
            $this->getLayout()->helper('page/layout')->applyTemplate($rootTemplate);
        }
    }

    protected function _initPost(){
         $data = $this->getRequest()->getParams();
         $post = $this->_getPostModel();
         $id = (isset($data['id']) && $data['id'] != NULL)? $data['id'] : $this->_matchIdentifier();
         if ($id != NULL && $id > 0){
             $post->load($id);
             Mage::register('current_post', $post);
         }         
         return $post;
    }
    
    protected function _matchIdentifier(){
        $identifier = trim($this->getRequest()->getPathInfo(), '/');

        $condition = new Varien_Object(array(
            'identifier' => $identifier,
            'continue'   => true
        ));
        Mage::dispatchEvent('notheme_recipe_controller_router_match_before', array(
            'router'    => $this,
            'condition' => $condition
        ));
        $identifier = $condition->getIdentifier();

        if ($condition->getRedirectUrl()) {
            Mage::app()->getFrontController()->getResponse()
                ->setRedirect($condition->getRedirectUrl())
                ->sendResponse();
            $this->getRequest()->setDispatched(true);
            return true;
        }
        if (!$condition->getContinue()) {
            return false;
        }
    }
    
    protected function _getPostModel(){
        return Mage::getModel('spectrumted_recipe/post');
    }   
    
    protected function getPost(){
        return Mage::registry('current_post');
    }
    

     
    protected function _initCategory($id = NULL){
         $data = $this->getRequest()->getParams();
         if ($id == NULL){
             $id = $data['id'];
         }
         $category = $this->_getCategoryModel();
         if ($id != NULL){
             $category->load($id);
             Mage::register('current_recipe_category', $category);
         }
         return $category;
    }
    
    protected function _getCategoryModel(){
        return Mage::getModel('spectrumted_recipe/category');
    }   
    
    protected function getCategory(){
        return Mage::registry('current_recipe_category');
    }
}