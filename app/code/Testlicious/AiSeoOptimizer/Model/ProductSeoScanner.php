<?php

declare(strict_types=1);

namespace Testlicious\AiSeoOptimizer\Model;

use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Serialize\Serializer\Json;
use Testlicious\AiSeoOptimizer\Model\ProductSeoResultFactory;
use Testlicious\AiSeoOptimizer\Model\ResourceModel\ProductSeoResult as ProductSeoResultResource;

class ProductSeoScanner
{
	private ProductCollectionFactory $productCollectionFactory;
	private ProductSeoResultFactory $productSeoResultFactory;
	private ProductSeoResultResource $productSeoResultResource;
	private Json $json;

	public function __construct(
		ProductCollectionFactory $productCollectionFactory,
		ProductSeoResultFactory $productSeoResultFactory,
		ProductSeoResultResource $productSeoResultResource,
		Json $json
	) {
		$this->productCollectionFactory = $productCollectionFactory;
		$this->productSeoResultFactory = $productSeoResultFactory;
		$this->productSeoResultResource = $productSeoResultResource;
		$this->json = $json;
	}

	/**
	 * San products and save SEO audit results.
	 *
	 * @param int $storeId
	 * @param int $pageSize
	 * @return array
	 * @throws LocalizedException
	 */

	public function scan(int $storeId = 0, int $pageSize = 50): array
	{
	$collection = $this->productCollectionFactory->create();

	$collection->setStoreId($storeId);
	$collection->addAttributeToSelect([
		'name',
		'sku',
		'description',
		'short_description',
		'meta_title',
		'meta_description',
		'url_key',
		'status',
		'visibility'
	]);

	$collection->setPageSize($pageSize);
	$collection->setCurPage(1);

	$scanned = 0;
	$saved = 0;

	foreach ($collection as $product) {
	$issues = [];
	$score = 0;

	$name = (string)$product->getName();
	$metaTitle = trim((string)$product->getMetaTitle());
	$metaDescription = trim((string)$product->getMetaDescription());
	$description = trim((string)$product->getDescription());
	$shortDescription = trim((string)$product->getShortDescription());
	$urlKey = trim((string)$product->getUrlKey());

	$descriptionPlain = trim(Strip_tags($description));
	$descriptionLength = mb_strlen($descriptionPlain);
	$metaTitleLength = mb_strlen($metaTitle);
	$metaDescriptionLength = mb_strlen($metaDescription);

	if ($metaTitle !== '') {
	$score += 20;

	if ($metaTitleLength <= 60) {
	$score += 10;
	} else {
	$issues[] = 'Meta title is longer then 60 characters';
	}
	} else {
	$issues[] = 'Missing meta title';
	}

	if ($metaDescription !== '') {
	$score += 25;

	if ($metaDescriptionLength <= 160) {
	$score += 5;
	} else {
	$issues[] = 'Meta description is longer than 160 characters';
	}
	} else {
	$issues[] = 'Missing meta description';
	}

	if ($descriptionLength > 300) {
	$score += 20;
	} else {
	$issues[] = 'Description is too short';
	}

	if ($urlKey !== '') {
	$score += 10;
	} else {
	$issues[] = 'Missing URL key';
	}

	if ($shortDescription !== '') {
	$score += 10;
	} else {
	$issues[] = 'Missing short description';
	}

	if ($score > 100) {
	$score = 100;
	}

	if (empty($issues)) {
	$issues[] = 'No major issues';
	}

	$audit = $this->productSeoResultFactory->create();

	$audit->setData([
		'entity_type' => 'product',
		'entity_id' => (int)$product->getId(),
		'store_id' => $storeId,
		'entity_name' => $name,
		'current_meta_title' => $metaTitle ?: null,
		'current_meta_description' => $metaDEscription ?: null,
		'current_url_key' => $urlKey ?: null,
		'seo_score' => $score,
		'issues_json' => $this->json->serialize($issues),
		'status' => 'pending'
	]);

	$this->productSeoResultResource->save($audit);

	$scanned++;
	$saved++;

	}

	return [
		'scanned' => $scanned,
		'saved' => $saved
	];
	}
}
