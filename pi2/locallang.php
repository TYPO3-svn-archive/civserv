<?php
/***************************************************************
*  Copyright notice - www4.citeq.de: osiris_muenster special edition
*
*  (c) 2004 ProService (osiris@ercis.de)
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
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
 * Language labels for plugin 'civserv_pi2'
 *
 * This file is detected by the translation tool.
 *
 *
 * @author	Stephan D�mmer <sduemmer@uni-muenster.de>
 * @author	Stefan Meesters <meesters@uni-muenster.de>
 * @package TYPO3
 * @subpackage tx_civserv
 * @version 1.0
 *
 */

$LOCAL_LANG = Array (
	'default' => Array (
		'back' => 'back',
		'pi_list_browseresults_prev' => '< previous',
		'pi_list_browseresults_next' => 'next >',
		'pi_list_browseresults_page' => '',
		'pi_list_browseresults_displays' => 'Items ###SPAN_BEGIN###%s to %s</span> of ###SPAN_BEGIN###%s</span>',
		'pi_list_browseresults_noResults' => 'Sorry, no items were found.',
		'pi_list_searchBox_search' => 'Search',
		'pi_list_searchBox_searchform' => 'Search form',
		'pi_list_searchBox_header' => 'Keyword search',
		'pi_list_searchBox_searchkey' => 'Please enter here your search item',
		'pi_list_searchBox_submit' => 'Klick here, to submit the search query',
		'pi_list_searchBox_defaultValue' => 'search item',
		'tx_civserv_pi2_circumstance.circumstance_tree' => 'Circumstances', //modification
		'tx_civserv_pi2_circumstance.circumstance_tree.heading' => 'Circumstances in %s',		//modification
		'tx_civserv_pi2_community_search_label' => 'You have not choosen a community yet. Please choose your community.',
		'tx_civserv_pi2_debit_form.debit_form' => 'Authorisation form for debit entries from bank accounts',
		'tx_civserv_pi2_debit_form.serviceName' => 'Name of service, for which the debit authorisation is granted',
		'tx_civserv_pi2_debit_form.serviceSelect' => 'Please select the service, for which the debit authorisation shall be granted',
		'tx_civserv_pi2_debit_form.cashNumber' => 'Cash number / personal key',
		'tx_civserv_pi2_debit_form.bankName' => 'Name of bank',
		'tx_civserv_pi2_debit_form.bankCode' => 'Bank code',
		'tx_civserv_pi2_debit_form.accountNumber' => 'Account number',
		'tx_civserv_pi2_debit_form.accountHolder' => 'Name of account holder',
		'tx_civserv_pi2_debit_form.firstname' => 'First name',
		'tx_civserv_pi2_debit_form.surname' => 'Surname',
		'tx_civserv_pi2_debit_form.agreement' => 'I agree, that all unsolicited data provided by me are stored for the purpose of fulfilling the duties of the city treasury.',
		'tx_civserv_pi2_debit_form.optional_data' => 'Optional information',
		'tx_civserv_pi2_debit_form.phone' => 'Phone',
		'tx_civserv_pi2_debit_form.email' => 'E-Mail',
		'tx_civserv_pi2_debit_form.button_send' => 'Send',
		'tx_civserv_pi2_debit_form.button_reset' => 'Cancel',
		'tx_civserv_pi2_debit_form.no_results' => 'Debit form uid not set in transaction configuration table!',
		'tx_civserv_pi2_debit_form.error_cashNumber' => 'Please enter a valid cash number!',
		'tx_civserv_pi2_debit_form.error_bankName','Please enter the name of your bank!',
		'tx_civserv_pi2_debit_form.error_bankCode' => 'Please enter a valid bank code!',
		'tx_civserv_pi2_debit_form.error_accountNumber' => 'Please enter a valid account number!',
		'tx_civserv_pi2_debit_form.error_firstname' => 'Please enter the first name of the account holder!',
		'tx_civserv_pi2_debit_form.error_surname' => 'Please enter the surname of the account holder!',
		'tx_civserv_pi2_debit_form.error_email' => 'Please enter a valid email address!',
		'tx_civserv_pi2_debit_form.error_service' => 'No debit form found for this service!',
		'tx_civserv_pi2_debit_form.complete' => 'Thank you! Your data has been successfully stored in our system.',
		'tx_civserv_pi2_email_form.email_form' => 'E-Mail Form',
		'tx_civserv_pi2_email_form.contact_form' => 'Contact ###HOSTER###',
		'tx_civserv_pi2_email_form.notice' => 'Please enter your postal address or email address or phone/fax number, so that we can get in contact with you.',
		'tx_civserv_pi2_email_form.title' => 'Title',
		'tx_civserv_pi2_email_form.chose' => 'Please chose',
		'tx_civserv_pi2_email_form.female' => 'Ms.',
		'tx_civserv_pi2_email_form.male' => 'Mr.',
		'tx_civserv_pi2_email_form.firstname' => 'Firstname',
		'tx_civserv_pi2_email_form.surname' => 'Surname',
		'tx_civserv_pi2_email_form.street' => 'Street, Nr.',
		'tx_civserv_pi2_email_form.postcode' => 'Postcode',
		'tx_civserv_pi2_email_form.city' => 'City',
		'tx_civserv_pi2_email_form.email' => 'E-Mail',
		'tx_civserv_pi2_email_form.phone' => 'Phone',
		'tx_civserv_pi2_email_form.fax' => 'Fax',
		'tx_civserv_pi2_email_form.subject' => 'Subject',
		'tx_civserv_pi2_email_form.bodytext' => 'Your text',
		'tx_civserv_pi2_email_form.submit' => 'Send e-mail',
		'tx_civserv_pi2_email_form.reset' => 'Reset',
		'tx_civserv_pi2_email_form.required' => 'required',
		'tx_civserv_pi2_email_form.error_org_id' => 'Wrong organisation id or organisation does not exist!',
		'tx_civserv_pi2_email_form.error_sv_id' => 'Wrong service id, employee id or position id. No email address found!',
		'tx_civserv_pi2_email_form.error_pos_id' => 'Wrong employee id or position id. No email address found!',
		'tx_civserv_pi2_email_form.error_general' => 'Organisation id, employee id, position id and service id wrong or not set. No email address found!',
		'tx_civserv_pi2_email_form.error_surname' => 'Please enter your surname!',
		'tx_civserv_pi2_email_form.error_firstname' => 'Please enter your first name!',
		'tx_civserv_pi2_email_form.error_postcode' => 'Please enter a valid postcode!',
		'tx_civserv_pi2_email_form.error_subject' => 'Please enter a subject!',
		'tx_civserv_pi2_email_form.error_bodytext' => 'Please enter your text!',
		'tx_civserv_pi2_email_form.complete' => 'Thank you! Your message has been successfully sent ',
		'tx_civserv_pi2_email_form.to' => 'to ',
		'tx_civserv_pi2_employee.employee_male' => 'Employee',
		'tx_civserv_pi2_employee.employee_female' => 'Employee',
		'tx_civserv_pi2_employee.employee_plural' => 'Employees',
		'tx_civserv_pi2_employee.title' => 'Title',
		'tx_civserv_pi2_employee.hours' => 'Working hours',
		'tx_civserv_pi2_employee.foto.alt_text' => 'picture of the employee',
		'tx_civserv_pi2_employee.organisation' => 'Organisation',
		'tx_civserv_pi2_employee.room' => 'Room',
		'tx_civserv_pi2_employee.datasec' => 'Datasec enabled! Employee is not shown.',
		'tx_civserv_pi2_employee.image_male' => 'Image of employee',
		'tx_civserv_pi2_employee.image_female' => 'Image of employee',
		'tx_civserv_pi2_employee.officehours' => 'In the table are the working hours of ###EMPLOYEE### shown.',
		'tx_civserv_pi2_error.invalid_mode' => 'Invalid mode',
		'tx_civserv_pi2_error.smarty' => 'The Smarty template ###TEMPLATE### does not exist.',
		'tx_civserv_pi2_error.message_label' => 'The following error occured',
		'tx_civserv_pi2_error.no_community' => 'No community found. The system seems to be missconfigured or not configured yet.',
		'tx_civserv_pi2_error.wrong_community_id' => 'Wrong community-id. The entered community is either invalid, the community is not in the current system or the system is misconfigured.',
		'tx_civserv_pi2_error.community_id_twice' => 'The current system seems to be misconfigured. The given community-id exists at least twice in the configuration table.',
		'tx_civserv_pi2_error.unknown_category' => 'The given Category (Circumstance, Usergroup or Organisation) is unknown.)',
		'tx_civserv_pi2_error.unvalid_organisation' => 'An invalid organisation id was given.',
		'tx_civserv_pi2_error.folder' => 'The folder for the files of the communites was not defined in the template.',
		'tx_civserv_pi2_employee_list.by_name' => ' by names',
		'tx_civserv_pi2_employee_list.em_by_name' => 'employees by names',
		'tx_civserv_pi2_employee_list.hod' => 'Head of Department',
		'tx_civserv_pi2_employee_list.hod_by_name' => 'Heads of Department by names',
		'tx_civserv_pi2_employee_list.empossearch' => 'employee-search',
		'tx_civserv_pi2_employee_list.by_organisation' => ' by department',
		'tx_civserv_pi2_employee_list.az' => 'Employees A - Z',
		'tx_civserv_pi2_employee_list.orcode' => 'Employees by department',
		'tx_civserv_pi2_employee_list.orcode_all' => 'all',
		'tx_civserv_pi2_employee_list.orcode_all_organisations' => 'all organisations',
		'tx_civserv_pi2_employee_list.orcode_hod' => 'Head of Department',
		'tx_civserv_pi2_employee_list.no_organisation' => 'No organisation',
		'tx_civserv_pi2_employee_list.no_department' => 'No department',
		'tx_civserv_pi2_employee_list.heading' => 'actual Filter: ###MODE###, ###FILTER###',
		'tx_civserv_pi2_employee_list.heading_searchmode' => 'Search Result: ###SEARCHWORD###',		
		'tx_civserv_pi2_employee_list.heading_hodmode' => 'actual Filter: ###MODE###',
		'tx_civserv_pi2_employee_list.available_employees' => 'Here you find the following employees',
		'tx_civserv_pi2_employee_list.non_selected' => 'None selected',

		
		'tx_civserv_pi2_employee_list.search' => 'Employee-Search',
		'tx_civserv_pi2_employee_list.searchform_send' => 'send',
		'tx_civserv_pi2_employee_list.searchform_submit' => 'go!',
		'tx_civserv_pi2_employee_list.searchform_choose' => 'please choose',
		'tx_civserv_pi2_employee_list.searchform_title' => 'enter searchword here',
		'tx_civserv_pi2_employee_list.searchform_search_label' => 'search employee',
		'tx_civserv_pi2_employee_list.searchform_search_text' => 'search...',
		'tx_civserv_pi2_employee_list.searchform_or_dropdown_label' => 'organisation',

		
		'tx_civserv_pi2_employee.backlink' => 'go back to overview',
		'tx_civserv_pi2_form_list.form_list' => 'Forms',
		'tx_civserv_pi2_form_list.overview' => 'Overview',
		'tx_civserv_pi2_form_list.available_forms' => 'Here you find the following forms',
		'tx_civserv_pi2_form_list.assigned_services' => 'The following services are assigned with this form',
		'tx_civserv_pi2_form_list.name' => 'form name',
		'tx_civserv_pi2_form_list.description' => 'form description',
		'tx_civserv_pi2_organisation_list.organisation_list' => 'Organisation A-Z',
		'tx_civserv_pi2_organisation_list.organisation_list.heading' => 'Organisation', //modification?
		'tx_civserv_pi2_organisation_list.available_organisations' => 'Here you find the following organisation',		
		'tx_civserv_pi2_organisation.building_address' => 'Building address',
		'tx_civserv_pi2_organisation.postal_address' => 'Postal address',
		'tx_civserv_pi2_organisation.phone' => 'Phone',
		'tx_civserv_pi2_organisation.fax' => 'Fax',
		'tx_civserv_pi2_organisation.email' => 'E-Mail',
		'tx_civserv_pi2_organisation.web_email' => 'Contact form',
		'tx_civserv_pi2_organisation.office_hours' => 'Office hours',
		'tx_civserv_pi2_organisation.supervisor' => 'Supervisor',
		'tx_civserv_pi2_organisation.address_male' => 'Mr.',
		'tx_civserv_pi2_organisation.address_female' => 'Ms.',
		'tx_civserv_pi2_organisation.postbox' => 'Postbox',
		'tx_civserv_pi2_organisation.pub_trans_info' => 'Public transport information',
		'tx_civserv_pi2_organisation.pub_trans_stop' => 'Stop',
		'tx_civserv_pi2_organisation.citymap_label' => 'Link to City-Map ',
		'tx_civserv_pi2_organisation.image' => 'Image of organisation',
		'tx_civserv_pi2_organisation.infopage' => 'Info Page',
		'tx_civserv_pi2_organisation.employee_details' => 'Jumps to a page with details of this employee',
		'tx_civserv_pi2_organisation.office_hours_summary' => 'In the table are the office hours of ###ORGANISATION### shown.',
		'tx_civserv_pi2_organisation.morning' => 'in the morning',
		'tx_civserv_pi2_organisation.afternoon' => 'in the afternoon',
		'tx_civserv_pi2_organisation.sub_org_label' => 'You can also visit us here:',
		'tx_civserv_pi2_organisation.super_org_label' => 'next higher organisation level:',
		'tx_civserv_pi2_organisation.organisation_tree' => 'Organisation Structure', //modification
		'tx_civserv_pi2_organisation.organisation_tree.heading' => 'Organisation Structure in %s', //modification
		'tx_civserv_pi2_organisation.all' => 'all organisations',
		'tx_civserv_pi2_organisation.backlink' => 'go back to the overview',
		'tx_civserv_pi2_menuarray.service_list' => 'Services A - Z',
		'tx_civserv_pi2_menuarray.circumstance_tree' => 'Circumstances',
		'tx_civserv_pi2_menuarray.usergroup_tree' => 'Usergroups',
		'tx_civserv_pi2_menuarray.organisation_tree' => 'Organisation Structure',
		'tx_civserv_pi2_menuarray.organisation_list' => 'Organisation A - Z',	
		'tx_civserv_pi2_menuarray.employee_list' => 'Employees A - Z',		
		'tx_civserv_pi2_menuarray.form_list' => 'Forms',
		'tx_civserv_pi2_menuarray.top15' => 'Top 15',
		'tx_civserv_pi2_menuarray.online_services' => 'Online Services',
		'tx_civserv_pi2_menuarray.fulltext_search' => 'Fulltext Search',
		'tx_civserv_pi2_menuarray.alternative_language' => 'Deutsche Inhalte',
		'tx_civserv_pi2_search.no_results' => 'No search results found!',
		'tx_civserv_pi2_search.empty_query' => 'Empty query! Search string required.',
		'tx_civserv_pi2_search.search' => 'Keyword search',
		'tx_civserv_pi2_search.search_word' => 'Search item(s)',
		'tx_civserv_pi2_search.search_type' => 'Search type',
		'tx_civserv_pi2_search.employee' => 'Employee',
		'tx_civserv_pi2_search.service' => 'Service',
		'tx_civserv_pi2_search.do_search' => 'Search',
		'tx_civserv_pi2_search_result.employee' => 'Matching employees',
		'tx_civserv_pi2_search_result.service' => 'Matching services',
		'tx_civserv_pi2_service.service' => 'Service',
		'tx_civserv_pi2_service.external_service' => 'This is an external service',
		'tx_civserv_pi2_service.name' => 'Service name',
		'tx_civserv_pi2_service.ext_service' => 'This is an external service. For further details about this service follow this link',
		'tx_civserv_pi2_service.description' => 'Description',
		'tx_civserv_pi2_service.description_short' => 'Short description',
		'tx_civserv_pi2_service.description_long' => 'Long description',
		'tx_civserv_pi2_service.error_valid' => 'Service does not exist or is not available.',
		'tx_civserv_pi2_service.image' => 'Image',
		'tx_civserv_pi2_service.image_text' => 'Image description',
		'tx_civserv_pi2_service.fees' => 'Fees',
		'tx_civserv_pi2_service.documents' => 'Necessary documents',
		'tx_civserv_pi2_service.forms' => 'Forms',
		'tx_civserv_pi2_service.legal' => 'Legal foundation',
		'tx_civserv_pi2_service.legal_local' => 'Legal foundation (local)',
		'tx_civserv_pi2_service.legal_global' => 'Legal foundation (general)',
		'tx_civserv_pi2_service.similar_services' => 'Similar services',
		'tx_civserv_pi2_service.organisation' => 'Responsible organisational unit(s)',
		'tx_civserv_pi2_service.contact_male' => 'Contact person',
		'tx_civserv_pi2_service.contact_female' => 'Contact person',
		'tx_civserv_pi2_service.contact_plural' => 'Contact persons',
		'tx_civserv_pi2_service.subnavigation' => 'Sub-navigation',
		'tx_civserv_pi2_service.link_to_section' => 'Jump label to section',
		'tx_civserv_pi2_service.pages_related_topics' => 'Pages with related topics',
		'tx_civserv_pi2_service.top' => 'Top of page',
		'tx_civserv_pi2_service.link_to_top' => 'Jump label to the beginning of this page',
		'tx_civserv_pi2_common.serviceinformation' => 'Service information',
		'tx_civserv_pi2_common.frequently_visited' => 'The following sites are visited frequently',
		'tx_civserv_pi2_service_list.service_list' => 'Services',
		'tx_civserv_pi2_service_list.online_service_list' => 'Online Services', //modification
		'tx_civserv_pi2_service_list.circumstance' => 'Circumstance',
		'tx_civserv_pi2_service_list.usergroup' => 'Usergroup',
		'tx_civserv_pi2_service_list.organisation' => 'Organisation',
		'tx_civserv_pi2_service_list.overview' => 'Overview',
		'tx_civserv_pi2_service_list.available_services' => 'Here you find the following services',
		'tx_civserv_pi2_service_list.abc_letter' => 'Letter',
		'tx_civserv_pi2_top15.top15' => 'The 15 most frequently requested services',
		'tx_civserv_pi2_usergroup.usergroup_tree' => 'Usergroups',
		'tx_civserv_pi2_usergroup.usergroup_tree.heading' => 'Usergroups in %s',  //modification
		'tx_civserv_pi2_weekday' => 'Weekday',
		'tx_civserv_pi2_weekday_101' => 'Monday to Friday',
		'tx_civserv_pi2_weekday_102' => 'Monday to Thursday',
		'tx_civserv_pi2_weekday_103' => 'Monday to Wednesday',
		'tx_civserv_pi2_weekday_201' => 'Monday',
		'tx_civserv_pi2_weekday_202' => 'Tuesday',
		'tx_civserv_pi2_weekday_203' => 'Wednesday',
		'tx_civserv_pi2_weekday_204' => 'Thursday',
		'tx_civserv_pi2_weekday_205' => 'Friday',
		'tx_civserv_pi2_weekday_206' => 'Saturday',
		'tx_civserv_pi2_weekday_207' => 'Sunday',
		'tx_civserv_pi2_weekday_301' => 'Holiday',
		'tx_civserv_pi2_weekday_302' => 'Sun- and Holiday',
		'tx_civserv_pi2_weekday_401' => 'Extra hours',
		'tx_civserv_pi2_weekday_402' => 'Please note',
//		'tx_civserv_pi2_weekday_403' => '',
		'tx_civserv_pi2_community_choice.notice' => 'The following information is related to ###COMMUNITY_NAME###.',
		'tx_civserv_pi2_community_choice.link_text' => 'Click here, to choose another community.',
	),
	'de' => Array (
		'back' => 'zur�ck',
		'pi_list_browseresults_prev' => '< zur�ck',
		'pi_list_browseresults_next' => 'vor >',
		'pi_list_browseresults_page' => '',
		'pi_list_browseresults_displays' => 'Elemente ###SPAN_BEGIN###%s bis %s</span> von ###SPAN_BEGIN###%s</span>',
		'pi_list_browseresults_noResults' => 'Es konnten leider keine Element in dieser Kategorie gefunden werden.',
		'pi_list_searchBox_search' => 'Suchen',
		'pi_list_searchBox_searchform' => 'Suchformular',
		'pi_list_searchBox_header' => 'Stichwortsuche',
		'pi_list_searchBox_searchkey' => 'Geben Sie hier Ihren Suchbegriff ein',
		'pi_list_searchBox_submit' => 'Klicken Sie hier, um die Suchanfrage abzusenden',
		'pi_list_searchBox_defaultValue' => 'Suchbegriff',
		'tx_civserv_pi2_circumstance.circumstance_tree' => 'Lebenslagen',  //modification --> navigation_tree
		'tx_civserv_pi2_circumstance.circumstance_tree.heading' => 'Lebenslagen in %s',  //modification
		'tx_civserv_pi2_community_search_label' => 'Sie haben noch keine Kommune (Kreis, Stadt oder Gemeinde) ausgew�hlt. Bitte treffen Sie Ihre Auswahl.',
		'tx_civserv_pi2_debit_form.debit_form' => 'Erm�chtigung zum Einzug von Forderungen mittels Lastschriften',
		'tx_civserv_pi2_debit_form.serviceName' => 'Name der Dienstleistung, f�r die die Lastschrifterm�chtigung erteilt wird',
		'tx_civserv_pi2_debit_form.serviceSelect' => 'Bitte w�hlen sie die Dienstleistung, f�r die die Lastschrifterm�chtigung erteilt werden soll',
		'tx_civserv_pi2_debit_form.cashNumber' => 'Kassenzeichen/PK',
		'tx_civserv_pi2_debit_form.bankName' => 'Bankinstitut',
		'tx_civserv_pi2_debit_form.bankCode' => 'Bankleitzahl',
		'tx_civserv_pi2_debit_form.accountNumber' => 'Kontonummer',
		'tx_civserv_pi2_debit_form.accountHolder' => 'Name des Kontoinhabers',
		'tx_civserv_pi2_debit_form.firstname' => 'Vorname',
		'tx_civserv_pi2_debit_form.surname' => 'Nachname',
		'tx_civserv_pi2_debit_form.agreement' => 'Ich bin damit einverstanden, dass die von mir gemachten freiwilligen Angaben zum Zwecke der Aufgabenerf�llung der Stadtkasse gespeichert werden.',
		'tx_civserv_pi2_debit_form.optional_data' => 'Optionale Angaben',
		'tx_civserv_pi2_debit_form.phone' => 'Telefon',
		'tx_civserv_pi2_debit_form.email' => 'E-Mail',
		'tx_civserv_pi2_debit_form.button_send' => 'Absenden',
		'tx_civserv_pi2_debit_form.button_reset' => 'Eingaben l�schen',
		'tx_civserv_pi2_debit_form.no_results' => 'UID f�r das Formular "Allgemeine Lastschrifterm�chtigung" ist in der Konfigurationstabelle nicht gesetzt!',
		'tx_civserv_pi2_debit_form.error_cashNumber' => 'Bitte geben Sie ein g�ltiges Kassenzeichen an!',
		'tx_civserv_pi2_debit_form.error_bankName' => 'Bitte geben Sie den Namen Ihres Bankinstitutes an!',
		'tx_civserv_pi2_debit_form.error_bankCode' => 'Bitte geben Sie eine g�ltige Bankleitzahl an!',
		'tx_civserv_pi2_debit_form.error_accountNumber' => 'Bitte geben Sie eine g�ltige Kontonummer an!',
		'tx_civserv_pi2_debit_form.error_email' => 'Bitte geben Sie eine g�ltige E-Mail-Adresse an!',
		'tx_civserv_pi2_debit_form.error_firstname' => 'Bitte geben Sie den Vornamen des Kontoinhabers an!',
		'tx_civserv_pi2_debit_form.error_surname' => 'Bitte geben Sie den Nachnamen des Kontoinhabers an!',
		'tx_civserv_pi2_debit_form.error_service' => 'F�r diese Dienstleistung wurde kein Lastschriftformular gefunden!',
		'tx_civserv_pi2_debit_form.complete' => 'Vielen Dank! Ihre Daten wurden erfolgreich in das System aufgenommen.',
		'tx_civserv_pi2_email_form.email_form' => 'E-Mail Formular',
		'tx_civserv_pi2_email_form.contact_form' => 'Kontakt zu ###HOSTER###',
		'tx_civserv_pi2_email_form.notice' => 'Geben Sie bitte eine Postadresse, E-Mail-Adresse oder Telefon-/Faxnummer an, damit wir Ihnen eine Antwort zukommen lassen k�nnen.',
		'tx_civserv_pi2_email_form.title' => 'Anrede',
		'tx_civserv_pi2_email_form.chose' => 'Bitte w�hlen Sie',
		'tx_civserv_pi2_email_form.female' => 'Frau',
		'tx_civserv_pi2_email_form.male' => 'Herr',
		'tx_civserv_pi2_email_form.firstname' => 'Vorname',
		'tx_civserv_pi2_email_form.surname' => 'Nachname',
		'tx_civserv_pi2_email_form.street' => 'Stra�e, Hnr.',
		'tx_civserv_pi2_email_form.postcode' => 'PLZ',
		'tx_civserv_pi2_email_form.city' => 'Ort',
		'tx_civserv_pi2_email_form.email' => 'E-Mail',
		'tx_civserv_pi2_email_form.phone' => 'Telefon',
		'tx_civserv_pi2_email_form.fax' => 'Fax',
		'tx_civserv_pi2_email_form.subject' => 'Betreff',
		'tx_civserv_pi2_email_form.bodytext' => 'Ihr Text',
		'tx_civserv_pi2_email_form.submit' => 'E-Mail abschicken',
		'tx_civserv_pi2_email_form.reset' => 'Alle Angaben l�schen',
		'tx_civserv_pi2_email_form.required' => 'Diese Felder m�ssen ausgef�llt werden.',
		'tx_civserv_pi2_email_form.error_org_id' => 'Falsche Organisations-ID oder Organisation existiert nicht!',
		'tx_civserv_pi2_email_form.error_sv_id' => 'Falsche service id, employee id oder position id. Keine E-Mail-Adresse gefunden!',
		'tx_civserv_pi2_email_form.error_pos_id' => 'Falsche employee id oder position id. Keine E-Mail-Adresse gefunden!',
		'tx_civserv_pi2_email_form.error_general' => 'Organisation id, employee id, position id und service id falsch oder nicht gesetzt. Keine E-Mail-Adresse gefunden!',
		'tx_civserv_pi2_email_form.error_surname' => 'Bitte geben Sie Ihren Nachnamen an!',
		'tx_civserv_pi2_email_form.error_firstname' => 'Bitte geben Sie Ihren Vornamen an!',
		'tx_civserv_pi2_email_form.error_postcode' => 'Bitte geben Sie eine g�ltige Postleitzahl an!',
		'tx_civserv_pi2_email_form.error_subject' => 'Bitte geben Sie einen Betreff an!',
		'tx_civserv_pi2_email_form.error_bodytext' => 'Bitte geben Sie Ihren Text ein!',
		'tx_civserv_pi2_email_form.complete' => 'Vielen Dank! Ihre Nachricht wurde erfolgreich gesendet ',
		'tx_civserv_pi2_email_form.to' => 'an ',
		'tx_civserv_pi2_employee.employee_male' => 'Mitarbeiter',
		'tx_civserv_pi2_employee.employee_female' => 'Mitarbeiterin',
		'tx_civserv_pi2_employee.employee_plural' => 'Mitarbeiter(innen)',
		'tx_civserv_pi2_employee.title' => 'Titel',
		'tx_civserv_pi2_employee.hours' => 'Erreichbarkeitszeiten',
		'tx_civserv_pi2_employee.foto.alt_text' => 'Foto des Mitarbeiters',
		'tx_civserv_pi2_employee.organisation' => 'Organisation',
		'tx_civserv_pi2_employee.room' => 'Raum',
		'tx_civserv_pi2_employee.datasec' => 'Datenschutzfreigabe ist nicht aktiviert. Mitarbeiterinformationen werden nicht angezeigt!',
		'tx_civserv_pi2_employee.image_male' => 'Foto des Mitarbeiters',
		'tx_civserv_pi2_employee.image_female' => 'Foto der Mitarbeiterin',
		'tx_civserv_pi2_employee.officehours' => 'In der Tabelle sind die Erreichbarkeitszeiten von ###EMPLOYEE### angegeben.',
		'tx_civserv_pi2_error.smarty' => 'Das Smarty Template ###TEMPLATE### existiert nicht.',
		'tx_civserv_pi2_error.message_label' => 'Der folgende Fehler ist aufgetreten ',
		'tx_civserv_pi2_error.no_community' => 'Es konnte keine Kommune gefunden werden. Entweder ist das System fehlkonfiguriert oder es wurde noch nicht konfiguriert.',
		'tx_civserv_pi2_error.wrong_community_id' => 'Falsche Gemeindekennziffer. Die �bergebene Gemeindekennziffer ist entweder ung�ltig oder das Virtuelle Rathaus der entsprechenden Gemeinde wird nicht in diesem System gepflegt. Diese Meldung weist in der Regel auf eine Fehlkonfiguration des Systems hin.',
		'tx_civserv_pi2_error.community_id_twice' => 'Die �bergebene Gemeindekennziffer existiert mehr als einmal in der Konfigurationstabelle f�r dieses System. Diese Meldung weist auf eine Fehlkonfiguration des Systems hin.',
		'tx_civserv_pi2_error.invalid_mode' => 'Ung�ltiger mode',
		'tx_civserv_pi2_error.unknown_category' => 'Die ausgew�hlte Kategorie (Lebenslage, Nutzergruppe oder Organisation) ist unbekannt.)',
		'tx_civserv_pi2_error.unvalid_organisation' => 'Es wurde eine ung�ltige Organisations-ID �bergeben.',
		'tx_civserv_pi2_error.folder' => 'Der Ordner f�r die Dateien der Kommunen wurde im Template nicht konfiguriert.',
		'tx_civserv_pi2_employee_list.by_name' => ' nach Namen',
		'tx_civserv_pi2_employee_list.em_by_name' => 'Mitarbeiter nach Namen',
		'tx_civserv_pi2_employee_list.hod' => 'Leiter/in',
		'tx_civserv_pi2_employee_list.hod_by_name' => 'Leiter/innen nach Namen',
		'tx_civserv_pi2_employee_list.empossearch' => 'Mitarbeiter-Suche',
		'tx_civserv_pi2_employee_list.by_organisation' => 'Mitarbeiter nach �mtern',
		'tx_civserv_pi2_employee_list.az' => 'Mitarbeiter(innen) A - Z',
		'tx_civserv_pi2_employee_list.orcode' => 'Mitarbeiter(innen) nach �mter',
		'tx_civserv_pi2_employee_list.orcode_all' => 'alle',
		'tx_civserv_pi2_employee_list.orcode_all_organisations' => 'alle Organisationen',
		'tx_civserv_pi2_employee_list.orcode_hod' => 'Leiter/innen',
		'tx_civserv_pi2_employee_list.no_organisation' => 'Keine Organisation',
		'tx_civserv_pi2_employee_list.no_department' => 'Kein Amt',
		'tx_civserv_pi2_employee_list.heading' => 'aktuelle Sortierung: ###MODE###, ###FILTER###', //will be replaced with em_by_name or with hod_by_name or with by_organisation
		'tx_civserv_pi2_employee_list.heading_searchmode' => 'Suchergebnis: ###SEARCHWORD###',		
		'tx_civserv_pi2_employee_list.heading_hodmode' => 'aktuelle Sortierung: ###MODE###',
		'tx_civserv_pi2_employee_list.available_employees' => 'Folgende Mitarbeiter(innen) finden Sie hier',
		'tx_civserv_pi2_employee_list.non_selected' => 'Nichts ausgew�hlt',

		
		
		'tx_civserv_pi2_employee_list.search' => 'Mitarbeiter-Suche',
		'tx_civserv_pi2_employee_list.searchform_send' => 'senden',
		'tx_civserv_pi2_employee_list.searchform_submit' => 'anzeigen',
		'tx_civserv_pi2_employee_list.searchform_choose' => 'bitte w�hlen',
		'tx_civserv_pi2_employee_list.searchform_title' => 'Bitte hier Suchbegriff eingeben',
		'tx_civserv_pi2_employee_list.searchform_search_label' => 'Mitarbeitersuche',
		'tx_civserv_pi2_employee_list.searchform_search_text' => 'Suchen...',
		'tx_civserv_pi2_employee_list.searchform_or_dropdown_label' => 'Auswahl nach Organisationseinheiten:',

		
		'tx_civserv_pi2_employee.backlink' => 'zur�ck zur �bersicht',
		'tx_civserv_pi2_form_list.form_list' => 'Formulare',
		'tx_civserv_pi2_form_list.overview' => '�berblick',
		'tx_civserv_pi2_form_list.available_forms' => 'Folgende Formulare finden Sie hier',
		'tx_civserv_pi2_form_list.assigned_services' => 'Die folgenden Anliegen sind mit diesem Formular verkn�pft',
		'tx_civserv_pi2_form_list.name' => 'Formularname',
		'tx_civserv_pi2_form_list.description' => 'Formularbeschreibung',
		#'tx_civserv_pi2_organisation_list.organisation_list' => '�mter, Einrichtungen, Kundenzentren', //modification
		'tx_civserv_pi2_organisation_list.organisation_list' => 'Organisation', //modification
		#'tx_civserv_pi2_organisation_list.organisation_list.heading' => 'St�dtische �mter, Einrichtungen und Kundenzentren von A - Z',	//modification
		'tx_civserv_pi2_organisation_list.organisation_list.heading' => 'Organisationen A - Z',	//modification
		'tx_civserv_pi2_organisation_list.available_organisations' => 'Folgende Organisationen finden Sie hier',		
		#'tx_civserv_pi2_menuarray.service_list' => 'Zust�ndigkeiten A - Z', //modification
		'tx_civserv_pi2_menuarray.service_list' => 'Anliegen', //modification
		'tx_civserv_pi2_menuarray.circumstance_tree' => 'Lebenslagen',
		'tx_civserv_pi2_menuarray.usergroup_tree' => 'Nutzergruppen',
		'tx_civserv_pi2_menuarray.organisation_tree' => 'Organisationsstruktur', //modification
		#'tx_civserv_pi2_menuarray.organisation_list' => '�mter, Einrichtungen, Kundenzentren', //modification
		'tx_civserv_pi2_menuarray.organisation_list' => 'Organisationen A - Z', //modification
		'tx_civserv_pi2_menuarray.employee_list' => 'Mitarbeiter(innen) A - Z',
		#'tx_civserv_pi2_menuarray.form_list' => 'Formulare im �berblick',	//modification
		'tx_civserv_pi2_menuarray.form_list' => 'Formulare',	//modification
		'tx_civserv_pi2_menuarray.top15' => 'Top 15',
		'tx_civserv_pi2_menuarray.online_services' => 'Online Dienste',
		'tx_civserv_pi2_menuarray.fulltext_search' => 'Volltextsuche',
		'tx_civserv_pi2_menuarray.alternative_language' => 'English Content',
		'tx_civserv_pi2_organisation.building_address' => 'Hausanschrift',
		'tx_civserv_pi2_organisation.postal_address' => 'Postanschrift',
		'tx_civserv_pi2_organisation.phone' => 'Telefon',
		'tx_civserv_pi2_organisation.fax' => 'Fax',
		'tx_civserv_pi2_organisation.email' => 'E-Mail',
		'tx_civserv_pi2_organisation.web_email' => 'zum Kontaktformular',
		'tx_civserv_pi2_organisation.office_hours' => 'Sprechzeiten',
		'tx_civserv_pi2_organisation.supervisor' => 'Leitung',
		'tx_civserv_pi2_organisation.address_male' => 'Herr',
		'tx_civserv_pi2_organisation.address_female' => 'Frau',
		'tx_civserv_pi2_organisation.postbox' => 'Postfach',
		'tx_civserv_pi2_organisation.pub_trans_info' => '�PNV-Information',
		'tx_civserv_pi2_organisation.pub_trans_stop' => 'Haltestelle',
		'tx_civserv_pi2_organisation.citymap_label' => 'Link zum Stadtplan',
		'tx_civserv_pi2_organisation.image' => 'Foto der Organisation',
		'tx_civserv_pi2_organisation.infopage' => 'Informationsseite',
		#'tx_civserv_pi2_organisation.organisation_tree' => 'Organisationsstruktur', //modification
		'tx_civserv_pi2_organisation.organisation_tree' => 'Organisationen', //modification
		#'tx_civserv_pi2_organisation.organisation_tree.heading' => 'Organisationsstruktur der �mter, Einrichtungen und Kundenzentren in %s', //modification
		'tx_civserv_pi2_organisation.organisation_tree.heading' => 'Organisationsstruktur in %s', //modification
		'tx_civserv_pi2_organisation.all' => 'alle Organisationen',
		'tx_civserv_pi2_organisation.backlink' => 'zur�ck zur �bersicht',
		'tx_civserv_pi2_organisation.employee_details' => 'Zeigt detailierte Mitarbeiterinformationen auf einer neuen Seite',
		'tx_civserv_pi2_organisation.office_hours_summary' => 'In der Tabelle sind die Sprechzeiten vom ###ORGANISATION### angegeben.',
		'tx_civserv_pi2_organisation.morning' => 'Vormittags',
		'tx_civserv_pi2_organisation.afternoon' => 'Nachmittags',
		'tx_civserv_pi2_organisation.sub_org_label' => 'Siehe auch:', //modification
		'tx_civserv_pi2_organisation.super_org_label' => '�bergeordnete Organisationseinheit:', //modification
		'tx_civserv_pi2_search.search' => 'Stichwortsuche',
		'tx_civserv_pi2_search.search_word' => 'Suchbegriff(e)',
		'tx_civserv_pi2_search.search_type' => 'Abfrageart',
		'tx_civserv_pi2_search.employee' => 'Mitarbeiter',
		'tx_civserv_pi2_search.service' => 'Dienstleistung',
		'tx_civserv_pi2_search.do_search' => 'Suchen',
		'tx_civserv_pi2_search.no_results' => 'Zu Ihrer Suche konnten keine Ergebnisse gefunden werden!',
		'tx_civserv_pi2_search.empty_query' => 'Leere Abfrage! Sie m�ssen ein Wort angeben, nach dem Sie suchen wollen.',
		'tx_civserv_pi2_search_result.employee' => 'Gefundene Mitarbeiter',
		'tx_civserv_pi2_search_result.service' => 'Gefundene Dienstleistungen',
		#'tx_civserv_pi2_service.service' => 'Zust�ndigkeiten A-Z', // modification
		'tx_civserv_pi2_service.service' => 'Anliegen A-Z', // modification
		'tx_civserv_pi2_service.external_service' => 'F�r diese Dienstleistung wenden Sie sich bitte an ',
		'tx_civserv_pi2_service.name' => 'Name des Anliegens',
		'tx_civserv_pi2_service.ext_service' => 'Bei dieser Dienstleistung handelt es sich um eine externe Dienstleistung. Details zu dieser Dienstleistung finden sie unter folgendem Link',
		'tx_civserv_pi2_service.description' => 'Beschreibung',
		'tx_civserv_pi2_service.description_short' => 'Kurzbeschreibung',
		'tx_civserv_pi2_service.description_long' => 'Ausf�hrliche Beschreibung',
		'tx_civserv_pi2_service.error_valid' => 'Dienstleistung exisitert nicht oder ist nicht verf�gbar.',
		'tx_civserv_pi2_service.image' => 'Bild',
		'tx_civserv_pi2_service.image_text' => 'Bildbeschreibung',
		'tx_civserv_pi2_service.fees' => 'Geb�hren',
		'tx_civserv_pi2_service.documents' => 'Ben�tigte Unterlagen',
		'tx_civserv_pi2_service.forms' => 'Formulare',
		'tx_civserv_pi2_service.legal' => 'Rechtsgrundlagen',
		'tx_civserv_pi2_service.legal_local' => 'Rechtsgrundlagen (Ortsrecht)',
		'tx_civserv_pi2_service.legal_global' => 'Rechtsgrundlagen (Allgemein)',
		'tx_civserv_pi2_service.similar_services' => '�hnliche Anliegen',
		'tx_civserv_pi2_service.organisation' => 'Zust�ndige Organisationseinheit(en)',
		'tx_civserv_pi2_service.contact_male' => 'Ansprechpartner',
		'tx_civserv_pi2_service.contact_female' => 'Ansprechpartnerin',
		'tx_civserv_pi2_service.contact_plural' => 'Ansprechpartner(innen)',
		'tx_civserv_pi2_service.subnavigation' => 'Subnavigation',
		'tx_civserv_pi2_service.link_to_section' => 'Sprungmarke zum Abschnitt',
		'tx_civserv_pi2_service.pages_related_topics' => 'Seiten mit �hnlichen Themen',
		'tx_civserv_pi2_service.top' => 'Seitenanfang',
		'tx_civserv_pi2_service.link_to_top' => 'Sprungmarke zum Seitenanfang',
		'tx_civserv_pi2_common.serviceinformation' => 'Serviceinformationen',
		'tx_civserv_pi2_common.frequently_visited' => 'Folgende Seiten wurden h�ufig aufgerufen',
		#'tx_civserv_pi2_service_list.service_list' => 'Zust�ndigkeiten A-Z', //modification
		'tx_civserv_pi2_service_list.service_list' => 'Anliegen', //modification
		'tx_civserv_pi2_service_list.online_service_list' => 'Online Dienstleistungen', //modification
		'tx_civserv_pi2_service_list.circumstance' => 'Lebenslage',
		'tx_civserv_pi2_service_list.usergroup' => 'Nutzergruppe',
		'tx_civserv_pi2_service_list.organisation' => 'Organisation',
		#'tx_civserv_pi2_service_list.overview' => 'Zust�ndigkeiten A-Z', //modification
		'tx_civserv_pi2_service_list.overview' => '�berblick', //modification
		'tx_civserv_pi2_service_list.available_services' => 'Dienstleistungen' , //modification
		'tx_civserv_pi2_service_list.abc_letter' => 'Buchstabe',
		'tx_civserv_pi2_top15.top15' => 'Die 15 am h�ufigsten nachgefragten Dienstleistungen',
		'tx_civserv_pi2_usergroup.usergroup_tree' => 'Nutzergruppen',
		'tx_civserv_pi2_usergroup.usergroup_tree.heading' => 'Nutzergruppen in %s',  //modification
		'tx_civserv_pi2_weekday' => 'Wochentag',
		'tx_civserv_pi2_weekday_101' => 'Montag bis Freitag',
		'tx_civserv_pi2_weekday_102' => 'Montag bis Donnerstag',
		'tx_civserv_pi2_weekday_103' => 'Montag bis Mittwoch',
		'tx_civserv_pi2_weekday_201' => 'Montag',
		'tx_civserv_pi2_weekday_202' => 'Dienstag',
		'tx_civserv_pi2_weekday_203' => 'Mittwoch',
		'tx_civserv_pi2_weekday_204' => 'Donnerstag',
		'tx_civserv_pi2_weekday_205' => 'Freitag',
		'tx_civserv_pi2_weekday_206' => 'Samstag',
		'tx_civserv_pi2_weekday_207' => 'Sonntag',
		'tx_civserv_pi2_weekday_301' => 'An Feiertagen',
		'tx_civserv_pi2_weekday_302' => 'Sonn- und Feiertags',
		'tx_civserv_pi2_weekday_401' => 'zus�tzlich',
		'tx_civserv_pi2_weekday_402' => 'Hinweis',
//		'tx_civserv_pi2_weekday_403' => '',
		'tx_civserv_pi2_community_choice.notice' => 'Die folgenden Informationen beziehen sich auf: ###COMMUNITY_NAME###.',
		'tx_civserv_pi2_community_choice.link_text' => 'Klicken Sie hier, um einen anderen Ort einzustellen.',
	),
);
?>