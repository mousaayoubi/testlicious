<?php
namespace Testlicious\AiSeoOptimizer\Controller\Adminhtml\Suggestion\Generate;

/**
 * Interceptor class for @see \Testlicious\AiSeoOptimizer\Controller\Adminhtml\Suggestion\Generate
 */
class Interceptor extends \Testlicious\AiSeoOptimizer\Controller\Adminhtml\Suggestion\Generate implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Testlicious\AiSeoOptimizer\Service\AiSuggestionGenerator $aiSuggestionGenerator, \Testlicious\AiSeoOptimizer\Service\GenerationLogger $generationLogger, \Testlicious\AiSeoOptimizer\Model\Config $config)
    {
        $this->___init();
        parent::__construct($context, $aiSuggestionGenerator, $generationLogger, $config);
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
