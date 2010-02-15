<?php


require_once(PATH_typo3.'/interfaces/interface.localrecordlist_actionsHook.php');


// examples of this kind of hook in:
// https://svn.typo3.org/TYPO3v4/Extensions/lockadmin/trunk/class.localrecordlist_actionsHook.php
// https://svn.typo3.org/TYPO3v4/Extensions/listaddons/trunk/class.tx_listaddons_hooks.php

class user_localRecordList_actionsHook implements localrecordlist_actionsHook {
	
	/**
	 * modifies Web>List clip icons (copy, cut, paste, etc.) of a displayed row
	 *
	 * @param	string		the current database table
	 * @param	array		the current record row
	 * @param	array		the default clip-icons to get modified
	 * @param	object		Instance of calling object
	 * @return	array		the modified clip-icons
	 */
	public function makeClip($table, $row, $cells, &$parentObject){
		//don't:
		#return localRecordList::makeClip($table, $row);
		#return $parentObject->makeClip($table, $row);
		//this is the right way:
		return $cells;
	}


	/**
	 * modifies Web>List control icons of a displayed row
	 *
	 * @param	string		the current database table
	 * @param	array		the current record row
	 * @param	array		the default control-icons to get modified
	 * @param	object		Instance of calling object
	 * @return	array		the modified control-icons
	 */
	public function makeControl($table, $row, $cells, &$parentObject){
		//this is where the music plays!
		debug($table, '$table');
		debug($row, '$row');
		debug($cells, '$cells');
		#debug($parentObject, '$parentObject'); //rather biggish!!!
		
		//for testing only:
		#$cells['delete'] = $this->spaceIcon;
		
		
		if($table == 'tx_civserv_model_service_temp'){
			// citeq test:
			$cells['delete'] = $this->spaceIcon;
		}
		
		
		//this is the right way:
		return $cells;
		//don't:
		#return localRecordList::makeControl($table, $row);
		#return $parentObject->makeControl($table, $row);
	}


	/**
	 * modifies Web>List header row columns/cells
	 *
	 * @param	string		the current database table
	 * @param	array		Array of the currently displayed uids of the table
	 * @param	array		An array of rendered cells/columns
	 * @param	object		Instance of calling (parent) object
	 * @return	array		Array of modified cells/columns
	 */
	public function renderListHeader($table, $currentIdList, $headerColumns, &$parentObject){
		//keep as is
		return $headerColumns;
		
	}


	/**
	 * modifies Web>List header row clipboard/action icons
	 *
	 * @param	string		the current database table
	 * @param	array		Array of the currently displayed uids of the table
	 * @param	array		An array of the current clipboard/action icons
	 * @param	object		Instance of calling (parent) object
	 * @return	array		Array of modified clipboard/action icons
	 */
	public function renderListHeaderActions($table, $currentIdList, $cells, &$parentObject){
		//keep as is
		return $cells;
	}
	
	
}


?>