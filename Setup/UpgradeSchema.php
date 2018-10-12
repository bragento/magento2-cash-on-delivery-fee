<?php
declare(strict_types = 1);
namespace Brandung\CashOnDeliveryFee\Setup;

use Brandung\CashOnDeliveryFee\Model\Total\CashOnDeliveryFee;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * @inheritdoc
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if (version_compare($context->getVersion(), '0.1.1', '<'))
        {
            foreach (['sales_invoice', 'sales_creditmemo'] as $entity)
            {
                foreach ([CashOnDeliveryFee::TOTAL_CODE => CashOnDeliveryFee::LABEL,
                             CashOnDeliveryFee::BASE_TOTAL_CODE => CashOnDeliveryFee::BASE_LABEL] as $attributeCode => $attributeLabel)
                {
                    $setup->getConnection()->addColumn($setup->getTable($entity), $attributeCode, [
                        'type' => Table::TYPE_DECIMAL,
                        'length' => '12,4',
                        'nullable' => false,
                        'default' => (float)null,
                        'comment' => $attributeLabel,
                    ]);

                }
            }
        }

        $setup->endSetup();
    }
}