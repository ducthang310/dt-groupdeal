<?php
/**
 * Created by JetBrains PhpStorm.
 * User: DUCTHANG
 * Date: 10/22/14
 * Time: 11:59 PM
 * To change this template use File | Settings | File Templates.
 */ 
class DT_GroupDeal_Model_Sales_Quote_Item extends Mage_Sales_Model_Quote_Item {
    /**
     * Check product representation in item
     *
     * @param   Mage_Catalog_Model_Product $product
     * @return  bool
     */
    public function representProduct($product)
    {
        $itemProduct = $this->getProduct();
        if (!$product || $itemProduct->getId() != $product->getId()) {
            return false;
        }

        /**
         * Check maybe product is planned to be a child of some quote item - in this case we limit search
         * only within same parent item
         */
        $stickWithinParent = $product->getStickWithinParent();
        if ($stickWithinParent) {
            if ($this->getParentItem() !== $stickWithinParent) {
                return false;
            }
        }

        // Check options
        $itemOptions    = $this->getOptionsByCode();
        $productOptions = $product->getCustomOptions();

        if(!$this->compareOptions($itemOptions, $productOptions)){
            return false;
        }
        if(!$this->compareOptions($productOptions, $itemOptions)){
            return false;
        }
        if (!Mage::app()->getRequest()->getParam('is_deal') != !$this->getIsDeal()) {
            return false;
        }
        return true;
    }
}