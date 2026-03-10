<?php

declare(strict_types=1);

namespace Test16\Test16\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Catalog\Model\Product;

class AddBrandLabelProductAttribute implements DataPatchInterface
{
	protected $moduleDataSetup;
	protected $eavSetupFactory;

	public function __construct(
		ModuleDataSetupInterface $moduleDataSetup,
		EavSetupFactory $eavSetupFactory
	){
		$this->moduleDataSetup = $moduleDataSetup;
		$this->eavSetupFactory = $eavSetupFactory;
	}

	public function apply(): self
	{
	$this->moduleDataSetup->getConnection()->startSetup();

	$eavSetup = $this->eavSetupFactory->create([
		'setup' => $this->moduleDataSetup
	]);

	$eavSetup->addAttribute(
		Product::ENTITY,
		'brand_label',
		[
			'type' => 'varchar',
			'label' => 'Brand Label',
			'input' => 'text',
			'required' => false,
			'sort_order' => 100,
			'global' => ScopedAttributeInterface::SCOPE_STORE,
			'visible' => true,
			'user_defined' => true,
			'searchable' => true,
			'filterable' => false,
			'comparable' => false,
			'visible_on_front' => true,
			'used_in_product_listing' => true,
			'unique' => false,
			'group' => 'General',
		]
	);

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
