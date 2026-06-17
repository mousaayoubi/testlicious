<?php
namespace Testlicious\AiSeoOptimizer\Controller\Adminhtml\Suggestion\Apply;

/**
 * Interceptor class for @see \Testlicious\AiSeoOptimizer\Controller\Adminhtml\Suggestion\Apply
 */
class Interceptor extends \Testlicious\AiSeoOptimizer\Controller\Adminhtml\Suggestion\Apply implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Testlicious\AiSeoOptimizer\Model\SuggestionFactory $suggestionFactory, \Testlicious\AiSeoOptimizer\Model\ResourceModel\Suggestion $suggestionResource, \Testlicious\AiSeoOptimizer\Service\SuggestionApplier $suggestionApplier)
    {
        $this->___init();
        parent::__construct($context, $suggestionFactory, $suggestionResource, $suggestionApplier);
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
