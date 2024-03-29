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
* This file holds the complete definition of the appearance of the tables and
* contenttypes in the backend from Typo3 for the extension "Virtual civil Service".
* Some aditional logic is implemented to hold different mandants in one installation
*
*
* @author Georg Niemeyer (niemeyer@uni-muenster.de),
* @author Tobias M�ller (mullerto@uni-muenster.de),
* @author Maurits Hinzen (mhinzen@uni-muenster.de),
* @author Christoph Rosenkranz (rosenkra@uni-muenster.de),
* @package TYPO3
* @subpackage tx_civserv
* @version 1.0
*
* 
*/
/**
 * [TABLE INDEX of SCRIPT]
 *
 * tx_civserv_service
 * tx_civserv_model_service
 * tx_civserv_form
 * tx_civserv_building
 * tx_civserv_room
 * tx_civserv_floor
 * tx_civserv_employee
 * tx_civserv_organisation
 * tx_civserv_officehours
 * tx_civserv_search_word
 * tx_civserv_position
 * tx_civserv_navigation
 * tx_civserv_building_bl_floor_mm
 * tx_civserv_employee_em_position_mm
 * tx_civserv_service_sv_position_mm
 * tx_civserv_model_service_temp
 * tx_civserv_region
 * tx_civserv_external_service
 * tx_civserv_conf_mandant
 *
 * TOTAL TABLES: 19
 *
 */




/**
 * This code reads out the folders, where images for model services, formulars, services,
 * employees and buildings should be saved in the filesystem.
 *
 * For model services, the path for uploads is read from the table tx_civserv_configuration
 *
 * For formulars, services, employees and buildings the general path for uploads is set to "fileadmin/civserv/".$mandantID.
 * $mandantID is the number of the actuell mandant, read from tx_civserv_mandant. In the TCA definitions (see below)
 * for formulars, services, employees and buildings the special folders like "/images" or "/forms" etc. are added to the
 * general path for a mandant, that is generated here.
 */
if (!defined ("TYPO3_MODE")) 	die ("Access denied.");

	if (TYPO3_MODE=='BE'){
		$mandant_conf = array();
		$mandantID = "";
		$uploadfolder = "";
		$cm_circumstance = 0;
		$cm_usergroup = 0;
		$cm_organisation = 0;
	
	
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'cf_value',			 							// SELECT ...
			'tx_civserv_configuration',						// FROM ...
			'cf_key = "model_service_image_folder"',		// AND title LIKE "%blabla%"', // WHERE...
			'', 											// GROUP BY...
			'',   											// ORDER BY...
			'' 												// LIMIT to 10 rows, starting with number 5 (MySQL compat.)
		);

		$model_service_image_folder = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
		$model_service_folder = $model_service_image_folder['cf_value'];

		$current_id=t3lib_div::_GET('id');
		if ($current_id == null){
			$url=parse_url(t3lib_div::_GET('returnUrl'));
			parse_str($url['query'], $url_query);
		    $current_id = $url_query['id'];
		}
		
		// citeq introduced this, can't remember why?
		// there are cases, when the returnURL does not reveal the current id.......
		if ($current_id == null){
			$current_id = $GLOBALS['WEBMOUNTS'][0];
		}
		
		if ($current_id > 0){
			$mandant_obj=t3lib_div::makeInstance('tx_civserv_mandant');
			$mandant_conf = $mandant_obj->get_mandant_conf_all($current_id);
			#$mandantID = $mandant_obj->get_mandant($current_id);
			$mandantID = $mandant_conf['cm_community_id'];
			if ($mandantID) {
				$upload_folder = "fileadmin/civserv/".$mandantID;
			} else {
				$upload_folder = "fileadmin/civserv";
			}
			if($mandant_conf['cm_circumstance_uid'] > 0){
				$cm_circumstance = $mandant_conf['cm_circumstance_uid'];
			}
			if($mandant_conf['cm_usergroup_uid'] > 0){
				$cm_usergroup = $mandant_conf['cm_usergroup_uid'];
			}
			if($mandant_conf['cm_organisation_uid'] > 0){
				$cm_organisation = $mandant_conf['cm_organisation_uid'];
			}
		} else $upload_folder = "fileadmin/civserv";
	}



/**
 * The definition of the backend-mask and logic for the table tx_civserv_service (contenttype service)
 * All labels are defined in civserv/locallang_db.php
 *
 * Relations to other tables: tx_civserv_model_service, tx_civserv_service_sv_similar_services_mm, tx_civserv_form,
 *	tx_civserv_service_sv_form_mm, tx_civserv_search_word, tx_civserv_service_sv_searchword_mm, tx_civserv_position,
 *	tx_civserv_service_sv_position_mm, tx_civserv_organisation, tx_civserv_service_sv_organisation_mm, tx_civserv_navigation,
 *	tx_civserv_service_sv_navigation_mm, tx_civserv_region
 *
 * Wizards (for more navigation comfort): 	class.tx_civserv_wizard_modelservice.php
 *											class.tx_civserv_wizard_service_similar_services.php
 *											class.tx_civserv_wizard_service_form.php
 *											class.tx_civserv_wizard_service_searchword.php
 *											class.tx_civserv_wizard_service_position.php
 *
 * Chekcboxes / Displayconditions:			sv_3rdparty_link is only displayed in the backend, if an external service should be integrated (sv_3rdparty_checkbox=true)
 *											if sv_model_service=true, only field which don't base on a model service are displayed
 *
 * Uploadfolder for images: "".$upload_folder."/images"
 *
 * itemsProcFunc: tx_civserv_mandant->limit_items (to display only data in selectorfields that is affected for the actual mandant)
 *
 * further information see below and Typo3 Core API, ext_tables.php, ext_tables.sql, ext_localconf.php
 */
$TCA["tx_civserv_service"] = Array (
	"ctrl" => $TCA["tx_civserv_service"]["ctrl"],
	"interface" => Array (
		"showRecordFieldList" => "hidden,starttime,endtime,fe_group,sv_name,sv_synonym1,sv_synonym2,sv_synonym3,sv_descr_short,sv_descr_long,sv_image,sv_image_text,sv_fees,sv_documents,sv_legal_local,sv_legal_global,sv_model_service,sv_similar_services,sv_service_version,sv_form,sv_searchword,sv_position,sv_organisation,sv_navigation,sv_region"
	),
	"feInterface" => $TCA["tx_civserv_service"]["feInterface"],
	"columns" => Array (
		"hidden" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:lang/locallang_general.php:LGL.hidden",
			"config" => Array (
				"type" => "check",
				"default" => "0"
			)
		),
		"starttime" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:lang/locallang_general.php:LGL.starttime",
			"config" => Array (
				"type" => "input",
				"size" => "8",
				"max" => "20",
				"eval" => "date",
				"default" => "0",
				"checkbox" => "0"
			)
		),
		"endtime" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:lang/locallang_general.php:LGL.endtime",
			"config" => Array (
				"type" => "input",
				"size" => "8",
				"max" => "20",
				"eval" => "date",
				"checkbox" => "0",
				"default" => "0",
				"range" => Array (
					"upper" => mktime(0,0,0,12,31,2020),
					"lower" => mktime(0,0,0,date("m")-1,date("d"),date("Y"))
				)
			)
		),
		"fe_group" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:lang/locallang_general.php:LGL.fe_group",
			"config" => Array (
				"type" => "select",
				"items" => Array (
					Array("", 0),
					Array("LLL:EXT:lang/locallang_general.php:LGL.hide_at_login", -1),
					Array("LLL:EXT:lang/locallang_general.php:LGL.any_login", -2),
					Array("LLL:EXT:lang/locallang_general.php:LGL.usergroups", "--div--")
				),
				"foreign_table" => "fe_groups"
			)
		),
		"sv_type" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_service.sv_type",
			"config" => Array (
				"type" => "check",
			)
		),
