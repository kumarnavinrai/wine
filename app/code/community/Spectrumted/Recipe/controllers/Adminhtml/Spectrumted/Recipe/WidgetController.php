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
class Spectrumted_Recipe_Adminhtml_Spectrumted_Recipe_WidgetController extends Mage_Adminhtml_Controller_action {

    public function categorychooserAction()
    {
        $uniqId = $this->getRequest()->getParam('uniq_id');
        $massAction = $this->getRequest()->getParam('use_massaction', false);
        $categoryGrid = $this->getLayout()->createBlock('spectrumted_recipe/adminhtml_category_widget_chooser', '', array(
            'id'                => $uniqId,
            'use_massaction' => $massAction,

        ));     
        $html = $categoryGrid->toHtml();
        $this->getResponse()->setBody($html);
    }

    public function postchooserAction()
    {
        $uniqId = $this->getRequest()->getParam('uniq_id');
        $massAction = $this->getRequest()->getParam('use_massaction', false);
        //$productTypeId = $this->getRequest()->getParam('product_type_id', null);
        $postGrid = $this->getLayout()->createBlock('spectrumted_recipe/adminhtml_post_widget_chooser', '', array(
            'id'                => $uniqId,
            'use_massaction' => $massAction,
            // 'product_type_id' => $productTypeId,
            // 'category_id'       => $this->getRequest()->getParam('category_id')
        ));
        $html = $postGrid->toHtml();


        $this->getResponse()->setBody($html);
    }

}