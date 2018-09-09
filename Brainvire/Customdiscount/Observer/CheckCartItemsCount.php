<?php
namespace Brainvire\Customdiscount\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory; 
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\RequestInterface;


class CheckCartItemsCount implements \Magento\Framework\Event\ObserverInterface
{
   
    protected $_cart;
    protected $_messageManager;
    protected $_redirect;
    protected $_responseFactory;
    protected $_url;

    public function __construct(\Magento\Framework\App\Response\RedirectInterface $redirect, \Magento\Checkout\Model\Cart $cart, \Magento\Framework\Message\ManagerInterface $messageManager, \Magento\Framework\UrlInterface $url, \Magento\Framework\App\ResponseFactory $responseFactory, RequestInterface $request
    ) {
        $this->_redirect = $redirect;
        $this->_messageManager = $messageManager;
        $this->_cart = $cart;
        $this->_responseFactory = $responseFactory;
        $this->_url = $url;
        $this->request = $request;
    }

    public function execute(\Magento\Framework\Event\Observer $observer) {

        $cartItemsCount = $this->_cart->getQuote()->getItemsCount();
        $cartItemsQuantityCount = $this->_cart->getQuote()->getItemsQty();
        $qty = $this->request->getPost('qty'); 
        if(!isset($qty)){
            $qty = '1';
        }
     
        if($cartItemsCount > 0 || $qty != '1' )
        {
            $observer->getRequest()->setParam('product', false);
            $redirectionUrl = $this->_redirect->getRefererUrl();
            $this->request->setPostValue('return_url', $redirectionUrl); 
            $this->_responseFactory->create()->setRedirect($redirectionUrl)->sendResponse();
            $this->_messageManager->addErrorMessage('You can purchase only 1 Product with 1 quantity in each order.');
            return $this;
        }
    }
}