/*	
// no good for our purposes, but this is how you would have to configure Radio-Buttons	
		"sv_type" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_service.sv_type",
			"config" => Array (
				"type" => "radio",
				'items' => Array (
						Array('LLL:EXT:civserv/locallang_db.php:tx_civserv_service.sv_globalnet', '0'),
						Array('LLL:EXT:civserv/locallang_db.php:tx_civserv_service.sv_internet', '1'),
						Array('LLL:EXT:civserv/locallang_db.php:tx_civserv_service.sv_intranet', '2'),
				),
			)
		),
*/		
		"sv_model_service" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_service.sv_model_service",
			"config" => Array (
				"type" => "group",
				"internal_type" => "db",
				"size" => 1,
				"allowed" => "tx_civserv_model_service",
				"show_thumbs" => 0,
				"minitems" => 0,
				"maxitems" => 1,
                "wizards" => Array(
					"_PADDING" => 2,
					"_VERTICAL" => 1,
					'modelservice' => Array(
							'type' => 'popup',
					        'title' => 'LLL:EXT:civserv/res/locallang_wizard.php:tx_civserv_wizard_modelservice.title',
       						'script' => 'EXT:civserv/res/class.tx_civserv_wizard_modelservice.php',
							'icon' => 'list.gif',
							'JSopenParams' => 'height=350,width=800,status=0,menubar=0,resizable=1,location=0',
							),
				),
			),
		),
		"sv_name" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_service.sv_name",
			"config" => Array (
				"type" => "input",
				"size" => "80",
				"max" => "255",
				"eval" => "trim, required",
			)
		),
		"sv_synonym1" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_service.sv_synonym1",
			"config" => Array (
				"type" => "input",
				"size" => "80",
				"max" => "255",
			)
		),
		"sv_synonym2" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_service.sv_synonym2",
			"config" => Array (
				"type" => "input",
				"size" => "80",
				"max" => "255",
			)
		),
		"sv_synonym3" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_service.sv_synonym3",
			"config" => Array (
				"type" => "input",
				"size" => "80",
				"max" => "255",
			)
		),
		"sv_3rdparty_checkbox" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_service.sv_3rdparty_checkbox",
			"displayCond" => "FIELD:sv_model_service:REQ:false",
			"config" => Array (
				"type" => "check",
			)
		),
		"sv_3rdparty_link" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_service.sv_3rdparty_link",
			"displayCond" => "FIELD:sv_3rdparty_checkbox:REQ:true",
			"config" => Array (
				"type" => "input",
				"size" => "15",
				"max" => "255",
				"checkbox" => "",
				"eval" => "trim",
				"wizards" => Array(
					"_PADDING" => 2,
					"link" => Array(
						"type" => "popup",
						"title" => "Link",
						"icon" => "link_popup.gif",
						"script" => "browse_links.php?mode=wizard",
						"JSopenParams" => "height=300,width=500,status=0,menubar=0,scrollbars=1"
					)
				)
			)
		),
		"sv_3rdparty_name" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_service.sv_3rdparty_name",
			"displayCond" => "FIELD:sv_3rdparty_checkbox:REQ:true",
			"config" => Array (
				"type" => "input",
				"size" => "30",
				"max" => "255",
			)
		),
		"sv_descr_short" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_service.sv_descr_short",
			"displayCond" => "FIELD:sv_model_service:REQ:false",
			"config" => Array (
				"type" => "text",
				"cols" => "10",
				"rows" => "2",
				"wizards" => Array(
					"_PADDING" => 2,
					"RTE" => Array(
						"notNewRecords" => 1,
						"RTEonly" => 1,
						"type" => "script",
						"title" => "Full screen Rich Text Editing|Formatteret redigering i hele vinduet",
						"icon" => "wizard_rte2.gif",
						"script" => "wizard_rte.php",
					),
				),
			)
		),
		"sv_descr_long" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_service.sv_descr_long",
			"config" => Array (
				"type" => "text",
				"cols" => "30",
				"rows" => "5",
				"wizards" => Array(
					"_PADDING" => 2,
					"RTE" => Array(
						"notNewRecords" => 1,
						"RTEonly" => 1,
						"type" => "script",
						"title" => "Full screen Rich Text Editing|Formatteret redigering i hele vinduet",
						"icon" => "wizard_rte2.gif",
						"script" => "wizard_rte.php",
					),
				),
			)
		),
		"sv_image" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_service.sv_image",
			"config" => Array (
				"type" => "group",
				"internal_type" => "file",
				"allowed" => "gif,png,jpeg,jpg",
				"max_size" => 500,
				"uploadfolder" => "".$upload_folder."/images",
				"show_thumbs" => 1,
				"size" => 1,
				"minitems" => 0,
				"maxitems" => 1,
			)
		),
		"sv_image_text" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_service.sv_image_text",
			"config" => Array (
				"type" => "input",
				"size" => "30",
				"max" => "255",
			)
		),
		"sv_fees" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_service.sv_fees",
			"displayCond" => "FIELD:sv_model_service:REQ:false",
			"config" => Array (
				"type" => "text",
				"cols" => "30",
				"rows" => "5",
				"wizards" => Array(
					"_PADDING" => 2,
					"RTE" => Array(
						"notNewRecords" => 1,
						"RTEonly" => 1,
						"type" => "script",
						"title" => "Full screen Rich Text Editing|Formatteret redigering i hele vinduet",
						"icon" => "wizard_rte2.gif",
						"script" => "wizard_rte.php",
					),
				),
			)
		),
		"sv_documents" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_service.sv_documents",
			"displayCond" => "FIELD:sv_model_service:REQ:false",
			"config" => Array (
				"type" => "text",
				"cols" => "30",
				"rows" => "5",
				"wizards" => Array(
					"_PADDING" => 2,
					"RTE" => Array(
						"notNewRecords" => 1,
						"RTEonly" => 1,
						"type" => "script",
						"title" => "Full screen Rich Text Editing|Formatteret redigering i hele vinduet",
						"icon" => "wizard_rte2.gif",
						"script" => "wizard_rte.php",
					),
				),
			)
		),
		"sv_legal_local" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_service.sv_legal_local",
			"config" => Array (
				"type" => "text",
				"cols" => "30",
				"rows" => "5",
				"wizards" => Array(
					"_PADDING" => 2,
					"RTE" => Array(
						"notNewRecords" => 1,
						"RTEonly" => 1,
						"type" => "script",
						"title" => "Full screen Rich Text Editing|Formatteret redigering i hele vinduet",
						"icon" => "wizard_rte2.gif",
						"script" => "wizard_rte.php",
					),
				),
			)
		),
		"sv_legal_global" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_service.sv_legal_global",
			"displayCond" => "FIELD:sv_model_service:REQ:false",
			"config" => Array (
				"type" => "text",
				"cols" => "30",
				"rows" => "5",
				"wizards" => Array(
					"_PADDING" => 2,
					"RTE" => Array(
						"notNewRecords" => 1,
						"RTEonly" => 1,
						"type" => "script",
						"title" => "Full screen Rich Text Editing|Formatteret redigering i hele vinduet",
						"icon" => "wizard_rte2.gif",
						"script" => "wizard_rte.php",
					),
				),
			)
		),
		"sv_similar_services_PLACEHOLDER" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_service.sv_similar_services_PLACEHOLDER",
			"dbField" => "sv_similar_services", //custom info passed on to $PA in userFunc
			"displayCond" => "REC:NEW:true",
			"config" => Array (
				"type" => "user",
				"userFunc" => "tx_civserv_user_be_msg->user_TCAform_test"
			)
		),
		"sv_similar_services" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_service.sv_similar_services",
			"displayCond" => "REC:NEW:false",
			"config" => Array (
				"type" => "group",
				"internal_type" => "db",
				"size" => 5,
				"itemListStyle" => "width:50em", 
				"selectedListStyle" => "width:50em",
				"allowed" => "tx_civserv_service",
				"show_thumbs" => 0,
				"minitems" => 0,
				"maxitems" => 50,
				"MM" => "tx_civserv_service_sv_similar_services_mm",
                "wizards" => Array(
					"_PADDING" => 2,
					"_VERTICAL" => 1,
					'similarservices' => Array(
							'type' => 'popup',
					        'title' => 'LLL:EXT:civserv/res/locallang_wizard.php:tx_civserv_wizard_service_similar_services.title',
       						'script' => 'EXT:civserv/res/class.tx_civserv_wizard_service_similar_services.php',
							'icon' => 'list.gif',
							'JSopenParams' => 'height=350,width=600,status=0,menubar=0,resizable=1,location=0',
					),
				),
			)
		),
		"sv_form_PLACEHOLDER" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_service.sv_form_PLACEHOLDER",
			"dbField" => "sv_form", //custom info passed on to $PA in userFunc
			"displayCond" => "REC:NEW:true",
			"config" => Array (
				"type" => "user",
				"userFunc" => "tx_civserv_user_be_msg->user_TCAform_test"
			)
		),
		"sv_form" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_service.sv_form",
			"displayCond" => "REC:NEW:false",
			"config" => Array (
				"type" => "group",
				"internal_type" => "db",
				"size" => 5,
				"itemListStyle" => "width:50em", 
				"selectedListStyle" => "width:50em",
				"allowed" => "tx_civserv_form",
				"show_thumbs" => 0,
				"minitems" => 0,
				"maxitems" => 50,
				"MM" => "tx_civserv_service_sv_form_mm",
                "wizards" => Array(
					"_PADDING" => 2,
					"_VERTICAL" => 1,
					'serviceform' => Array(
							'type' => 'popup',
					        'title' => 'LLL:EXT:civserv/res/locallang_wizard.php:tx_civserv_wizard_service_form.title',
       						'script' => 'EXT:civserv/res/class.tx_civserv_wizard_service_form.php',
							'icon' => 'EXT:civserv/list_form_abc_framed.gif',
							'JSopenParams' => 'height=350,width=600,status=0,menubar=0,resizable=1,location=0',
					),
					'serviceform_category' => Array(
							'type' => 'popup',
					        'title' => 'LLL:EXT:civserv/res/locallang_wizard.php:tx_civserv_wizard_service_form_category.title',
       						'script' => 'EXT:civserv/res/class.tx_civserv_wizard_service_form_category.php',
							'icon' => 'EXT:civserv/list_form_cat_framed.gif',
							'JSopenParams' => 'height=350,width=600,status=0,menubar=0,resizable=1,location=0',
					),
				),
			)
		),
		"sv_searchword_PLACEHOLDER" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_service.sv_searchword_PLACEHOLDER",
			"dbField" => "sv_searchword", //custom info passed on to $PA in userFunc
			"displayCond" => "REC:NEW:true",
			"config" => Array (
				"type" => "user",
				"userFunc" => "tx_civserv_user_be_msg->user_TCAform_test"
			)
		),
		"sv_searchword" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_service.sv_searchword",
			"displayCond" => "REC:NEW:false",
			"config" => Array (
				"type" => "group",
				"internal_type" => "db",
				"size" => 5,
				"allowed" => "tx_civserv_search_word",
				"show_thumbs" => 0,
				"minitems" => 0,
				"maxitems" => 50,
				"MM" => "tx_civserv_service_sv_searchword_mm",
                "wizards" => Array(
					"_PADDING" => 2,
					"_VERTICAL" => 1,
					'servicessearchword' => Array(
							'type' => 'popup',
					        'title' => 'LLL:EXT:civserv/res/locallang_wizard.php:tx_civserv_wizard_service_searchword.title',
       						'script' => 'EXT:civserv/res/class.tx_civserv_wizard_service_searchword.php',
							'icon' => 'list.gif',
							'JSopenParams' => 'height=350,width=600,status=0,menubar=0,resizable=1,location=0',
					),
				),
			)
		),
		"sv_position_PLACEHOLDER" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_service.sv_position_PLACEHOLDER",
			"dbField" => "sv_position", //custom info passed on to $PA in userFunc
			"displayCond" => "REC:NEW:true",
			"config" => Array (
				"type" => "user",
				"userFunc" => "tx_civserv_user_be_msg->user_TCAform_test"
			)
		),
		"sv_position" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_service.sv_position",
			"displayCond" => "REC:NEW:false",
			"config" => Array (
				"type" => "group",
				"internal_type" => "db",
				"size" => 5,
				"allowed" => "tx_civserv_position",
				"show_thumbs" => 0,
				"minitems" => 0,
				"maxitems" => 50,
				"MM" => "tx_civserv_service_sv_position_mm",
                "wizards" => Array(
					"_PADDING" => 2,
					"_VERTICAL" => 1,
					'serviceform' => Array(
							'type' => 'popup',
					        'title' => 'LLL:EXT:civserv/res/locallang_wizard.php:tx_civserv_wizard_service_position.title',
       						'script' => 'EXT:civserv/res/class.tx_civserv_wizard_service_position.php',
							'icon' => 'EXT:civserv/list_position_framed.gif',
							'JSopenParams' => 'height=350,width=600,status=0,menubar=0,resizable=1,location=0',
					),
					'serviceform_em_name' => Array(
							'type' => 'popup',
					        'title' => 'LLL:EXT:civserv/res/locallang_wizard.php:tx_civserv_wizard_service_position_em_name.title',
       						'script' => 'EXT:civserv/res/class.tx_civserv_wizard_service_position_em_name.php',
							'icon' => 'EXT:civserv/list_employee_framed.gif',
							'JSopenParams' => 'height=350,width=600,status=0,menubar=0,resizable=1,location=0',
					),
					'serviceform_limited_sv_organisation' => Array(
							'type' => 'popup',
					        'title' => 'LLL:EXT:civserv/res/locallang_wizard.php:tx_civserv_wizard_service_position_limited_sv_organisation.title',
       						'script' => 'EXT:civserv/res/class.tx_civserv_wizard_service_position_limited_sv_organisation.php',
							'icon' => 'EXT:civserv/list_organisation_framed.gif',
							'description' => 'LLL:EXT:civserv/res/locallang_wizard.php:tx_civserv_wizard_service_position_limited_sv_organisation.title',
							'JSopenParams' => 'height=350,width=600,status=0,menubar=0,resizable=1,location=0',
					),
					'serviceform_information' => Array(
							'type' => 'popup',
					        'title' => 'LLL:EXT:civserv/res/locallang_wizard.php:tx_civserv_wizard_service_position_information.title',
       						'script' => 'EXT:civserv/res/class.tx_civserv_wizard_service_position_information.php',
							'icon' => 'icon_note.gif',
							'JSopenParams' => 'height=350,width=600,status=0,menubar=0,resizable=1,location=0',
					),
				),
			)
		),
		"sv_organisation_PLACEHOLDER" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_service.sv_organisation_PLACEHOLDER",
			"dbField" => "sv_organisation", //custom info passed on to $PA in userFunc
			"displayCond" => "REC:NEW:true",
			"config" => Array (
				"type" => "user",
				"userFunc" => "tx_civserv_user_be_msg->user_TCAform_test"
			)
		),
		"sv_organisation" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_service.sv_organisation",
			"displayCond" => "REC:NEW:false",
			"config" => Array (
				"type" => "group",
				"internal_type" => "db",
				"size" => 5,
				"allowed" => "tx_civserv_organisation",
				"show_thumbs" => 0,
				"minitems" => 0,
				"maxitems" => 50,
				"MM" => "tx_civserv_service_sv_organisation_mm",
				 "wizards" => Array(
					"_PADDING" => 2,
					"_VERTICAL" => 1,
					'serviceform' => Array(
							'type' => 'popup',
					        'title' => 'LLL:EXT:civserv/res/locallang_wizard.php:tx_civserv_wizard_service_organisation.title',
       						'script' => 'EXT:civserv/res/class.tx_civserv_wizard_service_organisation.php',
							'icon' => 'list.gif',
							'JSopenParams' => 'height=350,width=600,status=0,menubar=0,resizable=1,location=0',
					),
				),
			)
		),
		"sv_navigation_PLACEHOLDER" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_service.sv_navigation_PLACEHOLDER",
			"dbField" => "sv_navigation", //custom info passed on to $PA in userFunc
			"displayCond" => "REC:NEW:true",
			"config" => Array (
				"type" => "user",
				"userFunc" => "tx_civserv_user_be_msg->user_TCAform_test"
			)
		),
		"sv_navigation" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_service.sv_navigation",
			"displayCond" => "REC:NEW:false",
			"config" => Array (
				"type" => "select",
				"foreign_table" => "tx_civserv_navigation",
#				"foreign_table_where" => "ORDER BY tx_civserv_navigation.nv_name",
				"foreign_table_where" => "AND tx_civserv_navigation.uid NOT IN (".$cm_circumstance.", ".$cm_usergroup.") ORDER BY tx_civserv_navigation.nv_name",
				"itemsProcFunc" => "tx_civserv_mandant->limit_items",
				"size" => 5,
				"minitems" => 0,
				"maxitems" => 50,
				"MM" => "tx_civserv_service_sv_navigation_mm",
			)
		),
		"sv_region_PLACEHOLDER_live" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_service.sv_region_PLACEHOLDER_live",
			"dbField" => "sv_region_live", //custom info passed on to $PA in userFunc, in this case its a fake field
			"displayCond" => "REC:NEW:true",
			"config" => Array (
				"type" => "user",
				"userFunc" => "tx_civserv_user_be_msg->user_TCAform_test"
			)
		),
		/*
		"sv_region_PLACEHOLDER_ws" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_service.sv_region_ws",
			"dbField" => "sv_region_ws", //custom info passed on to $PA in userFunc, in this case its a fake field
			"displayCond" => "FIELD:t3ver_wsid:>:0",
			"config" => Array (
				"type" => "user",
				"userFunc" => "tx_civserv_user_be_msg->user_TCAform_test"
			)
		),
		*/
		"sv_region" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_service.sv_region",
			"displayCond" => "REC:NEW:false",  //for LIVE workspace, see below!
			#"displayCond" => "VERSION:IS:false", // for custom workspaces -but doesn't work? see below
			#"displayCond" => "FIELD:t3ver_wsid:=:0",	// do not show region field for versioned records!
			"config" => Array (
				"type" => "select",
				"foreign_table" => "tx_civserv_region",
				"foreign_table_where" => "ORDER BY tx_civserv_region.re_name",
				"itemsProcFunc" => "tx_civserv_mandant->limit_region_items",
				"size" => 5,
				"minitems" => 0,
				"maxitems" => 50,
				"MM" => "tx_civserv_service_sv_region_mm",
			)
		),
	),
	"types" => Array (
#	to enable checkbox for intranet add 'sv_type' to show-item-array (ATTENTION: intranet feature is not yet fully implemented)	
#		"0" => Array("showitem" => "hidden;;1;;1-1-1, sv_type, sv_model_service, sv_name, sv_synonym1, [....]
		"0" => Array("showitem" => "hidden;;1;;1-1-1, sv_model_service,  sv_name, sv_synonym1, sv_synonym2, sv_synonym3, sv_3rdparty_checkbox, sv_3rdparty_link, sv_3rdparty_name, sv_descr_short;;;richtext[paste|copy|bold|italic|underline|formatblock|class|left|center|right|orderedlist|unorderedlist|outdent|indent|link|image]:rte_transform[mode=ts], sv_descr_long;;;richtext[paste|copy|bold|italic|underline|formatblock|class|left|center|right|orderedlist|unorderedlist|outdent|indent|link|image]:rte_transform[mode=ts], sv_image, sv_image_text, sv_fees;;;richtext[paste|copy|bold|italic|underline|formatblock|class|left|center|right|orderedlist|unorderedlist|outdent|indent|link|image]:rte_transform[mode=ts], sv_documents;;;richtext[paste|copy|bold|italic|underline|formatblock|class|left|center|right|orderedlist|unorderedlist|outdent|indent|link|image]:rte_transform[mode=ts], sv_legal_local;;;richtext[paste|copy|bold|italic|underline|formatblock|class|left|center|right|orderedlist|unorderedlist|outdent|indent|link|image]:rte_transform[mode=ts], sv_legal_global;;;richtext[paste|copy|bold|italic|underline|formatblock|class|left|center|right|orderedlist|unorderedlist|outdent|indent|link|image]:rte_transform[mode=ts], sv_similar_services_PLACEHOLDER, sv_similar_services, sv_service_version, sv_form_PLACEHOLDER, sv_form, sv_searchword_PLACEHOLDER, sv_searchword, sv_position_PLACEHOLDER, sv_position, sv_organisation_PLACEHOLDER, sv_organisation, sv_navigation_PLACEHOLDER, sv_navigation, sv_region_PLACEHOLDER_live, sv_region")
	),
	"palettes" => Array (
		"1" => Array("showitem" => "starttime, endtime, fe_group")
#		"1" => Array("showitem" => "starttime, endtime")
	)
);

#	debug($TCA['tx_civserv_service']['columns'], 'tx_civserv_service columns');
#	debug($TCA["tx_civserv_service"], 'tx_civserv_service');
#	debug($GLOBALS['BE_USER']->user['workspace_id'], 'TCA be_user workspace_id');

/*
if($GLOBALS['BE_USER']->user['workspace_id']==0) { //LIVE-Workspace!!!!
	$TCA['tx_civserv_service']['columns']['sv_region']['displayCond']="REC:NEW:false"; 
}
*/
#	debug($TCA['tx_civserv_service']['columns'], 'TCA tx_civserv_service->columns');



/**
 * The definition of the backend-mask and logic for the table tx_civserv_model_service (contenttype model service)
 * All labels are defined in civserv/locallang_db.php
 *
 * Relations to other tables: tx_civserv_search_word
 *
 * Wizards (for more navigation comfort): 	class.tx_civserv_wizard_service_searchword.php
 *
 * Uploadfolder for images: "".$model_service_folder
 *
 * itemsProcFunc: tx_civserv_ms_maintenance->show_mandants (to display all mandants from the system in the selectorboxes)
 *
 * further information see below and Typo3 Core API, ext_tables.php, ext_tables.sql, ext_localconf.php
 */
