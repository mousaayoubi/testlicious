<?php

declare(strict_types=1);

namespace Testlicious\AiSeoOptimizer\Model;

use Magento\Framework\Model\AbstractModel;
use Testlicious\AiSeoOptimizer\Model\ResourceModel\Suggestion as SuggestionResource;

class Suggestion extends AbstractModel
{
	protected function _construct(): void
	{
	$this->_init(SuggestionResource::class);
	}
}
