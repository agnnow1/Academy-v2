<?php
declare(strict_types=1);
namespace Academy\SmsSubscription\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Config
{
    private ScopeConfigInterface $scopeConfig;

    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    public function isSmsSubscriptionEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(
            'sms_subscription/general/enable',
            ScopeInterface::SCOPE_STORE
        );
    }
}
