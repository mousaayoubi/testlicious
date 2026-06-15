<?php

declare(strict_types=1);

namespace Testlicious\AiSeoOptimizer\Model\ResourceModel\Suggestion;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Testlicious\AiSeoOptimizer\Model\ResourceModel\Suggestion as SuggestionResource;
use Testlicious\AiSeoOptimizer\Model\Suggestion;

class Collection extends AbstractCollection
{
	protected function _construct(): void
	{
	$this->_init(Suggestion::class, SuggestionResource::class);
	}
}
