<?php

declare(strict_types=1);

namespace Testlicious\AiSeoOptimizer\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;

class SuggestionStatus implements OptionSourceInterface
{
	public function toOptionArray(): array
	{
	return [
		[
			'value' => 'pending_review',
			'label' => __('Pending Review'),
		],
		[
			'value' => 'applied',
			'label' => __('Applied'),
		],
		[
			'value' => 'rejected',
			'label' => __('Rejected'),
		],
	];
	}
}
