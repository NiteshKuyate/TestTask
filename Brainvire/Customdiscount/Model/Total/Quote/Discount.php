<?php
namespace Brainvire\Customdiscount\Model\Total\Quote;
use Brainvire\Customdiscount\Model\CustomdiscountFactory;

class Discount extends \Magento\Quote\Model\Quote\Address\Total\AbstractTotal
{
   
   protected $_modelCustomDiscountFactory ;
   public $discountAmount;
   
   public function __construct(
       CustomdiscountFactory $_modelCustomDiscountFactory
   ){
       $this->_modelCustomDiscountFactory = $_modelCustomDiscountFactory;
   }
   
   public function collect(
        \Magento\Quote\Model\Quote $quote,
        \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment,
        \Magento\Quote\Model\Quote\Address\Total $total
    ) {
        parent::collect($quote, $shippingAssignment, $total);
        $label = 'Brainvire Custom Discount';        
        $this->discountAmount = $this->getBrainvireCustomDiscount($quote,$total);
        
        $total->setDiscountDescription($label);
        $total->setDiscountAmount(-$this->discountAmount);
        $total->setBaseDiscountAmount(-$this->discountAmount);
        
        $total->setSubtotalWithDiscount($total->getSubtotal());
        $total->setBaseSubtotalWithDiscount($total->getBaseSubtotal());
        
        $total->addTotalAmount($this->getCode(), -$this->discountAmount);
        $total->addBaseTotalAmount($this->getCode(), -$this->discountAmount);

        return $this;
    }

    protected function getCustomDiscountAmount($sku, $productPriceBySku){
        /*Get Collection of module data*/
        $collection = $this->_modelCustomDiscountFactory->create()->getCollection(); 
        $collection->addFieldToFilter('product_sku',['eq' => $sku])
                   ->addFieldToFilter('status',['eq' => 1]);

        if(count($collection) > 0) {
         
         foreach($collection as $item){
             $type = $item['discount_type'];
             $value = $item['discount_amount'];

             if($type == "fixed") {
                return $value;
             } else if($type == "percentage") {
                $productPriceBySku = floatval($productPriceBySku );
                $discount = $productPriceBySku * (intval($value)/100 ) ;
                return $discount;
            }
          }
        } else {
            return 0;
        } 
    }

    protected function getBrainvireCustomDiscount($quote,$total) {
      $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
      $items = $quote->getAllItems();
      foreach($items as $item) {
        $productCollection = $objectManager
                            ->create('Magento\Catalog\Model\Product')
                            ->loadByAttribute('sku', $item->getSku());
        $productPriceBySku = $productCollection->getPrice();
        $this->discountAmount = $this->getCustomDiscountAmount($item->getSku(), $productPriceBySku);
        $item->setDiscountAmount($this->discountAmount);
        $item->setBaseDiscountAmount($this->discountAmount);
        return $this->discountAmount;         
      }
    }
}

?>
