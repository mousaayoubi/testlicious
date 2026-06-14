<?php

declare(strict_types=1);

namespace Testlicious\AiSeoOptimizer\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

class Status extends Column
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
	foreach ($dataSource['data']['items'] as &$item){
	if (empty($item['status'])) {
	continue;
	}
	switch ((string)$item['status']) {
	case 'pending':
		$item['status'] = 'Pending AI Suggestions';
		break;
	case 'suggested':
		$item['status'] = 'Suggestions Ready';
		break;
	case 'applied':
		$item['status'] = 'Applied';
		break;
	case 'ignored':
		$item['status'] = 'Ignored';
		break;
	case 'failed':
		$item['status'] = 'Failed';
		break;
	default:
		$item['status'] = ucfirst((string)$item['status']);
		break;
	}
	}

	return $dataSource;
	}
}
