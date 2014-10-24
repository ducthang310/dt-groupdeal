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
        $dataParams = $this->getRequest()->getParams();
        if (isset($dataParams['order_id']) && isset($dataParams['product_id'])) {
            $order = Mage::getModel('sales/order')->load($dataParams['order_id']);
            if ($order->getId()) {
                $quote = Mage::getModel('sales/quote')
                    ->setStoreId($order->getStoreId());
                $billAdd = Mage::getModel('sales/order_address')->load($order->getBillingAddressId());
                $shipAdd = Mage::getModel('sales/order_address')->load($order->getShippingAddressId());
                $customer = Mage::getModel('customer/customer')
                    ->setWebsiteId(1)
                    ->loadByEmail($order->getCustomerEmail());
                $quote->assignCustomer($customer);

                $items = $order->getItemsCollection();
                foreach ($items as $item) {
                    if ($item->getProductId() == $dataParams['product_id'] && $item->getIsDeal() && $item->getHasExpired()) {
                        $product = Mage::getModel('catalog/product')->load($dataParams['product_id']);
                        if ($product->getId()) {
                            $quote->addProduct($product, $item->getQtyOrdered());
                        }
                        //TODO: new process to add exist item's data to quote ||| Note: this function is only active with simple product
//                        $dataItem = $item->getData();
//                        unset($dataItem['item_id']);
//                        unset($dataItem['order_id']);
//                        unset($dataItem['quote_item_id']);
//                        $item = Mage::getModel('sales/quote_item')->addData($dataItem);
//                        $item->setQuote($quote);
//                        $item->setProduct($product);
//                        $quote->getItemsCollection()->addItem($item);
                    }
                }

                $billingAddress = $quote->getBillingAddress()->addData($billAdd->getData());
                $shippingAddress = $quote->getShippingAddress()->addData($shipAdd->getData());

                $shippingAddress->setCollectShippingRates(true)->collectShippingRates()
                    ->setShippingMethod($order->getShippingMethod())
                    ->setPaymentMethod($order->getPayment()->getMethodInstance()->getCode());

                $quote->getPayment()->importData(array('method' => $order->getPayment()->getMethodInstance()->getCode()));

                $quote->collectTotals()->save();

                $service = Mage::getModel('sales/service_quote', $quote);
                $service->submitAll();
                $orderExchange = $service->getOrder();
                // add comment
                $comment = 'Order created to deal products from order #' . $order->getIncrementId();
                $orderExchange->addStatusHistoryComment($comment);
                $orderExchange->save();
                // send order email
                $orderExchange->sendOrderUpdateEmail(true, $comment);
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('dt_groupdeal')->__('Order #' . $orderExchange->getIncrementId() . ' has been created from #') . $order->getIncrementId());
            }
        }
        $this->_redirect('*/*/edit', array('id' => $dataParams['deal_id']));
    }

    public function sendMailAction() {
        var_dump($this->getRequest()->getParams());die;
    }
}