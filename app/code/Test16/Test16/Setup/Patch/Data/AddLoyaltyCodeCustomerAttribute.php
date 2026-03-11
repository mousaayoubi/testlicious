<?php

declare(strict_types=1);

namespace Test16\Test16\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;
use Magento\Customer\Model\Customer;

class AddLoyaltyCodeCustomerAttribute implements DataPatchInterface
{
	protected $moduleDataSetup;
	protected $customerSetupFactory;
	protected $attributeSetFactory;

	public function  __construct(
		ModuleDataSetupInterface $moduleDataSetup,
		CustomerSetupFactory $customerSetupFactory,
		AttributeSetFactory $attributeSetFactory
){
	$this->moduleDataSetup = $moduleDataSetup;
	$this->customerSetupFactory = $customerSetupFactory;
	$this->attributeSetFactory = $attributeSetFactory;
	}

	public function apply(): self
	{
	$this->moduleDataSetup->getConnection()->startSetup();

	$customerSetup = $this->customerSetupFactory->create([
		'setup' => $this->moduleDataSetup
	]);

	$customerEntity = $customerSetup->getEavConfig()->getEntityType(Customer::ENTITY);
	$attributeSetId = $customerEntity->getDefaultAttributeSetId();

	$attributeSet = $this->attributeSetFactory->create();
	$attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);

	$customerSetup->addAttribute(
		Customer::ENTITY,
		'loyalty_code',
		[
			'type' => 'varchar',
			'label' => 'Loyalty Code',
			'input' => 'text',
			'required' => false,
			'visible' => true,
			'user_defined' => true,
			'sort_order' => 999,
			'position' => 999,
			'system' => 0,
		]
	);

	$attribute = $customerSetup->getEavConfig()
			  ->getAttribute(Customer::ENTITY, 'loyalty_code');

	$attribute->addData([
		'attribute_set_id' => $attributeSetId,
		'attribute_group_id' => $attributeGroupId,
		'used_in_forms' => [
			'adminhtml_customer',
			'customer_account_create',
			'customer_account_edit'
		]
	]);

	$attribute->save();

	$this->moduleDataSetup->getConnection()->endSetup();

	return $this;

	}

	public static function getDependencies(): array
	{
	return [];
	}

	public function getAliases(): array
	{
	return [];
	}
}
