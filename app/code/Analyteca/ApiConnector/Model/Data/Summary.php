<?php

declare(strict_types=1);

namespace Analyteca\ApiConnector\Model\Data;

use Magento\Framework\DataObject;
use Analyteca\ApiConnector\Api\Data\SummaryInterface;

class Summary extends DataObject implements SummaryInterface
{
	public function getFrom(): ?string
	{
		$value = $this->getData(self::FROM);
		return $value !== null ? (string)$value : null;
	}
	
	public function setFrom(?string $from): SummaryInterface
	{
		return $this->setData(self::FROM, $from);
	}

	public function getTo(): ?string
	{
		$value = $this->getData(self::TO);
		return $value !== null ? (string)$value : null;
	}
	public function setTo(?string $to): SummaryInterface
	{
		return $this->setData(self::TO, $to);
	}

	public function getTotalOrders(): int
	{
		return (int)$this->getData(self::TOTAL_ORDERS);
	}

	public function setTotalOrders(int $totalOrders): SummaryInterface
	{
		return $this->setData(self::TOTAL_ORDERS, $totalOrders);
	}

	public function getTotalRevenue(): float
	{
		return (float)$this->getData(self::TOTAL_REVENUE);
	}

	public function setTotalRevenue(float $totalRevenue): SummaryInterface 
	{
		return $this->setData(self::TOTAL_REVENUE, $totalRevenue);
	}

	public function getAverageOrderValue(): float
	{
		return (float)$this->getData(self::AVERAGE_ORDER_VALUE);
	}
	public function setAverageOrderValue(float $averageOrderValue): SummaryInterface
	{
		return $this->setData(self::AVERAGE_ORDER_VALUE, $averageOrderValue);
	}

	public function getCurrency(): string
	{
		return (string)$this->getData(self::CURRENCY);
	}

	public function setCurrency(string $currency): SummaryInterface
	{
		return $this->setData(self::CURRENCY, $currency);
	}

	public function getStatusBreakdown(): array
	{
		return (array)$this->getData(self::STATUS_BREAKDOWN);
	}

	public function setStatusBreakdown(array $statusBreakdown): SummaryInterface
	{
		return $this-setData(self::STATUS_BREAKDOWN, $statusBreakdown);
	}

	public function getLastSyncedAt(): ?string
	{
		$value = $this->getData(self::LAST_SYNCED_AT);
		return $value !== null ? (string)$value : null;
	}

	public function setLastSyncedAt(?string $lastSyncedAt): SummaryInterface
	{
		return $this->setData(self::LAST_SYNCED_AT, $lastSyncedAt);
	}

	public function getSource(): string
	{
		return (string)$this->getData(self::SOURCE);
	}

	public function setSource(string $source): SummaryInterface
	{
		return $this->setData(self::SOURCE, $source);
	}
}
