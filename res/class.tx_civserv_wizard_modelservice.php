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
 *   75: class tx_civserv_wizard_modelservice extends t3lib_SCbase
 *   92:     function init()
 *  197:     function main()
 *  260:     function menuConfig()
 *  312:     function getSelectForModelService()
 *  366:     function get_folders($startnode)
 *  397:     function printContent()
 *
 * TOTAL FUNCTIONS: 6
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */



	// DEFAULT initialization of a module [BEGIN]
require_once ('conf.php');
require_once ($BACK_PATH.'init.php');
require_once ($BACK_PATH.'template.php');
require_once (PATH_t3lib.'class.t3lib_scbase.php');

	// Initialization of language file
include ('locallang_wizard.php');
$LANG->includeLLFile('EXT:res/locallang_wizard.php');



/**
* This is a wizard for choosing a model service for a service. Only one model service can be selected.
*/
class tx_civserv_wizard_modelservice extends t3lib_SCbase {

	var $content;		// whole HTML-Content to be displayed in Wizard
	var $res;			// results from SQL-Queries
	var $P;				// Array given from the parent window in backend
	var $bparams;		// reads the value of the "caller-field" from backend, needed to adress and change this field
	var $mode;			// mode for group box, "db" in this case
	var $pArr;			// contains parts of the $bparams
	var $selectorbox1_checked;
	var $selectorbox2_checked;
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
		$this->selectorbox1_checked = t3lib_div::_GP('select1');
		$this->selectorbox2_checked = t3lib_div::_GP('select2');

			// Find "mode"
		$this->mode='db';

			// Is $P set? if not, read from URL. This is needed because otherwise
			// the p-array will be lost and no data could be written back to the
			// main window.
		if ($this->P['itemName']) {
			$this->pArr = array();
			$this->pArr = explode('|',$this->P['itemName']);
		} else {
			$this->pArr = array();
			$this->pArr[0] = t3lib_div::_GP('PItemName');
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
			<!-- TYPO3 Script ID: typo3conf/ext/civserv/xlass.tx_civserv_wizard_modelservice.php -->
			<meta http-equiv="Content-Type" content="text/html; charset='.$charset.'" />
			<meta name="GENERATOR" content="TYPO3 3.6.2, http://typo3.com, &#169; Kasper Sk&#229;rh&#248;j 1998-2004, extensions are copyright of their respective owners." />

			<title>'.$LANG->getLL('tx_civserv_wizard_modelservice.title').'</title>

			<link rel="stylesheet" type="text/css" href="stylesheet_wizard.css" />
		';

				// JavaScript
		$this->content.='
			<script type="text/javascript">
			script_ended = 0;
			var searchitem="";
			function jumpToUrl(URL)	{	//
				if (document.modelservice.searchitem.value){
					searchitem = document.modelservice.searchitem.value;
					URL = URL+"&searchitem="+searchitem;
				}
					split = URL.split("&");
					for(i=0;i<split.length;++i) {
							if (split[i] == "mode=search" && searchitem == "") {
								alert ("'.$LANG->getLL('all_wizards.search_warning').'");
							}
					}
					document.location = URL;
			}
	// This JavaScript is primarily for RTE/Link. jumpToUrl is used in the other cases as well...
			var elRef="";
			var targetDoc="";

				// Writes the element into main window.
			function insertElement(table, type, fp, close)	{	//
				if (1=='.($this->pArr[0]&&!$this->pArr[1]&&!$this->pArr[2] ? 1 : 0).')	{
						//selection of ModelService-title and UID
					 for(i=0;i<document.modelservice.SelectedService.length;++i) {
					  if(document.modelservice.SelectedService.options[i].selected == true) {
					   ServiceName=document.modelservice.SelectedService.options[i].label;
					   ServiceUID=document.modelservice.SelectedService.options[i].value;
					  }
					 }
					 	//check, if model service selected, or if only a folder is chosen
					if (ServiceUID != \'0\'){
						parent.window.opener.setFormValueFromBrowseWin("'.$this->pArr[0].'",fp?fp:table+"_"+ServiceUID,ServiceName);
						parent.window.opener.focus();
						parent.close();
					} else {
						alert("'.$LANG->getLL('tx_civserv_wizard_modelservice.warning_msg_1').'");
					}
				} else {
					alert("'.$LANG->getLL('tx_civserv_wizard_modelservice.warning_msg_2').'");
					if (close)	{
						parent.window.opener.focus();
						parent.close();
					}
				}
				return false;
			}
			</script>
			<!--###POSTJSMARKER###-->
			</head>
		';
		parent::init();
	}//end init


