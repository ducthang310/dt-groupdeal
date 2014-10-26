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
        $result = Mage::helper('dt_groupdeal')->checkDealTime(Mage::registry('current_deal'));
        if ($result['status'] == DT_GroupDeal_Helper_Data::DEAL_STATUS_ENDED) {
            if (!$row->getData('deal_create_new')) {
                $newOrderAction = array(
                    '@' => array('href' => $this->getUrl('*/deal/newOrder', array('order_id'=>$row->getId(), 'product_id'=>Mage::registry('current_deal')->getProductId(), 'deal_id'=>Mage::registry('current_deal')->getId()))),
                    '#' =>  Mage::helper('dt_groupdeal')->__('New Order')
                );
            } else {
                $newOrderAction = array(
                    '@' => array('class' => 'dt-no-action'),
                    '#' =>  Mage::helper('dt_groupdeal')->__('Order-Created'),
                    's' => true
                );
            }
            $this->addToActions($newOrderAction);
            if (!$row->getData('deal_send_mail')) {
                $sendMailAction = array(
                    '@' => array('href' => $this->getUrl('*/deal/sendMail', array('order_id'=>$row->getId(), 'product_id'=>Mage::registry('current_deal')->getProductId(), 'deal_id'=>Mage::registry('current_deal')->getId()))),
                    '#' =>  Mage::helper('dt_groupdeal')->__('Send Mail')
                );
            }else {
                $sendMailAction = array(
                    '@' => array('class' => 'dt-no-action'),
                    '#' =>  Mage::helper('dt_groupdeal')->__('Email-Sent'),
                    's' => true
                );
            }
            $this->addToActions($sendMailAction);
        } else {
            $sendMailAction = array(
                '@' => array('class' => 'dt-no-action'),
                '#' =>  Mage::helper('dt_groupdeal')->__('No Action'),
                's' => true
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
            if (isset($action['s']) && $action['s']) {
                $html[] = '<span ' . $attributesObject->serialize() . '>' . $action['#'] . '</span>';
            } else {
                $html[] = '<a ' . $attributesObject->serialize() . '>' . $action['#'] . '</a>';
            }

        }
        return  implode($html, '<br>');
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
