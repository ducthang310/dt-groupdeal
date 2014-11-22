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
    const XML_PATH_EMAIL_TEMPLATE_NEW_ORDER = 'dt_groupdeal/email_deal/email_new_order_template';
    const XML_PATH_EMAIL_TEMPLATE_JOIN_DEAL = 'dt_groupdeal/email_deal/email_join_deal_template';
    const XML_PATH_EMAIL_TEMPLATE_AFTER_DEAL = 'dt_groupdeal/email_deal/email_after_deal_template';
    const XML_PATH_EMAIL_DEAL_SENDER = 'dt_groupdeal/email_deal/sender_email_identity';

    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    protected function _initDeal($idFieldName = 'id')
    {
        $dealId = (int)$this->getRequest()->getParam($idFieldName);
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

    public function editAction()
    {
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

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function saveAction()
    {
        if ($postData = $this->getRequest()->getPost()) {
            try {
                $this->_initDeal();
                $dealModel = Mage::registry('current_deal');
                if (!$dealModel->getId()) {
                    $product = Mage::getModel('catalog/product')->load($postData['product_id']);
                    if ($product->getId()) {
                        $postData['current_price'] = $product->getFinalPrice();
                    } else {
                        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('dt_groupdeal')->__('The product with id %s does not exist.', $postData['product_id']));
                        $this->_redirect('*/*/');
                        return;
                    }
                }
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
    public function ordersAction()
    {
        $this->_initDeal();
        $this->loadLayout();
        $this->renderLayout();
    }

    public function newOrderAction()
    {
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
                    if (is_null($item->getParentItem()) && $item->getProductId() == $dataParams['product_id'] && $item->getIsDeal() && $item->getHasExpired()) {
//                        $product = Mage::getModel('catalog/product')->load($dataParams['product_id']);
//                        if ($product->getId()) {
//                            $quote->addProduct($product, $item->getQtyOrdered());
//                            break;// relative of product and deal is 1-1
//                        }
                        //TODO: new process to add exist item's data to quote ||| Note: this function is only active with simple product
//                        $dataItem = $item->getData();
//                        unset($dataItem['item_id']);
//                        unset($dataItem['order_id']);
//                        unset($dataItem['quote_item_id']);
//                        $item = Mage::getModel('sales/quote_item')->addData($dataItem);
//                        $item->setQuote($quote);
//                        $item->setProduct($product);
//                        $quote->getItemsCollection()->addItem($item);
                        try {
                            /* @var $item Mage_Sales_Model_Order_Item */
                            $product = Mage::getModel('catalog/product')->load($dataParams['product_id']);
                            if (!$product->getId()) {
                                return $this;
                            }

                            $info = $item->getProductOptionByCode('info_buyRequest');
                            $info = new Varien_Object($info);
                            $quote->addProduct($product, $info);
                            if (isset($dataParams['price']) && $dataParams['price']) {
                                foreach ($quote->getAllVisibleItems() as $item) {
                                    $item->setCustomPrice($dataParams['price']);
                                    $item->setOriginalCustomPrice($dataParams['price']);
                                    $item->getProduct()->setIsSuperMode(true);
                                    break;
                                }
                            }
                        } catch (Mage_Core_Exception $e){
                            Mage::logException($e);
                        }
                    }
                }

                $billingAddress = $quote->getBillingAddress()->addData($billAdd->getData());
                $shippingAddress = $quote->getShippingAddress()->addData($shipAdd->getData());

                $paymentCode = 'checkmo';
                $shippingAddress->setCollectShippingRates(true)->collectShippingRates()
                    ->setShippingMethod($order->getShippingMethod())
                    ->setPaymentMethod($paymentCode);

                $quote->getPayment()->importData(array('method' => $paymentCode));

                $quote->collectTotals()->save();

                $service = Mage::getModel('sales/service_quote', $quote);
                $service->submitAll();
                $orderExchange = $service->getOrder();
                // add comment
                $comment = 'This Order has been created for deal product from order #' . $order->getIncrementId();
                $orderExchange->addStatusHistoryComment($comment);
                $orderExchange->save();
                // send order email
                $orderExchange->sendOrderUpdateEmail(true, $comment);
                $order->setDealCreateNew(1)->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('dt_groupdeal')->__('Order #' . $orderExchange->getIncrementId() . ' has been created from #') . $order->getIncrementId());
            }
        }
        $this->_redirect('*/*/edit', array('id' => $dataParams['deal_id']));
    }

    public function sendMailAction()
    {
        $dataParams = $this->getRequest()->getParams();
        if (isset($dataParams['deal_id'])) {
            $deal = Mage::getModel('dt_groupdeal/deal')->load($dataParams['deal_id']);
            $order = Mage::getModel('sales/order')->load($dataParams['order_id']);
            if ($deal->getId() && $order->getId()) {
                try {
                    $translate = Mage::getSingleton('core/translate');
                    /* @var $translate Mage_Core_Model_Translate */
                    $translate->setTranslateInline(false);
                    $recipientEmail = $order->getCustomerEmail() ? $order->getCustomerEmail() : $order->getShippingAddress()->getEmail();
                    $dataEmail = array();
                    // data for email template: deal; order; product
                    // deal: info of deal + info after deal
                    $dataEmail['deal'] = $deal;
                    // order: order id + purchase date
                    $dataEmail['order'] = $order;
                    // item: name + + sku + base price + ordered qty
                    $items = $order->getItemsCollection();
                    foreach ($items as $item) {
                        if ($item->getProductId() == $dataParams['product_id'] && $item->getIsDeal() && $item->getHasExpired()) {
                            $item->setOriginalPrice(Mage::helper('core')->currency($item->getData('original_price'),true,false));
                            $item->setQtyOrdered((int) $item->getQtyOrdered());
                            $dataEmail['product'] =  $item;
                            break;// relative of product and deal is 1-1
                        }
                    }

                    //TODO: add final price of deal product to dataObject

                    $dataObject = new Varien_Object();
                    $dataObject->setData($dataEmail);

                    $mailTemplate = Mage::getModel('core/email_template');
                    /* @var $mailTemplate Mage_Core_Model_Email_Template */
                    $mailTemplate->setDesignConfig(array('area' => 'frontend'))
                        ->sendTransactional(
                        Mage::getStoreConfig(self::XML_PATH_EMAIL_TEMPLATE_AFTER_DEAL),
                        Mage::getStoreConfig(self::XML_PATH_EMAIL_DEAL_SENDER),
                        $recipientEmail,
                        null,
                        array('data' => $dataObject)
                    );

                    if (!$mailTemplate->getSentSuccess()) {
                        throw new Exception();
                    }

                    $order->setDealSendMail(1)->save();

                    $translate->setTranslateInline(true);
                } catch (Exception $e) {
                    Mage::logException($e);
                    $translate->setTranslateInline(true);

                    Mage::getSingleton('adminhtml/session')->addError(Mage::helper('dt_groupdeal')->__('Can\'t sent email. Please, try again later'));
                    $this->_redirect('*/*/edit', array('id' => $dataParams['deal_id']));
                    return;
                }
            }
        }
        Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('dt_groupdeal')->__('An confirmation email has been sent to Customer'));
        $this->_redirect('*/*/edit', array('id' => $dataParams['deal_id']));
    }

    public function resetAction() {
        $this->_initDeal();
        $deal = Mage::registry('current_deal');
        if ($deal->getId()) {
            if ($deal->getIsCalculated()) {
                $data = array(
                    'deal_from_date' => Mage::getModel('core/date')->date('Y-m-d H:i:s'),
                    'deal_to_date' => Mage::getModel('core/date')->date('Y-m-d H:i:s'),
                    'deal_description' => null,
                    'order_ids' => null,
                    'current_price' => null,
                    'current_qty_ordered' => 0,
                    'is_calculated' => null
                );
                if ($deal->getProductId()) {
                    $product = Mage::getModel('catalog/product')->load($deal->getProductId());
                    if ($product->getId()) {
                        $data['current_price'] = $product->getFinalPrice();
                    } else {
                        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('dt_groupdeal')->__('The product with id %s does not exist.', $postData['product_id']));
                        return;
                    }
                }
                $deal->addData($data)->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('dt_groupdeal')->__('Group Deal was successfully reset.'));
            } else {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('dt_groupdeal')->__('This deal has not been calculated.'));
            }
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('dt_groupdeal')->__('Please specify a group deal.'));
        }
        $this->_redirect('*/*/edit', array('id' => $deal->getId()));
    }

    public function calculateAction() {
        $this->_initDeal();
        $deal = Mage::registry('current_deal');
        if ($deal->getId()) {
            $result = Mage::helper('dt_groupdeal')->checkDealTime($deal);
            if ($result['status'] == DT_GroupDeal_Helper_Data::DEAL_STATUS_ENDED) {
                if (!$deal->getIsCalculated()) {
                    try {
                        $deal->setIsCalculated(1)->save();
//                        Mage::getModel('catalog/product')->load($deal->getProductId())->setSpecialPrice($deal->getCurrentPrice())->save();
                        Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('dt_groupdeal')->__('Group Deal was successfully calculated.'));
                    } catch (Exception $e) {
                        Mage::logException($e);
                    }
                } else {
                    Mage::getSingleton('adminhtml/session')->addError(Mage::helper('dt_groupdeal')->__('This deal has been calculated.'));
                }
            } else {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('dt_groupdeal')->__('Can not do this action. The deal has not ended yet.'));
            }
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('dt_groupdeal')->__('Please specify a group deal.'));
        }
        $this->_redirect('*/*/edit', array('id' => $deal->getId()));
    }
}