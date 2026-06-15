<?php

declare(strict_types=1);

namespace Testlicious\AiSeoOptimizer\Service;

use Magento\Catalog\Api\Data\ProductInterface;
use Testlicious\AiSeoOptimizer\Model\Config;

class SeoPromptBuilder
{
	public function __construct(
		private readonly Config $config
	) {
	}

	public function build(ProductInterface $product, ?int $storeId = null): string
	{
	$name = (string)$product->getName();
	$sku = (string)$product->getSku();
	$description = strip_tags((string)$product->getDescription());
	$shortDescription = strip_tags((string)$product->getShortDescription());
	$metaTitle = (string)$product->getMetaTitle();
	$metaDescription = (string)$product->getMetaDescription();
	$urlKey = (string)$product->getUrlKey();

	$language = $this->config->getDefaultLanguage($storeId);
	$tone = $this->config->getDefaultTone($storeId);

	return <<<PROMPT
You are an ecommerce SEO expert for Magento products.
Analyze the following product and generate improved SEO suggestions.

Language: ($language}
Tone: {$tone}

Product:
- Name: {$name}
- SKU: {$sku}
- URL Key: {$urlKey}
- Current Meta Title: {$metaTitle}
- Current Meta Description: {$metaDescription}
- Short Description: {$shortDescription}
- Description: {$description}

Return ONLY vlaid JSON with this exact structure:
{
"suggested_meta_title": "",
"suggested_meta_description": "",
"suggested_url_key": "",
"suggested_short_description": "",
focus_keywords": [],
"seo_notes": ""
}

Rules:
- Meta title should be under 60 characters when possible.
- Meta description should be under 160 characters when possible.
- URL key should be lowercase, hyphen-separated, and Magento-friendly.
- Do not invent unavailable product features.
- Keep the output useful for ecommerce search visibility.
PROMPT;
	}
}
