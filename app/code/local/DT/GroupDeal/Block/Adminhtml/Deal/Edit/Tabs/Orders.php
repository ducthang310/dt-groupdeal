<?php
/**
 * Created by JetBrains PhpStorm.
 * User: DucThang
 * Date: 10/23/14
 * Time: 8:03 PM
 */
class DT_GroupDeal_Block_Adminhtml_Deal_Edit_Tabs_Orders extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('deal_orders_grid');
        $this->setDefaultSort('created_at', 'desc');
        $this->setUseAjax(true);
    }

    protected function _prepareCollection()
    {
        $orderIds = explode(',', Mage::registry('current_deal')->getOrderIds());
        $collection = Mage::getResourceModel('sales/order_grid_collection')
            ->addFieldToSelect('entity_id')
            ->addFieldToSelect('increment_id')
            ->addFieldToSelect('customer_id')
            ->addFieldToSelect('created_at')
            ->addFieldToSelect('grand_total')
            ->addFieldToSelect('order_currency_code')
            ->addFieldToSelect('store_id')
            ->addFieldToSelect('billing_name')
            ->addFieldToSelect('shipping_name')
            ->addFieldToFilter('entity_id', $orderIds);

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('increment_id', array(
            'header'    => $this->__('Order #'),
            'width'     => '100',
            'index'     => 'increment_id',
        ));

        $this->addColumn('created_at', array(
            'header'    => $this->__('Purchase On'),
            'index'     => 'created_at',
            'type'      => 'datetime',
        ));

        /*$this->addColumn('shipping_firstname', array(
            'header'    => $this->__('Shipped to First Name'),
            'index'     => 'shipping_firstname',
        ));

        $this->addColumn('shipping_lastname', array(
            'header'    => $this->__('Shipped to Last Name'),
            'index'     => 'shipping_lastname',
        ));*/
        $this->addColumn('billing_name', array(
            'header'    => $this->__('Bill to Name'),
            'index'     => 'billing_name',
        ));

        $this->addColumn('shipping_name', array(
            'header'    => $this->__('Shipped to Name'),
            'index'     => 'shipping_name',
        ));

        $this->addColumn('grand_total', array(
            'header'    => $this->__('Order Total'),
            'index'     => 'grand_total',
            'type'      => 'currency',
            'currency'  => 'order_currency_code',
        ));

        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('store_id', array(
                'header'    => $this->__('Bought From'),
                'index'     => 'store_id',
                'type'      => 'store',
                'store_view' => true
            ));
        }

        $this->addColumn('action', array(
            'header'    => $this->__('Action'),
            'filter'    => false,
            'sortable'  => false,
            'width'     => '135px',
            'renderer'  => 'dt_groupdeal/adminhtml_deal_edit_tabs_renderer_action'
        ));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/sales_order/view', array('order_id' => $row->getId()));
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/orders', array('_current' => true));
    }

}
