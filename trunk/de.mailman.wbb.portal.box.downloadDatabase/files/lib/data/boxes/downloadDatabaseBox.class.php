<?php
class downloadDatabaseBox {
	protected $BoxData = array();
	public function __construct($data, $boxname = "") {
		$this->BoxData['templatename'] = "downloadDatabaseBox";
		$this->getBoxStatus($data);
		$this->BoxData['boxID'] = $data['boxID'];
        $categories = $permFiles = $recentFiles = $topFiles = array();

        if(WCF::getUser()->getPermission('user.board.canViewDownloadDatabaseBox') && WCF::getUser()->getPermission('user.dldb.canUseDownloadDB')) {
    		WCF::getCache()->addResource('dldbKat',
    			WCF_DIR.'cache/cache.dldbKat.php',
    			WCF_DIR.'lib/system/cache/CacheBuilderDLDBKat.class.php');
    		$cacheCat = WCF::getCache()->get('dldbKat');
    		WCF::getCache()->addResource('dldbData',
    			WCF_DIR.'cache/cache.dldbData.php',
    			WCF_DIR.'lib/system/cache/CacheBuilderDLDBData.class.php');
            $cacheData = WCF::getCache()->get('dldbData');

            require_once(WCF_DIR.'lib/data/user/group/Group.class.php');
            // permissions...
            foreach($cacheCat as $cat) {
                $grpIDs = preg_split('/\,/', $cat['groupIDs']);
                foreach($grpIDs as $grp) {
                	if(Group::isMember($grp)) {
                		$categories[] = $cat['katID'];
                		break 1;
                	} 
                }
			}

            // active, permission?!...
            foreach($cacheData as $k => $v) {
                if($v['activ'] == true && in_array($v['katID'], $categories)) $permFiles[] = $cacheData[$k];
            }

            if(count($permFiles) > 0) {
                // recent files...
                $i = 0;
                usort($permFiles, array($this, 'cmpRecentDesc'));
                // recent files...
                foreach($permFiles as $k => $v) {
                    $recentFiles[] = $v;
                    $i++;
                    if($i >= 5) break;
                }

                // top files...
                $i = 0;
                usort($permFiles, array($this, 'cmpTopDesc'));
                foreach($permFiles as $k => $v) {
                    $topFiles[] = $v;
                    $i++;
                    if($i >= 5) break;
                }

            }
        }
        WCF::getTPL()->assign(array(
            'dldbBoxRecentFiles' => $recentFiles,
            'dldbBoxTopFiles' => $topFiles
        ));
	}

	protected function getBoxStatus($data) {
		// get box status
		$this->BoxData['Status'] = 1;
		if (WBBCore::getUser()->userID) {
			$this->BoxData['Status'] = intval(WBBCore::getUser()->downloadDatabaseBox);
		}
		else {
			if (WBBCore::getSession()->getVar('downloadDatabaseBox') != false) {
				$this->BoxData['Status'] = WBBCore::getSession()->getVar('downloadDatabaseBox');
			}
		}
	}

	public function getData() {
		return $this->BoxData;
	}
	
	protected function cmpRecentDesc($a, $b) {
        if($a['datum'] == $b['datum']) return 0;
        else return ($b['datum'] < $a['datum']) ? -1 : 1;
	}
	protected function cmpTopDesc($a, $b) {
        if($a['downloads'] == $b['downloads']) return 0;
        else return ($b['downloads'] < $a['downloads']) ? -1 : 1;
	}

}

?>
