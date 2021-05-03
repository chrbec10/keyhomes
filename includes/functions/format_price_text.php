 <?php
  /**
   * @param string $saleType Auction or Sale
   * @param integer $price The price of the listing
   * @return string with formatted pricing
   */

  function format_price_text($saleType = 'Sale', $price = 0)
  {
    $output = '';

    switch ($saleType) {
      case 'Sale':
        $output = "Sale";
        if ($price > 0) {
          $output .= ' $' . number_format($price);
        }
        break;
      case 'Auction':
        $output = "Auction";
        if ($price > 0) {
          $output .= ', Reserve $' . number_format($price);
        }
        break;
    }

    return $output;
  }