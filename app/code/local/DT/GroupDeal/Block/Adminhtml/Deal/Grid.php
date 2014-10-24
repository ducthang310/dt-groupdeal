<?php
/**
 * Created by   : JetBrains PhpStorm.
 * @project     : dt-groupdeal
 * @author      : DUC THANG
 * @Date        : 10/7/14
 * @Time        : 9:36 PM
 * @copyright  Copyright (c) 2014
 *
 */
class DT_GroupDeal_Block_Adminhtml_Deal_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('dealGrid');
        $this->setDefaultSort('group_deal_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('dt_groupdeal/deal')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('group_deal_id', array(
            'header' => Mage::helper('dt_groupdeal')->__('ID'),
            'align' => 'right',
            'width' => '10px',
            'index' => 'group_deal_id',
        ));

        $this->addColumn('group_deal_name', array(
            'header' => Mage::helper('dt_groupdeal')->__('Name'),
            'align' => 'left',
            'width' => '550px',
            'index' => 'group_deal_name'
        ));

        $this->addColumn('deal_from_date', array(
            'header' => Mage::helper('dt_groupdeal')->__('From Date'),
            'width' => '90px',
            'type' => 'date',
            'index' => 'deal_from_date',
        ));

        $this->addColumn('deal_to_date', array(
            'header' => Mage::helper('dt_groupdeal')->__('To Date'),
            'width' => '90px',
            'type' => 'date',
            'index' => 'deal_to_date',
        ));

        $this->addExportType('*/*/exportCsv', Mage::helper('dt_groupdeal')->__('CSV'));
        $this->addExportType('*/*/exportXml', Mage::helper('dt_groupdeal')->__('XML'));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('group_deal_id');
        $this->getMassactionBlock()->setFormFieldName('group_deal');

        $this->getMassactionBlock()->addItem('delete', array(
            'label'    => Mage::helper('dt_groupdeal')->__('Delete'),
            'url'      => $this->getUrl('*/*/massDelete'),
            'confirm'  => Mage::helper('dt_groupdeal')->__('Are you sure?')
        ));
        return $this;
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }
}