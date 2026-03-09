<?php

declare(strict_types=1);

namespace Analyteca\ApiConnector\Api\Data;

interface SummaryStatusItemInterface
{
    public const STATUS = 'status';
    public const TOTAL_ORDERS = 'total_orders';
    public const TOTAL_REVENUE = 'total_revenue';

    /**
     * @return string
     */
    public function getStatus(): string;

    /**
     * @param string $status
     * @return $this
     */
    public function setStatus(string $status): self;

    /**
     * @return int
     */
    public function getTotalOrders(): int;

    /**
     * @param int $totalOrders
     * @return $this
     */
    public function setTotalOrders(int $totalOrders): self;

    /**
     * @return float
     */
    public function getTotalRevenue(): float;

    /**
     * @param float $totalRevenue
     * @return $this
     */
    public function setTotalRevenue(float $totalRevenue): self;
}
