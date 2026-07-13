<?php

declare(strict_types=1);

namespace Testlicious\AiSeoOptimizer\Cron;

use Psr\Log\LoggerInterface;
use Testlicious\AiSeoOptimizer\Model\Config;
use Testlicious\AiSeoOptimizer\Model\ProductSeoScanner;

class ScanProducts
{
public function __construct(
	private readonly ProductSeoScanner $productSeoScanner,
	private readonly Config $config,
	private readonly LoggerInterface $logger
) {
}
	public function execute(): void
	{
	if (!$this->config->isEnabled()) {
	return ;
	}

	$storeId = 0;
	$limit = max(
		1,
		$this->config->getMaxBatchSize($storeId)
	);

	try {
	$result = $this->productSeoScanner->scan(
		$storeId,
		$limit
	);

	$scannedCount = is_array($result)
		? count($result)
		: (int) $result;

	$this->logger->info(
		'AI SEO scheduled product scan completed.',
		[
			'store_id' => $storeId,
			'limit' => $limit,
			'scanned_count' => $scannedCount,
		]
	);
	} catch (\Throwable $exception) {
	$this->logger->error(
		'AI SEO scheduled product scan failed.',
		[
			'store_id' => $storeId,
			'exception' => $exception->getMessage(),
		]
	);
	}
}
}
