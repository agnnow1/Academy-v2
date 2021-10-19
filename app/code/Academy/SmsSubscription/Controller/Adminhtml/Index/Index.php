<?php
declare(strict_types=1);

namespace Academy\SmsSubscription\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action implements HttpGetActionInterface
{
    const ADMIN_RESOURCE = 'Academy_SmsSubscription::view';

    protected PageFactory $pageFactory;

    public function __construct(
        Context $context,
        PageFactory $rawFactory
    ) {
        parent::__construct($context);
        $this->pageFactory = $rawFactory;
    }

    public function execute(): Page
    {
        $resultPage = $this->pageFactory->create();
        $resultPage->setActiveMenu('Magento_Backend::marketing_communications');
        $resultPage->getConfig()->getTitle()->prepend(__('Sms Subscriptions'));

        return $resultPage;
    }
}
