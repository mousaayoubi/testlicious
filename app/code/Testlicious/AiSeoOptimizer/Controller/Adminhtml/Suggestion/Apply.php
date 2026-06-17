<?php

declare(strict_types=1);

namespace Testlicious\AiSeoOptimizer\Controller\Adminhtml\Suggestion;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\Redirect;
use Testlicious\AiSeoOptimizer\Model\SuggestionFactory;
use Testlicious\AiSeoOptimizer\Model\ResourceModel\Suggestion as SuggestionResource;
use Testlicious\AiSeoOptimizer\Service\SuggestionApplier;

class Apply extends Action
{
    public const ADMIN_RESOURCE = 'Testlicious_AiSeoOptimizer::suggestions';

    public function __construct(
        Context $context,
        private readonly SuggestionFactory $suggestionFactory,
        private readonly SuggestionResource $suggestionResource,
        private readonly SuggestionApplier $suggestionApplier
    ) {
        parent::__construct($context);
    }

    public function execute(): Redirect
    {
        $resultRedirect = $this->resultRedirectFactory->create();

        $suggestionId = (int)$this->getRequest()->getParam('suggestion_id');

        if ($suggestionId <= 0) {
            $this->messageManager->addErrorMessage(__('Invalid suggestion ID.'));
            return $resultRedirect->setPath('*/suggestion/index');
        }

        try {
            $suggestion = $this->suggestionFactory->create();
            $this->suggestionResource->load($suggestion, $suggestionId);

            if (!$suggestion->getId()) {
                $this->messageManager->addErrorMessage(__('Suggestion not found.'));
                return $resultRedirect->setPath('*/suggestion/index');
            }

            $this->suggestionApplier->apply($suggestion);

            $this->messageManager->addSuccessMessage(__('The AI SEO suggestion was applied successfully.'));
        } catch (\Throwable $e) {
            $this->messageManager->addErrorMessage(
                __('Unable to apply suggestion: %1', $e->getMessage())
            );
        }

        return $resultRedirect->setPath('*/suggestion/index');
    }
}
