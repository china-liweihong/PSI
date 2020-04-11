<?php

namespace Home\Controller;

use Home\Common\FIdConst;
use Home\Service\UserService;
use Home\Service\BankService;

/**
 * 物料Controller
 *
 * @author 李静波
 *        
 */
class MaterialController extends PSIBaseController
{

  /**
   * 物料单位 - 主页面
   */
  public function unitIndex()
  {
    $us = new UserService();

    if ($us->hasPermission(FIdConst::MATERIAL_UNIT)) {
      $this->initVar();

      $this->assign("title", "物料单位");

      $this->display();
    } else {
      $this->gotoLoginPage("/Home/Material/unitIndex");
    }
  }
}
