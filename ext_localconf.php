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
*
* Definition of some Typo Script Config which is set for special tables, pages and frontend-functions
* Definition of logical names for all used classes. the path of each class is assigned to a logical name, 
* which is used by Typo3 to address the class (for example in the tca.php). If a path changes, this is the 
* only part in the code, you have to update! 
* 
*
* @author Georg Niemeyer (niemeyer@uni-muenster.de),
* @author Tobias Müller (mullerto@uni-muenster.de),
* @author Maurits Hinzen (mhinzen@uni-muenster.de),
* @author Christoph Rosenkranz (rosenkra@uni-muenster.de),
* @package TYPO3
* @subpackage tx_civserv
* @version 1.0
*
* 
*/


if (!defined ("TYPO3_MODE")) 	die ("Access denied.");

t3lib_extMgm::addUserTSConfig('
	options.saveDocNew.tx_civserv_conf_mandant=1
');
t3lib_extMgm::addUserTSConfig('
	options.saveDocNew.tx_civserv_external_service=1
');
t3lib_extMgm::addUserTSConfig('
	options.saveDocNew.tx_civserv_region=1
');
t3lib_extMgm::addUserTSConfig('
	options.saveDocNew.tx_civserv_service=1
');
t3lib_extMgm::addUserTSConfig('
	options.saveDocNew.tx_civserv_model_service=1
');
t3lib_extMgm::addUserTSConfig('
	options.saveDocNew.tx_civserv_form=1
');
t3lib_extMgm::addUserTSConfig('
	options.saveDocNew.tx_civserv_building=1
');
t3lib_extMgm::addUserTSConfig('
	options.saveDocNew.tx_civserv_room=1
');
t3lib_extMgm::addUserTSConfig('
	options.saveDocNew.tx_civserv_floor=1
');
t3lib_extMgm::addUserTSConfig('
	options.saveDocNew.tx_civserv_employee=1
');
t3lib_extMgm::addUserTSConfig('
	options.saveDocNew.tx_civserv_organisation=1
');
t3lib_extMgm::addUserTSConfig('
	options.saveDocNew.tx_civserv_officehours=1
');
t3lib_extMgm::addUserTSConfig('
	options.saveDocNew.tx_civserv_search_call=1
');
t3lib_extMgm::addUserTSConfig('
	options.saveDocNew.tx_civserv_search_word=1
');
t3lib_extMgm::addUserTSConfig('
	options.saveDocNew.tx_civserv_position=1
');
t3lib_extMgm::addUserTSConfig('
	options.saveDocNew.tx_civserv_navigation=1
');
t3lib_extMgm::addUserTSConfig('
	options.saveDocNew.tx_civserv_category=1
');
t3lib_extMgm::addUserTSConfig('
	options.saveDocNew.tx_civserv_weekday=1
');
t3lib_extMgm::addUserTSConfig('
	options.saveDocNew.tx_civserv_building_bl_floor_mm=1
');
t3lib_extMgm::addUserTSConfig('
	options.disableDelete.tx_civserv_model_service_temp = 1
');

/**
* Disables "CURRENT VALUE IS NOT ..." label for
* technical display logic in
* tx_civserv_service.sv_logical_display
* (needed for hiding of other columns
* if model services or external services are selected) 
*/
t3lib_extMgm::addPageTSConfig('
	TCEFORM.tx_civserv_service.sv_logical_display.disableNoMatchingValueElement = 1
');

t3lib_extMgm::addPageTSConfig('
	TCEMAIN.clearCacheCmd = all
');

t3lib_extMgm::addPageTSConfig('
	TCEFORM.tx_civserv_model_service_temp.hidden.disabled = 1
');

t3lib_extMgm::addUserTSConfig('
	TCAdefaults.tx_civserv_model_service.hidden = 1
');


/**
 * Definition of own class files. To-Do: include file which again includes class-files
*/
$TYPO3_CONF_VARS["BE"]['XCLASS']['typo3/class.browse_links.php'] = t3lib_extMgm::extPath($_EXTKEY).'class.ux_browseLinks.php';
$TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/civserv/res/class.tx_civserv_floorbuild.php']=t3lib_extMgm::extPath($_EXTKEY).'res/class.tx_civserv_floorbuild.php';
$TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/civserv/res/class.tx_civserv_oepupdate.php']=t3lib_extMgm::extPath($_EXTKEY).'res/class.tx_civserv_oepupdate.php';
$TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/civserv/res/class.tx_civserv_mandant.php']=t3lib_extMgm::extPath($_EXTKEY).'res/class.tx_civserv_mandant.php';
$TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/civserv/res/class.tx_civserv_ms_maintenance.php']=t3lib_extMgm::extPath($_EXTKEY).'res/class.tx_civserv_ms_maintenance.php';
$TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/civserv/res/class.tx_civserv_commit.php']=t3lib_extMgm::extPath($_EXTKEY).'res/class.tx_civserv_commit.php';
$TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/civserv/res/class.tx_civserv_service_maintenance.php']=t3lib_extMgm::extPath($_EXTKEY).'res/class.tx_civserv_service_maintenance.php';
$TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/civserv/res/class.tx_civserv_weekday_maintenance.php']=t3lib_extMgm::extPath($_EXTKEY).'res/class.tx_civserv_weekday_maintenance.php';
$TYPO3_CONF_VARS['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['clearCachePostProc'][] = 'tx_civserv_commit->update_postAction'; //call user function
$TYPO3_CONF_VARS['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] = 'EXT:civserv/res/class.tx_civserv_commit.php:&tx_civserv_commit';
$TYPO3_CONF_VARS['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processCmdmapClass'][] = 'EXT:civserv/res/class.tx_civserv_commit.php:&tx_civserv_commit';
// these will only work if hook has been introduced to sources Typo3 4.0. manually!
$TYPO3_CONF_VARS['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['checkModifyAccessList'][] = 'EXT:civserv/res/class.tx_civserv_commit.php:&tx_civserv_commit';
$TYPO3_CONF_VARS['SC_OPTIONS']['typo3/class.db_list.inc']['makeQueryArray'][] = 'EXT:civserv/res/class.tx_civserv_commit.php:&tx_civserv_commit';



/**
* Extending TypoScript from static template uid=43 to set up userdefined tag (virtual civil services aka Virtuelle Verwaltung):
*/
t3lib_extMgm::addTypoScript($_EXTKEY,"editorcfg","
	tt_content.CSS_editor.ch.tx_civserv_pi1 = < plugin.tx_civserv_pi1.CSS_editor
",43);

t3lib_extMgm::addPItoST43($_EXTKEY,"pi1/class.tx_civserv_pi1.php","_pi1","list_type",1);

?>