<?php

declare(strict_types=1);

namespace Testlicious\AiSeoOptimizer\Service;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\LocalizedExcpetion;
use Magento\Framework\Serialize\Serializer\Json;
use Testlicious\AiSeoOptimizer\Model\Config;

class AiSuggestionGenerator
{
	public function __construct(
		private readonly ProductRepositoryInterface $productRepository,
		private readonly SeoPromptBuilder $promptBuilder,
		private readonly OpenAiClient $openAiClient,
		private readonly Json $json,
		private readonly Config $config
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

	return $this->decodeJsonResponse($rawResponse);
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
}
