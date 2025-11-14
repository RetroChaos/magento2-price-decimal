<?php
declare(strict_types=1);

namespace RetroChaos\PriceDecimal\Model;

use Magento\Framework\App\CacheInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Currency as MagentoCurrency;
use Magento\Framework\CurrencyInterface;

class Currency extends MagentoCurrency implements CurrencyInterface
{
	use PricePrecisionConfigTrait;

	public function __construct(
		CacheInterface $appCache,
		$options = null,
		$locale = null,
		?ConfigInterface $moduleConfig = null
	) {
		parent::__construct($appCache, $options, $locale);

		// DI will normally inject this; fallback keeps it from exploding
		$this->moduleConfig = $moduleConfig ?? ObjectManager::getInstance()
			->get(ConfigInterface::class);
	}
}
