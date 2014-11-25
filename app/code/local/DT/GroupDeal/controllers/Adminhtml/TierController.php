<?php
/**
 * Created by JetBrains PhpStorm.
 * User: DucThang
 * Date: 11/22/14
 * Time: 5:00 PM
 */
class DT_GroupDeal_Adminhtml_TierController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    protected function _initTier($idFieldName = 'id')
    {
        $tierId = (int)$this->getRequest()->getParam($idFieldName);
        $tier = Mage::getModel('dt_groupdeal/tier');

        if ($tierId) {
            $tier->load($tierId);
        }

        Mage::register('current_tier', $tier);
        return $this;
    }

    public function deleteAction()
    {
        $this->_initTier();
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $model = Mage::registry('current_tier');
                $model->delete();

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Tier was successfully deleted'));
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
        $content = $this->getLayout()->createBlock('dt_groupdeal/adminhtml_tier_grid')
            ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName = 'dt_groupdeal.xml';
        $content = $this->getLayout()->createBlock('dt_groupdeal/adminhtml_tier_grid')
            ->getXml();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function gridAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('dt_groupdeal/adminhtml_tier_grid')->toHtml()
        );
    }

    public function editAction()
    {
        $this->_initTier();
        $model = Mage::registry('current_tier');

        if ($model->getId() || $this->getRequest()->getParam('id') == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }

            Mage::register('tier_data', $model);

            $this->loadLayout();
            $this->_setActiveMenu('dt_groupdeal/index');

            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Tier Manager'), Mage::helper('adminhtml')->__('Tier Manager'));

            $this->_addContent($this->getLayout()->createBlock('dt_groupdeal/adminhtml_tier_edit'));
            $this->_addLeft($this->getLayout()->createBlock('dt_groupdeal/adminhtml_tier_edit_tabs'));

            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('dt_groupdeal')->__('Tier does not exist'));
            $this->_redirect('*/*/');
        }
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function saveAction()
    {
        if ($postData = $this->getRequest()->getPost()) {
            try {
                $this->_initTier();
                $tierModel = Mage::registry('current_tier');
                foreach($postData['tier_price'] as $k => $tier) {
                    if ($tier['delete']) {
                        unset($postData['tier_price'][$k]);
                    }
                    unset($postData['tier_price'][$k]['delete']);
                    unset($postData['tier_price'][$k]['tier_id']);
                }
                $tierModel
                    ->addData($postData)
                    ->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('dt_groupdeal')->__('Tier was successfully saved'));

                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $tierModel->getId()));
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
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('dt_groupdeal')->__('Unable to find tier to save'));
        $this->_redirect('*/*/');

    }

    /**
     * Deal orders grid
     *
     */
    public function ordersAction()
    {
        $productID = (int)$this->getRequest()->getParam('id');
        $product = Mage::getModel('catalog/product')->load($productID);
        $deal = Mage::getModel('dt_groupdeal/deal')->load($product->getDealId());
        Mage::register('current_deal', $deal);
        $this->loadLayout();
        $this->renderLayout();
    }

    public function tierAction () {
        if ($this->getRequest()->getPost('tier_id', false)) {
            $result = array();
            $tier = Mage::getModel('dt_groupdeal/tier')->load($this->getRequest()->getPost('tier_id'));
            if ($tier->getId()) {
                $tierBlock = $this->getLayout()->createBlock('dt_groupdeal/adminhtml_catalog_product_edit_tabs_deal_tier')->setTier($tier);
                $result['tier'] = $tierBlock->toHtml();
                $result['error'] = false;
            } else {
                $result['error'] = true;
                $result['message'] = 'Unable to find tier to load';
            }

            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
        } else {
            $result['error'] = true;
            $result['message'] = 'You must input an ID';
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode(array('error' => true)));
        }
    }
}