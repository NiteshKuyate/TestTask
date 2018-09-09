<?php
namespace Brainvire\Customdiscount\Block\Adminhtml\Customdiscount;

class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * @var \Magento\Framework\Module\Manager
     */
    protected $moduleManager;

    /**
     * @var \Brainvire\Customdiscount\Model\customdiscountFactory
     */
    protected $_customdiscountFactory;

    /**
     * @var \Brainvire\Customdiscount\Model\Status
     */
    protected $_status;

    protected $collectionFactory;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Brainvire\Customdiscount\Model\customdiscountFactory $customdiscountFactory
     * @param \Brainvire\Customdiscount\Model\Status $status
     * @param \Magento\Framework\Module\Manager $moduleManager
     * @param array $data
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Brainvire\Customdiscount\Model\CustomdiscountFactory $CustomdiscountFactory,
        \Brainvire\Customdiscount\Model\Status $status,
        \Magento\Framework\Module\Manager $moduleManager,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $collectionFactory,
        array $data = []
    ) {
        $this->_customdiscountFactory = $CustomdiscountFactory;
        $this->_status = $status;
        $this->moduleManager = $moduleManager;
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('postGrid');
        $this->setDefaultSort('brainvire_customdiscount_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(false);
        $this->setVarNameFilter('post_filter');
    }

    /**
     * @return $this
     */
    protected function _prepareCollection()
    {
        $collection = $this->_customdiscountFactory->create()->getCollection();
        $this->setCollection($collection);

        parent::_prepareCollection();

        return $this;
    }

    /**
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'brainvire_customdiscount_id',
            [
                'header' => __('ID'),
                'type' => 'number',
                'index' => 'brainvire_customdiscount_id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id'
            ]
        );
			
		$this->addColumn(
			'product_sku',
			[
				'header' => __('Sku'),
				'index' => 'product_sku',
				'type' => 'options',
				'options' => \Brainvire\Customdiscount\Block\Adminhtml\Customdiscount\Grid::getOptionArray0()
			]
		);
		
		$this->addColumn(
			'discount_type',
			[
				'header' => __('Discount Type'),
				'index' => 'discount_type',
				'type' => 'options',
				'options' => \Brainvire\Customdiscount\Block\Adminhtml\Customdiscount\Grid::getOptionArray1()
			]
		);
				
    	$this->addColumn(
    		'discount_amount',
    		[
    			'header' => __('Discount Amount'),
    			'index' => 'discount_amount',
    		]
    	);
			
		$this->addColumn(
			'status',
			[
				'header' => __('Status'),
				'index' => 'status',
				'type' => 'options',
				'options' => \Brainvire\Customdiscount\Block\Adminhtml\Customdiscount\Grid::getOptionArray3()
			]
		);
					
        $this->addColumn(
            'edit',
            [
                'header' => __('Edit'),
                'type' => 'action',
                'getter' => 'getId',
                'actions' => [
                    [
                        'caption' => __('Edit'),
                        'url' => [
                            'base' => '*/*/edit'
                        ],
                        'field' => 'brainvire_customdiscount_id'
                    ]
                ],
                'filter' => false,
                'sortable' => false,
                'index' => 'stores',
                'header_css_class' => 'col-action',
                'column_css_class' => 'col-action'
            ]
        );
		
	   $this->addExportType($this->getUrl('customdiscount/*/exportCsv', ['_current' => true]),__('CSV'));
	   $this->addExportType($this->getUrl('customdiscount/*/exportExcel', ['_current' => true]),__('Excel XML'));

        $block = $this->getLayout()->getBlock('grid.bottom.links');
        if ($block) {
            $this->setChild('grid.bottom.links', $block);
        }

        return parent::_prepareColumns();
    }
	
    /**
     * @return $this
     */
    protected function _prepareMassaction()
    {

        $this->setMassactionIdField('brainvire_customdiscount_id');
        //$this->getMassactionBlock()->setTemplate('Brainvire_Customdiscount::customdiscount/grid/massaction_extended.phtml');
        $this->getMassactionBlock()->setFormFieldName('customdiscount');

        $this->getMassactionBlock()->addItem(
            'delete',
            [
                'label' => __('Delete'),
                'url' => $this->getUrl('customdiscount/*/massDelete'),
                'confirm' => __('Are you sure?')
            ]
        );

        $statuses = $this->_status->getOptionArray();

        $this->getMassactionBlock()->addItem(
            'status',
            [
                'label' => __('Change status'),
                'url' => $this->getUrl('customdiscount/*/massStatus', ['_current' => true]),
                'additional' => [
                    'visibility' => [
                        'name' => 'status',
                        'type' => 'select',
                        'class' => 'required-entry',
                        'label' => __('Status'),
                        'values' => $statuses
                    ]
                ]
            ]
        );
        return $this;
    }
		

    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('customdiscount/*/index', ['_current' => true]);
    }

    /**
     * @param \Brainvire\Customdiscount\Model\customdiscount|\Magento\Framework\Object $row
     * @return string
     */
    public function getRowUrl($row)
    {
		
        return $this->getUrl(
            'customdiscount/*/edit',
            ['brainvire_customdiscount_id' => $row->getId()]
        );
		
    }
	
	static public function getOptionArray0()
	{
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $productCollection = $objectManager->create('Magento\Catalog\Model\ResourceModel\Product\Collection');
        /** Apply filters here */
        $collection = $productCollection->addAttributeToSelect('*')
        ->load();

        $data_array=array(); 
        foreach($collection as $product):
            $data_array['0'] = "Please select product sku";
            $data_array[$product->getSku()] = $product->getSku();
        endforeach;
        return($data_array);
	}

	static public function getValueArray0()
	{
        $data_array=array();
		foreach(\Brainvire\Customdiscount\Block\Adminhtml\Customdiscount\Grid::getOptionArray0() as $k=>$v){
           $data_array[]=array('value'=>$k,'label'=>$v);		
		}
        return($data_array);

	}
	
	static public function getOptionArray1()
	{
        $data_array=array(); 
		$data_array['fixed']='Fixed';
		$data_array['percentage']='Percentage';
        return($data_array);
	}
	static public function getValueArray1()
	{
        $data_array=array();
		foreach(\Brainvire\Customdiscount\Block\Adminhtml\Customdiscount\Grid::getOptionArray1() as $k=>$v){
           $data_array[]=array('value'=>$k,'label'=>$v);		
		}
        return($data_array);

	}
	
	static public function getOptionArray3()
	{
        $data_array=array(); 
		$data_array[0]='Disactive';
		$data_array[1]='Active';
        return($data_array);
	}
	static public function getValueArray3()
	{
        $data_array=array();
		foreach(\Brainvire\Customdiscount\Block\Adminhtml\Customdiscount\Grid::getOptionArray3() as $k=>$v){
           $data_array[]=array('value'=>$k,'label'=>$v);		
		}
        return($data_array);

	}
		

}