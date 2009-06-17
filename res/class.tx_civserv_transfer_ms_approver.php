<?php
/***************************************************************
* Copyright notice
*
* (c) 2006 citeq (osiris@citeq.de)
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
* This class has been introduced with typo3 4.x because a problem arose with mm-relations
* this class could possibly be made redundant by IRRE
* - function is called form tca.php in case of totally new records (tca.php takes care that relations to other records can only be added when the base-record has been saved once i.e. has a proper id)
* - function generates msg to BE-user, informing him, that he must save base record (tx_civserv_service) before he can make any relations to other records
* look at typo3 CORE API chapter 4 -> userFunc for further information
*
* Some scripts that use this class: ?
* Depends on: ?
*
*
* @author Britta Kohorst (kohorst@citeq.de)
* @package TYPO3
* @subpackage tx_civserv
* @version 1.0
*
* Changes: Datum, Initialen - vorgenommene �nderungen
*/

// ATTENTION: 
// Diese Klasse wird von der Typo3 core (t3lib_div->callUserFunction) nicht gezogen, obwohl sie in ext_tables.php und in ext_localconf.php eingebunden ist!!!!
// Die Funktionalit�t befindet sich deshalb in class.tx_civserv_user_be_msg.php

class tx_civserv_transfer_ms_approver {
	function transfer_approver_pages2modelservice(&$PA, &$fobj) {
//		[...] see: class.tx_civserv_user_be_msg.php
	}

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/civserv/res/class.tx_civserv_transfer_ms_approver.php']) {
	include_once ($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/civserv/res/class.tx_civserv_transfer_ms_approver.php']);
}

?>