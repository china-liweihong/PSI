<?php

namespace Home\DAO;

/**
 * 主菜单 DAO
 *
 * @author 李静波
 */
class MainMenuDAO extends PSIBaseExDAO {

	/**
	 * 查询所有的主菜单项 - 主菜单维护模块中使用
	 */
	public function allMenuItemsForMaintain() {
		$db = $this->db;
		
		$sql = "select id, caption, fid from (select * from t_menu_item union select * from t_menu_item_plus) m
					where parent_id is null order by show_order";
		$m1 = $db->query($sql);
		$result = [];
		
		$iconCls = "PSI-MainMenuItemIconClass";
		
		$index1 = 0;
		foreach ( $m1 as $menuItem1 ) {
			
			$children1 = [];
			
			$sql = "select id, caption, fid from (select * from t_menu_item union select * from t_menu_item_plus) m
						where parent_id = '%s' order by show_order ";
			$m2 = $db->query($sql, $menuItem1["id"]);
			
			// 第二级菜单
			$index2 = 0;
			foreach ( $m2 as $menuItem2 ) {
				$children2 = [];
				$sql = "select id, caption, fid from (select * from t_menu_item union select * from t_menu_item_plus) m
							where parent_id = '%s' order by show_order ";
				$m3 = $db->query($sql, $menuItem2["id"]);
				
				// 第三级菜单
				$index3 = 0;
				foreach ( $m3 as $menuItem3 ) {
					$children2[$index3]["id"] = $menuItem3["id"];
					$children2[$index3]["caption"] = $menuItem3["caption"];
					$children2[$index3]["fid"] = $menuItem3["fid"];
					$children2[$index3]["children"] = [];
					$children2[$index3]["iconCls"] = $iconCls;
					$index3 ++;
				}
				
				$fid = $menuItem2["fid"];
				if ($fid) {
					// 仅有二级菜单
					$children1[$index2]["id"] = $menuItem2["id"];
					$children1[$index2]["caption"] = $menuItem2["caption"];
					$children1[$index2]["fid"] = $menuItem2["fid"];
					$children1[$index2]["children"] = $children2;
					$children1[$index2]["iconCls"] = $iconCls;
					$index2 ++;
				} else {
					if (count($children2) > 0) {
						// 二级菜单还有三级菜单
						$children1[$index2]["id"] = $menuItem2["id"];
						$children1[$index2]["caption"] = $menuItem2["caption"];
						$children1[$index2]["fid"] = $menuItem2["fid"];
						$children1[$index2]["children"] = $children2;
						$children1[$index2]["iconCls"] = $iconCls;
						$index2 ++;
					}
				}
			}
			
			if (count($children1) > 0) {
				$menuItem1["iconCls"] = $iconCls;
				$result[$index1] = $menuItem1;
				$result[$index1]["children"] = $children1;
				$index1 ++;
			}
		}
		
		return $result;
	}

	/**
	 * Fid字段查询数据
	 */
	public function queryDataForFid($params) {
		$db = $this->db;
		
		$queryKey = $params["queryKey"] ?? "";
		
		$sql = "select fid, name from t_fid_plus
				where fid like '%s' or name like '%s' 
				order by fid limit 20";
		$queryParams = [];
		$queryParams[] = "%{$queryKey}%";
		$queryParams[] = "%{$queryKey}%";
		
		$data = $db->query($sql, $queryParams);
		$result = [];
		
		foreach ( $data as $v ) {
			$result[] = [
					"id" => $v["fid"],
					"name" => $v["name"]
			];
		}
		
		return $result;
	}

	/**
	 * 菜单项自定义字段 - 查询数据
	 */
	public function queryDataForMenuItem($params) {
		$db = $this->db;
		
		$queryKey = $params["queryKey"] ?? "";
		
		$sql = "select id, caption
				from t_menu_item
				where fid is null and caption like '%s' 
				order by caption limit 20";
		$queryParams = [];
		$queryParams[] = "%{$queryKey}%";
		
		$data = $db->query($sql, $queryParams);
		
		$result = [];
		foreach ( $data as $v ) {
			$result[] = [
					"id" => $v["id"],
					"name" => $v["caption"]
			];
		}
		
		return $result;
	}
}