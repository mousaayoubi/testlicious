<?php

declare(strict_types=1);

namespace Test15\Test15\Model\ResourceModel\Note;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Test15\Test15\Model\Note;
use Test15\Test15\Model\ResourceModel\Note as NoteResource;

class Collection extends AbstractCollection
{
	protected function _construct()
	{
		$this->_init(Note::class, NoteResource::class);
	}
}
