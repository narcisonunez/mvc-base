<?php

namespace Core\Base;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TwigFunctionsExtension extends AbstractExtension
{
	public function getFunctions()
	{
		return [
			new TwigFunction('config', [$this, 'config']),
		];
	}

	public function config($name, $default = null)
	{
		return config($name, $default);
	}
}
