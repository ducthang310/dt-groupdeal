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
class DT_GroupDeal_Model_Observer
{
    public function setDealForItem($observer)
    {
        $item = $observer->getQuoteItem();
        if (Mage::app()->getRequest()->getParam('is_deal')) {
            $item = ($item->getParentItem() ? $item->getParentItem() : $item);
            if (Mage::helper('dt_groupdeal')->checkProductInDeal($item->getProduct())) {
                $price = 0;
                $item->setCustomPrice($price);
                $item->setOriginalCustomPrice($price);
                $item->getProduct()->setIsSuperMode(true);
                $item->setIsDeal(1);
                $item->setHasExpired(1);
//                $item->save();
            }
        }
    }

    public function saveIsExpiredForDeal($observer)
    {
        /** @var $quote Mage_Sales_Model_Quote */
        $quote = $observer->getEvent()->getQuote();
        try {
            if ($quote->getId()) {
                foreach ($quote->getAllVisibleItems() as $item) {
                    if ($item->getParentItem()) {
                        continue;
                    }
                    if ($item->getId() && $item->getIsDeal()) {
                        $deal = Mage::helper('dt_groupdeal')->checkProductInDeal($item->getProduct());
                        if ($item->getHasExpired() != $deal) {
                            $item->setHasExpired($deal);
                            $item->save();
                        }
                        if ($deal) {
                            $message = array(
                                'This product is in the Deal time',
                                'The product\'s price will be calculated as soon as the Deal time is finished.'
                            );
                            $item->setMessage($message);
                        }
                    }
                }
            }
        } catch (Exception $e) {
            Mage::logException($e);
        }
    }

    /**
     * Add order_id for Deal if the order has Product Deal
     *
     * @param Varien_Event_Observer $observer
     * @return null
     */
    public function saveOrderIdForDeal($observer)
    {
        /** @var $orderInstance Mage_Sales_Model_Order */
        $orderInstance = $observer->getOrder();
        $items = $orderInstance->getAllItems();
        foreach ($items as $item) {
            if ($item->getParentItem()) {
                continue;
            }
            if ($item->getIsDeal() && $item->getHasExpired() && Mage::helper('dt_groupdeal')->checkProductInDeal($item->getProduct())) {
                $deal = Mage::registry('dt_deal_' . $item->getProduct()->getId());
                $deal->setData('no_update_tier', true);
                try {
                    $deal->setCurrentQtyOrdered((int)$deal->getCurrentQtyOrdered() + (int)$item->getQtyOrdered());
                    if ($deal->getTierId()) {
                        $tier = Mage::getModel('dt_groupdeal/tier')->load($deal->getTierId());
                        if ($tier->getId()) {
                            $tierPrice = $tier->getTierPrice();
                            if (0 < sizeof($tierPrice)) {
                                $currentPrice = $deal->getCurrentPrice();
                                for ($i = 0; $i < sizeof($tierPrice); $i++) {
                                    if ((int)$tierPrice[$i]['tier_qty'] <= (int)$deal->getCurrentQtyOrdered()) {
                                        $currentPrice = $tierPrice[$i]['tier_price'];
                                    } else {
                                        break;
                                    }
                                }
                                $deal->setCurrentPrice($currentPrice);
                            }
                        }
                    }

                    if (!$deal->getOrderIds()) {
                        $deal->setOrderIds($orderInstance->getId());
                    } else {
                        $deal->setOrderIds($deal->getOrderIds() . ',' . $orderInstance->getId());
                    }
                    $deal->save();
                } catch (Exception $e) {
                    Mage::logException($e);
                }
            }
        }
    }

    /**
     * Create new or edit deal of Products
     *
     * @param Varien_Event_Observer $observer
     * @return null
     */
    public function saveDeal($observer)
    {
        $data = Mage::app()->getRequest()->getPost('deal');
        $product = $observer->getEvent()->getProduct();
        $dealModel = Mage::getModel('dt_groupdeal/deal');
        if ($product->getDealId()) {
            $dealModel->load($product->getDealId());
        }
        if (! (int) $dealModel->getCurrentPrice()){
            $data['info']['current_price'] = $product->getPrice();
        }
        try {
            $dealModel->addData($data['info']);
            $dealModel->save();
            if (!$product->getDealId()) {
                $product->setDealId($dealModel->getId());
            }
        } catch (Exception $e) {
            Mage::logException($e);
        }
    }
}