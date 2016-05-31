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
class Spectrumted_Recipe_Block_Adminhtml_Post_Edit_Tab_Publishing extends Mage_Adminhtml_Block_Widget_Form
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('author_form_tab');
        $this->setTabId('author_form_tab');
    }

    /**
     * Prepares the publishing form
     * @return Mage_Adminhtml_Block_Widget_Form
     */
    protected function _prepareForm() {
        $form = new Varien_Data_Form();
        $this->setForm($form);

        $form->setDataObject(Mage::registry('current_post'));
        $fieldset = $form->addFieldset('spectrumted_recipe_publishing', array('legend' => Mage::helper('spectrumted_recipe')->__('Author Data')));

        $form->setFieldNameSuffix('publishing_data');

        $fieldset->addField('author', 'text', array(
            'label' => Mage::helper('spectrumted_recipe')->__('Author'),
            'class' => 'required-entry',
            'name' => 'author',
            'required' => true,
            'index' => 'author',
        ));
        $dateFormatIso = Mage::app()->getLocale()->getDateTimeFormat(Mage_Core_Model_Locale::FORMAT_TYPE_MEDIUM);
        $fieldset->addField('post_date', 'date', array(
            'name'   => 'post_date',
            'label'  => Mage::helper('spectrumted_recipe')->__('Show Post Date'),
            'title'  => Mage::helper('spectrumted_recipe')->__('Show Post Date'),
            'image'  => $this->getSkinUrl('images/grid-cal.gif'),
            'input_format' => Varien_Date::DATETIME_INTERNAL_FORMAT,
            'time'  => true,
            'format'       => $dateFormatIso
        ));

        $publish_fieldset = $form->addFieldset('spectrumted_recipe_publish', array('legend' => Mage::helper('spectrumted_recipe')->__('Publishing Data')));

        $publish_fieldset->addField('publish_date', 'date', array(
            'name'   => 'publish_date',
            'label'  => Mage::helper('spectrumted_recipe')->__('Publish Date'),
            'title'  => Mage::helper('spectrumted_recipe')->__('Publish Date'),
            'image'  => $this->getSkinUrl('images/grid-cal.gif'),
            'input_format' => Varien_Date::DATETIME_INTERNAL_FORMAT,
            'time'  => true,
            'format'       => $dateFormatIso
        ));

         $recipeofmonth_fieldset = $form->addFieldset('spectrumted_recipe_of_month', array('legend' => Mage::helper('spectrumted_recipe')->__('Recipe of the Month')));
 
		 $recipeofmonth_fieldset->addField('status_recipe_of_month', 'select', array(
            'label' => Mage::helper('spectrumted_recipe')->__('Status'),
            'index' => 'status_recipe_of_month',
            'name' => 'status_recipe_of_month',
            'values' => Mage::getSingleton('spectrumted_recipe/attribute_source_status')->getAllOptions()
        ));        
        
        
        if (Mage::getSingleton('adminhtml/session')->getRecipePostData()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getRecipePostData());
            Mage::getSingleton('adminhtml/session')->setRecipePostData(null);
        } elseif (Mage::registry('current_post')) {
            $form->setValues(Mage::registry('current_post')->getData());
        }
        return parent::_prepareForm();
    }

    /**
     * Override to set TimeZone of Date Fields so that the backend user can enter in the time in their locale.
     * @return Mage_Adminhtml_Block_Widget_Form
     */
    protected function _initFormValues(){
        $returnable = parent::_initFormValues();
        $timeZone = Mage::getStoreConfig('general/locale/timezone');
        //convert to localized Timezone
        //This is then converted back in
        //Spectrumted_Recipe_Adminhtml_Spectrumted_Recipe_PostController::_filterDates
        if (!$timeZone){
            $timeZone = Mage_Core_Model_Locale::DEFAULT_TIMEZONE;
        }
        $timeElementNames = array('publish_date','post_date');
        foreach($timeElementNames as $timeElementName){
            $zendDate = $this->getForm()->getElement($timeElementName)->getValueInstance();
            if ($zendDate){
                $zendDate->setTimezone($timeZone);
            }
        }
        return $returnable;
    }
}