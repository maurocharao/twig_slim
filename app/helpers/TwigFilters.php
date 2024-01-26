<?php

namespace app\helpers;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class TwigFilters extends AbstractExtension
{
  public function getFilters()
  {
    return [
      new TwigFilter('formatPrice', [ $this, 'formatPrice' ])
    ];
  }

  // usage: {{ 12345678.4576 | formatPrice(2) }}
  public function formatPrice($number, $decimals = 0, $decPoint = ',', $thousandsSep = '.')
  {
    $price = number_format($number, $decimals, $decPoint, $thousandsSep);
    $price = 'R$'.$price;

    return $price;
  }
}
