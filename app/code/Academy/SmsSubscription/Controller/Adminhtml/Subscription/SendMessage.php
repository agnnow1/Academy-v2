<?php

namespace Academy\SmsSubscription\Controller\Adminhtml\Subscription;

use Academy\SmsSubscription\Model\Message;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\View\Result\PageFactory;

class SendMessage extends Action implements HttpGetActionInterface, HttpPostActionInterface
{
    protected PageFactory $pageFactory;
    protected RequestInterface $request;
    protected Message $message;

    public function __construct(
        Context $context,
        PageFactory $rawFactory,
        RequestInterface $request,
        Message $message
    ) {
        parent::__construct($context);
        $this->pageFactory = $rawFactory;
        $this->request = $request;
        $this->message = $message;
    }

    public function execute()
    {
        $resultPage = $this->pageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__('Send message'));

        $message = $this->request->getParam('message');

        if (!empty($message)) {
            try {
                $this->message->send($message);
                $this->messageManager->addSuccessMessage(__('Message sent'));
                return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)
                    ->setPath('academy_sms_subscription/subscription/sendMessage');
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('Message could not be sent, please try again later'));
            }
        }

        return $resultPage;
    }
}
