<?php

declare(strict_types=1);

namespace Testlicious\AiSeoOptimizer\Controller\Adminhtml\Audit;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\Redirect;
use Testlicious\AiSeoOptimizer\Model\ProductSeoScanner;

class Scan extends Action
{
    public const ADMIN_RESOURCE = 'Testlicious_AiSeoOptimizer::audit';

    private ProductSeoScanner $productSeoScanner;

    public function __construct(
        Context $context,
        ProductSeoScanner $productSeoScanner
    ) {
        parent::__construct($context);
        $this->productSeoScanner = $productSeoScanner;
    }

    public function execute(): Redirect
    {
        $resultRedirect = $this->resultRedirectFactory->create();

        try {
            $result = $this->productSeoScanner->scan(0, 10);

            $this->messageManager->addSuccessMessage(
                __(
                    'SEO scan completed. Scanned %1 products and saved %2 audit records.',
                    $result['scanned'],
                    $result['saved']
                )
            );
        } catch (\Throwable $e) {
            $this->messageManager->addErrorMessage(
                __('SEO scan failed: %1', $e->getMessage())
            );
        }

        return $resultRedirect->setPath('aiseo/audit/index');
    }
}
