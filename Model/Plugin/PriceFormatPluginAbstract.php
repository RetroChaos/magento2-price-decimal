<?php

declare(strict_types=1);

namespace RetroChaos\PriceDecimal\Model\Plugin;

use RetroChaos\PriceDecimal\Model\ConfigInterface;
use RetroChaos\PriceDecimal\Model\PricePrecisionConfigTrait;

abstract class PriceFormatPluginAbstract
{

    use PricePrecisionConfigTrait;

    /**
     * @param ConfigInterface $moduleConfig
     */
    public function __construct(
        ConfigInterface $moduleConfig
    ) {
        $this->moduleConfig  = $moduleConfig;
    }
}
