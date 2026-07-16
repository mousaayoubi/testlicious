<?php

declare(strict_types=1);

namespace Testlicious\AiSeoOptimizer\Service;

use Psr\Log\LoggerInterface;
use Testlicious\AiSeoOptimizer\Model\Config;
use Testlicious\AiSeoOptimizer\Model\ProductSeoResult;
use Testlicious\AiSeoOptimizer\Model\ResourceModel\ProductSeoResult\CollectionFactory
    as AuditCollectionFactory;
use Testlicious\AiSeoOptimizer\Model\ResourceModel\Suggestion\CollectionFactory
    as SuggestionCollectionFactory;

class AutomaticSuggestionProcessor
{
    public function __construct(
        private readonly Config $config,
        private readonly AuditCollectionFactory $auditCollectionFactory,
        private readonly SuggestionCollectionFactory $suggestionCollectionFactory,
        private readonly AiSuggestionGenerator $aiSuggestionGenerator,
        private readonly LoggerInterface $logger
    ) {
    }

    public function process(int $storeId, int $limit): int
    {
        if (!$this->config->isAutoSuggestionGenerationEnabled($storeId)) {
            return 0;
        }

        $threshold = $this->config->getSuggestionScoreThreshold($storeId);

        $auditCollection = $this->auditCollectionFactory->create();
        $auditCollection->addFieldToFilter('entity_type', 'product');
        $auditCollection->addFieldToFilter('store_id', $storeId);
        $auditCollection->addFieldToFilter('seo_score', ['lt' => $threshold]);
        $auditCollection->setOrder('updated_at', 'DESC');
        $auditCollection->setPageSize($limit);
        $auditCollection->setCurPage(1);

        $generatedCount = 0;

        /** @var ProductSeoResult $audit */
        foreach ($auditCollection as $audit) {
            $auditId = (int)$audit->getId();

            if ($auditId <= 0 || $this->hasPendingSuggestion($auditId)) {
                continue;
            }

            try {
                /*
                 * Adjust this call if your AiSuggestionGenerator method
                 * has a different method name or parameter list.
                 */
                $this->aiSuggestionGenerator->generate($auditId);

                $generatedCount++;
            } catch (\Throwable $exception) {
                $this->logger->error(
                    'Automatic AI SEO suggestion generation failed.',
                    [
                        'audit_id' => $auditId,
                        'entity_id' => (int)$audit->getData('entity_id'),
                        'store_id' => $storeId,
                        'exception' => $exception->getMessage(),
                    ]
                );
            }
        }

        return $generatedCount;
    }

    private function hasPendingSuggestion(int $auditId): bool
    {
        $collection = $this->suggestionCollectionFactory->create();
        $collection->addFieldToFilter('audit_id', $auditId);
        $collection->addFieldToFilter('status', 'pending_review');
        $collection->setPageSize(1);

        return $collection->getSize() > 0;
    }
}