$TCA["tx_civserv_model_service"] = Array (
	"ctrl" => $TCA["tx_civserv_model_service"]["ctrl"],
	"interface" => Array (
		"showRecordFieldList" => "hidden,fe_group,ms_name,ms_synonym1,ms_synonym2,ms_synonym3,ms_descr_short,ms_descr_long,ms_image,ms_image_text,ms_fees,ms_documents,ms_legal_global,ms_searchword"
	),
	"feInterface" => $TCA["tx_civserv_model_service"]["feInterface"],
	"columns" => Array (
		"hidden" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:lang/locallang_general.php:LGL.hidden",
			"config" => Array (
				"type" => "check",
				"default" => "0"
			)
		),
		"fe_group" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:lang/locallang_general.php:LGL.fe_group",
			"config" => Array (
				"type" => "select",
				"items" => Array (
					Array("", 0),
					Array("LLL:EXT:lang/locallang_general.php:LGL.hide_at_login", -1),
					Array("LLL:EXT:lang/locallang_general.php:LGL.any_login", -2),
					Array("LLL:EXT:lang/locallang_general.php:LGL.usergroups", "--div--")
				),
				"foreign_table" => "fe_groups"
			)
		),
		"ms_name" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_model_service.ms_name",
			"config" => Array (
				"type" => "input",
				"size" => "30",
				"max" => "255",
				"eval" => "required",
			)
		),
		"ms_synonym1" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_model_service.ms_synonym1",
			"config" => Array (
				"type" => "input",
				"size" => "30",
				"max" => "255",
			)
		),
		"ms_synonym2" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_model_service.ms_synonym2",
			"config" => Array (
				"type" => "input",
				"size" => "30",
				"max" => "255",
			)
		),
		"ms_synonym3" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_model_service.ms_synonym3",
			"config" => Array (
				"type" => "input",
				"size" => "30",
				"max" => "255",
			)
		),
		"ms_descr_short" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_model_service.ms_descr_short",
			"config" => Array (
				"type" => "text",
				"cols" => "30",
				"rows" => "5",
				"wizards" => Array(
					"_PADDING" => 2,
					"RTE" => Array(
						"notNewRecords" => 1,
						"RTEonly" => 1,
						"type" => "script",
						"title" => "Full screen Rich Text Editing|Formatteret redigering i hele vinduet",
						"icon" => "wizard_rte2.gif",
						"script" => "wizard_rte.php",
					),
				),
			)
		),
		"ms_descr_long" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_model_service.ms_descr_long",
			"config" => Array (
				"type" => "text",
				"cols" => "30",
				"rows" => "5",
				"wizards" => Array(
					"_PADDING" => 2,
					"RTE" => Array(
						"notNewRecords" => 1,
						"RTEonly" => 1,
						"type" => "script",
						"title" => "Full screen Rich Text Editing|Formatteret redigering i hele vinduet",
						"icon" => "wizard_rte2.gif",
						"script" => "wizard_rte.php",
					),
				),
			)
		),
		"ms_image" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_model_service.ms_image",
			"config" => Array (
				"type" => "group",
				"internal_type" => "file",
				"allowed" => "gif,png,jpeg,jpg",
				"max_size" => 500,
				"uploadfolder" => "".$model_service_folder,
				"show_thumbs" => 1,
				"size" => 1,
				"minitems" => 0,
				"maxitems" => 1,
			)
		),
		"ms_image_text" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_model_service.ms_image_text",
			"config" => Array (
				"type" => "input",
				"size" => "30",
				"max" => "255",
			)
		),
		"ms_fees" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_model_service.ms_fees",
			"config" => Array (
				"type" => "text",
				"cols" => "30",
				"rows" => "5",
				"wizards" => Array(
					"_PADDING" => 2,
					"RTE" => Array(
						"notNewRecords" => 1,
						"RTEonly" => 1,
						"type" => "script",
						"title" => "Full screen Rich Text Editing|Formatteret redigering i hele vinduet",
						"icon" => "wizard_rte2.gif",
						"script" => "wizard_rte.php",
					),
				),
			)
		),
		"ms_documents" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_model_service.ms_documents",
			"config" => Array (
				"type" => "text",
				"cols" => "30",
				"rows" => "5",
				"wizards" => Array(
					"_PADDING" => 2,
					"RTE" => Array(
						"notNewRecords" => 1,
						"RTEonly" => 1,
						"type" => "script",
						"title" => "Full screen Rich Text Editing|Formatteret redigering i hele vinduet",
						"icon" => "wizard_rte2.gif",
						"script" => "wizard_rte.php",
					),
				),
			)
		),
		"ms_legal_global" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_model_service.ms_legal_global",
			"config" => Array (
				"type" => "text",
				"cols" => "30",
				"rows" => "5",
				"wizards" => Array(
					"_PADDING" => 2,
					"RTE" => Array(
						"notNewRecords" => 1,
						"RTEonly" => 1,
						"type" => "script",
						"title" => "Full screen Rich Text Editing|Formatteret redigering i hele vinduet",
						"icon" => "wizard_rte2.gif",
						"script" => "wizard_rte.php",
					),
				),
			)
		),
		"ms_searchword" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_model_service.ms_searchword",
			"config" => Array (
				"type" => "group",
				"internal_type" => "db",
				"size" => 5,
				"allowed" => "tx_civserv_search_word",
				"show_thumbs" => 0,
				"minitems" => 0,
				"maxitems" => 50,
                "wizards" => Array(
					"_PADDING" => 2,
					"_VERTICAL" => 1,
					'modelservicessearchword' => Array(
							'type' => 'popup',
					        'title' => 'LLL:EXT:civserv/res/locallang_wizard.php:tx_civserv_wizard_service_searchword.title',
       						'script' => 'EXT:civserv/res/class.tx_civserv_wizard_service_searchword.php',
							'icon' => 'list.gif',
							'JSopenParams' => 'height=350,width=600,status=0,menubar=0,resizable=1,location=0',
					),
				),

			)
		),
// can't get userfunc to work. Typo3 mopes about wrong prefix - although the class and function are prepended with user_ or tx_ respectively
// also the re-edited 'INSIDE TYPO3' for typo3 4.1.1 is awkwardly silent about 'userfunc'.....		
// but Typo3 Core Documentation still contains paragraph about TCA type => "user"
		"ms_mandant" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_model_service.ms_mandant",
			"config" => Array (
				"type" => "user",
#				"userfunc" => "tx_civserv_transfer_ms_approver->user_transfer_approver_pages2modelservice"	//functionsname kommt gar nicht erst bei t3lib_div->callUserFunction an!
#				"userFunc" => "tx_civserv_user_be_msg->user_TCAform_test"									//die Klasse und Methode gabs vorher schon, Aufruf funktioniert
#				"userFunc" => "tx_civserv_user_be_msg->user_transfer_approver_pages2modelservice"			//funktionsname kommt an, aber kann die dazugeh�rige funktion nicht finden!
				"userFunc" => "tx_civserv_user_be_msg->user_TCAform_test2",
				"eval" => "required"
			)
		),
		"ms_approver_one" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_model_service.ms_approver_one",
			"config" => Array (
				"type" => "user",
				"userFunc" => "tx_civserv_user_be_msg->user_TCAform_test2",
				"eval" => "required" //no use

			)
		),
		"ms_approver_two" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_model_service.ms_approver_two",
			"config" => Array (
				"type" => "user",
				"userFunc" => "tx_civserv_user_be_msg->user_TCAform_test2",
				"eval" => "required" //now use
			)
		),
	),
	"types" => Array (
		"0" => Array("showitem" => "hidden;;1;;1-1-1, ms_name, ms_synonym1, ms_synonym2, ms_synonym3, 
									ms_descr_short;;;richtext[paste|copy|bold|italic|underline|formatblock|class|left|center|right|orderedlist|unorderedlist|outdent|indent|link|image]:rte_transform[mode=ts], 
									ms_descr_long;;;richtext[paste|copy|bold|italic|underline|formatblock|class|left|center|right|orderedlist|unorderedlist|outdent|indent|link|image]:rte_transform[mode=ts], 
									ms_image, ms_image_text, 
									ms_fees;;;richtext[paste|copy|bold|italic|underline|formatblock|class|left|center|right|orderedlist|unorderedlist|outdent|indent|link|image]:rte_transform[mode=ts], 
									ms_documents;;;richtext[paste|copy|bold|italic|underline|formatblock|class|left|center|right|orderedlist|unorderedlist|outdent|indent|link|image]:rte_transform[mode=ts], 
									ms_legal_global;;;richtext[paste|copy|bold|italic|underline|formatblock|class|left|center|right|orderedlist|unorderedlist|outdent|indent|link|image]:rte_transform[mode=ts], 
									ms_searchword, 
									ms_mandant,  
									ms_approver_one,  
									ms_approver_two")
	),
	"palettes" => Array (
#		"1" => Array("showitem" => "fe_group")
		"1" => Array("showitem" => "")
	)
);


/**
 * The definition of the backend-mask and logic for the table tx_civserv_form (contenttype forms)
 * All labels are defined in civserv/locallang_db.php
 *
 * Chekcboxes / Displayconditions:			fo_url is only visible, if fo_external_checkbox=true, otherwise you have to upload a formular into the filesystem. In the frontend external forms are only showed, if fo_external_checkbox=true, else the form from the filesystem will be displayed!
 *
 * Uploadfolder for forms: "".$upload_folder."/forms"
 *
 * further information see below and Typo3 Core API, ext_tables.php, ext_tables.sql, ext_localconf.php
 */
$TCA["tx_civserv_form"] = Array (
	"ctrl" => $TCA["tx_civserv_form"]["ctrl"],
	"interface" => Array (
		"showRecordFieldList" => "hidden,starttime,endtime,fe_group,fo_number,fo_name,fo_descr,fo_external_checkbox, fo_url,fo_formular_file,fo_created_date,fo_status,fo_target"
	),
	"feInterface" => $TCA["tx_civserv_form"]["feInterface"],
	"columns" => Array (
		"hidden" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:lang/locallang_general.php:LGL.hidden",
			"config" => Array (
				"type" => "check",
				"default" => "0"
			)
		),
		"starttime" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:lang/locallang_general.php:LGL.starttime",
			"config" => Array (
				"type" => "input",
				"size" => "8",
				"max" => "20",
				"eval" => "date",
				"default" => "0",
				"checkbox" => "0"
			)
		),
		"endtime" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:lang/locallang_general.php:LGL.endtime",
			"config" => Array (
				"type" => "input",
				"size" => "8",
				"max" => "20",
				"eval" => "date",
				"checkbox" => "0",
				"default" => "0",
				"range" => Array (
					"upper" => mktime(0,0,0,12,31,2020),
					"lower" => mktime(0,0,0,date("m")-1,date("d"),date("Y"))
				)
			)
		),
		"fe_group" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:lang/locallang_general.php:LGL.fe_group",
			"config" => Array (
				"type" => "select",
				"items" => Array (
					Array("", 0),
					Array("LLL:EXT:lang/locallang_general.php:LGL.hide_at_login", -1),
					Array("LLL:EXT:lang/locallang_general.php:LGL.any_login", -2),
					Array("LLL:EXT:lang/locallang_general.php:LGL.usergroups", "--div--")
				),
				"foreign_table" => "fe_groups"
			)
		),
		"fo_number" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_form.fo_number",
			"config" => Array (
				"type" => "input",
				"size" => "30",
				"max" => "255",
			)
		),
		"fo_orga_code" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_form.fo_orga_code",
			"config" => Array (
				"type" => "input",
				"size" => "30",
				"max" => "255",
			)
		),
		"fo_codename" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_form.fo_codename",
			"config" => Array (
				"type" => "input",
				"size" => "30",
				"max" => "255",
			)
		),
		"fo_name" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_form.fo_name",
			"config" => Array (
				"type" => "input",
				"size" => "30",
				"max" => "255",
				"eval" => "trim, required",
			)
		),
		"fo_descr" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_form.fo_descr",
			"config" => Array (
				"type" => "text",
				"cols" => "30",
				"rows" => "5",
				"wizards" => Array(
					"_PADDING" => 2,
					"RTE" => Array(
						"notNewRecords" => 1,
						"RTEonly" => 1,
						"type" => "script",
						"title" => "Full screen Rich Text Editing|Formatteret redigering i hele vinduet",
						"icon" => "wizard_rte2.gif",
						"script" => "wizard_rte.php",
					),
				),
			)
		),
		"fo_category" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_form.fo_category",
			"config" => Array (
				"type" => "select",
				"foreign_table" => "tx_civserv_category",
				"foreign_table_where" => "ORDER BY tx_civserv_category.ca_name",
				"itemsProcFunc" => "tx_civserv_mandant->limit_items",
				"size" => 5,
				"minitems" => 0,
				"maxitems" => 50,
				"MM" => "tx_civserv_form_fo_category_mm",
			)
		),
		"fo_external_checkbox" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_form.fo_external_checkbox",
			"config" => Array (
				"type" => "check",
			)
		),
		"fo_url" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_form.fo_url",
			"displayCond" => "FIELD:fo_external_checkbox:REQ:true",
			"config" => Array (
				"type" => "input",
				"size" => "15",
				"max" => "255",
				"checkbox" => "",
				"eval" => "trim",
				"wizards" => Array(
					"_PADDING" => 2,
					"link" => Array(
						"type" => "popup",
						"title" => "Link",
						"icon" => "link_popup.gif",
						"script" => "browse_links.php?mode=wizard",
						"JSopenParams" => "height=300,width=500,status=0,menubar=0,scrollbars=1"
					)
				)
			)
		),
		"fo_formular_file" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_form.fo_formular_file",
			"displayCond" => "FIELD:fo_external_checkbox:REQ:false",
			"config" => Array (
				"type" => "group",
				"internal_type" => "file",
				"allowed" => "pdf,html,txt,doc",
				"max_size" => 40000,
				"uploadfolder" => "".$upload_folder."/forms",
				"show_thumbs" => 1,
				"size" => 1,
				"minitems" => 0,
				"maxitems" => 1,
			)
		),
		"fo_created_date" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_form.fo_created_date",
			"config" => Array (
				"type" => "input",
				"size" => "12",
				"max" => "20",
				"eval" => "datetime",
				"checkbox" => "0",
				"default" => "0"
			)
		),
		"fo_status" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_form.fo_status",
			"config" => Array (
				"type" => "radio",
				"items" => Array (
					Array("LLL:EXT:civserv/locallang_db.php:tx_civserv_form.fo_status.I.0", "0"),
					Array("LLL:EXT:civserv/locallang_db.php:tx_civserv_form.fo_status.I.1", "1"),
					Array("LLL:EXT:civserv/locallang_db.php:tx_civserv_form.fo_status.I.2", "2"),
					Array("LLL:EXT:civserv/locallang_db.php:tx_civserv_form.fo_status.I.3", "3"),
				),
			)
		),
		"fo_target" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_form.fo_target",
			"config" => Array (
				"type" => "check",
			)
		),
	),
	"types" => Array (
		"0" => Array("showitem" => "hidden;;1;;1-1-1, fo_number, fo_orga_code, fo_codename, fo_name, fo_descr;;;richtext[paste|copy|bold|italic|underline|formatblock|class|left|center|right|orderedlist|unorderedlist|outdent|indent|link|image]:rte_transform[mode=ts], fo_category, fo_external_checkbox, fo_url, fo_formular_file, fo_created_date, fo_status,fo_target")
	),
	"palettes" => Array (
#		"1" => Array("showitem" => "starttime, endtime, fe_group")
		"1" => Array("showitem" => "starttime, endtime")
	)
);



/**
 * The definition of the backend-mask and logic for the table tx_civserv_building (contenttype building)
 * All labels are defined in civserv/locallang_db.php
 *
 * Relations to other tables: tx_civserv_building_bl_floor_mm, tx_civserv_floor
 *
 * Uploadfolder for images: "".$upload_folder."/images"
 *
 * itemsProcFunc: tx_civserv_mandant->limit_items (to display only data in selectorfields that is affected for the actual mandant)
 *
 * further information see below and Typo3 Core API, ext_tables.php, ext_tables.sql, ext_localconf.php
 */
