<?php
namespace Academy\SmsSubscription\Controller\Index;

use Magento\Customer\Model\Session;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\View\Result\PageFactory;

class Index implements ActionInterface
{
    protected Session $session;
    private RedirectFactory $_redirectFactory;
    private PageFactory $_pageFactory;

    public function __construct(
        Session $customerSession,
        PageFactory $pageFactory,
        RedirectFactory $redirectFactory
    ) {
        $this->session = $customerSession;
        $this->_pageFactory = $pageFactory;
        $this->_redirectFactory = $redirectFactory;
    }

    public function execute()
    {
        if ($this->session->isLoggedIn()) {
            return $this->_pageFactory->create();
        }
        $redirect = $this->_redirectFactory->create();
        $redirect->setPath('customer/account/login');
        return $redirect;
    }
}
