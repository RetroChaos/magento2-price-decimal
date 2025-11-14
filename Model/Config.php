<?php
declare(strict_types=1);

namespace RetroChaos\PriceDecimal\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;

class Config implements ConfigInterface
{
	/** @var string */
	public const XML_PATH_PRICE_PRECISION =
		'catalog_price_decimal/general/price_precision';

	/** @var string */
	public const XML_PATH_CAN_SHOW_PRICE_DECIMAL =
		'catalog_price_decimal/general/can_show_decimal';

	public function __construct(
		private readonly ScopeConfigInterface $scopeConfig
	) {
	}

	public function getScopeConfig(): ScopeConfigInterface
	{
		return $this->scopeConfig;
	}

	public function isEnable(): bool
	{
		// keep behaviour you already had, just cast to bool
		return (bool) $this->getScopeConfig()->isSetFlag(
			'catalog_price_decimal/general/active'
		);
	}

	public function canShowPriceDecimal(): bool
	{
		return (bool) $this->getScopeConfig()->isSetFlag(
			self::XML_PATH_CAN_SHOW_PRICE_DECIMAL
		);
	}

	public function getPricePrecision(): int
	{
		$value = (string) $this->getScopeConfig()->getValue(
			self::XML_PATH_PRICE_PRECISION
		);

		// normalise & guard against weird config entries
		$precision = (int) $value;

		return max($precision, 0);
	}
}
