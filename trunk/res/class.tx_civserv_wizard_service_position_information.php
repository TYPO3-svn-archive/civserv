<?php
/***************************************************************
* Copyright notice
*
* (c) 2004 ProService (osiris@ercis.de)
* All rights reserved
*
* This script is part of the TYPO3 project. The TYPO3 project is
* free software; you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation; either version 2 of the License, or
* (at your option) any later version.
*
* The GNU General Public License can be found at
* http://www.gnu.org/copyleft/gpl.html.
* A copy is found in the textfile GPL.txt and important notices to the license
* from the author is found in LICENSE.txt distributed with these scripts.
*
*
* This script is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
* This class holds a user defined wizard to select and show content from extension tables
*
* Some scripts that use this class: tca.php (Invocation), ext_tables.php (Definition), ext_localconf.php (Definition)
* Depends on: tca.php, there it must be defined as wizard / userfunc
*
* $Id$
*
* @@author Tobias Müller (mullerto@@uni-muenster.de),
* @@author Maurits Hinzen (mhinzen@@uni-muenster.de),
* @@package TYPO3
* @@subpackage tx_civserv
* @@version 1.0
*
* Changes: 09.08.04, CR - Anpassung an Konventionen
*/
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   75: class tx_civserv_wizard_service_position_information extends t3lib_SCbase
 *   91:     function init()
 *  296:     function main()
 *  388:     function getPositions($letter)
 *  440:     function position_selected($position_uid)
 *  457:     function printContent()
 *
 * TOTAL FUNCTIONS: 5
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */


	// DEFAULT initialization of a module [BEGIN]
