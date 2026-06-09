<?php
namespace Testlicious\AiSeoOptimizer\Controller\Adminhtml\Product\Scan;

/**
 * Interceptor class for @see \Testlicious\AiSeoOptimizer\Controller\Adminhtml\Product\Scan
 */
class Interceptor extends \Testlicious\AiSeoOptimizer\Controller\Adminhtml\Product\Scan implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Testlicious\AiSeoOptimizer\Model\ProductSeoScanner $productSeoScanner)
    {
        $this->___init();
        parent::__construct($context, $productSeoScanner);
    }

    /**
     * {@inheritdoc}
     */
    public function execute(): \Magento\Framework\Controller\Result\Redirect
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'execute');
        return $pluginInfo ? $this->___callPlugins('execute', func_get_args(), $pluginInfo) : parent::execute();
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch(\Magento\Framework\App\RequestInterface $request)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'dispatch');
        return $pluginInfo ? $this->___callPlugins('dispatch', func_get_args(), $pluginInfo) : parent::dispatch($request);
    }
}
