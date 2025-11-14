<?php
declare(strict_types=1);

namespace RetroChaos\PriceDecimal\Model;

trait PricePrecisionConfigTrait
{
	private ConfigInterface $moduleConfig;

	protected function getConfig(): ConfigInterface
	{
		return $this->moduleConfig;
	}

	protected function getPricePrecision(): int
	{
		return $this->moduleConfig->getPricePrecision();
	}

	protected function canShowPriceDecimal(): bool
	{
		return $this->moduleConfig->canShowPriceDecimal();
	}
}
