<?php

declare(strict_types=1);

namespace Test18\Test18\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Note extends AbstractDb
{
	protected function _construct(): void
	{
	$this->_init('test18_note', 'entity_id');
	}
}
