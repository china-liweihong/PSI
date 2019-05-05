<?php

namespace Home\Service;

require __DIR__ . '/../Common/ip2region/Ip2Region.class.php';

/**
 * IP Service
 *
 * @author 李静波
 */
class IPService {

	/**
	 * 根据IP查询所在地区
	 *
	 * @param string $ip        	
	 * @return string
	 */
	public function toRegion($ip) {
		$r = new \Ip2Region();
		$data = $r->btreeSearch($ip);
		return $data["region"];
	}
}
