<?php
declare(strict_types=1);

namespace Academy\SmsSubscription\Controller\Index;

use Academy\SmsSubscription\Model\SubscriptionManager;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\ResultFactory;

class Update implements HttpPostActionInterface
{
    protected RequestInterface $request;
    protected SubscriptionManager $subscriptionManager;
    protected ResultFactory $resultFactory;

    public function __construct(
        RequestInterface $request,
        SubscriptionManager $subscriptionManager,
        ResultFactory $resultFactory
    ) {
        $this->request = $request;
        $this->subscriptionManager = $subscriptionManager;
        $this->resultFactory = $resultFactory;
    }

    public function execute()
    {
        $subscriptions = $this->request->getParam('subscription');
        foreach ($subscriptions as $id => $status) {
            $this->subscriptionManager->updateSubscription((int) $id, (int) $status);
        }
        $redirect = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_REDIRECT);
        $redirect->setUrl('/sms_subscription/index/index');
        return $redirect;
    }
}

