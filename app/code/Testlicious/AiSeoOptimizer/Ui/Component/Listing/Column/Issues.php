<?php

declare(strict_types=1);

namespace Testlicious\AiSeoOptimizer\Ui\Component\Listing\Column;

use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

class Issues extends Column
{
	private Json $json;

	public function __construct(
		ContextInterface $context,
		UiComponentFactory $uiComponentFactory,
		Json $json,
		array $components = [],
		array $data = []
	){
	parent::__construct($context, $uiComponentFactory, $components, $data);
	$this->json = $json;
	}

	public function prepareDataSource(array $dataSource): array
	{
	if (!isset($dataSource['data']['items'])) {
	return $dataSource;
	}

	foreach ($dataSource['data']['items'] as &$item) {
	if (empty($item['issues_json'])) {
	$item['issues_json'] = '';
	continue;
	}

	try {
		$issues = $this->json->unserialize((string)$item['issues_json']);
		if (is_array($issues)) {
	$item['issues_json'] = implode('<br />', array_map('htmlspecialchars', $issues));
		}
	} catch (\Throwable $e) {
	$item['issues_json'] = (string)$item['issues_json'];
	}
	}
	return $dataSource;
	}
}
