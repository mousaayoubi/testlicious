<?php

declare(strict_types=1);

namespace Testlicious\AiSeoOptimizer\Ui\Component\Listing\Column;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

class SuggestionActions extends Column
{
	public const URL_PATH_APPLY = 'aiseo/suggestion/apply';
	public const URL_PATH_REJECT = 'aiseo/suggestion/reject';

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
	if (!isset($item['suggestion_id'])) {
	continue;
	}

	$suggestionId = (int)$item['suggestion_id'];
	$status = (string)($item['status'] ?? '');

	if ($status !== 'pending_review') {
		$item[$this->getData('name')]= [];
		continue;
	}

	$item[$this->getData('name')]['apply'] = [
		'href' => $this->urlBuilder->getUrl(
			self::URL_PATH_APPLY,
			['suggestion_id' => $suggestionId]
		),
		'label' => __('Apply'),
		'confirm' => [
			'title' => __('Apply AI SEO Suggestion'),
			'message' => __('Are you sure you want to apply this suggesion to the product?')
		]
	];

	$item[$this->getData('name')]['reject'] = [
		'href' => $this->urlBuilder->getUrl(
			self::URL_PATH_REJECT,
			['suggestion_id' => $suggestionId]
		),
		'label' => __('Reject'),
		'confirm' => [
			'title' => __('Reject AI SEO Suggestion'),
			'message' => __('Are you sure you want to reject this suggestion?')
		]
	];
	}

	return $dataSource;
	}
}