$TCA["tx_civserv_building"] = Array (
	"ctrl" => $TCA["tx_civserv_building"]["ctrl"],
	"interface" => Array (
		"showRecordFieldList" => "hidden,fe_group,bl_number,bl_name,bl_descr,bl_mail_street,bl_mail_pob,bl_mail_postcode,bl_mail_city,bl_building_street,bl_building_postcode,bl_building_city,bl_pubtrans_stop,bl_pubtrans_url,bl_image,bl_telephone,bl_fax,bl_email,bl_floor"
	),
	"feInterface" => $TCA["tx_civserv_building"]["feInterface"],
	"columns" => Array (
		"hidden" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:lang/locallang_general.php:LGL.hidden",
			"config" => Array (
				"type" => "check",
				"default" => "0"
			)
		),
		"fe_group" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:lang/locallang_general.php:LGL.fe_group",
			"config" => Array (
				"type" => "select",
				"items" => Array (
					Array("", 0),
					Array("LLL:EXT:lang/locallang_general.php:LGL.hide_at_login", -1),
					Array("LLL:EXT:lang/locallang_general.php:LGL.any_login", -2),
					Array("LLL:EXT:lang/locallang_general.php:LGL.usergroups", "--div--")
				),
				"foreign_table" => "fe_groups"
			)
		),
		"bl_number" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_building.bl_number",
			"config" => Array (
				"type" => "input",
				"size" => "30",
				"max" => "255",
			)
		),
		"bl_name_to_show" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_building.bl_name_to_show",
			"config" => Array (
				"type" => "input",
				"size" => "30",
				"max" => "255",
			)
		),
		"bl_name" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_building.bl_name",
			"config" => Array (
				"type" => "input",
				"size" => "30",
				"max" => "255",
				"eval" => "required",
			)
		),
		"bl_descr" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_building.bl_descr",
			"config" => Array (
				"type" => "text",
				"cols" => "30",
				"rows" => "5",
			)
		),
		"bl_mail_street" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_building.bl_mail_street",
			"config" => Array (
				"type" => "input",
				"size" => "30",
				"max" => "255",
				#"eval" => "required",
			)
		),
		"bl_mail_pob" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_building.bl_mail_pob",
			"config" => Array (
				"type" => "input",
				"size" => "30",
				"max" => "255",
			)
		),
		"bl_mail_postcode" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_building.bl_mail_postcode",
			"config" => Array (
				"type" => "input",
				"size" => "30",
				"max" => "5",
			)
		),
		"bl_mail_city" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_building.bl_mail_city",
			"config" => Array (
				"type" => "input",
				"size" => "30",
				"max" => "255",
			)
		),
		"bl_building_street" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_building.bl_building_street",
			"config" => Array (
				"type" => "input",
				"size" => "30",
				"max" => "255",
				"eval" => "required",
			)
		),
		"bl_building_postcode" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_building.bl_building_postcode",
			"config" => Array (
				"type" => "input",
				"size" => "30",
				"max" => "5",
			)
		),
		"bl_building_city" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_building.bl_building_city",
			"config" => Array (
				"type" => "input",
				"size" => "30",
				"max" => "255",
			)
		),
		"bl_pubtrans_stop" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_building.bl_pubtrans_stop",
			"config" => Array (
				"type" => "input",
				"size" => "30",
				"max" => "255",
			)
		),
		"bl_pubtrans_url" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_building.bl_pubtrans_url",
			"config" => Array (
				"type" => "input",
				"size" => "15",
				"max" => "255",
				"checkbox" => "",
				"eval" => "trim",
				"wizards" => Array(
					"_PADDING" => 2,
					"link" => Array(
						"type" => "popup",
						"title" => "Link",
						"icon" => "link_popup.gif",
						"script" => "browse_links.php?mode=wizard",
						"JSopenParams" => "height=300,width=500,status=0,menubar=0,scrollbars=1"
					)
				)
			)
		),
		"bl_citymap_url" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_building.bl_citymap_url",
			"config" => Array (
				"type" => "input",
				"size" => "15",
				"max" => "255",
				"checkbox" => "",
				"eval" => "trim",
				"wizards" => Array(
					"_PADDING" => 2,
					"link" => Array(
						"type" => "popup",
						"title" => "Link",
						"icon" => "link_popup.gif",
						"script" => "browse_links.php?mode=wizard",
						"JSopenParams" => "height=300,width=500,status=0,menubar=0,scrollbars=1"
					)
				)
			)
		),
		"bl_image" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_building.bl_image",
			"config" => Array (
				"type" => "group",
				"internal_type" => "file",
				"allowed" => "gif,png,jpeg,jpg",
				"max_size" => 500,
				"uploadfolder" => "".$upload_folder."/images",
				"show_thumbs" => 1,
				"size" => 1,
				"minitems" => 0,
				"maxitems" => 1,
			)
		),
		"bl_telephone" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_building.bl_telephone",
			"config" => Array (
				"type" => "input",
				"size" => "30",
				"max" => "30",
			)
		),
		"bl_fax" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_building.bl_fax",
			"config" => Array (
				"type" => "input",
				"size" => "30",
				"max" => "30",
			)
		),
		"bl_email" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_building.bl_email",
			"config" => Array (
				"type" => "input",
				"size" => "48",
			)
		),
		"bl_floor_PLACEHOLDER" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_building.bl_floor_PLACEHOLDER",
			"dbField" => "bl_floor", //custom info passed on to $PA in userFunc
			"displayCond" => "REC:NEW:true",
			"config" => Array (
				"type" => "user",
				"userFunc" => "tx_civserv_user_be_msg->user_TCAform_test"
			)
		),
		"bl_floor" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_building.bl_floor",
			"displayCond" => "REC:NEW:false",
			"config" => Array (
				"type" => "select",
				"foreign_table" => "tx_civserv_floor",
				"foreign_table_where" => "ORDER BY tx_civserv_floor.uid",
				"itemsProcFunc" => "tx_civserv_mandant->limit_items",
				"size" => 5,
				"minitems" => 0,
				"maxitems" => 50,
				"MM" => "tx_civserv_building_bl_floor_mm",
				"wizards" => Array(
					"_PADDING" => 2,
					"_VERTICAL" => 1,
					"add" => Array(
						"type" => "script",
						"title" => "Create new record",
						"icon" => "add.gif",
						"params" => Array(
							"table"=>"tx_civserv_floor",
							"pid" => "###CURRENT_PID###",
							"setValue" => "prepend"
						),
						"script" => "wizard_add.php",
					),
				),
			)
		),
	),
	"types" => Array (
		"0" => Array("showitem" => "hidden;;1;;1-1-1, bl_number, bl_name_to_show, bl_name, bl_descr, bl_mail_street, bl_mail_pob, bl_mail_postcode, bl_mail_city, bl_building_street, bl_building_postcode, bl_building_city, bl_pubtrans_stop, bl_pubtrans_url, bl_citymap_url, bl_image, bl_telephone, bl_fax, bl_email, bl_floor_PLACEHOLDER, bl_floor")
	),
	"palettes" => Array (
#		"1" => Array("showitem" => "fe_group")
		"1" => Array("showitem" => "")
	)
);


/**
 * The definition of the backend-mask and logic for the table tx_civserv_room (contenttype room)
 * All labels are defined in civserv/locallang_db.php
 *
 * itemsProcFunc: tx_civserv_floorbuild->main (to get the valid floor-building-combinations for this mandant and display them in the selectorbox, that way a real MM-Relation-table can be referenced and used!)
 *
 * further information see below and Typo3 Core API, ext_tables.php, ext_tables.sql, ext_localconf.php
 */
$TCA["tx_civserv_room"] = Array (
	"ctrl" => $TCA["tx_civserv_room"]["ctrl"],
	"interface" => Array (
		"showRecordFieldList" => "hidden,fe_group,ro_number,ro_name,ro_descr,ro_telephone,ro_fax,rbf_building_bl_floor"
	),
	"feInterface" => $TCA["tx_civserv_room"]["feInterface"],
	"columns" => Array (
		"hidden" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:lang/locallang_general.php:LGL.hidden",
			"config" => Array (
				"type" => "check",
				"default" => "0"
			)
		),
		"fe_group" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:lang/locallang_general.php:LGL.fe_group",
			"config" => Array (
				"type" => "select",
				"items" => Array (
					Array("", 0),
					Array("LLL:EXT:lang/locallang_general.php:LGL.hide_at_login", -1),
					Array("LLL:EXT:lang/locallang_general.php:LGL.any_login", -2),
					Array("LLL:EXT:lang/locallang_general.php:LGL.usergroups", "--div--")
				),
				"foreign_table" => "fe_groups"
			)
		),
		"ro_number" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_room.ro_number",
			"config" => Array (
				"type" => "input",
				"size" => "30",
				"max" => "255",
			)
		),
		"ro_name" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_room.ro_name",
			"config" => Array (
				"type" => "input",
				"size" => "30",
				"max" => "255",
				"eval" => "required",
			)
		),
		"ro_descr" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_room.ro_descr",
			"config" => Array (
				"type" => "text",
				"cols" => "30",
				"rows" => "5",
				"wizards" => Array(
					"_PADDING" => 2,
					"RTE" => Array(
						"notNewRecords" => 1,
						"RTEonly" => 1,
						"type" => "script",
						"title" => "Full screen Rich Text Editing|Formatteret redigering i hele vinduet",
						"icon" => "wizard_rte2.gif",
						"script" => "wizard_rte.php",
					),
				),
			)
		),
		"ro_telephone" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_room.ro_telephone",
			"config" => Array (
				"type" => "input",
				"size" => "30",
				"max" => "30",
			)
		),
		"ro_fax" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_room.ro_fax",
			"config" => Array (
				"type" => "input",
				"size" => "30",
				"max" => "30",
			)
		),
		"rbf_building_bl_floor" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_room.rbf_building_bl_floor",
			"config" => Array (
				"type" => "select",
                "itemsProcFunc" => "tx_civserv_floorbuild->main",
			)
		),
		"rbf_label" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_room.ro_label",
			"config" => Array (
				"type" => "input",
				"size" => "30",
				"max" => "255",
				"eval" => "readonly",
			)
		),
	),
	"types" => Array (
		"0" => Array("showitem" => "hidden;;1;;1-1-1, ro_number, ro_name, ro_descr;;;richtext[paste|copy|bold|italic|underline|formatblock|class|left|center|right|orderedlist|unorderedlist|outdent|indent|link|image]:rte_transform[mode=ts], ro_telephone, ro_fax, rbf_building_bl_floor")
	),
	"palettes" => Array (
#		"1" => Array("showitem" => "fe_group")
		"1" => Array("showitem" => "")
	)
);



/**
 * The definition of the backend-mask and logic for the table tx_civserv_floor (contenttype floor)
 * All labels are defined in civserv/locallang_db.php
 *
 * further information see below and Typo3 Core API, ext_tables.php, ext_tables.sql, ext_localconf.php
 */
$TCA["tx_civserv_floor"] = Array (
	"ctrl" => $TCA["tx_civserv_floor"]["ctrl"],
	"interface" => Array (
		"showRecordFieldList" => "hidden,fe_group,fl_number,fl_descr"
	),
	"feInterface" => $TCA["tx_civserv_floor"]["feInterface"],
	"columns" => Array (
		"hidden" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:lang/locallang_general.php:LGL.hidden",
			"config" => Array (
				"type" => "check",
				"default" => "0"
			)
		),
		"fe_group" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:lang/locallang_general.php:LGL.fe_group",
			"config" => Array (
				"type" => "select",
				"items" => Array (
					Array("", 0),
					Array("LLL:EXT:lang/locallang_general.php:LGL.hide_at_login", -1),
					Array("LLL:EXT:lang/locallang_general.php:LGL.any_login", -2),
					Array("LLL:EXT:lang/locallang_general.php:LGL.usergroups", "--div--")
				),
				"foreign_table" => "fe_groups"
			)
		),
		"fl_number" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_floor.fl_number",
			"config" => Array (
				"type" => "input",
				"size" => "30",
				"max" => "255",
			)
		),
		"fl_descr" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_floor.fl_descr",
			"config" => Array (
				"type" => "input",
				"size" => "30",
				"max" => "255",
			)
		),
	),
	"types" => Array (
		"0" => Array("showitem" => "hidden;;1;;1-1-1, fl_number, fl_descr")
	),
	"palettes" => Array (
#		"1" => Array("showitem" => "fe_group")
		"1" => Array("showitem" => "")
	)
);



/**
 * The definition of the backend-mask and logic for the table tx_civserv_employee (contenttype employee)
 * All labels are defined in civserv/locallang_db.php
 *
 * Relations to other tables: tx_civserv_officehours, tx_civserv_employee_em_hours_mm, tx_civserv_position, tx_civserv_employee_em_position_mm
 *
 * Wizards (for more navigation comfort): 	class.tx_civserv_wizard_employee_em_position.php
 *
 * Uploadfolder for images: "".$upload_folder."/images"
 *
 * itemsProcFunc: tx_civserv_mandant->limit_items (to display only data in selectorfields that is affected for the actual mandant)
 *
 * further information see below and Typo3 Core API, ext_tables.php, ext_tables.sql, ext_localconf.php
 */
$TCA["tx_civserv_employee"] = Array (
	"ctrl" => $TCA["tx_civserv_employee"]["ctrl"],
	"interface" => Array (
		"showRecordFieldList" => "hidden,fe_group,em_number,em_address,em_title,em_name,em_firstname,em_telephone,em_fax,em_mobile,em_email,em_image,em_datasec,em_ss"
	),
	"feInterface" => $TCA["tx_civserv_employee"]["feInterface"],
	"columns" => Array (
		"hidden" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:lang/locallang_general.php:LGL.hidden",
			"config" => Array (
				"type" => "check",
				"default" => "0"
			)
		),
		"fe_group" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:lang/locallang_general.php:LGL.fe_group",
			"config" => Array (
				"type" => "select",
				"items" => Array (
					Array("", 0),
					Array("LLL:EXT:lang/locallang_general.php:LGL.hide_at_login", -1),
					Array("LLL:EXT:lang/locallang_general.php:LGL.any_login", -2),
					Array("LLL:EXT:lang/locallang_general.php:LGL.usergroups", "--div--")
				),
				"foreign_table" => "fe_groups"
			)
		),
		"em_number" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_employee.em_number",
			"config" => Array (
				"type" => "input",
				"size" => "30",
				"max" => "255",
			)
		),
		"em_address" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_employee.em_address",
			"config" => Array (
				"type" => "select",
				"items" => Array (
					Array("LLL:EXT:civserv/locallang_db.php:tx_civserv_employee.em_address.I.0", "0"),
					Array("LLL:EXT:civserv/locallang_db.php:tx_civserv_employee.em_address.I.1", "1"),
					Array("LLL:EXT:civserv/locallang_db.php:tx_civserv_employee.em_address.I.2", "2"),
				),
				"size" => 1,
				"maxitems" => 1,
			)
		),
		"em_title" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_employee.em_title",
			"config" => Array (
				"type" => "input",
				"size" => "30",
				"max" => "30",
			)
		),
		"em_name" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_employee.em_name",
			"config" => Array (
				"type" => "input",
				"size" => "30",
				"max" => "55",
				"eval" => "required",
			)
		),
		"em_firstname" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_employee.em_firstname",
			"config" => Array (
				"type" => "input",
				"size" => "30",
				"max" => "55",
				#"eval" => "required",
			)
		),
		"em_telephone" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_employee.em_telephone",
			"config" => Array (
				"type" => "input",
				"size" => "30",
				"max" => "30",
			)
		),
		"em_fax" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_employee.em_fax",
			"config" => Array (
				"type" => "input",
				"size" => "30",
				"max" => "30",
			)
		),
		"em_mobile" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_employee.em_mobile",
			"config" => Array (
				"type" => "input",
				"size" => "30",
				"max" => "30",
			)
		),
		"em_email" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_employee.em_email",
			"config" => Array (
				"type" => "input",
				"size" => "30",
				"max" => "55",
			)
		),
		"em_image" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_employee.em_image",
			"config" => Array (
				"type" => "group",
				"internal_type" => "file",
				"allowed" => "gif,png,jpeg,jpg",
				"max_size" => 500,
				"uploadfolder" => "".$upload_folder."/images",
				"show_thumbs" => 1,
				"size" => 1,
				"minitems" => 0,
				"maxitems" => 1,
			)
		),
		"em_datasec" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_employee.em_datasec",
			"config" => Array (
				"type" => "check",
			)
		),
		"em_pseudo" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_employee.em_pseudo",
			"config" => Array (
				"type" => "check",
			)
		),
		"em_hours" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_employee.em_hours",
			"config" => Array (
				"type" => "select",
				"foreign_table" => "tx_civserv_officehours",
				"foreign_table_where" => "ORDER BY tx_civserv_officehours.oh_weekday, tx_civserv_officehours.oh_start_morning",
				"itemsProcFunc" => "tx_civserv_mandant->limit_items",
				"size" => 5,
				"minitems" => 0,
				"maxitems" => 50,
				"MM" => "tx_civserv_employee_em_hours_mm",
			)
		),
		"em_position_PLACEHOLDER" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_employee.em_position_PLACEHOLDER",
			"dbField" => "em_position", //custom info passed on to $PA in userFunc
			"displayCond" => "REC:NEW:true",
			"config" => Array (
				"type" => "user",
				"userFunc" => "tx_civserv_user_be_msg->user_TCAform_test"
			)
		),
		"em_position" => Array (
			"exclude" => 1,
			"adminOnly" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_employee.em_position",
			"displayCond" => "REC:NEW:false",
			"config" => Array (
				"type" => "group",
				"internal_type" => "db",
				"size" => 5,
				"allowed" => "tx_civserv_position",
				"show_thumbs" => 0,
				"minitems" => 0,
				"maxitems" => 1000,
				"MM" => "tx_civserv_employee_em_position_mm",
                "wizards" => Array(
					"_PADDING" => 2,
					"_VERTICAL" => 1,
					'employeeposition' => Array(
							'type' => 'popup',
					        'title' => 'LLL:EXT:civserv/res/locallang_wizard.php:tx_civserv_wizard_employee_em_position.title',
       						'script' => 'EXT:civserv/res/class.tx_civserv_wizard_employee_em_position.php',
							'icon' => 'list.gif',
							'JSopenParams' => 'height=350,width=600,status=0,menubar=0,resizable=1,location=0',
					),
				),
			),
		),
	),
	"types" => Array (
		"0" => Array("showitem" => "hidden;;1;;1-1-1, em_number, em_address, em_title, em_name, em_firstname, em_telephone, em_fax, em_mobile, em_email, em_image, em_datasec, em_pseudo, em_hours, em_position_PLACEHOLDER, em_position")
	),
	"palettes" => Array (
#		"1" => Array("showitem" => "fe_group")
		"1" => Array("showitem" => "")
	)
);



