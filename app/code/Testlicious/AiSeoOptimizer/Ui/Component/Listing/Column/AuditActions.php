<?php

declare(strict_types=1);

namespace Testlicious\AiSeoOptimizer\Ui\Component\Listing\Column;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

class AuditActions extends Column
{
	public const URL_PATH_GENERATE = 'aiseo/suggestion/generate';

	public function __construct(
		ContextInterface $context,
		UiComponentFactory $uiComponentFactory,
		private readonly UrlInterface $urlBuilder,
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
	if (!isset($item['entity_id'])) {
	continue;
	}

	$item[$this->getData('name')]['generate'] = [
		'href' => $this->urlBuilder->getUrl(
			self::URL_PATH_GENERATE,
			[
				'product_id' => (int)$item['entity_id'],
				'store_id' => (int)($item['store_id'] ?? 0),
			]
		),
		'label' => __('Generate AI Suggestions'),
		'confirm' => [
			'title' => __('Generate AI Suggestions'),
			'message' => __('Generate AI Suggestions for this product?')
		],
	];
	}

	return $dataSource;
	}
}
