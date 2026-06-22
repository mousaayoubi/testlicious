<?php
namespace Testlicious\AiSeoOptimizer\Controller\Adminhtml\Suggestion\MassApply;

/**
 * Interceptor class for @see \Testlicious\AiSeoOptimizer\Controller\Adminhtml\Suggestion\MassApply
 */
class Interceptor extends \Testlicious\AiSeoOptimizer\Controller\Adminhtml\Suggestion\MassApply implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Ui\Component\MassAction\Filter $filter, \Testlicious\AiSeoOptimizer\Model\ResourceModel\Suggestion\CollectionFactory $collectionFactory, \Testlicious\AiSeoOptimizer\Service\SuggestionApplier $suggestionApplier)
    {
        $this->___init();
        parent::__construct($context, $filter, $collectionFactory, $suggestionApplier);
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