require_once ('conf.php');
require_once ($BACK_PATH.'init.php');
require_once ($BACK_PATH.'template.php');
require_once (PATH_t3lib.'class.t3lib_scbase.php');
require_once ($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/civserv/res/class.tx_civserv_mandant.php']);


	// Initialization of language file
include ('locallang_wizard.php');
$LANG->includeLLFile('EXT:res/locallang_wizard.php');



/**
* This is an abc-wizard for choosing positions for a service. Multiple positions can be selected.
*/
class tx_civserv_wizard_service_position_information extends t3lib_SCbase {

	var $content;		// whole HTML-Content to be displayed in Wizard
	var $res;			// results from SQL-Queries
	var $P;				// Array given from the parent window in backend
	var $bparams;		// reads the value of the "caller-field" from backend, needed to adress and change this field
	var $mode;			// mode for group box, "db" in this case
	var $pArr;			// contains parts of the $bparams
	var $PItemName;
	var $searchitem;

	/**
	 * Initializes the wizard by getting values out of the p-array.
	 *
	 * @return	[type]		Returns the HTML-Header including all JavaScript-Functions.
	 * @@return	void
	 */
function init() {
		global $LANG;		// Has to be in every function which uses localization data.

			// Gets parameters out of the p-array.
		$this->P = t3lib_div::_GP('P');

			// Find "mode"
		$this->mode='db';

			// Is $P set? if not, read from URL. This is needed because otherwise
			// the p-array will be lost and no data could be written back to the
			// main window.
		if ($this->P['itemName']) {
			$this->pArr = array();
			$this->pArr = explode('|',$this->P['itemName']);
			$this->service_pid = $this->P['pid'];	// Gets parent id of service from p-array.
		} else {
			$this->pArr = array();
			$this->pArr[0] = t3lib_div::_GP('PItemName');
			$this->service_pid = t3lib_div::_GP('service_pid');	// Gets parent id of service from url.
		}
		$this->PItemName = "&PItemName=".$this->pArr[0];

			// In case the parent id of service is still not set, try to
			// get it out of the database. The service_pid is important because with it the uid of the mandant can be retrievied.
		if (!intval($this->service_pid) > 0){
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				'pid',			 			// SELECT ...
				$this->P['table'],			// FROM ...
				'uid = '.$this->P['uid'],	// AND title LIKE "%blabla%"', // WHERE...
				'', 						// GROUP BY...
				'',   						// ORDER BY...
				'' 							// LIMIT to 10 rows, starting with number 5 (MySQL compat.)
			);
			$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
			$this->service_pid = $row['pid'];
		}

		$formFieldName = 'data['.$this->pArr[0].']['.$this->pArr[1].']['.$this->pArr[2].']';

			// Draw the header.
		$this->content.='
			<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
			 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
			<?xml version="1.0" encoding="iso-8859-1"?>
			<?xml-stylesheet href="#internalStyle" type="text/css"?>

			<html>
			<head>
			<!-- TYPO3 Script ID: typo3conf/ext/civserv/xlass.tx_civserv_wizard_employee_em_position.php -->
			<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
			<meta name="GENERATOR" content="TYPO3 3.6.2, http://typo3.com, &#169; Kasper Sk&#229;rh&#248;j 1998-2004, extensions are copyright of their respective owners." />

			<title>'.$LANG->getLL('tx_civserv_wizard_service_position_information.title').'</title>

			<link rel="stylesheet" type="text/css" href="stylesheet_wizard.css" />
		';

				// JavaScript
		$this->content.='
			<script type="text/javascript">

			script_ended = 0;
			
			
			function jumpToUrl(URL)	{	//
				document.location = URL;
			}
			

				// Adds all selected items of the displayed selectorbox
				// to the former selected items given in cur_selected_uid
				// and cur_selected_name.
			function returnOptions(cur_selected_uid,cur_selected_name)	{	//
				var options="";
				var selected_uid = "";
				var selected_name = "";
				var cur_uid_arr = cur_selected_uid.split("|");
				var cur_name_arr = cur_selected_name.split("|");
				var removed;	// just a dummy variable
				var num_removed=0;	// counts the deleted items out of an array

				for(i=0;i<document.serviceform.selectedPositions.length;++i) {
					num_removed=0;

						// Removes all selectorbox entries of current letter from current options.
					for (j=0;j<cur_uid_arr.length-num_removed;j++) {
						if (cur_uid_arr[j-num_removed]==document.serviceform.selectedPositions.options[i].value) {
							removed = cur_uid_arr.splice(j-num_removed,1);
							removed = cur_name_arr.splice(j-num_removed,1);
							num_removed++;
						}
					}

						// Gets all selected items of current selectorbox.
					if(document.serviceform.selectedPositions.options[i].selected == true) {
						if (selected_uid=="") {
							selected_uid=document.serviceform.selectedPositions.options[i].value;
							selected_name=document.serviceform.selectedPositions.options[i].label;
						} else {
							selected_uid=selected_uid+"|"+document.serviceform.selectedPositions.options[i].value;
							selected_name=selected_name+"|"+document.serviceform.selectedPositions.options[i].label;
						}
					}
				}

					// Adds selected items to the list of all selected items.
				if (cur_selected_uid.length>0) {
					if (selected_uid=="") {
						selected_uid = cur_uid_arr.join("|");
						selected_name = cur_name_arr.join("|");
					} else {
						selected_uid = selected_uid + "|" + cur_uid_arr.join("|");
						selected_name = selected_name + "|" + cur_name_arr.join("|");
					}
				}

				selected_uid = "&selected_uid=" + selected_uid;
				selected_name = "&selected_name=" + selected_name;
				options = selected_uid + selected_name;

				return options;
			}

				// Adds selected items (=options) and refreshes the
				// browser window.
			function add_options_refresh(letter,cur_selected_uid,cur_selected_name,script,PItemName,service_pid)	{	//
				searchitem = document.serviceform.searchitem.value
				if (document.serviceform.selectedPositions) {
					options = returnOptions(cur_selected_uid,cur_selected_name);
				} else {	// if no selectorbox is displayed at beginning
					options = "";
				}
				if (letter=="search" && searchitem=="") {
					alert ("'.$LANG->getLL('all_wizards.search_warning').'");
				} else {
					jumpToUrl(script+"?letter="+letter+options+PItemName+service_pid+"&searchitem="+searchitem,this);
				}
			}

		

			</script>
			<!--###POSTJSMARKER###-->
			</head>
		';
		parent::init();
	}//end init


	/**
	 * Contains all the logic of the wizard. Shows the abc and in a selectorbox underneath the
	 * positions beginning with the selected letter. The User can select one or more positions
	 * for one service.
	 *
	 * @return	[type]		Returns the HTML-Body with the abc and the selectorbox.
	 * @@return	void
	 */
	function main()	{
		global $LANG;
		$this->searchitem = (string)t3lib_div::_GP('searchitem');
			// Draw the body.
		$this->content.='
			<body scroll="auto" id="typo3-browse-links-php">
			<form name="serviceform" action="" method="post">

			<h3 class="bgColor5">'.$LANG->getLL('tx_civserv_wizard_service_position_information.select_letter_text').':</h3>
			<br /><br /><br /><table border="0" cellpadding="0" cellspacing="0" id="typo3-tree">
				<tr class="bgColor">';

		$script=basename(PATH_thisScript);


		$this->content.='
					<td width="120px" nowrap="nowrap">
						<input type="text" size="20" name="searchitem" id="searchitem" value="'.$this->searchitem.'">
					</td>
					<td>	
						<strong><a href="#" onclick="add_options_refresh(\'search\',\''.(string)t3lib_div::_GP('selected_uid').'\',\''.(string)t3lib_div::_GP('selected_name').'\',\''.$script.'\',\''.$this->PItemName.'\',\'&service_pid='.htmlspecialchars($this->service_pid).'\')">'.$LANG->getLL('all_abc_wizards.search').'</a><strong>
					</td>
				</tr>
			</table><br /><br /><br />';
			
		$this->content.='<table border="0" cellpadding="0" cellspacing="0" id="typo3-tree">
				<tr class="bgColor">
					<td nowrap="nowrap">';
					
		$this->content.=$this->getPositions();
		
		$this->content.='
				</td>
			</tr>
		</table>';

			// Displays a Cancel-Button at the end of the Page to exit the wizard without changing anything.
		$this->content.='
			<p class="closeWindow"><br /><br /><br /><input type="button" name="cancel" value="'.$LANG->getLL('tx_civserv_wizard_service_position_information.Cancel_Button').'" onclick="parent.close();"></p>
			</form>
			</body>
			</html>';
	}//end main

	/**
	 * Generates a selector box with the positions locally available for this install.
	 *
	 * @param	[type]		$letter: the selected letter to show positions beginning with this letter in the selectorbox.
	 * @return	[type]		...
	 * @@return	string		Selector box with positions.
	 */
	function getPositions()	{
		global $LANG;
		$GLOBALS['TYPO3_DB']->debugOutput = TRUE;
		$this->searchitem = (string)t3lib_div::_GP('searchitem');
		$this->searchitem = $this->make_clean($this->searchitem);


		if ($this->searchitem != "") {
			$this->res = $GLOBALS['TYPO3_DB']->exec_SELECT_mm_query(
			'tx_civserv_employee.em_name, tx_civserv_employee.em_firstname, tx_civserv_position.uid, tx_civserv_position.pid, tx_civserv_position.po_name',	//SELECT 							// SELECT ...
			'tx_civserv_employee',						// FROM local
			'tx_civserv_employee_em_position_mm',		// FROM mm
			'tx_civserv_position',						// FROM foreign
			'AND tx_civserv_employee.deleted=0 AND tx_civserv_employee.hidden=0	
			 AND tx_civserv_position.deleted=0 AND tx_civserv_position.hidden=0
			 AND po_name like \'%'.$this->searchitem.'%\'',	// AND title LIKE "%blabla%"', // WHERE...
			'', 										// GROUP BY...
			'po_name',   								// ORDER BY...
			'' 											// LIMIT to 10 rows, starting with number 5 (MySQL compat.)
			);
		} 
		$searchResult=array();

			// Removes all positions from other mandants so that only
			// the positions of the actual mandant are displayed in the
			// selectorbox.
		$mandant_obj = t3lib_div::makeInstance('tx_civserv_mandant');
		$mandant = $mandant_obj->get_mandant($this->service_pid);
		if ($this->res) {
			$count=0;
			while ($positions = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($this->res)) {
				$count++;
					// Checks if the uid is already selected.
				if ($mandant_obj->get_mandant($positions['pid'])==$mandant){
					if ($this->position_selected($positions[uid])) {
						$selVal = 'selected="selected"';
					} else {
						$selVal = '';
					}
						$searchResult[]='<li>'.htmlspecialchars($positions[em_name]).', '.htmlspecialchars($positions[em_firstname]).' ('.htmlspecialchars($positions[po_name]).')</li>';
				}
			}
		}
		$PItemName = "&PItemName=".$this->pArr[0];

			// Displays the search result
		if($count==1){
			return '<ul>'.implode('',$searchResult).'</ul>';
		}
		if($count>1){
			return '<p>'.$count.' Stellen mit der Bezeichnung '.$this->searchitem.' vorhanden</p><ul>'.implode('',$searchResult).'</ul>';
		}
		if($count==0 && $this->searchitem != ''){
			//todo: localize!!!
			return '<p>Keine Stelle mit der Bezeichnung '.$this->searchitem.' vorhanden</p>';
		}
		
	}//end getPositions


	/**
	 * Checks if a position is already selected by the user.
	 *
	 * @param	[type]		$position_uid [string]: the uid of a position
	 * @return	[type]		...
	 * @@return	boolean
	 */
	function position_selected($position_uid) {
		$selected_uid = explode('|',(string)t3lib_div::_GP('selected_uid'));
		foreach($selected_uid AS $key => $val) {
			if ($val==$position_uid) {
				return true;
			}
		}
		return false;
	}//end position_selected
	
	/**
	 * Cleans up User input in Search field.
	 *
	  */
	 	
	function make_clean($value) {
		$legal_chars = "%[^0-9a-zA-ZäöüÄÖÜß. ]%"; //allow letters, numbers & space
		$new_value = preg_replace($legal_chars,"",$value); //replace with ""
		return $new_value;
	}	

	/**
	 * Displays all of the content above in the browser window.
	 *
	 * @return	[type]		...
	 * @@return	void
	 */
	function printContent()	{
		echo $this->content;
	}//end printContent


} //end class


//checking for and including an extending-class file
   if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/civserv/res/class.tx_civserv_wizard_service_position_information.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/civserv/res/class.tx_civserv_wizard_service_position_information.php']);
   }


//Instantiating
$SOBE = t3lib_div::makeInstance('tx_civserv_wizard_service_position_information');
$SOBE->init();
$SOBE->main();
$SOBE->printContent();
?>