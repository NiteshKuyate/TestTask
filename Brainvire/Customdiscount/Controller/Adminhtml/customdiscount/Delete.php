<?php
namespace Brainvire\Customdiscount\Controller\Adminhtml\customdiscount;

class Delete extends \Magento\Backend\App\Action
{
    /**
     * Delete action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        /*check if we know what should be deleted*/
        $id = $this->getRequest()->getParam('brainvire_customdiscount_id');
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            try {
                /*init model and delete*/
                $model = $this->_objectManager->create('Brainvire\Customdiscount\Model\Customdiscount');
                $model->load($id);
                $model->delete();
                /*display success message*/
                $this->messageManager->addSuccess(__('The item has been deleted.'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                /*display error message*/
                $this->messageManager->addError($e->getMessage());
                /*go back to edit form*/
                return $resultRedirect->setPath('*/*/edit', ['brainvire_customdiscount_id' => $id]);
            }
        }
        /*display error message*/
        $this->messageManager->addError(__('We can\'t find a item to delete.'));
        /*go to grid*/
        return $resultRedirect->setPath('*/*/');
    }
}