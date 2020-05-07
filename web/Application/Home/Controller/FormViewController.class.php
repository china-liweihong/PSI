<?php

namespace Home\Controller;

use Home\Common\DemoConst;
use Home\Common\FIdConst;
use Home\Service\UserService;
use Home\Service\FormViewService;

/**
 * 表单视图Controller
 *
 * @author 李静波
 *        
 */
class FormViewController extends PSIBaseController
{

  /**
   * 表单视图开发助手 - 主页面
   */
  public function index()
  {
    $us = new UserService();

    if ($us->hasPermission(FIdConst::FORM_VIEW_SYSTEM_DEV)) {
      $this->initVar();

      $this->assign("title", "视图开发助手");

      $this->display();
    } else {
      $this->gotoLoginPage("/Home/FormView/index");
    }
  }

  /**
   * 视图分类列表
   */
  public function categoryList()
  {
    if (IS_POST) {
      $us = new UserService();
      if (!$us->hasPermission(FIdConst::FORM_VIEW_SYSTEM_DEV)) {
        die("没有权限");
      }

      $params = [];

      $service = new FormViewService();
      $this->ajaxReturn($service->categoryList($params));
    }
  }

  /**
   * 新增或编辑视图分类
   */
  public function editViewCategory()
  {
    if (IS_POST) {
      $us = new UserService();
      if (!$us->hasPermission(FIdConst::FORM_VIEW_SYSTEM_DEV)) {
        die("没有权限");
      }

      $params = [
        "id" => I("post.id"),
        "code" => I("post.code"),
        "name" => I("post.name")
      ];

      $service = new FormViewService();
      $this->ajaxReturn($service->editViewCategory($params));
    }
  }

  /**
   * 删除视图分类
   */
  public function deleteViewCategory()
  {
    if (IS_POST) {
      $us = new UserService();
      if (!$us->hasPermission(FIdConst::FORM_VIEW_SYSTEM_DEV)) {
        die("没有权限");
      }

      $params = [
        "id" => I("post.id")
      ];

      $service = new FormViewService();
      $this->ajaxReturn($service->deleteViewCategory($params));
    }
  }

  /**
   * 视图分类自定义字段 - 查询数据
   */
  public function queryDataForFvCategory()
  {
    if (IS_POST) {
      $us = new UserService();
      if (!$us->hasPermission(FIdConst::FORM_VIEW_SYSTEM_DEV)) {
        die("没有权限");
      }

      $params = [
        "queryKey" => I("post.queryKey")
      ];

      $service = new FormViewService();
      $this->ajaxReturn($service->queryDataForFvCategory($params));
    }
  }

  /**
   * 视图的列表
   */
  public function fvList()
  {
    if (IS_POST) {
      $us = new UserService();
      if (!$us->hasPermission(FIdConst::FORM_VIEW_SYSTEM_DEV)) {
        die("没有权限");
      }

      $params = [];
      $service = new FormViewService();
      $this->ajaxReturn($service->fvList($params));
    }
  }
}
