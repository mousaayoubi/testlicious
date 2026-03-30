<?php

declare(strict_types=1);

namespace Test21\Test21\Controller\Adminhtml\Note;

use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{
	public const AMDIN_RESOURCE = 'Test21_Test21::notes';
	
	protected $pageFactory;

	public function __construct(
		Action\Context $context,
		PageFactory $pageFactory
	){
		parent::__construct($context);
		$this->pageFactory = $pageFactory;
	}

	public function execute()
	{
		$resultPage = $this->pageFactory->create();
		$resultPage->setActiveMenu('Test21_Test21::notes');
		$resultPage->getConfig()->getTitle()->prepend(__('Notes'));

		return $resultPage;
	}
}

