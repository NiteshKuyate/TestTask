<?php

namespace Brainvire\Customdiscount\Model\ResourceModel\Customdiscount;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Brainvire\Customdiscount\Model\Customdiscount', 'Brainvire\Customdiscount\Model\ResourceModel\Customdiscount');
        $this->_map['fields']['page_id'] = 'main_table.page_id';
    }

}
?>