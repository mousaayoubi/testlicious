<?php

declare(strict_types=1);

namespace Testlicious\AiSeoOptimizer\Model;

use Magento\Framework\Model\AbstractModel;
use Testlicious\AiSeoOptimizer\Model\ResourceModel\GenerationLog as GenerationLogResource;

class GenerationLog extends AbstractModel
{
	protected function _construct(): void
	{
	$this->_init(GenerationLogResource::class);
	}
}
