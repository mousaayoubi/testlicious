<?php

declare(strict_types=1);

namespace Test18\Test18\Model\ResourceModel\Note;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Test18\Test18\Model\Note as NoteModel;
use Test18\Test18\Model\ResourceModel\Note as NoteResource;

class Collection extends AbstractCollection
{
	protected function _construct(): void
	{
		$this->_init(NoteModel::class, NoteResource::class);
	}
}
