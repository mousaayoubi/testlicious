<?php

declare(strict_types=1);

namespace Testlicious\AiSeoOptimizer\Service;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\LocalizedExcpetion;
use Magento\Framework\Serialize\Serializer\Json;
use Testlicious\AiSeoOptimizer\Model\Config;
use Testlicious\AiSeoOptimizer\Model\SuggestionFactory;
use Testlicious\AiSeoOptimizer\Model\ResourceModel\Suggestion as SuggestionResource;
use Magento\Framework\App\ResourceConnection;
use Testlicious\AiSeoOptimizer\Model\ProductSeoResultFactory;
use Testlicious\AiSeoOptimizer\Model\ResourceModel\ProductSeoResult as ProductSeoResultResource;

class AiSuggestionGenerator
{
	public function __construct(
		private readonly ProductRepositoryInterface $productRepository,
		private readonly SeoPromptBuilder $promptBuilder,
		private readonly OpenAiClient $openAiClient,
		private readonly Json $json,
		private readonly Config $config,
		private readonly SuggestionFactory $suggestionFactory,
		private readonly SuggestionResource $suggestionResource,
		private readonly ResourceConnection $resourceConnection,
		private readonly ProductSeoResultFactory $auditResultFactory,
		private readonly ProductSeoResultResource $auditResultResource
	) {
	}

	/**
	 * @throws LocalizedException
	 */
	public function generateByProductId(int $productId, ?int $storeId = null): array
	{
	if (!$this->config->isEnabled($storeId)) {
	throw new LocalizedException(__('Ai SEO Optimizer is disabled.'));
	}

	$product = $this->productRepository->getById($productId, false, $storeId);
	$prompt = $this->promptBuilder->build($product, $storeId);
	$rawResponse = $this->openAiClient->generateText($prompt, $storeId);

	$suggestions = $this->decodeJsonResponse($rawResponse);

	$suggestion = $this->suggestionFactory->create();

	$auditId = $this->getLatestAuditId($productId, $storeId ?? 0);

	$suggestion->setData([
		'audit_id' => $auditId,
		'entity_type' => 'product',
		'entity_id' => $productId,
		'store_id' => $storeId ?? 0,
		'suggested_meta_title' => $suggestions['suggested_meta_title'],
		'suggested_meta_description' => $suggestions['suggested_meta_description'],
		'suggested_short_description' => $suggestions['suggested_short_description'],
		'suggested_description' => $suggestions['suggested_description'] ?? '',
		'suggested_keywords_json' => $this->json->serialize($suggestions['focus_keywords']),
		'suggested_faq_json' => $this->json->serialize([]),
		'seo_notes' => $suggestions['seo_notes'],
		'status' => 'pending_review',
		'model' => $this->config->getModel($storeId),
	]);

	$this->suggestionResource->save($suggestion);

	$suggestions['suggestion_id'] = (int)$suggestion->getId();

	return $suggestions;
	}

	/**
	 * @throws LocalizedException
	 */
	private function decodeJsonResponse(string $rawResponse): array
	{
	$cleaned = trim($rawResponse);

	if (str_starts_with($cleaned, '```json')) {
	$cleaned = preg_replace('/^```json\s*/', '', $cleaned);
	$cleaned = preg_replace('/\s*```$/','', $cleaned);
	} elseif (str_starts_with($cleaned, '```')) {
		$cleaned = preg_replace('/^```\s*/', '', $cleaned);
		$cleaned = preg_replace('./s*```$/', '', $cleaned);
	}
	
	try {
	$data = $this->json->unserialize($cleaned);
	} catch (\InvalidArgumentException $exception) {
	throw new LocalizedException(
		__('OpenAI returned invalid JSON: %1', $rawResponse)
		);
	}

	if (!is_array($data)) {
	throw new LocalizedException(__('OpenAI response was not a JSON object.'));
	}

	return [
		'suggested_meta_title' => (string)($data['suggested_meta_title'] ?? ''),
		'suggested_meta_description' => (string)($data['suggested_meta_description'] ?? ''),
		'suggested_url_key' => (string)($data['suggested)url_key'] ?? ''),
		'suggested_short_description' => (string)($data['suggested_short_description'] ?? ''),
		'focus_keywords' => $data['focus_keywords'] ?? [],
		'seo_notes' => (string)($data['seo_notes'] ?? ''),
	];
	}

	/**
	 * @throws LocalizedException
	 */
	private function getLatestAuditId(int $productId, int $storeId): int
	{
	$connection = $this->resourceConnection->getConnection();
	$tableName = $this->resourceConnection->getTableName('testlicious_aiseo_audit');

	$select = $connection->select()
		      ->from($tableName, ['audit_id'])
		      ->where('entity_type = ?', 'product')
		      ->where('entity_id = ?', $productId)
		      ->where('store_id = ?', $storeId)
		      ->order('audit_id DESC')
		      ->limit(1);

	$auditId = (int)$connection->fetchOne($select);

	if ($auditId <= 0) {
	throw new LocalizedException(
		__('No SEO audir found for product ID %1. Please scan the product first, then generate AI suggestions.', $productId)
	);
	}
	return $auditId;
	}

	public function generateForAudit(int $auditId): void
	{
	$audit = $this->auditResultFactory->create();
	$this->auditResultResource->load($audit, $auditId);

	if (!$audit->getId()) {
	throw new \RuntimeException(__('Audit result not found.'));
	}

	$this->generateByProductId(
		(int)$audit->getEntityId(),
		(int)$audit->getStoreId(),
		(int)$audit->getId()
	);
	}
}
