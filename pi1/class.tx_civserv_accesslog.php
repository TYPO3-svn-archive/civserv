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
* This class holds some functions used by the TYPO3 backend....
*
* Some scripts that use this class: ?
* Depends on: ?
*
* $Id$
*
* @author Georg Niemeyer (niemeyer@uni-muenster.de)
* @package TYPO3
* @subpackage tx_civserv
* @version 1.0
*
* Changes: Datum, Initialen - vorgenommene Änderungen
*/
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   55: class tx_civserv_accesslog
 *   57:     function update_log($service_uid, $log_interval, $ip)
 *   91:     function aggr_log ()
 *
 * TOTAL FUNCTIONS: 2
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_civserv_accesslog {

	function update_log($service_uid, $log_interval, $ip){
		$GLOBALS['TYPO3_DB']->debugOutput=TRUE;
		//$today = getdate();
		$lock_interval = 10;
		$lock_interval = $lock_interval * 60;
		$time = time();
		//$today_str = $today['year'] . (($today['mon'] < 10) ? '0' . $today['mon']:$today['mon']) . (($today['mday']<10) ? '0' . $today['mday']:$today['mday']);
		$lock_res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*',
														 'tx_civserv_accesslog',
														 'al_service_uid = ' . intval($service_uid) . ' AND remote_addr = \''.$ip.'\' AND tstamp > '.$time.' - '.$lock_interval,
														 '',
														 '',
														 '',
														 '');
		$lock = intval($GLOBALS['TYPO3_DB']->sql_num_rows($lock_res));

		if (!$lock) {
			$GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_civserv_accesslog',
												   array(	"al_service_uid"=>$service_uid,
												   			"tstamp" => $time,
												   			"al_number" => 1,
												   			"remote_addr" => $ip));
			$GLOBALS['TYPO3_DB']->exec_DELETEquery('tx_civserv_accesslog',
												   'TO_DAYS(tstamp) < TO_DAYS(FROM_UNIXTIME(' . $time . ')) - ' . $log_interval);

			$this->aggr_log();
		}
	}

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	function aggr_log (){
		$time = time();
		$aggr_result = $GLOBALS['TYPO3_DB']->exec_SELECTquery('al_service_uid, sum(al_number) as al_number, FROM_UNIXTIME( tstamp,\'%Y%m%d\') as tstamp',
															  'tx_civserv_accesslog',
															  'tstamp > 99999999 AND TO_DAYS(FROM_UNIXTIME(tstamp))< TO_DAYS(FROM_UNIXTIME('.$time.'))',
															  'al_service_uid, FROM_UNIXTIME( tstamp,\'%Y%m%d\') ',
														 	  '',
														 	  '');


		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($aggr_result)) {
			$GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_civserv_accesslog',
												   array(	"al_service_uid"=> $row['al_service_uid'],
												   			"tstamp" => $row['tstamp'],
												   			"al_number" => $row['al_number']));

		}

		$GLOBALS['TYPO3_DB']->exec_DELETEquery('tx_civserv_accesslog',
												'tstamp > 99999999 AND TO_DAYS(FROM_UNIXTIME(tstamp))< TO_DAYS(FROM_UNIXTIME('.$time.'))');

	}

}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/civserv/pi1/class.tx_civserv_accesslog.php']) {
	include_once ($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/civserv/pi1/class.tx_civserv_accesslog.php']);
}
?>