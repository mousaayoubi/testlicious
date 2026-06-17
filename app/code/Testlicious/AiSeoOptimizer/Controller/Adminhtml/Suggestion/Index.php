<?php

declare(strict_types=1);

namespace Testlicious\AiSeoOptimizer\Controller\Adminhtml\Suggestion;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{
	public const ADMIN_RESOURCE = 'Testlicious_AiSeoOptimizer::suggestions';

	public function __construct(
		Context $context,
		private readonly PageFactory $resultPageFactory
	) {
	parent::__construct($context);
	}

	public function execute(): ResultInterface
	{
	$resultPage = $this->resultPageFactory->create();

	$resultPage->setActiveMenu('Testlicious_AiSeoOptimizer::suggestions');
	$resultPage->getConfig()->getTitle()->prepend(__('AI SEO Suggestions'));

	return $resultPage;
	}
}
