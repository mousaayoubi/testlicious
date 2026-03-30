<?php

declare(strict_types=1);

namespace Test21\Test21\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Note extends AbstractDb
{
	protected function _construct()
{
	$this->_init('test21_note', 'note_id');
}
}
