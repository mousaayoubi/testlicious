<?php
namespace Magento\CatalogRuleGraphQl\Model\Resolver\AllCatalogRules;

/**
 * Interceptor class for @see \Magento\CatalogRuleGraphQl\Model\Resolver\AllCatalogRules
 */
class Interceptor extends \Magento\CatalogRuleGraphQl\Model\Resolver\AllCatalogRules implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\CatalogRule\Model\Config\CatalogRule $config, \Magento\CatalogRule\Model\ResourceModel\GetAllCatalogRules $getAllCatalogRules)
    {
        $this->___init();
        parent::__construct($config, $getAllCatalogRules);
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(\Magento\Framework\GraphQl\Config\Element\Field $field, $context, \Magento\Framework\GraphQl\Schema\Type\ResolveInfo $info, ?array $value = null, ?array $args = null): ?array
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'resolve');
        return $pluginInfo ? $this->___callPlugins('resolve', func_get_args(), $pluginInfo) : parent::resolve($field, $context, $info, $value, $args);
    }
}
