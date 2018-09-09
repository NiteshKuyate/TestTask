<?php
namespace Brainvire\Customdiscount\Model;

class Customdiscount extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Brainvire\Customdiscount\Model\ResourceModel\Customdiscount');
    }
}
?>