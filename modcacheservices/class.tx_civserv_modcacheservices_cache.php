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
 *   54: class tx_civserv_modcacheservices_cache
 *   67:     function main(&$parentObj, $uid, $site)
 *  215:     function cacheServices ($lastProcessed, $startTime, $site)
 *  288:     function cHashParams($addQueryParams)
 *
 *              SECTION: Helper functions for generating the pidlist:
 *  330:     function getTreeList($id,$depth,$begin=0,$dontCheckEnableFields=0,$addSelectFields='',$moreWhereClauses='')
 *  365:     function checkEnableFields($row)
 *  383:     function checkPagerecordForIncludeSection($row)
 *
 * TOTAL FUNCTIONS: 6
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_civserv_modcacheservices_cache {

	var $totalPages;		// contains the number of total pages being processed, for statistical purposes only.

	/**
	 * Main function of this class, starts caching of a given page.
	 * Generates the content and the prgressbar.
	 *
	 * @param	Obj		The parent object
	 * @param	integer		Uid of the site, which is used for caching
	 * @param	string		Sitepath to the index.php, wich is used to cache the pages
	 * @return	string		Content that is to be displayed within the module
	 */
	function main(&$parentObj, $uid, $site)    {
		global $BACK_PATH, $LANG;

		$content = '';

		// Start or resume session
		session_name('modcacheservices');
		session_start();
		if ($_SESSION['uid'] != $uid) {
			$_SESSION['uid'] = $uid;

			// get the community name and the page uid
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
						'cm_community_name, cm_community_id, cm_uid, cm_page_uid',
						'tx_civserv_conf_mandant',
						'uid = ' . $uid);

			if ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
				$community_id = $row['cm_community_id'];
			} else {
				$content .= $LANG->getLL('error');
			}
			$_SESSION['community_name'] = $row['cm_community_name'];
			$_SESSION['community_id'] = $row['cm_community_id'];
			$_SESSION['page_uid'] = $row['cm_page_uid'];

			$pidlist = $this->getTreeList($row['cm_uid'],10);
			$_SESSION['pidlist'] = substr($pidlist,0,strlen($pidlist)-1);
		}

		// this local variable is only set for the return statement, where the session could be destroyed
		$community_name = $_SESSION['community_name'];

		if (t3lib_div::GPvar('tx_civserv_modcacheservices_cache_doit') && !t3lib_div::GPvar ('tx_civserv_modcacheservices_cache_cancel')) {
			$lastProcessed = t3lib_div::GPvar('tx_civserv_modcacheservices_cache_lastProcessed');
			$initialStartTime = intval(t3lib_div::GPvar('tx_civserv_modcacheservices_cache_startTime')) ? intval(t3lib_div::GPvar('tx_civserv_modcacheservices_cache_startTime')) : time();
			$elapsedTime = time() - $initialStartTime;
			$startTime = time();

			// Make sure that the "Now caching ..." screen get's displayed before the first page is being processed:
			if (isset($lastProcessed)) {
				$lastProcessed = $this->cacheServices ($lastProcessed, $startTime, $site);
			} else {
				$lastProcessed = 0;
				$_SESSION['page_uid'] = t3lib_div::GPvar('tx_civserv_modcacheservices_cache_page_uid');
			}

			if (intval ($this->totalServices) != 0) {
				$percentDone = intval(($lastProcessed == -1 ? $this->totalServices : $lastProcessed) / $this->totalServices * 100);
			} else {
				$percentDone = 0;
			}
			$content .= '
				'.($lastProcessed != -1 ? $LANG->getLL('caching') : $LANG->getLL('done')) .'<br />
				<br />
				<table cellspacing="0" cellpadding="0">
					<tr>
						<td><strong>' . $LANG->getLL('elapsedTime') . ':</strong></td>
						<td colspan="3">' . $elapsedTime . ' ' . $LANG->getLL('seconds') . '</td><td>&nbsp;</td>
					</tr>
					<tr>
						<td><strong>' . $LANG->getLL('processed') . ':</strong>&nbsp;</td>
						<td style="width:100px;">' . ($lastProcessed == -1 ? $this->totalServices : $lastProcessed) . ' / ' . $this->totalServices . '</td>
						<td style="width:100px; border: 1px solid black;"><div style="float: left; width:'.$percentDone.'px; background-color:green;">&nbsp;</div><div style="float:left; width:'.(100 - $percentDone) . 'px; background-color:'.$parentObj->doc->bgColor2 . ';">&nbsp;</div></td>
						<td> ' . $percentDone . ' %</td>
					</tr>
				</table>
				<br />
				<input type="hidden" name="tx_civserv_modcacheservices_cache_doit" value="yeah" />
				<input type="hidden" name="tx_civserv_modcacheservices_cache_startTime" value="' . $initialStartTime . '" />
				<input type="hidden" name="tx_civserv_modcacheservices_cache_lastProcessed" value="' . $lastProcessed . '" />
			';

			if ($lastProcessed != -1) {
				$content .= '<input type="submit" name="tx_civserv_modcacheservices_cache_cancel" value="' . $LANG->getLL('cancel') . '" onclick="window.stop(); " />';
				$content .= $parentObj->doc->wrapScriptTags('
					document.forms[0].submit();
				');
			} else {
				// delete session variables und destroy session
				session_unset();
				session_destroy();

				$content .= '<a href="index.php">' . $LANG->getLL('returnToMainMenu') . '</a>';
			}

		} else {
			// get count of local services
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
						'count(*)',
						'tx_civserv_service',
						'tx_civserv_service.deleted=0 AND ' .
						' tx_civserv_service.hidden=0 AND ' .
						'((UNIX_TIMESTAMP(LOCALTIMESTAMP) BETWEEN tx_civserv_service.starttime AND tx_civserv_service.endtime) OR ' .
						'((UNIX_TIMESTAMP(LOCALTIMESTAMP) > tx_civserv_service.starttime) AND (tx_civserv_service.endtime = 0)) OR ' .
						'(tx_civserv_service.starttime = 0 AND tx_civserv_service.endtime = 0)) AND ' .
						'tx_civserv_service.pid IN (' . $_SESSION['pidlist'] . ')');
			if ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
				$local_services = $row['count(*)'];
			} else {
				$content .= '<strong>' . $LANG->getLL('error') . '</strong><br />';
			}
			$content .= $LANG->getLL('local_services') . ': ' . $local_services . '<br />';

			// get count of external services
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
						'count(*)',
						'tx_civserv_service, tx_civserv_external_service',
						'tx_civserv_service.uid = tx_civserv_external_service.es_external_service AND ' .
						'tx_civserv_external_service.deleted=0 AND ' .
						'tx_civserv_external_service.hidden=0 AND ' .
						'tx_civserv_service.deleted=0 AND ' .
						'tx_civserv_service.hidden=0 AND ' .
						'((UNIX_TIMESTAMP(LOCALTIMESTAMP) BETWEEN tx_civserv_service.starttime AND tx_civserv_service.endtime) OR ' .
						'((UNIX_TIMESTAMP(LOCALTIMESTAMP) > tx_civserv_service.starttime) AND (tx_civserv_service.endtime = 0)) OR ' .
						'(tx_civserv_service.starttime = 0 AND tx_civserv_service.endtime = 0)) AND ' .
						'tx_civserv_external_service.pid IN (' . $_SESSION['pidlist'] . ')');
			if ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
				$external_services = $row['count(*)'];
			} else {
				$content .= '<strong>' . $LANG->getLL('error') . '</strong><br />';
			}
			$content .= $LANG->getLL('external_services') . ': ' . $external_services . '<br /><br />';

			$services = $local_services + $external_services;
			$_SESSION['services_count'] = $services;
			$content .= '<strong>' . $LANG->getLL('services') . ': ' . $services . '</strong><br /><br />';

			$content .= $LANG->getLL('page_id') . ': <input type="text" name="tx_civserv_modcacheservices_cache_page_uid" value="' . $_SESSION['page_uid'] . '" /><br /><br />';

			$content .= '<img ' . t3lib_iconWorks::skinImg ($BACK_PATH, '../t3lib/gfx/icon_note.gif').' align="absmiddle" /> <strong>' . $LANG->getLL('note') . ':</strong>
				' . $LANG->getLL('note_text') . '
				<br />
				<br />
				<input name="tx_civserv_modcacheservices_cache_doit" type="submit" value="' . $LANG->getLL('startCaching') . '" />
			';
		}
		return $parentObj->doc->section($community_name,$content,0,1);
	}

	/**
	 * Caches as much paches as possible in 5 seconds.
	 *
	 * @param	integer		the last prozessed service
	 * @param	integer		the start time
	 * @param	string		sitepath to the index.php, wich is used to cache the pages
	 * @return	integer		the new last prozessed service
	 */
	function cacheServices ($lastProcessed, $startTime, $site) {
		global $TYPO3_DB;

		// first time function is called get service id's
		if ($lastProcessed == 0) {
			// get the services of selected community
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
						'tx_civserv_service.uid',
						'tx_civserv_service',
						'tx_civserv_service.deleted=0 AND ' .
						' tx_civserv_service.hidden=0 AND ' .
						'((UNIX_TIMESTAMP(LOCALTIMESTAMP) BETWEEN tx_civserv_service.starttime AND tx_civserv_service.endtime) OR ' .
						'((UNIX_TIMESTAMP(LOCALTIMESTAMP) > tx_civserv_service.starttime) AND (tx_civserv_service.endtime = 0)) OR ' .
						'(tx_civserv_service.starttime = 0 AND tx_civserv_service.endtime = 0)) AND ' .
						'tx_civserv_service.pid IN (' . $_SESSION['pidlist'] . ')');
			$row_counter = 1;
			while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res))	{
				$service_uids[$row_counter] = $row[uid];
				$row_counter++;
			}
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
						'tx_civserv_service.uid',
						'tx_civserv_service, tx_civserv_external_service',
						'tx_civserv_service.uid = tx_civserv_external_service.es_external_service AND ' .
						'tx_civserv_external_service.deleted=0 AND ' .
						'tx_civserv_external_service.hidden=0 AND ' .
						'tx_civserv_service.deleted=0 AND ' .
						'tx_civserv_service.hidden=0 AND ' .
						'((UNIX_TIMESTAMP(LOCALTIMESTAMP) BETWEEN tx_civserv_service.starttime AND tx_civserv_service.endtime) OR ' .
						'((UNIX_TIMESTAMP(LOCALTIMESTAMP) > tx_civserv_service.starttime) AND (tx_civserv_service.endtime = 0)) OR ' .
						'(tx_civserv_service.starttime = 0 AND tx_civserv_service.endtime = 0)) AND ' .
						'tx_civserv_external_service.pid IN (' . $_SESSION['pidlist'] . ')');
			while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res))	{
				$service_uids[$row_counter] = $row[uid];
				$row_counter++;
			}
			// save uids in session
			$_SESSION['service_uids'] = $service_uids;
		}

		$totalServices = $this->totalServices = $_SESSION['services_count'];

		// Fetch all page records except those we already have processed:
		$newLastProcessed = $lastProcessed + 1;

		// Process as many services as possible during the specified time span:
		while (time() - $startTime < 5 && $newLastProcessed <= $totalServices) {
			$addQueryParams = '&tx_civserv_pi1[community_id]=' . $_SESSION['community_id'] . '&tx_civserv_pi1[mode]=service&tx_civserv_pi1[id]=' . $_SESSION['service_uids'][$newLastProcessed];
			$pA = $this->cHashParams($addQueryParams);
			$addQueryParams .= '&cHash=' . t3lib_div::shortMD5(serialize($pA));
			$wget = 'wget "' . $site . 'index.php?id=' . $_SESSION['page_uid'] . $addQueryParams  . '" --spider --timeout=0 --wait=0';
			$out .= shell_exec($wget);
			$newLastProcessed ++;
		}

		if ($newLastProcessed > $totalServices) {
			return -1;
		}

		return $newLastProcessed;
	}


	/**
	 * (function is copied from class.tslib_fe.php and a little bit customized for using in the BE)
	 * Splits the input query-parameters into an array with certain parameters filtered out.
	 * Used to create the cHash value
	 *
	 * @param	string		Query-parameters: "&xxx=yyy&zzz=uuu"
	 * @return	array		Array with key/value pairs of query-parameters WITHOUT a certain list of variable names (like id, type, no_cache etc) and WITH a variable, encryptionKey, specific for this server/installation
	 * @access private
	 * @see makeCacheHash(), tslib_cObj::typoLink()
	 */
	function cHashParams($addQueryParams) {
		global $TYPO3_CONF_VARS;
		$params = explode('&',substr($addQueryParams,1));       // Splitting parameters up
		// Make array:
		$pA = array();
		foreach($params as $theP)       {
			$pKV = explode('=', $theP);     // Splitting single param by '=' sign
			if (!t3lib_div::inList('id,type,no_cache,cHash,MP,ftu',$pKV[0]))        {
				$pA[$pKV[0]] = (string)rawurldecode($pKV[1]);
			}
		}
		$pA['encryptionKey'] = $TYPO3_CONF_VARS['SYS']['encryptionKey'];
		ksort($pA);
		return $pA;
	}


	/******************************
	 *
	 * Helper functions for generating the pidlist:
	 *
	 *******************************/

 	/**
 * (function is a copied from class.tslib_content.php and a little bit customized for using in the BE)
 * Generates a list of Page-uid's from $id. List does not include $id itself
 *  The only pages WHICH PREVENTS DECENDING in a branch are
 *    - deleted pages,
 *    - pages in a recycler or of the Backend User Section type
 *    - pages that has the extendToSubpages set, WHERE start/endtime, hidden and fe_users would hide the records.
 *
 *  Returns the list with a comma in the end (if any pages selected!) - which means the input page id can comfortably be appended to the output string if you need it to.
 *
 * @param	integer		The id of the start page from which point in the page tree to decend.
 * @param	integer		The number of levels to decend. If you want to decend infinitely, just set this to 100 or so. Should be at least "1" since zero will just make the function return (no decend...)
 * @param	integer		$begin is an optional integer that determines at which level in the tree to start collecting uid's. Zero means 'start right away', 1 = 'next level and out'
 * @param	boolean		See function description
 * @param	string		Additional fields to select. Syntax: ",[fieldname],[fieldname],..."
 * @param	string		Additional where clauses. Syntax: " AND [fieldname]=[value] AND ..."
 * @return	string		A list of page ID integer values for the decended levels.
 * @see tslib_fe::checkEnableFields(), tslib_fe::checkPagerecordForIncludeSection()
 */
	function getTreeList($id,$depth,$begin=0,$dontCheckEnableFields=0,$addSelectFields='',$moreWhereClauses='')     {
		$depth=intval($depth);
		$begin=intval($begin);
		$id=intval($id);
		$theList='';
		$allFields = 'uid,hidden,starttime,endtime,fe_group,extendToSubpages,doktype,php_tree_stop'.$addSelectFields;
		if ($id && $depth>0)    {
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($allFields, 'pages', 'pid='.intval($id).' AND deleted=0 AND doktype!=255 AND doktype!=6'.$moreWhereClauses, '' ,'sorting');
			while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res))      {
				if ($dontCheckEnableFields || $this->checkPagerecordForIncludeSection($row)) {
					if ($begin<=0)  {
						if ($dontCheckEnableFields || $this->checkEnableFields($row))        {
							$theList.=$row['uid'].',';
						}
					}
					if ($depth>1 && !$row['php_tree_stop']) {
						$theList.=$this->getTreeList($row['uid'], $depth-1, $begin-1, $dontCheckEnableFields, $addSelectFields, $moreWhereClauses);
					}
				}
			}
		}
		return $theList;
	}


	/**
	 * (function is copied from class.tslib_fe.php and a little bit customized for using in the BE)
	 * Checks page record for enableFields
	 * Returns true if enableFields does not disable the page record.
	 * Takes notice of the ->showHiddenPage flag and uses SIM_EXEC_TIME for start/endtime evaluation
	 *
	 * @param	array		The page record to evaluate (needs fields; hidden, starttime, endtime, fe_group)
	 * @return	boolean		True, if record is viewable.
	 * @see tslib_cObj::getTreeList(), checkPagerecordForIncludeSection()
	 */
	function checkEnableFields($row)        {
		if (!$row['hidden']
			&& $row['starttime']<=$GLOBALS['SIM_EXEC_TIME']
			&& ($row['endtime']==0 || $row['endtime']>$GLOBALS['SIM_EXEC_TIME'])) {
			return 1;
		}
	}


	/**
	 * (function is copied from class.tslib_fe.php and a little bit customized for using in the BE)
	 * Checks page record for include section
	 *
	 * @param	array		The page record to evaluate (needs fields;extendToSubpages + hidden, starttime, endtime, fe_group)
	 * @return	boolean		Returns true if either extendToSubpages is not checked or if the enableFields does not disable the page record.
	 * @access private
	 * @see checkEnableFields(), tslib_cObj::getTreeList(), checkRootlineForIncludeSection()
	 */
	function checkPagerecordForIncludeSection($row) {
		return (!$row['extendToSubpages'] || $this->checkEnableFields($row)) ? 1 : 0;
	}

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/civserv/modcacheservices/class.tx_civserv_modcacheservices_cache.php'])    {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/civserv/modcacheservices/class.tx_civserv_modcacheservices_cache.php']);
}

?>