<?php

namespace Academy\SmsSubscription\Model;

use Magento\Framework\Model\AbstractModel;

class Subscription extends AbstractModel
{
    const STATUS_UNSUBSCRIBED = 0;
    const STATUS_SUBSCRIBED = 1;
    protected function _construct()
    {
        $this->_init('Academy\SmsSubscription\Model\ResourceModel\Subscription');
    }

    public function getSubscriptionId(): int
    {
        return (int) $this->getData('subscription_id');
    }

    public function getCustomerId(): int
    {
        return (int) $this->getData('customer_id');
    }

    public function getStatus(): string
    {
        return (string) $this->getData('status');
    }

    public function getPhone(): string
    {
        return (string) $this->getData('telephone');
    }

    public function getCreatedAt()
    {
        return $this->getData('created_at');
    }

    public function getUpdatedAt()
    {
        return $this->getData('updated_at');
    }
}
