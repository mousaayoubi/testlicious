<?php

declare(strict_types=1);

namespace Testlicious\AiSeoOptimizer\Controller\Adminhtml\Suggestion;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\Redirect;
use Testlicious\AiSeoOptimizer\Service\AiSuggestionGenerator;

class Generate extends Action
{
	public const ADMIN_RESOURCE = 'Testlicious_AiSeoOptimizer::suggestions';

	public function __construct(
		Context $context,
		private readonly AiSuggestionGenerator $aiSuggestionGenerator
	) {
	parent::__construct($context);
        }
	public function execute(): Redirect
	{
	$resultRedirect = $this->resultRedirectFactory->create();

	$productId = (int)$this->getRequest()->getParam('product_id');
	$storeIdParam = $this->getRequest()->getParam('store_id');
	$storeId = $storeIdParam !== null && $storeIdParam !== '' ? (int)$storeIdParam : null;

	if ($productId <= 0) {
	$this->messageManager->addErrorMessage(__('Missing product ID.'));
	return $resultRedirect->setPath('*/*/index');
	}

	try {
	$suggestions = $this->aiSuggestionGenerator->generateByProductId($productId, $storeId);

	$this->messageManager->addSuccessMessage(
		__(
			'AI suggestions generated. Meta Title %1',
			$suggestions['suggested_meta_title'] ?? ''
		)
	);
	} catch (\Throwable $exception){
	$this->messageManager->addErrorMessage(
		__('Unable to generate AI suggestions: %1', $exception->getMessage())
	);
	}

	return $resultRedirect->setPath('aiseo_optimizer/product/index');
	}
}
