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
    public function buyAction() {
        if (!$this->_expireDeal()) {
            return;
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
                if (Mage::helper('dt_groupdeal')->checkDeal($product)) {
                    Mage::register('dt_product', $product);
                    return true;
                }
            }
        }
        return false;
    }
}