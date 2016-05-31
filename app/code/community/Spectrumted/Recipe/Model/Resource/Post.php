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
class Spectrumted_Recipe_Model_Resource_Post extends Mage_Core_Model_Mysql4_Abstract
{
    function _construct()
    {   
        $this->_init('spectrumted_recipe/post', 'entity_id');
    }
    
    function checkIdentifier($identifier, $storeId){    
        $adapter = $this->_getReadAdapter();
        $_helper = Mage::helper('spectrumted_recipe');
        
        $status = ($_helper->isIpPermitted(NULL, $storeId ))? array(Spectrumted_Recipe_Model_Post::STATUS_ACTIVE, Spectrumted_Recipe_Model_Post::STATUS_DRAFT) : array(Spectrumted_Recipe_Model_Post::STATUS_ACTIVE);

        $binds = array(
            'store_id' => $storeId,
            'url_key' => $identifier
        );
        $select = $adapter->select('entity_id')
                ->from(array('post' => $this->getMainTable()))
                ->where('post.cms_identifier = :url_key')
                //can't put in the status as a bind - Zend changes this to IN('1,2') instead of  IN(1,2). the former obvsiouly won't work
                ->where('post.status IN(?)', $status)
                ->where('FIND_IN_SET(:store_id, post.store_ids) OR FIND_IN_SET(0, post.store_ids) OR post.store_ids = \'\'');
        return $adapter->fetchOne($select, $binds);
    }
}