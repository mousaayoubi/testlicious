<?php

declare(strict_types=1);

namespace Testlicious\AiSeoOptimizer\Model;

use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Serialize\Serializer\Json;
use Testlicious\AiSeoOptimizer\Model\ProductSeoResultFactory;
use Testlicious\AiSeoOptimizer\Model\ResourceModel\ProductSeoResult as ProductSeoResultResource;
use Testlicious\AiSeoOptimizer\Model\ResourceModel\ProductSeoResult\CollectionFactory as ProductSeoResultCollectionFactory;

class ProductSeoScanner
{
    private ProductCollectionFactory $productCollectionFactory;

    private ProductSeoResultFactory $productSeoResultFactory;

    private ProductSeoResultResource $productSeoResultResource;

    private ProductSeoResultCollectionFactory $productSeoResultCollectionFactory;

    private Json $json;

    public function __construct(
        ProductCollectionFactory $productCollectionFactory,
        ProductSeoResultFactory $productSeoResultFactory,
        ProductSeoResultResource $productSeoResultResource,
        ProductSeoResultCollectionFactory $productSeoResultCollectionFactory,
        Json $json
    ) {
        $this->productCollectionFactory = $productCollectionFactory;
        $this->productSeoResultFactory = $productSeoResultFactory;
        $this->productSeoResultResource = $productSeoResultResource;
        $this->productSeoResultCollectionFactory = $productSeoResultCollectionFactory;
        $this->json = $json;
    }

    /**
     * Scan products and save SEO audit results.
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
        $created = 0;
        $updated = 0;

        foreach ($collection as $product) {
            $issues = [];
            $score = 0;

            $name = (string)$product->getName();
            $metaTitle = trim((string)$product->getMetaTitle());
            $metaDescription = trim((string)$product->getMetaDescription());
            $description = trim((string)$product->getDescription());
            $shortDescription = trim((string)$product->getShortDescription());
            $urlKey = trim((string)$product->getUrlKey());

            $descriptionPlain = trim(strip_tags($description));
            $descriptionLength = mb_strlen($descriptionPlain);
            $metaTitleLength = mb_strlen($metaTitle);
            $metaDescriptionLength = mb_strlen($metaDescription);

            if ($metaTitle !== '') {
                $score += 20;

                if ($metaTitleLength <= 60) {
                    $score += 10;
                } else {
                    $issues[] = 'Meta title is longer than 60 characters';
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

            $audit = $this->getExistingAudit(
                (int)$product->getId(),
                $storeId
            );

            $isNew = !$audit->getId();

            $audit->setData('entity_type', 'product');
            $audit->setData('entity_id', (int)$product->getId());
            $audit->setData('store_id', $storeId);
            $audit->setData('entity_name', $name);
            $audit->setData('current_meta_title', $metaTitle ?: null);
            $audit->setData('current_meta_description', $metaDescription ?: null);
            $audit->setData('current_url_key', $urlKey ?: null);
            $audit->setData('seo_score', $score);
            $audit->setData('issues_json', $this->json->serialize($issues));
            $audit->setData('status', 'pending');

            $this->productSeoResultResource->save($audit);

            $scanned++;

            if ($isNew) {
                $created++;
            } else {
                $updated++;
            }
        }

        return [
            'scanned' => $scanned,
            'created' => $created,
            'updated' => $updated,
            'saved' => $created + $updated
        ];
    }

    private function getExistingAudit(int $productId, int $storeId): ProductSeoResult
    {
	$collection = $this->productSeoResultCollectionFactory->create();

	$collection->addFieldToFilter('entity_type', 'product');
	$collection->addFieldToFilter('entity_id', $productId);
	$collection->addFieldToFilter('store_id', $storeId);
	$collection->setPageSize(1);
	$collection->setCurPage(1);

	$audit = $collection->getFirstItem();

	if ($audit && $audit->getId()) {
	return $audit;
	}

	return $this->productSeoResultFactory->create();

    }
}
