<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2004 ProService (osiris@ercis.de)
*  All rights reserved
*
*  This script is part of the Typo3 project. The Typo3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
 * Module 'Cache Services' for the 'civserv' extension.
 *
 * $Id$
 *
 * @author	Stefan Meesters <meesters@uni-muenster.de>
 * @package TYPO3
 * @subpackage tx_civserv
 * @version 1.0
 */
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   68: class tx_civserv_cacheservices extends t3lib_SCbase
 *   76:     function init()
 *   87:     function menuConfig()
 *  114:     function main()
 *  148:     function printContent()
 *  157:     function moduleContent()
 *
 * TOTAL FUNCTIONS: 5
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */

// out-commented the following three lines as they seem to cause trouble with typo3_src >= 4.2.6
#define('PATH_thisScript',str_replace('//','/', str_replace('\\','/', php_sapi_name()=='cgi'||php_sapi_name()=='isapi' ? $HTTP_SERVER_VARS['PATH_TRANSLATED']:$HTTP_SERVER_VARS['SCRIPT_FILENAME'])));
#define("PATH_typo3", dirname(dirname(dirname(dirname(dirname(PATH_thisScript)))))."/typo3/");
#define("PATH_site", dirname(PATH_typo3)."/");

	// DEFAULT initialization of a module [BEGIN]
unset($MCONF);
require_once ('conf.php');
require_once ($BACK_PATH . 'init.php');
require_once ($BACK_PATH . 'template.php');
require_once ($BACK_PATH . 'template.php');
include ('locallang.php');
require_once (PATH_t3lib . 'class.t3lib_scbase.php');
$BE_USER->modAccess($MCONF,1);	// This checks permissions and exits if the users has no permission for entry.
	// DEFAULT initialization of a module [END]

class tx_civserv_cacheservices extends t3lib_SCbase {
	var $pageinfo;

	/**
	 * Initialize the Module.
	 *
	 * @return	void		Nothing.
	 */
	function init()	{
		global $AB,$BE_USER,$LANG,$BACK_PATH,$TCA_DESCR,$TCA,$HTTP_GET_VARS,$HTTP_POST_VARS,$CLIENT,$TYPO3_CONF_VARS;

		parent::init();
	}

	/**
	 * Adds items to the ->MOD_MENU array. Used for the function menu selector.
	 *
	 * @return	[type]		...
	 */
	function menuConfig()	{
		global $LANG;
		$MOD_MENUfunction['0'] = $LANG->getLL('overview','Overview');

		// get the communities from the table 'tx_civserv_conf_mandant'
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'uid, cm_community_name',
			'tx_civserv_conf_mandant',
			'deleted=0 and hidden=0');

		$row_counter = 0;
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res))	{
			$MOD_MENUfunction[$row['uid']] = $row['cm_community_name'];
			$row_counter++;
		}
		$this->MOD_MENU['function'] = $MOD_MENUfunction;

		parent::menuConfig();
	}


	// If you chose "web" as main module, you will need to consider the $this->id parameter which will contain the uid-number of the page clicked in the page tree
	/**
	 * Main function of the module. Write the content to $this->content
	 *
	 * @return	void		Nothing.
	 */
	function main()    {
		global $BE_USER,$LANG,$BACK_PATH;

			// Draw the header.
		$this->doc = t3lib_div::makeInstance('mediumDoc');
		$this->doc->docType= 'xhtml_trans';
		$this->doc->backPath = $BACK_PATH;
		$this->doc->form='<form action="'.htmlspecialchars('index.php?id='.$this->id).'" method="post" autocomplete="off">';

			// Add some JavaScript:
		$this->doc->JScode.= $this->doc->wrapScriptTags('
			function jumpToUrl(URL)	{	//
				document.location = URL;
			}
		');

		$this->content .= $this->doc->startPage($LANG->getLL('title'));
		$this->content .= $this->doc->section($LANG->getLL('title'),$this->doc->funcMenu($headerSection,t3lib_BEfunc::getFuncMenu($this->id,'SET[function]',$this->MOD_SETTINGS['function'],$this->MOD_MENU['function'])));

		$this->content .= $this->moduleContent ();

		// ShortCut
		if ($BE_USER->mayMakeShortcut())	{
			$this->content.=$this->doc->spacer(20).$this->doc->section('',$this->doc->makeShortcutIcon('id',implode(',',array_keys($this->MOD_MENU)),$this->MCONF['name']));
		}

		$this->content.=$this->doc->endPage();
	}

	/**
	 * Prints out the module HTML
	 *
	 * @return	void		Nothing.
	 */
	function printContent()	{
		echo $this->content;
	}

	/**
	 * Generates the module content
	 *
	 * @return	string		Content that is to be displayed within the module
	 */
	function moduleContent()	{
		global $LANG;
		$content = '';
		switch((string)$this->MOD_SETTINGS['function']) {
			case 0:
				$content .= $this->doc->section($LANG->getLL('overview').":",$content,0,1);
				$content .= $LANG->getLL('description') . '<br /><br />';
				$content .= $LANG->getLL('caching_note') . ':';
				$content .= '<ul>';
				$content .= '<li>' . $LANG->getLL('no_cache1') . '</li>';
				$content .= '<li>' . $LANG->getLL('no_cache2') . '</li>';
				$content .= '<li>' . $LANG->getLL('no_cache3') . '</li>';
				$content .= '</ul>';
				break;
	      	default:
				$sitepath = substr($_SERVER['SCRIPT_NAME'],0,strpos($_SERVER['SCRIPT_NAME'],'typo3conf/'));
				$site = 'http://' . $_SERVER['HTTP_HOST'] . $sitepath;

				require_once('./class.tx_civserv_modcacheservices_cache.php');

				$subModuleObj = t3lib_div::makeInstance('tx_civserv_modcacheservices_cache');
				$content .= $subModuleObj->main($this,$this->MOD_SETTINGS['function'],$site);
			}
		return $content;
	}



}


if (defined("TYPO3_MODE") && $TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/civserv/modcacheservices/index.php"])	{

	include_once($TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/civserv/modcacheservices/index.php"]);
}




// Make instance:
$SOBE = t3lib_div::makeInstance("tx_civserv_cacheservices");
$SOBE->init();

// Include files?
reset($SOBE->include_once);
while(list(,$INC_FILE)=each($SOBE->include_once))	{include_once($INC_FILE);}

$SOBE->main();
$SOBE->printContent();

?>