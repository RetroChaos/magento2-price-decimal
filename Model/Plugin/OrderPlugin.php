<?php

declare(strict_types=1);

namespace RetroChaos\PriceDecimal\Model\Plugin;

use Magento\Sales\Model\Order;

class OrderPlugin extends PriceFormatPluginAbstract
{
    /**
     * @param Order $subject
     * @param array ...$args
     * @return array
     */
    public function beforeFormatPricePrecision(Order $subject, ...$args): array
    {
        //is enabled
        if ($this->getConfig()->isEnable()) {
            //change the precision
            $args[1] = $this->getPricePrecision();
        }

        return $args;
    }
}
