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
class Spectrumted_Recipe_Model_Category 
extends  Spectrumted_Recipe_Model_Abstract_Store
implements Spectrumted_Recipe_Model_IFrontendRoute
{
    
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const ROUTE_ACTION_NAME = 'index';
    const ROUTE_CONTROLLER_NAME = 'category';
    const CACHE_TAG = 'spectrumted_recipe_category';
    
    public function _construct() {
        $this->_init('spectrumted_recipe/category');
        $this->setContollerName();
        $this->setActionName();
    }
    
    function getListUrl($noRedirect = false){
        if (!$this->getData('list_url')){
            $params = array('id' => $this->getId());
            if (!$this->getCmsIdentifier() || $noRedirect){
                if ($this->getCurrentCategoryId()){
                    $params['category_id'] = $this->getCurrentCategoryId();
                }
                $url = Mage::getModel('core/url')->getUrl(Mage::helper('spectrumted_recipe')->getFrontendName() .'/category/index', $params);
            }
            else
            {
                $url = Mage::getModel('core/url')->getUrl(Mage::helper('spectrumted_recipe')->getFrontendName() ."/". $this->getCmsIdentifier() , null);
            }
            $this->setData('list_url', $url);
        }
        return $this->getData('list_url');
    }
    
    function getRssUrl(){
        if (!$this->getData('rss_url')){
            $storeId = Mage::app()->getStore()->getId();
            $this->setData('rss_url',Mage::getSingleton('core/url')->getUrl('rss/recipe/index', array('category_id' => $this->getId(), 'store_id' => $storeId)));
        }
        return $this->getData('rss_url');
    }

    function getRouteControllerName(){
        return self::ROUTE_CONTROLLER_NAME;
    }
    function getRouteActionName(){
        return self::ROUTE_ACTION_NAME;
    }
    public function getRouteParams($id){
        return array('id' => $id, 'category_id' => $id);
    }
    public function checkIdentifier($identifier, $storeId){
        return $this->getResource()->checkIdentifier($identifier, $storeId);
    }
}