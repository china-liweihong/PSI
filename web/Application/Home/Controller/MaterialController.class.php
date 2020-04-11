<?php

namespace Home\Controller;

use Home\Common\FIdConst;
use Home\Service\UserService;
use Home\Service\BankService;
use Home\Service\MaterialService;

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

  /**
   * 获得所有的物料单位列表
   *
   */
  public function allUnits()
  {
    if (IS_POST) {
      $us = new UserService();
      if (!$us->hasPermission(FIdConst::MATERIAL_UNIT)) {
        $this->ajaxReturn([]);
        return;
      }

      $service = new MaterialService();
      $this->ajaxReturn($service->allUnits());
    }
  }

  /**
   * 新增或编辑物料单位
   */
  public function editUnit()
  {
    if (IS_POST) {
      $us = new UserService();
      if (!$us->hasPermission(FIdConst::MATERIAL_UNIT)) {
        $this->ajaxReturn($this->noPermission("物料单位"));
        return;
      }

      $params = [
        "id" => I("post.id"),
        "name" => I("post.name"),
        "code" => I("post.code"),
        "recordStatus" => I("post.recordStatus")
      ];

      $service = new MaterialService();
      $this->ajaxReturn($service->editUnit($params));
    }
  }

  /**
   * 删除物料单位
   */
  public function deleteUnit()
  {
    if (IS_POST) {
      $us = new UserService();
      if (!$us->hasPermission(FIdConst::MATERIAL_UNIT)) {
        $this->ajaxReturn($this->noPermission("物料单位"));
        return;
      }

      $params = [
        "id" => I("post.id")
      ];
      $service = new MaterialService();
      $this->ajaxReturn($service->deleteUnit($params));
    }
  }
}
