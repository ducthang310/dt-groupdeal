<?php
/**
 * Created by   : JetBrains PhpStorm.
 * @project     : dt-groupdeal
 * @author      : DUC THANG
 * @Date        : 10/7/14
 * @Time        : 11:00 PM
 * @copyright  Copyright (c) 2014
 *
 */
class DT_GroupDeal_Model_Resource_Deal extends Mage_Core_Model_Resource_Db_Abstract
{
    protected   function _construct()
    {
        $this->_init('dt_groupdeal/deal', 'group_deal_id');
    }

    protected function _afterSave(Mage_Core_Model_Abstract $object)
    {
        if ($object->getData('tier_price')) {
            $this->saveTierPrice($object);
        }
        return $this;
    }

    protected function saveTierPrice($object) {
        if ($data = $object->getData('tier_price')) {
            $query = '';
            $insert = 'INSERT INTO `' . $this->getTable('dt_groupdeal/tierprice') . '` (`tier_id`, `group_deal_id`, `tier_qty`, `tier_price`) VALUES';
            $count = 0;
            foreach ($data as $row) {
                if ($row['tier_id'] || !$row['delete']) {
                    if ($row['delete']) {
                        // delete
                        $query .= "DELETE FROM `" . $this->getTable('dt_groupdeal/tierprice') . "` WHERE `tier_id` = " . $row["tier_id"] . ";";
                    } elseif ($row['tier_id']) {
                        // update
                        $query .= "UPDATE  `" . $this->getTable('dt_groupdeal/tierprice') . "` SET  `tier_qty` =  '" . $row['tier_qty'] . "', `tier_price` =  '" . $row['tier_price'] . "' WHERE  `tier_id` = " . $row["tier_id"] . ";";
                    } else {
                        // insert
                        if ($count == 0) {
                            $insert .= " (NULL, '" . $object->getId() . "', '" . $row['tier_qty'] . "', '" . $row['tier_price'] . "')";
                        } else {
                            $insert .= ", (NULL, '" . $object->getId() . "', '" . $row['tier_qty'] . "', '" . $row['tier_price'] . "')";
                        }
                        $count++;
                    }
                }
            }
            $insert .= ';';
            $this->_getWriteAdapter()->query($query . $insert);
        }
    }

    protected function _afterLoad(Mage_Core_Model_Abstract $object)
    {
        $condition = $this->getReadConnection()->quoteInto('group_deal_id =?', $object->getId());
        $select = $this->getReadConnection()->select()
            ->from($this->getTable('dt_groupdeal/tierprice'), array('tier_id', 'tier_qty', 'tier_price'))
            ->where($condition);
        $object->setData('tier_price', $this->getReadConnection()->fetchAll($select));
        return $this;
    }
}