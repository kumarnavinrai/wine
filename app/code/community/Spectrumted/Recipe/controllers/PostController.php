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
class Spectrumted_Recipe_PostController extends Spectrumted_Recipe_Controller_Abstract {
    public function indexAction(){
        $this->_initPost();
        $this->_init();
        if ($this->getPost()->getLayoutUpdateXml()) {
            $this->getLayout()->getUpdate()->addUpdate($this->getPost()->getLayoutUpdateXml());
            $this->generateLayoutXml()->generateLayoutBlocks();
        }
        if ($this->getPost()->getRootTemplate() && $this->getPost()->getRootTemplate() != 'use_default') {
            $this->getLayout()->helper('page/layout')->applyTemplate($this->getPost()->getRootTemplate());
        }
        else{
            $this->_setDefaultLayout();
        }
        $this->renderLayout();
    }
    
    protected function _setMetaData(){
        parent::_setMetaData();
        $headBlock = $this->getLayout()->getBlock('head');
        if ($this->getPost()->getMetaTitle() !=  NULL){
            $headBlock->setTitle($this->getPost()->getMetaTitle());
        }
        elseif($this->getPost()->getTitle() != NULL){
            $headBlock->setTitle($this->getPost()->getTitle());
        }
        if ($this->getPost()->getMetaDescription() !=  NULL){
            $headBlock->setDescription($this->getPost()->getMetaDescription());
        }
        if ($this->getPost()->getMetaKeywords() !=  NULL){
            $headBlock->setKeywords($this->getPost()->getMetaKeywords());
        }
    }   

    protected function _setBreadCrumbs(){
        $breadcrumbBlock = $this->getLayout()->getBlock('breadcrumbs');
        if ($breadcrumbBlock){
        $frontendName = Mage::helper('spectrumted_recipe')->getFrontendName();
        $UcFrontendName = ucfirst($frontendName);
        $breadcrumbBlock->addCrumb('home', 
                                    array('label'=>Mage::helper('spectrumted_recipe')->__('Home'), 
                                          'title'=>Mage::helper('spectrumted_recipe')->__('Go to Home Page'), 
                                          'link'=>Mage::getBaseUrl())); 
        $breadcrumbBlock->addCrumb('recipe', 
                                    array('label'=>Mage::helper('spectrumted_recipe')->__($UcFrontendName), 
                                          'title'=>Mage::helper('spectrumted_recipe')->__('Go to ' . $UcFrontendName), 
                                          'link'=> Mage::getSingleton('core/url')->getUrl($frontendName))
                                        ); 
        if ($this->getRequest()->getParam('category_id')){
            $this->_initCategory($this->getRequest()->getParam('category_id'));
            $breadcrumbBlock->addCrumb('category', 
                                        array('label'=>Mage::helper('spectrumted_recipe')->__($this->getCategory()->getName()), 
                                            'title'=>Mage::helper('spectrumted_recipe')->__('Go to '. $this->getCategory()->getName()), 
                                            'link'=> $this->getCategory()->getListUrl())
                                            ); 
        }
        $breadcrumbBlock->addCrumb('post', 
                                    array('label'=>Mage::helper('spectrumted_recipe')->__($this->getPost()->getTitle()), 
                                          'title'=>Mage::helper('spectrumted_recipe')->__('Go to ' . $this->getPost()->getTitle()), 
                                          'link'=> $this->getPost()->getReadMoreUrl())); 
        }
    }
    
    


}