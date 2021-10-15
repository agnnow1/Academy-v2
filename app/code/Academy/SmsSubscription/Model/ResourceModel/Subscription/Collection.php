<?php

namespace Academy\SmsSubscription\Model\ResourceModel\Subscription;

use Academy\SmsSubscription\Model\ResourceModel\Subscription as SubscriptionResource;
use Academy\SmsSubscription\Model\Subscription;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'subscription_id';

    protected function _construct()
    {
        $this->_init(
            Subscription::class,
            SubscriptionResource::class
        );
    }
}
