<?php

declare(strict_types=1);

namespace Testlicious\AiSeoOptimizer\Controller\Adminhtml\Suggestion;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\Redirect;
use Testlicious\AiSeoOptimizer\Model\Config;
use Testlicious\AiSeoOptimizer\Service\AiSuggestionGenerator;
use Testlicious\AiSeoOptimizer\Service\GenerationLogger;

class Generate extends Action
{
	public const ADMIN_RESOURCE = 'Testlicious_AiSeoOptimizer::suggestions';

	public function __construct(
		Context $context,
		private readonly AiSuggestionGenerator $aiSuggestionGenerator,
		private readonly GenerationLogger $generationLogger,
		private readonly Config $config
	) {
	parent::__construct($context);
        }
	public function execute(): Redirect
	{
	$resultRedirect = $this->resultRedirectFactory->create();

	$productId = (int)$this->getRequest()->getParam('product_id');
	$storeIdParam = $this->getRequest()->getParam('store_id');
	$storeId = $storeIdParam !== null && $storeIdParam !== '' ? (int)$storeIdParam : 0;

	if ($productId <= 0) {
	$this->messageManager->addErrorMessage(__('Missing product ID.'));
	return $resultRedirect->setPath('adminhtml/dashboard/index');
	}

	$model = $this->config->getModel($storeId);

	try {
		$suggestions = $this->aiSuggestionGenerator->generateByProductId($productId, $storeId);

		$message = sprintf(
			'Suggestion ID: %s | Title: %s',
			$suggestions['suggestion_id'] ?? '',
			$suggestions['suggested_meta_title'] ?? ''
		);

		$this->generationLogger->logSuccess(
			'product',
			$productId,
			$storeId,
			'generate_suggestion',
			$model,
			$message
		);

	$this->messageManager->addSuccessMessage(
		__(
			'AI suggestions generated and saved. Suggestion ID: %1 | Title: %2 | Description: %3 | URL Key: %4',
			$suggestions['suggestion_id'] ?? '',
			$suggestions['suggested_meta_title'] ?? '',
			$suggestions['suggested_meta_description'] ?? '',
			$suggestions['suggested_url_key'] ?? ''
			
		)
	);
	} catch (\Throwable $exception){
	try {
	$this->generationLogger->logError(
		'product',
		$productId,
		$storeId,
		'generate_suggestion',
		$model,
		$exception->getMessage()
	);
	} catch (\Throwable $logException) {
	// Do not block the admin response if logging fails.
	}

	$this->messageManager->addErrorMessage(
		__('Unable to generate AI suggestions: %1', $exception->getMessage())
	);
	}

	return $resultRedirect->setPath('adminhtml/dashboard/index');
	}
}
