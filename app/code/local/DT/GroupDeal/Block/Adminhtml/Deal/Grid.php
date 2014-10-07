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
            'width' => '150px',
            'index' => 'group_deal_name'
        ));

        $this->addColumn('created_at', array(
            'header' => Mage::helper('dt_groupdeal')->__('Created At'),
            'width' => '10px',
            'type' => 'datetime',
            'index' => 'created_at',
        ));

        $this->addColumn('updated_at', array(
            'header' => Mage::helper('dt_groupdeal')->__('Updated At'),
            'width' => '100px',
            'type' => 'datetime',
            'index' => 'updated_at',
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