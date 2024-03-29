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
* Configuration for the model service workflow
*
* $Id$
*
* @author Georg Niemeyer (niemeyer@uni-muenster.de),
* @author Tobias M�ller (mullerto@uni-muenster.de),
* @author Maurits Hinzen (mhinzen@uni-muenster.de),
* @package TYPO3
* @subpackage tx_civserv
* @version 1.0
*
* 
*/

	// DO NOT REMOVE OR CHANGE THESE 3 LINES:
define('TYPO3_MOD_PATH', '../typo3conf/ext/civserv/modmsworkflow/');
$BACK_PATH='../../../../typo3/';
$MCONF["name"]="web_txcivservmsworkflow";

	
$MCONF["access"]="user,group";
$MCONF["script"]="index.php";

$MLANG["default"]["tabs_images"]["tab"] = "moduleicon.gif";
$MLANG["default"]["ll_ref"]="LLL:EXT:civserv/modmsworkflow/locallang_mod.php";
?>