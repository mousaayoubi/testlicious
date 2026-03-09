<?php

declare(strict_types=1);

namespace Analyteca\ApiConnector\Api\Data;

interface SummaryInterface
{
    public const FROM = 'from';
    public const TO = 'to';
    public const TOTAL_ORDERS = 'total_orders';
    public const TOTAL_REVENUE = 'total_revenue';
    public const AVERAGE_ORDER_VALUE = 'average_order_value';
    public const CURRENCY = 'currency';
    public const STATUS_BREAKDOWN = 'status_breakdown';
    public const LAST_SYNCED_AT = 'last_synced_at';
    public const SOURCE = 'source';

    /**
     * @return string|null
     */
    public function getFrom(): ?string;

    /**
     * @param string|null $from
     * @return $this
     */
    public function setFrom(?string $from): self;

    /**
     * @return string|null
     */
    public function getTo(): ?string;

    /**
     * @param string|null $to
     * @return $this
     */
    public function setTo(?string $to): self;

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

    /**
     * @return float
     */
    public function getAverageOrderValue(): float;

    /**
     * @param float $averageOrderValue
     * @return $this
     */
    public function setAverageOrderValue(float $averageOrderValue): self;

    /**
     * @return string
     */
    public function getCurrency(): string;

    /**
     * @param string $currency
     * @return $this
     */
    public function setCurrency(string $currency): self;

    /**
     * @return \Analyteca\ApiConnector\Api\Data\SummaryStatusItemInterface[]
     */
    public function getStatusBreakdown(): array;

    /**
     * @param \Analyteca\ApiConnector\Api\Data\SummaryStatusItemInterface[] $statusBreakdown
     * @return $this
     */
    public function setStatusBreakdown(array $statusBreakdown): self;

    /**
     * @return string|null
     */
    public function getLastSyncedAt(): ?string;

    /**
     * @param string|null $lastSyncedAt
     * @return $this
     */
    public function setLastSyncedAt(?string $lastSyncedAt): self;

    /**
     * @return string
     */
    public function getSource(): string;

    /**
     * @param string $source
     * @return $this
     */
    public function setSource(string $source): self;
}
