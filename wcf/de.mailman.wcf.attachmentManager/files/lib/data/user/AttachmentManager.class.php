<?php
/**
 * $Id$
 * @author      MailMan (http://wbb3addons.ump2002.net)
 * @package     de.mailman.wcf.attachmentManager
 */

if(!defined('RET_ERROR')) define('RET_ERROR', 1);
if(!defined('RET_WARNING')) define('RET_WARNING', 2);
if(!defined('RET_INFO')) define('RET_INFO', 4);

// defaults
if(!defined('ATTACHMENTMANAGER_ITEMSPERPAGE'))  define('ATTACHMENTMANAGER_ITEMSPERPAGE', 20);
if(!defined('ATTACHMENTMANAGER_MAXLENGTHACP'))  define('ATTACHMENTMANAGER_MAXLENGTHACP', 20);
if(!defined('ATTACHMENTMANAGER_MAXLENGTHUCP'))  define('ATTACHMENTMANAGER_MAXLENGTHUCP', 0);
if(!defined('ATTACHMENTMANAGER_SCALING'))       define('ATTACHMENTMANAGER_SCALING', true);
if(!defined('ATTACHMENTMANAGER_SORTFIELD'))     define('ATTACHMENTMANAGER_SORTFIELD', 'uploadTime');
if(!defined('ATTACHMENTMANAGER_SORTORDER'))     define('ATTACHMENTMANAGER_SORTORDER', 'ASC');
if(!defined('ATTACHMENTMANAGER_TARGETWINDOW'))  define('ATTACHMENTMANAGER_TARGETWINDOW', '_blank');
if(!defined('ATTACHMENT_THUMBNAIL_HEIGHT'))     define('ATTACHMENT_THUMBNAIL_HEIGHT', 200);
if(!defined('ATTACHMENT_THUMBNAIL_WIDTH'))      define('ATTACHMENT_THUMBNAIL_WIDTH', 200);

class AttachmentManager {

	protected static $fileTypeGroups = array(
		'Music' => array('Aif', 'Mid', 'Mp3', 'Ogg', 'Wav', 'Aiff'),
		'System' => array('Bat', 'Dll'),
		'Web' => array('Css', 'Js'),
		'Database' => array('Db'),
		'Image' => array('Dmg', 'Img', 'Iso'),
		'TextDocument' => array('Doc', 'Sxw', 'Odt', 'Swd'),
		'Picture' => array('Png', 'Gif', 'Jpg', 'Jpeg', 'Tif', 'Tiff', 'Bmp', 'Psd'),
		'Html' => array('Html', 'Htm', 'Shtml', 'Tpl', 'Mht'),
		'Text' => array('Txt', 'Log', 'Sql', 'Rtf', 'Wri', 'Diff'),
		'Font' => array('Otf', 'Ttf'),
		'Archive' => array('Zip', 'Rar', 'Ace', '7z', 'Tar', 'Gz', 'Gzip', 'Bz2'),
		'SpreadSheet' => array('Xls', 'Sxc', 'Ods', 'Csv'),
		//'Video' => array('Mpeg', 'Avi', 'Wma', 'Mpg'),
		//'Xml' => array('Xml', 'Dtd'),
		//'Flash' => array('Swf', 'Fla'),
		'Java' => array('Jar', 'Java', 'Class')//,
		//'Php' => array('Php', 'Php3', 'Php4', 'Php5', 'Phtml')
	);

    public function wbbExists() {
        if(!defined('WBB_EXISTS')) {
            if(!defined('WBB_N') || !defined('WBB_DIR')) define('WBB_EXISTS', false);
            else define('WBB_EXISTS', true);
        }
        return WBB_EXISTS;
    }


    public function getUserByName($username) {
        $username = trim($username);
        $sql = "SELECT *"
            ."\n  FROM wcf".WCF_N."_user"
            ."\n WHERE LOWER(username) LIKE LOWER('".$username."%')";
		$row = WCF::getDB()->getFirstRow($sql);
		return $row;
    }

    public function getUserById($userID) {
        $sql = "SELECT *"
            ."\n  FROM wcf".WCF_N."_user"
            ."\n WHERE userID = ".$userID;
		$row = WCF::getDB()->getFirstRow($sql);
		return $row;
    }

