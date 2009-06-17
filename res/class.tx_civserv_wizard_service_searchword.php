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
* @@author Tobias M�ller (mullerto@@uni-muenster.de),
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
 *   74: class tx_civserv_wizard_service_searchword extends t3lib_SCbase
 *   90:     function init()
 *  278:     function main()
 *  370:     function getSearchwords($letter)
 *  413:     function searchword_selected($searchword_uid)
 *  430:     function printContent()
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
* This is an abc-wizard for choosing search words for a service. Multiple search words can be selected.
*/
class tx_civserv_wizard_service_searchword extends t3lib_SCbase {

	var $content;		// whole HTML-Content to be displayed in Wizard
	var $res;			// results from SQL-Queries
	var $P;				// Array given from the parent window in backend
	var $bparams;		// reads the value of the "caller-field" from backend, needed to adress and change this field
	var $mode;			// mode for group box, "db" in this case
	var $pArr;			// contains parts of the $bparams
	var $PItemName;
	var $searchitem;
	
	var $arrAlphabet;

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

		$this->arrAlphabet = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');	



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

			<title>'.$LANG->getLL('tx_civserv_wizard_service_searchword.title').'</title>

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
						alert("'.$LANG->getLL('tx_civserv_wizard_service_searchword.warning_msg_1').'");
					}
				} else {
					alert("'.$LANG->getLL('tx_civserv_wizard_service_searchword.warning_msg_2').'");
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

				for(i=0;i<document.serviceform.selectedSearchwords.length;++i) {
					num_removed=0;

						// Removes all selectorbox entries of current letter from current options.
					for (j=0;j<cur_uid_arr.length-num_removed;j++) {
						if (cur_uid_arr[j-num_removed]==document.serviceform.selectedSearchwords.options[i].value) {
							removed = cur_uid_arr.splice(j-num_removed,1);
							removed = cur_name_arr.splice(j-num_removed,1);
							num_removed++;
						}
					}

						// Gets all selected items of current selectorbox.
					if(document.serviceform.selectedSearchwords.options[i].selected == true) {
						if (selected_uid=="") {
							selected_uid=document.serviceform.selectedSearchwords.options[i].value;
							selected_name=document.serviceform.selectedSearchwords.options[i].label;
						} else {
							selected_uid=selected_uid+"|"+document.serviceform.selectedSearchwords.options[i].value;
							selected_name=selected_name+"|"+document.serviceform.selectedSearchwords.options[i].label;
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
				if (document.serviceform.selectedSearchwords) {
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

				// Writes all selected items back to main window and
				// closes the popup wizard.
			function save_and_quit()	{	//
				options = returnOptions(\''.(string)t3lib_div::_GP('selected_uid').'\',\''.(string)t3lib_div::_GP('selected_name').'\');
				insertElements(\'tx_civserv_search_word\',\'db\',\'\',1,options);
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
	 * search words beginning with the selected letter. The User can select one or more search words
	 * for one service or model service.
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

			<h3 class="bgColor5">'.$LANG->getLL('tx_civserv_wizard_service_searchword.select_letter_text').':</h3>
			<table border="0" cellpadding="0" cellspacing="0" id="typo3-tree">
				<tr class="bgColor">
					<td nowrap="nowrap">
		';

		$script=basename(PATH_thisScript);

		//render A-Z list
		
		foreach($this->arrAlphabet as $char){
			if($this->getSearchwordByLetter($char)){
				$this->content .= '<a href="#" onclick="add_options_refresh(\''.$char.'\',\''.(string)t3lib_div::_GP('selected_uid').'\',\''.(string)t3lib_div::_GP('selected_name').'\',\''.$script.'\',\''.$this->PItemName.'\',\'&service_pid='.htmlspecialchars($this->service_pid).'\')">'.$char.'</a>';
			}else{
				$this->content .= '<span style="color:#066">'.$char.'</span>';
			}
			$this->content .= ' ';
		}
		if($this->getSearchwordByLetter('other')){
			$this->content .= '<a href="#" onclick="add_options_refresh(\'other\',\''.(string)t3lib_div::_GP('selected_uid').'\',\''.(string)t3lib_div::_GP('selected_name').'\',\''.$script.'\',\''.$this->PItemName.'\',\'&service_pid='.htmlspecialchars($this->service_pid).'\')">'.$LANG->getLL('all_abc_wizards.other').'</a>';
		}else{
			$this->content .= '<span style="color:#066">'.$LANG->getLL('all_abc_wizards.other').'</span>';
		}

		$this->content.='
					</td>
					<td>
						<input type="text" size="20" name="searchitem" id="searchitem" value="'.$this->searchitem.'"> <br />
						<a href="#" onclick="add_options_refresh(\'search\',\''.(string)t3lib_div::_GP('selected_uid').'\',\''.(string)t3lib_div::_GP('selected_name').'\',\''.$script.'\',\''.$this->PItemName.'\',\'&service_pid='.htmlspecialchars($this->service_pid).'\')">'.$LANG->getLL('all_abc_wizards.search').'</a>
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
			if ($letter != "other" and $letter != "search") {
				$this->content.='<h3 class="bgColor5">'.$LANG->getLL('tx_civserv_wizard_service_searchword.select_searchword_text').''.$letter.':</h3>';
			} else {
				$this->content.='<h3 class="bgColor5">'.$LANG->getLL('tx_civserv_wizard_service_searchword.select_searchword_text_no_abc').':</h3>';
			}
			$this->content.='<table border="0" cellpadding="0" cellspacing="0" id="typo3-tree">
				<tr class="bgColor">
					<td nowrap="nowrap">
			';

				// Gets all search words beginning with the chosen letter and displays them in a selectorbox.
			$this->content .= $this->getSearchwords($letter);

				// Displays a OK-Button to save the selected search words.
			$this->content .= '
					</td>
				</tr>
			</table>

			<input type="button" name="Return" value="'.$LANG->getLL('tx_civserv_wizard_service_searchword.OK_Button').'" onclick="return save_and_quit();">
		';
		}

			// Displays a Cancel-Button at the end of the Page to exit the wizard without changing anything.
		$this->content.='
		<input type="button" name="cancel" value="'.$LANG->getLL('tx_civserv_wizard_service_searchword.Cancel_Button').'" onclick="parent.close();">
		</form>
		</body>
		</html>
		';
	}//end main

	/**
	 * Generates a selector box with the search words locally available for this install.
	 *
	 * @param	[type]		$letter: the selected letter to show search words beginning with this letter in the selectorbox.
	 * @return	[type]		...
	 * @@return	string		Selector box with search words.
	 */
	function getSearchwords($letter)	{
		global $LANG;
		$this->searchitem = (string)t3lib_div::_GP('searchitem');
		$this->searchitem = $this->make_clean($this->searchitem);
		
		
		if ($letter != "other" and $letter != "search") {
				// Gets all search words with the selected letter at the
				// beginning out of the database. Checks also if formulars aren't hidden or
				// deleted.
			$this->res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				'uid,
				 pid,
				 sw_search_word',  			// SELECT...
				'tx_civserv_search_word',					// FROM ...
				'upper(left(sw_search_word,1))=\''.$letter.'\' 
				 AND deleted=0 AND hidden=0',	// AND title LIKE "%blabla%"', // WHERE...
				'sw_search_word', 										// GROUP BY...
				'sw_search_word',   						// ORDER BY...
				'' 											// LIMIT to 10 rows, starting with number 5 (MySQL compat.)
				);
			} 
		if ($letter == "other") {
				// Gets all search words which don't begin with a letter
				// out of the database. Checks also if formulars aren't hidden or
				// deleted.
				
			$where = ' deleted=0 AND hidden=0';
			foreach($this->arrAlphabet as $char){		
				$where .= ' AND !(upper(left(tx_civserv_search_word.sw_search_word,1))=\''.$char.'\')'; 
			}
	
			$this->res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				'uid,
				 pid,
				 sw_search_word',  // SELECT...
				'tx_civserv_search_word',					// FROM ...
				$where,	// AND title LIKE "%blabla%"', // WHERE...
				'sw_search_word', 										// GROUP BY...
				'sw_search_word',   						// ORDER BY...
				'' 											// LIMIT to 10 rows, starting with number 5 (MySQL compat.)
			);
		}
		if ($letter == "search" AND $this->searchitem != "") {
				$this->res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				'uid,
				 pid,
				 sw_search_word',  // SELECT...
				'tx_civserv_search_word',						// FROM ...
				'sw_search_word like \'%'.$this->searchitem.'%\' 
				 AND deleted=0 AND hidden=0',	// AND title LIKE "%blabla%"', // WHERE...
				'sw_search_word', 										// GROUP BY...
				'sw_search_word',   								// ORDER BY...
				'' 											// LIMIT to 10 rows, starting with number 5 (MySQL compat.)
			);
		} 
		$menuItems=array();

		//if you don't want to make search_words community-specific comment out the following 2 lines
		$mandant_obj = t3lib_div::makeInstance('tx_civserv_mandant');
		$mandant = $mandant_obj->get_mandant($this->service_pid);
		if ($this->res) {
			// hier war doch sonst immer der mandanten-krams drinne?!!!
			while ($searchwords = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($this->res)) {
				//if you don't want to make search_words community-specific swap the if-condition to 'always true'
				if(1 == 1){
				#if($mandant_obj->get_mandant($searchwords['pid']) == $mandant){
					// Checks if the uid is already selected.
					if ($this->searchword_selected($searchwords[uid])) {
						$selVal = 'selected="selected"';
					} else {
						$selVal = '';
					}
					$menuItems[]='<option label="'.htmlspecialchars($searchwords['sw_search_word']).'" value="'.htmlspecialchars($searchwords['uid']).'"'.$selVal.'>'.htmlspecialchars($searchwords['sw_search_word']).'</option>';
				}
			}
		}
		$PItemName = "&PItemName=".$this->pArr[0];

			// Displays the second selectorbox with the search words.
		return '<select name="selectedSearchwords" size="10" multiple="multiple">'.implode('',$menuItems).'</select>
			   ';
	}//end getSearchwords


	/**
	 * Checks if a search word is already selected by the user.
	 *
	 * @param	[type]		$searchword_uid [string]: the uid of a search word
	 * @return	[type]		...
	 * @@return	boolean
	 */
	function searchword_selected($searchword_uid) {
		$selected_uid = explode('|',(string)t3lib_div::_GP('selected_uid'));
		foreach($selected_uid AS $key => $val) {
			if ($val==$searchword_uid) {
				return true;
			}
		}
		return false;
	}//end searchword_selected
	
	/**
	 * Cleans up User input in Search field.
	 *
	  */
	 	
	function make_clean($value) {
		$legal_chars = "%[^0-9a-zA-Z������� ]%"; //allow letters, numbers & space
		$new_value = preg_replace($legal_chars,"",$value); //replace with ""
		return $new_value;
	}	



	/**
	 * Checks if there is a position beginning the the given letter, at all
	 *
	 * @param	[type]		$char [string]: a letter from the alphabet
	 * @return	[type]		...
	 * @@return	boolean
	 */
	function getSearchwordByLetter($char){
		//if you don't want to make search_words community-specific comment out the following 2 lines
		$mandant_obj = t3lib_div::makeInstance('tx_civserv_mandant');
		$mandant = $mandant_obj->get_mandant($this->service_pid);
		
		$where = ' deleted=0 AND hidden=0';
			 
		if($char !== 'other' && $char > ''){
			$where .= ' AND upper(left(sw_search_word,1))=\''.$char.'\'';
		}	 
		if($char == 'other'){
			foreach($this->arrAlphabet as $char){		
				$where .= ' AND !(upper(left(tx_civserv_search_word.sw_search_word,1))=\''.$char.'\')'; 
			}
		}	 
		
		$this->res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'uid,
			 pid,
			 sw_search_word',  // SELECT...
			'tx_civserv_search_word',																// FROM ...
			$where,
			'sw_search_word', 																	// GROUP BY...
			'',   																// ORDER BY...
			'' 																		// LIMIT to 10 rows, starting with number 5 (MySQL compat.)
		);
		while($searchwords = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($this->res)){
			//if you don't want to make search_words community-specific swap the if-condition to 'always true'
			if(1 == 1){
			#if ($mandant_obj->get_mandant($searchwords['pid']) == $mandant){
				return true;
			}
		}
		return false;
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
   if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/civserv/res/class.tx_civserv_wizard_service_searchword.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/civserv/res/class.tx_civserv_wizard_service_searchword.php']);
   }


//Instantiating
$SOBE = t3lib_div::makeInstance('tx_civserv_wizard_service_searchword');
$SOBE->init();
$SOBE->main();
$SOBE->printContent();
?>
