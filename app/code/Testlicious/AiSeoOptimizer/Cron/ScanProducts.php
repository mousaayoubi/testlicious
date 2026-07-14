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
		$this->logger->info(
			'AI SEO scheduled product scan skipped because the module is disabled.'
		);
		return ;
	}

	if (!$this->config->isCronEnabled()) {
	$this->logger->info(
		'AI SEO scheduled product scan skipped because scheduled scanning is disabled.'
	);

	return;
	}

	$storeId = $this->config->getCronStoreId();
	$limit = $this->config->getCronProductLimit();

	try {
	$scannedCount = $this->productSeoScanner->scan(
		$storeId,
		$limit
	);

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
			'limit' => $limit,
			'exception' => $exception->getMessage(),
		]
	);
	}
}
}
