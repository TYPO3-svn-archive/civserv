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
* Language labels for database tables/fields belonging to extension "civserv"
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

$LOCAL_LANG = Array (
	"default" => Array (
		"tx_civserv.doktype.model_service" => "Model Service Container",
		"tx_civserv_conf_mandant" => "Mandant",
		"tx_civserv_conf_mandant.cm_community_name" => "Community name",
		"tx_civserv_conf_mandant.cm_community_id" => "Community id",
		"tx_civserv_conf_mandant.cm_uid" => "Community-Entrypoint",
		"tx_civserv_conf_mandant.cm_circumstance_uid" => "Circumstance",
		"tx_civserv_conf_mandant.cm_usergroup_uid" => "Usergroup",
		"tx_civserv_conf_mandant.cm_organisation_uid" => "Organisation uid",
		"tx_civserv_conf_mandant.cm_service_folder_uid" => "Service folder uid",
		"tx_civserv_conf_mandant.cm_external_folder_uid" => "External service folder uid",
		"tx_civserv_conf_mandant.cm_alternative_language_folder_uid" => "Alternative language folder uid",
		"tx_civserv_conf_mandant.cm_building_folder_uid" => "Building folder uid",
		"tx_civserv_conf_mandant.cm_model_service_temp_uid" => "Model service temp uid",
		"tx_civserv_conf_mandant.cm_page_uid" => "Front-End-Page uid",
		"tx_civserv_conf_mandant.cm_search_uid" => "Fulltext-Search-Page uid",
		"tx_civserv_conf_mandant.cm_alternative_page_uid" => "Alternative-Language-Page uid",
		"tx_civserv_conf_mandant.cm_info_folder_uid" => "Info-folder uid",
		"tx_civserv_conf_mandant.cm_community_type" => "Community type",
		"tx_civserv_conf_mandant.cm_target_email" => "E-Mail for model service delivery",
		"tx_civserv_conf_mandant.cm_employeesearch" => "Find employee with search engine?",
		"tx_civserv_conf_mandant.cm_subtitle_contains_organisation" => "page_subtitles of service folders carry organisation_uids (has to be done manually!!)?",
		"tx_civserv_external_service" => "External Service",
		"tx_civserv_external_service.es_external_service" => "External Services",
		"tx_civserv_external_service.es_name" => "Name",
		"tx_civserv_external_service.es_navigation" => "Service-Navigation-Relationship (Navigation)",
		"tx_civserv_region" => "Region",
		"tx_civserv_region.re_name" => "Region title",
		"be_users.tx_civserv_approv" => "Right to commit",
		"be_users.tx_civserv_employee" => "Employee",
		"tx_civserv_service" => "Service",
		"tx_civserv_service.sv_type" => "Intranet",
#		"tx_civserv_service.sv_type" => "Intra- oder Internet?",
		"tx_civserv_service.sv_globalnet" => "egal",
		"tx_civserv_service.sv_intranet" => "Intranet Service",
		"tx_civserv_service.sv_internet" => "Internet Service",
		"tx_civserv_service.sv_name" => "Name",
		"tx_civserv_service.sv_synonym1" => "1st Synonym",
		"tx_civserv_service.sv_synonym2" => "2nd Synonym",
		"tx_civserv_service.sv_synonym3" => "3rd Synonym",
		"tx_civserv_service.sv_descr_short" => "Short description",
		"tx_civserv_service.sv_descr_long" => "Detailed description",
		"tx_civserv_service.sv_image" => "Image",
		"tx_civserv_service.sv_image_text" => "Image text",
		"tx_civserv_service.sv_fees" => "Fees, rates and tolls",
		"tx_civserv_service.sv_documents" => "Necessary documents",
		"tx_civserv_service.sv_legal_local" => "Legal basis (local issues)",
		"tx_civserv_service.sv_legal_global" => "Legal basis (global issues)",
		"tx_civserv_service.sv_model_service" => "Model service (Modelservice-Service-Relationship)",
		"tx_civserv_service.sv_similar_services" => "Similar services",
		"tx_civserv_service.sv_similar_services_PLACEHOLDER" => "Similar services info",
		"tx_civserv_service.sv_service_version" => "Service version",
		"tx_civserv_service.sv_form" => "Service-Form-Relationship (Form)",
		"tx_civserv_service.sv_form_PLACEHOLDER" => "Forms info",
		"tx_civserv_service.sv_searchword" => "Service-Searchword-relationship (Searchword)",
		"tx_civserv_service.sv_searchword_PLACEHOLDER" => "Searchword Info",
		"tx_civserv_service.sv_position" => "Service-Position-Relationship (Position)",
		"tx_civserv_service.sv_position_PLACEHOLDER" => "Position info",
		"tx_civserv_service.sv_organisation" => "Service-Organisation-Relationship (Organisation)",
		"tx_civserv_service.sv_organisation_PLACEHOLDER" => "Organisation Info",
		"tx_civserv_service.sv_navigation" => "Service-Navigation-Relationship (Navigation)",
		"tx_civserv_service.sv_navigation_PLACEHOLDER" => "Navigation Info",
		"tx_civserv_service.sv_3rdparty_checkbox" => "Third-Party Service?",
		"tx_civserv_service.sv_3rdparty_link" => "Link to Third-Party Service",
		"tx_civserv_service.sv_3rdparty_name" => "Name of Third-Party",
		"tx_civserv_service.sv_logical_display" => "Technical logic field (Do not change)",
		"tx_civserv_service.sv_region" => "Service available for",
		"tx_civserv_service.sv_region_PLACEHOLDER_live" => "Region Info",
		"tx_civserv_service_sv_position_mm" => "Service-Position-Relationship",
		"tx_civserv_service_sv_position_mm.sp_descr" => "Short additional description",
		"tx_civserv_service_sv_position_mm.sp_descr_PLACEHOLDER" => "Service-Position Info",
		"tx_civserv_model_service_temp" => "attended model service",
		"tx_civserv_model_service" => "Model service",
		"tx_civserv_model_service.ms_name" => "Name",
		"tx_civserv_model_service.ms_synonym1" => "1st Synonym",
		"tx_civserv_model_service.ms_synonym2" => "2nd Synonym",
		"tx_civserv_model_service.ms_synonym3" => "3rd Synonym",
		"tx_civserv_model_service.ms_descr_short" => "Short description",
		"tx_civserv_model_service.ms_descr_long" => "Detailed description",
		"tx_civserv_model_service.ms_image" => "Image",
		"tx_civserv_model_service.ms_image_text" => "Image text",
		"tx_civserv_model_service.ms_fees" => "Fees, rates and tolls",
		"tx_civserv_model_service.ms_documents" => "Necessary documents",
		"tx_civserv_model_service.ms_legal_global" => "Legal basis (Global issues)",
		"tx_civserv_model_service.ms_searchword" => "Modelservice-Searchword-Relationship (Searchword)",
		"tx_civserv_model_service.ms_mandant" => "Responsible mandant for processing",
		"tx_civserv_model_service.ms_approver_one" => "Control instance 1",
		"tx_civserv_model_service.ms_approver_two" => "Control instance 2",
		"tx_civserv_model_service_temp.ms_comment_approver_one" => "Comment from the first Control instance",
		"tx_civserv_model_service_temp.ms_comment_approver_two" => "Comment from the second Control instance",
		"tx_civserv_model_service_temp.ms_additional_label" => "Title",
		"tx_civserv_form" => "Form",
		"tx_civserv_form.fo_number" => "Number",
		"tx_civserv_form.fo_orga_code" => "Organisation Code",
		"tx_civserv_form.fo_codename" => "Internal Form Name",
		"tx_civserv_form.fo_name" => "Name",
		"tx_civserv_form.fo_descr" => "Description",
		"tx_civserv_form.fo_category" => "Category",
		"tx_civserv_form.fo_url" => "Link (URL to external form)",
		"tx_civserv_form.fo_created_date" => "Creation date",
		"tx_civserv_form.fo_status.I.0" => "None",
		"tx_civserv_form.fo_status.I.1" => "Filling",
		"tx_civserv_form.fo_status.I.2" => "Sending",
		"tx_civserv_form.fo_status.I.3" => "Reading",
		"tx_civserv_form.fo_status" => "Status",
		"tx_civserv_form.fo_formular_file" => "uploaded for",
		"tx_civserv_form.fo_external_checkbox" => "External form",
     	"tx_civserv_form.fo_target" => "start form in same browser window",
		"tx_civserv_building" => "Building",
		"tx_civserv_building.bl_number" => "Number",
		"tx_civserv_building.bl_name" => "Name",
		"tx_civserv_building.bl_name_to_show" => "Name",
		"tx_civserv_building.bl_descr" => "Description",
		"tx_civserv_building.bl_mail_street" => "Street (postal)",
		"tx_civserv_building.bl_mail_pob" => "Post-office box",
		"tx_civserv_building.bl_mail_postcode" => "Post Code (postalisch)",
		"tx_civserv_building.bl_mail_city" => "City (postal)",
		"tx_civserv_building.bl_building_street" => "Street (building)",
		"tx_civserv_building.bl_building_postcode" => "Post code (building)",
		"tx_civserv_building.bl_building_city" => "City (building)",
		"tx_civserv_building.bl_pubtrans_stop" => "Stopping point (public transport services)",
		"tx_civserv_building.bl_pubtrans_url" => "Public transport services - Link (URL)",
		"tx_civserv_building.bl_citymap_url" => "City Map - Link (URL)",
		"tx_civserv_building.bl_image" => "Image",
		"tx_civserv_building.bl_telephone" => "Telephone number (information)",
		"tx_civserv_building.bl_fax" => "Fax number (information)",
		"tx_civserv_building.bl_email" => "E-Mail (information)",
		"tx_civserv_building.bl_floor" => "Building-Floor-Relationship (Floor)",
		"tx_civserv_building.bl_floor_PLACEHOLDER" => "Building-Floor-Relationship Info",
		"tx_civserv_room" => "Room",
		"tx_civserv_room.ro_number" => "Number",
		"tx_civserv_room.ro_name" => "Name",
		"tx_civserv_room.ro_label" => "Location of room",
		"tx_civserv_room.ro_descr" => "Description",
		"tx_civserv_room.ro_telephone" => "Telephone number",
		"tx_civserv_room.ro_fax" => "Fax number",
		"tx_civserv_room.ro_floor" => "Room-Floor-Relationship (Floor)",
		"tx_civserv_room.ro_building" => "Room-Building-Relationship (Buidling)",
		"tx_civserv_room.rbf_building_bl_floor" => "Room-Building-Floor-Relationship (Building-Floor)",
		"tx_civserv_floor" => "Floor",
		"tx_civserv_floor.fl_number" => "Number (story)",
		"tx_civserv_floor.fl_descr" => "Description",
		"tx_civserv_employee" => "Employee",
		"tx_civserv_employee.em_number" => "Personnel number",
		"tx_civserv_employee.em_address.I.2" => "Mrs.",
		"tx_civserv_employee.em_address.I.1" => "Mr.",
		"tx_civserv_employee.em_address" => "Form of address",
		"tx_civserv_employee.em_title" => "Title",
		"tx_civserv_employee.em_name" => "Name",
		"tx_civserv_employee.em_firstname" => "First name",
		"tx_civserv_employee.em_telephone" => "Telephone number",
		"tx_civserv_employee.em_fax" => "Fax number",
		"tx_civserv_employee.em_mobile" => "Mobile phone number",
		"tx_civserv_employee.em_email" => "E-Mail",
		"tx_civserv_employee.em_image" => "Image",
		"tx_civserv_employee.em_datasec" => "Data security clearance",
		"tx_civserv_employee.em_pseudo" => "Pseudo employee",
		"tx_civserv_employee.em_hours" => "Office hours",
		"tx_civserv_employee.em_position" => "Employee-Position-Relationship (Position)",
		"tx_civserv_employee.em_position_PLACEHOLDER" => "Position Info",
		"tx_civserv_organisation" => "Organisation",
		"tx_civserv_organisation.or_number" => "Organisation ID",
		"tx_civserv_organisation.or_index" => "Organsiation Index",
		"tx_civserv_organisation.or_code" => "Organisation Short Title",
		"tx_civserv_organisation.or_name" => "Name",
		"tx_civserv_organisation.or_synonym1" => "1. Synonym",
		"tx_civserv_organisation.or_synonym2" => "2. Synonym",
		"tx_civserv_organisation.or_synonym3" => "3. Synonym",		
		"tx_civserv_organisation.or_hours" => "Office hours",
		"tx_civserv_organisation.or_telephone" => "Telephone number",
		"tx_civserv_organisation.or_fax" => "Fax number",
		"tx_civserv_organisation.or_email" => "E-Mail",
		"tx_civserv_organisation.or_image" => "Image",
		"tx_civserv_organisation.or_infopage" => "Information page",
		"tx_civserv_organisation.or_addinfo" => "Additional information",
		"tx_civserv_organisation.or_addlocation" => "Additional Location",
		"tx_civserv_organisation.or_structure" => "Below the following hierarchy level",
		"tx_civserv_organisation.or_building" => "Organisation-Building-Relationship (Building)",
		"tx_civserv_organisation.or_building_to_show" => "Building(s) to be shown in FE",
		"tx_civserv_organisation.or_supervisor" => "Organisation supervisor",
		"tx_civserv_organisation.or_show_supervisor" => "Display supervisor",
		"tx_civserv_officehours" => "Office hours",
		"tx_civserv_officehours.oh_descr" => "Description",
		"tx_civserv_officehours.oh_descr" => "Name",
		"tx_civserv_officehours.oh_manual_checkbox" => "Free text",
		"tx_civserv_officehours.oh_start_morning" => "Start time (Morning)",
		"tx_civserv_officehours.oh_end_morning" => "End time (Morning)",
		"tx_civserv_officehours.oh_start_afternoon" => "Start time (Afternoon)",
		"tx_civserv_officehours.oh_end_afternoon" => "End time (Afternoon)",
		"tx_civserv_officehours.oh_freestyle" => "...on appointment",
		"tx_civserv_officehours.oh_weekday" => "Officehours-Weekday-Relationship (Weekday)",
		"tx_civserv_search_call" => "Search call",
		"tx_civserv_search_call.sc_search_call" => "Search call",
		"tx_civserv_search_call.sc_time" => "Time",
		"tx_civserv_search_call.sc_searchwords" => "Searchcall-Searchword-Relationship (Search word statistic)",
		"tx_civserv_search_word" => "Search word",
		"tx_civserv_search_word.sw_search_word" => "Search word",
		"tx_civserv_position" => "Position",
		"tx_civserv_position.po_name" => "Formal Name (internal)",
		"tx_civserv_position.po_nice_name" => "Nice Name",
		"tx_civserv_position.po_descr" => "Description",
		"tx_civserv_position.po_organisation" => "Position-Organisation-Relationship (Organisation)",
		"tx_civserv_position.po_number" => "Position ID",
		"tx_civserv_navigation" => "Navigation",
		"tx_civserv_navigation.nv_name" => "Name",
		"tx_civserv_navigation.nv_label" => "Label",
		"tx_civserv_navigation.nv_structure" => "Below the following hierarchy level",
		"tx_civserv_category" => "Category",
		"tx_civserv_category.ca_name" => "Name",
		"tx_civserv_weekday" => "Weekday",
		"tx_civserv_weekday.wd_name" => "Name",
		"tx_civserv_building_bl_floor_mm" => "Building-Floor-Relationship",
		"tx_civserv_employee_em_position_mm" => "Employee-Position-Relationship (MM-Relation)",
		"tx_civserv_employee_em_position_mm.ep_datasec" => "Publish as Contact Person",
		"tx_civserv_employee_em_position" => "Employee-Position-Relationship",
		"tx_civserv_employee_em_position.ep_room" => "Employee-Position-Room-Relationship",
		"tx_civserv_employee_em_position.uid_local_label" => "Employee",
		"tx_civserv_employee_em_position.uid_foreign_label" => "Position",
		"tx_civserv_employee_em_position.ep_telephone" => "Telephone number",
		"tx_civserv_employee_em_position.ep_fax" => "Fax number",
		"tx_civserv_employee_em_position.ep_mobile" => "Mobile phone number",
		"tx_civserv_employee_em_position.ep_email" => "E-Mail",
		"tx_civserv_weekday_101" => "Monday to Friday",
		"tx_civserv_weekday_102" => "Monday to Thursday",
		"tx_civserv_weekday_103" => "Monday to Wednesday",
		"tx_civserv_weekday_104" => "Tuesday to Saturday",
		"tx_civserv_weekday_201" => "Monday",
		"tx_civserv_weekday_202" => "Tuesday",
		"tx_civserv_weekday_203" => "Wednesday",
		"tx_civserv_weekday_204" => "Thursday",
		"tx_civserv_weekday_205" => "Friday",
		"tx_civserv_weekday_206" => "Saturday",
		"tx_civserv_weekday_207" => "Sunday",
		"tx_civserv_weekday_301" => "Holiday",
		"tx_civserv_weekday_302" => "Sun- and Holiday",
		"tx_civserv_weekday_401" => "Extra hours",
		"tx_civserv_weekday_402" => "Please note",
		"tx_civserv_weekday_403" => "no Weekday specified",
		"tt_content.list_type_pi1" => "Virtual Civil Services (civserv)",	 // Name of Plug-in (for Flag)
		"tt_content.list_type_pi2" => "civserv - pi2 extended employeelist",
		"tt_content.list_type_pi3" => "civserv - pi3 extended employeelist",
	),
	"de" => Array (
		"tx_civserv.doktype.model_service" => "Model Service Container",
		"tx_civserv_conf_mandant" => "Mandant",
		"tx_civserv_conf_mandant.cm_community_name" => "Mandantenbezeichnung",
		"tx_civserv_conf_mandant.cm_community_id" => "Gemeindekennziffer",
		"tx_civserv_conf_mandant.cm_uid" => "UID des Einstiegspunkt des Mandanten im Verzeichnisbaum",
		"tx_civserv_conf_mandant.cm_circumstance_uid" => "Einstiegspunkt in Lebenslagen",
		"tx_civserv_conf_mandant.cm_usergroup_uid" => "Einstiegspunkt in Nutzergruppen",
		"tx_civserv_conf_mandant.cm_organisation_uid" => "Einstiegspunkt in Organisationen",
		"tx_civserv_conf_mandant.cm_service_folder_uid" => "UID des Ordners 'Dienstleistungen'",
		"tx_civserv_conf_mandant.cm_external_folder_uid" => "UID des Ordners 'Externe Dienstleistungen'",
		"tx_civserv_conf_mandant.cm_alternative_language_folder_uid" => "UID des Ordners 'Anderssprachige Dienstleistungen'",
		"tx_civserv_conf_mandant.cm_building_folder_uid" => "UID des Ordners 'R�umlichkeiten'",
		"tx_civserv_conf_mandant.cm_model_service_temp_uid" => "UID des Ordners 'betreute Musteranliegen'",
		"tx_civserv_conf_mandant.cm_page_uid" => "UID der Front-End-Seite",
		"tx_civserv_conf_mandant.cm_search_uid" => "UID der Fulltext-Search-Seite",
		"tx_civserv_conf_mandant.cm_alternative_page_uid" => "UID der alternativen FE-Seite",
		"tx_civserv_conf_mandant.cm_info_folder_uid" => "UID des Ordners 'Infoseiten'",
		"tx_civserv_conf_mandant.cm_community_type" => "Typ der Kommune",
		"tx_civserv_conf_mandant.cm_target_email" => "Zielmailadresse f�r Benachtrichtigungen im Workflow (als Kontrollinstanz)",
		"tx_civserv_conf_mandant.cm_employeesearch" => "Mitarbeiterdaten von Suchmaschine erfassen lassen?",
		"tx_civserv_conf_mandant.cm_subtitle_contains_organisation" => "Organisations-UIDs im Page-Subtitle der DienstleistungsOrdner (manuell eintragen!!)?",
		"tx_civserv_external_service" => "Externe Dienstleistung",
		"tx_civserv_external_service.es_external_service" => "Externe Dienstleistungen",
		"tx_civserv_external_service.es_name" => "Name",
		"tx_civserv_external_service.es_navigation" => "Navigationselemente (Lebenslagen bzw. Nutzergruppen)",
		"tx_civserv_region" => "Region",
		"tx_civserv_region.re_name" => "Regionsbezeichnung",
		"be_users.tx_civserv_approv" => "Freigabeberechtigt",
		"be_users.tx_civserv_employee" => "Mitarbeiter",
		"tx_civserv_service" => "Dienstleistungen",
		"tx_civserv_service.sv_type" => "Intranet",
#		"tx_civserv_service.sv_type" => "Intra- oder Internet?",
		"tx_civserv_service.sv_globalnet" => "egal",
		"tx_civserv_service.sv_intranet" => "Intranet Service",
		"tx_civserv_service.sv_internet" => "Internet Service",
		"tx_civserv_service.sv_name" => "Name",
		"tx_civserv_service.sv_synonym1" => "1. Synonym",
		"tx_civserv_service.sv_synonym2" => "2. Synonym",
		"tx_civserv_service.sv_synonym3" => "3. Synonym",
		"tx_civserv_service.sv_descr_short" => "Kurzbeschreibung",
		"tx_civserv_service.sv_descr_long" => "Ausf�hrliche Beschreibung",
		"tx_civserv_service.sv_image" => "Bild",
		"tx_civserv_service.sv_image_text" => "Bild Text (Barrierefreiheit)",
		"tx_civserv_service.sv_fees" => "Geb�hren",
		"tx_civserv_service.sv_documents" => "Ben�tigte Unterlagen",
		"tx_civserv_service.sv_legal_local" => "Rechtsgrundlagen (Ortsrecht)",
		"tx_civserv_service.sv_legal_global" => "Rechtsgrundlagen (Allgemein)",
		"tx_civserv_service.sv_model_service" => "Musteranliegen",
		"tx_civserv_service.sv_similar_services" => "�hnliche Dienstleistungen",
		"tx_civserv_service.sv_similar_services_PLACEHOLDER" => "�hnliche Dienstleistungen Info",
		"tx_civserv_service.sv_service_version" => "Dienstleistungsversion",
		"tx_civserv_service.sv_form" => "Formulare",
		"tx_civserv_service.sv_form_PLACEHOLDER" => "Formulare Info",
		"tx_civserv_service.sv_searchword" => "Suchw�rter",
		"tx_civserv_service.sv_searchword_PLACEHOLDER" => "Suchw�rter Info",
		"tx_civserv_service.sv_position" => "Stellen",
		"tx_civserv_service.sv_position_PLACEHOLDER" => "Stellen Info",
		"tx_civserv_service.sv_organisation" => "Organisationen",
		"tx_civserv_service.sv_organisation_PLACEHOLDER" => "Organisationen Info",
		"tx_civserv_service.sv_navigation" => "Navigationselemente (Lebenslagen bzw. Nutzergrupen)",
		"tx_civserv_service.sv_navigation_PLACEHOLDER" => "Navigationselemente Info",
		"tx_civserv_service.sv_3rdparty_checkbox" => "Externe Anbieter",
		"tx_civserv_service.sv_3rdparty_link" => "Link f�r externe Dienstleistung",
		"tx_civserv_service.sv_3rdparty_name" => "Name des externen Dienstleisters",
		"tx_civserv_service.sv_region" => "Dienstleistung in folgenden Regionen verf�gbar machen",
		"tx_civserv_service.sv_region_PLACEHOLDER_live" => "Region Info",
		"tx_civserv_service.sv_logical_display" => "Technisches Feld (Anzeigelogik)",
		"tx_civserv_service_sv_position_mm" => "Dienstleistung-Stellen-Zuordnung",
		"tx_civserv_service_sv_position_mm.sp_descr" => "Kurze Zusatzbeschreibung",
		"tx_civserv_service_sv_position_mm.sp_descr_PLACEHOLDER" => "Mitarbeiter-Stellenzugeh�rigkeit Info",
		"tx_civserv_model_service_temp" => "betreute Musteranliegen",
		"tx_civserv_model_service" => "Musteranliegen",
		"tx_civserv_model_service.ms_name" => "Name",
		"tx_civserv_model_service.ms_synonym1" => "1. Synonym",
		"tx_civserv_model_service.ms_synonym2" => "2. Synonym",
		"tx_civserv_model_service.ms_synonym3" => "3. Synonym",
		"tx_civserv_model_service.ms_descr_short" => "Kurzbeschreibung",
		"tx_civserv_model_service.ms_descr_long" => "Ausf�hrliche Beschreibung",
		"tx_civserv_model_service.ms_image" => "Bild",
		"tx_civserv_model_service.ms_image_text" => "Bild Text (Barrierefreiheit)",
		"tx_civserv_model_service.ms_fees" => "Geb�hren",
		"tx_civserv_model_service.ms_documents" => "Ben�tigte Unterlagen",
		"tx_civserv_model_service.ms_legal_global" => "Rechtsgrundlagen (Allgemein)",
		"tx_civserv_model_service.ms_searchword" => "Suchw�rter",
		"tx_civserv_model_service.ms_mandant" => "F�r Bearbeitung zust�ndiger Mandant",
		"tx_civserv_model_service.ms_approver_one" => "Kontrollinstanz 1",
		"tx_civserv_model_service.ms_approver_two" => "Kontrollinstanz 2",
		"tx_civserv_model_service_temp.ms_comment_approver_one" => "Ablehnungskommentar der ersten Kontrollinstanz",
		"tx_civserv_model_service_temp.ms_comment_approver_two" => "Ablehnungskommentar der zweiten Kontrollinstanz",
		"tx_civserv_model_service_temp.ms_additional_label" => "Titel",
		"tx_civserv_form" => "Formular",
		"tx_civserv_form.fo_number" => "Nummer",
		"tx_civserv_form.fo_orga_code" => "�mterk�rzel",
		"tx_civserv_form.fo_codename" => "Verwaltungsinterner Formularname",
		"tx_civserv_form.fo_name" => "Name",
		"tx_civserv_form.fo_descr" => "Beschreibung",
		"tx_civserv_form.fo_category" => "Kategorie",
		"tx_civserv_form.fo_url" => "Link (URL zu externem Formular)",
		"tx_civserv_form.fo_created_date" => "Erstellungsdatum",
		"tx_civserv_form.fo_status.I.0" => "-",
		"tx_civserv_form.fo_status.I.1" => "Ausf�llen",
		"tx_civserv_form.fo_status.I.2" => "Verschicken",
		"tx_civserv_form.fo_status.I.3" => "Lesen",
		"tx_civserv_form.fo_status" => "Status",
		"tx_civserv_form.fo_formular_file" => "Formular",
		"tx_civserv_form.fo_external_checkbox" => "Externes Formular",
		"tx_civserv_form.fo_target" => "im selben Fenster starten",
		"tx_civserv_building" => "Geb�ude",
		"tx_civserv_building.bl_number" => "Nummer",
		"tx_civserv_building.bl_name" => "Name",
		"tx_civserv_building.bl_name_to_show" => "Anzeige-Name",
		"tx_civserv_building.bl_descr" => "Beschreibung",
		"tx_civserv_building.bl_mail_street" => "Stra�e (postalisch)",
		"tx_civserv_building.bl_mail_pob" => "Postfach",
		"tx_civserv_building.bl_mail_postcode" => "PLZ (postalisch)",
		"tx_civserv_building.bl_mail_city" => "Ort (postalisch)",
		"tx_civserv_building.bl_building_street" => "Stra�e (Haus)",
		"tx_civserv_building.bl_building_postcode" => "PLZ (Haus)",
		"tx_civserv_building.bl_building_city" => "Ort (Haus)",
		"tx_civserv_building.bl_pubtrans_stop" => "Haltestelle (�PNV)",
		"tx_civserv_building.bl_pubtrans_url" => "�PNV - Link (URL)",
		"tx_civserv_building.bl_citymap_url" => "Stadtplan - Link (URL)",
		"tx_civserv_building.bl_image" => "Bild",
		"tx_civserv_building.bl_telephone" => "Telefonnummer (Information)",
		"tx_civserv_building.bl_fax" => "Faxnummer (Information)",
		"tx_civserv_building.bl_email" => "E-Mail (Information)",
		"tx_civserv_building.bl_floor" => "Etagen",
		"tx_civserv_building.bl_floor_PLACEHOLDER" => "Etagen Info",
		"tx_civserv_room" => "Raum",
		"tx_civserv_room.ro_number" => "Nummer",
		"tx_civserv_room.ro_name" => "Name",
		"tx_civserv_room.ro_label" => "Raumzuordnung",
		"tx_civserv_room.ro_descr" => "Beschreibung",
		"tx_civserv_room.ro_telephone" => "Telefonnummer",
		"tx_civserv_room.ro_fax" => "Faxnummer",
		"tx_civserv_room.ro_floor" => "Etagen",
		"tx_civserv_room.ro_building" => "Geb�ude",
		"tx_civserv_room.rbf_building_bl_floor" => "Geb�ude-Etage-Kombinationen",
		"tx_civserv_floor" => "Etage",
		"tx_civserv_floor.fl_number" => "Nummer (Stockwerk)",
		"tx_civserv_floor.fl_descr" => "Bezeichnung",
		"tx_civserv_employee" => "Mitarbeiter",
		"tx_civserv_employee.em_number" => "ID",
		"tx_civserv_employee.em_address.I.2" => "Frau",
		"tx_civserv_employee.em_address.I.1" => "Herr",
		"tx_civserv_employee.em_address" => "Anrede",
		"tx_civserv_employee.em_title" => "Titel",
		"tx_civserv_employee.em_name" => "Name",
		"tx_civserv_employee.em_firstname" => "Vorname",
		"tx_civserv_employee.em_telephone" => "Telefonnummer",
		"tx_civserv_employee.em_fax" => "Faxnummer",
		"tx_civserv_employee.em_mobile" => "Dienstnummer (Mobiltelefon)",
		"tx_civserv_employee.em_email" => "E-Mail",
		"tx_civserv_employee.em_image" => "Bild",
		"tx_civserv_employee.em_datasec" => "Datenschutzfreigabe",
		"tx_civserv_employee.em_pseudo" => "Pseudo-Mitarbeiter",
		"tx_civserv_employee.em_hours" => "�ffentliche Erreichbarkeitszeiten",
		"tx_civserv_employee.em_position" => "Stellen",
		"tx_civserv_employee.em_position_PLACEHOLDER" => "Stellen Info",
		"tx_civserv_organisation" => "Organisation",
		"tx_civserv_organisation.or_number" => "Organisations-Id",
		"tx_civserv_organisation.or_index" => "Organsiation Kennziffer",
		"tx_civserv_organisation.or_code" => "�mter-K�rzel",
		"tx_civserv_organisation.or_name" => "Name",
		"tx_civserv_organisation.or_synonym1" => "1. Synonym",
		"tx_civserv_organisation.or_synonym2" => "2. Synonym",
		"tx_civserv_organisation.or_synonym3" => "3. Synonym",				
		"tx_civserv_organisation.or_hours" => "�ffnungszeiten",
		"tx_civserv_organisation.or_telephone" => "Telefonnummer",
		"tx_civserv_organisation.or_fax" => "Faxnummer",
		"tx_civserv_organisation.or_email" => "E-Mail",
		"tx_civserv_organisation.or_image" => "Bild",
		"tx_civserv_organisation.or_infopage" => "Informationsseite",
		"tx_civserv_organisation.or_addinfo" => "Zusatzinformationen",
		"tx_civserv_organisation.or_addlocation" => "Zusatz-Address-Informationen",
		"tx_civserv_organisation.or_structure" => "Unterhalb folgender Hierarchieebene",
		"tx_civserv_organisation.or_building" => "Geb�ude",
		"tx_civserv_organisation.or_building_to_show" => "Anzeige-Geb�ude",
		"tx_civserv_organisation.or_supervisor" => "Organisations-Leiter",
		"tx_civserv_organisation.or_show_supervisor" => "Den Leiter anzeigen",
		"tx_civserv_officehours" => "�ffnungszeiten",
		"tx_civserv_officehours.oh_descr" => "Bezeichnung",
		"tx_civserv_officehours.oh_name" => "Benennung",
		"tx_civserv_officehours.oh_manual_checkbox" => "Freie Texteingabe",
		"tx_civserv_officehours.oh_start_morning" => "Anfangszeit (Vormittags)",
		"tx_civserv_officehours.oh_end_morning" => "Endzeit (Vormittags)",
		"tx_civserv_officehours.oh_start_afternoon" => "Anfangszeit (Nachmittags)",
		"tx_civserv_officehours.oh_end_afternoon" => "Endzeit (Nachmittags)",
		"tx_civserv_officehours.oh_freestyle" => "\"...nach Vereinbarung\" o.�",
		"tx_civserv_officehours.oh_weekday" => "Wochentage",
		"tx_civserv_search_call" => "Suchaufruf",
		"tx_civserv_search_call.sc_search_call" => "Suchaufruf",
		"tx_civserv_search_call.sc_time" => "Zeit",
		"tx_civserv_search_call.sc_searchwords" => "Suchwort Statistik",
		"tx_civserv_search_word" => "Suchwort",
		"tx_civserv_search_word.sw_search_word" => "Suchwort",
		"tx_civserv_position" => "Stelle",
		"tx_civserv_position.po_name" => "Name (formal name)",
		"tx_civserv_position.po_nice_name" => "Stellenbezeichnung (sprechender Name)",
		"tx_civserv_position.po_descr" => "Beschreibung",
		"tx_civserv_position.po_organisation" => "Organisationen",
		"tx_civserv_position.po_number" => "Stellen-ID",
		"tx_civserv_navigation" => "Navigation",
		"tx_civserv_navigation.nv_name" => "Name",
		"tx_civserv_navigation.nv_label" => "Label",
		"tx_civserv_navigation.nv_structure" => "Unterhalb folgender Hierarchieebene",
		"tx_civserv_category" => "Kategorie",
		"tx_civserv_category.ca_name" => "Name",
		"tx_civserv_weekday" => "Wochentag",
		"tx_civserv_weekday.wd_name" => "Name",
		"tx_civserv_building_bl_floor_mm" => "Geb�ude-Etage-Zuordnung",
		"tx_civserv_employee_em_position_mm" => "Mitarbeiter-Stelle-Zuordnung",
		"tx_civserv_employee_em_position_mm.ep_datasec" => "Als Ansprechpartner anzeigen",
		"tx_civserv_employee_em_position" => "Mitarbeiter-Stelle-Zuordnung",
		"tx_civserv_employee_em_position.ep_room" => "R�ume",
		"tx_civserv_employee_em_position.uid_local_label" => "Mitarbeiter",
		"tx_civserv_employee_em_position.uid_foreign_label" => "Stelle",
		"tx_civserv_employee_em_position.ep_telephone" => "Telefonnummer",
		"tx_civserv_employee_em_position.ep_fax" => "Faxnummer",
		"tx_civserv_employee_em_position.ep_mobile" => "Dienstnummer (Mobiltelefon)",
		"tx_civserv_employee_em_position.ep_email" => "E-Mail",
		"tx_civserv_weekday_101" => "Montag bis Freitag",
		"tx_civserv_weekday_102" => "Montag bis Donnerstag",
		"tx_civserv_weekday_103" => "Montag bis Mittwoch",
		"tx_civserv_weekday_104" => "Dienstag bis Samstag",
		"tx_civserv_weekday_201" => "Montag",
		"tx_civserv_weekday_202" => "Dienstag",
		"tx_civserv_weekday_203" => "Mittwoch",
		"tx_civserv_weekday_204" => "Donnerstag",
		"tx_civserv_weekday_205" => "Freitag",
		"tx_civserv_weekday_206" => "Samstag",
		"tx_civserv_weekday_207" => "Sonntag",
		"tx_civserv_weekday_301" => "An Feiertagen",
		"tx_civserv_weekday_302" => "Sonn- und Feiertags",
		"tx_civserv_weekday_401" => "zus�tzlich",
		"tx_civserv_weekday_402" => "Hinweis",
		"tx_civserv_weekday_403" => "keine Wochentag-Angabe",
		"tt_content.list_type_pi1" => "Virtuelle Verwaltung (civserv)",	 // Name of Plug-in (for Flag)
		"tt_content.list_type_pi2" => "civserv - pi2 erweiterte Mitarbeiterliste",	 // Name of Plug-in (for Flag)
		"tt_content.list_type_pi3" => "civserv - pi3 erweiterte Mitarbeiterliste",
	),
);
?>