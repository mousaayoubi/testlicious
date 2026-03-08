<?php

declare(strict_types=1);

namespace Test15\Test15\Model;

use Magento\Framework\Model\AbstractModel;

class Note extends AbstractModel
{
	protected function _construct()
	{
		$this->_init(\Test15\Test15\Model\ResourceModel\Note::class);
	}
}
