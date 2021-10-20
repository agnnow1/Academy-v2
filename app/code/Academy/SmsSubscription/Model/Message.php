<?php
declare(strict_types=1);

namespace Academy\SmsSubscription\Model;

use Academy\SmsSubscription\Model\ResourceModel\Subscription\CollectionFactory;
use Magento\Framework\HTTP\Client\Curl;
use Magento\Framework\Serialize\Serializer\Json;

class Message
{
    protected CollectionFactory $subscriptionCollectionFactory;
    protected Curl $curl;
    protected Json $json;
    protected Config $config;

    public function __construct(
        CollectionFactory $subscriptionCollectionFactory,
        Curl $curl,
        Json $json,
        Config $config
    ) {
        $this->subscriptionCollectionFactory = $subscriptionCollectionFactory;
        $this->curl = $curl;
        $this->json = $json;
        $this->config = $config;
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

        if(empty($data)) {
            return;
        }

        $apiKey = $this->config->getApiKey();
        $apiUrl = $this->config->getApiUrl();
        $this->curl->addHeader("Content-Type", "application/json");
        $this->curl->addHeader("X-API-KEY", $apiKey);

        $this->curl->post($apiUrl, $this->json->serialize($data));

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
