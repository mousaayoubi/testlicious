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

    public function getFrom(): ?string;
    public function setFrom(?string $from): self;

    public function getTo(): ?string;
    public function setTo(?string $to): self;

    public function getTotalOrders(): int;
    public function setTotalOrders(int $totalOrders): self;

    public function getTotalRevenue(): float;
    public function setTotalRevenue(float $totalRevenue): self;

    public function getAverageOrderValue(): float;
    public function setAverageOrderValue(float $averageOrderValue): self;

    public function getCurrency(): string;
    public function setCurrency(string $currency): self;

    /**
     * Example:
     * [
     *   ["status" => "complete", "total_orders" => 12, "total_revenue" => 1299.50],
     *   ["status" => "processing", "total_orders" => 4, "total_revenue" => 180.00]
     * ]
     */
    public function getStatusBreakdown(): array;
    public function setStatusBreakdown(array $statusBreakdown): self;

    public function getLastSyncedAt(): ?string;
    public function setLastSyncedAt(?string $lastSyncedAt): self;

    public function getSource(): string;
    public function setSource(string $source): self;
}
