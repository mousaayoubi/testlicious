<?php

declare(strict_types=1);

namespace Analyteca\ApiConnector\Api;

use Analyteca\ApiConnector\Api\Data\SummaryInterface;

interface SummaryManagementInterface
{
    /**
     * @param string|null $from
     * @param string|null $to
     * @param string|null $statuses
     * @return \Analyteca\ApiConnector\Api\Data\SummaryInterface
     */
    public function getSummary(
        ?string $from = null,
        ?string $to = null,
        ?string $statuses = null
    ): SummaryInterface;
}
