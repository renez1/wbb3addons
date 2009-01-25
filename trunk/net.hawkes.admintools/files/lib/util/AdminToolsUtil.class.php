<?php


class AdminToolsUtil {
	
	
	public static function readDiskInfo($pow = 2, $dec = 2) {
		$diskInformation = array();
		if(function_exists('disk_free_space') && function_exists('disk_total_space')) {
			$root = '';
			if($tmp = @disk_total_space($_SERVER["DOCUMENT_ROOT"])) $root = $_SERVER["DOCUMENT_ROOT"];
			else {
				$sql = "SELECT packageDir FROM wcf".WCF_N."_package
            			WHERE packageID = ".PACKAGE_ID;
				$row = WCF::getDB()->getFirstRow($sql);
				$root = FileUtil::getRealPath(WCF_DIR.$row['packageDir']);
			}
			if(!empty($root)) {
				$diskInformation['totalSpace'] = round(disk_total_space($root) / pow(1024, $pow), $dec);
				$diskInformation['freeSpace']  = round(disk_free_space($root) / pow(1024, $pow), $dec);
				$diskInformation['usedSpace']  = round($diskInformation['totalSpace'] - $diskInformation['freeSpace'], $dec);
				if($diskInformation['totalSpace'] > 0) {
					$diskInformation['freeQuota'] = round($diskInformation['freeSpace'] * 100 / $diskInformation['totalSpace'], $dec);
					$diskInformation['usedQuota'] = round($diskInformation['usedSpace'] * 100 / $diskInformation['totalSpace'], $dec);
				} else {
					$diskInformation['freeQuota'] = $diskInformation['usedQuota'] = 0;
				}
			}
		}
		return $diskInformation;
	}
}
?>