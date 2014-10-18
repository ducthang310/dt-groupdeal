<?php
/**
 * Created by   : JetBrains PhpStorm.
 * @project     : dt-groupdeal
 * @author      : DUC THANG
 * @Date        : 10/12/14
 * @Time        : 2:41 PM
 * @copyright  Copyright (c) 2014
 *
 */
class DT_GroupDeal_Block_Adminhtml_Deal_Edit_Tabs_Dealform extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {
        // initial form
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $dealData = Mage::registry('deal_data');

        $fieldset = $form->addFieldset('dt_form', array('legend' => Mage::helper('dt_groupdeal')->__('Group Deal information')));

        $fieldset->addField('group_deal_name', 'text', array(
            'label' => Mage::helper('dt_groupdeal')->__('Name'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'group_deal_name',
        ));

        $fieldset->addField('product_id', 'text', array(
            'label' => Mage::helper('dt_groupdeal')->__('Product Id'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'product_id',
        ));

        $fieldset->addField('is_active', 'select', array(
            'label' => Mage::helper('dt_groupdeal')->__('Status'),
            'class' 	=> 'input-select',
            'required' => true,
            'name' => 'is_active',
            'options'	=> array('1' => Mage::helper('adminhtml')->__('Active'), '0' => Mage::helper('adminhtml')->__('Inactive')),
        ));

        $fieldset->addField('tier_price', 'text', array(
            'label' => Mage::helper('dt_groupdeal')->__('Tier Price'),
            'class'=> 'required-entry',
            'required' => true,
            'name' => 'tier_price',
            'value' => $dealData->getData('tier_price')
        ));

        $form->getElement('tier_price')->setRenderer(
            $this->getLayout()->createBlock('dt_groupdeal/adminhtml_deal_edit_tabs_renderer_tier')
        );

        $dateFormatIso = Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);
        $fieldset->addField('deal_from_date', 'date', array(
            'name'   => 'deal_from_date',
            'label'  => Mage::helper('dt_groupdeal')->__('From Date'),
            'title'  => Mage::helper('dt_groupdeal')->__('From Date'),
            'class' => 'required-entry',
            'required' => true,
            'image'  => $this->getSkinUrl('images/grid-cal.gif'),
            'input_format' => Varien_Date::DATETIME_INTERNAL_FORMAT,
            'format'       => $dateFormatIso
        ));
        $fieldset->addField('deal_to_date', 'date', array(
            'name'   => 'deal_to_date',
            'label'  => Mage::helper('dt_groupdeal')->__('To Date'),
            'title'  => Mage::helper('dt_groupdeal')->__('To Date'),
            'class' => 'required-entry',
            'required' => true,
            'image'  => $this->getSkinUrl('images/grid-cal.gif'),
            'input_format' => Varien_Date::DATETIME_INTERNAL_FORMAT,
            'format'       => $dateFormatIso
        ));

        $fieldset->addField('deal_description', 'textarea', array(
            'label' => Mage::helper('dt_groupdeal')->__('Description'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'deal_description',
        ));

        if (Mage::getSingleton('adminhtml/session')->getDealData()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getDealData());
            Mage::getSingleton('adminhtml/session')->setDealData(null);
        } elseif (Mage::registry('deal_data')) {
            $form->setValues(Mage::registry('deal_data')->getData());
        }

        return parent::_prepareForm();

    }
}