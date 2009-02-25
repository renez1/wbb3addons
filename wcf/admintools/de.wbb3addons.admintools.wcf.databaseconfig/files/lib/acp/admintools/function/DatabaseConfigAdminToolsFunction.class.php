<?php
require_once(WCF_DIR.'lib/acp/admintools/function/AbstractAdminToolsFunction.class.php');

/**
 * Copies board permissions
 *
 * This file is part of Admin Tools 2.
 *
 * Admin Tools 2 is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Admin Tools 2 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Admin Tools 2.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author	Nendilo
 * @copyright	2009 wbb3addons.de
 * @license	GNU General Public License <http://www.gnu.org/licenses/>
 * @package	de.wbb3addons.admintools.wcf.databaseconfig
 * @subpackage acp.admintools.function
 * @category WCF
 */
class DatabaseConfigAdminToolsFunction extends AbstractAdminToolsFunction {

    protected $_configPassword      = '';
    protected $_configClass         = '';
    protected $_time                = '';
    protected $availableNotTables   = '';

	/**
	 * Prepares the permission list
	 */
	public function __construct() {
        $this->_configPassword  = $this->parseConfig(2);
        $this->_configClass     = $this->parseConfig(5);
        $this->_time            = time();
	}

	/**
	 * @see AdminToolsFunction::execute($data)
	 */
	public function execute($data) {
		parent::execute($data);

		$parameters         = $data['parameters']['database.config'];
        $error              = array();
        $missingTables      = array();
        $wcfExistingTables  = WCF::getDB()->getTableNames();
        $wcfRequiredTables  = $this->replaceWcfNumber($parameters['wcfNumber']);
        // Check dbHost
        if(empty($parameters['dbHost'])) $error[] = WCF::getLanguage()->get('wcf.acp.admintools.function.database.config.errorDbHostEmpty');
        // Check dbUser
        if(empty($parameters['dbUser'])) $error[] = WCF::getLanguage()->get('wcf.acp.admintools.function.database.config.errorDbUserEmpty');
        // Check dbPassword's
        if(empty($parameters['dbPassword1'])){ $error[] = WCF::getLanguage()->get('wcf.acp.admintools.function.database.config.errorDbPassword1Empty');}
        elseif(!empty($parameters['dbPassword1']) && empty($parameters['dbPassword2'])){ $error[] = WCF::getLanguage()->get('wcf.acp.admintools.function.database.config.errorDbPassword2Empty');}
        elseif($parameters['dbPassword1'] != $parameters['dbPassword2']){ $error[] = WCF::getLanguage()->get('wcf.acp.admintools.function.database.config.errorDbPasswordsUnequal');}
        // Check dbName
        if(empty($parameters['dbName'])) $error[] = WCF::getLanguage()->get('wcf.acp.admintools.function.database.config.errorDbNameEmpty');
        // Check dbCharset
        if($parameters['dbCharset'] == '0') $error[] = WCF::getLanguage()->get('wcf.acp.admintools.function.database.config.errorDbCharsetEmpty');
        // Check wcfNumber
        if(empty($parameters['wcfNumber']) || $parameters['wcfNumber'] == '0'){ $error[] = WCF::getLanguage()->get('wcf.acp.admintools.function.database.config.errorWcfNumberEmpty');}
        elseif(!is_int($parameters['wcfNumber'])){ $error[] = WCF::getLanguage()->get('wcf.acp.admintools.function.database.config.errorWcfNumberNOTint');}
        // Check oldDbPassword
        if(empty($parameters['oldDbPassword'])){ $error[] = WCF::getLanguage()->get('wcf.acp.admintools.function.database.config.errorOldDbPasswordEmpty');}
        elseif($parameters['oldDbPassword'] != $this->_configPassword){ $error[] = WCF::getLanguage()->get('wcf.acp.admintools.function.database.config.errorOldDbPasswordUnequal');}
        // If $error send message
        if(count($error)){
            $this->setReturnMessage('error', '<ul>'.implode($error).'</ul>');
        }
        else{
            // Check connection
            $connect = @mysql_connect($parameters['dbHost'], $parameters['dbUser'], $parameters['dbPassword1'], true);
            if(!$connect && $parameters['checkConnect']){
                $this->setReturnMessage('error', '<ul>'.WCF::getLanguage()->get('wcf.acp.admintools.function.database.config.errorNoConnect').'</ul>');
            }
            // Check database
            elseif(!@mysql_select_db($parameters['dbName'], $connect) && $parameters['checkConnect']){
                $this->setReturnMessage('error', '<ul>'.WCF::getLanguage()->get('wcf.acp.admintools.function.database.config.errorNoDatabase').'</ul>');
            }
            else{
                if($parameters['checkTables'] && $parameters['checkConnect']){
                    foreach($wcfRequiredTables as $table){
                        if(@mysql_num_rows(@mysql_query("SHOW TABLES LIKE '".$table."'", $connect)) == 0){
                            $missingTables[] = $table;
                            $this->availableNotTables .= '<li>'.$table.'</li>';
                        }
                    }
                    // Count not available tables
                    if(count($missingTables)){
                        (count($missingTables) == count($wcfRequiredTables)) ? $this->setReturnMessage('error', '<ul>'.WCF::getLanguage()->get('wcf.acp.admintools.function.database.config.errorTablesFailing').'</ul>') : $this->setReturnMessage('error', '<ul>'.WCF::getLanguage()->get('wcf.acp.admintools.function.database.config.errorTablesIncompleted', array('$availableTables' => $this->availableNotTables)).'</ul>');
                    }
                }
                // Create backup
                elseif(!@copy(WCF_DIR.'config.inc.php', WCF_DIR.'acp/backup/wcf_'.date('Y-m-d-H-i-s', $this->_time).'_config.inc.php')){
                    $this->setReturnMessage('error', '<ul>'.WCF::getLanguage()->get('wcf.acp.admintools.function.database.config.errorBackupFailed').'</ul>');
                }
                // Write config.inc.php
                else{
                    $file = "<?php\n";
                    $file .= "\$dbHost = '".StringUtil::replace("'", "\\'", $parameters['dbHost'])."';\n";
                    $file .= "\$dbUser = '".StringUtil::replace("'", "\\'", $parameters['dbUser'])."';\n";
                    $file .= "\$dbPassword = '".StringUtil::replace("'", "\\'", $parameters['dbPassword1'])."';\n";
                    $file .= "\$dbName = '".StringUtil::replace("'", "\\'", $parameters['dbName'])."';\n";
                    $file .= "\$dbCharset = '".StringUtil::replace("'", "\\'", $parameters['dbCharset'])."';\n";
                    $file .= "\$dbClass = '".StringUtil::replace("'", "\\'", $this->_configClass)."';\n";
                    $file .= "if (!defined('WCF_N')) define('WCF_N', ".$parameters['wcfNumber'].");\n?>";
                    if(!@file_put_contents(WCF_DIR.'config.inc.php', $file)){
                        $this->setReturnMessage('error', '<ul>'.WCF::getLanguage()->get('wcf.acp.admintools.function.database.config.errorCanNOTWrite').'</ul>');
                    }
                    // The end
                    else{
                        $this->executed();
                    }
                }
            }
            @mysql_close($connect);
        }
    }
    private function parseConfig($x){
        $file = file_get_contents(WCF_DIR.'config.inc.php');
        $pattern = '/\$(.*) = \'(.*)\';/i';
        preg_match_all($pattern, $file, $matches, PREG_PATTERN_ORDER);
        return $matches[2][$x];
    }
    protected function replaceWcfNumber($wcfNumber){
        $wcfTables = WCF::getDB()->getTableNames();
        /* Test missing tables
        $wcfTables[] = 'wcf1_test_table1';
        $wcfTables[] = 'wcf1_test_table2';
        $wcfTables[] = 'wcf1_test_table3';
        */
        foreach($wcfTables as $key => $table){
            if(StringUtil::indexOf($table, 'wcf'.WCF_N.'_') === false){
                unset($wcfTables[$key]);
            }
            else{
                $wcfTables[$key] = StringUtil::replace('wcf'.WCF_N.'_', 'wcf'.$wcfNumber.'_', $table);
            }
        }
        return $wcfTables;
    }
}
?>