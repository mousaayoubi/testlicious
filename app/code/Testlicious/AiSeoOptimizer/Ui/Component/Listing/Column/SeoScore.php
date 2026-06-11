<?php

declare(strict_types=1);

namespace Testlicious\AiSeoOptimizer\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

class SeoScore extends Column
{
	public function __construct(
		ContextInterface $context,
		UiComponentFactory $uiComponentFactory,
		array $components = [],
		array $data = []
	) {
	parent::__construct($context, $uiComponentFactory, $components, $data);
	}

	public function prepareDataSource(array $dataSource): array
	{
	if (!isset($dataSource['data']['items'])) {
	return $dataSource;
	}

	foreach ($dataSource['data']['items'] as &$item) {
	if (!isset($item['seo_score'])) {
	continue;
	}

	$score = (int)$item['seo_score'];

	if ($score >= 85) {
		$label = 'Excellent';
	} elseif ($score >= 65) {
		$label = 'Good';
	} elseif ($score >= 40) {
		$label = 'Needs Improvement';
	} else {
		$label = 'Needs Work';
	}

	$item['seo_score'] = sprintf('%d / 100 - %s', $score, $label);
	}
	return $dataSource;
	}
}
