<?php

declare(strict_types=1);

namespace RetroChaos\PriceDecimal\Test\Unit\Plugin\Model;

use Magento\Directory\Model\PriceCurrency as MagentoPriceCurrency;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use RetroChaos\PriceDecimal\Model\ConfigInterface;
use RetroChaos\PriceDecimal\Model\Plugin\PriceCurrency;

class PriceCurrencyTest extends TestCase
{
	/** @var MagentoPriceCurrency&MockObject */
	private MagentoPriceCurrency $priceCurrencyMock;

	protected function setUp(): void
	{
		$this->priceCurrencyMock = $this->getMockBuilder(MagentoPriceCurrency::class)
			->disableOriginalConstructor()
			->getMock();
	}

	/**
	 * Helper to create a plugin instance with a mocked config.
	 *
	 * @param bool $enabled
	 * @param int  $precision
	 * @return PriceCurrency
	 */
	private function createPlugin(bool $enabled, int $precision): PriceCurrency
	{
		/** @var ConfigInterface&MockObject $configMock */
		$configMock = $this->getMockBuilder(ConfigInterface::class)
			->onlyMethods(['isEnable', 'getScopeConfig', 'canShowPriceDecimal', 'getPricePrecision'])
			->getMock();

		$configMock->method('isEnable')->willReturn($enabled);
		$configMock->method('canShowPriceDecimal')->willReturn(true);
		$configMock->method('getPricePrecision')->willReturn($precision);

		$objectManager = new ObjectManager($this);

		/** @var PriceCurrency $plugin */
		$plugin = $objectManager->getObject(
			PriceCurrency::class,
			['moduleConfig' => $configMock]
		);

		return $plugin;
	}

	public function testBeforeFormatAppliesConfiguredPrecisionWhenEnabled(): void
	{
		$plugin        = $this->createPlugin(true, 3);
		$price         = 22.54321;

		// emulate arguments to PriceCurrency::format($price, $includeContainer, $precision, ...)
		$args = [$price, true, null];

		$resultArgs = $plugin->beforeFormat($this->priceCurrencyMock, ...$args);

		// index 2 should now be our configured precision 3
		$this->assertSame(3, $resultArgs[2]);
	}

	public function testBeforeFormatDoesNothingWhenDisabled(): void
	{
		$plugin        = $this->createPlugin(false, 4);
		$price         = 22.54321;
		$args          = [$price, true, 2];

		$resultArgs = $plugin->beforeFormat($this->priceCurrencyMock, ...$args);

		// when disabled, the plugin should just return the original args
		$this->assertSame($args, $resultArgs);
	}

	public function testBeforeConvertAndRoundInjectsPrecisionWhenEnabled(): void
	{
		$plugin    = $this->createPlugin(true, 4);
		$price     = 10.9876;
		$args      = [$price, null];

		$resultArgs = $plugin->beforeConvertAndRound($this->priceCurrencyMock, ...$args);

		// convertAndRound($price, $precision, ...)
		$this->assertSame(4, $resultArgs[1]);
	}

	public function testAroundRoundUsesModulePrecisionWhenEnabled(): void
	{
		$plugin   = $this->createPlugin(true, 3);
		$price    = 10.9876;

		$proceed = function ($price, ...$args) {
			// emulate core: round($price, $precision)
			$precision = $args[0] ?? 2;
			return round($price, $precision);
		};

		$result = $plugin->aroundRound(
			$this->priceCurrencyMock,
			$proceed,
			$price
		);

		$this->assertSame(
			round($price, 3),
			$result,
			'When enabled, plugin should use module precision for rounding'
		);
	}

	public function testAroundRoundDelegatesToProceedWhenDisabled(): void
	{
		$plugin   = $this->createPlugin(false, 3);
		$price    = 10.9876;

		$proceed = function ($price, ...$args) {
			// emulate core round with explicit precision
			$precision = $args[0] ?? 4;
			return round($price, $precision);
		};

		$result = $plugin->aroundRound(
			$this->priceCurrencyMock,
			$proceed,
			$price,
			4
		);

		$this->assertSame(
			round($price, 4),
			$result,
			'When disabled, plugin should call proceed with original args'
		);
	}
}
