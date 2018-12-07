<?php

namespace Home\DAO;

use Home\Common\FIdConst;

/**
 * 工厂 DAO
 *
 * @author 李静波
 */
class FactoryDAO extends PSIBaseExDAO {

	/**
	 * 供应商分类列表
	 *
	 * @param array $params        	
	 * @return array
	 */
	public function categoryList($params) {
		$db = $this->db;
		
		$code = $params["code"];
		$name = $params["name"];
		$address = $params["address"];
		$contact = $params["contact"];
		$mobile = $params["mobile"];
		$tel = $params["tel"];
		
		$inQuery = false;
		if ($code || $name || $address || $contact || $mobile || $tel) {
			$inQuery = true;
		}
		
		$loginUserId = $params["loginUserId"];
		if ($this->loginUserIdNotExists($loginUserId)) {
			return $this->emptyResult();
		}
		
		$sql = "select c.id, c.code, c.name
				from t_factory_category c ";
		$queryParam = [];
		$ds = new DataOrgDAO($db);
		$rs = $ds->buildSQL(FIdConst::FACTORY, "c", $loginUserId);
		if ($rs) {
			$sql .= " where " . $rs[0];
			$queryParam = array_merge($queryParam, $rs[1]);
		}
		$sql .= " order by c.code";
		
		$data = $db->query($sql, $queryParam);
		
		$result = [];
		foreach ( $data as $v ) {
			$id = $v["id"];
			
			$queryParam = [];
			$sql = "select count(s.id) as cnt
					from t_factory s
					where (s.category_id = '%s') ";
			$queryParam[] = $id;
			if ($code) {
				$sql .= " and (s.code like '%s') ";
				$queryParam[] = "%{$code}%";
			}
			if ($name) {
				$sql .= " and (s.name like '%s' or s.py like '%s' ) ";
				$queryParam[] = "%{$name}%";
				$queryParam[] = "%{$name}%";
			}
			if ($address) {
				$sql .= " and (s.address like '%s' or s.address_shipping like '%s') ";
				$queryParam[] = "%{$address}%";
				$queryParam[] = "%{$address}%";
			}
			if ($contact) {
				$sql .= " and (s.contact01 like '%s' or s.contact02 like '%s' ) ";
				$queryParam[] = "%{$contact}%";
				$queryParam[] = "%{$contact}%";
			}
			if ($mobile) {
				$sql .= " and (s.mobile01 like '%s' or s.mobile02 like '%s' ) ";
				$queryParam[] = "%{$mobile}%";
				$queryParam[] = "%{$mobile}";
			}
			if ($tel) {
				$sql .= " and (s.tel01 like '%s' or s.tel02 like '%s' ) ";
				$queryParam[] = "%{$tel}%";
				$queryParam[] = "%{$tel}";
			}
			$rs = $ds->buildSQL(FIdConst::FACTORY, "s", $loginUserId);
			if ($rs) {
				$sql .= " and " . $rs[0];
				$queryParam = array_merge($queryParam, $rs[1]);
			}
			
			$d = $db->query($sql, $queryParam);
			$factoryCount = $d[0]["cnt"];
			
			if ($inQuery && $factoryCount == 0) {
				// 当前是查询，而且当前分类下没有符合查询条件的工厂，就不返回该工厂分类
				continue;
			}
			
			$result[] = [
					"id" => $id,
					"code" => $v["code"],
					"name" => $v["name"],
					"cnt" => $factoryCount
			];
		}
		
		return $result;
	}

	/**
	 * 新增工厂分类
	 *
	 * @param array $params        	
	 * @return array|null
	 */
	public function addFactoryCategory(& $params) {
		$db = $this->db;
		
		$code = trim($params["code"]);
		$name = trim($params["name"]);
		
		$dataOrg = $params["dataOrg"];
		$companyId = $params["companyId"];
		if ($this->dataOrgNotExists($dataOrg)) {
			return $this->badParam("dataOrg");
		}
		if ($this->companyIdNotExists($companyId)) {
			return $this->badParam("companyId");
		}
		
		if ($this->isEmptyStringAfterTrim($code)) {
			return $this->bad("分类编码不能为空");
		}
		if ($this->isEmptyStringAfterTrim($name)) {
			return $this->bad("分类名称不能为空");
		}
		
		// 检查分类编码是否已经存在
		$sql = "select count(*) as cnt from t_factory_category where code = '%s' ";
		$data = $db->query($sql, $code);
		$cnt = $data[0]["cnt"];
		if ($cnt > 0) {
			return $this->bad("编码为 [$code] 的分类已经存在");
		}
		
		$id = $this->newId();
		
		$sql = "insert into t_factory_category (id, code, name, data_org, company_id)
					values ('%s', '%s', '%s', '%s', '%s') ";
		$rc = $db->execute($sql, $id, $code, $name, $dataOrg, $companyId);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		// 操作成功
		$params["id"] = $id;
		return null;
	}

	/**
	 * 编辑工厂分类
	 *
	 * @param array $params        	
	 * @return array|null
	 */
	public function updateFactoryCategory(& $params) {
		$db = $this->db;
		
		$id = $params["id"];
		$code = trim($params["code"]);
		$name = trim($params["name"]);
		
		if ($this->isEmptyStringAfterTrim($code)) {
			return $this->bad("分类编码不能为空");
		}
		if ($this->isEmptyStringAfterTrim($name)) {
			return $this->bad("分类名称不能为空");
		}
		
		// 检查分类编码是否已经存在
		$sql = "select count(*) as cnt from t_factory_category where code = '%s' and id <> '%s' ";
		$data = $db->query($sql, $code, $id);
		$cnt = $data[0]["cnt"];
		if ($cnt > 0) {
			return $this->bad("编码为 [$code] 的分类已经存在");
		}
		
		$sql = "update t_factory_category
				set code = '%s', name = '%s'
				where id = '%s' ";
		$rc = $db->execute($sql, $code, $name, $id);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		// 操作成功
		return null;
	}

	/**
	 * 根据工厂分类id查询工厂分类
	 *
	 * @param string $id        	
	 * @return array|NULL
	 */
	public function getFactoryCategoryById($id) {
		$db = $this->db;
		
		$sql = "select code, name from t_factory_category where id = '%s' ";
		$data = $db->query($sql, $id);
		if ($data) {
			return [
					"code" => $data[0]["code"],
					"name" => $data[0]["name"]
			];
		} else {
			return null;
		}
	}

	/**
	 * 删除工厂分类
	 *
	 * @param array $params        	
	 * @return NULL|array
	 */
	public function deleteFactoryCategory(& $params) {
		$db = $this->db;
		
		$id = $params["id"];
		
		$category = $this->getFactoryCategoryById($id);
		if (! $category) {
			return $this->bad("要删除的分类不存在");
		}
		
		$params["code"] = $category["code"];
		$params["name"] = $category["name"];
		$name = $params["name"];
		
		$sql = "select count(*) as cnt
				from t_factory
				where category_id = '%s' ";
		$query = $db->query($sql, $id);
		$cnt = $query[0]["cnt"];
		if ($cnt > 0) {
			$db->rollback();
			return $this->bad("当前分类 [{$name}] 下还有工厂，不能删除");
		}
		
		$sql = "delete from t_factory_category where id = '%s' ";
		$rc = $db->execute($sql, $id);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		// 操作成功
		return null;
	}
}