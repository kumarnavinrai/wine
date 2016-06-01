<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magento.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magento.com for more information.
 *
 * @category    Mage
 * @package     Mage_Eav
 * @copyright  Copyright (c) 2006-2016 X.commerce, Inc. and affiliates (http://www.magento.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Emtity attribute option model
 *
 * @method Mage_Eav_Model_Resource_Entity_Attribute_Option _getResource()
 * @method Mage_Eav_Model_Resource_Entity_Attribute_Option getResource()
 * @method int getAttributeId()
 * @method Mage_Eav_Model_Entity_Attribute_Option setAttributeId(int $value)
 * @method int getSortOrder()
 * @method Mage_Eav_Model_Entity_Attribute_Option setSortOrder(int $value)
 *
 * @category    Mage
 * @package     Mage_Eav
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Mage_Eav_Model_Entity_Attribute_Coption extends Mage_Core_Model_Abstract
{
	
protected $_options = null;

    public function getAllOptions($withEmpty = false){
        if (is_null($this->_options)){
            $this->_options = array();
            $methods = Mage::getSingleton('shipping/config')->getActiveCarriers();
            $coptions = array();
			  foreach($methods as $_code => $_method)
				{
					if(!$_title = Mage::getStoreConfig("carriers/$_code/title"))
						$_title = $_code;

					$this->_options[] = array('value' => $_code, 'label' => $_title . " ($_code)");
				}
			
			
			
            // $this->_options[] = array('label'=> 'Flat Rate', value=>1);
            // $this->_options[] = array('label'=> 'UPS', value=>3);
            // $this->_options[] = array('label'=> 'FedEx', value=>3);
			// $this->_options[] = array('label'=> 'TNT', value=>4);
        }
        $options = $this->_options;
        if ($withEmpty) {
            array_unshift($options, array('value'=>'', 'label'=>''));
        }
		
		/*
		$methods = Mage::getSingleton('shipping/config')->getActiveCarriers();

    $options = array();

    foreach($methods as $_code => $_method)
    {
        if(!$_title = Mage::getStoreConfig("carriers/$_code/title"))
            $_title = $_code;

        $options[] = array('value' => $_code, 'label' => $_title . " ($_code)");
    }

    if($isMultiSelect)
    {
        array_unshift($options, array('value'=>'', 'label'=> Mage::helper('adminhtml')->__('--Please Select--')));
    }
		*/
		
        return $options;
    }
    public function getOptionText($value)
    {
        $options = $this->getAllOptions(false);

        foreach ($options as $item) {
            if ($item['value'] == $value) {
                return $item['label'];
            }
        }
        return false;
    }
	

	
	const MAIN = 1;
	const OTHER = 2;
 
  
    public function toOptionArray()
    {
        return $this->getAllOptions();
    }
 /*
    public function addValueSortToCollection($collection, $dir = 'asc')
    {
        $adminStore  = Mage_Core_Model_App::ADMIN_STORE_ID;
        $valueTable1 = $this->getAttribute()->getAttributeCode() . '_t1';
        $valueTable2 = $this->getAttribute()->getAttributeCode() . '_t2';
 
        $collection->getSelect()->joinLeft(
            array($valueTable1 => $this->getAttribute()->getBackend()->getTable()),
            "`e`.`entity_id`=`{$valueTable1}`.`entity_id`"
            . " AND `{$valueTable1}`.`attribute_id`='{$this->getAttribute()->getId()}'"
            . " AND `{$valueTable1}`.`store_id`='{$adminStore}'",
            array()
        );
 
        if ($collection->getStoreId() != $adminStore) {
            $collection->getSelect()->joinLeft(
                array($valueTable2 => $this->getAttribute()->getBackend()->getTable()),
                "`e`.`entity_id`=`{$valueTable2}`.`entity_id`"
                . " AND `{$valueTable2}`.`attribute_id`='{$this->getAttribute()->getId()}'"
                . " AND `{$valueTable2}`.`store_id`='{$collection->getStoreId()}'",
                array()
            );
            $valueExpr = new Zend_Db_Expr("IF(`{$valueTable2}`.`value_id`>0, `{$valueTable2}`.`value`, `{$valueTable1}`.`value`)");
 
        } else {
            $valueExpr = new Zend_Db_Expr("`{$valueTable1}`.`value`");
        }
 
 
 
        $collection->getSelect()
            ->order($valueExpr, $dir);
 
        return $this;
    }
 
    public function getFlatColums()
    {
        $columns = array(
            $this->getAttribute()->getAttributeCode() => array(
                'type'      => 'varchar',
                'unsigned'  => false,
                'is_null'   => true,
                'default'   => null,
                'extra'     => null
            )
        );
        return $columns;
    }
 
 
    public function getFlatUpdateSelect($store)
    {
        return Mage::getResourceModel('eav/entity_attribute')
            ->getFlatUpdateSelect($this->getAttribute(), $store);
    }
*/
}
