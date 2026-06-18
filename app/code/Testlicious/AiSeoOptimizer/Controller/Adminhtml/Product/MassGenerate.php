<?php

declare(strict_types=1);

namespace Testlicious\AiSeoOptimizer\Controller\Adminhtml\Product;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Ui\Component\MassAction\Filter;
use Testlicious\AiSeoOptimizer\Model\ResourceModel\ProductSeoResult\CollectionFactory;
use Testlicious\AiSeoOptimizer\Service\AiSuggestionGenerator;
use Testlicious\AiSeoOptimizer\Service\GenerationLogger;

class MassGenerate extends Action 
{
	public const ADMIN_RESOURCE = 'Testlicious_AiSeoOptimizer::audits';

	public function __construct(
		Context $context,
		private readonly Filter $filter,
		private readonly CollectionFactory $collectionFactory,
		private readonly AiSuggestionGenerator $aiSuggestionGenerator,
		private readonly GenerationLogger $generationLogger
	) {
	parent::__construct($context);
	}

	public function execute(): Redirect
	{
	$resultRedirect = $this->resultRedirectFactory->create();

	try {
	$collection = $this->filter->getCollection($this->collectionFactory->create());

	$generated = 0;
	$failed = 0;

	foreach ($collection as $auditResult) {
	try {
	$this->aiSuggestionGenerator->generateForAudit((int)$auditResult->getId());
	$generated++;
	} catch (\Throwable $exception) {
		$failed++;

		$this->messageManager->addErrorMessage(
			__('Audit ID %1 failed: %2', $auditResult->getId(), $exception->getMessage())
		);
	}
	}

	if ($generated > 0) {
	$this->messageManager->addSuccessMessage(
		__('%1 AI suggestion(s) generated successfully.', $generated)
	);
	}

	if ($failed > 0) {
	$this->messageManager->addErrorMessage(
		__('%1 suggestion(s) failed to generate.', $failed)
	);
	}
	} catch (\Throwable $exception) {
	$this->messageManager->addErrorMessage(
		__('Unable to generate AI suggestrions: %1', $exception->getMessage())
	);
	}

	return $resultRedirect->setPath('*/*/index');
	}
}
