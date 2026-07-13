<?php

declare(strict_types=1);

namespace Testlicious\AiSeoOptimizer\Console\Command;

use Magento\Framework\App\Area;
use Magento\Framework\App\State;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Testlicious\AiSeoOptimizer\Model\Config;
use Testlicious\AiSeoOptimizer\Model\ProductSeoScanner;

class ScanProductsCommand extends Command
{
	private const OPTION_STORE_ID = 'store-id';
	private const OPTION_LIMIT = 'limit';

	public function __construct(
		private readonly ProductSeoScanner $productSeoScanner,
		private readonly Config $config,
		private readonly State $appState,
		?string $name = null
	) {
	parent::__construct($name);
	}

	protected function configure(): void
	{
	$this->setName('aiseo:scan:products')
      ->setDescription('Scan Magento products for SEO issues')
      ->addOption(
	      self::OPTION_STORE_ID,
	      null,
	      InputOption::VALUE_OPTIONAL,
	      'Store ID to scan',
	      '0'
      )
      ->addOption(
	      self::OPTION_LIMIT,
	      null,
	      InputOption::VALUE_OPTIONAL,
	      'Maximum number of products to scan'
      );

	parent::configure();
	}

	protected function execute(
		InputInterface $input,
		OutputInterface $output
	): int {
		try {
			$this->appState->setAreaCode(Area::AREA_ADMINHTML);
		} catch (\Exception){
			//Area code may already be set.
			}
		if (!$this->config->isEnabled()) {
			$output->writeln(
				'<error>AI SEO Optimizer is disabled in configuration.</error>'
			);

			return Command::FAILURE;
		}

		$storeId = max(
			0,
			(int) $input->getOption(self::OPTION_STORE_ID)
		);

		$configuredBatchSize = max(
			1,
			(int) $this->config->getMaxBatchSize($storeId)
		);

		$requestedLimit = $input->getOption(self::OPTION_LIMIT);

		$limit = $requestedLimit !== null
			? max(1, (int) $requestedLimit)
			: $configuredBatchSize;

		$limit = min($limit, $configuredBatchSize);

		$output->writeln('<info>Starting product SEO scan...</info>');
		$output->writeln(sprintf('Store ID: %d', $storeId));
		$output->writeln(sprintf('Product limit: %d', $limit));

		try {
			$result = $this->productSeoScanner->scan(
				$storeId,
				$limit
			);
		} catch (\Throwable $exception) {
			$output->writeln(
				sprintf(
					'<error>SEO scan failed: %s</error>',
					$exception->getMessage()
				)
			);

			return Command::FAILURE;
		}

		$scannedCount = is_array($result)
			? count($result)
			: (int) $result;

		$output->writeln(
			sprintf(
				'<info>SEO scan completed. Products scanned: %d</info>',
				$scannedCount
			)
		);

		return Command::SUCCESS;
	}
}