/**
 * The definition of the backend-mask and logic for the table tx_civserv_organisation (contenttype organisation)
 * All labels are defined in civserv/locallang_db.php
 *
 * Relations to other tables: tx_civserv_employee, tx_civserv_officehours, tx_civserv_organisation_or_hours_mm, tx_civserv_organisation_or_structure_mm, tx_civserv_organisation_or_building_mm, tx_civserv_building
 *
 * Wizards (for more navigation comfort): 	class.tx_civserv_wizard_organisation_supervisor.php
 *
 * Uploadfolder for images: "".$upload_folder."/images"
 *
 * itemsProcFunc: tx_civserv_mandant->limit_items (to display only data in selectorfields that is affected for the actual mandant)
 *
 * further information see below and Typo3 Core API, ext_tables.php, ext_tables.sql, ext_localconf.php
 */
$TCA["tx_civserv_organisation"] = Array (
	"ctrl" => $TCA["tx_civserv_organisation"]["ctrl"],
	"interface" => Array (
		"showRecordFieldList" => "hidden,fe_group,or_name,or_hours,or_telephone,or_fax,or_email,or_image,or_infopage,or_addinfo,or_structure,or_building"
	),
	"feInterface" => $TCA["tx_civserv_organisation"]["feInterface"],
	"columns" => Array (
		"hidden" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:lang/locallang_general.php:LGL.hidden",
			"config" => Array (
				"type" => "check",
				"default" => "0"
			)
		),
		"fe_group" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:lang/locallang_general.php:LGL.fe_group",
			"config" => Array (
				"type" => "select",
				"items" => Array (
					Array("", 0),
					Array("LLL:EXT:lang/locallang_general.php:LGL.hide_at_login", -1),
					Array("LLL:EXT:lang/locallang_general.php:LGL.any_login", -2),
					Array("LLL:EXT:lang/locallang_general.php:LGL.usergroups", "--div--")
				),
				"foreign_table" => "fe_groups"
			)
		),
		"or_number" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_organisation.or_number",
			"config" => Array (
				"type" => "input",
				"size" => "30",
				"max" => "255",
				#"eval" => "required",
			)
		),
		"or_index" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_organisation.or_index",
			"config" => Array (
				"type" => "input",
				"size" => "30",
				"max" => "10",
			)
		),
		"or_code" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_organisation.or_code",
			"config" => Array (
				"type" => "input",
				"size" => "30",
				"max" => "10",
			)
		),
		"or_name" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_organisation.or_name",
			"config" => Array (
				"type" => "input",
				"size" => "30",
				"max" => "255",
				"eval" => "required",
			)
		),
		"or_synonym1" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_organisation.or_synonym1",
			"config" => Array (
				"type" => "input",
				"size" => "30",
				"max" => "255",
			)
		),
		"or_synonym2" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_organisation.or_synonym2",
			"config" => Array (
				"type" => "input",
				"size" => "30",
				"max" => "255",
			)
		),
		"or_synonym3" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_organisation.or_synonym3",
			"config" => Array (
				"type" => "input",
				"size" => "30",
				"max" => "255",
			)
		),
		"or_supervisor" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_organisation.or_supervisor",
			"config" => Array (
				"type" => "group",
				"internal_type" => "db",
				"size" => 1,
				"allowed" => "tx_civserv_employee",
				"show_thumbs" => 0,
				"minitems" => 0,
				"maxitems" => 1,
                "wizards" => Array(
					"_PADDING" => 2,
					"_VERTICAL" => 1,
					'organisationsupervisor' => Array(
							'type' => 'popup',
					        'title' => 'LLL:EXT:civserv/res/locallang_wizard.php:tx_civserv_wizard_organisation_supervisor.title',
       						'script' => 'EXT:civserv/res/class.tx_civserv_wizard_organisation_supervisor.php',
							'icon' => 'list.gif',
							'JSopenParams' => 'height=350,width=600,status=0,menubar=0,resizable=1,location=0',
					),
				),
			)
		),
		"or_show_supervisor" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_organisation.or_show_supervisor",
#			"displayCond" => "FIELD:or_supervisor:REQ:true",
			"config" => Array (
				"type" => "check",
			)
		),
		"or_hours" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_organisation.or_hours",
			"config" => Array (
				"type" => "select",
				"foreign_table" => "tx_civserv_officehours",
				"foreign_table_where" => "ORDER BY tx_civserv_officehours.oh_weekday, tx_civserv_officehours.oh_start_morning",
				"itemsProcFunc" => "tx_civserv_mandant->limit_items",
				"size" => 5,
				"minitems" => 1,
				"maxitems" => 50,
				"MM" => "tx_civserv_organisation_or_hours_mm",
			)
		),
		"or_telephone" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_organisation.or_telephone",
			"config" => Array (
				"type" => "input",
				"size" => "30",
				"max" => "30",
			)
		),
		"or_fax" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_organisation.or_fax",
			"config" => Array (
				"type" => "input",
				"size" => "30",
				"max" => "30",
			)
		),
		"or_email" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_organisation.or_email",
			"config" => Array (
				"type" => "input",
				"size" => "30",
				"max" => "55",
			)
		),
		"or_image" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_organisation.or_image",
			"config" => Array (
				"type" => "group",
				"internal_type" => "file",
				"allowed" => "gif,png,jpeg,jpg",
				"max_size" => 500,
				"uploadfolder" => "".$upload_folder."/images",
				"show_thumbs" => 1,
				"size" => 1,
				"minitems" => 0,
				"maxitems" => 1,
			)
		),
		"or_infopage" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_organisation.or_infopage",
			"config" => Array (
				"type" => "input",
				"size" => "15",
				"max" => "255",
				"wizards" => Array(
					"_PADDING" => 2,
					"link" => Array(
						"type" => "popup",
						"title" => "Link",
						"icon" => "link_popup.gif",
						"script" => "browse_links.php?mode=wizard",
						"JSopenParams" => "height=300,width=500,status=0,menubar=0,scrollbars=1"
					)
				)
			)
		),
		"or_addinfo" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_organisation.or_addinfo",
			"config" => Array (
				"type" => "text",
				"cols" => "30",
				"rows" => "5",
				"wizards" => Array(
					"_PADDING" => 2,
					"RTE" => Array(
						"notNewRecords" => 1,
						"RTEonly" => 1,
						"type" => "script",
						"title" => "Full screen Rich Text Editing|Formatteret redigering i hele vinduet",
						"icon" => "wizard_rte2.gif",
						"script" => "wizard_rte.php",
					),
				),
			)
		),
		"or_addlocation" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_organisation.or_addlocation",
			"config" => Array (
				"type" => "input",
				"size" => "30",
				"max" => "255",
			)
		),
		"or_structure" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_organisation.or_structure",
			"config" => Array (
				"type" => "select",
				"foreign_table" => "tx_civserv_organisation",
				"foreign_table_where" => "ORDER BY tx_civserv_organisation.or_name",
				"itemsProcFunc" => "tx_civserv_mandant->limit_items",
				"size" => 5,
				"minitems" => 0,
				"maxitems" => 100,
				"MM" => "tx_civserv_organisation_or_structure_mm",
				"wizards" => Array(
					"_PADDING" => 2,
					"_VERTICAL" => 1,
					"add" => Array(
						"type" => "script",
						"title" => "Create new record",
						"icon" => "add.gif",
						"params" => Array(
							"table"=>"tx_civserv_organisation",
							"pid" => "###CURRENT_PID###",
							"setValue" => "prepend"
						),
						"script" => "wizard_add.php",
					),
				),
			)
		),
		"or_building_to_show" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_organisation.or_building_to_show",
			"config" => Array (
				"type" => "select",
				"foreign_table" => "tx_civserv_building",
				"foreign_table_where" => "ORDER BY tx_civserv_building.bl_name",
				"itemsProcFunc" => "tx_civserv_mandant->limit_building_items",
				"size" => 5,
				"minitems" => 0,
				"maxitems" => 50,
				"MM" => "tx_civserv_organisation_or_building_to_show_mm",
			)
		),
		"or_building" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_organisation.or_building",
			"config" => Array (
				"type" => "select",
				"foreign_table" => "tx_civserv_building",
				"foreign_table_where" => "ORDER BY tx_civserv_building.bl_name",
				"itemsProcFunc" => "tx_civserv_mandant->limit_items",
				"size" => 5,
				"minitems" => 1,
				"maxitems" => 100,
				"MM" => "tx_civserv_organisation_or_building_mm",
			)
		),
	),
	"types" => Array (
		"0" => Array("showitem" => "	hidden;;1;;1-1-1, 
										or_number, 
										or_index,
										or_code, 
										or_name, 
										or_synonym1, 
										or_synonym2, 
										or_synonym3, 
										or_supervisor, 
										or_show_supervisor, 
										or_hours, 
										or_telephone, 
										or_fax, 
										or_email, 
										or_image, 
										or_infopage, 
										or_addlocation, 
										or_addinfo;;;richtext[paste|copy|bold|italic|underline|formatblock|class|left|center|right|orderedlist|unorderedlist|outdent|indent|link|image]:rte_transform[mode=ts], 
										or_structure, 
										or_building_to_show, 
										or_building")
	),
	"palettes" => Array (
#		"1" => Array("showitem" => "fe_group")
		"1" => Array("showitem" => "")
	)
);



/**
 * The definition of the backend-mask and logic for the table tx_civserv_officehours (contenttype officehours)
 * All labels are defined in civserv/locallang_db.php
 *
 * further information see below and Typo3 Core API, ext_tables.php, ext_tables.sql, ext_localconf.php
 */
