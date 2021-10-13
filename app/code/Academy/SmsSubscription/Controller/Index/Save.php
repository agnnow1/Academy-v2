<?php
declare(strict_types=1);

namespace Academy\SmsSubscription\Controller\Index;

use Academy\SmsSubscription\Model\SubscriptionManager;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Message\ManagerInterface;

class Save implements HttpPostActionInterface
{
    protected RequestInterface $request;
    protected ResultFactory $resultFactory;
    protected ManagerInterface $messageManager;
    protected SubscriptionManager $subscriptionManager;

    public function __construct(
        RequestInterface $request,
        ResultFactory $resultFactory,
        ManagerInterface $messageManager,
        SubscriptionManager $subscriptionManager
    ) {
        $this->request = $request;
        $this->resultFactory = $resultFactory;
        $this->messageManager = $messageManager;
        $this->subscriptionManager = $subscriptionManager;
    }

    public function execute()
    {
        $phone = $this->request->getParam('phone');

        try {
            $this->subscriptionManager->createSubscription($phone);
            $this->messageManager->addSuccessMessage(__('Thank you for your subscription.'));
        } catch (AlreadyExistsException $e) {
            $this->messageManager->addErrorMessage(__('Phone number is already subscribed.'));
        }

        $redirect = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_REDIRECT);
        $redirect->setUrl('/sms_subscription/index/index');
        return $redirect;
    }
}
