<?php

declare(strict_types=1);

namespace Testlicious\AiSeoOptimizer\Ui\Component\Listing\Column;

use Magento\Ui\Component\Listing\Columns\Column;

class SuggestionStatus extends Column
{
	public function prepareDataSource(array $dataSource): array
	{
	if (!isset($dataSource['data']['items'])) {
	return $dataSource;
	}

	foreach ($dataSource['data']['items'] as &$item) {
        
	if (!array_key_exists('status', $item)) {
	continue;
	}
	$status = (string)$item['status'];

	if ($status === 'pending_review') {
	$label = 'Pending Review';
	$class = 'grid-severity-notice';
	} elseif ($status === 'applied') {
	$label = 'Applied';
	$class = 'grid-severity-major';
	} elseif ($status === 'rejected') {
	$label = 'Rejected';
	$class = 'grid-severity-critical';
	} else {
	$label = $status;
	$class = 'grid-severity-minor';
	}

	$item['status'] = sprintf(
		'<span class="%s"><span>%s</span></span>',
		$class,
		htmlspecialChars($label, ENT_QUOTES, 'UTF-8')
	);
	}

	return $dataSource;
	}
}
