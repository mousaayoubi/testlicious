<?php

declare(strict_types=1);

namespace Testlicious\AiSeoOptimizer\Model\ResourceModel\GenerationLog;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Testlicious\AiSeoOptimizer\Model\GenerationLog;
use Testlicious\AiSeoOptimizer\Model\ResourceModel\GenerationLog as GenerationLogResource;

class Collection extends AbstractCollection
{
	protected function _construct(): void
	{
	$this->_init(GenerationLog::class, GenerationLogResource::class);
	}
}
