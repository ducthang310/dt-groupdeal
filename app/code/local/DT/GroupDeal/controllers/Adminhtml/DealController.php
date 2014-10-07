<?php
/**
 * Created by   : JetBrains PhpStorm.
 * @project     : dt-groupdeal
 * @author      : DUC THANG
 * @Date        : 10/7/14
 * @Time        : 9:46 PM
 * @copyright  Copyright (c) 2014
 *
 */
class DT_GroupDeal_Adminhtml_DealController extends Mage_Adminhtml_Controller_action
{
    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function deleteAction()
    {
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $model = Mage::getModel('dt_groupdeal/deal');

                $model->setId($this->getRequest()->getParam('id'))
                    ->delete();

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Deal was successfully deleted'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }

    public function exportCsvAction()
    {
        $fileName = 'dt_groupdeal.csv';
        $content = $this->getLayout()->createBlock('dt_groupdeal/adminhtml_deal_grid')
            ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName = 'dt_groupdeal.xml';
        $content = $this->getLayout()->createBlock('dt_groupdeal/adminhtml_deal_grid')
            ->getXml();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function gridAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('dt_groupdeal/adminhtml_deal_grid')->toHtml()
        );
    }

    public function newAction(){
        $this->loadLayout();
        $this->_addContent($this->getLayout()->createBlock('form/adminhtml_form_edit'))
            ->_addLeft($this->getLayout()->createBlock('form/adminhtml_form_edit_tabs'));
        $this->renderLayout();
    }
}