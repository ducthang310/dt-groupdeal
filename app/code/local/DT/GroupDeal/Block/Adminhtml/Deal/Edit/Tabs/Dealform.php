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

        $fieldSet = $form->addFieldset('dt_form', array('legend' => Mage::helper('dt_groupdeal')->__('Group Deal information')));

        $fieldSet->addField('group_deal_id', 'hidden', array(
            'label' => Mage::helper('dt_groupdeal')->__('Group Deal ID'),
            'readonly' => true,
            'name' => 'group_deal_id',
        ));

        $fieldSet->addField('product_id', 'text', array(
            'label' => Mage::helper('dt_groupdeal')->__('Product Id'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'product_id',
        ));

        $fieldSet->addField('group_deal_name', 'text', array(
            'label' => Mage::helper('dt_groupdeal')->__('Name'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'group_deal_name',
        ));

        $fieldSet->addField('deal_from_date', 'text', array(
            'label' => Mage::helper('dt_groupdeal')->__('From date'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'deal_from_date',
        ));

        $fieldSet->addField('deal_to_date', 'text', array(
            'label' => Mage::helper('dt_groupdeal')->__('To date'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'deal_to_date',
        ));

        $fieldSet->addField('deal_description', 'text', array(
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