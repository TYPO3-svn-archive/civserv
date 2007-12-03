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
* $Id: class.tx_civserv_wizard_service_form_category.php,v 1.5 2006/02/27 15:14:06 bkohorst Exp $
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
 *   76: class tx_civserv_wizard_service_form_category extends t3lib_SCbase
 *   94:     function init()
 *  331:     function main()
 *  392:     function getFormCategories()
 *  436:     function getForms()
 *  477:     function form_selected($service_uid)
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
* This is a wizard for choosing similar forms for a service. Multiple forms can be selected.
*/
class tx_civserv_wizard_service_form_category extends t3lib_SCbase {

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
#		debug($this->P, 'tx_civserv_wizard_service_form_category->init: P');

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
#		debug($formFieldName, 'wizard_form_cat->init: $formFieldName');

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
			<!-- TYPO3 Script ID: typo3conf/ext/civserv/xlass.tx_civserv_wizard_employee_em_position.php -->
			<meta http-equiv="Content-Type" content="text/html; charset='.$charset.'" />
			<meta name="GENERATOR" content="TYPO3 3.6.2, http://typo3.com, &#169; Kasper Sk&#229;rh&#248;j 1998-2004, extensions are copyright of their respective owners." />

			<title>'.$LANG->getLL('tx_civserv_wizard_service_form_category.title').'</title>

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
						alert("'.$LANG->getLL('tx_civserv_wizard_service_form_category.warning_msg_1').'");
					}
				} else {
					alert("'.$LANG->getLL('tx_civserv_wizard_service_form_category.warning_msg_2').'");
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

				if (document.serviceformsbycategory.selectedforms) {
					for(i=0;i<document.serviceformsbycategory.selectedforms.length;++i) {
						num_removed=0;

							// Removes all selectorbox entries of current category_uid from current options.
						for (j=0;j<cur_uid_arr.length-num_removed;j++) {
							if (cur_uid_arr[j-num_removed]==document.serviceformsbycategory.selectedforms.options[i].value) {
								removed = cur_uid_arr.splice(j-num_removed,1);
								removed = cur_name_arr.splice(j-num_removed,1);
								num_removed++;
							}
						}

							// Gets all selected items of current selectorbox.
						if(document.serviceformsbycategory.selectedforms.options[i].selected == true) {
							if (selected_uid=="") {
								selected_uid=document.serviceformsbycategory.selectedforms.options[i].value;
								selected_name=document.serviceformsbycategory.selectedforms.options[i].label;
							} else {
								selected_uid=selected_uid+"|"+document.serviceformsbycategory.selectedforms.options[i].value;
								selected_name=selected_name+"|"+document.serviceformsbycategory.selectedforms.options[i].label;
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
				searchitem = document.serviceformsbycategory.searchitem.value;
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
				insertElements(\'tx_civserv_form\',\'db\',\'\',1,options);
				return false;
			}

				// Changes the service category by setting category_uid to the uid selected in the selectorbox.
				// If no category is selected (so the dummy entry is selected), category_uid is empty.
			function changeCategory()	{	//
					//selection of Category and UID
				for(i=0;i<document.serviceformsbycategory.selectedCategories.length;++i) {
					if(document.serviceformsbycategory.selectedCategories.options[i].selected == true) {
						category_uid = ServiceUID=document.serviceformsbycategory.selectedCategories.options[i].value;
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
	 * Contains all the logic of the wizard. Shows the service categories in a first selectorbox and all forms
	 * of a selected category in a second selectorbox underneath. The User can select one or more similar forms
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
			<form name="serviceformsbycategory" action="" method="post">

			<h3 class="bgColor5">'.$LANG->getLL('tx_civserv_wizard_service_form_category.select_category_text').':</h3>
			<table border="0" cellpadding="0" cellspacing="0" id="typo3-tree">
				<tr class="bgColor">
					<td nowrap="nowrap">
		';

		$script=basename(PATH_thisScript);

			// Displays all service categories in the first selectorbox.
		$this->content.=$this->getFormCategories();

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
			<h3 class="bgColor5">'.$LANG->getLL('tx_civserv_wizard_service_form_category.select_form_text').''.$category_uid.':</h3>
			<table border="0" cellpadding="0" cellspacing="0" id="typo3-tree">
				<tr class="bgColor">
					<td nowrap="nowrap">
			';

				// Gets all forms beginning with the chosen category_uid and displays them in a selectorbox.
			$this->content.=$this->getForms();

				// Displays a OK-Button to save the selected forms.
			$this->content.='
					</td>
				</tr>
			</table>

			<input type="button" name="Return" value="'.$LANG->getLL('tx_civserv_wizard_service_form_category.OK_Button').'" onclick="return save_and_quit();">
		';
		}

			// Displays a Cancel-Button at the end of the Page to exit the wizard without changing anything.
		$this->content.='
		<input type="button" name="cancel" value="'.$LANG->getLL('tx_civserv_wizard_service_form_category.Cancel_Button').'" onclick="parent.close();">
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
	function getFormCategories()	{
		global $LANG;
#		$GLOBALS['TYPO3_DB']->debugOutput = TRUE;

			// Gets all categories which aren't hidden or deleted out of the database.
		$this->res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'*',																	// SELECT ...
			'tx_civserv_category',																// FROM ...
			'deleted=0 AND hidden=0',	// AND title LIKE "%blabla%"', // WHERE...
			'', 																	// GROUP BY...
			'ca_name',   																// ORDER BY...
			'' 																		// LIMIT to 10 rows, starting with number 5 (MySQL compat.)
			);
			
		$menuItems=array();
		$menuItems[0] = '<option label="[ '.$LANG->getLL('tx_civserv_wizard_service_form_category.form_category_dummy').' ]" value="0">[ '.$LANG->getLL('tx_civserv_wizard_service_form_category.form_category_dummy').' ]</option>';
		#$menuItems[0] = '<option label="[höm höm höm ]" value="0">[har har har]</option>';
			// Removes all organisations from other mandants so that only
			// the organisations of the actual mandant are displayed in the
			// selectorbox.
		$mandant_obj = t3lib_div::makeInstance('tx_civserv_mandant');
		$mandant = $mandant_obj->get_mandant($this->service_pid);

		while ($categories = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($this->res)) {
			if ($mandant_obj->get_mandant($categories['pid'])==$mandant){
				// Checks if the uid is already selected.
				if ((string)$categories[uid] == $this->category_uid) {
					$selVal = 'selected="selected"';
				} else {
					$selVal = '';
				}
				$menuItems[]='<option label="'.htmlspecialchars($categories[ca_name]).'" value="'.htmlspecialchars($categories[uid]).'"'.$selVal.'>'.htmlspecialchars($categories[ca_name]).'</option>';
			}	
		}

		$PItemName = "&PItemName=".$this->pArr[0];
#		debug($PItemName, '->getFormCategory: $PItemName');

			// Displays the first selectorbox with the categories.
			
		if(count($menuItems)>1){	// 1 for the dummy
#			debug($menuItems, 'tx_civserv_wizard_service_form_category.php->getFormCategories: return wert $menuItems');
			return '<select name="selectedCategories" onchange="changeCategory()">'.implode('',$menuItems).'</select>';
		}else{
			// Baustelle!
			return str_replace('###br###','<br />','<h1>'.$LANG->getLL('tx_civserv_wizard_service_form_category.warning_no_category').'</h1>');
		}
	}//end getFormCategories


	/**
	 * Generates a selector box with the forms from the selected category.
	 *
	 * @return	[type]		...
	 * @@return	string		Selector box with forms.
	 */
	function getForms()	{
		global $LANG;
#		$GLOBALS['TYPO3_DB']->debugOutput = TRUE;
		$this->searchitem = (string)t3lib_div::_GP('searchitem');
		$mode = (string)t3lib_div::_GP('mode');
		$this->searchitem = $this->make_clean($this->searchitem);

			// Gets all forms with the selected category_uid out of the database.
			// Checks also if forms and categories aren't hidden or deleted.
#		debug($this->category_uid, 'tx_civserv_wizard_service_form_category.php->getForms: $this->category_uid');

		$this->res = $GLOBALS['TYPO3_DB']->exec_SELECT_mm_query(
				'tx_civserv_form.uid,
				 tx_civserv_form.pid,
				 tx_civserv_form.fo_name',			 							// SELECT ...
				'tx_civserv_form',
				'tx_civserv_form_fo_category_mm',
				'tx_civserv_category',						// FROM ...
				'AND tx_civserv_category.uid ='.$this->category_uid.'
				 AND tx_civserv_form.deleted=0 AND tx_civserv_form.hidden=0
				 AND tx_civserv_category.deleted=0 AND tx_civserv_category.hidden=0',	// AND title LIKE "%blabla%"', // WHERE...
				'', 										// GROUP BY...
				'fo_name',   								// ORDER BY...
				'' 											// LIMIT to 10 rows, starting with number 5 (MySQL compat.)
			);

		if ($this->searchitem != "" AND $mode == "search") {
			$this->res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				'*',			 							// SELECT ...
				'tx_civserv_form',						// FROM ...
				'fo_name like \'%'.$this->searchitem.'%\' AND deleted=0 AND hidden=0',	// AND title LIKE "%blabla%"', // WHERE...
				'', 										// GROUP BY...
				'fo_name',   								// ORDER BY...
				'' 											// LIMIT to 10 rows, starting with number 5 (MySQL compat.)
				);
		}
		$menuItems=array();


		$mandant_obj = t3lib_div::makeInstance('tx_civserv_mandant');
		$mandant = $mandant_obj->get_mandant($this->service_pid);
		while ($forms = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($this->res)) {
			if ($mandant_obj->get_mandant($forms['pid'])==$mandant){
				// Checks if the uid is already selected.
				if ($this->form_selected($forms['uid'])) {
					$selVal = 'selected="selected"';
				} else {
					$selVal = '';
				}
				$menuItems[]='<option label="'.htmlspecialchars(trim($forms[fo_name])).'" value="'.htmlspecialchars($forms['uid']).'" '.$selVal.'>'.htmlspecialchars($forms[fo_name]).'</option>';
			}
		}

		$PItemName = "&PItemName=".$this->pArr[0];

			// Displays the second selectorbox with the forms.
		return '<select name="selectedforms" size="10" multiple="multiple">'.implode('',$menuItems).'</select>';
	}//end getForms


	/**
	 * Checks if a service is already selected by the user.
	 *
	 * @param	[type]		$service_uid [string]: the uid of a service
	 * @return	[type]		...
	 * @@return	boolean
	 */
	function form_selected($form_uid) {
		$selected_uid = explode('|',(string)t3lib_div::_GP('selected_uid'));
		foreach($selected_uid AS $key => $val) {
			if ($val==$form_uid) {
				return true;
			}
		}
		return false;
	}//end form_selected
	
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
   if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/civserv/res/class.tx_civserv_wizard_service_form_category.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/civserv/res/class.tx_civserv_wizard_service_form_category.php']);
   }


//Instantiating
$SOBE = t3lib_div::makeInstance('tx_civserv_wizard_service_form_category');
$SOBE->init();
$SOBE->main();
$SOBE->printContent();
?>
