<?php
namespace Magento\CustomerGraphQl\Model\Resolver\CustomerGroup;

/**
 * Interceptor class for @see \Magento\CustomerGraphQl\Model\Resolver\CustomerGroup
 */
class Interceptor extends \Magento\CustomerGraphQl\Model\Resolver\CustomerGroup implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Customer\Model\Config\AccountInformation $config, \Magento\CustomerGraphQl\Model\GetCustomerGroupName $getCustomerGroup)
    {
        $this->___init();
        parent::__construct($config, $getCustomerGroup);
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
