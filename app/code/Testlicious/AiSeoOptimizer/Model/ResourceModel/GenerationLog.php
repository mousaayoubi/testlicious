<?php

declare(strict_types=1);

namespace Testlicious\AiSeoOptimizer\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class GenerationLog extends AbstractDb
{
	protected function _construct(): void
	{
	$this->_init('testlicious_aiseo_generation_log', 'log_id');
	}
}
