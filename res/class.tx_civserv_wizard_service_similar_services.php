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
 *   76: class tx_civserv_wizard_service_similar_services extends t3lib_SCbase
 *   94:     function init()
 *  331:     function main()
 *  392:     function getServiceCategories()
 *  436:     function getServices()
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
class tx_civserv_wizard_service_similar_services extends t3lib_SCbase {

	var $content;		// whole HTML-Content to be displayed in Wizard
	var $res;			// results from SQL-Queries
	var $P;				// Array given from the parent window in backend
	var $bparams;		// reads the value of the "caller-field" from backend, needed to adress and change this field
	var $mode;			// mode for group box, "db" in this case
	var $pArr;			// contains parts of the $bparams
	var $PItemName;
	var $category_uid;
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

			// Get the uid of the service-category from the url. When the wizard-window ist created, no uid is set in
			// the url, so no category is selected in the first selectorbox.
		$this->category_uid = (string)t3lib_div::_GP('category_uid');

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

			// Gets the service folder uid for a given mandant.
		$this->service_folder_uid =	(string)t3lib_div::_GP('service_folder_uid');
		if ($this->service_folder_uid == "") {
			$mandant_obj = t3lib_div::makeInstance('tx_civserv_mandant');
			$community_id = $mandant_obj->get_mandant($this->service_pid);
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				'cm_service_folder_uid',			// SELECT ...
				'tx_civserv_conf_mandant',			// FROM ...
				'cm_community_id = '.$community_id,	// AND title LIKE "%blabla%"', // WHERE...
				'', 								// GROUP BY...
				'',   								// ORDER BY...
				'' 									// LIMIT to 10 rows, starting with number 5 (MySQL compat.)
			);
			$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
			$this->service_folder_uid = $row['cm_service_folder_uid'];
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

			<title>'.$LANG->getLL('tx_civserv_wizard_service_similar_services.title').'</title>

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