$TCA["tx_civserv_officehours"] = Array (
	"ctrl" => $TCA["tx_civserv_officehours"]["ctrl"],
	"interface" => Array (
		"showRecordFieldList" => "hidden,fe_group,oh_start,oh_end,oh_weekday"
	),
	"feInterface" => $TCA["tx_civserv_officehours"]["feInterface"],
	"columns" => Array (
		"hidden" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:lang/locallang_general.php:LGL.hidden",
			"config" => Array (
				"type" => "check",
				"default" => "0"
			)
		),
		"fe_group" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:lang/locallang_general.php:LGL.fe_group",
			"config" => Array (
				"type" => "select",
				"items" => Array (
					Array("", 0),
					Array("LLL:EXT:lang/locallang_general.php:LGL.hide_at_login", -1),
					Array("LLL:EXT:lang/locallang_general.php:LGL.any_login", -2),
					Array("LLL:EXT:lang/locallang_general.php:LGL.usergroups", "--div--")
				),
				"foreign_table" => "fe_groups"
			)
		),
		"oh_descr" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_officehours.oh_descr",
			"config" => Array (
				"type" => "input",
				"size" => "30",
				"max" => "255",
				"eval" => "required",
			)
		),
		"oh_name" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_officehours.oh_name",
			"config" => Array (
				"type" => "input",
				"size" => "30",
				"max" => "255",
				"eval" => "required",
			)
		),
		"oh_manual_checkbox" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_officehours.oh_manual_checkbox",
			"config" => Array (
				"type" => "check",
			)
		),
		"oh_start_morning" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_officehours.oh_start_morning",
			"displayCond" => "FIELD:oh_manual_checkbox:REQ:false",
			"config" => Array (
				"type" => "select",
				"items" => Array (
					Array("--", "--"),
					Array(" ", 0),
					Array("07:00", "07:00"),
					Array("07:30", "07:30"),
					Array("08:00", "08:00"),
					Array("08:30", "08:30"),
					Array("09:00", "09:00"),
					Array("09:30", "09:30"),
					Array("10:00", "10:00"),
					Array("10:30", "10:30"),
					Array("11:00", "11:00"),
					Array("11:30", "11:30"),
					Array("12:00", "12:00"),
					Array("12:30", "12:30"),
					Array("13:00", "13:00"),
				),
				"eval" => "required,time",
			)
		),
		"oh_end_morning" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_officehours.oh_end_morning",
			"displayCond" => "FIELD:oh_manual_checkbox:REQ:false",
			"config" => Array (
				"type" => "select",
				"items" => Array (
					Array("--", "--"),
					Array(" ", 0),
					Array("07:00", "07:00"),
					Array("07:30", "07:30"),
					Array("08:00", "08:00"),
					Array("08:30", "08:30"),
					Array("09:00", "09:00"),
					Array("09:30", "09:30"),
					Array("10:00", "10:00"),
					Array("10:30", "10:30"),
					Array("11:00", "11:00"),
					Array("11:30", "11:30"),
					Array("12:00", "12:00"),
					Array("12:15", "12:15"),
					Array("12:30", "12:30"),
					Array("13:00", "13:00"),
					Array("13:30", "13:30"),
					Array("14:00", "14:00"),
				),
				"eval" => "required,time",
			)
		),
		"oh_start_afternoon" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_officehours.oh_start_afternoon",
			"displayCond" => "FIELD:oh_manual_checkbox:REQ:false",
			"config" => Array (
				"type" => "select",
				"items" => Array (
					Array("--", "--"),
					Array(" ", 0),
					Array("13:00", "13:00"),
					Array("13:30", "13:30"),
					Array("14:00", "14:00"),
					Array("14:30", "14:30"),
					Array("15:00", "15:00"),
					Array("15:30", "15:30"),
					Array("16:00", "16:00"),
					Array("16:30", "16:30"),
					Array("17:00", "17:00"),
					Array("17:30", "17:30"),
					Array("18:00", "18:00"),
					Array("18:30", "18:30"),
					Array("19:00", "19:00"),
					Array("19:30", "19:30"),
					Array("20:00", "20:00"),
					Array("20:30", "20:30"),
					Array("21:00", "21:00"),
				),
				"eval" => "required,time",
			)
		),
		"oh_end_afternoon" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_officehours.oh_end_afternoon",
			"displayCond" => "FIELD:oh_manual_checkbox:REQ:false",
			"config" => Array (
				"type" => "select",
				"items" => Array (
					Array("--", "--"),
					Array(" ", 0),
					Array("13:00", "13:00"),
					Array("13:30", "13:30"),
					Array("14:00", "14:00"),
					Array("14:30", "14:30"),
					Array("15:00", "15:00"),
					Array("15:30", "15:30"),
					Array("16:00", "16:00"),
					Array("16:30", "16:30"),
					Array("17:00", "17:00"),
					Array("17:30", "17:30"),
					Array("18:00", "18:00"),
					Array("18:30", "18:30"),
					Array("19:00", "19:00"),
					Array("19:30", "19:30"),
					Array("20:00", "20:00"),
					Array("20:30", "20:30"),
					Array("21:00", "21:00"),
					Array("21:30", "21:30"),
					Array("22:00", "22:00"),
				),
				"eval" => "required,time",
			)
		),
		"oh_freestyle" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_officehours.oh_freestyle",
			"displayCond" => "FIELD:oh_manual_checkbox:REQ:true",
			"config" => Array (
				"type" => "input",
				"size" => "25",
				"max" => "255",
				"eval" => "trim",
			)
		),
		"oh_weekday" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_officehours.oh_weekday",
			"config" => Array (
				"type" => "select",
				"items" => Array (
					//Array("", ""),
					Array("LLL:EXT:civserv/locallang_db.php:tx_civserv_weekday_101", 101),	// monday to friday
					Array("LLL:EXT:civserv/locallang_db.php:tx_civserv_weekday_102", 102),	// monday to thursday
					Array("LLL:EXT:civserv/locallang_db.php:tx_civserv_weekday_103", 103),	// monday to wednesday
					Array("LLL:EXT:civserv/locallang_db.php:tx_civserv_weekday_104", 104),	// tuesday to saturday
					Array("LLL:EXT:civserv/locallang_db.php:tx_civserv_weekday_201", 201),	// monday
					Array("LLL:EXT:civserv/locallang_db.php:tx_civserv_weekday_202", 202),	// tuesday
					Array("LLL:EXT:civserv/locallang_db.php:tx_civserv_weekday_203", 203),	// wednesday
					Array("LLL:EXT:civserv/locallang_db.php:tx_civserv_weekday_204", 204),	// thursday
					Array("LLL:EXT:civserv/locallang_db.php:tx_civserv_weekday_205", 205),	// friday
					Array("LLL:EXT:civserv/locallang_db.php:tx_civserv_weekday_206", 206),	// saturday
					Array("LLL:EXT:civserv/locallang_db.php:tx_civserv_weekday_207", 207),	// sunday
					Array("LLL:EXT:civserv/locallang_db.php:tx_civserv_weekday_301", 301),	// on bank holidays
					Array("LLL:EXT:civserv/locallang_db.php:tx_civserv_weekday_302", 302),	// saturdays and sundays
					Array("LLL:EXT:civserv/locallang_db.php:tx_civserv_weekday_401", 401),	// additionally
					Array("LLL:EXT:civserv/locallang_db.php:tx_civserv_weekday_402", 402),	// hint (hinweis)
					Array("LLL:EXT:civserv/locallang_db.php:tx_civserv_weekday_403", 403),	// weekday empty!!
				),
				//"eval" => "required,time",
			)
		),
	),
	"types" => Array (
		"0" => Array("showitem" => "hidden;;1;;1-1-1, oh_name, oh_manual_checkbox, oh_start_morning, oh_end_morning, oh_start_afternoon, oh_end_afternoon, oh_freestyle, oh_weekday")
	),
	"palettes" => Array (
#		"1" => Array("showitem" => "fe_group")
		"1" => Array("showitem" => "")
	)
);


/**
 * The definition of the backend-mask and logic for the table tx_civserv_search_word (contenttype search_word)
 * All labels are defined in civserv/locallang_db.php
 *
 * itemsProcFunc: tx_civserv_mandant->limit_items (to display only data in selectorfields that is affected for the actual mandant)
 *
 * further information see below and Typo3 Core API, ext_tables.php, ext_tables.sql, ext_localconf.php
 */
$TCA["tx_civserv_search_word"] = Array (
	"ctrl" => $TCA["tx_civserv_search_word"]["ctrl"],
	"interface" => Array (
		"showRecordFieldList" => "hidden,fe_group,sw_search_word"
	),
	"feInterface" => $TCA["tx_civserv_search_word"]["feInterface"],
	"columns" => Array (
		"hidden" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:lang/locallang_general.php:LGL.hidden",
			"config" => Array (
				"type" => "check",
				"default" => "0"
			)
		),
		"fe_group" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:lang/locallang_general.php:LGL.fe_group",
			"config" => Array (
				"type" => "select",
				"items" => Array (
					Array("", 0),
					Array("LLL:EXT:lang/locallang_general.php:LGL.hide_at_login", -1),
					Array("LLL:EXT:lang/locallang_general.php:LGL.any_login", -2),
					Array("LLL:EXT:lang/locallang_general.php:LGL.usergroups", "--div--")
				),
				"foreign_table" => "fe_groups"
			)
		),
		// would need items procfunc here!!!
		// the procfunc must take care of duplikate entries!!
		// but we can't combine procfuncs with textfields, can we?
		"sw_search_word" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_search_word.sw_search_word",
			"config" => Array (
				"type" => "input",
				"size" => "30",
				"max" => "255",
			)
		),
	),
	"types" => Array (
		"0" => Array("showitem" => "hidden;;1;;1-1-1, sw_search_word")
	),
	"palettes" => Array (
#		"1" => Array("showitem" => "fe_group")
		"1" => Array("showitem" => "")
	)
);



/**
 * The definition of the backend-mask and logic for the table tx_civserv_position (contenttype position)
 * All labels are defined in civserv/locallang_db.php
 *
 * Relations to other tables: tx_civserv_organisation, tx_civserv_position_po_organisation_mm
 *
 * further information see below and Typo3 Core API, ext_tables.php, ext_tables.sql, ext_localconf.php
 */
$TCA["tx_civserv_position"] = Array (
	"ctrl" => $TCA["tx_civserv_position"]["ctrl"],
	"interface" => Array (
		"showRecordFieldList" => "hidden,fe_group,po_name,po_descr,po_organisation"
	),
	"feInterface" => $TCA["tx_civserv_position"]["feInterface"],
	"columns" => Array (
		"hidden" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:lang/locallang_general.php:LGL.hidden",
			"config" => Array (
				"type" => "check",
				"default" => "0"
			)
		),
		"fe_group" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:lang/locallang_general.php:LGL.fe_group",
			"config" => Array (
				"type" => "select",
				"items" => Array (
					Array("", 0),
					Array("LLL:EXT:lang/locallang_general.php:LGL.hide_at_login", -1),
					Array("LLL:EXT:lang/locallang_general.php:LGL.any_login", -2),
					Array("LLL:EXT:lang/locallang_general.php:LGL.usergroups", "--div--")
				),
				"foreign_table" => "fe_groups"
			)
		),
		"po_number" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_position.po_number",
			"config" => Array (
				"type" => "input",
				"size" => "30",
				"max" => "255",
			)
		),
		"po_name" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_position.po_name",
			"config" => Array (
				"type" => "input",
				"size" => "30",
				"max" => "255",
				"eval" => "required",
			)
		),
		"po_nice_name" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_position.po_nice_name",
			"config" => Array (
				"type" => "input",
				"size" => "100",
				"max" => "255",
			)
		),
		"po_descr" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_position.po_descr",
			"config" => Array (
				"type" => "text",
				"cols" => "30",
				"rows" => "5",
				"wizards" => Array(
					"_PADDING" => 2,
					"RTE" => Array(
						"notNewRecords" => 1,
						"RTEonly" => 1,
						"type" => "script",
						"title" => "Full screen Rich Text Editing|Formatteret redigering i hele vinduet",
						"icon" => "wizard_rte2.gif",
						"script" => "wizard_rte.php",
					),
				),
			)
		),
		"po_organisation" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_position.po_organisation",
			"config" => Array (
				"type" => "select",
				"foreign_table" => "tx_civserv_organisation",
				"foreign_table_where" => "ORDER BY tx_civserv_organisation.or_name",
				"itemsProcFunc" => "tx_civserv_mandant->limit_items",
				"size" => 5,
				"minitems" => 0,
				"maxitems" => 50,
				"MM" => "tx_civserv_position_po_organisation_mm",
			)
		),
	),
	"types" => Array (
		"0" => Array("showitem" => "hidden;;1;;1-1-1, po_number, po_name, po_nice_name, po_descr;;;richtext[paste|copy|bold|italic|underline|formatblock|class|left|center|right|orderedlist|unorderedlist|outdent|indent|link|image]:rte_transform[mode=ts], po_organisation")
	),
	"palettes" => Array (
#		"1" => Array("showitem" => "fe_group")
		"1" => Array("showitem" => "")
	)
);



/**
 * The definition of the backend-mask and logic for the table tx_civserv_navigation (contenttype navigation)
 * All labels are defined in civserv/locallang_db.php
 *
 * Relations to other tables: tx_civserv_navigation_nv_structure_mm
 *
 * itemsProcFunc: tx_civserv_mandant->limit_items (to display only data in selectorfields that is affected for the actual mandant)
 *
 * further information see below and Typo3 Core API, ext_tables.php, ext_tables.sql, ext_localconf.php
 */
$TCA["tx_civserv_navigation"] = Array (
	"ctrl" => $TCA["tx_civserv_navigation"]["ctrl"],
	"interface" => Array (
		"showRecordFieldList" => "hidden,fe_group,nv_name,nv_structure"
	),
	"feInterface" => $TCA["tx_civserv_navigation"]["feInterface"],
	"columns" => Array (
		"hidden" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:lang/locallang_general.php:LGL.hidden",
			"config" => Array (
				"type" => "check",
				"default" => "0"
			)
		),
		"fe_group" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:lang/locallang_general.php:LGL.fe_group",
			"config" => Array (
				"type" => "select",
				"items" => Array (
					Array("", 0),
					Array("LLL:EXT:lang/locallang_general.php:LGL.hide_at_login", -1),
					Array("LLL:EXT:lang/locallang_general.php:LGL.any_login", -2),
					Array("LLL:EXT:lang/locallang_general.php:LGL.usergroups", "--div--")
				),
				"foreign_table" => "fe_groups"
			)
		),
		"nv_name" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_navigation.nv_name",
			"config" => Array (
				"type" => "input",
				"size" => "30",
				"max" => "255",
				"eval" => "required",
			)
		),
		"nv_label" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_navigation.nv_label",
			"config" => Array (
				"type" => "input",
				"size" => "30",
				"max" => "255",
			)
		),
		"nv_structure" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_navigation.nv_structure",
			"config" => Array (
				"type" => "select",
				"foreign_table" => "tx_civserv_navigation",
				"foreign_table_where" => "ORDER BY tx_civserv_navigation.nv_name",
				"itemsProcFunc" => "tx_civserv_mandant->limit_items",
				"size" => 5,
				"minitems" => 0,
				"maxitems" => 50,
				"MM" => "tx_civserv_navigation_nv_structure_mm",
				"wizards" => Array(
					"_PADDING" => 2,
					"_VERTICAL" => 1,
					"add" => Array(
						"type" => "script",
						"title" => "Create new record",
						"icon" => "add.gif",
						"params" => Array(
							"table"=>"tx_civserv_navigation",
							"pid" => "###CURRENT_PID###",
							"setValue" => "prepend"
						),
						"script" => "wizard_add.php",
					),
				),
			)
		),
	),
	"types" => Array (
		"0" => Array("showitem" => "hidden;;1;;1-1-1, nv_name, nv_structure")
	),
	"palettes" => Array (
#		"1" => Array("showitem" => "fe_group")
		"1" => Array("showitem" => "")
	)
);


/**
 * The definition of the backend-mask and logic for the table tx_civserv_navigation (contenttype navigation)
 * All labels are defined in civserv/locallang_db.php
 *
 * itemsProcFunc: tx_civserv_mandant->limit_items (to display only data in selectorfields that is affected for the actual mandant)
 *
 * further information see below and Typo3 Core API, ext_tables.php, ext_tables.sql, ext_localconf.php
 */
$TCA["tx_civserv_category"] = Array (
	"ctrl" => $TCA["tx_civserv_category"]["ctrl"],
	"interface" => Array (
		"showRecordFieldList" => "hidden,fe_group,ca_name"
	),
	"feInterface" => $TCA["tx_civserv_category"]["feInterface"],
	"columns" => Array (
		"hidden" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:lang/locallang_general.php:LGL.hidden",
			"config" => Array (
				"type" => "check",
				"default" => "0"
			)
		),
		"fe_group" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:lang/locallang_general.php:LGL.fe_group",
			"config" => Array (
				"type" => "select",
				"items" => Array (
					Array("", 0),
					Array("LLL:EXT:lang/locallang_general.php:LGL.hide_at_login", -1),
					Array("LLL:EXT:lang/locallang_general.php:LGL.any_login", -2),
					Array("LLL:EXT:lang/locallang_general.php:LGL.usergroups", "--div--")
				),
				"foreign_table" => "fe_groups"
			)
		),
		"ca_name" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_cateogory.ca_name",
			"config" => Array (
				"type" => "input",
				"size" => "30",
				"max" => "255",
				"eval" => "required",
			)
		),
	),
	"types" => Array (
		"0" => Array("showitem" => "hidden;;1;;1-1-1, ca_name")
	),
	"palettes" => Array (
#		"1" => Array("showitem" => "fe_group")
		"1" => Array("showitem" => "")
	)
);




