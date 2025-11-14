<?php
declare(strict_types=1);

namespace RetroChaos\PriceDecimal\Model\Plugin;

use Magento\Framework\Currency as Subject;
use RetroChaos\PriceDecimal\Model\ConfigInterface;
use RetroChaos\PriceDecimal\Model\PricePrecisionConfigTrait;

class Currency
{
	use PricePrecisionConfigTrait;

	public function __construct(ConfigInterface $moduleConfig)
	{
		$this->moduleConfig = $moduleConfig;
	}

	/**
	 * @param Subject $subject
	 * @param mixed   ...$arguments
	 * @return array
	 */
	public function beforeToCurrency(Subject $subject, ...$arguments): array
	{
		if (!$this->getConfig()->isEnable()) {
			return $arguments;
		}

		// $arguments[0] = value, $arguments[1] = options (array) in core
		if (!isset($arguments[1]) || !is_array($arguments[1])) {
			$arguments[1] = [];
		}

		$arguments[1]['precision'] = $subject->getPricePrecision();

		return $arguments;
	}
}
