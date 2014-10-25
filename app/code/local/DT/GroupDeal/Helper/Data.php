<?php
/**
 * Created by   : JetBrains PhpStorm.
 * @project     : dt-groupdeal
 * @author      : DUC THANG
 * @Date        : 10/7/14
 * @Time        : 8:41 PM
 * @copyright  Copyright (c) 2014
 *
 */ 
class DT_GroupDeal_Helper_Data extends Mage_Core_Helper_Abstract
{
    const DEAL_STATUS_ENDED = 'ended';
    const DEAL_STATUS_RUNNING = 'running';
    const DEAL_STATUS_QUEUEING = 'queueing';

    public function checkDealTime($deal) {
        $dealFromTime = new DateTime($deal->getDealFromDate());
        $dealToTime = new DateTime($deal->getDealToDate());
        $currentTime = new DateTime(Mage::getModel('core/date')->date('Y-m-d H:i:s'));
        if ($dealToTime < $currentTime) {
            $result['status'] = self::DEAL_STATUS_ENDED;
        } elseif ($dealFromTime < $currentTime && $currentTime < $dealToTime) {
            $result['status'] = self::DEAL_STATUS_RUNNING;
        } else {
            $result['status'] = self::DEAL_STATUS_QUEUEING;
        }
        return $result;
    }

    public function checkProductInDeal($product) {
        if ($product && $product->getId()) {
            if (Mage::registry('dt_deal_' . $product->getId())) {
                return 1;
            }
            // check deal for $product
            $_col = Mage::getModel('dt_groupdeal/deal')->getCollection();
            $_col->addFieldToFilter('product_id', $product->getId());
            $_deal = $_col->getFirstItem();
            if ($_deal->getId() && $_deal->getIsActive()) {
                $result = $this->checkDealTime($_deal);
                if ($result['status'] == self::DEAL_STATUS_RUNNING) {
                    Mage::register('dt_deal_' . $product->getId(), $_deal);
                    return 1;
                }
            }
        }
        return 0;
    }
}