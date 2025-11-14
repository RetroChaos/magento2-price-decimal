<?php

declare(strict_types=1);

namespace RetroChaos\PriceDecimal\Model\Plugin\Local;

use RetroChaos\PriceDecimal\Model\Plugin\PriceFormatPluginAbstract;

class Format extends PriceFormatPluginAbstract
{

    /**
     * @param $subject
     * @param $result
     *
     * @return mixed
     */
    public function afterGetPriceFormat($subject, $result): mixed
    {
        $precision = $this->getPricePrecision();

        if ($this->getConfig()->isEnable()) {
            $result['precision'] = $precision;
            $result['requiredPrecision'] = $precision;
        }

        return $result;
    }
}
