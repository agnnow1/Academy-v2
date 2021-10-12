<?php
declare(strict_types=1);

namespace Academy\SmsSubscription\ViewModel;

use Academy\SmsSubscription\Model\ResourceModel\Subscription\CollectionFactory;
use Academy\SmsSubscription\Model\Subscription;
use Magento\Customer\Model\Session;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\View\LayoutInterface;
use Magento\Theme\Block\Html\Pager;

class SubscriptionList implements ArgumentInterface
{
    protected CollectionFactory $collectionFactory;
    protected Session $customerSession;
    protected LayoutInterface $layoutInterface;
    protected RequestInterface $request;
    protected ?string $pager = null;
    protected UrlInterface $url;

    public function __construct(
        CollectionFactory $collectionFactory,
        Session $customerSession,
        LayoutInterface $layoutInterface,
        RequestInterface $request,
        UrlInterface $url
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->customerSession = $customerSession;
        $this->layoutInterface = $layoutInterface;
        $this->request = $request;
        $this->url = $url;
    }

    public function getSubscriptions(): \Academy\SmsSubscription\Model\ResourceModel\Subscription\Collection
    {
        $page = $this->request->getParam('p') ?: 1;
        $pageSize = $this->request->getParam('limit') ?: 5;

        $customerId = $this->getCurrentCustomerId();
        return $this->collectionFactory->create()
            ->addFieldToFilter('customer_id', ['eq' => $customerId])
            ->setPageSize($pageSize)
            ->setCurPage($page);
    }

    private function getCurrentCustomerId(): int
    {
        return (int) $this->customerSession->getCustomerId();
    }

    public function getPagerHtml(): ?string
    {
        if (!$this->pager && $this->getSubscriptions()) {
            $pager = $this->layoutInterface->createBlock(Pager::class, 'subscriptions.pager')
                ->setAvailableLimit([5 => 5, 10 => 10, 15 => 15, 20 => 20])
                ->setShowPerPage(true)
                ->setCollection($this->getSubscriptions());

            $this->pager = $this->layoutInterface->renderElement($pager->getNameInLayout());
        }

        return $this->pager;
    }

    public function getFormAction(): string
    {
        return $this->url->getUrl('sms_subscription/index/save');
    }

    public function getUpdateUrl(): string
    {
        return $this->url->getUrl('sms_subscription/index/update');
    }

    public function getIsSubscribed(Subscription $subscription): bool
    {
        return (int)$subscription->getStatus() === Subscription::STATUS_SUBSCRIBED;
    }
}
