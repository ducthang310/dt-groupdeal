<?php
/**
 * Created by   : JetBrains PhpStorm.
 * @project     : dt-groupdeal
 * @author      : DUC THANG
 * @Date        : 10/8/14
 * @Time        : 11:22 PM
 * @copyright  Copyright (c) 2014
 *
 */
class DT_GroupDeal_Block_Adminhtml_Deal_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();

        $this->_objectId = 'id';
        $this->_blockGroup = 'dt_groupdeal';
        $this->_controller = 'adminhtml_deal';

        $this->_updateButton('save', 'label', Mage::helper('dt_groupdeal')->__('Save'));
        $this->_updateButton('delete', 'label', Mage::helper('dt_groupdeal')->__('Delete'));


        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = '
            function saveAndContinueEdit(){
                editForm.submit($("edit_form").action+"back/edit/");
            }
        ';

        if( Mage::registry('current_deal') && Mage::registry('current_deal')->getId() ) {
            $dataCal = $dataRes = array();
            if (Mage::registry('current_deal')->getIsActive()) {
                $result = Mage::helper('dt_groupdeal')->checkDealTime(Mage::registry('current_deal'));
                if ($result['status'] == DT_GroupDeal_Helper_Data::DEAL_STATUS_ENDED) {
                    if (Mage::registry('current_deal')->getIsCalculated()) {
                        $dataCal['class'] = 'disabled';
                        $dataCal['msg'] = Mage::helper('adminhtml')->__('This Deal has been calculated.');
                        $dataCal['onclick'] = "notifyDeal('" . $dataCal['msg'] . "')";

                        $dataRes['class'] = 'add-widget';
                        $dataRes['msg'] = Mage::helper('adminhtml')->__('Are you sure you want to do this?');
                        $dataRes['url'] = $this->getUrl('*/*/reset', array('id' => Mage::registry('current_deal')->getId()));
                        $dataRes['onclick'] = "actionDealConfirm('" . $dataRes['msg'] . "', '" . $dataRes['url'] . "')";
                    } else {
                        $dataCal['class'] = 'add-widget';
                        $dataCal['msg'] = Mage::helper('adminhtml')->__('Are you sure you want to do this?');
                        $dataCal['url'] = $this->getUrl('*/*/reset', array('id' => Mage::registry('current_deal')->getId()));
                        $dataCal['onclick'] = "actionDealConfirm('" . $dataCal['msg'] . "', '" . $dataCal['url'] . "')";

                        $dataRes['class'] = 'disabled';
                        $dataRes['msg'] = Mage::helper('adminhtml')->__('Please calculate price before reset this deal.');
                        $dataRes['onclick'] = "notifyDeal('" . $dataRes['msg'] . "')";
                    }
                } else {
                    $dataCal['class'] = $dataRes['class'] = 'disabled';
                    $dataCal['msg'] = Mage::helper('adminhtml')->__('Can not do this action. The deal has not ended yet.');
                    $dataCal['onclick'] = $dataRes['onclick'] = "notifyDeal('" . $dataCal['msg'] . "')";
                }
            } else {
                $dataCal['class'] = $dataRes['class'] = 'disabled';
                $dataCal['msg'] = Mage::helper('adminhtml')->__('This deal is not active.');
                $dataCal['onclick'] = $dataRes['onclick'] = $dataRes['onclick'] = "notifyDeal('" . $dataCal['msg'] . "')";
            }
            $this->_addButton('calculatePrice', array(
                'label'     => Mage::helper('adminhtml')->__('Calculate Price'),
                'onclick'   => $dataCal['onclick'],
                'class'     => $dataCal['class'],
            ), -100);

            $this->_addButton('resetDeal', array(
                'label'     => Mage::helper('adminhtml')->__('Reset Deal'),
                'onclick'   => $dataRes['onclick'],
                'class'     => $dataRes['class'],
            ), -100);

            $this->_formScripts[] = '
                function notifyDeal(msg){
                    alert(msg);
                }
                function actionDealConfirm(message, url) {
                    confirmSetLocation(message, url);
                }
            ';
        }
    }

    public function getHeaderText()
    {
        if( Mage::registry('current_deal') && Mage::registry('current_deal')->getId() ) {
            return Mage::helper('dt_groupdeal')->__("Edit deal '%s'", Mage::registry('current_deal')->getGroupDealName());
        } else {
            return Mage::helper('dt_groupdeal')->__('Add deal');
        }
    }
}