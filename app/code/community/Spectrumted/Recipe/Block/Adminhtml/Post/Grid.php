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
class Spectrumted_Recipe_Block_Adminhtml_Post_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId('spectrumted_recipe_postGrid');
        // This is the primary key of the database
        $this->setDefaultSort('created_at');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setRowClickCallback('openGridRow');
        $this->setUseAjax(true);
    }

    protected function _prepareMassaction() {
        return $this;
    }

    private function _getStoreId() {
        return (int) $this->getRequest()->getParam('store', 0);
    }

    protected function _getStore() {
        return Mage::app()->getStore($this->_getStoreId());
    }

    protected function _prepareCollection() {
        $store = $this->_getStore();
        $collection = Mage::getModel('spectrumted_recipe/post')
                 // ->setStoreId($this->_getStoreId())
                ->getCollection();
        if ($this->_getStoreId()){
            $collection->addStoreFilter($this->_getStoreId());
        }
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {

        $this->addColumn('entity_id', array(
            'header' => Mage::helper('spectrumted_recipe')->__('ID'),
            'align' => 'right',
            'width' => '50px',
            'index' => 'entity_id',
        ));

        $this->addColumn('title', array(
            'header' => Mage::helper('spectrumted_recipe')->__('Title'),
            'align' => 'left',
            //'width'     => '300px',
            'default' => '1',
            'index' => 'title',
        ));

        $this->addColumn('cms_identifier', array(
            'header' => Mage::helper('spectrumted_recipe')->__('URL Key'),
            'align' => 'left',
            'default' => '1',
            'index' => 'cms_identifier',
        ));
        $this->addColumn('created_at', array(
            'header' => Mage::helper('spectrumted_recipe')->__('Created At'),
            'align' => 'left',
            'width' => '120px',
            'type' => 'date',
            'default' => '--',
            'index' => 'created_at',
        ));
        $this->addColumn('updated_at', array(
            'header' => Mage::helper('spectrumted_recipe')->__('Updated At'),
            'align' => 'left',
            'width' => '120px',
            'type' => 'date',
            'default' => '--',
            'index' => 'updated_at',
        ));
        $this->addColumn('status', array(
            'header' => Mage::helper('spectrumted_recipe')->__('Status'),
            'align' => 'left',
            'index' => 'status',
            'type' => 'options',
            'options' => Mage::getModel('spectrumted_recipe/attribute_source_post_status')->toOptionArray()
        ));
        return parent::_prepareColumns();
    }

    public function getGridUrl() {
        return $this->getUrl('*/*/grid', array('_current' => true, 'store' => $this->getRequest()->getParam('store')));
    }

    public function getRowUrl($row) {
        return $this->getUrl('*/*/edit', array('id' => $row->getData("entity_id"), 'store' => $this->getRequest()->getParam('store')));
    }

}