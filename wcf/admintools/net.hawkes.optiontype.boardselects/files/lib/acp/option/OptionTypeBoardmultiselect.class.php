<?php
require_once(WBB_DIR.'lib/data/board/Board.class.php');
require_once(WCF_DIR.'lib/acp/option/OptionTypeSelect.class.php');

/**
 * OptionTypeBoardmultiselect is an implementation of OptionType for 'select' tags with baords as dynamic content.
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
 * @author	Oliver Kliebisch
 * @copyright	2009 Oliver Kliebisch
 * @license	GNU General Public License <http://www.gnu.org/licenses/>
 * @package	net.hawkes.optiontype.boardselects
 * @subpackage acp.option
 * @category WBB
 */
class OptionTypeBoardmultiselect extends OptionTypeSelect {
	
	/**
	 * @see OptionType::getFormElement()
	 */
	public function getFormElement(&$optionData) {
		if (!isset($optionData['optionValue'])) {
			$optionData['optionValue'] = false;
		}
		else {
			$optionData['optionValue'] = explode(',', $optionData['optionValue']);
		}
		// get options
		$options = Board::getBoardSelect(array(), true);

		WCF::getTPL()->assign(array(
			'optionData' => $optionData,
			'options' => $options
		));
		return WCF::getTPL()->fetch('optionTypeBoardmultiselect');
	}
	
	/**
	 * @see OptionType::validate()
	 */
	public function validate($optionData, $newValue) {
		$options = Board::getBoardSelect(array(), true);
		if(is_array($newValue)) {
			foreach($newValue as $key => $value) {
				if(!isset($options[$value])) return false;
			}
		}
		return true;
	}
	
	/**	 
	 * @see OptionType::getData()
	 */
	public function getData($optionData, $newValue) {
		if(is_array($newValue)) {
			return implode(',', $newValue);
		}
		return null;
	}
}
?>