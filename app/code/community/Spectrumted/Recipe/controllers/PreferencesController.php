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
class Spectrumted_Recipe_PreferencesController extends Spectrumted_Recipe_Controller_Abstract {

    public function indexAction(){
        $this->_init();
        $this->renderLayout();
    }
    
    public function saveAction(){
        $session = Mage::getSingleton('customer/session');
        try{
            $session->getCustomer()
                    ->setData('default_recipe_category_ids', $this->getRequest()->getParam('default_recipe_category_ids'))
                    ->save();
            $session->addSuccess('Successfully Saved Preferences');
            
        }
        catch(Exception $ex){
            $session->addError('Failed to Save, please notify support');
        }
        $this->_redirect('customer/account/index');
    }
    
    protected function _setBreadCrumbs(){
        $breadcrumbBlock = $this->getLayout()->getBlock('breadcrumbs');
        if ($breadcrumbBlock){
        $breadcrumbBlock->addCrumb('home', 
                                    array('label'=>Mage::helper('spectrumted_recipe')->__('Home'), 
                                          'title'=>Mage::helper('spectrumted_recipe')->__('Go to Home Page'), 
                                          'link'=>Mage::getBaseUrl())); 
        $breadcrumbBlock->addCrumb('recipe', 
                                    array('label'=>Mage::helper('spectrumted_recipe')->__('Recipe'), 
                                          'title'=>Mage::helper('spectrumted_recipe')->__('Go to Recipe'), 
                                          'link'=> Mage::getSingleton('core/url')->getUrl('recipe'))
                                        ); 
       }
    }
    

}