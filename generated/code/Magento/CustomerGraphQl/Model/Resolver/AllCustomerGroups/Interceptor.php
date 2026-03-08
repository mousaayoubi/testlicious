<?php
namespace Magento\CustomerGraphQl\Model\Resolver\AllCustomerGroups;

/**
 * Interceptor class for @see \Magento\CustomerGraphQl\Model\Resolver\AllCustomerGroups
 */
class Interceptor extends \Magento\CustomerGraphQl\Model\Resolver\AllCustomerGroups implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Customer\Api\GroupRepositoryInterface $groupRepository, \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder, \Magento\Customer\Model\Config\AccountInformation $config)
    {
        $this->___init();
        parent::__construct($groupRepository, $searchCriteriaBuilder, $config);
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(\Magento\Framework\GraphQl\Config\Element\Field $field, $context, \Magento\Framework\GraphQl\Schema\Type\ResolveInfo $info, ?array $value = null, ?array $args = null): array
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'resolve');
        return $pluginInfo ? $this->___callPlugins('resolve', func_get_args(), $pluginInfo) : parent::resolve($field, $context, $info, $value, $args);
    }
}
