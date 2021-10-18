<?php
declare(strict_types=1);

namespace Academy\SmsSubscription\Model;

use Academy\SmsSubscription\Model\ResourceModel\Subscription\CollectionFactory;
use Magento\Framework\HTTP\Client\Curl;
use Magento\Framework\Serialize\Serializer\Json;

class Message
{
    const API_KEY = 'Tra1l3rParkB0y5';
    const API_URL = 'https://kbbswfakqv.cfolks.pl/api/sms/send';

    protected CollectionFactory $subscriptionCollectionFactory;
    protected Curl $curl;
    protected Json $json;

    public function __construct(
        CollectionFactory $subscriptionCollectionFactory,
        Curl $curl,
        Json $json
    ) {
        $this->subscriptionCollectionFactory = $subscriptionCollectionFactory;
        $this->curl = $curl;
        $this->json = $json;
    }

    /**
     * @throws \Exception
     */
    public function send(string $message)
    {
        $data = [];

        /** @var Subscription $subscription */
        foreach ($this->getSubscriptions() as $subscription) {
            $data[] = [
                'number' => $subscription->getPhone(),
                'message' => $message
            ];
        }

        $this->curl->addHeader("Content-Type", "application/json");
        $this->curl->addHeader("X-API-KEY", self::API_KEY);

        $this->curl->post(self::API_URL, $this->json->serialize($data));

        $status = $this->curl->getStatus();

        if ($status !== 200) {
            throw new \Exception();
        }
    }

    public function getSubscriptions(): ResourceModel\Subscription\Collection
    {
        return $this->subscriptionCollectionFactory->create()
            ->addFieldToFilter('status', ['eq' => Subscription::STATUS_SUBSCRIBED]);
    }
}
