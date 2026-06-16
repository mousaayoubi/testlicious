<?php

declare(strict_types=1);

namespace Testlicious\AiSeoOptimizer\Service;

use Magento\Framework\Exception\AlreadyExistsException;
use Testlicious\AiSeoOptimizer\Model\GenerationLogFactory;
use Testlicious\AiSeoOptimizer\Model\ResourceModel\GenerationLog as GenerationLogResource;

class GenerationLogger
{
public function __construct(
	private readonly GenerationLogFactory $generationLogFactory,
	private readonly GenerationLogResource $generationLogResource
) {
}
/**
 * @throws AlreadyExsistsException
 */
public function logSucess(
	string $entityType,
	int $entityId,
	int $storeId,
	string $action,
	string $model,
	string $message
): void {
	$this->saveLog(
		$entityType,
		$entityId,
		$storeId,
		$action,
		'success',
		$model,
		$message,
		null
	);
}

/**
 * @throws AlreadyExistsException
 */
public function logError(
	string $entityType,
	int $entityId,
	int $storeId,
	string $action,
	string $model,
	string $errorMessage
): void {
	$this->saveLog(
		$entityType,
		$entityId,
		$storeId,
		$action,
		'error',
		$model,
		null,
		$errorMessage
	);
}

/**
 * @throws AlreadyExistsException
 */

private function saveLog(
	string $entityType,
	int $entityId,
	int $storeId,
	string $action,
	string $status,
	string $model,
	?string $message,
	?string $errorMessage
): void {
	$log = $this->generationLogFactory->create();

	$log->setData([
		'entity_type' => $entityType,
		'entity_id' => $entityId,
		'store_id' => $storeId,
		'action' => $action,
		'status' => $status,
		'model' => $model,
		'message' => $message,
		'error_message' => $errorMessage,
	]);

	$this->generationLogResource->save($log);
}
}
