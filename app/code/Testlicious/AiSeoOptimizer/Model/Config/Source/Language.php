<?php

declare(strict_types=1);

namespace Testlicious\AiSeoOptimizer\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class Language implements OptionSourceInterface
{
	public function toOptionArray(): array
	{
	return [
		['value' => 'en', 'label' => __('English')],
		['vlaue' => 'ar', 'label' => __('Arabic')],
		['value' => 'both', 'label' => __('English and Arabic')]
	];
	}
}