/**
 * The definition of the backend-mask and logic for the table tx_civserv_building_bl_floor_mm (contenttype building_floor_relation)
 * All labels are defined in civserv/locallang_db.php
 *
 * Relations to other tables: tx_civserv_building, tx_civserv_floor
 *
 * further information see below and Typo3 Core API, ext_tables.php, ext_tables.sql, ext_localconf.php
 *
$TCA["tx_civserv_building_bl_floor_mm"] = Array (
	"ctrl" => $TCA["tx_civserv_building_bl_floor_mm"]["ctrl"],
	"interface" => Array (
		"showRecordFieldList" => "hidden,fe_group,uid_local,uid_foreign"
	),
	"feInterface" => $TCA["tx_civserv_building_bl_floor_mm"]["feInterface"],
	"columns" => Array (
		"hidden" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:lang/locallang_general.php:LGL.hidden",
			"config" => Array (
				"type" => "check",
				"default" => "0"
			)
		),
		"fe_group" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:lang/locallang_general.php:LGL.fe_group",
			"config" => Array (
				"type" => "select",
				"items" => Array (
					Array("", 0),
					Array("LLL:EXT:lang/locallang_general.php:LGL.hide_at_login", -1),
					Array("LLL:EXT:lang/locallang_general.php:LGL.any_login", -2),
					Array("LLL:EXT:lang/locallang_general.php:LGL.usergroups", "--div--")
				),
				"foreign_table" => "fe_groups"
			)
		),
		"uid_local" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_building",
			"config" => Array (
				"type" => "select",
				"foreign_table" => "tx_civserv_building",
				"foreign_table_where" => "ORDER BY tx_civserv_building.uid",
				"size" => 1,
				"minitems" => 0,
				"maxitems" => 1,
			)
		),
		"uid_foreign" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_floor",
			"config" => Array (
				"type" => "select",
				"foreign_table" => "tx_civserv_floor",
				"foreign_table_where" => "ORDER BY tx_civserv_floor.uid",
				"size" => 1,
				"minitems" => 0,
				"maxitems" => 1,
			)
		),
	),
	"types" => Array (
		"0" => Array("showitem" => "hidden;;1;;1-1-1, uid_local, uid_foreign")
	),
	"palettes" => Array (
#		"1" => Array("showitem" => "fe_group")
		"1" => Array("showitem" => "")
	)
);
*/

/**
 * The definition of the backend-mask and logic for the table tx_civserv_employee_em_position_mm (contenttype employee_em_position_relation)
 * All labels are defined in civserv/locallang_db.php
 *
 * ep_label field is not displayed in backend-mask. It is the title of the contenttype you see when using the "List"-module and klicking on a folder. The label is set and saved by the class "class.tx_civserv_commit.php"
 *
 * Relations to other tables: tx_civserv_position, tx_civserv_officehours, tx_civserv_officehours_oep_employee_em_position_mm_mm, tx_civserv_room
 *
 * itemsProcFunc: 	tx_civserv_mandant->limit_items (to display only data in selectorfields that is affected for the actual mandant)
 *					tx_civserv_oepupdate->ep_room2 (Shows building and floor in the selectorbox for each room in the Employee-Position-Relationship)
 *
 * further information see below and Typo3 Core API, ext_tables.php, ext_tables.sql, ext_localconf.php
 */
$TCA["tx_civserv_employee_em_position_mm"] = Array (
	"ctrl" => $TCA["tx_civserv_employee_em_position_mm"]["ctrl"],
	"interface" => Array (
		"showRecordFieldList" => "hidden,fe_group,uid_local,uid_foreign,ep_room, ep_officehours,ep_telephone, ep_fax, ep_mobile, ep_email"
	),
	"feInterface" => $TCA["tx_civserv_employee_em_position_mm"]["feInterface"],
	"columns" => Array (
		"hidden" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:lang/locallang_general.php:LGL.hidden",
			"config" => Array (
				"type" => "check",
				"default" => "0"
			)
		),
		"fe_group" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:lang/locallang_general.php:LGL.fe_group",
			"config" => Array (
				"type" => "select",
				"items" => Array (
					Array("", 0),
					Array("LLL:EXT:lang/locallang_general.php:LGL.hide_at_login", -1),
					Array("LLL:EXT:lang/locallang_general.php:LGL.any_login", -2),
					Array("LLL:EXT:lang/locallang_general.php:LGL.usergroups", "--div--")
				),
				"foreign_table" => "fe_groups"
			)
		),
		"uid_local" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_employee",
			"config" => Array (
				"type" => "select",
				"foreign_table" => "tx_civserv_employee",
				"foreign_table_where" => "ORDER BY tx_civserv_employee.em_name, tx_civserv_employee.em_firstname",
				"itemsProcFunc" => "tx_civserv_mandant->limit_items",
				"size" => 1,
				"minitems" => 0,
				"maxitems" => 1,
			)
		),
		"ep_label" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_employee_em_position_mm.uid_local_label",
			"config" => Array (
				"type" => "input",
				"size" => "45",
				"max" => "255",
			)
		),
		"uid_foreign" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_position",
			"config" => Array (
				"type" => "select",
				"foreign_table" => "tx_civserv_position",
				"foreign_table_where" => "ORDER BY tx_civserv_position.po_name",
				"itemsProcFunc" => "tx_civserv_mandant->limit_items",
				"size" => 1,
				"minitems" => 0,
				"maxitems" => 1,
			)
		),
		"ep_officehours" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_officehours",
			"config" => Array (
				"type" => "select",
				"foreign_table" => "tx_civserv_officehours",
				"foreign_table_where" => "ORDER BY tx_civserv_officehours.oh_weekday, tx_civserv_officehours.oh_start_morning",
				"itemsProcFunc" => "tx_civserv_mandant->limit_items",
				"size" => 5,
				"minitems" => 0,
				"maxitems" => 50,
				"MM" => "tx_civserv_officehours_oep_employee_em_position_mm_mm",
			),
		),
		"ep_room" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_room",
			"config" => Array (
				"type" => "group",
				"internal_type" => "db",
				"size" => 1,
				"itemListStyle" => "width:50em", 
				"selectedListStyle" => "width:50em",
				"allowed" => "tx_civserv_room",
				"show_thumbs" => 0,
				"minitems" => 0,
				"maxitems" => 1,
				//"MM" => "tx_civserv_employee_em_position_mm",
				
				//"type" => "select",
				//"items" => Array("", 0),
				//"itemsProcFunc" => "tx_civserv_oepupdate->ep_room2",
				//"noTableWrapping" => 1,
				
				"wizards" => Array(
					"_PADDING" => 2,
					"_VERTICAL" => 1,
					'similarservices' => Array(
							'type' => 'popup',
					        'title' => 'LLL:EXT:civserv/res/locallang_wizard.php:tx_civserv_wizard_employee_position_room.title',
       						'script' => 'EXT:civserv/res/class.tx_civserv_wizard_employee_position_room.php',
							'icon' => 'list.gif',
							'JSopenParams' => 'height=350,width=600,status=0,menubar=0,resizable=1,location=0',
					),
				),
			),
		),
		"ep_telephone" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_employee.em_telephone",
			"config" => Array (
				"type" => "input",
				"size" => "30",
				"max" => "30",
			)
		),
		"ep_fax" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_employee.em_fax",
			"config" => Array (
				"type" => "input",
				"size" => "30",
				"max" => "30",
			)
		),
		"ep_mobile" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_employee.em_mobile",
			"config" => Array (
				"type" => "input",
				"size" => "30",
				"max" => "30",
			)
		),
		"ep_email" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_employee.em_email",
			"config" => Array (
				"type" => "input",
				"size" => "30",
				"max" => "55",
			)
		),
		"ep_datasec" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_employee_em_position_mm.ep_datasec",
			"config" => Array (
				"type" => "check",
			)
		),
	),
	"types" => Array (
		"0" => Array("showitem" => "hidden;;1;;1-1-1, ep_officehours, ep_room, ep_telephone, ep_fax, ep_mobile, ep_email, ep_datasec")
	),
	"palettes" => Array (
#		"1" => Array("showitem" => "fe_group")
		"1" => Array("showitem" => "")
	)
);


/**
 * The definition of the backend-mask and logic for the table tx_civserv_service_sv_position_mm (contenttype service_position_relation)
 * All labels are defined in civserv/locallang_db.php
 *
 * sp_label field is not displayed in backend-mask. It is the title of the contenttype you see when using the "List"-module and klicking on a folder. The label is set and saved by the class "class.tx_civserv_commit.php"
 *
 * Relations to other tables: tx_civserv_position, tx_civserv_service
 *
 * itemsProcFunc: 	tx_civserv_mandant->limit_items (to display only data in selectorfields that is affected for the actual mandant)
 *
 * further information see below and Typo3 Core API, ext_tables.php, ext_tables.sql, ext_localconf.php
 */
$TCA["tx_civserv_service_sv_position_mm"] = Array (
	"ctrl" => $TCA["tx_civserv_service_sv_position_mm"]["ctrl"],
	"interface" => Array (
		"showRecordFieldList" => "hidden,fe_group,uid_local,uid_foreign,sp_descr"
	),
	"feInterface" => $TCA["tx_civserv_employee_em_position_mm"]["feInterface"],
	"columns" => Array (
		"hidden" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:lang/locallang_general.php:LGL.hidden",
			"displayCond" => "FIELD:pid:>:0",
			"config" => Array (
				"type" => "check",
				"default" => "0"
			)
		),
		"fe_group" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:lang/locallang_general.php:LGL.fe_group",
			"config" => Array (
				"type" => "select",
				"items" => Array (
					Array("", 0),
					Array("LLL:EXT:lang/locallang_general.php:LGL.hide_at_login", -1),
					Array("LLL:EXT:lang/locallang_general.php:LGL.any_login", -2),
					Array("LLL:EXT:lang/locallang_general.php:LGL.usergroups", "--div--")
				),
				"foreign_table" => "fe_groups"
			)
		),
		"uid_local" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_employee",
			"config" => Array (
				"type" => "select",
				"foreign_table" => "tx_civserv_service",
				"foreign_table_where" => "ORDER BY tx_civserv_service.sv_name",
				"itemsProcFunc" => "tx_civserv_mandant->limit_items",
				"size" => 1,
				"minitems" => 0,
				"maxitems" => 1,
			)
		),
		"uid_foreign" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_position",
			"config" => Array (
				"type" => "select",
				"foreign_table" => "tx_civserv_position",
				"foreign_table_where" => "ORDER BY tx_civserv_position.po_name",
				"itemsProcFunc" => "tx_civserv_mandant->limit_items",
				"size" => 1,
				"minitems" => 0,
				"maxitems" => 1,
			)
		),
		"sp_label" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_service_sv_position_mm.sp_label",
			"config" => Array (
				"type" => "input",
				"size" => "45",
				"max" => "255",
			)
		),
		"sp_descr_PLACEHOLDER" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_service_sv_position_mm.sp_descr_PLACEHOLDER",
			"displayCond" => "FIELD:pid:<=:0", // problem with rights for custom_ws-editors, they get no permission msg from core.
			"dbField" => "sp_descr", //custom info passed on to $PA in userFunc
			"config" => Array (
				"type" => "user",
				"userFunc" => "tx_civserv_user_be_msg->user_TCAform_test"
			)
		),
		"sp_descr" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_service_sv_position_mm.sp_descr",
			"displayCond" => "FIELD:pid:>:0",
			"config" => Array (
				"type" => "text",
				"cols" => "30",
				"rows" => "5",
				"wizards" => Array(
					"_PADDING" => 2,
					"RTE" => Array(
						"notNewRecords" => 1,
						"RTEonly" => 1,
						"type" => "script",
						"title" => "Full screen Rich Text Editing|Formatteret redigering i hele vinduet",
						"icon" => "wizard_rte2.gif",
						"script" => "wizard_rte.php",
					),
				),
			)
		),
	),
	"types" => Array (
		"0" => Array("showitem" => "hidden;;1;;1-1-1, sp_descr_PLACEHOLDER, sp_descr;;;richtext[paste|copy|bold|italic|underline|formatblock|class|left|center|right|orderedlist|unorderedlist|outdent|indent|link|image]:rte_transform[mode=ts]")
	),
	"palettes" => Array (
#		"1" => Array("showitem" => "fe_group")
		"1" => Array("showitem" => "")
	)
);

#	debug($TCA["tx_civserv_service_sv_position_mm"]['columns'], 'TCA tx_civserv_service_sv_position_mm->columns');

if (t3lib_div::int_from_ver(TYPO3_version) < 4000000) {
	$TCA['tx_civserv_service_sv_position_mm']['columns']['sp_descr_PLACEHOLDER']['displayCond']="FIELD:pid:<:-1"; //make sure its never displayed in typo3 version <= 3.8.1
}


/**
 * The definition of the backend-mask and logic for the table tx_civserv_model_service_temp (contenttype model service)
 * All labels are defined in civserv/locallang_db.php
 *
 * Relations to other tables: tx_civserv_model_service_temp, tx_civserv_search_word
 *
 * Chekcboxes / Displayconditions:			ms_comment_approver_one / ms_comment_approver_two is only displayed in the backend, if a comment is given in the workflow from an approver
 *
 * Uploadfolder for images: "".$model_service_folder
 *
 * further information see below and Typo3 Core API, ext_tables.php, ext_tables.sql, ext_localconf.php
 */
