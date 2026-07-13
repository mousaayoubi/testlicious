<?php
namespace Testlicious\AiSeoOptimizer\Console\Command\ScanProductsCommand;

/**
 * Interceptor class for @see \Testlicious\AiSeoOptimizer\Console\Command\ScanProductsCommand
 */
class Interceptor extends \Testlicious\AiSeoOptimizer\Console\Command\ScanProductsCommand implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Testlicious\AiSeoOptimizer\Model\ProductSeoScanner $productSeoScanner, \Testlicious\AiSeoOptimizer\Model\Config $config, \Magento\Framework\App\State $appState, ?string $name = null)
    {
        $this->___init();
        parent::__construct($productSeoScanner, $config, $appState, $name);
    }

    /**
     * {@inheritdoc}
     */
    public function run(\Symfony\Component\Console\Input\InputInterface $input, \Symfony\Component\Console\Output\OutputInterface $output): int
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'run');
        return $pluginInfo ? $this->___callPlugins('run', func_get_args(), $pluginInfo) : parent::run($input, $output);
    }
}
