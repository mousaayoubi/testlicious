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
            ['value' => 'friendly', 'label' => __('Friendly')],
            ['value' => 'persuasive', 'label' => __('Persuasive')],
            ['value' => 'luxury', 'label' => __('Luxury')],
            ['value' => 'simple', 'label' => __('Simple')],
        ];
    }
}
