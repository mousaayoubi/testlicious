<?php

declare(strict_types=1);


namespace Testlicious\AiSeoOptimizer\Model\ResourceModel\ProductSeoResult;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Testlicious\AiSeoOptimizer\Model\ProductSeoResult;
use Testlicious\AiSeoOptimizer\Model\ResourceModel\ProductSeoResult as ProductSeoResultResource;

class Collection extends AbstractCollection
{
	protected $_idFieldName = 'audit_id';

	protected $_eventPrefix = 'testlicious_aiseooptimizer_product_seo_result_collection';

	protected $_eventObject = 'product_seo_result_collection';

	protected function _construct(): void
	{
	$this->_init(
		ProductSeoResult::class,
		ProductSeoResultResource::class
	);
	}
}
