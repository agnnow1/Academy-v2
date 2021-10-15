<?php

namespace Academy\SmsSubscription\Ui\DataProvider\Subscription\Listing;

use Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult;

class Collection extends SearchResult
{
    protected function _initSelect(): void
    {
        parent::_initSelect();

        $this->addFilterToMap('name', new \Zend_Db_Expr('CONCAT(ce.firstname, " ", ce.lastname)'));
    }
}
