<?php

declare(strict_types=1);

namespace Test21\Test21\Model\ResourceModel\Note;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Test21\Test21\Model\Note as NoteModel;
use Test21\Test21\Model\ResourceModel\Note as NoteResource;

class Collection extends AbstractCollection
{
	protected function _construct()
	{
	$this->_init(NoteModel::class, NoteResource::class);
	}
}
