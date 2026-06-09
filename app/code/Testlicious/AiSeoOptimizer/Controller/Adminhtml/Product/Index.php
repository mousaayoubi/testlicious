<?php

declare(strict_types=1);

namespace Testlicious\AiSeoOptimizer\Controller\Adminhtml\Product;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{
	public const ADMIN_RESOURCE = 'Testlicious_AiSeoOptimizer::audit';

	private PageFactory $resultPageFactory;

	public function __construct(
		Context $context,
		PageFactory $resultPageFactory
	) {
	parent::__construct($context);
	$this->resultPageFactory = $resultPageFactory;
	}

	public function execute(): Page
	{
	$resultPage = $this->resultPageFactory->create();
	$resultPage->setActiveMenu('Testlicious_AiSeoOptimizer::audit');
	$resultPage->getConfig()->getTitle()->prepend(__('AI SEO Product Audit'));

	return $resultPage;
	}
}
