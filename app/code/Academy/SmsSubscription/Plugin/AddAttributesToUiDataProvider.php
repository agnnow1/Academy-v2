<?php
declare(strict_types=1);

namespace Academy\SmsSubscription\Plugin;

use Academy\SmsSubscription\Ui\DataProvider\Subscription\ListingDataProvider as SubscriptionDataProvider;
use Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult;

class AddAttributesToUiDataProvider
{
    public function afterGetSearchResult(SubscriptionDataProvider $subject, SearchResult $result): SearchResult
    {
        if ($result->isLoaded()) {
            return $result;
        }

        $result->getSelect()->joinLeft(
            ['ce' => $result->getTable('customer_entity')],
            'ce.entity_id = main_table.customer_id',
            ['name' => new \Zend_Db_Expr('CONCAT(ce.firstname, " ", ce.lastname)')]
        );

        return $result;
    }
}
