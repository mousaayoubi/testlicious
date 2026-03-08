<?php
namespace Magento\SalesRuleGraphQl\Model\Resolver\AppliedCartRules;

/**
 * Interceptor class for @see \Magento\SalesRuleGraphQl\Model\Resolver\AppliedCartRules
 */
class Interceptor extends \Magento\SalesRuleGraphQl\Model\Resolver\AppliedCartRules implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\SalesRule\Model\Config\Coupon $config, \Magento\SalesRule\Model\ResourceModel\GetAppliedCartRules $getAppliedCartRules)
    {
        $this->___init();
        parent::__construct($config, $getAppliedCartRules);
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(\Magento\Framework\GraphQl\Config\Element\Field $field, $context, \Magento\Framework\GraphQl\Schema\Type\ResolveInfo $info, ?array $value = null, ?array $args = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'resolve');
        return $pluginInfo ? $this->___callPlugins('resolve', func_get_args(), $pluginInfo) : parent::resolve($field, $context, $info, $value, $args);
    }
}
