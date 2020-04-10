<?php

namespace Core\Base;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TwigFunctionsExtension extends AbstractExtension
{
	public function getFunctions()
	{
		return [
			new TwigFunction('config', 'config'),
			new TwigFunction('form_method', 'form_method'),
			new TwigFunction('form_csrf', 'form_csrf'),
		];
	}
}
