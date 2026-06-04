<?php

declare(strict_types=1);

namespace Testlicious\AiSeoOptimizer\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class Tone implements OptionSourceInterface
{
	public function toOptionArray(): array
	{
	return [
	['value' => 'professional', 'label' => __('Professional')],
	['value' => 'luxury', 'label' => __('Luxury')],
	['value' => 'friendly', 'label' => __('Friendly')],
	['value' => 'technical', 'label' => __('Technical')],
	['value' => 'playful', 'label' => __('Playful')]
	];
	}
}
