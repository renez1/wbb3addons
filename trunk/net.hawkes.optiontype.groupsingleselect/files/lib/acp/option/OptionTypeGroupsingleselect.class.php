<?php
require_once(WCF_DIR.'lib/acp/option/OptionTypeSelect.class.php');

/**
 * OptionTypeSelect is an implementation of OptionType for 'select' tags with user groups as options.
 *
 * @package	net.hawkes.optiontype.groupsingleselect
 * @author	Oliver KKliebisch
 * @copyright	2009 Oliver Kliebisch
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 */
class OptionTypeGroupsingleselect extends OptionTypeSelect  {
	
	/**
	 * @see OptionType::getFormElement()
	 */
	public function getFormElement(&$optionData) {
		$optionData['divClass'] = 'select';
		if (!isset($optionData['optionValue'])) {
			if (isset($optionData['defaultValue'])) $optionData['optionValue'] = $optionData['defaultValue'];
			else $optionData['optionValue'] = false;
		}
		
		$options = array();
		$groups = Group::getAllGroups();
		foreach($groups as $groupID => $group) {
			$options[$groupID] = StringUtil::encodeHTML($group);
		}
		
		WCF::getTPL()->assign(array(
			'optionData' => $optionData,
			'options' => $options
		));
		return WCF::getTPL()->fetch('optionTypeSelect');
	}	
}
?>