$TCA["tx_civserv_model_service_temp"] = Array (
	"ctrl" => $TCA["tx_civserv_model_service_temp"]["ctrl"],
	"interface" => Array (
		"showRecordFieldList" => "fe_group,ms_synonym1,ms_synonym2,ms_synonym3,ms_descr_short,ms_descr_long,ms_image,ms_image_text,ms_fees,ms_documents,ms_legal_global,ms_searchword"
	),
	"feInterface" => $TCA["tx_civserv_model_service_temp"]["feInterface"],
	"columns" => Array (
		"hidden" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:lang/locallang_general.php:LGL.hidden",
			"config" => Array (
				"type" => "check",
				"default" => "0"
			)
		),
		"fe_group" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:lang/locallang_general.php:LGL.fe_group",
			"config" => Array (
				"type" => "select",
				"items" => Array (
					Array("", 0),
					Array("LLL:EXT:lang/locallang_general.php:LGL.hide_at_login", -1),
					Array("LLL:EXT:lang/locallang_general.php:LGL.any_login", -2),
					Array("LLL:EXT:lang/locallang_general.php:LGL.usergroups", "--div--")
				),
				"foreign_table" => "fe_groups"
			)
		),
		"ms_name" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_model_service.ms_name",
			"config" => Array (
				"type" => "input",
				"size" => "30",
				"max" => "255",
				"eval" => "required",
			)
		),
		"ms_synonym1" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_model_service.ms_synonym1",
			"config" => Array (
				"type" => "input",
				"size" => "30",
				"max" => "255",
			)
		),
		"ms_synonym2" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_model_service.ms_synonym2",
			"config" => Array (
				"type" => "input",
				"size" => "30",
				"max" => "255",
			)
		),
		"ms_synonym3" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_model_service.ms_synonym3",
			"config" => Array (
				"type" => "input",
				"size" => "30",
				"max" => "255",
			)
		),
		"ms_descr_short" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_model_service.ms_descr_short",
			"config" => Array (
				"type" => "text",
				"cols" => "30",
				"rows" => "5",
				"wizards" => Array(
					"_PADDING" => 2,
					"RTE" => Array(
						"notNewRecords" => 1,
						"RTEonly" => 1,
						"type" => "script",
						"title" => "Full screen Rich Text Editing|Formatteret redigering i hele vinduet",
						"icon" => "wizard_rte2.gif",
						"script" => "wizard_rte.php",
					),
				),
			)
		),
		"ms_descr_long" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_model_service.ms_descr_long",
			"config" => Array (
				"type" => "text",
				"cols" => "30",
				"rows" => "5",
				"wizards" => Array(
					"_PADDING" => 2,
					"RTE" => Array(
						"notNewRecords" => 1,
						"RTEonly" => 1,
						"type" => "script",
						"title" => "Full screen Rich Text Editing|Formatteret redigering i hele vinduet",
						"icon" => "wizard_rte2.gif",
						"script" => "wizard_rte.php",
					),
				),
			)
		),
		"ms_image" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_model_service.ms_image",
			"config" => Array (
				"type" => "group",
				"internal_type" => "file",
				"allowed" => "gif,png,jpeg,jpg",
				"max_size" => 500,
				"uploadfolder" => "".$model_service_folder,
				"show_thumbs" => 1,
				"size" => 1,
				"minitems" => 0,
				"maxitems" => 1,
			)
		),
		"ms_image_text" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_model_service.ms_image_text",
			"config" => Array (
				"type" => "input",
				"size" => "30",
				"max" => "255",
			)
		),
		"ms_fees" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_model_service.ms_fees",
			"config" => Array (
				"type" => "text",
				"cols" => "30",
				"rows" => "5",
				"wizards" => Array(
					"_PADDING" => 2,
					"RTE" => Array(
						"notNewRecords" => 1,
						"RTEonly" => 1,
						"type" => "script",
						"title" => "Full screen Rich Text Editing|Formatteret redigering i hele vinduet",
						"icon" => "wizard_rte2.gif",
						"script" => "wizard_rte.php",
					),
				),
			)
		),
		"ms_documents" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_model_service.ms_documents",
			"config" => Array (
				"type" => "text",
				"cols" => "30",
				"rows" => "5",
				"wizards" => Array(
					"_PADDING" => 2,
					"RTE" => Array(
						"notNewRecords" => 1,
						"RTEonly" => 1,
						"type" => "script",
						"title" => "Full screen Rich Text Editing|Formatteret redigering i hele vinduet",
						"icon" => "wizard_rte2.gif",
						"script" => "wizard_rte.php",
					),
				),
			)
		),
		"ms_legal_global" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_model_service.ms_legal_global",
			"config" => Array (
				"type" => "text",
				"cols" => "30",
				"rows" => "5",
				"wizards" => Array(
					"_PADDING" => 2,
					"RTE" => Array(
						"notNewRecords" => 1,
						"RTEonly" => 1,
						"type" => "script",
						"title" => "Full screen Rich Text Editing|Formatteret redigering i hele vinduet",
						"icon" => "wizard_rte2.gif",
						"script" => "wizard_rte.php",
					),
				),
			)
		),
		"ms_searchword" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_model_service.ms_searchword",
			"config" => Array (
				"type" => "group",
				"internal_type" => "db",
				"size" => 5,
				"allowed" => "tx_civserv_search_word",
				"show_thumbs" => 0,
				"minitems" => 0,
				"maxitems" => 50,
                "wizards" => Array(
					"_PADDING" => 2,
					"_VERTICAL" => 1,
					'modelservicessearchword' => Array(
							'type' => 'popup',
					        'title' => 'LLL:EXT:civserv/res/locallang_wizard.php:tx_civserv_wizard_service_searchword.title',
       						'script' => 'EXT:civserv/res/class.tx_civserv_wizard_service_searchword.php',
							'icon' => 'list.gif',
							'JSopenParams' => 'height=350,width=600,status=0,menubar=0,resizable=1,location=0',
					),
				),
			)
		),
		"ms_additional_label" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_model_service_temp.ms_additional_label",
			"config" => Array (
				"type" => "input",
				"size" => "45",
				"max" => "255",
			)
		),
		"ms_comment_approver_one" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_model_service_temp.ms_comment_approver_one",
			"displayCond" => "FIELD:ms_comment_approver_one:REQ:true",
			"config" => Array (
				"type" => "text",
				"cols" => "30",
				"rows" => "5",
			)
		),
		"ms_comment_approver_two" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_model_service_temp.ms_comment_approver_two",
			"displayCond" => "FIELD:ms_comment_approver_two:REQ:true",
			"config" => Array (
				"type" => "text",
				"cols" => "30",
				"rows" => "5",
			)
		),
	),
	"types" => Array (
		"0" => Array("showitem" => "hidden;;1;;1-1-1, ms_synonym1, ms_synonym2, ms_synonym3, ms_descr_short;;;richtext[paste|copy|bold|italic|underline|formatblock|class|left|center|right|orderedlist|unorderedlist|outdent|indent|link|image]:rte_transform[mode=ts], ms_descr_long;;;richtext[paste|copy|bold|italic|underline|formatblock|class|left|center|right|orderedlist|unorderedlist|outdent|indent|link|image]:rte_transform[mode=ts], ms_image, ms_image_text, ms_fees;;;richtext[paste|copy|bold|italic|underline|formatblock|class|left|center|right|orderedlist|unorderedlist|outdent|indent|link|image]:rte_transform[mode=ts], ms_documents;;;richtext[paste|copy|bold|italic|underline|formatblock|class|left|center|right|orderedlist|unorderedlist|outdent|indent|link|image]:rte_transform[mode=ts], ms_legal_global;;;richtext[paste|copy|bold|italic|underline|formatblock|class|left|center|right|orderedlist|unorderedlist|outdent|indent|link|image]:rte_transform[mode=ts], ms_searchword, ms_comment_approver_one, ms_comment_approver_two")
	),
	"palettes" => Array (
#		"1" => Array("showitem" => "fe_group")
		"1" => Array("showitem" => "")
	)
);


/**
 * The definition of the backend-mask and logic for the table tx_civserv_region (contenttype region)
 * All labels are defined in civserv/locallang_db.php
 *
 * further information see below and Typo3 Core API, ext_tables.php, ext_tables.sql, ext_localconf.php
 */
$TCA["tx_civserv_region"] = Array (
	"ctrl" => $TCA["tx_civserv_region"]["ctrl"],
	"interface" => Array (
		"showRecordFieldList" => "hidden,fe_group,re_name"
	),
	"feInterface" => $TCA["tx_civserv_region"]["feInterface"],
	"columns" => Array (
		"hidden" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:lang/locallang_general.php:LGL.hidden",
			"config" => Array (
				"type" => "check",
				"default" => "0"
			)
		),
		"fe_group" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:lang/locallang_general.php:LGL.fe_group",
			"config" => Array (
				"type" => "select",
				"items" => Array (
					Array("", 0),
					Array("LLL:EXT:lang/locallang_general.php:LGL.hide_at_login", -1),
					Array("LLL:EXT:lang/locallang_general.php:LGL.any_login", -2),
					Array("LLL:EXT:lang/locallang_general.php:LGL.usergroups", "--div--")
				),
				"foreign_table" => "fe_groups"
			)
		),
		"re_name" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_region.re_name",
			"config" => Array (
				"type" => "input",
				"size" => "30",
				"max" => "255",
			)
		),
	),
	"types" => Array (
		"0" => Array("showitem" => "hidden;;1;;1-1-1, re_name")
	),
	"palettes" => Array (
#		"1" => Array("showitem" => "fe_group")
		"1" => Array("showitem" => "")
	)
);


/**
 * The definition of the backend-mask and logic for the table tx_civserv_external_service (contenttype external service)
 * All labels are defined in civserv/locallang_db.php
 *
 * Relations to other tables: tx_civserv_service, tx_civserv_ext_service_esv_navigation_mm
 *
 * itemsProcFunc: tx_civserv_mandant->limit_items (to display only data in selectorfields that is affected for the actual mandant)
 *
 * further information see below and Typo3 Core API, ext_tables.php, ext_tables.sql, ext_localconf.php
 */
$TCA["tx_civserv_external_service"] = Array (
	"ctrl" => $TCA["tx_civserv_external_service"]["ctrl"],
	"interface" => Array (
		"showRecordFieldList" => "hidden,fe_group,es_name,es_navigation,es_external_service"
	),
	"feInterface" => $TCA["tx_civserv_external_service"]["feInterface"],
	"columns" => Array (
		"hidden" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:lang/locallang_general.php:LGL.hidden",
			"config" => Array (
				"type" => "check",
				"default" => "0"
			)
		),
		"fe_group" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:lang/locallang_general.php:LGL.fe_group",
			"config" => Array (
				"type" => "select",
				"items" => Array (
					Array("", 0),
					Array("LLL:EXT:lang/locallang_general.php:LGL.hide_at_login", -1),
					Array("LLL:EXT:lang/locallang_general.php:LGL.any_login", -2),
					Array("LLL:EXT:lang/locallang_general.php:LGL.usergroups", "--div--")
				),
				"foreign_table" => "fe_groups"
			)
		),
		"es_name" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_external_service.es_name",
			"config" => Array (
				"type" => "input",
				"size" => "30",
				"max" => "255",
				"eval" => "required",
			)
		),
		"es_navigation" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_external_service.es_navigation",
			"config" => Array (
				"type" => "select",
				"foreign_table" => "tx_civserv_navigation",
				"foreign_table_where" => "ORDER BY tx_civserv_navigation.nv_name",
				"itemsProcFunc" => "tx_civserv_mandant->limit_items",
				"size" => 5,
				"minitems" => 0,
				"maxitems" => 50,
				"MM" => "tx_civserv_ext_service_esv_navigation_mm",
			)
		),
		"es_external_service" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_external_service.es_external_service",
			"config" => Array (
				"type" => "select",
				"foreign_table" => "tx_civserv_service",
				"foreign_table_where" => "ORDER BY tx_civserv_service.sv_name",
				"size" => 1,
				"minitems" => 1,
				"maxitems" => 50,
			),
		),
	),
	"types" => Array (
		"0" => Array("showitem" => "hidden;;1;;1-1-1, es_navigation")
	),
	"palettes" => Array (
#		"1" => Array("showitem" => "fe_group")
		"1" => Array("showitem" => "")
	)
);

/**
 * The definition of the backend-mask and logic for the table tx_civserv_conf_mandant (content-type mandant)
 * All labels are defined in civserv/locallang_db.php
 *
 * Relations to other tables: tx_civserv_region, tx_civserv_conf_mandant_cm_region_mm
 *
 * further information see below and Typo3 Core API, ext_tables.php, ext_tables.sql, ext_localconf.php
 */
$TCA["tx_civserv_conf_mandant"] = Array (
	"ctrl" => $TCA["tx_civserv_conf_mandant"]["ctrl"],
	"interface" => Array (
		"showRecordFieldList" => "hidden,fe_group,cm_community_name, cm_community_id, cm_uid, cm_page_uid, cm_search_uid, cm_circumstance_uid, cm_usergroup_uid, cm_organisation_uid, cm_service_folder_uid, cm_external_service_folder_uid, cm_model_service_temp_uid, cm_target_email, cm_community_type"
	),
	"feInterface" => $TCA["tx_civserv_conf_mandant"]["feInterface"],
	"columns" => Array (
		"hidden" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:lang/locallang_general.php:LGL.hidden",
			"config" => Array (
				"type" => "check",
				"default" => "0"
			)
		),
		"fe_group" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:lang/locallang_general.php:LGL.fe_group",
			"config" => Array (
				"type" => "select",
				"items" => Array (
					Array("", 0),
					Array("LLL:EXT:lang/locallang_general.php:LGL.hide_at_login", -1),
					Array("LLL:EXT:lang/locallang_general.php:LGL.any_login", -2),
					Array("LLL:EXT:lang/locallang_general.php:LGL.usergroups", "--div--")
				),
				"foreign_table" => "fe_groups"
			)
		),
		"cm_community_name" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_conf_mandant.cm_community_name",
			"config" => Array (
				"type" => "input",
				"size" => "30",
				"max" => "255",
				"eval" => "required",
			)
		),
		"cm_community_id" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_conf_mandant.cm_community_id",
			"config" => Array (
				"type" => "input",
				"size" => "30",
				"max" => "255",
				"eval" => "required",
			)
		),
		"cm_uid" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_conf_mandant.cm_uid",
			"config" => Array (
				"type" => "input",
				"size" => "30",
				"max" => "255",
				"eval" => "required",
			)
		),
		"cm_circumstance_uid" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_conf_mandant.cm_circumstance_uid",
			"config" => Array (
				"type" => "input",
				"size" => "30",
				"max" => "255",
				"eval" => "required",
			)
		),
		"cm_usergroup_uid" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_conf_mandant.cm_usergroup_uid",
			"config" => Array (
				"type" => "input",
				"size" => "30",
				"max" => "255",
				"eval" => "required",
			)
		),
		"cm_organisation_uid" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_conf_mandant.cm_organisation_uid",
			"config" => Array (
				"type" => "input",
				"size" => "30",
				"max" => "255",
				"eval" => "required",
			)
		),
		"cm_service_folder_uid" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_conf_mandant.cm_service_folder_uid",
			"config" => Array (
				"type" => "input",
				"size" => "30",
				"max" => "255",
				"eval" => "required",
			)
		),
		"cm_external_service_folder_uid" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_conf_mandant.cm_external_folder_uid",
			"config" => Array (
				"type" => "input",
				"size" => "30",
				"max" => "255",
				"eval" => "required",
			)
		),
		"cm_alternative_language_folder_uid" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_conf_mandant.cm_alternative_language_folder_uid",
			"config" => Array (
				"type" => "input",
				"size" => "30",
				"max" => "255",
				"eval" => "required",
			)
		),
		"cm_model_service_temp_uid" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_conf_mandant.cm_model_service_temp_uid",
			"config" => Array (
				"type" => "input",
				"size" => "30",
				"max" => "255",
				"eval" => "required",
			)
		),
		"cm_building_folder_uid" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_conf_mandant.cm_building_folder_uid",
			"config" => Array (
				"type" => "input",
				"size" => "30",
				"max" => "255",
				"eval" => "required",
			)
		),
		"cm_page_uid" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_conf_mandant.cm_page_uid",
			"config" => Array (
				"type" => "input",
				"size" => "30",
				"max" => "255",
				"eval" => "required",
			)
		),
		"cm_search_uid" => Array (
					"exclude" => 1,
					"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_conf_mandant.cm_search_uid",
					"config" => Array (
						"type" => "input",
						"size" => "30",
						"max" => "255",
						"eval" => "required",
					)
		),
		"cm_alternative_page_uid" => Array (
					"exclude" => 1,
					"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_conf_mandant.cm_alternative_page_uid",
					"config" => Array (
						"type" => "input",
						"size" => "30",
						"max" => "255",
						"eval" => "required",
					)
		),
		"cm_info_folder_uid" => Array (
					"exclude" => 1,
					"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_conf_mandant.cm_info_folder_uid",
					"config" => Array (
						"type" => "input",
						"size" => "30",
						"max" => "255",
						"eval" => "required",
					)
		),
		"cm_community_type" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_conf_mandant.cm_community_type",
			"config" => Array (
				"type" => "select",
				"foreign_table" => "tx_civserv_region",
				"foreign_table_where" => "ORDER BY tx_civserv_region.re_name",
				"size" => 5,
				"minitems" => 1,
				"maxitems" => 50,
				"MM" => "tx_civserv_conf_mandant_cm_region_mm",
			)
		),
		"cm_target_email" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_conf_mandant.cm_target_email",
			"config" => Array(
				"type" => "input",
				"size" => "30",
				"max" => "80",
				"eval" => "trim",
			)
		),
		"cm_employeesearch" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_conf_mandant.cm_employeesearch",
			"config" => Array (
				"type" => "check",
			)
		),
		"cm_page_subtitle_contains_organisation_uid" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:civserv/locallang_db.php:tx_civserv_conf_mandant.cm_subtitle_contains_organisation",
			"config" => Array (
				"type" => "check",
			)
		),			
	),
	"types" => Array (
		"0" => Array("showitem" => "hidden;;1;;1-1-1, cm_community_name, cm_community_id, cm_uid, cm_circumstance_uid, cm_usergroup_uid, cm_organisation_uid, cm_service_folder_uid, cm_alternative_language_folder_uid, cm_external_service_folder_uid, cm_building_folder_uid, cm_model_service_temp_uid, cm_page_uid, cm_alternative_page_uid, cm_search_uid, cm_info_folder_uid, cm_target_email, cm_employeesearch, cm_page_subtitle_contains_organisation_uid, cm_community_type")
	),
	"palettes" => Array (
#		"1" => Array("showitem" => "fe_group")
		"1" => Array("showitem" => "")
	)
);


?>