	/**
	 * Contains all the logic of the wizard. Shows the two selectorboxes for selecting model service categories
	 * and the model service itself. The User can select only one model service for a service.
	 *
	 * @return	[type]		Returns the HTML-Body with the two selectorboxes.
	 * @@return	void
	 */
	function main()	{
		global $LANG;
		$script=basename(PATH_thisScript);
		$this->searchitem = (string)t3lib_div::_GP('searchitem');
		// Draw the body.
		$this->content.='
			<body scroll="auto" id="typo3-browse-links-php">
			<form name="modelservice" action="" method="post">

			<h3 class="bgColor5">'.$LANG->getLL('tx_civserv_wizard_modelservice.select_category_text').':</h3>
			<table border="0" cellpadding="0" cellspacing="0" id="typo3-tree">
				<tr class="bgColor">
					<td nowrap="nowrap">
		';

			// Displays the selectorbox for model service categories. At the first call, no category should be selected.
		$PItemName = "&PItemName=".$this->pArr[0].'&select1=1';
		if ($this->selectorbox1_checked) {
			$this->content.=t3lib_BEfunc::getFuncMenu($this->id,'SET[function]',$this->MOD_SETTINGS['function'],$this->MOD_MENU['function'],'', $PItemName);
		} else {
			$this->MOD_SETTINGS['function']='0';
			$this->content.=t3lib_BEfunc::getFuncMenu($this->id,'SET[function]','0',$this->MOD_MENU['function'],'', $PItemName);
		}
		$this->content.='
					</td>
					<td>
						<input type="text" size="20" name="searchitem" id="searchitem" value="'.$this->searchitem.'"> <br />
						<a href="#" name="link" onclick="jumpToUrl(\''.$script.'?id='.$this->id.''.$PItemName.'&SET[function]=0&mode=search\');">'.$LANG->getLL('all_category_wizards.search').'</a>
					</td>
				</tr>
			</table>
		';

		// Only display second selectorbox if a model service category is selected.

		if ((string)$this->MOD_SETTINGS['function']==0 AND $this->searchitem == "" AND !$this->selectorbox2_checked) {
			// do nothing
		} else {
			$this->content.='
			<h3 class="bgColor5">'.$LANG->getLL('tx_civserv_wizard_modelservice.select_model_service_text').':</h3>
			<table border="0" cellpadding="0" cellspacing="0" id="typo3-tree">
				<tr class="bgColor">
					<td nowrap="nowrap">
			';

				// Gets all model services from the selected category and displays them in a selectorbox.
			$this->content.=$this->getSelectForModelService();

				// Displays a OK-Button to save the selected model service.
			$this->content.='
					</td>
				</tr>
			</table>

			<input type="button" name="Return" value="'.$LANG->getLL('tx_civserv_wizard_modelservice.OK_Button').'" onclick="return insertElement(\'tx_civserv_model_service\',\'db\',\'\',1);">
		';
		}

			// Displays a Cancel-Button at the end of the Page to exit the wizard without changing anything.
		$this->content.='
			<input type="button" name="cancel" value="'.$LANG->getLL('tx_civserv_wizard_modelservice.Cancel_Button').'" onclick="parent.close();">
			</form>';
		$this->content.='
			<br/><br/>
			<p style="padding:20px; font-weight:bold; font-size:2em">'.$LANG->getLL('tx_civserv_wizard_modelservice.save_header').'</p>
			<p style="padding:20px; font-size:1.5em">'.$LANG->getLL('tx_civserv_wizard_modelservice.save_message').'</p>
		';
		$this->content.='
		</body>
		</html>
		';
	}//end main


	/**
	 * Adds items to the ->MOD_MENU array. Used for the function menu selector.
	 * This function configures the selectorbox by searching the model service data-
	 * base for model service categories.
	 *
	 * @return	[type]		...
	 * @@return	void		Returns the selectorbox with the model service categories.
	 */
	function menuConfig()	{
		global $LANG;		// Has to be in every function which uses localization data.

			// Gets the root-UID of model service out of the config table and stores
			// it in $uidModelService.
		$this->res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'cf_value',			 							// SELECT ...
			'tx_civserv_configuration',		 				// FROM ...
			'cf_module="model service" AND cf_key="uid"', 	// AND title LIKE "%blabla%"', // WHERE...
			'', 											// GROUP BY...
			'',   											// ORDER BY...
			'' 												// LIMIT to 10 rows, starting with number 5 (MySQL compat.)
			);
		$uidModelService = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($this->res);

