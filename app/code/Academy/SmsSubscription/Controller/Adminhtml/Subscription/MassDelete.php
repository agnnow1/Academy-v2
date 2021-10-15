<?php
declare(strict_types=1);

namespace Academy\SmsSubscription\Controller\Adminhtml\Subscription;

use Academy\SmsSubscription\Model\SubscriptionManager;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Academy\SmsSubscription\Model\ResourceModel\Subscription\CollectionFactory;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\NotFoundException;
use Magento\Ui\Component\MassAction\Filter;

class MassDelete extends Action implements HttpPostActionInterface
{
    const ADMIN_RESOURCE = 'Academy_SmsSubscription::delete';

    protected CollectionFactory $collectionFactory;

    protected Filter $filter;

    protected SubscriptionManager $subscriptionManager;

    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory,
        SubscriptionManager $subscriptionManager
    ) {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->subscriptionManager = $subscriptionManager;
        parent::__construct($context);
    }

    public function execute(): Redirect
    {
        if (!$this->getRequest()->isPost()) {
            throw new NotFoundException(__('Page not found'));
        }
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $subscriptionDeleted = 0;
        foreach ($collection->getItems() as $subscription) {
            try {
                $this->subscriptionManager->deleteSubscription($subscription);
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(
                    __('Could not delete subscription with id %1', $subscription->getSubscriptionId())
                );
            }
            $subscriptionDeleted++;
        }

        if ($subscriptionDeleted) {
            $this->messageManager->addSuccessMessage(
                __('A total of %1 record(s) have been deleted.', $subscriptionDeleted)
            );
        }
        return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('academy_sms_subscription/index/index');
    }
}
