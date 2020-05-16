<?php

namespace Home\Controller;

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

      $params = [
        "categoryId" => I("post.categoryId")
      ];
      $service = new FormViewService();
      $this->ajaxReturn($service->fvList($params));
    }
  }

  /**
   * 新增或编辑视图
   */
  public function editFv()
  {
    if (IS_POST) {
      $us = new UserService();
      if (!$us->hasPermission(FIdConst::FORM_VIEW_SYSTEM_DEV)) {
        die("没有权限");
      }

      $params = [
        "id" => I("post.id"),
        "categoryId" => I("post.categoryId"),
        "code" => I("post.code"),
        "name" => I("post.name"),
        "moduleName" => I("post.moduleName"),
        "xtype" => I("post.xtype"),
        "region" => I("post.region"),
        "widthOrHeight" => I("post.widthOrHeight"),
        "layout" => I("post.layout"),
        "memo" => I("post.memo"),
      ];

      $service = new FormViewService();
      $this->ajaxReturn($service->editFv($params));
    }
  }

  /**
   * 某个视图的详情
   */
  public function fvInfo()
  {
    if (IS_POST) {
      $us = new UserService();
      if (!$us->hasPermission(FIdConst::FORM_VIEW_SYSTEM_DEV)) {
        die("没有权限");
      }

      $params = [
        "id" => I("post.id"),
      ];

      $service = new FormViewService();
      $this->ajaxReturn($service->fvInfo($params));
    }
  }

  /**
   * 视图 - 运行主界面
   */
  public function run()
  {
    $fid = I("get.fid");

    $us = new UserService();
    if ($us->hasPermission($fid)) {
      $this->initVar();

      $service = new FormViewService();

      $params = ["fid" => $fid];
      $md = $service->getMetadataForRuntimeInit($params);
      if ($md) {
        $this->assign("title", $md["title"]);
        $this->assign("fid", $fid);

        $this->display();
      } else {
        // 错误的fid，跳转到首页
        $this->gotoLoginPage("/Home");
      }
    } else {
      $this->gotoLoginPage("/Home");
    }
  }
}
