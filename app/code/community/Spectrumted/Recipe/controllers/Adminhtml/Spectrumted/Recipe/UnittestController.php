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
class Spectrumted_Recipe_Adminhtml_Spectrumted_Recipe_UnittestController extends Spectrumted_Recipe_Controller_Adminhtml_Abstract {



    public function indexAction() {
        $this->_title($this->__('Recipe'))->_title($this->__('Post'));
        $this->loadLayout();
        //$this->getLayout()->createBlock('spectrumted_recipe/adminhtml_post');
       
        $this->renderLayout();
    }

    
    //public function postnewsaveAction(){
    //    $postdata = array(
    //            'spectrumted_recipe' => array(
    //                  'title'           => 'Unit Test',
    //                  'status'          => Spectrumted_Recipe_Model_Post::STATUS_DRAFT,
    //                  'summary'         => '<p>Unit Testing Content Summary</p>',
    //                  'content_html'    => '<p>Unit Testing Content Content</p>',
    //                  'store_ids'       => '1,2',
    //                  'category_ids'    => '1,2',
    //                  'tag_ids'         => '1,2',
    //            ), 
    //            'meta_data' => array(
    //                  'meta_description'=> 'Recipe Unit Test desc',
    //                  'meta_title'      => 'Recipe Unit Test Title',
    //                  'meta_keywords'   => 'Recipe Unit Test Keywords',
    //            )
    //        );
    //    $this->_redirect('*/spectrumted_recipe_post/save', $postdata);
    //}
    

}