    public function getMessageTypes($userID = 0, $showOnlyFileType = '', $showOnlyImages = 0) {
        $ret = array();
        $i = 0;
        $sql = "SELECT messageType, COUNT(*) AS cnt"
            ."\n  FROM wcf".WCF_N."_attachment"
            ."\n WHERE 1 = 1";
        if($userID > 0) $sql .= "\n   AND userID = ".$userID;
        if(!empty($showOnlyFileType)) $sql .= "\n   AND fileType = '".$showOnlyFileType."'";
        if(!empty($showOnlyImages)) $sql .= "\n   AND isImage = 1";
        $sql .= "\n GROUP BY messageType"
            ."\n ORDER BY messageType";
        $result = WCF::getDB()->sendQuery($sql);
        while($row = WCF::getDB()->fetchArray($result)) {
            $ret[$i] = $row;
            $i++;
        }
        return $ret;
    }

    public function getFileTypes($userID = 0, $showOnlyMessageType = '', $showOnlyImages = 0) {
        $ret = array();
        $i = 0;
        $sql = "SELECT fileType, COUNT(*) AS cnt"
            ."\n  FROM wcf".WCF_N."_attachment"
            ."\n WHERE 1 = 1";
        if($userID > 0) $sql .= "\n   AND userID = ".$userID;
        if(!empty($showOnlyMessageType)) $sql .= "\n   AND messageType = '".$showOnlyMessageType."'";
        if(!empty($showOnlyImages)) $sql .= "\n   AND isImage = 1";
        $sql .= "\n GROUP BY fileType"
            ."\n ORDER BY fileType";
        $result = WCF::getDB()->sendQuery($sql);
        while($row = WCF::getDB()->fetchArray($result)) {
            $ret[$i] = $row;
            $i++;
        }
        return $ret;
    }

    public function getPmUsage() {
		$maxPm = intval(WCF::getUser()->getPermission('user.pm.maxPm'));
		if(!$maxPm) $ret = 1.0;
		else {
			$ret = (double) WCF::getUser()->pmTotalCount / (double) $maxPm;
			if ($ret > 1.0) $ret = 1.0;
		}
		return $ret;
    }

    public function getTotalInfo($userID = 0) {
        $row = array();
        if(!$userID > 0 && !WCF::getUser()->getPermission('admin.general.attachmentManager.canView')) {
            $row['cnt'] = 0;
            $row['downloads'] = 0;
            $row['attachmentSize'] = 0;
            return $row;
        }
        $sql = "SELECT COUNT(*) AS cnt, IFNULL(SUM(downloads),0) AS downloads, IFNULL(SUM(attachmentSize),0) AS attachmentSize"
            ."\n  FROM wcf".WCF_N."_attachment"
            ."\n WHERE 1 = 1";
	    if($userID > 0) $sql .= "\n   AND userID = ".$userID;
        $row = WCF::getDB()->getFirstRow($sql);
        if(!empty($row['attachmentSize'])) $row['attachmentSize'] = round($row['attachmentSize'] / pow(1024,2), 2).' MB';
        return $row;
    }

    public function getCount($userID = 0, $showOnlyImages = 0, $showOnlyMessageType = '', $showOnlyFileType = '') {
		// count number of attachments
		$sql = "SELECT COUNT(*) AS count"
            ."\n  FROM wcf".WCF_N."_attachment"
            ."\n WHERE 1 = 1";
	    if($userID > 0) $sql .= "\n   AND userID = ".$userID;
        if(!empty($showOnlyImages)) $sql .= "\n   AND isImage = 1";
        if(!empty($showOnlyMessageType)) $sql .= "\n   AND messageType = '".$showOnlyMessageType."'";
        if(!empty($showOnlyFileType)) $sql .= "\n   AND fileType = '".$showOnlyFileType."'";
		$row = WCF::getDB()->getFirstRow($sql);
		return $row['count'];
    }

