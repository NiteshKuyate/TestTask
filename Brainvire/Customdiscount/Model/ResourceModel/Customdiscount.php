<?php
namespace Brainvire\Customdiscount\Model\ResourceModel;

class Customdiscount extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('brainvire_customdiscount', 'brainvire_customdiscount_id');
    }
}
?>