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
     * Calculate price using catalog price rules of configurable product
     *
     * @param Varien_Event_Observer $observer
     *
     * @return Mage_CatalogRule_Model_Observer
     */
    public function catalogProductTypeConfigurablePrice(Varien_Event_Observer $observer)
    {
        $product = $observer->getEvent()->getProduct();
        if ($product instanceof Mage_Catalog_Model_Product
            && $product->getConfigurablePrice() !== null
        ) {
            $configurablePrice = $product->getConfigurablePrice();
            $productPriceRule = Mage::getModel('catalogrule/rule')->calcProductPriceRule($product, $configurablePrice);
            Mage::log('$configurablePrice : ' . $configurablePrice , null, 'dt_config_price.log');
            Mage::log('$productPriceRule : ' . $productPriceRule , null, 'dt_config_price.log');
            if ($productPriceRule !== null) {
                $product->setConfigurablePrice($productPriceRule);
            }
        }

        return $this;
    }

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
        $pId        = $product->getId();
        $storeId    = $product->getStoreId();

        if ($observer->hasDate()) {
            $date = $observer->getEvent()->getDate();
        } else {
            $date = Mage::app()->getLocale()->storeTimeStamp($storeId);
        }

        if ($observer->hasWebsiteId()) {
            $wId = $observer->getEvent()->getWebsiteId();
        } else {
            $wId = Mage::app()->getStore($storeId)->getWebsiteId();
        }

        if ($observer->hasCustomerGroupId()) {
            $gId = $observer->getEvent()->getCustomerGroupId();
        } elseif ($product->hasCustomerGroupId()) {
            $gId = $product->getCustomerGroupId();
        } else {
            $gId = Mage::getSingleton('customer/session')->getCustomerGroupId();
        }

        $key = "$date|$wId|$gId|$pId";
        if (!isset($this->_rulePrices[$key])) {
            $rulePrice = Mage::getResourceModel('catalogrule/rule')
                ->getRulePrice($date, $wId, $gId, $pId);
            $this->_rulePrices[$key] = $rulePrice;
        }
        if ($this->_rulePrices[$key]!==false) {
            $finalPrice = min($product->getData('final_price'), $this->_rulePrices[$key]);
            $product->setFinalPrice($finalPrice);
        }
        return $this;
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
        $storeId = $product->getStoreId();
        $date = Mage::app()->getLocale()->storeDate($storeId);
        $key = false;

        if ($ruleData = Mage::registry('rule_data')) {
            $wId = $ruleData->getWebsiteId();
            $gId = $ruleData->getCustomerGroupId();
            $pId = $product->getId();

            $key = "$date|$wId|$gId|$pId";
        }
        elseif (!is_null($product->getWebsiteId()) && !is_null($product->getCustomerGroupId())) {
            $wId = $product->getWebsiteId();
            $gId = $product->getCustomerGroupId();
            $pId = $product->getId();
            $key = "$date|$wId|$gId|$pId";
        }

        if ($key) {
            if (!isset($this->_rulePrices[$key])) {
                $rulePrice = Mage::getResourceModel('catalogrule/rule')
                    ->getRulePrice($date, $wId, $gId, $pId);
                $this->_rulePrices[$key] = $rulePrice;
            }
            if ($this->_rulePrices[$key]!==false) {
                $finalPrice = min($product->getData('final_price'), $this->_rulePrices[$key]);
                $product->setFinalPrice($finalPrice);
            }
        }

        return $this;
    }

    public function prepareCatalogProductCollectionPrices(Varien_Event_Observer $observer)
    {
        /* @var $collection Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Collection */
        $collection = $observer->getEvent()->getCollection();
        $store      = Mage::app()->getStore($observer->getEvent()->getStoreId());
        $websiteId  = $store->getWebsiteId();
        if ($observer->getEvent()->hasCustomerGroupId()) {
            $groupId = $observer->getEvent()->getCustomerGroupId();
        } else {
            /* @var $session Mage_Customer_Model_Session */
            $session = Mage::getSingleton('customer/session');
            if ($session->isLoggedIn()) {
                $groupId = Mage::getSingleton('customer/session')->getCustomerGroupId();
            } else {
                $groupId = Mage_Customer_Model_Group::NOT_LOGGED_IN_ID;
            }
        }
        if ($observer->getEvent()->hasDate()) {
            $date = $observer->getEvent()->getDate();
        } else {
            $date = Mage::app()->getLocale()->storeTimeStamp($store);
        }

        $productIds = array();
        /* @var $product Mage_Core_Model_Product */
        foreach ($collection as $product) {
            $key = implode('|', array($date, $websiteId, $groupId, $product->getId()));
            if (!isset($this->_rulePrices[$key])) {
                $productIds[] = $product->getId();
            }
        }

        if ($productIds) {
            $rulePrices = Mage::getResourceModel('catalogrule/rule')
                ->getRulePrices($date, $websiteId, $groupId, $productIds);
            foreach ($productIds as $productId) {
                $key = implode('|', array($date, $websiteId, $groupId, $productId));
                $this->_rulePrices[$key] = isset($rulePrices[$productId]) ? $rulePrices[$productId] : false;
            }
        }

        return $this;
    }
}