    public function getInfo($userID = 0, $showOnlyImages = 0, $showOnlyMessageType = '', $showOnlyFileType = '') {
        $sql = "SELECT COUNT(*) AS cnt, IFNULL(SUM(downloads),0) AS downloads, IFNULL(SUM(attachmentSize),0) AS attachmentSize"
            ."\n  FROM wcf".WCF_N."_attachment"
            ."\n WHERE 1 = 1";
	    if($userID > 0) $sql .= "\n   AND userID = ".$userID;
        if(!empty($showOnlyImages)) $sql .= "\n   AND isImage = 1";
        if(!empty($showOnlyMessageType)) $sql .= "\n   AND messageType = '".$showOnlyMessageType."'";
        if(!empty($showOnlyFileType)) $sql .= "\n   AND fileType = '".$showOnlyFileType."'";
        $row = WCF::getDB()->getFirstRow($sql);
        if(!empty($row['attachmentSize'])) $row['attachmentSize'] = round($row['attachmentSize'] / pow(1024,2), 2).' MB';
        return $row;
    }

    public function getAttachments($userID, $sortField, $sortOrder, $itemsPerPage, $pageNo, $isACP = false, $showThumbnails = 0, $showOnlyImages = 0, $showOnlyMessageType = '', $showOnlyFileType = '') {
        $ret = array();
        $i = 0;
        if($userID > 0) {
            $sortField2 = '';
            if($sortField == 'username') $sortField = 'uploadTime';
            if($sortField != 'attachmentName') $sortField2 .= ', LOWER(attachmentName) ASC';
            $sql = "SELECT *"
                ."\n  FROM wcf".WCF_N."_attachment"
                ."\n WHERE 1 = 1"
                ."\n   AND userID = ".$userID;
            if(!empty($showOnlyImages)) $sql .= "\n   AND isImage = 1";
            if(!empty($showOnlyMessageType)) $sql .= "\n   AND messageType = '".$showOnlyMessageType."'";
            if(!empty($showOnlyFileType)) $sql .= "\n   AND fileType = '".$showOnlyFileType."'";
            $sql .= "\n ORDER BY ".$sortField." ".$sortOrder.$sortField2
                ."\n LIMIT ".$itemsPerPage
                ."\nOFFSET ".(($pageNo - 1) * $itemsPerPage);
        } else {
            if(!WCF::getUser()->getPermission('admin.general.attachmentManager.canView')) return $ret;
            $sortField2 = '';
            if($sortField == 'username') $sortField = 'LOWER('.$sortField.')';
            else $sortField2 .= ', LOWER(username) ASC';
            if($sortField != 'attachmentName') $sortField2 .= ', LOWER(attachmentName) ASC';
            $sql = "SELECT *"
                ."\n  FROM wcf".WCF_N."_attachment at"
                ."\n  LEFT JOIN wcf".WCF_N."_user us ON (us.userID = at.userID)"
                ."\n WHERE 1 = 1";
            if(!empty($showOnlyImages)) $sql .= "\n   AND isImage = 1";
            if(!empty($showOnlyMessageType)) $sql .= "\n   AND messageType = '".$showOnlyMessageType."'";
            if(!empty($showOnlyFileType)) $sql .= "\n   AND fileType = '".$showOnlyFileType."'";
            $sql .= "\n ORDER BY ".$sortField." ".$sortOrder.$sortField2
                ."\n LIMIT ".$itemsPerPage
                ."\nOFFSET ".(($pageNo - 1) * $itemsPerPage);
        }

        $result = WCF::getDB()->sendQuery($sql);
        while($row = WCF::getDB()->fetchArray($result)) {
            // username
            if(self::wbbExists() && empty($row['username']) && $row['messageType'] == 'post') {
                $tmp = WCF::getDB()->getFirstRow('SELECT username FROM wbb'.WBB_N.'_post WHERE postID = '.$row['messageID']);
                if(isset($tmp['username'])) $row['username'] = $tmp['username'];
            } else if(empty($row['username']) && $row['messageType'] == 'pm') {
                $tmp = WCF::getDB()->getFirstRow('SELECT username FROM wcf'.WCF_N.'_pm WHERE pmID = '.$row['messageID']);
                if(isset($tmp['username'])) $row['username'] = $tmp['username'];
            }
            if(!empty($row['username'])) $row['username'] = StringUtil::encodeHTML($row['username']);
            else $row['username'] = '-';
            if(!empty($row['userID'])) $row['username'] = '<a href="index.php?form=UserEdit&userID='.$row['userID'].'&packageID='.PACKAGE_ID.SID_ARG_2ND_NOT_ENCODED.'" title="'.WCF::getLanguage()->get('wcf.acp.user.edit').'">'.$row['username'].'</a>';

            // pm
            $ownPM = false;
            if($row['messageType'] == 'pm' && !empty($row['userID']) && ($row['userID'] == $userID || $row['userID'] == WCF::getUser()->userID)) {
                if(!empty($userID)) $tUserID = $userID;
                else $tUserID = WCF::getUser()->userID;
                $sql = "SELECT COUNT(*) AS cnt"
                    ."\n  FROM wcf".WCF_N."_pm"
                    ."\n WHERE pmID = ".$row['messageID']
                    ."\n   AND userID = ".$tUserID
                    ."\n   AND saveInOutbox != 0";
                $tmp = WCF::getDB()->getFirstRow($sql);
                if(!empty($tmp['cnt'])) $ownPM = true;
            }

            if(!empty($row['attachmentSize'])) $row['attachmentSize'] = round(($row['attachmentSize'] / 1024),2).' kB';

            // message type urls
            $row['messageTypeUrl'] = $row['messageType'];
            if(self::wbbExists() && preg_match('/^(post|pm)$/', $row['messageType'])) {
                if($row['messageType'] == 'post') $row['messageTypeUrl'] = '<a href="'.RELATIVE_WBB_DIR.'index.php?page=Thread&postID='.$row['messageID'].'#post'.$row['messageID'].'" target="'.ATTACHMENTMANAGER_TARGETWINDOW.'">'.$row['messageType'].'</a>';
                else if($ownPM) $row['messageTypeUrl'] = '<a href="'.RELATIVE_WBB_DIR.'index.php?page=PMView&pmID='.$row['messageID'].'#pm'.$row['messageID'].'" target="'.ATTACHMENTMANAGER_TARGETWINDOW.'">'.$row['messageType'].'</a>';
            }

            // thumbnails / files
            $maxLength = 0;
            $shortFileName = $row['attachmentName'];
            if($isACP && ATTACHMENTMANAGER_MAXLENGTHACP > 0) $maxLength = ATTACHMENTMANAGER_MAXLENGTHACP;
            else if(ATTACHMENTMANAGER_MAXLENGTHUCP > 0) $maxLength = ATTACHMENTMANAGER_MAXLENGTHUCP;
            if($maxLength > 0 && strlen($shortFileName) > $maxLength) {
                preg_match('/^(.*)(\..*)$/',$shortFileName, $match);
                if(isset($match[2])) $shortFileName = substr($match[1], 0, ($maxLength - (strlen($match[2]) + 2))).'..'.$match[2];
                else $shortFileName = substr($shortFileName, 0, $maxLength);
            }

            $row['attachmentUrl'] = '<span title="'.$row['attachmentName'].'">'.$shortFileName.'</span>';
            if(self::wbbExists()) {
                if($row['messageType'] == 'pm' && !$ownPM) {
                    $row['attachmentUrl'] = '<span title="'.$row['attachmentName'].'">'.$shortFileName.'</span>';
                } else if(!empty($showThumbnails) && !empty($row['isImage'])) {
                    if(!empty($row['thumbnailSize'])) {
                        $row['attachmentUrl'] = '<a href="'.RELATIVE_WBB_DIR.'index.php?page=Attachment&attachmentID='.$row['attachmentID'].'&h='.$row['sha1Hash'].'" target="'.ATTACHMENTMANAGER_TARGETWINDOW.'" style="width:'.ATTACHMENT_THUMBNAIL_WIDTH.'px; height:'.ATTACHMENT_THUMBNAIL_HEIGHT.'px;"><img src="'.RELATIVE_WBB_DIR.'index.php?page=Attachment&attachmentID='.$row['attachmentID'].'&h='.$row['sha1Hash'].'&thumbnail=1" alt="'.$row['attachmentName'].'" title="'.$row['attachmentName'].'" style="max-width:'.ATTACHMENT_THUMBNAIL_WIDTH.'px; max-height:'.ATTACHMENT_THUMBNAIL_HEIGHT.'px;" /></a>';
                    } else {
                        $row['attachmentUrl'] = '<a href="'.RELATIVE_WBB_DIR.'index.php?page=Attachment&attachmentID='.$row['attachmentID'].'&h='.$row['sha1Hash'].'" target="'.ATTACHMENTMANAGER_TARGETWINDOW.'" style="width:'.ATTACHMENT_THUMBNAIL_WIDTH.'px; height:'.ATTACHMENT_THUMBNAIL_HEIGHT.'px;"><img src="'.RELATIVE_WBB_DIR.'index.php?page=Attachment&attachmentID='.$row['attachmentID'].'&h='.$row['sha1Hash'].'" alt="'.$row['attachmentName'].'" title="'.$row['attachmentName'].'" style="max-width:'.ATTACHMENT_THUMBNAIL_WIDTH.'px; max-height:'.ATTACHMENT_THUMBNAIL_HEIGHT.'px;" /></a>';
                    }
                } else {
                    $row['attachmentUrl'] = '<a href="'.RELATIVE_WBB_DIR.'index.php?page=Attachment&attachmentID='.$row['attachmentID'].'&h='.$row['sha1Hash'].'" target="'.ATTACHMENTMANAGER_TARGETWINDOW.'" title="'.$row['attachmentName'].'">'.$shortFileName.'</a>';
                }
            }

            $icon = RELATIVE_WCF_DIR.'icon/fileTypeIconDefaultM.png';
            // get file extension
            $extension = StringUtil::firstCharToUpperCase(StringUtil::toLowerCase(StringUtil::substring($row['attachmentName'], StringUtil::lastIndexOf($row['attachmentName'], '.') + 1)));
            // get file type icon
            if (file_exists(WCF_DIR.'icon/fileTypeIcon'.$extension.'M.png')) {
            	$icon = RELATIVE_WCF_DIR.'icon/fileTypeIcon'.$extension.'M.png';
            } else {
            	foreach (self::$fileTypeGroups as $key => $group) {
            		if (in_array($extension, $group)) {
            		    $icon = RELATIVE_WCF_DIR.'icon/fileTypeIcon'.$key.'M.png';
            		    break;
            		}
            	}
            }
            $row['mimeIcon'] = '<img src="'.$icon.'"'.($isACP ? ' height="16" width="16"' : '').' alt="'.$row['fileType'].'" title="'.$row['fileType'].'" />';
            $ret[$i] = $row;
            $i++;
        }
        return $ret;
    }

