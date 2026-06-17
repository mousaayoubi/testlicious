<?php

declare(strict_types=1);

namespace Testlicious\AiSeoOptimizer\Service;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;
use Testlicious\AiSeoOptimizer\Model\Suggestion;
use Testlicious\AiSeoOptimizer\Model\ResourceModel\Suggestion as SuggestionResource;

class SuggestionApplier
{
	public function __construct(
		private readonly ProductRepositoryInterface $productRepository,
		private readonly SuggestionResource $suggestionResource
	) {
	}
	
	public function apply(Suggestion $suggestion): void
	{
	if ($suggestion->getData('entity_type') !== 'product') {
	throw new LocalizedException(__('Only product suggestions can be applied.'));
	}

	if ($suggestion->getData('status') === 'applied') {
	throw new LocalizedException(__('This suggestion has already been applied.'));
	}

	$productId = (int)$suggestion->getData('entity_id');
	$storeId = (int)$suggestion->getData('store_id');

	if ($productId <= 0) {
	throw new LocalizedException(__('Invalid product ID.'));
	}

	$product = $this->productRepository->getById($productId, false, $storeId, true);

	$metaTitle = trim((string)$suggestion->getData('suggested_meta_title'));
	$metaDescription = trim((string)$suggestion->getData('suggested_meta_description'));
	$metaKeywords = trim((string)$suggestion->getData('suggested_meta_keyword'));

	if ($metaTitle !== '') {
	$product->setCustomAttribute('meta_title', $metaTitle);
	}

	if ($metaDescription !== '') {
	$product->setCustomAttribute('meta_description', $metaDescription);
	}

	if ($metaKeywords !== '') {
	$product->setCustomAttribute('meta_keyword', $metaKeywords);
	}

	$this->productRepository->save($product);

	$suggestion->setData('status', 'applied');
	$this->suggestionResource->save($suggestion);
	}

	public function reject(Suggestion $suggestion): void
	{
	if ($suggestion->getData('status') === 'applied') {
	throw new LocalizedException(__('Applied suggestions cannot be rejected.'));
	}
	
	$suggestion->setData('status', 'rejected');
	$this->suggestionResource->save($suggestion);
	}
}
