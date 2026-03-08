<?php

declare(strict_types=1);

namespace Test15\Test15\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Note extends AbstractDb
{
	protected function _construct()
	{
		$this->_init('vendor_notes', 'entity_id');
	}
}
