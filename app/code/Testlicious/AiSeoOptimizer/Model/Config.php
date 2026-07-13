<?php

declare(strict_types=1);

namespace Testlicious\AiSeoOptimizer\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Config
{
	private const XML_PATH_ENABLED = 'aiseo_optimizer/general/enabled';
	private const XML_PATH_DEFAULT_LANGUAGE = 'aiseo_optimizer/general/default_language';
	private const XML_PATH_DEFAULT_TONE = 'aiseo_optimizer/general/default_tone';
	private const XML_PATH_OPENAI_API_KEY = 'aiseo_optimizer/openai/api_key';
	private const XML_PATH_OPENAI_MODEL = 'aiseo_optimizer/openai/model';

	private const XML_PATH_MAX_BATCH_SIZE = 'aiseo_optimizer/general/max_batch_size';

	public function __construct(
		private readonly ScopeConfigInterface $scopeConfig
	) {
	}

	public function isEnabled(?int $storeId = null): bool
	{
	return $this->scopeConfig->isSetFlag(
		self::XML_PATH_ENABLED,
		ScopeInterface::SCOPE_STORE,
		$storeId
	);
	}

	public function getApiKey(?int $storeId = null): string
	{
	return trim((string)$this->scopeConfig->getValue(
		self::XML_PATH_OPENAI_API_KEY,
		ScopeInterface::SCOPE_STORE,
		$storeId
	));
	}

	public function getModel(?int $storeId = null): string
	{
	$model = trim((string)$this->scopeConfig->getValue(
		self::XML_PATH_OPENAI_MODEL,
		ScopeInterface::SCOPE_STORE,
		$storeId
	));

	return $model !== '' ? $model : 'gpt-5-nano';
	}

	public function getDefaultLanguage(?int $storeId = null): string
	{
	$language = trim((string)$this->scopeConfig->getValue(
		self::XML_PATH_DEFAULT_LANGUAGE,
		ScopeInterface::SCOPE_STORE,
		$storeId
	));

	return $language !== '' ? $language : 'English';
	}

	public function getDefaultTone(?int $storeId = null): string
	{
	$tone = trim((string)$this->scopeConfig->getValue(
		self::XML_PATH_DEFAULT_TONE,
		ScopeInterface::SCOPE_STORE,
		$storeId
	));

	return $tone !== '' ? $tone : 'Professional';
	}

	public function getMaxBatchSize(?int $storeId = null): int
	{
	return max(
		1,
		(int) $this->scopeConfig->getValue(
			self::XML_PATH_MAX_BATCH_SIZE,
			ScopeInterface::SCOPE_STORE,
			$storeId
		)
	);
	}
}