			// Gets all model service categories out of the database by selecting all model services
			// with PID = $uidModelService.
		$this->res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'*',			 						// SELECT ...
			'pages',		 						// FROM ...
			'pid='.$uidModelService[cf_value].' AND deleted=0 AND hidden=0', 		// AND title LIKE "%blabla%"', // WHERE...
			'', 									// GROUP BY...
			'title',   									// ORDER BY...
			'' 										// LIMIT to 10 rows, starting with number 5 (MySQL compat.)
			);

			// Places a message to inform the user to select a model service
			// category as the first entry in the selectorbox. The model service
			// categories selected above are also stored in this box.
		$menuItems = array();
		$menuItems[0] = '[ '.$LANG->getLL('tx_civserv_wizard_modelservice.model_service_category_dummy').' ]';
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($this->res)) {
  			$menuItems[$row['uid']] = $row['title'];
		}
		$this->MOD_MENU = Array (
			'function' => $menuItems,
			'extSel' => '',
			'phpFile' => '',
			'tuneXHTML' => '',
			'tuneQuotes' => '',
			'tuneBeautify' => '',
		);
		parent::menuConfig();
	}


	/**
	 * Generates a selector box with the model services locally available for this install.
	 *
	 * @return	[type]		...
	 * @@return	string		Selector box with model services.
	 */
	function getSelectForModelService()	{
		global $LANG;
			// Selects all model services of the selected category AND categories underneath it by checking if
			// the PIDs of the model services and the UID of the model service
			// categories are the same.
		$this->searchitem = $this->make_clean($this->searchitem);
		$mode = (string)t3lib_div::_GP('mode');
		
		$folders = $this->get_folders($this->MOD_SETTINGS['function']);
		$pid_top=(string)$this->MOD_SETTINGS['function'];
		if(count($folders)>0 and $mode != "search"){
			$where_clause = 'pid IN ('.$pid_top.', '.implode(",", $folders).')';
		}else{
			$where_clause = 'pid ='.$pid_top;
		}
		if($this->searchitem != ""){
			$where_clause = 'ms_name like \'%'.$this->searchitem.'%\'';
		}
		
		$this->res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'*',			 								// SELECT ...
			'tx_civserv_model_service',						// FROM ...
			''.$where_clause.' AND deleted=0 AND hidden=0',								// AND title LIKE "%blabla%"', // WHERE...
			'', 											// GROUP BY...
			'ms_name',   											// ORDER BY...
			'' 												// LIMIT to 10 rows, starting with number 5 (MySQL compat.)
			);
		$menuItems=array();

			// Configures the selectorbox for model services. At the first call, no category should be selected,
			// so a message to inform the user to select a model service is placed
			// as the first entry in the selectorbox. The model services
			// selected above are also stored in this box.
		if ($this->selectorbox2_checked) {
			$menuItems[]='<option label="" value="0">[ '.$LANG->getLL('tx_civserv_wizard_modelservice.model_service_dummy').' ]</option>';
		} else {
			$this->MOD_SETTINGS['extSel']='0';
			$menuItems[]='<option label="" value="0" selected="selected">[ '.$LANG->getLL('tx_civserv_wizard_modelservice.model_service_dummy').' ]</option>';
		}
		while ($modelService = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($this->res)) {
			$selVal = strcmp($modelService[uid],$this->MOD_SETTINGS['extSel']) ? '' : ' selected="selected"';
			$menuItems[]='<option label="'.htmlspecialchars($modelService[ms_name]).'" value="'.htmlspecialchars($modelService[uid]).'"'.$selVal.'>'.htmlspecialchars($modelService[ms_name]).'</option>';
		}
			// SelectedService should be called SET[extSel], but this won't work with JavaScript because of []! But the meaning is the same and it works.
		$PItemName = "&PItemName=".$this->pArr[0].'&select1=1&select2=1';

			// Displays the second selectorbox with the model services.
		return '<select name="SelectedService" onchange="jumpToUrl(\'?SET[extSel]=\'+this.options[this.selectedIndex].value+ \''.htmlspecialchars($PItemName).'\',this);">'.implode('',$menuItems).'</select>
			   ';
	}//end getSelectForModelService


	/**
	 * Gives back all uid's from folders in the pagetree underneath the startnode
	 *
	 * @param	[type]		$startnode: ...
	 * @return	[type]		...
	 * @@param	the uid of the startnode.
	 * @@return	void
	 */
	function get_folders($startnode) {
		$GLOBALS['TYPO3_DB']->debugOutput=TRUE;
		$result = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'pid,
			uid',
			'pages',
			'pid = '.$GLOBALS['TYPO3_DB']->quoteStr($startnode,'pages').' AND deleted=0 AND hidden=0',
			'',
			'title',
			'');
		$selectedUIDs = array();
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($result)) {
			$selectedUIDs[] = $row['uid'];
			$selectedUIDs = array_merge($this->get_folders($row['uid']),$selectedUIDs);
		}
		return $selectedUIDs;
	}//end get_folders

	
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
   if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/civserv/res/class.tx_civserv_wizard_modelservice.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/civserv/res/class.tx_civserv_wizard_modelservice.php']);
   }


//Instantiating
$SOBE = t3lib_div::makeInstance('tx_civserv_wizard_modelservice');
$SOBE->init();
$SOBE->main();
$SOBE->printContent();
?>
