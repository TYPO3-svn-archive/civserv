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
 *   75: class tx_civserv_wizard_organisation_supervisor extends t3lib_SCbase
 *   92:     function init()
 *  306:     function main()
 *  398:     function getSupervisors($letter)
 *  452:     function employee_selected($employee_uid)
 *  469:     function printContent()
 *
 * TOTAL FUNCTIONS: 5
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */


	// DEFAULT initialization of a module
require_once ('conf.php');
require_once ($BACK_PATH.'init.php');
require_once ($BACK_PATH.'template.php');
require_once (PATH_t3lib.'class.t3lib_scbase.php');
require_once ($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/civserv/res/class.tx_civserv_mandant.php']);


	// Initialization of language file
include ('locallang_wizard.php');
$LANG->includeLLFile('EXT:res/locallang_wizard.php');



/**
* This is an abc-wizard for choosing an employee as a supervisor for an organization. Only one employee can be selected.
*/
class tx_civserv_wizard_organisation_supervisor extends t3lib_SCbase {

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

			// Is $P set? If not, read from URL. This is needed because otherwise
			// the p-array will be lost and no data could be written back to the
			// main window.
		if ($this->P['itemName']) {
			$this->pArr = array();
			$this->pArr = explode('|',$this->P['itemName']);
			$this->organisation_pid = $this->P['pid'];	// Gets parent id of organisation from p-array.
		} else {
			$this->pArr = array();
			$this->pArr[0] = t3lib_div::_GP('PItemName');
			$this->organisation_pid = t3lib_div::_GP('organisation_pid');	// Gets parent id of organisation from url.
		}
		$this->PItemName = "&PItemName=".$this->pArr[0];

			// In case the parent id of organisation is still not set, try to
			// get it out of the database.
		if (!intval($this->organisation_pid) > 0){
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				'pid',			 			// SELECT ...
				$this->P['table'],			// FROM ...
				'uid = '.$this->P['uid'],	// AND title LIKE "%blabla%"', // WHERE...
				'', 						// GROUP BY...
				'',   						// ORDER BY...
				'' 							// LIMIT to 10 rows, starting with number 5 (MySQL compat.)
			);
			$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
			$this->organisation_pid = $row['pid'];
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

			<title>'.$LANG->getLL('tx_civserv_wizard_organisation_supervisor.title').'</title>

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

				// Writes all elements given in options into main window. (In fact, only one element can be written back in this case.)
			function insertElements(table, type, fp, close, options)	{	//
				var cur_uid_arr;
				var cur_name_arr;
				var error_flag = 0;

					// Divides the options parameter into the two parameters
					// cur_selected_uid and cur_selected_name.
				string_divide_position = options.search("&selected_name=");
				cur_selected_uid = options.slice(0+14,string_divide_position);
				cur_selected_name = options.slice(string_divide_position+15);

				if (1=='.($this->pArr[0]&&!$this->pArr[1]&&!$this->pArr[2] ? 1 : 0).')	{
					 	//check, if positions are selected
					if (cur_selected_uid != \'\'){
						cur_uid_arr = cur_selected_uid.split("|");
						cur_name_arr = cur_selected_name.split("|");

							// Inserts all Elements
						for (j=0;j<cur_uid_arr.length;j++) {
							if (cur_uid_arr[j]!=0) {
								parent.window.opener.setFormValueFromBrowseWin("'.$this->pArr[0].'",fp?fp:table+"_"+cur_uid_arr[j],cur_name_arr[j]);
							} else {
								error_flag = 1;
							}
						}
						if (error_flag == 0) {
							parent.window.opener.focus();
							parent.close();
						} else {
							alert("'.$LANG->getLL('tx_civserv_wizard_organisation_supervisor.warning_msg_1').'");
						}
					} else {
						alert("'.$LANG->getLL('tx_civserv_wizard_organisation_supervisor.warning_msg_1').'");
					}
				} else {
					alert("'.$LANG->getLL('tx_civserv_wizard_organisation_supervisor.warning_msg_2').'");
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

				for(i=0;i<document.organisationform.selectedsupervisor.length;++i) {
					num_removed=0;

						// Removes all selectorbox entries of current letter from current options.
					for (j=0;j<cur_uid_arr.length-num_removed;j++) {
						if (cur_uid_arr[j-num_removed]==document.organisationform.selectedsupervisor.options[i].value) {
							removed = cur_uid_arr.splice(j-num_removed,1);
							removed = cur_name_arr.splice(j-num_removed,1);
							num_removed++;
						}
					}

						// Gets all selected items of current selectorbox.
					if(document.organisationform.selectedsupervisor.options[i].selected == true) {
						if (selected_uid=="") {
							selected_uid=document.organisationform.selectedsupervisor.options[i].value;
							selected_name=document.organisationform.selectedsupervisor.options[i].label;
						} else {
							selected_uid=selected_uid+"|"+document.organisationform.selectedsupervisor.options[i].value;
							selected_name=selected_name+"|"+document.organisationform.selectedsupervisor.options[i].label;
						}
					}
				}

				selected_uid = "&selected_uid=" + selected_uid;
				selected_name = "&selected_name=" + selected_name;
				options = selected_uid + selected_name;

				return options;
			}

				// Adds selected items (=options) and refreshes the
				// browser window.
			function add_options_refresh(letter,cur_selected_uid,cur_selected_name,script,PItemName,organisation_pid)	{	//
			searchitem = document.organisationform.searchitem.value
			if (document.organisationform.selectedsupervisor) {
					options = returnOptions(cur_selected_uid,cur_selected_name);
				} else {	// if no selectorbox is displayed at beginning
					options = "";
				}
				if (letter=="searchitem" && searchitem=="") {
					alert ("'.$LANG->getLL('all_wizards.search_warning').'");
				} else {
					jumpToUrl(script+"?letter="+letter+options+PItemName+organisation_pid+"&searchitem="+searchitem,this);
				}
			}

				// Writes all selected items back to main window and
				// closes the popup wizard.
			function save_and_quit()	{	//
				options = returnOptions(\''.(string)t3lib_div::_GP('selected_uid').'\',\''.(string)t3lib_div::_GP('selected_name').'\');
				insertElements(\'tx_civserv_employee\',\'db\',\'\',1,options);
				return false;
			}

			</script>
			<!--###POSTJSMARKER###-->
			</head>
		';
		parent::init();
	}//end init


	/**
	 * Contains all the logic of the wizard. Shows the abc and in a selectorbox underneath the
	 * employees beginning with the selected letter. The User can select one or more supervisor (=employee)
	 * for one organisation.
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
			<form name="organisationform" action="" method="post">

			<h3 class="bgColor5">'.$LANG->getLL('tx_civserv_wizard_organisation_supervisor.select_letter_text').':</h3>
			<table border="0" cellpadding="0" cellspacing="0" id="typo3-tree">
				<tr class="bgColor">
					<td nowrap="nowrap">
		';

		$script=basename(PATH_thisScript);

			// Displays the abc to select all employees beginning with
			// the selected letter.
		$this->content.='
			<a href="#" onclick="add_options_refresh(\'A\',\''.(string)t3lib_div::_GP('selected_uid').'\',\''.(string)t3lib_div::_GP('selected_name').'\',\''.$script.'\',\''.$this->PItemName.'\',\'&organisation_pid='.htmlspecialchars($this->organisation_pid).'\')">A</a>
			<a href="#" onclick="add_options_refresh(\'B\',\''.(string)t3lib_div::_GP('selected_uid').'\',\''.(string)t3lib_div::_GP('selected_name').'\',\''.$script.'\',\''.$this->PItemName.'\',\'&organisation_pid='.htmlspecialchars($this->organisation_pid).'\')">B</a>
			<a href="#" onclick="add_options_refresh(\'C\',\''.(string)t3lib_div::_GP('selected_uid').'\',\''.(string)t3lib_div::_GP('selected_name').'\',\''.$script.'\',\''.$this->PItemName.'\',\'&organisation_pid='.htmlspecialchars($this->organisation_pid).'\')">C</a>
			<a href="#" onclick="add_options_refresh(\'D\',\''.(string)t3lib_div::_GP('selected_uid').'\',\''.(string)t3lib_div::_GP('selected_name').'\',\''.$script.'\',\''.$this->PItemName.'\',\'&organisation_pid='.htmlspecialchars($this->organisation_pid).'\')">D</a>
			<a href="#" onclick="add_options_refresh(\'E\',\''.(string)t3lib_div::_GP('selected_uid').'\',\''.(string)t3lib_div::_GP('selected_name').'\',\''.$script.'\',\''.$this->PItemName.'\',\'&organisation_pid='.htmlspecialchars($this->organisation_pid).'\')">E</a>
			<a href="#" onclick="add_options_refresh(\'F\',\''.(string)t3lib_div::_GP('selected_uid').'\',\''.(string)t3lib_div::_GP('selected_name').'\',\''.$script.'\',\''.$this->PItemName.'\',\'&organisation_pid='.htmlspecialchars($this->organisation_pid).'\')">F</a>
			<a href="#" onclick="add_options_refresh(\'G\',\''.(string)t3lib_div::_GP('selected_uid').'\',\''.(string)t3lib_div::_GP('selected_name').'\',\''.$script.'\',\''.$this->PItemName.'\',\'&organisation_pid='.htmlspecialchars($this->organisation_pid).'\')">G</a>
			<a href="#" onclick="add_options_refresh(\'H\',\''.(string)t3lib_div::_GP('selected_uid').'\',\''.(string)t3lib_div::_GP('selected_name').'\',\''.$script.'\',\''.$this->PItemName.'\',\'&organisation_pid='.htmlspecialchars($this->organisation_pid).'\')">H</a>
			<a href="#" onclick="add_options_refresh(\'I\',\''.(string)t3lib_div::_GP('selected_uid').'\',\''.(string)t3lib_div::_GP('selected_name').'\',\''.$script.'\',\''.$this->PItemName.'\',\'&organisation_pid='.htmlspecialchars($this->organisation_pid).'\')">I</a>
			<a href="#" onclick="add_options_refresh(\'J\',\''.(string)t3lib_div::_GP('selected_uid').'\',\''.(string)t3lib_div::_GP('selected_name').'\',\''.$script.'\',\''.$this->PItemName.'\',\'&organisation_pid='.htmlspecialchars($this->organisation_pid).'\')">J</a>
			<a href="#" onclick="add_options_refresh(\'K\',\''.(string)t3lib_div::_GP('selected_uid').'\',\''.(string)t3lib_div::_GP('selected_name').'\',\''.$script.'\',\''.$this->PItemName.'\',\'&organisation_pid='.htmlspecialchars($this->organisation_pid).'\')">K</a>
			<a href="#" onclick="add_options_refresh(\'L\',\''.(string)t3lib_div::_GP('selected_uid').'\',\''.(string)t3lib_div::_GP('selected_name').'\',\''.$script.'\',\''.$this->PItemName.'\',\'&organisation_pid='.htmlspecialchars($this->organisation_pid).'\')">L</a>
			<a href="#" onclick="add_options_refresh(\'M\',\''.(string)t3lib_div::_GP('selected_uid').'\',\''.(string)t3lib_div::_GP('selected_name').'\',\''.$script.'\',\''.$this->PItemName.'\',\'&organisation_pid='.htmlspecialchars($this->organisation_pid).'\')">M</a>
			<a href="#" onclick="add_options_refresh(\'N\',\''.(string)t3lib_div::_GP('selected_uid').'\',\''.(string)t3lib_div::_GP('selected_name').'\',\''.$script.'\',\''.$this->PItemName.'\',\'&organisation_pid='.htmlspecialchars($this->organisation_pid).'\')">N</a>
			<a href="#" onclick="add_options_refresh(\'O\',\''.(string)t3lib_div::_GP('selected_uid').'\',\''.(string)t3lib_div::_GP('selected_name').'\',\''.$script.'\',\''.$this->PItemName.'\',\'&organisation_pid='.htmlspecialchars($this->organisation_pid).'\')">O</a>
			<a href="#" onclick="add_options_refresh(\'P\',\''.(string)t3lib_div::_GP('selected_uid').'\',\''.(string)t3lib_div::_GP('selected_name').'\',\''.$script.'\',\''.$this->PItemName.'\',\'&organisation_pid='.htmlspecialchars($this->organisation_pid).'\')">P</a>
			<a href="#" onclick="add_options_refresh(\'Q\',\''.(string)t3lib_div::_GP('selected_uid').'\',\''.(string)t3lib_div::_GP('selected_name').'\',\''.$script.'\',\''.$this->PItemName.'\',\'&organisation_pid='.htmlspecialchars($this->organisation_pid).'\')">Q</a>
			<a href="#" onclick="add_options_refresh(\'R\',\''.(string)t3lib_div::_GP('selected_uid').'\',\''.(string)t3lib_div::_GP('selected_name').'\',\''.$script.'\',\''.$this->PItemName.'\',\'&organisation_pid='.htmlspecialchars($this->organisation_pid).'\')">R</a>
			<a href="#" onclick="add_options_refresh(\'S\',\''.(string)t3lib_div::_GP('selected_uid').'\',\''.(string)t3lib_div::_GP('selected_name').'\',\''.$script.'\',\''.$this->PItemName.'\',\'&organisation_pid='.htmlspecialchars($this->organisation_pid).'\')">S</a>
			<a href="#" onclick="add_options_refresh(\'T\',\''.(string)t3lib_div::_GP('selected_uid').'\',\''.(string)t3lib_div::_GP('selected_name').'\',\''.$script.'\',\''.$this->PItemName.'\',\'&organisation_pid='.htmlspecialchars($this->organisation_pid).'\')">T</a>
			<a href="#" onclick="add_options_refresh(\'U\',\''.(string)t3lib_div::_GP('selected_uid').'\',\''.(string)t3lib_div::_GP('selected_name').'\',\''.$script.'\',\''.$this->PItemName.'\',\'&organisation_pid='.htmlspecialchars($this->organisation_pid).'\')">U</a>
			<a href="#" onclick="add_options_refresh(\'V\',\''.(string)t3lib_div::_GP('selected_uid').'\',\''.(string)t3lib_div::_GP('selected_name').'\',\''.$script.'\',\''.$this->PItemName.'\',\'&organisation_pid='.htmlspecialchars($this->organisation_pid).'\')">V</a>
			<a href="#" onclick="add_options_refresh(\'W\',\''.(string)t3lib_div::_GP('selected_uid').'\',\''.(string)t3lib_div::_GP('selected_name').'\',\''.$script.'\',\''.$this->PItemName.'\',\'&organisation_pid='.htmlspecialchars($this->organisation_pid).'\')">W</a>
			<a href="#" onclick="add_options_refresh(\'X\',\''.(string)t3lib_div::_GP('selected_uid').'\',\''.(string)t3lib_div::_GP('selected_name').'\',\''.$script.'\',\''.$this->PItemName.'\',\'&organisation_pid='.htmlspecialchars($this->organisation_pid).'\')">X</a>
			<a href="#" onclick="add_options_refresh(\'Y\',\''.(string)t3lib_div::_GP('selected_uid').'\',\''.(string)t3lib_div::_GP('selected_name').'\',\''.$script.'\',\''.$this->PItemName.'\',\'&organisation_pid='.htmlspecialchars($this->organisation_pid).'\')">Y</a>
			<a href="#" onclick="add_options_refresh(\'Z\',\''.(string)t3lib_div::_GP('selected_uid').'\',\''.(string)t3lib_div::_GP('selected_name').'\',\''.$script.'\',\''.$this->PItemName.'\',\'&organisation_pid='.htmlspecialchars($this->organisation_pid).'\')">Z</a>
			<a href="#" onclick="add_options_refresh(\'other\',\''.(string)t3lib_div::_GP('selected_uid').'\',\''.(string)t3lib_div::_GP('selected_name').'\',\''.$script.'\',\''.$this->PItemName.'\',\'&organisation_pid='.htmlspecialchars($this->organisation_pid).'\')">'.$LANG->getLL('all_abc_wizards.other').'</a>
		';

		$this->content.='
					</td>
					<td>
						<input type="text" size="20" name="searchitem" id="searchitem" value="'.$this->searchitem.'"> <br />
						<a href="#" onclick="add_options_refresh(\'searchitem\',\''.(string)t3lib_div::_GP('selected_uid').'\',\''.(string)t3lib_div::_GP('selected_name').'\',\''.$script.'\',\''.$this->PItemName.'\',\'&organisation_pid='.htmlspecialchars($this->organisation_pid).'\')">'.$LANG->getLL('all_abc_wizards.search').'</a>
					</td>
				</tr>
			</table>
		';

			// Stores selected letter in variable.
		$letter = (string)t3lib_div::_GP('letter');

			// Only display second selectorbox if a letter is selected.
		if ($letter=='') {
			// do nothing
		} else {
			if ($letter != "other" and $letter != "searchitem") {
				$this->content.='<h3 class="bgColor5">'.$LANG->getLL('tx_civserv_wizard_organisation_supervisor.select_supervisor_text').''.$letter.':</h3>';
			} else {
				$this->content.='<h3 class="bgColor5">'.$LANG->getLL('tx_civserv_wizard_organisation_supervisor.select_supervisor_text_no_abc').':</h3>';
			}
			$this->content.='<table border="0" cellpadding="0" cellspacing="0" id="typo3-tree">
				<tr class="bgColor">
					<td nowrap="nowrap">
			';

				// Gets all employees beginning with the chosen letter.
			$this->content.=$this->getSupervisors($letter);

				// Displays a OK-Button to save the selected supervisor.
			$this->content.='
					</td>
				</tr>
			</table>

			<input type="button" name="Return" value="'.$LANG->getLL('tx_civserv_wizard_organisation_supervisor.OK_Button').'" onclick="return save_and_quit();">
		';
		}

			// Displays a Cancel-Button at the end of the Page to exit the wizard without changing anything.
		$this->content.='
		<input type="button" name="cancel" value="'.$LANG->getLL('tx_civserv_wizard_organisation_supervisor.Cancel_Button').'" onclick="parent.close();">
		</form>
		</body>
		</html>
		';
	}//end main


	/**
	 * Generates a selector box with the employees locally available for this install.
	 *
	 * @param	[type]		$letter: the selected letter to show employees beginning with this letter in the selectorbox.
	 * @return	[type]		...
	 * @@return	string		Selector box with employees.
	 */
	function getSupervisors($letter)	{
		global $LANG;
		$GLOBALS['TYPO3_DB']->debugOutput = TRUE;
		$this->searchitem = (string)t3lib_div::_GP('searchitem');
		$this->searchitem = $this->make_clean($this->searchitem);


		if ($letter != "other" and $letter != "searchitem") {
				// Gets all employees with the selected letter at the
				// beginning out of the database. Checks also if employees aren't hidden or
				// deleted.
			$this->res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				'*',			 							// SELECT ...
				'tx_civserv_employee',						// FROM ...
				'upper(left(em_name,1))=\''.$letter.'\' AND deleted=0 AND hidden=0',	// AND title LIKE "%blabla%"', // WHERE...
				'', 										// GROUP BY...
				'em_name',   								// ORDER BY...
				'' 											// LIMIT to 10 rows, starting with number 5 (MySQL compat.)
				);
			} 
		if ($letter == "other") {
				// Gets all employees which don't begin with a letter
				// out of the database. Checks also if employees aren't hidden or
				// deleted.
			$this->res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				'*',			 							// SELECT ...
				'tx_civserv_employee',						// FROM ...
				'!(upper(left(em_name,1))=\'A\') AND !(upper(left(em_name,1))=\'B\') AND !(upper(left(em_name,1))=\'C\') AND !(upper(left(em_name,1))=\'D\') AND !(upper(left(em_name,1))=\'E\') AND !(upper(left(em_name,1))=\'F\') AND !(upper(left(em_name,1))=\'G\') AND !(upper(left(em_name,1))=\'H\') AND !(upper(left(em_name,1))=\'I\') AND !(upper(left(em_name,1))=\'J\') AND !(upper(left(em_name,1))=\'K\') AND !(upper(left(em_name,1))=\'L\') AND !(upper(left(em_name,1))=\'M\') AND !(upper(left(em_name,1))=\'N\') AND !(upper(left(em_name,1))=\'O\') AND !(upper(left(em_name,1))=\'P\') AND !(upper(left(em_name,1))=\'Q\') AND !(upper(left(em_name,1))=\'R\') AND !(upper(left(em_name,1))=\'S\') AND !(upper(left(em_name,1))=\'T\') AND !(upper(left(em_name,1))=\'U\') AND !(upper(left(em_name,1))=\'V\') AND !(upper(left(em_name,1))=\'W\') AND !(upper(left(em_name,1))=\'X\') AND !(upper(left(em_name,1))=\'Y\') AND !(upper(left(em_name,1))=\'Z\') AND deleted=0 AND hidden=0',	// AND title LIKE "%blabla%"', // WHERE...
				'', 										// GROUP BY...
				'em_name',   								// ORDER BY...
				'' 											// LIMIT to 10 rows, starting with number 5 (MySQL compat.)
				);
			}
		if ($letter == "searchitem" AND $this->searchitem != "") {
				$this->res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				'*',			 							// SELECT ...
				'tx_civserv_employee',						// FROM ...
				'em_name like \'%'.$this->searchitem.'%\' AND deleted=0 AND hidden=0',	// AND title LIKE "%blabla%"', // WHERE...
				'', 										// GROUP BY...
				'em_name',   								// ORDER BY...
				'' 											// LIMIT to 10 rows, starting with number 5 (MySQL compat.)
				);
			} 
		
		$menuItems=array();

		$menuItems[]='<option label="dummy" value="0">[ '.$LANG->getLL('tx_civserv_wizard_organisation_supervisor.supervisor_dummy').' ]</option>';

			// Removes all employees from other mandants so that only
			// the employees of the actual mandant are displayed in the
			// selectorbox.
		$mandant_obj = t3lib_div::makeInstance('tx_civserv_mandant');
		$mandant = $mandant_obj->get_mandant($this->organisation_pid);
		if ($this->res) {
			while ($employee = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($this->res)) {
					// Checks if the uid is already selected.
				if ($mandant_obj->get_mandant($employee['pid'])==$mandant){
					if ($this->employee_selected($employee[uid])) {
						$selVal = 'selected="selected"';
					} else {
						$selVal = '';
					}
					$menuItems[]='<option label="'.htmlspecialchars($employee[em_name]).', '.htmlspecialchars($employee[em_firstname]).'" value="'.htmlspecialchars($employee[uid]).'"'.$selVal.'">'.htmlspecialchars($employee[em_name]).', '.htmlspecialchars($employee[em_firstname]).'</option>';
				}
			}
		}
		$PItemName = "&PItemName=".$this->pArr[0];

			// Displays the second selectorbox with the employees.
		return '<select name="selectedsupervisor" size="1">'.implode('',$menuItems).'</select>
			   ';
	}//end getSupervisors


	/**
	 * Checks if an employee is already selected by the user.
	 *
	 * @param	[type]		$employee_uid [string]: the uid of an employee
	 * @return	[type]		...
	 * @@return	boolean
	 */
	function employee_selected($employee_uid) {
		$selected_uid = explode('|',(string)t3lib_div::_GP('selected_uid'));
		foreach($selected_uid AS $key => $val) {
			if ($val==$employee_uid) {
				return true;
			}
		}
		return false;
	}//end employee_selected

	
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


} //end class


//checking for and including an extending-class file
   if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/civserv/res/class.tx_civserv_wizard_organisation_supervisor.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/civserv/res/class.tx_civserv_wizard_organisation_supervisor.php']);
   }


//Instantiating
$SOBE = t3lib_div::makeInstance('tx_civserv_wizard_organisation_supervisor');
$SOBE->init();
$SOBE->main();
$SOBE->printContent();
?>