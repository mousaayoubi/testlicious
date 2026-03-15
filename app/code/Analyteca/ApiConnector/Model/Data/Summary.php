<?php

declare(strict_types=1);

namespace Analyteca\ApiConnector\Model\Data;

use Magento\Framework\DataObject;
use Analyteca\ApiConnector\Api\Data\SummaryInterface;

class Summary extends DataObject implements SummaryInterface
{
	public const TIMESERIES = 'timeseries';

    public function getFrom(): ?string
    {
        $value = $this->getData(self::FROM);
        return $value !== null ? (string)$value : null;
    }

    public function setFrom(?string $from): SummaryInterface
    {
        $this->setData(self::FROM, $from);
        return $this;
    }

    public function getTo(): ?string
    {
        $value = $this->getData(self::TO);
        return $value !== null ? (string)$value : null;
    }

    public function setTo(?string $to): SummaryInterface
    {
        $this->setData(self::TO, $to);
        return $this;
    }

    public function getTotalOrders(): int
    {
        return (int)$this->getData(self::TOTAL_ORDERS);
    }

    public function setTotalOrders(int $totalOrders): SummaryInterface
    {
        $this->setData(self::TOTAL_ORDERS, $totalOrders);
        return $this;
    }

    public function getTotalRevenue(): float
    {
        return (float)$this->getData(self::TOTAL_REVENUE);
    }

    public function setTotalRevenue(float $totalRevenue): SummaryInterface
    {
        $this->setData(self::TOTAL_REVENUE, $totalRevenue);
        return $this;
    }

    public function getAverageOrderValue(): float
    {
        return (float)$this->getData(self::AVERAGE_ORDER_VALUE);
    }

    public function setAverageOrderValue(float $averageOrderValue): SummaryInterface
    {
        $this->setData(self::AVERAGE_ORDER_VALUE, $averageOrderValue);
        return $this;
    }

    public function getCurrency(): string
    {
        return (string)$this->getData(self::CURRENCY);
    }

    public function setCurrency(string $currency): SummaryInterface
    {
        $this->setData(self::CURRENCY, $currency);
        return $this;
    }

    public function getStatusBreakdown(): array
    {
        return (array)$this->getData(self::STATUS_BREAKDOWN);
    }

    public function setStatusBreakdown(array $statusBreakdown): SummaryInterface
    {
        $this->setData(self::STATUS_BREAKDOWN, $statusBreakdown);
        return $this;
    }

    public function getLastSyncedAt(): ?string
    {
        $value = $this->getData(self::LAST_SYNCED_AT);
        return $value !== null ? (string)$value : null;
    }

    public function setLastSyncedAt(?string $lastSyncedAt): SummaryInterface
    {
        $this->setData(self::LAST_SYNCED_AT, $lastSyncedAt);
        return $this;
    }

    public function getSource(): string
    {
        return (string)$this->getData(self::SOURCE);
    }

    public function setSource(string $source): SummaryInterface
    {
        $this->setData(self::SOURCE, $source);
        return $this;
    }

    public function getTimeseries(): array
    {
	return $this->getData(self::TIMESERIES) ?? [];
    }

    public function setTimeseries(array $timeseries)
    {
	return $this->setData(self::TIMESERIES, $timeseries);
    }
}
