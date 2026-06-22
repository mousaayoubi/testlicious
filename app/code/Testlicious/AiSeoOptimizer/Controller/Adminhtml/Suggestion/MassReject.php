<?php

declare(strict_types=1);

namespace Testlicious\AiSeoOptimizer\Controller\Adminhtml\Suggestion;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Ui\Component\MassAction\Filter;
use Testlicious\AiSeoOptimizer\Model\ResourceModel\Suggestion\CollectionFactory;
use Testlicious\AiSeoOptimizer\Service\SuggestionApplier;

class MassReject extends Action
{
	public const ADMIN_RESOURCE = 'Testlicious_AiSeoOptimizer::suggestions';

	public function __construct(
		Context $context,
		private readonly Filter $filter,
		private readonly CollectionFactory $collectionFactory,
		private readonly SuggestionApplier $suggestionApplier
	) {
	parent::__construct($context);
	}

	public function execute(): Redirect
	{
	$resultRedirect = $this->resultRedirectFactory->create();

	try {
	$collection = $this->filter->getCollection($this->collectionFactory->create());

	$rejected = 0;
	$failed = 0;

	foreach ($collection as $suggestion) {
	try {
	$this->suggestionApplier->reject($suggestion);
	$rejected++;
	} catch (\Throwable $exception) {
	$failed++;

	$this->messageManager->addErrorMessage(
		__('Suggestion ID %1 failed %2', $suggestion->getId(), $exception->getMessage())
	);
	}
	}

	if ($rejected > 0) {
	$this->messageManager->addSuccessMessage(
		__('%1 AI suggestion(s) rejected successfully.', $rejected)
	);
	}

	if ($failed > 0) {
	$this->messageManager->addErrorMessage(
		__('%1 AI suggestion(s) failed to reject.', $failed)
	);
	}
	} catch (\Throwable $exception) {
	$this->messageManager->addErrorMessage(
		__('Unable to reject AI suggestions: %1', $exception->getMessage())
	);
	}

	return $resultRedirect->setPath('*/suggestion/index');
	}
}
