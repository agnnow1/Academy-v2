<?php
declare(strict_types=1);

namespace Academy\SmsSubscription\Model;

use Academy\SmsSubscription\Model\ResourceModel\Subscription as SubscriptionResource;
use Magento\Customer\Model\Session;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Message\ManagerInterface;

class SubscriptionManager
{
    protected ResourceModel\Subscription $subscriptionResource;
    protected SubscriptionFactory $subscriptionFactory;
    protected ManagerInterface $manager;
    protected Session $session;

    public function __construct(
        SubscriptionResource $subscriptionResource,
        SubscriptionFactory $subscriptionFactory,
        ManagerInterface $manager,
        Session $session
    ) {
        $this->subscriptionResource = $subscriptionResource;
        $this->subscriptionFactory = $subscriptionFactory;
        $this->manager = $manager;
        $this->session = $session;
    }

    /**
     * @throws AlreadyExistsException
     */
    public function createSubscription(string $phone): void
    {
        $customerId = $this->session->getCustomerId();

        $subscription = $this->subscriptionFactory->create();
        $date = new \DateTime();

        $data = [
            'customer_id' => $customerId,
            'telephone' => $phone,
            'status' => 1,
            'created_at' => $date->format('Y-m-d H:i:s'),
            'updated_at' => $date->format('Y-m-d H:i:s')
        ];
        $subscription->setData($data);

        $this->subscriptionResource->save($subscription);
    }

    public function updateSubscription(int $id, int $status): void
    {
        $subscription = $this->subscriptionFactory->create();
        $date = new \DateTime();
        $this->subscriptionResource->load($subscription, $id, 'subscription_id');

        $subscription->setData('status', $status);
        $subscription->setData('updated_at', $date->format('Y-m-d H:i:s'));
        $this->subscriptionResource->save($subscription);
    }

    /**
     * @throws \Exception
     */
    public function deleteSubscription(Subscription $subscription): void
    {
        $this->subscriptionResource->delete($subscription);
    }
}
