<?php

declare(strict_types=1);

namespace Analyteca\ApiConnector\Model;

use Zend_Db_Expr;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\DB\Select;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Exception\InputException;
use Analyteca\ApiConnector\Api\Data\SummaryInterface;
use Analyteca\ApiConnector\Api\SummaryManagementInterface;
use Analyteca\ApiConnector\Model\Data\SummaryFactory;

class SummaryManagement implements SummaryManagementInterface
{
    public function __construct(
        private readonly ResourceConnection $resourceConnection,
        private readonly SummaryFactory $summaryFactory,
        private readonly StoreManagerInterface $storeManager
    ) {
    }

    public function getSummary(
        ?string $from = null,
        ?string $to = null,
        ?string $statuses = null
    ): SummaryInterface {
        // logic...
    }
}
