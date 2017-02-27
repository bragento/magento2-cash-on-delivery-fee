<?php
declare(strict_types = 1);
namespace Brandung\CashOnDeliveryFee\Setup;

use Brandung\CashOnDeliveryFee\Model\Total\CashOnDeliveryFee;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $this->addOrderTotalField($setup, CashOnDeliveryFee::TOTAL_CODE, CashOnDeliveryFee::LABEL);
        $this->addOrderTotalField($setup, CashOnDeliveryFee::BASE_TOTAL_CODE, CashOnDeliveryFee::BASE_LABEL);

        $setup->endSetup();
    }

    public function addOrderTotalField(
        SchemaSetupInterface $setup,
        string $fieldName,
        string $fieldComment
    ) {
        $setup->getConnection()->addColumn($setup->getTable('sales_order'), $fieldName, [
            'type' => Table::TYPE_DECIMAL,
            'length' => '12,4',
            'nullable' => false,
            'default' => (float)null,
            'comment' => $fieldComment,
        ]);
    }
}
