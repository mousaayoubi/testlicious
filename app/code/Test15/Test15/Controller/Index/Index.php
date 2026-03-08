<?php

declare(strict_types=1);

namespace Test15\Test15\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Test15\Test15\Model\NoteFactory;
use Magento\Framework\Controller\Result\JsonFactory;

class Index extends Action
{
	protected $noteFactory;
	protected $jsonFactory;

	public function __construct(
		Context $context,
		NoteFactory $noteFactory,
		JsonFactory $jsonFactory
	){
		parent::__construct($context);
		$this->noteFactory = $noteFactory;
		$this->jsonFactory = $jsonFactory;
	}

	public function execute()
	{
		$note = $this->noteFactory->create();

		$note->setData([
			'title' => 'Magento Flat Table Example',
			'content' => 'This is stored in a flat table',
			'priority' => 1
		]);

		$note->save();

		$result = $this->jsonFactory->create();

		return $result->setData([
			'message' => 'Note saved successfully',
			'id' => $note->getId()
		]);
	}
}
