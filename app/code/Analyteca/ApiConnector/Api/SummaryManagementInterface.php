<?php

declare(strict_types=1);

namespace Analyteca\ApiConnector\Api;

interface SummaryManagementInterface
{
    /**
     * Get analytics summary for a date range.
     *
     * @param string $from
     * @param string $to
     * @return string
     */
    public function getSummary(string $from, string $to): string;
}
