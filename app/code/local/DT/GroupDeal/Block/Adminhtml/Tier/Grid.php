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
class DT_GroupDeal_Block_Adminhtml_Tier_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('tierGrid');
        $this->setDefaultSort('tier_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('dt_groupdeal/tier')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('tier_id', array(
            'header' => Mage::helper('dt_groupdeal')->__('ID'),
            'align' => 'right',
            'width' => '10px',
            'index' => 'tier_id',
        ));

        $this->addColumn('tier_name', array(
            'header' => Mage::helper('dt_groupdeal')->__('Name'),
            'align' => 'left',
//            'width' => '650px',
            'index' => 'tier_name'
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
        $this->setMassactionIdField('tier_id');
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