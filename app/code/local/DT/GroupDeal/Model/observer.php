<?php
/**
 * Created by   : JetBrains PhpStorm.
 * @project     : dt-groupdeal
 * @author      : DUC THANG
 * @Date        : 10/16/14
 * @Time        : 5:01 PM
 * @copyright  Copyright (c) 2014
 *
 */
class DT_GroupDeal_Model_observer
{
    /**
     * Apply catalog price rules to product on frontend
     *
     * @param   Varien_Event_Observer $observer
     *
     * @return  Mage_CatalogRule_Model_Observer
     */
    public function processFrontFinalPrice($observer)
    {
        $product    = $observer->getEvent()->getProduct();
        $flag = Mage::helper('dt_groupdeal')->checkDeal($product);
        if ($flag) {
            $product->setFinalPrice(2.5);
        }
        return $this;
    }

    public function saveIsDeal($observer) {
        /** @var $quote Mage_Sales_Model_Quote */
        $quote = $observer->getEvent()->getQuote();
        try {
            foreach ($quote->getAllVisibleItems() as $item) {
                if ($item->getParentItem()) {
                    continue;
                }
                $deal = Mage::helper('dt_groupdeal')->checkDeal($item->getProduct());
                if ($item->getIsDeal() != $deal) {
                    $item->setIsDeal($deal);
                    if ($quote->getId()) {
                        $item->save();
                    }
                }
                if ($deal) {
                    $message = array(
                        'This product is in the Deal time',
                        'The product\'s price will be calculated as soon as the Deal time is finished.'
                    );
                    $item->setMessage($message);
                }
            }
        } catch (Exception $e) {
            Mage::logException($e);
        }
    }

    /**
     * Apply catalog price rules to product in admin
     *
     * @param   Varien_Event_Observer $observer
     *
     * @return  Mage_CatalogRule_Model_Observer
     */
    public function processAdminFinalPrice($observer)
    {
        $product = $observer->getEvent()->getProduct();
        $flag = Mage::helper('dt_groupdeal')->checkDeal($product);
        if ($flag) {
            $product->setFinalPrice(2.5);
        }
        return $this;
    }
}