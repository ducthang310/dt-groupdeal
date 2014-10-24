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

    protected function _initDeal($idFieldName = 'id')
    {
        $dealId = (int) $this->getRequest()->getParam($idFieldName);
        $deal = Mage::getModel('dt_groupdeal/deal');

        if ($dealId) {
            $deal->load($dealId);
        }

        Mage::register('current_deal', $deal);
        return $this;
    }

    public function deleteAction()
    {
        $this->_initDeal();
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $model = Mage::registry('current_deal');
                $model->delete();

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

    public function editAction() {
        $this->_initDeal();
        $model = Mage::registry('current_deal');

        if ($model->getId() || $this->getRequest()->getParam('id') == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }

            Mage::register('deal_data', $model);

            $this->loadLayout();
            $this->_setActiveMenu('dt_groupdeal/index');

            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Deal Manager'), Mage::helper('adminhtml')->__('Deal Manager'));

            $this->_addContent($this->getLayout()->createBlock('dt_groupdeal/adminhtml_deal_edit'));
            $this->_addLeft($this->getLayout()->createBlock('dt_groupdeal/adminhtml_deal_edit_tabs'));

            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('dt_groupdeal')->__('Deal does not exist'));
            $this->_redirect('*/*/');
        }
    }

    public function newAction() {
        $this->_forward('edit');
    }

    public function saveAction() {
        if ($postData = $this->getRequest()->getPost()) {
            try {
                $this->_initDeal();
                $dealModel = Mage::registry('current_deal');
                $dealModel
                    ->addData($postData)
                    ->save();

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('dt_groupdeal')->__('Group Deal was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $dealModel->getId()));
                    return;
                }
                $this->_redirect('*/*/');

                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($postData);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('dt_groupdeal')->__('Unable to find group deal to save'));
        $this->_redirect('*/*/');

    }

    /**
     * Deal orders grid
     *
     */
    public function ordersAction() {
        $this->_initDeal();
        $this->loadLayout();
        $this->renderLayout();
    }

    public function newOrderAction() {
        var_dump($this->getRequest()->getParams());die;
    }

    public function sendMailAction() {
        var_dump($this->getRequest()->getParams());die;
    }
}