<?php

namespace app\helpers;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use app\src\Flash;
use Carbon\Carbon;

class TwigFunctions extends AbstractExtension
{
	public function getFunctions()
	{
		return array(
			new TwigFunction('message', [ $this, 'message' ]),
			new TwigFunction('timeAgo', [ $this, 'timeAgo' ])
		);
	}

	public function message($index)
	{
		return Flash::get($index);
	}

	public function timeAgo($date)
	{
		Carbon::setLocale('pt-br');

		$created = Carbon::parse($date);

		return $created->diffForHumans();
	}
}
