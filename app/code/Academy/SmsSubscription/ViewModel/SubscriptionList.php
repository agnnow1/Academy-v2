<?php
declare(strict_types=1);

namespace Academy\SmsSubscription\ViewModel;

use Academy\SmsSubscription\Model\ResourceModel\Subscription\CollectionFactory;
use Magento\Customer\Model\Session;
use Magento\Framework\App\RequestInterface;
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

    public function __construct(
        CollectionFactory $collectionFactory,
        Session $customerSession,
        LayoutInterface $layoutInterface,
        RequestInterface $request
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->customerSession = $customerSession;
        $this->layoutInterface = $layoutInterface;
        $this->request = $request;
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
}
