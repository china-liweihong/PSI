<?php

namespace Home\Service;

require_once __DIR__ . '/../Common/Money/Money.php';

use Capital\Money;

/**
 * 助手Service
 *
 * @author 李静波
 */
class UtilService
{
  /**
   * 金额转换成中文大写汉字
   */
  public function moneyToCap($m)
  {
    return (new Money($m))->toCapital();
  }
}
