<?php

declare(strict_types=1);

namespace Analyteca\ApiConnector\Model;

use DateTime;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Select;
use Magento\Framework\DB\Sql\Expression;
use Magento\Framework\Exception\InputException;
use Magento\Store\Model\StoreManagerInterface;
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
        $this->validateDateRange($from, $to);
        $normalizedStatuses = $this->normalizeStatuses($statuses);

        $connection = $this->resourceConnection->getConnection();
        $tableName = $this->resourceConnection->getTableName('sales_order');

        $summarySelect = $connection->select()->from(
            ['so' => $tableName],
            [
                'total_orders' => new Expression('COUNT(so.entity_id)'),
                'total_revenue' => new Expression('COALESCE(SUM(so.base_grand_total), 0)'),
                'average_order_value' => new Expression('COALESCE(AVG(so.base_grand_total), 0)'),
                'last_synced_at' => new Expression('MAX(so.updated_at)')
            ]
        );

        $this->applyFilters($summarySelect, $from, $to, $normalizedStatuses);
        $summaryRow = $connection->fetchRow($summarySelect) ?: [];

        $breakdownSelect = $connection->select()->from(
            ['so' => $tableName],
            [
                'status' => 'so.status',
                'total_orders' => new Expression('COUNT(so.entity_id)'),
                'total_revenue' => new Expression('COALESCE(SUM(so.base_grand_total), 0)')
            ]
        )
            ->group('so.status')
            ->order('total_orders DESC');

        $this->applyFilters($breakdownSelect, $from, $to, $normalizedStatuses);
        $breakdownRows = $connection->fetchAll($breakdownSelect) ?: [];

        $statusBreakdown = [];
        foreach ($breakdownRows as $row) {
            $statusBreakdown[] = [
                'status' => (string) ($row['status'] ?? ''),
                'total_orders' => (int) ($row['total_orders'] ?? 0),
                'total_revenue' => round((float) ($row['total_revenue'] ?? 0), 2),
            ];
        }

        $timeseriesSelect = $connection->select()->from(
    ['so' => $tableName],
    [
        'date' => new Expression('DATE(so.created_at)'),
        'total_orders' => new Expression('COUNT(so.entity_id)'),
        'total_revenue' => new Expression('COALESCE(SUM(so.base_grand_total), 0)'),
        'average_order_value' => new Expression('COALESCE(AVG(so.base_grand_total), 0)')
    ]
)
    ->group(new Expression('DATE(so.created_at)'))
    ->order(new Expression('DATE(so.created_at) ASC'));

$this->applyFilters($timeseriesSelect, $from, $to, $normalizedStatuses);
$timeseriesRows = $connection->fetchAll($timeseriesSelect) ?: [];

$timeseries = [];
foreach ($timeseriesRows as $row) {
    $timeseries[] = [
        'date' => (string) ($row['date'] ?? ''),
        'total_orders' => (int) ($row['total_orders'] ?? 0),
        'total_revenue' => round((float) ($row['total_revenue'] ?? 0), 2),
        'average_order_value' => round((float) ($row['average_order_value'] ?? 0), 2),
    ];
}

        $currency = (string) ($this->storeManager->getStore()->getBaseCurrencyCode() ?: 'USD');

        $summary = $this->summaryFactory->create();
        $summary->setFrom($from);
        $summary->setTo($to);
        $summary->setTotalOrders((int) ($summaryRow['total_orders'] ?? 0));
        $summary->setTotalRevenue(round((float) ($summaryRow['total_revenue'] ?? 0), 2));
        $summary->setAverageOrderValue(round((float) ($summaryRow['average_order_value'] ?? 0), 2));
        $summary->setCurrency($currency);
        $summary->setStatusBreakdown($statusBreakdown);
        $summary->setTimeseries($timeseries);
        $summary->setLastSyncedAt(
            isset($summaryRow['last_synced_at']) && $summaryRow['last_synced_at'] !== null
                ? (string) $summaryRow['last_synced_at']
                : null
        );
        $summary->setSource('custom_magento_api');

        return $summary;
    }

    private function applyFilters(
        Select $select,
        ?string $from,
        ?string $to,
        array $statuses
    ): void {
        if ($from) {
            $select->where('so.created_at >= ?', $from . ' 00:00:00');
        }

        if ($to) {
            $select->where('so.created_at <= ?', $to . ' 23:59:59');
        }

        if (!empty($statuses)) {
            $select->where('so.status IN (?)', $statuses);
        } else {
            $select->where('so.status <> ?', 'canceled');
        }
    }

    private function normalizeStatuses(?string $statuses): array
    {
        if ($statuses === null || trim($statuses) === '') {
            return [];
        }

        $parts = array_map('trim', explode(',', $statuses));
        $parts = array_filter($parts, static fn(string $value): bool => $value !== '');

        return array_values(array_unique($parts));
    }

    private function validateDateRange(?string $from, ?string $to): void
    {
        if ($from !== null && !$this->isValidDate($from)) {
            throw new InputException(__('Invalid "from" date. Expected format: YYYY-MM-DD'));
        }

        if ($to !== null && !$this->isValidDate($to)) {
            throw new InputException(__('Invalid "to" date. Expected format: YYYY-MM-DD'));
        }

        if ($from !== null && $to !== null && $from > $to) {
            throw new InputException(__('The "from" date cannot be greater than the "to" date.'));
        }
    }

    private function isValidDate(string $value): bool
    {
        $date = DateTime::createFromFormat('Y-m-d', $value);
        return $date !== false && $date->format('Y-m-d') === $value;
    }
}
