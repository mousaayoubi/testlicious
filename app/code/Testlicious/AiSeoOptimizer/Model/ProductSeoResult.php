<?php

declare(strict_types=1);

namespace Testlicious\AiSeoOptimizer\Model;

use Magento\Framework\Model\AbstractModel;
use Testlicious\AiSeoOptimizer\Model\ResourceModel\ProductSeoResult as ProductSeoResultResource;

class ProductSeoResult extends AbstractModel
{
	public const CACHE_TAG = 'testlicious_aiseooptimizer_product_seo_result';
	protected $_cacheTag = self::CACHE_TAG;

	protected $_eventPrefix = 'testlicious_aiseooptimizer_product_seo_result';
	protected function _construct(): void
	{
	$this->_init(ProductSeoResultResource::class);
	}
}
