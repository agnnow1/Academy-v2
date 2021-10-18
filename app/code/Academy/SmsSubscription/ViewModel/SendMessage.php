<?php
declare(strict_types=1);

namespace Academy\SmsSubscription\ViewModel;

use Magento\Framework\Data\Form\FormKey;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class SendMessage implements ArgumentInterface
{
    protected UrlInterface $url;
    protected FormKey $formKey;

    public function __construct(
        UrlInterface $url,
        FormKey $formKey
    ) {
        $this->url = $url;
        $this->formKey = $formKey;
    }

    public function getAction(): string
    {
        return $this->url->getUrl('academy_sms_subscription/subscription/sendMessage');
    }

    public function getFormKey(): string
    {
        return $this->formKey->getFormKey();
    }
}