				if (1=='.($this->pArr[0]&&!$this->pArr[1]&&!$this->pArr[2] ? 1 : 0).')	{
					 	//check, if positions are selected
					if (cur_selected_uid != \'\'){
						cur_uid_arr = cur_selected_uid.split("|");
						cur_name_arr = cur_selected_name.split("|");

							// inserts all Elements
						for (j=0;j<cur_uid_arr.length;j++) {
							parent.window.opener.setFormValueFromBrowseWin("'.$this->pArr[0].'",fp?fp:table+"_"+cur_uid_arr[j],cur_name_arr[j]);
						}

						parent.window.opener.focus();
						parent.close();
					} else {
						alert("'.$LANG->getLL('tx_civserv_wizard_service_similar_services.warning_msg_1').'");
					}
				} else {
					alert("'.$LANG->getLL('tx_civserv_wizard_service_similar_services.warning_msg_2').'");
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

				if (document.similarservices.selectedServices) {
					for(i=0;i<document.similarservices.selectedServices.length;++i) {
						num_removed=0;

							// Removes all selectorbox entries of current category_uid from current options.
						for (j=0;j<cur_uid_arr.length-num_removed;j++) {
							if (cur_uid_arr[j-num_removed]==document.similarservices.selectedServices.options[i].value) {
								removed = cur_uid_arr.splice(j-num_removed,1);
								removed = cur_name_arr.splice(j-num_removed,1);
								num_removed++;
							}
						}

							// Gets all selected items of current selectorbox.
						if(document.similarservices.selectedServices.options[i].selected == true) {
							if (selected_uid=="") {
								selected_uid=document.similarservices.selectedServices.options[i].value;
								selected_name=document.similarservices.selectedServices.options[i].label;
							} else {
								selected_uid=selected_uid+"|"+document.similarservices.selectedServices.options[i].value;
								selected_name=selected_name+"|"+document.similarservices.selectedServices.options[i].label;
							}
						}
					}
				} else { // when no category is selected in first selectorbox
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

				return options;
			}

				// Adds selected items (=options) and refreshes the
				// browser window.
			function add_options_refresh(category_uid,mode,cur_selected_uid,cur_selected_name,script,PItemName,service_pid,service_folder_uid)	{	//
				searchitem = document.similarservices.searchitem.value;
				options = returnOptions(cur_selected_uid,cur_selected_name);
				if (mode=="search" && searchitem=="") {
					alert ("'.$LANG->getLL('all_wizards.search_warning').'");
				} else {
				jumpToUrl(script+"?category_uid="+category_uid+options+PItemName+service_pid+service_folder_uid+"&searchitem="+searchitem+"&mode="+mode,this);
				}
			}

				// Writes all selected items back to main window and
				// closes the popup wizard.
			function save_and_quit()	{	//
				options = returnOptions(\''.(string)t3lib_div::_GP('selected_uid').'\',\''.(string)t3lib_div::_GP('selected_name').'\');
				insertElements(\'tx_civserv_service\',\'db\',\'\',1,options);
				return false;
			}

				// Changes the service category by setting category_uid to the uid selected in the selectorbox.
				// If no category is selected (so the dummy entry is selected), category_uid is empty.
			function changeCategory()	{	//
					//selection of ModelService-title and UID
				for(i=0;i<document.similarservices.selectedCategories.length;++i) {
					if(document.similarservices.selectedCategories.options[i].selected == true) {
						category_uid = ServiceUID=document.similarservices.selectedCategories.options[i].value;
					}
				}
				if (category_uid == "0") {
					category_uid="";
				}
				add_options_refresh(category_uid,\'normal\',\''.(string)t3lib_div::_GP('selected_uid').'\',\''.(string)t3lib_div::_GP('selected_name').'\',\''.$script.'\',\''.$this->PItemName.'\',\'&service_pid='.htmlspecialchars($this->service_pid).'\',\'&service_folder_uid='.htmlspecialchars($this->service_folder_uid).'\');
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
			<form name="similarservices" action="" method="post">

			<h3 class="bgColor5">'.$LANG->getLL('tx_civserv_wizard_service_similar_services.select_category_text').':</h3>
			<table border="0" cellpadding="0" cellspacing="0" id="typo3-tree">
				<tr class="bgColor">
					<td nowrap="nowrap">
		';

		$script=basename(PATH_thisScript);

			// Displays all service categories in the first selectorbox.
		$this->content.=$this->getServiceCategories();

		$this->content.='
					</td>
					<td>
						<input type="text" size="20" name="searchitem" id="searchitem" value="'.$this->searchitem.'"> <br />
						<a href="#" onclick="add_options_refresh(\''.$this->service_folder_uid.'\',\'search\',\''.(string)t3lib_div::_GP('selected_uid').'\',\''.(string)t3lib_div::_GP('selected_name').'\',\''.$script.'\',\''.$this->PItemName.'\',\'&service_pid='.htmlspecialchars($this->service_pid).'\',\'&service_folder_uid='.htmlspecialchars($this->service_folder_uid).'\');">'.$LANG->getLL('all_category_wizards.search').'</a>
					</td>
				</tr>
			</table>
		';

			// Only display second selectorbox if a category is selected.
		if ($this->category_uid=='') {
			// do nothing
		} else {
			$this->content.='
			<h3 class="bgColor5">'.$LANG->getLL('tx_civserv_wizard_service_similar_services.select_service_text').''.$category_uid.':</h3>
			<table border="0" cellpadding="0" cellspacing="0" id="typo3-tree">
				<tr class="bgColor">
					<td nowrap="nowrap">
			';

				// Gets all services beginning with the chosen category_uid and displays them in a selectorbox.
			$this->content.=$this->getServices();

				// Displays a OK-Button to save the selected services.
			$this->content.='
					</td>
				</tr>
			</table>

			<input type="button" name="Return" value="'.$LANG->getLL('tx_civserv_wizard_service_similar_services.OK_Button').'" onclick="return save_and_quit();">
		';
		}

			// Displays a Cancel-Button at the end of the Page to exit the wizard without changing anything.
		$this->content.='
		<input type="button" name="cancel" value="'.$LANG->getLL('tx_civserv_wizard_service_similar_services.Cancel_Button').'" onclick="parent.close();">
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
	function getServiceCategories()	{
		global $LANG;
		//$GLOBALS['TYPO3_DB']->debugOutput = TRUE;

			// Gets all categories which aren't hidden or deleted out of the database.
		$this->res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'*',																	// SELECT ...
			'pages',																// FROM ...
			'pid = '.intval($this->service_folder_uid).' AND deleted=0 AND hidden=0',	// AND title LIKE "%blabla%"', // WHERE...
			'', 																	// GROUP BY...
			'title',   																// ORDER BY...
			'' 																		// LIMIT to 10 rows, starting with number 5 (MySQL compat.)
			);

		$menuItems=array();
		$menuItems[0] = '<option label="[ '.$LANG->getLL('tx_civserv_wizard_service_similar_services.service_category_dummy').' ]" value="0">[ '.$LANG->getLL('tx_civserv_wizard_service_similar_services.service_category_dummy').' ]</option>';

		while ($categories = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($this->res)) {
				// Checks if the uid is already selected.
			if ((string)$categories[uid] == $this->category_uid) {
				$selVal = 'selected="selected"';
			} else {
				$selVal = '';
			}
			$menuItems[]='<option label="'.htmlspecialchars($categories[title]).'" value="'.htmlspecialchars($categories[uid]).'"'.$selVal.'>'.htmlspecialchars($categories[title]).'</option>';
		}

		$PItemName = "&PItemName=".$this->pArr[0];

			// Displays the first selectorbox with the categories.
		return '<select name="selectedCategories" onchange="changeCategory()">'.implode('',$menuItems).'</select>
			   ';
	}//end getServiceCategories


	/**
	 * Generates a selector box with the services from the selected category.
	 *
	 * @return	[type]		...
	 * @@return	string		Selector box with services.
	 */
	function getServices()	{
		global $LANG;
		$GLOBALS['TYPO3_DB']->debugOutput = TRUE;
		$this->searchitem = (string)t3lib_div::_GP('searchitem');
		$mode = (string)t3lib_div::_GP('mode');
		$this->searchitem = $this->make_clean($this->searchitem);

			// Gets all services with the selected category_uid out of the database.
			// Checks also if positions aren't hidden or deleted.

			// get all the child-folders to the chosen category (only 1 level recursion)
		if ($mode == "normal") {
		$this->res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'uid',			 				// SELECT ...
			'pages',						// FROM ...
			'pid='.$this->category_uid.' AND deleted=0 AND hidden=0',	// AND title LIKE "%blabla%"', // WHERE...
			'', 										// GROUP BY...
			'',   										// ORDER BY...
			'' 											// LIMIT to 10 rows, starting with number 5 (MySQL compat.)
			);
		$pidList=array();
		$pidList[0]=$this->category_uid;
		while ($uids = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($this->res)) {
			$pidList[]=$uids[uid];
		}

		$liste=implode(',',$pidList);

		$this->res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'*',			 							// SELECT ...
			'tx_civserv_service',						// FROM ...
			'pid in('.$liste.') AND deleted=0 AND hidden=0',	// AND title LIKE "%blabla%"', // WHERE...
			'', 										// GROUP BY...
			'sv_name',   								// ORDER BY...
			'' 											// LIMIT to 10 rows, starting with number 5 (MySQL compat.)
			);
		} 
		if ($this->searchitem != "" AND $mode == "search") {
		
		$fliste = array();
			
		$this->res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'uid',			 							// SELECT ...
			'pages',									// FROM ...
			'pid = '.$this->service_folder_uid.' AND deleted=0 AND hidden=0',			// AND title LIKE "%blabla%"', // WHERE...
			'', 										// GROUP BY...
			'',   										// ORDER BY...
			'' 											// LIMIT to 10 rows, starting with number 5 (MySQL compat.)
			);
		
		$fliste = $this->getlist($this->res, $fliste);
		$fliste = implode(',',$fliste);
		
		$this->res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'*',			 							// SELECT ...
			'tx_civserv_service',						// FROM ...
			'pid in('.$fliste.') AND sv_name like \'%'.$this->searchitem.'%\' AND deleted=0 AND hidden=0',	// AND title LIKE "%blabla%"', // WHERE...
			'', 										// GROUP BY...
			'sv_name',   								// ORDER BY...
			'' 											// LIMIT to 10 rows, starting with number 5 (MySQL compat.)
			);
		}
		$menuItems=array();

		while ($services = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($this->res)) {
				// Checks if the uid is already selected.
			if ($this->service_selected($services[uid])) {
				$selVal = 'selected="selected"';
			} else {
				$selVal = '';
			}
			$menuItems[]='<option label="'.htmlspecialchars($services[sv_name]).'" value="'.htmlspecialchars($services[uid]).'"'.$selVal.'">'.htmlspecialchars($services[sv_name]).'</option>';
		}

		$PItemName = "&PItemName=".$this->pArr[0];

			// Displays the second selectorbox with the services.
		return '<select name="selectedServices" size="10" multiple="multiple">'.implode('',$menuItems).'</select>
			   ';
	}//end getServices


	/**
	 * Checks if a service is already selected by the user.
	 *
	 * @param	[type]		$service_uid [string]: the uid of a service
	 * @return	[type]		...
	 * @@return	boolean
	 */
	function service_selected($service_uid) {
		$selected_uid = explode('|',(string)t3lib_div::_GP('selected_uid'));
		foreach($selected_uid AS $key => $val) {
			if ($val==$service_uid) {
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
				'pid = '.$row[0].' AND deleted=0 AND hidden=0',// AND title LIKE "%blabla%"', // WHERE...
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
   if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/civserv/res/class.tx_civserv_wizard_service_similar_services.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/civserv/res/class.tx_civserv_wizard_service_similar_services.php']);
   }


//Instantiating
$SOBE = t3lib_div::makeInstance('tx_civserv_wizard_service_similar_services');
$SOBE->init();
$SOBE->main();
$SOBE->printContent();
?>