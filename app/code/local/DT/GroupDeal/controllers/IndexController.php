<?php
/**
 * Created by JetBrains PhpStorm.
 * User: DUCTHANG
 * Date: 10/20/14
 * Time: 11:35 PM
 * To change this template use File | Settings | File Templates.
 */
class DT_GroupDeal_IndexController extends Mage_Core_Controller_Front_Action
{
    public function buyAction()
    {
        if (!$this->_expireDeal()) {
            Mage::getSingleton('catalog/session')->addError('The Deal has already expired.');
            $this->getResponse()->setRedirect(Mage::registry('dt_product')->getProductUrl());
            return $this;
        }
        $this
            ->loadLayout()
            ->_initLayoutMessages('checkout/session')
            ->getLayout()->getBlock('head')->setTitle($this->__('Buy With Deal'));
        $this->renderLayout();
    }

    /**
     * Validate Deal time
     *
     * @return bool
     */
    protected function _expireDeal()
    {
        if ($this->getRequest()->getParam('id')) {
            $product = Mage::getModel('catalog/product')->load($this->getRequest()->getParam('id'));
            if ($product->getId()) {
                Mage::register('dt_product', $product);
                if (Mage::helper('dt_groupdeal')->checkDealExpired($product)) {
                    return true;
                }
            }
        }
        return false;
    }

    public function saveAction()
    {
        if (!$this->_expireDeal()) {
            Mage::getSingleton('catalog/session')->addError('The Deal has already expired.');
            $this->getResponse()->setRedirect(Mage::registry('dt_product')->getProductUrl());
            return $this;
        }
        $data = $this->getRequest()->getPost();
        $dataOrder = array();
        $dataOrder['group_deal_id'] = Mage::registry('dt_deal_' . $this->getRequest()->getParam('id'))->getId();
        foreach ($data['billing'] as $name => $content) {
            $dataOrder['billing_' . $name] = $content;
        }
        foreach ($data['shipping'] as $name => $content) {
            $dataOrder['shipping_' . $name] = $content;
        }
        try {
            $order = Mage::getModel('dt_groupdeal/order');
            $order->addData($dataOrder)->save();
        } catch (Exception $e) {
            Mage::logException($e);
        }
        Mage::getSingleton('catalog/session')->addSuccess('An confirmation email of the Deal has been sent to you. Thanks!');
        $this->getResponse()->setRedirect(Mage::registry('dt_product')->getProductUrl());
        return $this;
    }
}