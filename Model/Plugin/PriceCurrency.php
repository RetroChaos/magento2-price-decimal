<?php

declare(strict_types=1);

namespace RetroChaos\PriceDecimal\Model\Plugin;

use Magento\Directory\Model\PriceCurrency as BasePriceCurrency;

class PriceCurrency extends PriceFormatPluginAbstract
{
	public function beforeFormat(BasePriceCurrency $subject, ...$args): array
	{
		if (!$this->getConfig()->isEnable()) {
			return $args;
		}

		// ensure includeContainer (index 1) is defined
		if (!array_key_exists(1, $args)) {
			$args[1] = true;
		}

		$precision = $this->getPricePrecision();

		// only override precision when it's not explicitly set
		if (!array_key_exists(2, $args) || $args[2] === null) {
			$args[2] = $precision;
		}

		return $args;
	}

	/**
	 * @param BasePriceCurrency $subject
	 * @param callable $proceed
	 * @param $price
	 * @param array ...$args
	 * @return float
	 */
	public function aroundRound(
		BasePriceCurrency $subject,
		callable $proceed,
		$price,
		...$args
	): float
	{
		if (!$this->getConfig()->isEnable()) {
			return $proceed($price, ...$args);
		}

		return round((float) $price, $this->getPricePrecision());
	}

	/**
     * @param BasePriceCurrency $subject
     * @param array ...$args
     * @return array
     */
	public function beforeConvertAndFormat(BasePriceCurrency $subject, ...$args): array
	{
		if (!$this->getConfig()->isEnable()) {
			return $args;
		}

		if (!array_key_exists(1, $args)) {
			$args[1] = true;
		}

		$precision = $this->getPricePrecision();

		if (!array_key_exists(2, $args) || $args[2] === null) {
			$args[2] = $precision;
		}

		return $args;
	}


	/**
     * @param BasePriceCurrency $subject
     * @param array ...$args
     * @return array
     */
	public function beforeConvertAndRound(BasePriceCurrency $subject, ...$args): array
	{
		if (!$this->getConfig()->isEnable()) {
			return $args;
		}

		$precision = $this->getPricePrecision();

		if (!array_key_exists(1, $args) || $args[1] === null) {
			$args[1] = $precision;
		}

		return $args;
	}
}
