<?php

declare(strict_types=1);

namespace Analyteca\ApiConnector\Model;

use Analyteca\ApiConnector\Api\SummaryManagementInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Exception\LocalizedException;
use Psr\Log\LoggerInterface;
use Zend_Db_Expr;

class SummaryManagement implements SummaryManagementInterface
{
    private ResourceConnection $resource;
    private LoggerInterface $logger;

    public function __construct(
        ResourceConnection $resource,
        LoggerInterface $logger
    ) {
        $this->resource = $resource;
        $this->logger = $logger;
    }

    /**
     * @param string $from
     * @param string $to
     * @return string
     * @throws LocalizedException
     */
    public function getSummary(string $from, string $to): string
    {
        $this->validateDateRange($from, $to);

        try {
            $connection = $this->resource->getConnection();

            $ordersTable = $this->resource->getTableName('sales_order');
            $orderItemsTable = $this->resource->getTableName('sales_order_item');

            $summary = $this->getSummaryTotals($connection, $ordersTable, $from, $to);
            $timeseries = $this->getTimeseries($connection, $ordersTable, $from, $to);
            $statusBreakdown = $this->getRevenueByStatus($connection, $ordersTable, $from, $to);
            $topProducts = $this->getTopProducts($connection, $ordersTable, $orderItemsTable, $from, $to);

            $payload = [
                'from' => $from,
                'to' => $to,
                'revenue' => (float) ($summary['revenue'] ?? 0),
                'orders' => (int) ($summary['orders'] ?? 0),
                'aov' => (float) ($summary['aov'] ?? 0),
                'refunds' => (float) ($summary['refunds'] ?? 0),
                'timeseries' => array_values($timeseries),
                'statusBreakdown' => array_values($statusBreakdown),
                'revenueByStatus' => array_values($statusBreakdown),
                'topProducts' => array_values($topProducts),
                'source' => 'Magento',
                'lastSyncedAt' => gmdate('c'),
            ];

            return json_encode($payload, JSON_UNESCAPED_SLASHES);
        } catch (\Throwable $e) {
            $this->logger->error('Analyteca summary API failed', [
                'from' => $from,
                'to' => $to,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw new LocalizedException(__('Unable to build analytics summary.'));
        }
    }

    private function validateDateRange(string $from, string $to): void
    {
        if (!$this->isValidDateOnly($from) || !$this->isValidDateOnly($to)) {
            throw new LocalizedException(__('Dates must be in YYYY-MM-DD format.'));
        }

        $fromTs = strtotime($from . ' 00:00:00');
        $toTs = strtotime($to . ' 23:59:59');

        if ($fromTs === false || $toTs === false) {
            throw new LocalizedException(__('Invalid date range.'));
        }

        if ($fromTs > $toTs) {
            throw new LocalizedException(__('"from" cannot be later than "to".'));
        }

        $diffDays = (int) ceil(($toTs - $fromTs) / 86400);
        if ($diffDays > 366) {
            throw new LocalizedException(__('Date range cannot exceed 366 days.'));
        }
    }

    private function isValidDateOnly(string $value): bool
    {
        return (bool) preg_match('/^\d{4}-\d{2}-\d{2}$/', $value);
    }

    private function getSummaryTotals(
        \Magento\Framework\DB\Adapter\AdapterInterface $connection,
        string $ordersTable,
        string $from,
        string $to
    ): array {
        $select = $connection->select()
            ->from(
                ['so' => $ordersTable],
                [
                    'revenue' => new Zend_Db_Expr('COALESCE(SUM(so.base_grand_total), 0)'),
                    'orders' => new Zend_Db_Expr('COUNT(so.entity_id)'),
                    'aov' => new Zend_Db_Expr('COALESCE(AVG(so.base_grand_total), 0)'),
                    'refunds' => new Zend_Db_Expr('COALESCE(SUM(so.base_total_refunded), 0)'),
                ]
            )
            ->where('DATE(so.created_at) >= ?', $from)
            ->where('DATE(so.created_at) <= ?', $to)
            ->where('so.state NOT IN (?)', ['canceled']);

        return (array) $connection->fetchRow($select);
    }

    private function getTimeseries(
        \Magento\Framework\DB\Adapter\AdapterInterface $connection,
        string $ordersTable,
        string $from,
        string $to
    ): array {
        $select = $connection->select()
            ->from(
                ['so' => $ordersTable],
                [
                    'date' => new Zend_Db_Expr('DATE(so.created_at)'),
                    'revenue' => new Zend_Db_Expr('COALESCE(SUM(so.base_grand_total), 0)'),
                    'orders' => new Zend_Db_Expr('COUNT(so.entity_id)'),
                    'aov' => new Zend_Db_Expr('COALESCE(AVG(so.base_grand_total), 0)'),
                ]
            )
            ->where('DATE(so.created_at) >= ?', $from)
            ->where('DATE(so.created_at) <= ?', $to)
            ->where('so.state NOT IN (?)', ['canceled'])
            ->group(new Zend_Db_Expr('DATE(so.created_at)'))
            ->order(new Zend_Db_Expr('DATE(so.created_at) ASC'));

        $rows = $connection->fetchAll($select);

        return array_map(
            static function (array $row): array {
                return [
                    'date' => (string) ($row['date'] ?? ''),
                    'label' => !empty($row['date']) ? date('M d', strtotime((string) $row['date'])) : '',
                    'revenue' => (float) ($row['revenue'] ?? 0),
                    'orders' => (int) ($row['orders'] ?? 0),
                    'aov' => (float) ($row['aov'] ?? 0),
                ];
            },
            $rows
        );
    }

    private function getRevenueByStatus(
        \Magento\Framework\DB\Adapter\AdapterInterface $connection,
        string $ordersTable,
        string $from,
        string $to
    ): array {
        $select = $connection->select()
            ->from(
                ['so' => $ordersTable],
                [
                    'status' => 'so.status',
                    'orders' => new Zend_Db_Expr('COUNT(so.entity_id)'),
                    'revenue' => new Zend_Db_Expr('COALESCE(SUM(so.base_grand_total), 0)'),
                ]
            )
            ->where('DATE(so.created_at) >= ?', $from)
            ->where('DATE(so.created_at) <= ?', $to)
            ->where('so.state NOT IN (?)', ['canceled'])
            ->group('so.status')
            ->order('revenue DESC');

        $rows = $connection->fetchAll($select);

        return array_map(
            static function (array $row): array {
                return [
                    'status' => (string) ($row['status'] ?? 'unknown'),
                    'orders' => (int) ($row['orders'] ?? 0),
                    'revenue' => (float) ($row['revenue'] ?? 0),
                ];
            },
            $rows
        );
    }

    private function getTopProducts(
        \Magento\Framework\DB\Adapter\AdapterInterface $connection,
        string $ordersTable,
        string $orderItemsTable,
        string $from,
        string $to
    ): array {
        $select = $connection->select()
            ->from(
                ['soi' => $orderItemsTable],
                [
                    'name' => 'soi.name',
                    'sku' => 'soi.sku',
                    'qtySold' => new Zend_Db_Expr('COALESCE(SUM(soi.qty_ordered), 0)'),
                    'orders' => new Zend_Db_Expr('COUNT(DISTINCT soi.order_id)'),
                    'revenue' => new Zend_Db_Expr('COALESCE(SUM(soi.base_row_total), 0)'),
                ]
            )
            ->joinInner(
                ['so' => $ordersTable],
                'so.entity_id = soi.order_id',
                []
            )
            ->where('DATE(so.created_at) >= ?', $from)
            ->where('DATE(so.created_at) <= ?', $to)
            ->where('so.state NOT IN (?)', ['canceled'])
            ->where('soi.parent_item_id IS NULL')
            ->group(['soi.name', 'soi.sku'])
            ->order('revenue DESC')
            ->limit(10);

        $rows = $connection->fetchAll($select);

        return array_map(
            static function (array $row): array {
                return [
                    'name' => (string) ($row['name'] ?? 'Unnamed product'),
                    'sku' => (string) ($row['sku'] ?? '—'),
                    'qtySold' => (float) ($row['qtySold'] ?? 0),
                    'orders' => (int) ($row['orders'] ?? 0),
                    'revenue' => (float) ($row['revenue'] ?? 0),
                ];
            },
            $rows
        );
    }
}
