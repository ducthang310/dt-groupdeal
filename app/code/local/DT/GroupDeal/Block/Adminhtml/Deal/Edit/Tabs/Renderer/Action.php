<?php
/**
 * Created by JetBrains PhpStorm.
 * User: DucThang
 * Date: 10/23/14
 * Time: 8:03 PM
 */

class DT_GroupDeal_Block_Adminhtml_Deal_Edit_Tabs_Renderer_Action
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    /**
     * Array to store all options data
     *
     * @var array
     */
    protected $_actions = array();

    public function render(Varien_Object $row)
    {
        $this->_actions = array();
        if (true) {
            $newOrderAction = array(
                '@' => array('href' => $this->getUrl('*/deal/newOrder', array('order_id'=>$row->getId(), 'product_id'=>Mage::registry('current_deal')->getProductId(), 'deal_id'=>Mage::registry('current_deal')->getId()))),
                '#' =>  Mage::helper('sales')->__('New Order')
            );
            $this->addToActions($newOrderAction);
        }
        if (true) {
            $sendMailAction = array(
                '@' => array('href' => $this->getUrl('*/deal/sendMail', array('order_id'=>$row->getId(), 'product_id'=>Mage::registry('current_deal')->getProductId(), 'deal_id'=>Mage::registry('current_deal')->getId()))),
                '#' =>  Mage::helper('sales')->__('Send Mail')
            );
            $this->addToActions($sendMailAction);
        }
        return $this->_actionsToHtml();
    }

    protected function _getEscapedValue($value)
    {
        return addcslashes(htmlspecialchars($value),'\\\'');
    }

    /**
     * Render options array as a HTML string
     *
     * @param array $actions
     * @return string
     */
    protected function _actionsToHtml(array $actions = array())
    {
        $html = array();
        $attributesObject = new Varien_Object();

        if (empty($actions)) {
            $actions = $this->_actions;
        }

        foreach ($actions as $action) {
            $attributesObject->setData($action['@']);
            $html[] = '<a ' . $attributesObject->serialize() . '>' . $action['#'] . '</a>';
        }
        return  implode($html, '<span class="separator">|</span>');
    }

    /**
     * Add one action array to all options data storage
     *
     * @param array $actionArray
     * @return void
     */
    public function addToActions($actionArray)
    {
        $this->_actions[] = $actionArray;
    }
}