    public function deleteAttachments($userID, $attachments) {
        $ret['CODE'] = 0;
        $ret['MSG'] = '';

        $del = implode(',', $attachments);
        $sql = "DELETE FROM wcf".WCF_N."_attachment"
            ."\n WHERE 1 = 1"
            .($userID > 0 ? "\n   AND userID = ".$userID : "")
            ."\n   AND attachmentID IN (".$del.")";

        if(WCF::getDB()->sendQuery($sql)) {
            $cntDeleted = WCF::getDB()->getAffectedRows();
            if($cntDeleted > 0) {
                foreach($attachments as $attachmentID) {
                    // delete attachment file
                    if (file_exists(WCF_DIR.'attachments/attachment-'.$attachmentID)) @unlink(WCF_DIR.'attachments/attachment-'.$attachmentID);
                    // delete thumbnail, if exists
                    if (file_exists(WCF_DIR.'attachments/thumbnail-'.$attachmentID)) @unlink(WCF_DIR.'attachments/thumbnail-'.$attachmentID);
                }
            }
            $ret['CODE'] = RET_INFO;
            $ret['MSG'] = WCF::getLanguage()->get('wcf.user.attachmentManager.info.deleted', array('$cntDeleted' => $cntDeleted));
        } else {
            $ret['CODE'] = RET_ERROR;
            $ret['MSG'] = WCF::getLanguage()->get('wcf.user.attachmentManager.error.deleted');
        }
        return $ret;
    }
}
?>
