<?php
require_once(WCF_DIR.'lib/acp/option/OptionTypeSelect.class.php');

/**
 * OptionTypeBoardsingleselect is an implementation of OptionType for 'select' tags with baords as dynamic content.
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
 *
 * @author	Nendilo
 * @copyright	Nendilo
 * @license	GNU General Public License <http://www.gnu.org/licenses/>
 * @package	de.wbb3plugins.optiontype.charsetselects
 * @subpackage acp.option
 * @category WCF
 */
class OptionTypeCharsetsingleselect extends OptionTypeSelect {

	/**
	 * @see OptionType::getFormElement()
	 */
	public function getFormElement(&$optionData) {
		if (!isset($optionData['optionValue'])) {
			if (isset($optionData['defaultValue'])) $optionData['optionValue'] = $optionData['defaultValue'];
			else $optionData['optionValue'] = false;
		}
		 
		// get options
		$options = array();
        $sql = 'SHOW CHARACTER SET';
		$result = WCF::getDB()->sendQuery($sql);
        $x = 0;
		while($charset = WCF::getDB()->fetchArray($result)) {
			$options[$charset['Charset']] = StringUtil::encodeHTML($charset['Description']);
		}
		WCF::getTPL()->assign(array(
			'optionData' => $optionData,
			'options' => $options
		));
		return WCF::getTPL()->fetch('optionTypeSelect');
	}

}
?>