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
 *   76: class tx_civserv_wizard_employee_position_room extends t3lib_SCbase
 *   94:     function init()
 *  331:     function main()
 *  392:     function getBuilding()
 *  436:     function getFloors()
 
 *  477:     function service_selected($service_uid)
 *  494:     function printContent()
 *
 * TOTAL FUNCTIONS: 6
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */


	// DEFAULT initialization of a module
require_once ('conf.php');
require_once ($BACK_PATH.'init.php');
require_once ($BACK_PATH.'template.php');
require_once (PATH_t3lib.'class.t3lib_scbase.php');
#require_once (PATH_tslib.'class.tslib_pibase.php');

require_once ($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/civserv/res/class.tx_civserv_mandant.php']);


	// Initialization of language file
include ('locallang_wizard.php');
$LANG->includeLLFile('EXT:res/locallang_wizard.php');



/**
* This is a wizard for choosing similar services for a service. Multiple services can be selected.
*/
class tx_civserv_wizard_employee_position_room extends t3lib_SCbase {

	var $content;		// whole HTML-Content to be displayed in Wizard
	var $res;			// results from SQL-Queries
	var $P;				// Array given from the parent window in backend
	var $bparams;		// reads the value of the "caller-field" from backend, needed to adress and change this field
	var $mode;			// mode for group box, "db" in this case
	var $pArr;			// contains parts of the $bparams
	var $PItemName;
	var $letter;
	var $building_uid;
	var $floor_uid;
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
			$this->employee_pid = $this->P['pid'];	// Gets parent id of service from p-array.
		} else {
			//??
			$this->pArr = array();
			$this->pArr[0] = t3lib_div::_GP('PItemName');
			$this->employee_pid = t3lib_div::_GP('employee_pid');	// Gets parent id of service from url.
		}
		$this->PItemName = "&PItemName=".$this->pArr[0];

			// Get the uid of the building from the url. When the wizard-window ist created, no uid is set in
			// the url, so no category is selected in the first selectorbox.
			// we absolutely need this!!!!
		$this->building_uid = (string)t3lib_div::_GP('building_uid');
		$this->letter = (string)t3lib_div::_GP('letter');
		
		//wird ein paar zeilen später initialisiert
		#$this->building_pid = (string)t3lib_div::_GP('building_pid');

			// In case the parent id of service is still not set, try to
			// get it out of the database. The employee_pid is important because with it the uid of the mandant can be retrievied.
		if (!intval($this->employee_pid) > 0){
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				'pid',			 			// SELECT ...
				$this->P['table'],			// FROM ...
				'uid = '.$this->P['uid'],	// AND title LIKE "%blabla%"', // WHERE...
				'', 						// GROUP BY...
				'',   						// ORDER BY...
				'' 							// LIMIT to 10 rows, starting with number 5 (MySQL compat.)
			);
			$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
			$this->employee_pid = $row['pid'];
		}

			// Gets the building_pid for a given mandant.
		$this->building_pid =	(string)t3lib_div::_GP('building_folder_uid');
		if ($this->building_pid == "") {
			$mandant_obj = t3lib_div::makeInstance('tx_civserv_mandant');
			$community_id = $mandant_obj->get_mandant($this->employee_pid);
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				'cm_building_folder_uid',			// SELECT ...
				'tx_civserv_conf_mandant',			// FROM ...
				'cm_community_id = '.$community_id,	// AND title LIKE "%blabla%"', // WHERE...
				'', 								// GROUP BY...
				'',   								// ORDER BY...
				'' 									// LIMIT to 10 rows, starting with number 5 (MySQL compat.)
			);
			$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
			$this->building_pid = $row['cm_building_folder_uid'];
		}

		$formFieldName = 'data['.$this->pArr[0].']['.$this->pArr[1].']['.$this->pArr[2].']';

		    //get charset
        $charset = $GLOBALS['LANG']->charSet ? $GLOBALS['LANG']->charSet : 'iso-8859-1';
        
			// Draw the header.
		$this->content.='
			<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
			 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
			<?xml version="1.0" encoding="'.$charset.'"?>
			<?xml-stylesheet href="#internalStyle" type="text/css"?>

			<html>
			<head>
			<!-- TYPO3 Script ID: typo3conf/ext/civserv/xlass.tx_civserv_wizard_employee_position_room.php -->
			<meta http-equiv="Content-Type" content="text/html; charset='.$charset.'" />
			<meta name="GENERATOR" content="TYPO3 3.6.2, http://typo3.com, &#169; Kasper Sk&#229;rh&#248;j 1998-2004, extensions are copyright of their respective owners." />

			<title>'.$LANG->getLL('tx_civserv_wizard_employee_position_room.title').'</title>

			<link rel="stylesheet" type="text/css" href="stylesheet_wizard.css" />
		';

				// JavaScript
		$this->content.='
			<script type="text/javascript">

			script_ended = 0;
			function jumpToUrl(URL)	{	//
				document.location = URL;
			}
	// This JavaScript is primarily for RTE/Link. jumpToUrl is used in the other cases as well...
			var elRef="";
			var targetDoc="";

				// Writes all Elements given in options into main window.
			function insertElements(table, type, fp, close, options)	{	//
				var cur_uid_arr;
				var cur_name_arr;

					// Divides the options parameter into the two parameters
					// cur_selected_uid and cur_selected_name.
				string_divide_position = options.search("&selected_name=");
				cur_selected_uid = options.slice(0+14,string_divide_position);
				cur_selected_name = options.slice(string_divide_position+15);
				if (1=='.($this->pArr[0]&&!$this->pArr[1]&&!$this->pArr[2] ? 1 : 0).'){
					 	//check, if positions are selected
					if (cur_selected_uid != \'\'){
						cur_uid_arr = cur_selected_uid.split("|");
						cur_name_arr = cur_selected_name.split("|");

							// inserts all Elements
						for (j=0;j<cur_uid_arr.length;j++) {
							//hier gehts nicht weiter:
							parent.window.opener.setFormValueFromBrowseWin("'.$this->pArr[0].'",fp?fp:table+"_"+cur_uid_arr[j],cur_name_arr[j]);
						}
						//Baustelle????
						parent.window.opener.focus();
						parent.close();
					} else {
						alert("'.$LANG->getLL('tx_civserv_wizard_employee_position_room.warning_msg_1').'");
					}
				} else {
					alert("'.$LANG->getLL('tx_civserv_wizard_employee_position_room.warning_msg_2').'");
					if (close)	{
						parent.window.opener.focus();
						parent.close();
					}
				}
				return false;
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

				if (document.emposroom.selectedRooms) {
					//alert("we haf a rom");
					for(i=0;i<document.emposroom.selectedRooms.length;++i) {
						num_removed=0;

							// Removes all selectorbox entries of current building_uid from current options.
						for (j=0;j<cur_uid_arr.length-num_removed;j++) {
							if (cur_uid_arr[j-num_removed]==document.emposroom.selectedRooms.options[i].value) {
								removed = cur_uid_arr.splice(j-num_removed,1);
								removed = cur_name_arr.splice(j-num_removed,1);
								num_removed++;
							}
						}

							// Gets all selected items of current selectorbox.
						if(document.emposroom.selectedRooms.options[i].selected == true){
							if (selected_uid=="") {
								selected_uid=document.emposroom.selectedRooms.options[i].value;
								selected_name=document.emposroom.selectedRooms.options[i].label;
							} else {
								selected_uid=selected_uid+"|"+document.emposroom.selectedRooms.options[i].value;
								selected_name=selected_name+"|"+document.emposroom.selectedRooms.options[i].label;
							}
							//alert("selected_uid: "+selected_uid);
						}
					}
				} else { // when no category is selected in first selectorbox
					//alert("what?"); //immer wenn raum oder etage noch ungesetzt
				}

					// adds selected items to the list of all selected items
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
				//alert("options: "+options);
				return options;
			}
			
				// Adds selected items (=options) and refreshes the
				// browser window.
			function add_options_refresh(letter,cur_selected_uid,cur_selected_name,script,PItemName,employee_pid,building_pid)	{	//
				searchitem = document.emposroom.searchitem.value
				if (document.emposroom.selectedBuilding) {
					options = returnOptions(cur_selected_uid,cur_selected_name);
				} else {	// if no selectorbox is displayed at beginning
					options = "";
				}
				if (letter=="search" && searchitem=="") {
					alert ("'.$LANG->getLL('all_wizards.search_warning').'");
				} else {
					//hier?
					jumpToUrl(script+"?letter="+letter+options+PItemName+employee_pid+building_pid+"&searchitem="+searchitem+"&letter="+letter+"&caller=optionsrefresh_simple",this);
				}
			}



				// Adds selected items (=options) and refreshes the
				// browser window.
			function add_options_refresh_buildings(building_uid,mode,cur_selected_uid,cur_selected_name,script,PItemName,letter,employee_pid,building_pid)	{	//
				searchitem = document.emposroom.searchitem.value;
				options = returnOptions(cur_selected_uid,cur_selected_name);
				if (mode=="search" && searchitem=="") {
					alert ("'.$LANG->getLL('all_wizards.search_warning').'");
				} else {
					//alert(building_uid);
					jumpToUrl(script+"?building_uid="+building_uid+options+PItemName+employee_pid+building_pid+"&searchitem="+searchitem+"&mode="+mode+"&letter="+letter+"&caller=optionsrefresh_buildings",this);
				}
			}
			
			
				// Adds selected items (=options) and refreshes the
				// browser window.
			function add_options_refresh_floors(floor_uid,mode,cur_selected_uid,cur_selected_name,script,PItemName,letter,building_uid,employee_pid,building_pid)	{	//
				searchitem = document.emposroom.searchitem.value;
				options = returnOptions(cur_selected_uid,cur_selected_name);
				if (mode=="search" && searchitem=="") {
					alert ("'.$LANG->getLL('all_wizards.search_warning').'");
				} else {
					jumpToUrl(script+"?floor_uid="+floor_uid+options+PItemName+letter+building_uid+employee_pid+building_pid+"&searchitem="+searchitem+"&mode="+mode+"&caller=optionsrefresh_floors",this);
				}
			}


				// Writes all selected items back to main window and
				// closes the popup wizard.
			function save_and_quit()	{	//
				options = returnOptions(\''.(string)t3lib_div::_GP('selected_uid').'\',\''.(string)t3lib_div::_GP('selected_name').'\');
				insertElements(\'tx_civserv_room\',\'db\',\'\',1,options);
				return false;
			}

				// Changes the building by setting building_uid to the uid selected in the selectorbox.
				// If no building is selected (so the dummy entry is selected), building_uid is empty.
			function changeBuilding()	{	//
					//selection of UID and....
				for(i=0;i<document.emposroom.selectedBuilding.length;++i) {
					if(document.emposroom.selectedBuilding.options[i].selected == true) {
						building_uid = BuildingUID=document.emposroom.selectedBuilding.options[i].value;
					}
				}
				if (building_uid == "0") {
					building_uid="";
				}
				//alert(building_uid);
				add_options_refresh_buildings(building_uid,\'normal\',\''.(string)t3lib_div::_GP('selected_uid').'\',\''.(string)t3lib_div::_GP('selected_name').'\',\''.$script.'\',\''.$this->PItemName.'\',\'&letter='.htmlspecialchars($this->letter).'\',\'&employee_pid='.htmlspecialchars($this->employee_pid).'\',\'&building_pid='.htmlspecialchars($this->building_pid).'\');
			}

				// Changes the floor by setting floor_uid to the uid selected in the selectorbox.
				// If no floor is selected (so the dummy entry is selected), floor_uid is empty.
			function changeFloor()	{	//
					//selection of UID and.....
				for(i=0;i<document.emposroom.selectedFloor.length;++i) {
					if(document.emposroom.selectedFloor.options[i].selected == true) {
						floor_uid = FloorUID=document.emposroom.selectedFloor.options[i].value;
					}
				}
				if (floor_uid == "0") {
					floor_uid="";
				}
				add_options_refresh_floors(floor_uid,\'normal\',\''.(string)t3lib_div::_GP('selected_uid').'\',\''.(string)t3lib_div::_GP('selected_name').'\',\''.$script.'\',\''.$this->PItemName.'\',\'&letter='.htmlspecialchars($this->letter).'\',\'&building_uid='.htmlspecialchars($this->building_uid).'\',\'&employee_pid='.htmlspecialchars($this->employee_pid).'\',\'&building_pid='.htmlspecialchars($this->building_pid).'\');
			}
			</script>
			<!--###POSTJSMARKER###-->
			</head>
		';
		parent::init();
	}//end init


	/**
	 * Contains all the logic of the wizard. Shows the service categories in a first selectorbox and all services
	 * of a selected category in a second selectorbox underneath. The User can select one or more similar services
	 * for one service.
	 *
	 * @return	[type]		Returns the HTML-Body with the two selectorboxes.
	 * @@return	void
	 */
	function main()	{
		global $LANG;
		$this->searchitem = (string)t3lib_div::_GP('searchitem');
			// Draw the body.
		$this->content.='
			<body scroll="auto" id="typo3-browse-links-php">
			<form name="emposroom" action="" method="post">
			<h3 class="bgColor5">'.$LANG->getLL('tx_civserv_wizard_employee_position_room.select_letter_text').':</h3>
			<table border="0" cellpadding="0" cellspacing="0" id="typo3-tree">
				<tr class="bgColor">
					<td nowrap="nowrap">
		';

		$script=basename(PATH_thisScript);

			// Displays the abc to select all positions beginning with
			// the selected letter.
		$this->content.='
			<a href="#" onclick="add_options_refresh(\'A\',\''.(string)t3lib_div::_GP('selected_uid').'\',\''.(string)t3lib_div::_GP('selected_name').'\',\''.$script.'\',\''.$this->PItemName.'\',\'&employee_pid='.htmlspecialchars($this->employee_pid).'\',\'&building_pid='.htmlspecialchars($this->building_pid).'\')">A</a>
			<a href="#" onclick="add_options_refresh(\'B\',\''.(string)t3lib_div::_GP('selected_uid').'\',\''.(string)t3lib_div::_GP('selected_name').'\',\''.$script.'\',\''.$this->PItemName.'\',\'&employee_pid='.htmlspecialchars($this->employee_pid).'\',\'&building_pid='.htmlspecialchars($this->building_pid).'\')">B</a>
			<a href="#" onclick="add_options_refresh(\'C\',\''.(string)t3lib_div::_GP('selected_uid').'\',\''.(string)t3lib_div::_GP('selected_name').'\',\''.$script.'\',\''.$this->PItemName.'\',\'&employee_pid='.htmlspecialchars($this->employee_pid).'\',\'&building_pid='.htmlspecialchars($this->building_pid).'\')">C</a>
			<a href="#" onclick="add_options_refresh(\'D\',\''.(string)t3lib_div::_GP('selected_uid').'\',\''.(string)t3lib_div::_GP('selected_name').'\',\''.$script.'\',\''.$this->PItemName.'\',\'&employee_pid='.htmlspecialchars($this->employee_pid).'\',\'&building_pid='.htmlspecialchars($this->building_pid).'\')">D</a>
			<a href="#" onclick="add_options_refresh(\'E\',\''.(string)t3lib_div::_GP('selected_uid').'\',\''.(string)t3lib_div::_GP('selected_name').'\',\''.$script.'\',\''.$this->PItemName.'\',\'&employee_pid='.htmlspecialchars($this->employee_pid).'\',\'&building_pid='.htmlspecialchars($this->building_pid).'\')">E</a>
			<a href="#" onclick="add_options_refresh(\'F\',\''.(string)t3lib_div::_GP('selected_uid').'\',\''.(string)t3lib_div::_GP('selected_name').'\',\''.$script.'\',\''.$this->PItemName.'\',\'&employee_pid='.htmlspecialchars($this->employee_pid).'\',\'&building_pid='.htmlspecialchars($this->building_pid).'\')">F</a>
			<a href="#" onclick="add_options_refresh(\'G\',\''.(string)t3lib_div::_GP('selected_uid').'\',\''.(string)t3lib_div::_GP('selected_name').'\',\''.$script.'\',\''.$this->PItemName.'\',\'&employee_pid='.htmlspecialchars($this->employee_pid).'\',\'&building_pid='.htmlspecialchars($this->building_pid).'\')">G</a>
			<a href="#" onclick="add_options_refresh(\'H\',\''.(string)t3lib_div::_GP('selected_uid').'\',\''.(string)t3lib_div::_GP('selected_name').'\',\''.$script.'\',\''.$this->PItemName.'\',\'&employee_pid='.htmlspecialchars($this->employee_pid).'\',\'&building_pid='.htmlspecialchars($this->building_pid).'\')">H</a>
			<a href="#" onclick="add_options_refresh(\'I\',\''.(string)t3lib_div::_GP('selected_uid').'\',\''.(string)t3lib_div::_GP('selected_name').'\',\''.$script.'\',\''.$this->PItemName.'\',\'&employee_pid='.htmlspecialchars($this->employee_pid).'\',\'&building_pid='.htmlspecialchars($this->building_pid).'\')">I</a>
			<a href="#" onclick="add_options_refresh(\'J\',\''.(string)t3lib_div::_GP('selected_uid').'\',\''.(string)t3lib_div::_GP('selected_name').'\',\''.$script.'\',\''.$this->PItemName.'\',\'&employee_pid='.htmlspecialchars($this->employee_pid).'\',\'&building_pid='.htmlspecialchars($this->building_pid).'\')">J</a>
			<a href="#" onclick="add_options_refresh(\'K\',\''.(string)t3lib_div::_GP('selected_uid').'\',\''.(string)t3lib_div::_GP('selected_name').'\',\''.$script.'\',\''.$this->PItemName.'\',\'&employee_pid='.htmlspecialchars($this->employee_pid).'\',\'&building_pid='.htmlspecialchars($this->building_pid).'\')">K</a>
			<a href="#" onclick="add_options_refresh(\'L\',\''.(string)t3lib_div::_GP('selected_uid').'\',\''.(string)t3lib_div::_GP('selected_name').'\',\''.$script.'\',\''.$this->PItemName.'\',\'&employee_pid='.htmlspecialchars($this->employee_pid).'\',\'&building_pid='.htmlspecialchars($this->building_pid).'\')">L</a>
			<a href="#" onclick="add_options_refresh(\'M\',\''.(string)t3lib_div::_GP('selected_uid').'\',\''.(string)t3lib_div::_GP('selected_name').'\',\''.$script.'\',\''.$this->PItemName.'\',\'&employee_pid='.htmlspecialchars($this->employee_pid).'\',\'&building_pid='.htmlspecialchars($this->building_pid).'\')">M</a>
			<a href="#" onclick="add_options_refresh(\'N\',\''.(string)t3lib_div::_GP('selected_uid').'\',\''.(string)t3lib_div::_GP('selected_name').'\',\''.$script.'\',\''.$this->PItemName.'\',\'&employee_pid='.htmlspecialchars($this->employee_pid).'\',\'&building_pid='.htmlspecialchars($this->building_pid).'\')">N</a>
			<a href="#" onclick="add_options_refresh(\'O\',\''.(string)t3lib_div::_GP('selected_uid').'\',\''.(string)t3lib_div::_GP('selected_name').'\',\''.$script.'\',\''.$this->PItemName.'\',\'&employee_pid='.htmlspecialchars($this->employee_pid).'\',\'&building_pid='.htmlspecialchars($this->building_pid).'\')">O</a>
			<a href="#" onclick="add_options_refresh(\'P\',\''.(string)t3lib_div::_GP('selected_uid').'\',\''.(string)t3lib_div::_GP('selected_name').'\',\''.$script.'\',\''.$this->PItemName.'\',\'&employee_pid='.htmlspecialchars($this->employee_pid).'\',\'&building_pid='.htmlspecialchars($this->building_pid).'\')">P</a>
			<a href="#" onclick="add_options_refresh(\'Q\',\''.(string)t3lib_div::_GP('selected_uid').'\',\''.(string)t3lib_div::_GP('selected_name').'\',\''.$script.'\',\''.$this->PItemName.'\',\'&employee_pid='.htmlspecialchars($this->employee_pid).'\',\'&building_pid='.htmlspecialchars($this->building_pid).'\')">Q</a>
			<a href="#" onclick="add_options_refresh(\'R\',\''.(string)t3lib_div::_GP('selected_uid').'\',\''.(string)t3lib_div::_GP('selected_name').'\',\''.$script.'\',\''.$this->PItemName.'\',\'&employee_pid='.htmlspecialchars($this->employee_pid).'\',\'&building_pid='.htmlspecialchars($this->building_pid).'\')">R</a>
			<a href="#" onclick="add_options_refresh(\'S\',\''.(string)t3lib_div::_GP('selected_uid').'\',\''.(string)t3lib_div::_GP('selected_name').'\',\''.$script.'\',\''.$this->PItemName.'\',\'&employee_pid='.htmlspecialchars($this->employee_pid).'\',\'&building_pid='.htmlspecialchars($this->building_pid).'\')">S</a>
			<a href="#" onclick="add_options_refresh(\'T\',\''.(string)t3lib_div::_GP('selected_uid').'\',\''.(string)t3lib_div::_GP('selected_name').'\',\''.$script.'\',\''.$this->PItemName.'\',\'&employee_pid='.htmlspecialchars($this->employee_pid).'\',\'&building_pid='.htmlspecialchars($this->building_pid).'\')">T</a>
			<a href="#" onclick="add_options_refresh(\'U\',\''.(string)t3lib_div::_GP('selected_uid').'\',\''.(string)t3lib_div::_GP('selected_name').'\',\''.$script.'\',\''.$this->PItemName.'\',\'&employee_pid='.htmlspecialchars($this->employee_pid).'\',\'&building_pid='.htmlspecialchars($this->building_pid).'\')">U</a>
			<a href="#" onclick="add_options_refresh(\'V\',\''.(string)t3lib_div::_GP('selected_uid').'\',\''.(string)t3lib_div::_GP('selected_name').'\',\''.$script.'\',\''.$this->PItemName.'\',\'&employee_pid='.htmlspecialchars($this->employee_pid).'\',\'&building_pid='.htmlspecialchars($this->building_pid).'\')">V</a>
			<a href="#" onclick="add_options_refresh(\'W\',\''.(string)t3lib_div::_GP('selected_uid').'\',\''.(string)t3lib_div::_GP('selected_name').'\',\''.$script.'\',\''.$this->PItemName.'\',\'&employee_pid='.htmlspecialchars($this->employee_pid).'\',\'&building_pid='.htmlspecialchars($this->building_pid).'\')">W</a>
			<a href="#" onclick="add_options_refresh(\'X\',\''.(string)t3lib_div::_GP('selected_uid').'\',\''.(string)t3lib_div::_GP('selected_name').'\',\''.$script.'\',\''.$this->PItemName.'\',\'&employee_pid='.htmlspecialchars($this->employee_pid).'\',\'&building_pid='.htmlspecialchars($this->building_pid).'\')">X</a>
			<a href="#" onclick="add_options_refresh(\'Y\',\''.(string)t3lib_div::_GP('selected_uid').'\',\''.(string)t3lib_div::_GP('selected_name').'\',\''.$script.'\',\''.$this->PItemName.'\',\'&employee_pid='.htmlspecialchars($this->employee_pid).'\',\'&building_pid='.htmlspecialchars($this->building_pid).'\')">Y</a>
			<a href="#" onclick="add_options_refresh(\'Z\',\''.(string)t3lib_div::_GP('selected_uid').'\',\''.(string)t3lib_div::_GP('selected_name').'\',\''.$script.'\',\''.$this->PItemName.'\',\'&employee_pid='.htmlspecialchars($this->employee_pid).'\',\'&building_pid='.htmlspecialchars($this->building_pid).'\')">Z</a>
			<a href="#" onclick="add_options_refresh(\'other\',\''.(string)t3lib_div::_GP('selected_uid').'\',\''.(string)t3lib_div::_GP('selected_name').'\',\''.$script.'\',\''.$this->PItemName.'\',\'&employee_pid='.htmlspecialchars($this->employee_pid).'\',\'&building_pid='.htmlspecialchars($this->building_pid).'\')">'.$LANG->getLL('all_abc_wizards.other').'</a>
		';
		//add search field:
		$this->content.='
					</td>
					<td>
						<input type="text" size="20" name="searchitem" id="searchitem" value="'.$this->searchitem.'"><br />'.
						//the following link is for the search-field!!!
						'<a href="#" onclick="add_options_refresh(\'search\',\''.(string)t3lib_div::_GP('selected_uid').'\',\''.(string)t3lib_div::_GP('selected_name').'\',\''.$script.'\',\''.$this->PItemName.'\',\'&employee_pid='.htmlspecialchars($this->employee_pid).'\',\'&building_pid='.htmlspecialchars($this->building_pid).'\')">'.$LANG->getLL('all_abc_wizards.search').'</a>
					</td>
				</tr>
			</table>
		';
		// Stores selected letter in variable.
		$this->letter = (string)t3lib_div::_GP('letter');

		//1: Only display building_selection if a letter is selected.
		if ($this->letter=='') {
			// do nothing
		} else {
			if ($this->letter != "other" and $this->letter != "search") {
				$this->content.='<h3 class="bgColor5">'.$LANG->getLL('tx_civserv_wizard_employee_position_room.select_building_text').''.$this->letter.':</h3>';
			} else {
				$this->content.='<h3 class="bgColor5">'.$LANG->getLL('tx_civserv_wizard_employee_position_room.select_building_text_no_abc').':</h3>';
			}
			$this->content.='<table border="0" cellpadding="0" cellspacing="0" id="typo3-tree">
				<tr class="bgColor">
					<td nowrap="nowrap">
			';
			
			// Displays all buildings beginning with the chosen letter in the first selectorbox.
			$this->content.=$this->getBuilding();
		}

			//2: Only display floor-selectorbox if a building is selected.
		if ($this->building_uid=='') {
			// do nothing
		} else {
			$this->content.='
			<h3 class="bgColor5">'.$LANG->getLL('tx_civserv_wizard_employee_position_room.select_floor_text').':</h3>
			<table border="0" cellpadding="0" cellspacing="0" id="typo3-tree">
				<tr class="bgColor">
					<td nowrap="nowrap">
			';

				// Gets all floors for the chosen building and displays them in a selectorbox.
			$this->content.=$this->getFloors();
			//this one is needed!!
			$this->floor_uid = (string)t3lib_div::_GP('floor_uid');
		}
		
		
			//3: Only display room-selectorbox if a floor is selected.
		if ($this->floor_uid=='') {
			// do nothing
		} else {
			$this->content.='
						<br />
						<h3 class="bgColor5">'.$LANG->getLL('tx_civserv_wizard_employee_position_room.select_room_text').':</h3>
						<table border="0" cellpadding="0" cellspacing="0" id="typo3-tree">
							<tr class="bgColor">
								<td nowrap="nowrap">
						';

				// Gets all services beginning with the chosen building_uid and displays them in a selectorbox.
			$this->content.=$this->getRoom();
			//try?
			$this->floor_uid = (string)t3lib_div::_GP('floor_uid');

				// Displays a OK-Button to save the selected services.
			$this->content.='
							</td>
						</tr>
					</table>
					<br /><br />
					<input type="button" name="Return" value="'.$LANG->getLL('tx_civserv_wizard_employee_position_room.OK_Button').'" onclick="return save_and_quit();">
					';
		}

			// Displays a Cancel-Button at the end of the Page to exit the wizard without changing anything.
		$this->content.='
		<input type="button" name="cancel" value="'.$LANG->getLL('tx_civserv_wizard_employee_position_room.Cancel_Button').'" onclick="parent.close();">
		</form>
		</body>
		</html>
		';
	}//end main


	/**
	 * Generates a selector box with the service categories locally available for this install.
	 *
	 * @return	[type]		...
	 * @@return	string		Selector box with service categories.
	 */
	function getBuilding(){
		global $LANG;
		$this->searchitem = (string)t3lib_div::_GP('searchitem');
		$this->searchitem = $this->make_clean($this->searchitem);
		
		if ($this->letter != "other" and $this->letter != "search") {
			// Gets all categories which aren't hidden or deleted out of the database.
			#$query_string='pid = '.intval($this->building_pid).' AND upper(left(bl_name,1))=\''.$this->letter.'\' AND deleted=0 AND hidden=0';
			$this->res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				'*',																	// SELECT ...
				'tx_civserv_building',																// FROM ...
				'pid = '.intval($this->building_pid).' AND upper(left(bl_name,1))=\''.$this->letter.'\' AND deleted=0 AND hidden=0',	// AND title LIKE "%blabla%"', // WHERE...
				'', 																	// GROUP BY...
				'bl_name',   																// ORDER BY...
				'' 																		// LIMIT to 10 rows, starting with number 5 (MySQL compat.)
			);
		}
		if ($this->letter == "other") {
			// Gets all positions which don't begin with a letter
			// out of the database. Checks also if positions aren't hidden or
			// deleted.
			$query_string='pid = '.intval($this->building_pid).' AND!(upper(left(bl_name,1))=\'A\') AND !(upper(left(bl_name,1))=\'B\') AND !(upper(left(bl_name,1))=\'C\') AND !(upper(left(bl_name,1))=\'D\') AND !(upper(left(bl_name,1))=\'E\') AND !(upper(left(bl_name,1))=\'F\') AND !(upper(left(bl_name,1))=\'G\') AND !(upper(left(bl_name,1))=\'H\') AND !(upper(left(bl_name,1))=\'I\') AND !(upper(left(bl_name,1))=\'J\') AND !(upper(left(bl_name,1))=\'K\') AND !(upper(left(bl_name,1))=\'L\') AND !(upper(left(bl_name,1))=\'M\') AND !(upper(left(bl_name,1))=\'N\') AND !(upper(left(bl_name,1))=\'O\') AND !(upper(left(bl_name,1))=\'P\') AND !(upper(left(bl_name,1))=\'Q\') AND !(upper(left(bl_name,1))=\'R\') AND !(upper(left(bl_name,1))=\'S\') AND !(upper(left(bl_name,1))=\'T\') AND !(upper(left(bl_name,1))=\'U\') AND !(upper(left(bl_name,1))=\'V\') AND !(upper(left(bl_name,1))=\'W\') AND !(upper(left(bl_name,1))=\'X\') AND !(upper(left(bl_name,1))=\'Y\') AND !(upper(left(bl_name,1))=\'Z\') AND deleted=0 AND hidden=0';
			$this->res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				'*',			 							// SELECT ...
				'tx_civserv_building',						// FROM ...
				'pid = '.intval($this->building_pid).' AND!(upper(left(bl_name,1))=\'A\') AND !(upper(left(bl_name,1))=\'B\') AND !(upper(left(bl_name,1))=\'C\') AND !(upper(left(bl_name,1))=\'D\') AND !(upper(left(bl_name,1))=\'E\') AND !(upper(left(bl_name,1))=\'F\') AND !(upper(left(bl_name,1))=\'G\') AND !(upper(left(bl_name,1))=\'H\') AND !(upper(left(bl_name,1))=\'I\') AND !(upper(left(bl_name,1))=\'J\') AND !(upper(left(bl_name,1))=\'K\') AND !(upper(left(bl_name,1))=\'L\') AND !(upper(left(bl_name,1))=\'M\') AND !(upper(left(bl_name,1))=\'N\') AND !(upper(left(bl_name,1))=\'O\') AND !(upper(left(bl_name,1))=\'P\') AND !(upper(left(bl_name,1))=\'Q\') AND !(upper(left(bl_name,1))=\'R\') AND !(upper(left(bl_name,1))=\'S\') AND !(upper(left(bl_name,1))=\'T\') AND !(upper(left(bl_name,1))=\'U\') AND !(upper(left(bl_name,1))=\'V\') AND !(upper(left(bl_name,1))=\'W\') AND !(upper(left(bl_name,1))=\'X\') AND !(upper(left(bl_name,1))=\'Y\') AND !(upper(left(bl_name,1))=\'Z\') AND deleted=0 AND hidden=0',
				'', 										// GROUP BY...
				'bl_name',   								// ORDER BY...
				'' 											// LIMIT to 10 rows, starting with number 5 (MySQL compat.)
			);
		} 
		if ($this->letter == "search" AND $this->searchitem != "") {
			$this->res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'*',			 							// SELECT ...
			'tx_civserv_building',						// FROM ...
			'pid = '.intval($this->building_pid).' AND bl_name like \'%'.$this->searchitem.'%\' AND deleted=0 AND hidden=0',	// AND title LIKE "%blabla%"', // WHERE...
			'', 										// GROUP BY...
			'bl_name',   								// ORDER BY...
			'' 											// LIMIT to 10 rows, starting with number 5 (MySQL compat.)
			);
		} 
		$menuItems=array();
		$menuItems[0] = '<option label="[ '.$LANG->getLL('tx_civserv_wizard_employee_position_room.building_dummy').' ]" value="0">[ '.$LANG->getLL('tx_civserv_wizard_employee_position_room.building_dummy').' ]</option>';

		while ($buildings = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($this->res)) {
				// Checks if the uid is already selected.
			/*	
			if ((string)$buildings[uid] == $this->building_uid) {
				$selVal = 'selected="selected"';
			} else {
				$selVal = '';
			}
			*/
			if ($this->building_selected($buildings[uid])) {
				$selVal = 'selected="selected"';
			} else {
				$selVal = '';
			}
			$menuItems[]='<option label="'.htmlspecialchars($buildings[bl_name]).'" value="'.htmlspecialchars($buildings[uid]).'"'.$selVal.'>'.htmlspecialchars($buildings[bl_name]).'</option>';
		}

		$PItemName = "&PItemName=".$this->pArr[0];

			// Displays the first selectorbox with the categories.
		return '<select name="selectedBuilding" onchange="changeBuilding()">'.implode('',$menuItems).'</select><br /><br />';
	}//end getBuilding
	
	
	/**
	 * Generates a selector box with the floors from the selected category.
	 *
	 * @return	[type]		...
	 * @@return	string		Selector box with floors.
	 */
	function getFloors()	{
		global $LANG;
		$this->searchitem = (string)t3lib_div::_GP('searchitem');
		$mode = (string)t3lib_div::_GP('mode');
		$this->searchitem = $this->make_clean($this->searchitem);

			// Gets all floors with the selected building_uid out of the database.
			// Checks also if buildings aren't hidden or deleted.

			// get all the child-folders to the chosen category (only 1 level recursion)
		if ($mode == "normal") {
			$this->res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				'uid_foreign as uid',			 				// SELECT ...
				'tx_civserv_building_bl_floor_mm',						// FROM ...
				'uid_local='.$this->building_uid.' AND deleted=0 AND hidden=0',	// AND title LIKE "%blabla%"', // WHERE...
				'', 										// GROUP BY...
				'',   										// ORDER BY...
				'' 											// LIMIT to 10 rows, starting with number 5 (MySQL compat.)
				);
			$floorList=array();
			$floorList[0]=$this->building_uid;
			while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($this->res)) {
				$floorList[]=$row[uid];
			}
			$liste=implode(',',$floorList);
			$this->res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				'*',			 							// SELECT ...
				'tx_civserv_floor',						// FROM ...
				'uid in('.$liste.') AND !deleted AND !hidden',	// AND title LIKE "%blabla%"', // WHERE...
				'', 										// GROUP BY...
				'fl_descr',   								// ORDER BY...
				'' 											// LIMIT to 10 rows, starting with number 5 (MySQL compat.)
			);
		} 
		if ($this->searchitem != "" AND $mode == "search") {
			$fliste = array();
			$this->res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				'uid',			 							// SELECT ...
				'pages',									// FROM ...
				'pid = '.$this->building_pid.' AND deleted=0 AND hidden=0',			// AND title LIKE "%blabla%"', // WHERE...
				'', 										// GROUP BY...
				'',   										// ORDER BY...
				'' 											// LIMIT to 10 rows, starting with number 5 (MySQL compat.)
				);
			
			$fliste = $this->getlist($this->res, $fliste);
			$fliste = implode(',',$fliste);
			
			$this->res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				'*',			 							// SELECT ...
				'tx_civserv_floor',						// FROM ...
				'pid in('.$fliste.') AND sv_name like \'%'.$this->searchitem.'%\' AND !deleted AND !hidden',	// AND title LIKE "%blabla%"', // WHERE...
				'', 										// GROUP BY...
				'sv_name',   								// ORDER BY...
				'' 											// LIMIT to 10 rows, starting with number 5 (MySQL compat.)
			);
		}
		$menuItems=array();
		$menuItems[0] = '<option label="[ '.$LANG->getLL('tx_civserv_wizard_employee_position_room.floor_dummy').' ]" value="0">[ '.$LANG->getLL('tx_civserv_wizard_employee_position_room.floor_dummy').' ]</option>';		while ($floors = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($this->res)) {
				// Checks if the uid is already selected.
			if ($this->floor_selected($floors[uid])) {
				$selVal = 'selected="selected"';
			} else {
				$selVal = '';
			}
			$menuItems[]='<option label="'.htmlspecialchars($floors[fl_descr]).'" value="'.htmlspecialchars($floors[uid]).'"'.$selVal.'>'.htmlspecialchars($floors[fl_descr]).'</option>';
		}
		$PItemName = "&PItemName=".$this->pArr[0];

			// Displays the second selectorbox with the floors.
		return '<select name="selectedFloor" 		onchange="changeFloor()">		'.implode('',$menuItems).'</select><br /><br />';
	}//end getFloors

	
	
	
	


	/**
	 * Generates a selector box with the services from the selected category.
	 *
	 * @return	[type]		...
	 * @@return	string		Selector box with services.
	 */
	function getRoom()	{
		global $LANG;
		$this->searchitem = (string)t3lib_div::_GP('searchitem');
		$mode = (string)t3lib_div::_GP('mode');
		$this->searchitem = $this->make_clean($this->searchitem);

			// Gets all services with the selected building_uid out of the database.
			// Checks also if positions aren't hidden or deleted.

			// get all the child-folders to the chosen category (only 1 level recursion)
		if ($mode == "normal") {
		$this->res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'uid',			 				// SELECT ...
			'tx_civserv_building_bl_floor_mm',						// FROM ...
			'uid_local='.$this->building_uid.' AND uid_foreign='.$this->floor_uid.' AND deleted=0 AND hidden=0',	// AND title LIKE "%blabla%"', // WHERE...
			'', 										// GROUP BY...
			'',   										// ORDER BY...
			'' 											// LIMIT to 10 rows, starting with number 5 (MySQL compat.)
			);
		$rblfl_list=array();
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($this->res)) {
			$rblfl_list[]=$row[uid];
		}
		$liste=implode(',',$rblfl_list);

		$this->res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'*',			 							// SELECT ...
			'tx_civserv_room',						// FROM ...
			'rbf_building_bl_floor in('.$liste.') AND deleted=0 AND hidden=0',	// AND title LIKE "%blabla%"', // WHERE...
			'', 										// GROUP BY...
			'ro_name',   								// ORDER BY...
			'' 											// LIMIT to 10 rows, starting with number 5 (MySQL compat.)
			);
		} 
		if ($this->searchitem != "" AND $mode == "search") {
			$fliste = array();
			$this->res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				'uid',			 							// SELECT ...
				'pages',									// FROM ...
				'pid = '.$this->building_pid.' AND deleted=0 AND hidden=0',			// AND title LIKE "%blabla%"', // WHERE...
				'', 										// GROUP BY...
				'',   										// ORDER BY...
				'' 											// LIMIT to 10 rows, starting with number 5 (MySQL compat.)
				);
			$fliste = $this->getlist($this->res, $fliste);
			$fliste = implode(',',$fliste);
			$this->res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				'*',			 							// SELECT ...
				'tx_civserv_service',						// FROM ...
				'pid in('.$fliste.') AND sv_name like \'%'.$this->searchitem.'%\' AND !deleted AND !hidden',	// AND title LIKE "%blabla%"', // WHERE...
				'', 										// GROUP BY...
				'sv_name',   								// ORDER BY...
				'' 											// LIMIT to 10 rows, starting with number 5 (MySQL compat.)
			);
		}
		$menuItems=array();
		$menuItems[0] = '<option label="[ '.$LANG->getLL('tx_civserv_wizard_employee_position_room.room_dummy').' ]" value="0">[ '.$LANG->getLL('tx_civserv_wizard_employee_position_room.room_dummy').' ]</option>';
		while ($rooms = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($this->res)) {
				// Checks if the uid is already selected.
			if ($this->item_selected($rooms[uid])) {
				$selVal = 'selected="selected"';
			} else {
				$selVal = '';
			}
			$menuItems[]='<option label="'.htmlspecialchars($rooms[ro_name]).'" value="'.htmlspecialchars($rooms[uid]).'"'.$selVal.'>'.htmlspecialchars($rooms[ro_name]).'</option>';
		}
		$PItemName = "&PItemName=".$this->pArr[0];

			// Displays the second selectorbox with the services.
		return '<select name="selectedRooms">'.implode('',$menuItems).'</select>';
	}//end getRoom


	/**
	 * Checks if a service is already selected by the user.
	 *
	 * @param	[type]		$service_uid [string]: the uid of a service
	 * @return	[type]		...
	 * @@return	boolean
	 */
	function item_selected($item_uid) {
		$selected_uid = explode('|',(string)t3lib_div::_GP('selected_uid'));
		foreach($selected_uid AS $key => $val) {
			if ($val==$item_uid) {
				return true;
			}
		}
		return false;
	}//end service_selected
	
	function floor_selected($item_uid) {
		$selected_uid = explode('|',(string)t3lib_div::_GP('floor_uid'));
		foreach($selected_uid AS $key => $val) {
			if ($val==$item_uid) {
				return true;
			}
		}
		return false;
	}//end service_selected
	
	function building_selected($item_uid) {
		$selected_uid = explode('|',(string)t3lib_div::_GP('building_uid'));
		foreach($selected_uid AS $key => $val) {
			if ($val==$item_uid) {
				return true;
			}
		}
		return false;
	}//end service_selected
	

	
	/**
	 * Gets pid by recursion.
	 *
	  */

	function getlist($result, $fliste){
	global $fliste;
	if($result){
			while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
			$fliste[]=$row[0];
			$res2 = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				'uid',			 							// SELECT ...
				'pages',									// FROM ...
				'pid = '.$row[0].' AND !deleted AND !hidden',// AND title LIKE "%blabla%"', // WHERE...
				'', 										// GROUP BY...
				'',   										// ORDER BY...
				'' 											// LIMIT to 10 rows, starting with number 5 (MySQL compat.)
				);
			$this->getlist($res2, $liste);
			}
		}
		return $fliste;
	}
	
	
	/**
	 * Cleans up User input in Search field.
	 *
	  */
	 	
	function make_clean($value) {
		$legal_chars = "%[^0-9a-zA-ZäöüÄÖÜß ]%"; //allow letters, numbers & space
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

	function js_alert($msg) {
				echo "<script type=\"text/javascript\">alert('".$msg."');</script>";
	}


} //end class


//checking for and including an extending-class file
   if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/civserv/res/class.tx_civserv_wizard_employee_position_room.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/civserv/res/class.tx_civserv_wizard_employee_position_room.php']);
   }


//Instantiating
$SOBE = t3lib_div::makeInstance('tx_civserv_wizard_employee_position_room');
$SOBE->init();
$SOBE->main();
$SOBE->printContent();
?>
