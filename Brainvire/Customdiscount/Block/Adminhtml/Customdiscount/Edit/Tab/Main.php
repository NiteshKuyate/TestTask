<?php

namespace Brainvire\Customdiscount\Block\Adminhtml\Customdiscount\Edit\Tab;

/**
 * Customdiscount edit form main tab
 */
class Main extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    /**
     * @var \Brainvire\Customdiscount\Model\Status
     */
    protected $_status;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        \Brainvire\Customdiscount\Model\Status $status,
        array $data = []
    ) {
        $this->_systemStore = $systemStore;
        $this->_status = $status;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form
     *
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareForm()
    {
        /* @var $model \Brainvire\Customdiscount\Model\BlogPosts */
        $model = $this->_coreRegistry->registry('customdiscount');

        $isElementDisabled = false;

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('page_');

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Discount Information')]);

        if ($model->getId()) {
            $fieldset->addField('brainvire_customdiscount_id', 'hidden', ['name' => 'brainvire_customdiscount_id']);
        }
					
        $fieldset->addField(
            'product_sku',
            'select',
            [
                'label' => __('Sku'),
                'title' => __('Sku'),
                'name' => 'product_sku',
				'required' => true,
                'options' => \Brainvire\Customdiscount\Block\Adminhtml\Customdiscount\Grid::getOptionArray0(),
                'disabled' => $isElementDisabled
            ]
        );

        $fieldset->addField(
            'discount_type',
            'select',
            [
                'label' => __('Discount Type'),
                'title' => __('Discount Type'),
                'name' => 'discount_type',
				'required' => true,
                'options' => \Brainvire\Customdiscount\Block\Adminhtml\Customdiscount\Grid::getOptionArray1(),
                'disabled' => $isElementDisabled
            ]
        );
						
        $fieldset->addField(
            'discount_amount',
            'text',
            [
                'name' => 'discount_amount',
                'label' => __('Discount Amount'),
                'title' => __('Discount Amount'),
				'required' => true,
                'disabled' => $isElementDisabled
            ]
        );
						
        $fieldset->addField(
            'status',
            'select',
            [
                'label' => __('Status'),
                'title' => __('Status'),
                'name' => 'status',
				'required' => true,
                'options' => \Brainvire\Customdiscount\Block\Adminhtml\Customdiscount\Grid::getOptionArray3(),
                'disabled' => $isElementDisabled
            ]
        );

        if (!$model->getId()) {
            $model->setData('is_active', $isElementDisabled ? '0' : '1');
        }

        $form->setValues($model->getData());
        $this->setForm($form);
		
        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('Discount Information');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Discount Information');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }
    
    public function getTargetOptionArray(){
    	return array(
    				'_self' => "Self",
					'_blank' => "New Page",
    				);
    }
}
