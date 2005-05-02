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
* This file holds the complete definition of own classes, all
* tables which are supposed to be contenttypes with there standard
* attributes and icon-files as well as definitions needed for the
* context sensitive help
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



/**
* Definition of own classes
*/
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/civserv/res/class.tx_civserv_floorbuild.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/civserv/res/class.tx_civserv_floorbuild.php']);
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/civserv/res/class.tx_civserv_oepupdate.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/civserv/res/class.tx_civserv_oepupdate.php']);
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/civserv/res/class.tx_civserv_mandant.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/civserv/res/class.tx_civserv_mandant.php']);
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/civserv/res/class.tx_civserv_ms_maintenance.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/civserv/res/class.tx_civserv_ms_maintenance.php']);
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/civserv/res/class.tx_civserv_commit.php']) {
	include_once ($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/civserv/res/class.tx_civserv_commit.php']);
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/civserv/res/class.tx_civserv_service_maintenance.php']) {
	include_once ($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/civserv/res/class.tx_civserv_service_maintenance.php']);
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/civserv/res/class.tx_civserv_weekday_maintenance.php']) {
	include_once ($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/civserv/res/class.tx_civserv_weekday_maintenance.php']);
}


if (TYPO3_MODE=='BE')	{
	// model service workflow
	t3lib_extMgm::addModule('web','txcivservmsworkflow','before:info',t3lib_extMgm::extPath($_EXTKEY).'modmsworkflow/');
	// cache all services
	t3lib_extMgm::addModule('tools','txcivservcacheservices','',t3lib_extMgm::extPath($_EXTKEY).'modcacheservices/');
}



/**
* Definition of plug-in "Virtual civil services" aka "Virtuelle Verwaltung".
* Needed for intergation in Frontend.
*/
t3lib_div::loadTCA("tt_content");
$TCA["tt_content"]["types"]["list"]["subtypes_excludelist"][$_EXTKEY."_pi1"]="layout,select_key";

t3lib_extMgm::addPlugin(Array("LLL:EXT:civserv/locallang_db.php:tt_content.list_type_pi1", $_EXTKEY."_pi1"),"list_type");

t3lib_extMgm::addStaticFile($_EXTKEY,"pi1/static/","Civil Services");



/**
* Definition of all tables which become contenttypes in the backend
*/
$TCA["tx_civserv_conf_mandant"] = Array (
	"ctrl" => Array (
		"title" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_conf_mandant",
		"label" => "cm_community_name",
		"tstamp" => "tstamp",
		"crdate" => "crdate",
		"cruser_id" => "cruser_id",
		"sortby" => "sorting",
		"delete" => "deleted",
		"enablecolumns" => Array (
			"disabled" => "hidden",
			"fe_group" => "fe_group",
		),
		"dynamicConfigFile" => t3lib_extMgm::extPath($_EXTKEY)."tca.php",
		"iconfile" => t3lib_extMgm::extRelPath($_EXTKEY)."icon_tx_civserv_mandant.gif",
	),
	"feInterface" => Array (
		"fe_admin_fieldList" => "hidden, fe_group, cm_community_name, cm_community_id, cm_uid, cm_circumstance_uid, cm_usergroup_uid, cm_organisation_uid, cm_service_folder_uid, cm_external_service_folder_uid, cm_model_service_temp_uid, cm_page_uid, cm_search_uid, cm_community_type, cm_target_email, cm_employeesearch",
	)
);


$TCA["tx_civserv_external_service"] = Array (
	"ctrl" => Array (
		"title" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_external_service",
		"label" => "es_name",
		"tstamp" => "tstamp",
		"crdate" => "crdate",
		"cruser_id" => "cruser_id",
		"sortby" => "sorting",
		"delete" => "deleted",
		"enablecolumns" => Array (
			"disabled" => "hidden",
			"fe_group" => "fe_group",
		),
		"dynamicConfigFile" => t3lib_extMgm::extPath($_EXTKEY)."tca.php",
		"iconfile" => t3lib_extMgm::extRelPath($_EXTKEY)."icon_tx_civserv_external_service.gif",
	),
	"feInterface" => Array (
		"fe_admin_fieldList" => "hidden, fe_group, es_external_service, es_name, es_navigation",
	)
);



$TCA["tx_civserv_region"] = Array (
	"ctrl" => Array (
		"title" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_region",
		"label" => "re_name",
		"tstamp" => "tstamp",
		"crdate" => "crdate",
		"cruser_id" => "cruser_id",
		"sortby" => "sorting",
		#"default_sortby" => "ORDER BY re_name",
		"delete" => "deleted",
		"enablecolumns" => Array (
			"disabled" => "hidden",
			"fe_group" => "fe_group",
		),
		"dynamicConfigFile" => t3lib_extMgm::extPath($_EXTKEY)."tca.php",
		"iconfile" => t3lib_extMgm::extRelPath($_EXTKEY)."icon_tx_civserv_region.gif",
	),
	"feInterface" => Array (
		"fe_admin_fieldList" => "hidden, fe_group, re_name",
	)
);


$TCA["tx_civserv_service"] = Array (
	"ctrl" => Array (
		"title" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_service",
		"label" => "sv_name",
		"requestUpdate" => "sv_region_checkbox,sv_model_service",
		"tstamp" => "tstamp",
		"crdate" => "crdate",
		"cruser_id" => "cruser_id",
		"sortby" => "sorting",
		#"default_sortby" => "ORDER BY sv_name",
		"delete" => "deleted",
		"enablecolumns" => Array (
			"disabled" => "hidden",
			"starttime" => "starttime",
			"endtime" => "endtime",
			"fe_group" => "fe_group",
		),
		"dynamicConfigFile" => t3lib_extMgm::extPath($_EXTKEY)."tca.php",
		"iconfile" => t3lib_extMgm::extRelPath($_EXTKEY)."icon_tx_civserv_service.gif",
	),
	"feInterface" => Array (
		"fe_admin_fieldList" => "hidden, starttime, endtime, fe_group, sv_name, sv_synonym1, sv_synonym2, sv_synonym3, sv_descr_short, sv_descr_long, sv_image, sv_image_text, sv_fees, sv_documents, sv_legal_local, sv_legal_global, sv_model_service, sv_similar_services, sv_service_version, sv_form, sv_searchword, sv_position, sv_organisation, sv_navigation, sv_region_checkbox, sv_region_link, sv_region_name",
	)
);


$TCA["tx_civserv_model_service"] = Array (
	"ctrl" => Array (
		"title" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_model_service",
		"label" => "ms_name",
		"tstamp" => "tstamp",
		"crdate" => "crdate",
		"cruser_id" => "cruser_id",
		"sortby" => "sorting",
		#"default_sortby" => "ORDER BY ms_name",
		"delete" => "deleted",
		"enablecolumns" => Array (
			"disabled" => "hidden",
			"fe_group" => "fe_group",
		),
		"dynamicConfigFile" => t3lib_extMgm::extPath($_EXTKEY)."tca.php",
		"iconfile" => t3lib_extMgm::extRelPath($_EXTKEY)."icon_tx_civserv_model_service.gif",
	),
	"feInterface" => Array (
		"fe_admin_fieldList" => "hidden, fe_group, ms_name, ms_synonym1, ms_synonym2, ms_synonym3, ms_descr_short, ms_descr_long, ms_image, ms_image_text, ms_fees, ms_documents, ms_legal_global, ms_searchword, ms_mandant, ms_approver_one, ms_approver_two",
	)
);


$TCA["tx_civserv_model_service_temp"] = Array (
	"ctrl" => Array (
		"title" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_model_service_temp",
		"label" => "ms_additional_label",
		"label_alt" => "ms_name",
		"label_alt_force" => 1,
		"tstamp" => "tstamp",
		"crdate" => "crdate",
		"cruser_id" => "cruser_id",
		"sortby" => "sorting",
		#"default_sortby" => "ORDER BY ms_name",
#		"delete" => "deleted",
		"enablecolumns" => Array (
			"disabled" => "hidden",
			"fe_group" => "fe_group",
		),
		"dynamicConfigFile" => t3lib_extMgm::extPath($_EXTKEY)."tca.php",
		"iconfile" => t3lib_extMgm::extRelPath($_EXTKEY)."icon_tx_civserv_model_service_temp.gif",
	)
);


$TCA["tx_civserv_form"] = Array (
	"ctrl" => Array (
		"title" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_form",
		"label" => "fo_name",
		"requestUpdate" => "fo_external_checkbox",
		"tstamp" => "tstamp",
		"crdate" => "crdate",
		"cruser_id" => "cruser_id",
		"sortby" => "sorting",
		#"default_sortby" => "ORDER BY fo_name",
		"delete" => "deleted",
		"enablecolumns" => Array (
			"disabled" => "hidden",
			"starttime" => "starttime",
			"endtime" => "endtime",
			"fe_group" => "fe_group",
		),
		"dynamicConfigFile" => t3lib_extMgm::extPath($_EXTKEY)."tca.php",
		"iconfile" => t3lib_extMgm::extRelPath($_EXTKEY)."icon_tx_civserv_form.gif",
	),
	"feInterface" => Array (
		"fe_admin_fieldList" => "hidden, starttime, endtime, fe_group, fo_number, fo_name, fo_descr, fo_external_checkbox, fo_url, fo_formular_file, fo_created_date, fo_status",
	)
);


$TCA["tx_civserv_building"] = Array (
	"ctrl" => Array (
		"title" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_building",
		"label" => "bl_name",
#		"requestUpdate" => "bl_floor",
		"tstamp" => "tstamp",
		"crdate" => "crdate",
		"cruser_id" => "cruser_id",
		"sortby" => "sorting",
		#"default_sortby" => "ORDER BY bl_name",
		"delete" => "deleted",
		"enablecolumns" => Array (
			"disabled" => "hidden",
			"fe_group" => "fe_group",
		),
		"dynamicConfigFile" => t3lib_extMgm::extPath($_EXTKEY)."tca.php",
		"iconfile" => t3lib_extMgm::extRelPath($_EXTKEY)."icon_tx_civserv_building.gif",
	),
	"feInterface" => Array (
		"fe_admin_fieldList" => "hidden, fe_group, bl_number, bl_name, bl_descr, bl_mail_street, bl_mail_pob, bl_mail_postcode, bl_mail_city, bl_building_street, bl_building_postcode, bl_building_city, bl_pubtrans_stop, bl_pubtrans_url, bl_image, bl_telephone, bl_fax, bl_email, bl_floor",
	)
);


/**
* Changes:
* 06.08.04, CR - hide ro_floor & ro_building
* in feInterface because they are not longer
* needed (done by rfb_building_bl_floor
*/
$TCA["tx_civserv_room"] = Array (
	"ctrl" => Array (
		"title" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_room",
		"label" => "ro_name",
		"tstamp" => "tstamp",
		"crdate" => "crdate",
		"cruser_id" => "cruser_id",
		"sortby" => "sorting",
		#"default_sortby" => "ORDER BY ro_name",
		"delete" => "deleted",
		"enablecolumns" => Array (
			"disabled" => "hidden",
			"fe_group" => "fe_group",
		),
		"dynamicConfigFile" => t3lib_extMgm::extPath($_EXTKEY)."tca.php",
		"iconfile" => t3lib_extMgm::extRelPath($_EXTKEY)."icon_tx_civserv_room.gif",
	),
	"feInterface" => Array (
		"fe_admin_fieldList" => "hidden, fe_group, ro_number, ro_name, ro_descr, ro_telephone, ro_fax, /*ro_floor, ro_building*/, rbf_building_bl_floor",
	)
);


$TCA["tx_civserv_floor"] = Array (
	"ctrl" => Array (
		"title" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_floor",
		"label" => "fl_descr",
		"tstamp" => "tstamp",
		"crdate" => "crdate",
		"cruser_id" => "cruser_id",
		"sortby" => "sorting",
		#"default_sortby" => "ORDER BY fl_descr",
		"delete" => "deleted",
		"enablecolumns" => Array (
			"disabled" => "hidden",
			"fe_group" => "fe_group",
		),
		"dynamicConfigFile" => t3lib_extMgm::extPath($_EXTKEY)."tca.php",
		"iconfile" => t3lib_extMgm::extRelPath($_EXTKEY)."icon_tx_civserv_floor.gif",
	),
	"feInterface" => Array (
		"fe_admin_fieldList" => "hidden, fe_group, fl_number, fl_descr",
	)
);


$TCA["tx_civserv_employee"] = Array (
	"ctrl" => Array (
		"title" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_employee",
		"label" => "em_name",
		"label_alt" => "em_firstname, uid",
		"label_alt_force" => 1,
		"requestUpdate" => "em_position",
		"tstamp" => "tstamp",
		"crdate" => "crdate",
		"cruser_id" => "cruser_id",
		"sortby" => "sorting",
		#"default_sortby" => "ORDER BY em_name",
		"delete" => "deleted",
		"enablecolumns" => Array (
			"disabled" => "hidden",
			"fe_group" => "fe_group",
		),
		"dynamicConfigFile" => t3lib_extMgm::extPath($_EXTKEY)."tca.php",
		"iconfile" => t3lib_extMgm::extRelPath($_EXTKEY)."icon_tx_civserv_employee.gif",
	),
	"feInterface" => Array (
		"fe_admin_fieldList" => "hidden, fe_group, em_number, em_address, em_title, em_name, em_firstname, em_telephone, em_fax, em_mobile, em_email, em_image, em_datasec, em_hours, em_position",
	)
);


$TCA["tx_civserv_organisation"] = Array (
	"ctrl" => Array (
		"title" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_organisation",
		"label" => "or_name",
		"tstamp" => "tstamp",
		"crdate" => "crdate",
		"cruser_id" => "cruser_id",
		"sortby" => "sorting",
		"delete" => "deleted",
		"enablecolumns" => Array (
			"disabled" => "hidden",
			"fe_group" => "fe_group",
		),
		"dynamicConfigFile" => t3lib_extMgm::extPath($_EXTKEY)."tca.php",
		"iconfile" => t3lib_extMgm::extRelPath($_EXTKEY)."icon_tx_civserv_organisation.gif",
	),
	"feInterface" => Array (
		"fe_admin_fieldList" => "hidden, fe_group, or_number, or_name, or_hours, or_telephone, or_fax, or_email, or_image, or_infopage, or_addinfo, or_structure, or_building",
	)
);


$TCA["tx_civserv_officehours"] = Array (
	"ctrl" => Array (
		"title" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_officehours",
		"label" => "oh_descr",
		"requestUpdate" => "oh_manual_checkbox",
		#"label_alt" => "oh_start_morning, oh_end_morning, oh_start_afternoon, oh_end_afternoon,",
		"label_alt" => "oh_name",
		"label_alt_force" => 1,
		"tstamp" => "tstamp",
		"crdate" => "crdate",
		"cruser_id" => "cruser_id",
		"sortby" => "sorting",
		#"default_sortby" => "ORDER BY oh_weekday",
		"delete" => "deleted",
		"enablecolumns" => Array (
			"disabled" => "hidden",
			"fe_group" => "fe_group",
		),
		"dynamicConfigFile" => t3lib_extMgm::extPath($_EXTKEY)."tca.php",
		"iconfile" => t3lib_extMgm::extRelPath($_EXTKEY)."icon_tx_civserv_officehours.gif",
	),
	"feInterface" => Array (
		"fe_admin_fieldList" => "hidden, fe_group, oh_descr, oh_manual_checkbox, oh_start_morning, oh_end_morning, oh_start_afternoon, oh_end_afternoon, oh_weekday, oh_freestyle",
	)
);


$TCA["tx_civserv_search_word"] = Array (
	"ctrl" => Array (
		"title" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_search_word",
		"label" => "sw_search_word",
		"tstamp" => "tstamp",
		"crdate" => "crdate",
		"cruser_id" => "cruser_id",
		"sortby" => "sorting",
		#"default_sortby" => "ORDER BY sw_search_word",
		"delete" => "deleted",
		"enablecolumns" => Array (
			"disabled" => "hidden",
			"fe_group" => "fe_group",
		),
		"dynamicConfigFile" => t3lib_extMgm::extPath($_EXTKEY)."tca.php",
		"iconfile" => t3lib_extMgm::extRelPath($_EXTKEY)."icon_tx_civserv_search_word.gif",
	),
	"feInterface" => Array (
		"fe_admin_fieldList" => "hidden, fe_group, sw_search_word",
	)
);


$TCA["tx_civserv_position"] = Array (
	"ctrl" => Array (
		"title" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_position",
		"label" => "po_name",
		"tstamp" => "tstamp",
		"crdate" => "crdate",
		"cruser_id" => "cruser_id",
		"sortby" => "sorting",
		#"default_sortby" => "ORDER BY po_name",
		"delete" => "deleted",
		"enablecolumns" => Array (
			"disabled" => "hidden",
			"fe_group" => "fe_group",
		),
		"dynamicConfigFile" => t3lib_extMgm::extPath($_EXTKEY)."tca.php",
		"iconfile" => t3lib_extMgm::extRelPath($_EXTKEY)."icon_tx_civserv_position.gif",
	),
	"feInterface" => Array (
		"fe_admin_fieldList" => "hidden, fe_group, po_name, po_descr, po_organisation, po_mandant",
	)
);


$TCA["tx_civserv_navigation"] = Array (
	"ctrl" => Array (
		"title" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_navigation",
		"label" => "nv_name",
		"tstamp" => "tstamp",
		"crdate" => "crdate",
		"cruser_id" => "cruser_id",
		"sortby" => "sorting",
		#"default_sortby" => "ORDER BY nv_name",
		"delete" => "deleted",
		"enablecolumns" => Array (
			"disabled" => "hidden",
			"fe_group" => "fe_group",
		),
		"dynamicConfigFile" => t3lib_extMgm::extPath($_EXTKEY)."tca.php",
		"iconfile" => t3lib_extMgm::extRelPath($_EXTKEY)."icon_tx_civserv_navigation.gif",
	),
	"feInterface" => Array (
		"fe_admin_fieldList" => "hidden, fe_group, nv_name, nv_structure",
	)
);

/*
$TCA["tx_civserv_building_bl_floor_mm"] = Array (
	"ctrl" => Array (
		"title" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_building_bl_floor_mm",
		"label" => "uid_local",
		"label_alt" => "uid_foreign",
		"label_alt_force" => 1,
		"tstamp" => "tstamp",
		"crdate" => "crdate",
		"cruser_id" => "cruser_id",
		"sortby" => "sorting",
		#"default_sortby" => "ORDER BY uid_local",
		"delete" => "deleted",
		"enablecolumns" => Array (
			"disabled" => "hidden",
			"fe_group" => "fe_group",
		),
		"dynamicConfigFile" => t3lib_extMgm::extPath($_EXTKEY)."tca.php",
		"iconfile" => t3lib_extMgm::extRelPath($_EXTKEY)."icon_tx_civserv_building_bl_floor_mm.gif",
	),
	"feInterface" => Array (
		"fe_admin_fieldList" => "hidden, fe_group, uid_local, uid_foreign",
	)
);
*/

$TCA["tx_civserv_employee_em_position_mm"] = Array (
	"ctrl" => Array (
		"title" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_employee_em_position_mm",
		"label" => "ep_label",
		"tstamp" => "tstamp",
		"crdate" => "crdate",
		"cruser_id" => "cruser_id",
		"sortby" => "sorting",
		#"default_sortby" => "ORDER BY ep_label",
		"delete" => "deleted",
		"enablecolumns" => Array (
			"disabled" => "hidden",
			"fe_group" => "fe_group",
		),
		"dynamicConfigFile" => t3lib_extMgm::extPath($_EXTKEY)."tca.php",
		"iconfile" => t3lib_extMgm::extRelPath($_EXTKEY)."icon_tx_civserv_employee_em_position_mm.gif",
	),
	"feInterface" => Array (
		"fe_admin_fieldList" => "hidden, fe_group, uid_local, uid_foreign, ep_officehours, ep_room, ep_telephone, ep_fax, ep_mobile, ep_email",
	)
);


$TCA["tx_civserv_service_sv_position_mm"] = Array (
	"ctrl" => Array (
		"title" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_service_sv_position_mm",
		"label" => "sp_label",
		"tstamp" => "tstamp",
		"crdate" => "crdate",
		"cruser_id" => "cruser_id",
		"sortby" => "sorting",
		#"default_sortby" => "ORDER BY sp_label",
		"enablecolumns" => Array (
			"disabled" => "hidden",
			"fe_group" => "fe_group",
		),
		"dynamicConfigFile" => t3lib_extMgm::extPath($_EXTKEY)."tca.php",
		"iconfile" => t3lib_extMgm::extRelPath($_EXTKEY)."icon_tx_civserv_service_sv_position_mm.gif",
	),
	"feInterface" => Array (
		"fe_admin_fieldList" => "hidden, fe_group, uid_local, uid_foreign, sp_descr",
	)
);


/**
* The following lines are needed for implementing the context sensitive help (CSH) for all masks in the backend
*/
t3lib_extMgm::addLLrefForTCAdescr('tx_civserv_building','EXT:civserv/CSH/locallang_csh_building.php');
t3lib_extMgm::addLLrefForTCAdescr('tx_civserv_employee_em_position_mm','EXT:civserv/CSH/locallang_csh_employee_em_position_mm.php');
t3lib_extMgm::addLLrefForTCAdescr('tx_civserv_employee','EXT:civserv/CSH/locallang_csh_employee.php');
t3lib_extMgm::addLLrefForTCAdescr('tx_civserv_floor','EXT:civserv/CSH/locallang_csh_floor.php');
t3lib_extMgm::addLLrefForTCAdescr('tx_civserv_form','EXT:civserv/CSH/locallang_csh_form.php');
t3lib_extMgm::addLLrefForTCAdescr('tx_civserv_model_service','EXT:civserv/CSH/locallang_csh_model_service.php');
t3lib_extMgm::addLLrefForTCAdescr('tx_civserv_navigation','EXT:civserv/CSH/locallang_csh_navigation.php');
t3lib_extMgm::addLLrefForTCAdescr('tx_civserv_organisation','EXT:civserv/CSH/locallang_csh_organisation.php');
t3lib_extMgm::addLLrefForTCAdescr('tx_civserv_search_word','EXT:civserv/CSH/locallang_csh_search_word.php');
t3lib_extMgm::addLLrefForTCAdescr('tx_civserv_service_sv_position_mm','EXT:civserv/CSH/locallang_csh_service_sv_position_mm.php');
t3lib_extMgm::addLLrefForTCAdescr('tx_civserv_service','EXT:civserv/CSH/locallang_csh_service.php');
/**
* END of CSH-definitions
*/

?>