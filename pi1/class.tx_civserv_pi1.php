<?php
/***************************************************************
*  Copyright notice
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
 * Plugin 'Civil Services' for the 'civserv' extension.
 *
 * $Id$
 *
 * @author	Stephan Dümmer <sduemmer@uni-muenster.de>
 * @author	Stefan Meesters <meesters@uni-muenster.de>
 * @package TYPO3
 * @subpackage tx_civserv
 * @version 1.0
 *
 */
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *  106: class tx_civserv_pi1 extends tslib_pibase
 *  117:     function main($content,$conf)
 *
 *              SECTION: Functions for the navigation:
 *  326:     function serviceList(&$smartyServiceList,$abcBar=false,$searchBox=false,$topList=false)
 *  391:     function makeServiceListQuery($char=all,$limit=true,$count=false)
 *  493:     function getServiceListHeading($mode,$uid)
 *  543:     function navigationTree(&$smartyTree,$uid,$searchBox=false,$topList=false)
 *  578:     function formList(&$smartyFormList,$organisation_id=0,$abcBar=false,$searchBox=false,$topList=false,$orgaList=false)
 *  713:     function makeFormListQuery($char=all,$organisation_id=0,$limit=true,$count=false)
 *  788:     function do_search(&$smartySearchResult,$searchBox)
 *  943:     function calculate_top15(&$smartyTop15,$showCounts=1,$topN=15,$searchBox=false)
 *
 *              SECTION: Helper functions for the navigation functions:
 *  996:     function makeAbcBar($query)
 * 1041:     function buildRegexp($char)
 * 1070:     function makeTree($uid,$add_content,$mode)
 *
 *              SECTION: Functions for the detail pages (service, employee, organisation):
 * 1144:     function serviceDetail(&$smartyService,$searchBox=false,$topList=false)
 * 1450:     function queryService($uid)
 * 1471:     function employeeDetail(&$smartyEmployee,$searchBox)
 * 1672:     function organisationDetail(&$smartyOrganisation)
 *
 *              SECTION: Functions for choosing and changeing the community :
 * 1856:     function chooseCommunity(&$smartyCommunity)
 * 1889:     function linkCommunityChoice($content,$conf)
 * 1907:     function getCommunityName($content,$conf)
 * 1938:     function getChoiceLink()
 *
 *              SECTION: Functions for the email form:
 * 1967:     function setEmailForm(&$smartyEmailForm)
 * 2005:     function checkEmailForm(&$smartyEmailForm)
 * 2125:     function getEhoster_email()
 * 2189:     function makeEmailQuery($emp_id,$pos_id,$sv_id)
 *
 *              SECTION: Functions for the debit form:
 * 2256:     function setDebitForm(&$smartyDebitForm)
 * 2351:     function checkDebitForm(&$smartyDebitForm)
 *
 *              SECTION: Various helper functions:
 * 2488:     function formatStr($str)
 * 2506:     function getImageCode($image,$path,$conf,$altText)
 * 2519:     function sql_fetch_array_r($result)
 * 2542:     function pi_linkTP_keepPIvars_url($overrulePIvars=array(),$cache=0,$clearAnyway=0,$altPageId=0)
 * 2559:     function pi_list_searchBox($divParams='',$header=false)
 * 2601:     function pi_list_browseresults($showResultCount=1,$divParams='',$spacer=false)
 *
 *              SECTION: Function for generating a menu array:
 * 2707:     function makeMenuArray($content,$conf)
 *
 * TOTAL FUNCTIONS: 33
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */


require_once(PATH_tslib . 'class.tslib_pibase.php');
require_once(t3lib_extMgm::extPath('smarty') . 'class.tx_smarty.php');
require_once(t3lib_extMgm::extPath('civserv') . 'pi1/class.tx_civserv_accesslog.php');
require_once(t3lib_extMgm::extPath('civserv') . 'res/class.tx_civserv_mandant.php');


/**
 * Class for plugin 'Civil Services'
 *
 */
class tx_civserv_pi1 extends tslib_pibase {
	var $prefixId = 'tx_civserv_pi1';						// Same as class name
	var $scriptRelPath = 'pi1/class.tx_civserv_pi1.php';	// Path to this script relative to the extension dir
	var $extKey = 'civserv';								// The extension key

	/**
	 * @param	string		Content that is to be displayed within the plugin
	 * @param	array		Configuration array
	 * @return	$content		Content that is to be displayed within the plugin
	 */
	function main($content,$conf)	{
		//$GLOBALS['TYPO3_DB']->debugOutput=true;	 // Debugging

		// Load configuration array
		$this->conf = $conf;
		// Get default values for piVars from template setup
		$this->pi_setPiVarDefaults();
		// Get language for the frontend, necessary for pi_getLL-functions
		$this->pi_loadLL();

		// Necessary for formatStr
		$this->local_cObj = t3lib_div::makeInstance('tslib_cObj');

		// Start or resume session
		session_name($this->extKey);
		session_start();

		// create and instanciate smarty object
		$tx_smarty = t3lib_div::makeInstanceClassName('tx_smarty');
		$smartyObject = new $tx_smarty($this->extKey);
		$smartyObject->template_dir = PATH_site;
		$smartyObject->compile_dir =t3lib_extMgm::siteRelPath($this->extKey).'templates_c/'; 
		
		//set path variables for includes within templates
		$smartyObject->assign('right_searchbox_template', $this->conf['tpl_right_searchbox']);
		$smartyObject->assign('right_top_template', $this->conf['tpl_right_top']);
		$smartyObject->assign('organisation_template', $this->conf['tpl_organisation']);

		// If community-id is given in GET or POST variable, priority is POST,
		// get the community name and the pidlist for this community from the
		// database and store it in the session
		if ((($this->piVars[community_id] <= '') && ($_SESSION['community_id'] <= '')) || ($this->piVars[community_id] == 'choose')) {
			$template = $this->conf['tpl_community_choice'];
			$accurate = $this->chooseCommunity($smartyObject);
			$choose = true;
	 	} elseif (($this->piVars[community_id] != $_SESSION['community_id']) || ($_SESSION['community_name'] <= '')) {
			if ($this->piVars[community_id] > '') {
				$community_id = $this->piVars[community_id];
			} else {
				$community_id = $_SESSION['community_id'];
			}
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				'*',
				'tx_civserv_conf_mandant',
				'cm_community_id = ' . $community_id,
				'',
				'',
				'');
			$community_data = $this->sql_fetch_array_r($res);
			// Check if given community-id exists in the database
			switch (count($community_data)) {
				case '0':
					$GLOBALS['error_message'] = $this->pi_getLL('tx_civserv_pi1_error.wrong_community_id','Wrong community-id. The entered community is either invalid, the community is not in the current system or the system is misconfigured.');

				case '1':
					// Set session variables
					$_SESSION['community_id'] = $community_id;
					$_SESSION['community_name'] = $community_data[0]['cm_community_name'];
					$_SESSION['community_pidlist'] = $this->pi_getPidList($community_data[0]['cm_uid'],$this->conf['recursive']);
					$_SESSION['circumstance_uid'] = $community_data[0]['cm_circumstance_uid'];
					$_SESSION['usergroup_uid'] = $community_data[0]['cm_usergroup_uid'];
					$_SESSION['organisation_uid'] = $community_data[0]['cm_organisation_uid'];
					$_SESSION['employee_search'] = $community_data[0]['cm_employeesearch'];
					break;
				default:
					$GLOBALS['error_message'] = $this->pi_getLL('tx_civserv_pi1_error.community_id_twice','The current system seems to be misconfigured. The given community-id exists at least twice in the configuration table.');

			}
		}

		if (!$choose) {
			// Set local array from session variables
			$this->community['id'] = $_SESSION['community_id'];
			$this->community['name'] = $_SESSION['community_name'];
			$this->community['pidlist'] = $_SESSION['community_pidlist'];
			$this->community['circumstance_uid'] = $_SESSION['circumstance_uid'];
			$this->community['usergroup_uid'] = $_SESSION['usergroup_uid'];
			$this->community['organisation_uid'] = $_SESSION['organisation_uid'];
			$this->community['employee_search'] = $_SESSION['employee_search'];

			// Set piVars[community_id] because it could only be registered in the session and not in the URL
			$this->piVars[community_id] = $_SESSION['community_id'];

			switch($this->piVars[mode])	{
				case 'service_list':
					$GLOBALS['TSFE']->page['title'] = $this->pi_getLL('tx_civserv_pi1_service_list.service_list','Service list');
					$template = $this->conf['tpl_service_list'];
					$accurate = $this->serviceList($smartyObject,$this->conf['abcBarAtServiceList'],$this->conf['searchAtServiceList'],$this->conf['topAtServiceList']);
					break;

				case 'circumstance_tree':
					$GLOBALS['TSFE']->page['title'] = $this->pi_getLL('tx_civserv_pi1_circumstance.circumstance_tree','Circumstances');
					$template = $this->conf['tpl_circumstance_tree'];
					$accurate = $this->navigationTree($smartyObject,$this->community[circumstance_uid],$this->conf['searchAtCircumstanceTree'],$this->conf['topAtCircumstanceTree']);
					break;

				case 'usergroup_tree':
					$GLOBALS['TSFE']->page['title'] = $this->pi_getLL('tx_civserv_pi1_usergroup.usergroup_tree','Usergroups');
					$template = $this->conf['tpl_usergroup_tree'];
					$accurate = $this->navigationTree($smartyObject,$this->community[usergroup_uid],$this->conf['searchAtUsergroupTree'],$this->conf['topAtUsergroupTree']);
					break;

				case 'organisation_tree':
					$GLOBALS['TSFE']->page['title'] = $this->pi_getLL('tx_civserv_pi1_organisation.organisation_tree','Organisation');
					$template = $this->conf['tpl_organisation_tree'];
					$accurate = $this->navigationTree($smartyObject,$this->community[organisation_uid],$this->conf['searchAtOrganisationTree'],$this->conf['topAtOrganisationTree']);
					break;
					
				case 'employee_list':
					$GLOBALS['TSFE']->page['title'] = $this->pi_getLL('tx_civserv_pi1_employee_list.employee_list','Employees A - Z');
					$template = $this->conf['tpl_employee_list'];
					$accurate = $this->employee_list($smartyObject,$this->conf['abcBarAtEmployeeList'],$this->conf['searchAtEmployeeList'],$this->conf['topAtEmployeeList']);
					break;					

				case 'form_list':
					$GLOBALS['TSFE']->page['title'] = $this->pi_getLL('tx_civserv_pi1_form_list.form_list','Forms');
					$template = $this->conf['tpl_form_list'];
					$accurate = $this->formList($smartyObject,$this->piVars[id],$this->piVars[id]?$this->conf['abcBarAtFormList_orga']:$this->conf['abcBarAtFormList_all'],$this->conf['searchAtFormList'],$this->conf['topAtFormList'],$this->conf['orgaList']);
					break;

				case 'top15':
					$GLOBALS['TSFE']->page['title'] = "TOP 15";
					$template = $this->conf['tpl_top15'];
					$accurate = $this->calculate_top15($smartyObject,$this->conf['show_counts'],$this->conf['service_count'],$this->conf['searchAtTop15']);
					break;

				case 'search_result':
					$template = $this->conf['tpl_search_result'];
					$accurate = $this->do_search($smartyObject,true);
					break;

				case 'circumstance':
					$GLOBALS['TSFE']->page['title'] = $this->pi_getLL('tx_civserv_pi1_service_list.circumstance','Circumstance');
					$template = $this->conf['tpl_circumstance'];
					$accurate = $this->serviceList($smartyObject,$this->conf['abcBarAtCircumstance'],$this->conf['searchAtCircumstance'],$this->conf['topAtCircumstance']);
					break;

				case 'usergroup':
					$GLOBALS['TSFE']->page['title'] = $this->pi_getLL('tx_civserv_pi1_service_list.usergroup','Usergroup');
					$template = $this->conf['tpl_usergroup'];
					$accurate = $this->serviceList($smartyObject,$this->conf['abcBarAtUsergroup'],$this->conf['searchAtUsergroup'],$this->conf['topAtUsergroup']);
					break;

				case 'organisation':
					$GLOBALS['TSFE']->page['title'] = $this->pi_getLL('tx_civserv_pi1_service_list.organisation','Organisation');
					$template = $this->conf['tpl_service_list'];
					$accurate = $this->organisationDetail($smartyObject) && $this->serviceList($smartyObject,$this->conf['abcBarAtOrganisation'],$this->conf['searchAtOrganisation'],$this->conf['topAtOrganisation']);
					break;

				case 'service':
					$template = $this->conf['tpl_service'];
					$accurate = $this->serviceDetail($smartyObject,$this->conf['searchAtService']);
					break;

				case 'employee':
					$template = $this->conf['tpl_employee'];
					$accurate = $this->employeeDetail($smartyObject,$this->conf['searchAtEmployee']);
					break;

				case 'check_email_form':
					$reset = t3lib_div::_POST('reset');
					//Check if reset button was clicked (necessary for resetting the email form)
					if (!isset($reset)) {
						$template = $this->conf['tpl_email_form'];
						$accurate = $this->checkEmailForm($smartyObject);
						break;
					}

				case 'set_email_form':
					$template = $this->conf['tpl_email_form'];
					$accurate = $this->setEmailForm($smartyObject);
					break;

				case 'check_debit_form':
					$reset = t3lib_div::_POST('reset');
					//Check if reset button was clicked (necessary for resetting the debit form)
					if (!isset($reset)) {
						$template = $this->conf['tpl_debit_authorisation'];
						$accurate = $this->checkDebitForm($smartyObject);
						break;
					}

				case 'set_debit_form':
					$template = $this->conf['tpl_debit_authorisation'];
					$accurate = $this->setDebitForm($smartyObject);
					break;

				case 'check_contact_form':
					$reset = t3lib_div::_POST('reset');
					//Check if reset button was clicked (necessary for resetting the contact form)
					if (!isset($reset)) {
						$template = $this->conf['tpl_contact_form'];
						$accurate = $this->checkEmailForm($smartyObject);
						break;
					}

				case 'set_contact_form':
					$template = $this->conf['tpl_contact_form'];
					$accurate = $this->setEmailForm($smartyObject);
					break;

				case 'legal_notice':
					$template = $this->conf['tpl_legal_notice'];
					$accurate = $this->showLegalNotice($smartyObject);
					break;

				case 'plain_text':
					$template = $this->conf['tpl_plain_text'];
					$accurate = $this->showPlainText($smartyObject);
					break;

				default:
					$accurate = false;
					$GLOBALS['error_message'] = $this->pi_getLL('tx_civserv_pi1_error.invalid_mode','Invalid mode');
			}
		}

		if (!$accurate) {
			$template = $this->conf['tpl_error_page'];
			$smartyObject->assign('error_message_label',$this->pi_getLL('tx_civserv_pi1_error.message_label','The following error occured'));
			$smartyObject->assign('error_message',$GLOBALS['error_message']);
		}

		// check if the specified template exists
		if ($smartyObject->template_exists($template)) {
			$content = $smartyObject->fetch($template);
		} else {
			$content = str_replace('###TEMPLATE###',$template,$this->pi_getLL('tx_civserv_pi1_error.smarty','The Smarty template <i>###TEMPLATE###</i> does not exist.'));
			$content.="<br />was steht im templatepfad??: ".$template." got me???";
		}

		return $this->pi_wrapInBaseClass($content);
	}









	/******************************
	 *
	 * Functions for the navigation:
	 *
	 *******************************/


	/**
	 * Generates a list of all available services. Is also used from other modes than 'service_list'.
	 *
	 * @param	object		Smarty object, the template key/value-pairs should be assigned to
	 * @param	boolean		If true, an ABC-bar is generated to navigate throug services
	 * @param	boolean		If true, a searchbox is generated (keyword search)
	 * @param	boolean		If true, a list with the top <i>plugin.tx_civserv_pi1.topCount</i> services is generated
	 * @return	boolean		True, if the function was executed without any error, otherwise false
	 */
	function serviceList(&$smartyServiceList,$abcBar=false,$searchBox=false,$topList=false) {
		$query = $this->makeServiceListQuery($this->piVars[char]);
		if (!$query) {
			return false;
		}
		$res = $GLOBALS['TYPO3_DB']->sql(TYPO3_db,$query);
		
		$row_counter = 0;
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res))	{
			$services[$row_counter]['link'] =  htmlspecialchars($this->pi_linkTP_keepPIvars_url(array(mode => 'service',id => $row['uid']),$this->conf['cache_services'],1));
			if ($row['name'] == $row['realname']) {
				$services[$row_counter]['name'] = $row['name'];
			} else {
				$services[$row_counter]['name'] = $row['name'] . ' (= ' . $row['realname'] . ')';
			}
			$row_counter++;
		}

		// Retrieve the service count
		$row_count = 0;
		$query = $this->makeServiceListQuery($this->piVars[char],false,true);
		$res = $GLOBALS['TYPO3_DB']->sql(TYPO3_db,$query);
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			$row_count += $row['count(*)'];
		}

		$this->internal['res_count'] = $row_count;
		$this->internal['results_at_a_time']= $this->conf['services_per_page'];
		$this->internal['maxPages'] = $this->conf['max_pages_in_pagebar'];

		$smartyServiceList->assign('services',$services);
		if ($abcBar) {
			$query = $this->makeServiceListQuery(all,false);
			$smartyServiceList->assign('abcbar',$this->makeAbcBar($query));
		}
		$smartyServiceList->assign('heading',$this->getServiceListHeading($this->piVars[mode],$this->piVars[id]));
		$GLOBALS['TSFE']->page['title'] = $this->getServiceListHeading($this->piVars[mode],$this->piVars[id]);
		if($this->piVars[char]>''){
			//ToDo Language support!!!! pi_getll(....)
			$GLOBALS['TSFE']->page['title'] .= " Buchstabe ".$this->piVars[char];
		}
		
		if ($searchBox) {
			$_SERVER['REQUEST_URI'] = $this->pi_linkTP_keepPIvars_url(array(mode => 'search_result'),0,1);
			$smartyServiceList->assign('searchbox', $this->pi_list_searchBox('',true));
		}

		if ($topList) {
			if (!$this->calculate_top15($smartyServiceList,false,$this->conf['topCount'])) {
				return false;
			}
		}

		$smartyServiceList->assign('subheading',$this->pi_getLL('tx_civserv_pi1_service_list.available_services','Here you find the following services'));
		$smartyServiceList->assign('pagebar',$this->pi_list_browseresults(true,'',' | '));

		return true;
	}


	/**
	 * Generates a database query for the function serviceList. The returned query depends on the given parameter (like described below)
	 * and the piVars 'mode', 'char' and 'pointer', additionally the pidlist for the actual community is fetched from the class variable community.
	 * The returned query contains UNIONs.
	 *
	 * @param	string		The beginning character, the list should be limited to. Can also be a sequence of beginning characters.
	 * @param	boolean		If true, the list is limited to 'max_services_per_page' (constant from $this->conf) services per page. The page number is fetched from piVars[pointer].
	 * @param	boolean		If true, the services are only counted.
	 * @return	string		The database query
	 */
	function makeServiceListQuery($char=all,$limit=true,$count=false) {
		$from  =	'tx_civserv_service';
		$where =	'NOT tx_civserv_service.deleted AND NOT tx_civserv_service.hidden
					 AND ((UNIX_TIMESTAMP(LOCALTIMESTAMP) BETWEEN tx_civserv_service.starttime AND tx_civserv_service.endtime)
					 	OR ((UNIX_TIMESTAMP(LOCALTIMESTAMP) > tx_civserv_service.starttime) AND (tx_civserv_service.endtime = 0))
					 	OR (tx_civserv_service.starttime = 0 AND tx_civserv_service.endtime = 0))';
		$orderby =	$this->piVars[sort]?'name DESC':'name ASC';
		switch ($this->piVars[mode]) {
			case 'service_list':
				$where .=	'';
				break;
			case 'circumstance':
			case 'usergroup':
				$from  .=	', tx_civserv_navigation, ###NAVIGATION_MM_TABLE###';
				$where .= 	'AND ###SERVICE_TABLE###.uid = ###NAVIGATION_MM_TABLE###.uid_local
							 AND ###NAVIGATION_MM_TABLE###.uid_foreign = tx_civserv_navigation.uid
							 AND tx_civserv_navigation.uid = ' . $this->piVars[id];
				break;
			case 'organisation':
				$from  .=	', tx_civserv_organisation, tx_civserv_service_sv_organisation_mm';
				$where .= 	'AND tx_civserv_service.uid =  tx_civserv_service_sv_organisation_mm.uid_local
							 AND tx_civserv_service_sv_organisation_mm.uid_foreign = tx_civserv_organisation.uid
							 AND tx_civserv_organisation.uid = ' . $this->piVars[id];
				break;
			// not yet implemented in the main() function
			case 'employee_service_list':
				$from  .=	', tx_civserv_service_sv_position_mm, tx_civserv_position, tx_civserv_employee, tx_civserv_employee_em_position';
				$where .=	'AND tx_civserv_service.uid = tx_civserv_service_sv_position_mm.uid_local
							 AND tx_civserv_service_sv_position_mm.uid_foreign = tx_civserv_position.uid
							 AND tx_civserv_employee_em_position.ep_position = tx_civserv_position.uid
							 AND tx_civserv_employee_em_position.ep_employee = tx_civserv_employee.uid
							 AND tx_civserv_employee.uid = ' . $this->piVars[id];
				break;
		}

		if ($char != all) {
			$regexp = $this->buildRegexp($char);
		}

		$query = '';

		// The first time the loop is executed, the part of the query for selecting the services which are located directly at the community is build.
		// The second time the loop is executed, the part of the query for selecting the services located at another community is build.
		for ($i = 1; $i <= 2; $i++) {
			if ($i == 1) {
				$navigation_mm_table = 'tx_civserv_service_sv_navigation_mm';
				$service_table = 'tx_civserv_service';
			} else {
				$navigation_mm_table = 'tx_civserv_ext_service_esv_navigation_mm';
				$service_table = 'tx_civserv_external_service';
				$from  .=	', tx_civserv_external_service';
				$where .=	' ' .
							'AND tx_civserv_external_service.es_external_service = tx_civserv_service.uid
							 AND NOT tx_civserv_external_service.deleted
							 AND NOT tx_civserv_external_service.hidden
							 AND tx_civserv_external_service.pid IN (' . $this->community[pidlist] . ')';
				$query .= 'UNION ALL ';
			}

			// services by realnames
			$query .=	'SELECT ' . ($count?'count(*) ':'tx_civserv_service.uid, sv_name AS name, sv_name AS realname ') . '
						 FROM ' . str_replace('###NAVIGATION_MM_TABLE###',$navigation_mm_table,$from) . '
						 WHERE ' . str_replace('###SERVICE_TABLE###',$service_table,str_replace('###NAVIGATION_MM_TABLE###',$navigation_mm_table,$where)) . ' ' .
							(($i==1)?'AND tx_civserv_service.pid IN (' . $this->community[pidlist] . ') ':'') .
							($regexp?'AND sv_name REGEXP "' . $regexp . '"':'') . ' ';
			// services by synonyms
			for ($synonymNr = 1; $synonymNr <= 3; $synonymNr++) {
				$query .=	'UNION ALL
							 SELECT ' . ($count?'count(*) ':'tx_civserv_service.uid, sv_synonym' . $synonymNr . ' AS name, sv_name AS realname ') . '
							 FROM ' . str_replace('###NAVIGATION_MM_TABLE###',$navigation_mm_table,$from) . '
							 WHERE ' . str_replace('###SERVICE_TABLE###',$service_table,str_replace('###NAVIGATION_MM_TABLE###',$navigation_mm_table,$where)) . ' ' .
								(($i==1)?'AND tx_civserv_service.pid IN (' . $this->community[pidlist] . ') ':'') .
								($regexp?'AND sv_synonym' . $synonymNr . ' REGEXP "' . $regexp . '"':'') .
								'AND sv_synonym' . $synonymNr . ' != "" ' . ' ';
			}
		}

		if (!$count) {
			$query .= 'ORDER BY ' . $orderby . ' ';

			if ($limit) {
				if ($this->piVars[pointer] > '') {
					$start = $this->conf['services_per_page'] * $this->piVars[pointer];
				} else {
					$start = 0;
				}
				$count = $this->conf['services_per_page'];
				$query .= 'LIMIT ' . $start . ',' . $count;
			}
		}
		return $query;
	}


	/**
	 * Builds the heading for service list (used from function serviceList()). The heading depends on the mode and,
	 * if mode ist not 'service_list', the selected organisation id or navigation id.
	 *
	 * @param	string		The mode like given in piVars[mode].
	 * @param	integer		The uid from the selected organisation or circumstancd/usergroup.
	 * @return	string		The heading
	 */
	function getServiceListHeading($mode,$uid) {
		switch ($mode) {
			case 'service_list' :
				$heading = $this->pi_getLL('tx_civserv_pi1_service_list.service_list','Service list');
				break;
			case 'circumstance' :
				$heading = $this->pi_getLL('tx_civserv_pi1_service_list.circumstance','Circumstance');
				$field = 'nv_name';
				$table = 'tx_civserv_navigation';
				break;
			case 'usergroup' :
				$heading = $this->pi_getLL('tx_civserv_pi1_service_list.usergroup','Usergroup');
				$field = 'nv_name';
				$table = 'tx_civserv_navigation';
				break;
			case 'organisation' :
				$heading = $this->pi_getLL('tx_civserv_pi1_service_list.organisation','Organisation');
				$field = 'or_name';
				$table = 'tx_civserv_organisation';
				break;
		}
		if ($mode != 'service_list') {
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
						$field,
						$table,
						'uid = ' . $uid);
			$category = $this->sql_fetch_array_r($res);
			if (count($category) == 1) {
				$heading .= ': ' . $category[0][$field];
			} else {
				$GLOBALS['error_message'] = $this->pi_getLL('tx_civserv_pi1_error.unknown_category','The given Category (Circumstance, Usergroup or Organisation) is unknown.');
				return false;
			}
		} else {
			$heading .= ': ' . $this->pi_getLL('tx_civserv_pi1_service_list.overview','Overview');
		}

		return $heading;
	}


	/**
	 * Generate and return tree structure for circumstances und usergroups.
	 *
	 * @param	object		Smarty object, the template key/value-pairs should be assigned to
	 * @param	integer		UID of highest level circumstance/usergroup/organisation
	 * @param	boolean		If true, a searchbox is generated (keyword search)
	 * @param	boolean		If true, a list with the top <i>plugin.tx_civserv_pi1.topCount</i> services is generated
	 * @return	boolean		True, if the function was executed without any error, otherwise false
	 */
	function navigationTree(&$smartyTree,$uid,$searchBox=false,$topList=false) {
		$mode = $this->piVars[mode];
		$content = $this->makeTree($uid,$content,$mode);
		$smartyTree->assign('content',$content);

		if ($searchBox) {
			$_SERVER['REQUEST_URI'] = $this->pi_linkTP_keepPIvars_url(array(mode => 'search_result'),0,1);
			$smartyTree->assign('searchbox', $this->pi_list_searchBox('',true));
		}

		if ($topList) {
			if (!$this->calculate_top15($smartyTree,false,$this->conf['topCount'])) {
				return false;
			}
		}

		// Assign labels
		$smartyTree->assign('circumstance_tree_label',$this->pi_getLL('tx_civserv_pi1_circumstance.circumstance_tree','Circumstances'));
		$smartyTree->assign('usergroup_tree_label',$this->pi_getLL('tx_civserv_pi1_usergroup.usergroup_tree','Usergroups'));
		$smartyTree->assign('organisation_tree_label',$this->pi_getLL('tx_civserv_pi1_organisation.organisation_tree','Organisation'));
		return true;
	}

	/**
	 * Generates a list of all employees
	 *
	 * @param	[type]		$$smartyEmployeeList: ...
	 * @param	[type]		$abcBar: ...
	 * @param	[type]		$searchBox: ...
	 * @param	[type]		$topList: ...
	 * @return	[type]		...
	 */
	function employee_list(&$smartyEmployeeList,$abcBar=false,$searchBox=false,$topList=false){
		$query = $this->makeEmployeeListQuery($this->piVars[char]);
		$res_employees = $GLOBALS['TYPO3_DB']->sql(TYPO3_db,$query);
		$row_counter = 0;
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_employees) ) {
				$employees[$row_counter]['em_name'] = $row['em_name'];
				$employees[$row_counter]['em_firstname'] = $row['em_firstname'];
				$employees[$row_counter]['em_datasec'] = $row['em_datasec'];

				//select the organisation assigned to the employee
				$orga_res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
					'tx_civserv_position.uid as pos_uid, tx_civserv_organisation.uid as or_uid, tx_civserv_employee.uid as emp_uid, or_name as organisation',
					'tx_civserv_employee, tx_civserv_position, tx_civserv_organisation, tx_civserv_employee_em_position_mm, tx_civserv_position_po_organisation_mm',
					'tx_civserv_employee.uid = ' . $row['emp_uid'] . ' AND tx_civserv_position.uid = '.$row['pos_uid'] .'
					 AND !tx_civserv_organisation.deleted AND !tx_civserv_organisation.hidden
					 AND !tx_civserv_employee.deleted AND !tx_civserv_employee.hidden
					 AND !tx_civserv_position.deleted AND !tx_civserv_position.hidden
					 AND !tx_civserv_organisation.deleted AND !tx_civserv_organisation.hidden
					 AND tx_civserv_employee.uid = tx_civserv_employee_em_position_mm.uid_local
					 AND tx_civserv_employee_em_position_mm.uid_foreign = tx_civserv_position.uid
					 AND tx_civserv_position.uid = tx_civserv_position_po_organisation_mm.uid_local
					 AND tx_civserv_organisation.uid = tx_civserv_position_po_organisation_mm.uid_foreign',
					 '',
					 '',
					 '');
					while ($orga_row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($orga_res) ) {
						$employees[$row_counter]['orga_name'] = $orga_row[organisation];
					}
					$employees[$row_counter]['em_url'] = htmlspecialchars($this->pi_linkTP_keepPIvars_url(array(mode => 'employee',id => $row['emp_uid'],pos_id => $row['pos_uid']),1,1));
					$row_counter++;
		}


		// Retrieve the employee count
		$row_count = 0;
		$query = $this->makeEmployeeListQuery($this->piVars[char],false,true);
		$res = $GLOBALS['TYPO3_DB']->sql(TYPO3_db,$query);
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			#$row_count += $row['anzahl'];
			$row_count += $row['count(*)'];
		}

		$this->internal['res_count'] = $row_count;
		$this->internal['results_at_a_time']= $this->conf['employee_per_page'];
		$this->internal['maxPages'] = $this->conf['max_pages_in_pagebar'];

		$smartyEmployeeList->assign('heading',$this->pi_getLL('tx_civserv_pi1_employee_list.employee_list.heading','Employees'));
		$smartyEmployeeList->assign('subheading',$this->pi_getLL('tx_civserv_pi1_employee_list.available_employees','Here you find the following employees'));
		$smartyEmployeeList->assign('pagebar',$this->pi_list_browseresults(true,'',' | '));
		$smartyEmployeeList->assign('employees',$employees);

		if ($abcBar) {
			$query = $this->makeEmployeeListQuery(all,false);
			$smartyEmployeeList->assign('abcbar',$this->makeAbcBar($query));
		}

		return true;
	}
	
	/**
	 * Generates a database query for the function employee_list. The returned query depends on the given parameter (like described below)
	 * and the piVars 'char' and 'pointer', additionally the pidlist for the actual community is fetched from the class variable community.
	 *
	 * @param	[type]		$char: ...
	 * @param	[type]		$limit: ...
	 * @param	[type]		$count: ...
	 * @return	[type]		...
	 */
	function makeEmployeeListQuery($char=all,$limit=true,$count=false) {
			if ($char != all) {
				$regexp = $this->buildRegexp($char);
			}
			if ($count){
				$query = 'Select count(*) from tx_civserv_employee, tx_civserv_position, tx_civserv_employee_em_position_mm where tx_civserv_employee.pid IN (' . $this->community[pidlist] . ') AND tx_civserv_employee.uid = tx_civserv_employee_em_position_mm.uid_local AND tx_civserv_employee_em_position_mm.uid_foreign = tx_civserv_position.uid';
			} else {
				$query = 'Select tx_civserv_employee.em_name, tx_civserv_employee.em_firstname, tx_civserv_employee.em_name as name, tx_civserv_employee.em_datasec, '.
					'tx_civserv_employee.uid as emp_uid, tx_civserv_position.uid as pos_uid ' .
					'from tx_civserv_employee, tx_civserv_position, tx_civserv_employee_em_position_mm ' .
					 'where tx_civserv_employee.pid IN (' . $this->community[pidlist] . ') '
					 . ($regexp?'AND em_name REGEXP "' . $regexp . '"':'') .
					 'AND tx_civserv_employee.uid = tx_civserv_employee_em_position_mm.uid_local ' .
					 'AND tx_civserv_employee_em_position_mm.uid_foreign = tx_civserv_position.uid' ;
			}

			$orderby =	$this->piVars[sort]?'name DESC':'name ASC';

			if (!$count) {
			$query .= ' ORDER BY ' . $orderby . ' ';

				if ($limit) {
					if ($this->piVars[pointer] > '') {
						$start = $this->conf['employee_per_page'] * $this->piVars[pointer];
					} else {
						$start = 0;
					}
					$max = $this->conf['employee_per_page'];
					$query .= 'LIMIT ' . $start . ',' . $max;
					}
			}
			return $query;
		}

	
	
	/**
	 * Generates a list of all available forms, including the assigned services.
	 *
	 * @param	object		Smarty object, the template key/value-pairs should be assigned to
	 * @param	integer		If given, only the forms according to the organisation with the given organisation id are contained in the list.
	 * @param	boolean		If true, an ABC-bar is generated to navigate throug forms.
	 * @param	boolean		If true, a searchbox is generated (keyword search)
	 * @param	boolean		If true, a list with the top <i>plugin.tx_civserv_pi1.topCount</i> services is generated
	 * @param	boolean		If true, a list with all organisations is generated
	 * @return	boolean		True, if the function was executed without any error, otherwise false
	 */
	function formList(&$smartyFormList,$organisation_id=0,$abcBar=false,$searchBox=false,$topList=false,$orgaList=false) {
		//Set path to forms of services
		$folder_forms = $this->conf['folder_services'];
		$folder_forms .= $this->community['id'] . '/forms/';

		$query = $this->makeFormListQuery($this->piVars[char],$organisation_id);
		$forms_res = $GLOBALS['TYPO3_DB']->sql(TYPO3_db,$query);

		$form_row_counter = 0;
		while ($form_row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($forms_res) ) {
			$forms[$form_row_counter]['name'] = $this->pi_getEditIcon($form_row[name],'fo_name',$this->pi_getLL('tx_civserv_pi1_form_list.name','form name'),$form_row,'tx_civserv_form');
			$forms[$form_row_counter]['descr'] = $this->formatStr($this->local_cObj->stdWrap($this->pi_getEditIcon(trim($form_row[descr]),'fo_descr',$this->pi_getLL('tx_civserv_pi1_form_list.description','form description'),$form_row,'tx_civserv_form'),$this->conf['fo_name_stdWrap.']));
			if ($form_row[checkbox] == 1) {
				$forms[$form_row_counter]['url'] = $this->cObj->typoLink_URL(array(parameter => $form_row[url]));
			} else {
				$forms[$form_row_counter]['url'] = $folder_forms . $form_row[file];
			}

			// select the services assigned to the form
			$services_res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
						'tx_civserv_service.uid, tx_civserv_service.sv_name AS name',
						'tx_civserv_service, tx_civserv_form, tx_civserv_service_sv_form_mm',
						'NOT tx_civserv_service.hidden AND NOT tx_civserv_service.deleted AND
						 ((UNIX_TIMESTAMP(LOCALTIMESTAMP) BETWEEN tx_civserv_service.starttime AND tx_civserv_service.endtime) OR
					 	 ((UNIX_TIMESTAMP(LOCALTIMESTAMP) > tx_civserv_service.starttime) AND (tx_civserv_service.endtime = 0)) OR
					 	 (tx_civserv_service.starttime = 0 AND tx_civserv_service.endtime = 0)) AND
						 tx_civserv_service.uid = tx_civserv_service_sv_form_mm.uid_local AND
						 tx_civserv_form.uid = tx_civserv_service_sv_form_mm.uid_foreign AND
				   		 tx_civserv_form.uid = ' . $form_row[uid]);

			$service_row_counter = 0;
			while ($service_row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($services_res)) {
				$forms[$form_row_counter]['services'][$service_row_counter]['name'] = $service_row[name];
				$forms[$form_row_counter]['services'][$service_row_counter]['link'] = htmlspecialchars($this->pi_linkTP_keepPIvars_url(array(mode => 'service',id => $service_row['uid']),$this->conf['cache_services'],1));
				$service_row_counter++;
			}
			$form_row_counter++;
		}

		// getting the form count
		$row_count = 0;
		$query = $this->makeFormListQuery($this->piVars[char],$organisation_id,false,true);
		$res = $GLOBALS['TYPO3_DB']->sql(TYPO3_db,$query);
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			//$row_count += $row['count(*)'];
			//change proposed by kreis warendorf to eliminate duplicates
			$row_count += $row['count(DISTINCT tx_civserv_form.uid)'];
		}

		$this->internal['res_count'] = $row_count;
		$this->internal['results_at_a_time'] = $this->conf['forms_per_page'];
		$this->internal['maxPages'] = $this->conf['max_pages_in_pagebar'];
		$smartyFormList->assign('form_list',$forms);

		if ($abcBar) {
			$query = $this->makeFormListQuery(all,$organisation_id,false);
			$smartyFormList->assign('abcbar',$this->makeAbcBar($query));
		}

		// get heading
		$heading = $this->pi_getLL('tx_civserv_pi1_form_list.form_list','Forms') . ': ';
		
		if ($organisation_id != 0) {
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
						'tx_civserv_organisation.or_name AS name',
						'tx_civserv_organisation',
						'tx_civserv_organisation.uid = ' . $organisation_id);
			if ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
				$heading .= $row[name];
			} else {
				$GLOBALS['error_message'] = $this->pi_getLL('tx_civserv_pi1_error.unvalid_organisation','An invalid organisation id was given.');
				return false;
			}
		} else {
			$heading .= $this->pi_getLL('tx_civserv_pi1_form_list.overview','Overview');
		}
		$GLOBALS['TSFE']->page['title'] = $heading;
		$smartyFormList->assign('heading',$heading);
		$smartyFormList->assign('subheading',$this->pi_getLL('tx_civserv_pi1_form_list.available_forms','Here you find the following forms'));
		$smartyFormList->assign('assigned_services',$this->pi_getLL('tx_civserv_pi1_form_list.assigned_services','The following services are assigned with this form'));
		$smartyFormList->assign('pagebar',$this->pi_list_browseresults(true,'',' | '));

		if ($searchBox) {
			$_SERVER['REQUEST_URI'] = $this->pi_linkTP_keepPIvars_url(array(mode => 'search_result'),0,1);
			$smartyFormList->assign('searchbox', $this->pi_list_searchBox('',true));
		}

		if ($topList) {
			if (!$this->calculate_top15($smartyFormList,false,$this->conf['topCount'])) {
				return false;
			}
		}

		if ($orgaList) {
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
						'tx_civserv_organisation.uid AS uid, tx_civserv_organisation.or_name AS name',
						'tx_civserv_form, tx_civserv_service, tx_civserv_organisation, tx_civserv_service_sv_form_mm, tx_civserv_service_sv_organisation_mm',
						'tx_civserv_organisation.pid IN (' . $this->community[pidlist] . ')
					 	 AND NOT tx_civserv_form.hidden AND NOT tx_civserv_form.deleted
						 AND ((UNIX_TIMESTAMP(LOCALTIMESTAMP) BETWEEN tx_civserv_form.starttime AND tx_civserv_form.endtime)
					 	 OR ((UNIX_TIMESTAMP(LOCALTIMESTAMP) > tx_civserv_form.starttime) AND (tx_civserv_form.endtime = 0))
					 	 OR (tx_civserv_form.starttime = 0 AND tx_civserv_form.endtime = 0))
					 	 AND NOT tx_civserv_service.hidden AND NOT tx_civserv_service.deleted
						 AND ((UNIX_TIMESTAMP(LOCALTIMESTAMP) BETWEEN tx_civserv_service.starttime AND tx_civserv_service.endtime)
					 	 OR ((UNIX_TIMESTAMP(LOCALTIMESTAMP) > tx_civserv_service.starttime) AND (tx_civserv_service.endtime = 0))
					 	 OR (tx_civserv_service.starttime = 0 AND tx_civserv_service.endtime = 0))
					 	 AND NOT tx_civserv_organisation.hidden AND NOT tx_civserv_organisation.deleted
					 	 AND tx_civserv_service.uid = tx_civserv_service_sv_form_mm.uid_local
					 	 AND tx_civserv_form.uid = tx_civserv_service_sv_form_mm.uid_foreign
					 	 AND tx_civserv_service.uid = tx_civserv_service_sv_organisation_mm.uid_local
					 	 AND tx_civserv_organisation.uid = tx_civserv_service_sv_organisation_mm.uid_foreign' .
					 	 (($organisation_id != 0)?' AND tx_civserv_organisation.uid != ' . $organisation_id:''),
					 	 'tx_civserv_organisation.uid',
					 	 'name');
			$row_counter = 0;
			while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res))	{
				$organisations[$row_counter]['link'] =  htmlspecialchars($this->pi_linkTP_keepPIvars_url(array(mode => 'form_list',id => $row['uid']),1,1));
				$organisations[$row_counter]['name'] = $row['name'];
				$row_counter++;
			}
			$smartyFormList->assign('organisations',$organisations);
		}

		return true;
	}


	/**
	 * Generates a database query for the function formList. The returned query depends on the given parameter (like described below)
	 * and the piVars 'char' and 'pointer', additionally the pidlist for the actual community is fetched from the class variable community.
	 * The returned query contains UNIONs.
	 *
	 * @param	string		The beginning character, on which the list should be limited. Could also be a sequence of beginning characters.
	 * @param	integer		Organisation uid. If not sero, the query is limited to forms according to this organisation.
	 * @param	boolean		If true, the list is limited to 'forms_per_page' (constant from $this->conf) services per page. The page number is fetched from piVars[pointer].
	 * @param	boolean		If true, the services are only counted.
	 * @return	string		The database query.
	 */
	function makeFormListQuery($char=all,$organisation_id=0,$limit=true,$count=false) {
		if ($count) {
			//$select = 'count(*)';
			//change proposed by kreis warendorf to eliminate duplicates
			$select = 'count(DISTINCT tx_civserv_form.uid)';
		} else {
			$select = 'tx_civserv_form.uid, tx_civserv_form.fo_name AS name, tx_civserv_form.fo_descr AS descr, tx_civserv_form.fo_external_checkbox AS checkbox, tx_civserv_form.fo_url AS url, tx_civserv_form.fo_formular_file AS file';
		}

		$from  =	'tx_civserv_form, tx_civserv_service, tx_civserv_service_sv_form_mm';
		$where =	'NOT tx_civserv_form.deleted AND NOT tx_civserv_form.hidden
					 AND ((UNIX_TIMESTAMP(LOCALTIMESTAMP) BETWEEN tx_civserv_form.starttime AND tx_civserv_form.endtime)
					 OR ((UNIX_TIMESTAMP(LOCALTIMESTAMP) > tx_civserv_form.starttime) AND (tx_civserv_form.endtime = 0))
					 OR (tx_civserv_form.starttime = 0 AND tx_civserv_form.endtime = 0))
				 	 AND NOT tx_civserv_service.hidden AND NOT tx_civserv_service.deleted
					 AND ((UNIX_TIMESTAMP(LOCALTIMESTAMP) BETWEEN tx_civserv_service.starttime AND tx_civserv_service.endtime)
				 	 OR ((UNIX_TIMESTAMP(LOCALTIMESTAMP) > tx_civserv_service.starttime) AND (tx_civserv_service.endtime = 0))
				 	 OR (tx_civserv_service.starttime = 0 AND tx_civserv_service.endtime = 0))
				 	 AND tx_civserv_service.uid = tx_civserv_service_sv_form_mm.uid_local
				 	 AND tx_civserv_form.uid = tx_civserv_service_sv_form_mm.uid_foreign';
		if ($organisation_id != 0) {
			$from  .=	', tx_civserv_organisation, tx_civserv_service_sv_organisation_mm';
			$where .=	' AND NOT tx_civserv_organisation.hidden AND NOT tx_civserv_organisation.deleted
					 	 AND tx_civserv_service.uid = tx_civserv_service_sv_organisation_mm.uid_local
					 	 AND tx_civserv_organisation.uid = tx_civserv_service_sv_organisation_mm.uid_foreign
						 AND tx_civserv_organisation.uid = ' . $organisation_id;
		}

		if ($char != all) {
			$regexp = $this->buildRegexp($char);
		}

		$orderby = $this->piVars[sort]?'name DESC':'name ASC';

		// The first time the loop is executed, the part of the query for selecting the services which are located directly at the community is build.
		// The second time the loop is executed, the part of the query for selecting the services located at another community is build.
		$query = '';
		for ($i = 1; $i <= 2; $i++) {
			if ($i == 2) {
				$from  .=	', tx_civserv_external_service';
				$where .=	' ' .
							'AND tx_civserv_external_service.es_external_service = tx_civserv_service.uid
							 AND NOT tx_civserv_external_service.deleted
							 AND NOT tx_civserv_external_service.hidden
							 AND tx_civserv_external_service.pid IN (' . $this->community[pidlist] . ')';
				$query .= 'UNION ALL ';
			}
			//change proposed by Kreis Warendorf 24.01.05: we don't want double entries, so we go for DISTINCT
			$query .=	'SELECT DISTINCT ' . $select . '
						 FROM ' . $from . '
						 WHERE ' . $where . ' ' .
							(($i==1)?'AND tx_civserv_form.pid IN (' . $this->community[pidlist] . ') ':' ') .
							($regexp?'AND fo_name REGEXP "' . $regexp . '" ':' ');
		}
		if (!$count) {
			$query .= 'ORDER BY ' . $orderby . ' ';

			if ($limit) {
				if ($this->piVars[pointer] > '') {
					$start = $this->conf['forms_per_page'] * $this->piVars[pointer];
				} else {
					$start = 0;
				}
				$count = $this->conf['forms_per_page'];
				$query .= 'LIMIT ' . $start . ',' . $count;
			}
		}
		return $query;
	}


	/**
	 * Executes a search in the database for given keywords.
	 *
	 * @param	object		Smarty object, the template key/value-pairs should be assigned to
	 * @param	boolean		If true, a searchbox is generated (keyword search)
	 * @return	boolean		True, if the function was executed without any error, otherwise false
	 */
	function do_search(&$smartySearchResult,$searchBox) {
		$searchString = $this->piVars['sword'];
		$searchString = ereg_replace('"', '', $searchString);	//Delete quotation marks from search value
		$sword = preg_split('/[\s,.\"]+/',$searchString);		//Split search string into multiple keywords and store them in an array

		//Set initial where clauses
		$querypart_where = 'pid IN (' . $this->community[pidlist] . ')';
		$querypart_where2 = 'sw.uid = tx_civserv_service_sv_searchword_mm.uid_foreign
							 AND tx_civserv_service_sv_searchword_mm.uid_local = sv.uid';
		$querypart_where3 = '';
		$querypart_where4 = '';

		if (!empty($searchString)) {
			// Because UNION is not yet implemented in the sql wrapper class, a lower
			// abstraction level for the sql statement is used. This should be no problem,
			// because UNION is standard in almost all DBMS.
			for ($i = 0; $i < count($sword); $i++) {
				if ($i == 0) {
						$querypart_where .= ' AND (sv_name LIKE "%' . $sword[$i] . '%"
											  OR sv_synonym1 LIKE "%' . $sword[$i] . '%"
											  OR sv_synonym2 LIKE "%' . $sword[$i] . '%"
											  OR sv_synonym3 LIKE "%' . $sword[$i] . '%"';
						$querypart_where2 .= ' AND (sw.sw_search_word LIKE "%' . $sword[$i] . '%" ';
						$querypart_where3 .= 'ms_name LIKE "%' . $sword[$i] . '%"
											  OR ms_synonym1 LIKE "%' . $sword[$i] . '%"
											  OR ms_synonym2 LIKE "%' . $sword[$i] . '%"
											  OR ms_synonym3 LIKE "%' . $sword[$i] . '%"';
						$querypart_where4 .= 'sw_search_word LIKE "%' . $sword[$i] . '%" ';
				} else {
					$querypart_where .= ' OR sv_name LIKE "%' . $sword[$i] . '%"
										  OR sv_synonym1 LIKE "%' . $sword[$i] . '%"
										  OR sv_synonym2 LIKE "%' . $sword[$i] . '%"
										  OR sv_synonym3 LIKE "%' . $sword[$i] . '%"';
					$querypart_where2 .= ' OR sw.sw_search_word LIKE "%' . $sword[$i] . '%" ';
					$querypart_where3 .= ' OR ms_name LIKE "%' . $sword[$i] . '%"
										  OR ms_synonym1 LIKE "%' . $sword[$i] . '%"
										  OR ms_synonym2 LIKE "%' . $sword[$i] . '%"
										  OR ms_synonym3 LIKE "%' . $sword[$i] . '%"';
					$querypart_where4 .= ' OR sw_search_word LIKE "%' . $sword[$i] . '%" ';
				}
			}

			//Query for getting uid list of matching search words
			$res_searchword = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
						'uid',
						'tx_civserv_search_word',
						$querypart_where4 . ' AND NOT deleted AND NOT hidden',
						'',
						'');

			if ($GLOBALS['TYPO3_DB']->sql_num_rows($res_searchword) > 0) {
				$uidlist_searchwords = $this->sql_fetch_array_r($res_searchword);
			} else {
				$uidlist_searchwords[0]=''; //Create empty array for 'in_array'-function
			}

			//Query for getting uid list of model services
			$res_model_service = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
						'uid,ms_searchword',
						'tx_civserv_model_service',
						'ms_searchword != 0',
						'',
						'');

			//Open uid match list
			$searchword_uid_list = '(';
			$list_start = 1;
			while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_model_service) ) {
					$searchword_uid_array = explode(",", $row[ms_searchword]);
					for ($i = 0 ; $i < count($searchword_uid_array) ; $i++) {
						if ($uidlist_searchwords != NULL && in_array(array('uid' => $searchword_uid_array[$i]),$uidlist_searchwords)) {
							$searchword_uid_list .=  $list_start?$row[uid]:',' . $row[uid];    //Add model service uid to match list
							$list_start = 0;
							break;
						}
					}
			}
			//Close uid match list
			$searchword_uid_list .= ')';

			if ($searchword_uid_list != '()') {
				$querypart_where3 .= ' OR (ms.uid IN ' . $searchword_uid_list . ')';
			}

			$res = $GLOBALS['TYPO3_DB']->sql(TYPO3_db,
					'SELECT sv.uid as uid, sv.sv_name as name
					 FROM tx_civserv_service as sv
					 WHERE !sv.deleted AND !sv.hidden AND ((UNIX_TIMESTAMP(LOCALTIMESTAMP) BETWEEN sv.starttime AND sv.endtime) OR
														   ((UNIX_TIMESTAMP(LOCALTIMESTAMP) > sv.starttime) AND (sv.endtime=0)) OR
														   (sv.starttime=0 AND sv.endtime=0) ) AND ' . $querypart_where . ')
					 UNION
					 SELECT sv.uid as uid, sv.sv_name as name
					 FROM tx_civserv_service as sv, tx_civserv_search_word as sw, tx_civserv_service_sv_searchword_mm
					 WHERE !sv.deleted AND !sv.hidden AND !sw.deleted AND !sw.hidden
					 								  AND ((UNIX_TIMESTAMP(LOCALTIMESTAMP) BETWEEN sv.starttime AND sv.endtime) OR
														   ((UNIX_TIMESTAMP(LOCALTIMESTAMP) > sv.starttime) AND (sv.endtime=0)) OR
														   (sv.starttime=0 AND sv.endtime=0) )
													  AND sv.pid IN (' . $this->community[pidlist] . ') AND ' . $querypart_where2 . ')
					 UNION
					 SELECT sv.uid as uid, sv.sv_name as name
					 FROM tx_civserv_service as sv, tx_civserv_model_service as ms
					 WHERE !sv.deleted AND !sv.hidden AND !ms.deleted AND !ms.hidden
													  AND ((UNIX_TIMESTAMP(LOCALTIMESTAMP) BETWEEN sv.starttime AND sv.endtime) OR
														   ((UNIX_TIMESTAMP(LOCALTIMESTAMP) > sv.starttime) AND (sv.endtime=0)) OR
														   (sv.starttime=0 AND sv.endtime=0) )
													  AND sv.sv_model_service = ms.uid AND sv.pid IN (' . $this->community[pidlist] . ')
													  AND (' . $querypart_where3 . ')

					ORDER BY name');

			$rowcount = $GLOBALS['TYPO3_DB']->sql_num_rows($res);
			// Check if query returned any results
			if ($rowcount == 0) {
				$GLOBALS['error_message'] = $this->pi_getLL('tx_civserv_pi1_search.no_results','No search results found!');
				return false;
			} else {
				// Output service search results
				$row_counter = 0;
				while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res) ) {
					$search_data[$row_counter]['link'] = htmlspecialchars($this->pi_linkTP_keepPIvars_url(array(mode => 'service',id => $row['uid']),1,1));
					$search_data[$row_counter]['name'] = $row['name'];
					$row_counter++;
				}

				if ($searchBox) {
					$_SERVER['REQUEST_URI'] = $this->pi_linkTP_keepPIvars_url(array(mode => 'search_result'),0,1);
					$smartySearchResult->assign('searchbox', $this->pi_list_searchBox());
				}

				$smartySearchResult->assign('service',$search_data);
				$smartySearchResult->assign('number',$rowcount);
				$smartySearchResult->assign('employee_label',$this->pi_getLL('tx_civserv_pi1_search_result.employee','Matching employees'));
				$smartySearchResult->assign('service_label',$this->pi_getLL('tx_civserv_pi1_search_result.service','Matching services'));
				return true;
			}
		}	// End query services

		// Empty query
		else {
			$GLOBALS['error_message'] = $this->pi_getLL('tx_civserv_pi1_search.empty_query','Empty query! Search string required.');
			return false;
		}
		// End empty query
	}


	/**
	 * Calculates the 15 most frequently used services through a database query.
	 *
	 * @param	object		Smarty object, the template key/value-pairs should be assigned to
	 * @param	boolean		If true, the count the service is visited is shown. Default is true.
	 * @param	integer		Sets how many frequently used services are shown. Default is 15.
	 * @param	boolean		If true, a searchbox is generated (keyword search)
	 * @return	boolean		True, if the function was executed without any error, otherwise false
	 */
	function calculate_top15(&$smartyTop15,$showCounts=1,$topN=15,$searchBox=false) {
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('sv.uid as uid,sv.sv_name as name,SUM(al.al_number) as number',	//WHERE
														 'tx_civserv_accesslog as al,tx_civserv_service as sv',			//FROM
														 '!sv.deleted AND !sv.hidden AND sv.uid = al.al_service_uid
														  AND sv.pid IN (' . $this->community[pidlist] . ')',							//WHERE
														 'al.al_service_uid',											//GROUP BY
														 'number DESC',													//ORDER BY
														 $topN); 														//LIMIT
		$row_counter = 0;
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			$top15_data[$row_counter]['link'] = htmlspecialchars($this->pi_linkTP_keepPIvars_url(array(mode => 'service',id => $row['uid']),1,1));
			$top15_data[$row_counter]['name'] = $row['name'];
			if ($showCounts) {
				$top15_data[$row_counter]['number'] = $row['number'];
			}
			$row_counter++;
		}
		$smartyTop15->assign('top15',$top15_data);
		$smartyTop15->assign('top15_label',$this->pi_getLL('tx_civserv_pi1_top15.top15','The 15 most frequently requested services'));
		$smartyTop15->assign('serviceinformation_label',$this->pi_getLL('tx_civserv_pi1_common.serviceinformation','Service information'));
		$smartyTop15->assign('frequently_visited_label',$this->pi_getLL('tx_civserv_pi1_common.frequently_visited','The following sites are visited frequently'));

		if ($searchBox) {
			$_SERVER['REQUEST_URI'] = $this->pi_linkTP_keepPIvars_url(array(mode => 'search_result'),0,1);
			$smartyTop15->assign('searchbox', $this->pi_list_searchBox('',true));
		}

		return true;
	}









	/******************************
	 *
	 * Heplper functions for the navigation functions:
	 *
	 *******************************/


	/**
	 * Builds a bar with all characters from the alphabet and an last item 'A-Z'. If a special character has to be active (if it contains items),
	 * is determined from the result set of the given query. The link for each character is build by adding piVars[char] to the actual url.
	 * Used by the functions 'serviceList' and 'formList'.
	 *
	 * @param	string		A query which gets all items.
	 * @return	string		HTML-Code for abc-bar.
	 */
	function makeAbcBar($query) {
		// getting all accouring initial from the DB
		$res = $GLOBALS['TYPO3_DB']->sql(TYPO3_db,$query);

		$row_counter = 0;
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res))	{
			$initial = str_replace(array('Ä','Ö','Ü'),array('A','O','U'),strtoupper($row['name']{0}));
			$occuringInitials[] = $initial;
			$row_counter++;
		}
		if ($occuringInitials ) $occuringInitials = array_unique($occuringInitials);

		$alphabet = array(A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z);

		// build a string with the links to the character-sites
		$abcBar =  '<p id="abcbar">' . "\n\t";
		for($i = 0; $i < sizeof($alphabet); $i++)	{
			$actual = (strtoupper($this->piVars[char])==$alphabet[$i]);
			if($occuringInitials && in_array($alphabet[$i],$occuringInitials))	{
				$abcBar .= sprintf('%s' . $this->pi_linkTP_keepPIvars($alphabet[$i],array(char => $alphabet[$i],pointer => 0),1,0) . '%s | ',
						$actual?'<strong>':'',
						$actual?'</strong>':'');
			}
			else	{
				$abcBar .= $alphabet[$i] . ' | ';
			}
		}
		// adding the link 'A-Z'
		$actual = ($this->piVars[char] <= '');
		$abcBar .= sprintf('%s' . $this->pi_linkTP_keepPIvars('A-Z',array(char => '',pointer => 0),1,0) . '%s' . "\n",
						$actual?'<strong>':'',
						$actual?'</strong>':'');
		$abcBar .= "</p>\n";
		return $abcBar;
	}


	/**
	 * Build a regular expression to select all items which begin with the given string (normally one character).
	 * In oder to use this function with the abc-bar, umlauts are treated like the corrospendent vocals.
	 * Used by the functions 'serviceList' and 'formList'.
	 *
	 * @param	string		The charavter or string the alement should begin with
	 * @return	string		The regular expression.
	 */
	 function buildRegexp($char) {
		switch (strtoupper($char)) {
			case ''  :
				break;
			case 'A' :
				$regexp = '^A|^Ä';
			break;
			case 'O' :
				$regexp = '^O|^Ö';
				break;
			case 'U' :
		 		$regexp = '^U|^Ü';
				break;
			default :
				$regexp = '^' . $char;
		}
		
		return $regexp;
	}


	/**
	 * Generates a tree structure for circumstances, organisations and usergroups based on html list tags.
	 * Used by the functions 'navigationTree' and 'organisationTree'.
	 *
	 * @param	integer		UID of circumstance, organisation or usergroup
	 * @param	string		HTML content
	 * @param	string		Mode for which tree is to be generated (
	 * @return	string		Content that is to be displayed within the plugin
	 */
	function makeTree($uid,$add_content,$mode) {
		global $add_content;
		//Execute query depending on mode
		if ($mode == 'circumstance_tree' || $mode == 'usergroup_tree') {
			$result = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
								'nv1.uid as uid, nv1.nv_name as name',
								'tx_civserv_navigation as nv1,tx_civserv_navigation_nv_structure_mm as nvmm,tx_civserv_navigation as nv2',
								'!nv1.deleted AND !nv1.hidden AND !nv2.deleted AND !nv2.hidden
								 AND nv1.uid = nvmm.uid_local AND nv2.uid = nvmm.uid_foreign
								 AND nv2.uid = ' . $uid ,
								'',
								'name',
								'');
		}
		if ($mode == 'organisation_tree') {
			$result = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
								'or1.uid as uid, or1.or_name as name',
								'tx_civserv_organisation as or1,tx_civserv_organisation_or_structure_mm as ormm,tx_civserv_organisation as or2',
								'!or1.deleted AND !or1.hidden AND !or2.deleted AND !or2.hidden
								 AND or1.uid = ormm.uid_local AND or2.uid = ormm.uid_foreign
								 AND or2.uid = ' . $uid ,
								'',
								'or1.sorting',
								'');
		}
		//Check if query returned any results
		if ($GLOBALS['TYPO3_DB']->sql_num_rows($result) > 0) {
			$add_content = $add_content .  '<ul>';
			while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($result)) {
				$uid = $row["uid"];
				switch ($mode) {
					case 'circumstance_tree':
						$link_mode = 'circumstance';
						break;
					case 'usergroup_tree':
						$link_mode = 'usergroup';
						break;
					case 'organisation_tree':
						$link_mode = 'organisation';
						break;
				}
				$add_content .= '    <li>&nbsp;<a href="' . htmlspecialchars($this->pi_linkTP_keepPIvars_url(array(mode => $link_mode,id => $row['uid']),1,1)) . '">' . $row["name"] . '</a>';
				$this->makeTree($uid,$add_content,$mode);
				$add_content .= "</li>\n";
			}
			$add_content = $add_content . "</ul>\n";
		}
		return $add_content;
	}










	/******************************
	 *
	 * Functions for the detail pages (service, employee, organisation):
	 *
	 *******************************/


	/**
	 * Generates information about a specific service.
	 *
	 * @param	object		Smarty object, the template key/value-pairs should be assigned to
	 * @param	boolean		If true, a searchbox is generated (keyword search)
	 * @param	boolean		If true, a list with the top <i>plugin.tx_civserv_pi1.topCount</i> services is generated
	 * @return	boolean		True, if the function was executed without any error, otherwise false
	 */
	function serviceDetail(&$smartyService,$searchBox=false,$topList=false) {
		$uid = $this->piVars[id];
		$community_id = $this->community['id'];
		$employee = $this->community['employee_search'];
				
		//search Employee Details
		$smartyService->assign('employee_search',$employee);

		//Set path to forms of services
		$folder_forms = $this->conf['folder_services'];
		$folder_forms .= $this->community['id'] . '/forms/';

		//Query for standard service details
		$res_common = $this->queryService(intval($uid));

		//Query for associated forms
		$res_forms = $GLOBALS['TYPO3_DB']->exec_SELECT_mm_query(
						'tx_civserv_form.uid as uid, fo_name as name, fo_url as url, fo_formular_file as file, fo_external_checkbox as checkbox',
						'tx_civserv_service',
						'tx_civserv_service_sv_form_mm',
						'tx_civserv_form',
						'AND tx_civserv_service.uid = ' . $uid . '
		 				 AND NOT tx_civserv_form.hidden AND NOT tx_civserv_form.deleted
		 				 AND ((UNIX_TIMESTAMP(LOCALTIMESTAMP) BETWEEN tx_civserv_form.starttime AND tx_civserv_form.endtime)
						 	  OR ((UNIX_TIMESTAMP(LOCALTIMESTAMP) > tx_civserv_form.starttime) AND (tx_civserv_form.endtime=0))
						 	  OR (tx_civserv_form.starttime=0 AND tx_civserv_form.endtime=0) )',
						'',
						'name');	//ORDER BY

		//Query for associated organisation units
		$res_orga = $GLOBALS['TYPO3_DB']->exec_SELECT_mm_query(
						'tx_civserv_organisation.uid as uid, or_name as name',
						'tx_civserv_service',
						'tx_civserv_service_sv_organisation_mm',
						'tx_civserv_organisation',
						'AND tx_civserv_service.uid = ' . $uid . '
		 				 AND NOT tx_civserv_organisation.hidden AND NOT tx_civserv_organisation.deleted',
						'',
						'name');	//ORDER BY

		//Query for associated employees
		$res_employees = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
						'tx_civserv_employee.uid as emp_uid, tx_civserv_position.uid as pos_uid, tx_civserv_service.uid as sv_uid, tx_civserv_service_sv_position_mm.sp_descr as description, em_title as title, em_name as name, em_firstname as firstname, em_telephone, ep_telephone , em_email, ep_email, em_datasec as datasec',
						'tx_civserv_service, tx_civserv_service_sv_position_mm, tx_civserv_position, tx_civserv_employee, tx_civserv_employee_em_position_mm',
						'tx_civserv_service.uid = ' . $uid . '
						 AND !tx_civserv_service.deleted AND !tx_civserv_service.hidden
						 AND !tx_civserv_position.deleted AND !tx_civserv_position.hidden
						 AND !tx_civserv_employee.deleted AND !tx_civserv_employee.hidden
						 AND !tx_civserv_employee_em_position_mm.deleted AND !tx_civserv_employee_em_position_mm.hidden
						 AND tx_civserv_service.uid = tx_civserv_service_sv_position_mm.uid_local
						 AND tx_civserv_service_sv_position_mm.uid_foreign = tx_civserv_position.uid
						 AND tx_civserv_employee.uid = tx_civserv_employee_em_position_mm.uid_local
						 AND tx_civserv_employee_em_position_mm.uid_foreign = tx_civserv_position.uid 
						 AND tx_civserv_employee.pid IN (' . $this->community[pidlist] . ')',
						'',
						'tx_civserv_service_sv_position_mm.sorting');	//ORDER BY

		//Query for search words
		$res_search_word = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
						'tx_civserv_service.uid as suid, tx_civserv_search_word.uid as wuid, tx_civserv_service.sv_name as sname, tx_civserv_search_word.sw_search_word as sword',
						'tx_civserv_service, tx_civserv_search_word, tx_civserv_service_sv_searchword_mm',
						'tx_civserv_service.uid = ' . $uid . '
						AND !tx_civserv_service.deleted AND !tx_civserv_service.hidden
						AND tx_civserv_service.uid = tx_civserv_service_sv_searchword_mm.uid_local
						AND tx_civserv_search_word.uid = tx_civserv_service_sv_searchword_mm.uid_foreign',
						'',
						'tx_civserv_search_word.sw_search_word'); //ORDER BY
						
		//Query for similar services
		$res_similar = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
						'similar.uid AS uid, similar.sv_name AS name',
						'tx_civserv_service AS service,tx_civserv_service_sv_similar_services_mm AS mm,tx_civserv_service AS similar',
						'service.uid = mm.uid_local AND mm.uid_foreign = similar.uid AND
						 service.uid = ' . $uid . ' AND service.uid != similar.uid AND
						 !similar.deleted AND !similar.hidden AND ((UNIX_TIMESTAMP(LOCALTIMESTAMP) BETWEEN similar.starttime AND similar.endtime) OR
								  ((UNIX_TIMESTAMP(LOCALTIMESTAMP) > similar.starttime) AND (similar.endtime=0)) OR
								  (similar.starttime=0 AND similar.endtime=0) )',
						'',
						'similar.sv_name');	//ORDER BY

		//Retrieve all uid's of transaction forms from transaction configuration table
		$res_transactions = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
						'ct_transaction_uid as uid',
						'tx_civserv_conf_transaction',
						'tx_civserv_conf_transaction.ct_community_id  = ' . $community_id);

		if ($GLOBALS['TYPO3_DB']->sql_num_rows($res_common) == 0) {
			$GLOBALS['error_message'] = $this->pi_getLL('tx_civserv_pi1_service.error_valid','Service does not exist or is not available.');
			return false;
		}

		$service_common = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_common);
		$service_employees = $this->sql_fetch_array_r($res_employees);

		//Check if service is an external service
		if (!array_key_exists($service_common['pid'],array_flip(explode(',',$this->community[pidlist])))) {
			$mandant = t3lib_div::makeInstanceClassName('tx_civserv_mandant');
			$mandantInst = new $mandant();
			$service_community = $mandantInst->get_mandant($service_common['pid']);
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
											'cm_community_name',
											'tx_civserv_conf_mandant',
											'cm_community_id = ' . $service_community);
			$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
			$smartyService->assign('external_service_label',$this->pi_getLL('tx_civserv_pi1_service.external_service','This service is provided and advised by') . ': ' . $row['cm_community_name']);
		} else {
			$service_community = $this->community[id];
		}

		$row_counter = 0;
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_similar))	{
			$similar[$row_counter]['link'] =  htmlspecialchars($this->pi_linkTP_keepPIvars_url(array(mode => 'service',id => $row['uid']),$this->conf['cache_services'],1));
			$similar[$row_counter]['name'] = $row['name'];
			$row_counter++;
		}

		//Add coloumns with url for email form and employee page to array $service_employees and format position description string
		for ($i = 0; $i < count($service_employees); $i++) {
			// use typolink, because of the possibility to use encrypted email-adresses for spam-protection
			$service_employees[$i]['email_code'] = $this->cObj->typoLink($service_employees[$i]['ep_email']?$service_employees[$i]['ep_email']:$service_employees[$i]['em_email'],array(parameter => $service_employees[$i]['ep_email']?$service_employees[$i]['ep_email']:$service_employees[$i]['em_email'],ATagParams => 'class="email"'));	// use typolink, because of the possibility to use encrypted email-adresses for spam-protection
			$service_employees[$i]['email_form_url'] = htmlspecialchars($this->pi_linkTP_keepPIvars_url(array(mode => 'set_email_form',id => $service_employees[$i]['emp_uid'],sv_id => $service_employees[$i]['sv_uid'],pos_id => $service_employees[$i]['pos_uid']),1,1));
			$service_employees[$i]['employee_url'] = htmlspecialchars($this->pi_linkTP_keepPIvars_url(array(mode => 'employee',id => $service_employees[$i]['emp_uid'],pos_id => $service_employees[$i]['pos_uid']),1,1));
			// Disabled b design issues
			//$service_employees[$i]['description'] = $this->formatStr($this->local_cObj->stdWrap($service_employees[$i]['description'],$this->conf['ep_sv_description_stdWrap.']));
			$service_employees[$i]['description'] = nl2br($service_employees[$i]['description']);
		}
		$smartyService->assign('employees',$service_employees);

		if ($GLOBALS['TYPO3_DB']->sql_num_rows($res_transactions) > 0) {
			$service_transactions = $this->sql_fetch_array_r($res_transactions);
		} else {
			$service_transactions[0] = ''; //Create empty array for 'in_array'-function
		}

		$row_counter = 0;
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_forms) ) {
			$service_forms[$row_counter]['name'] = $row[name];
			//Set correct url depending on type of associated form (transaction form, external form oder form file)
			if ($row[checkbox] == 1 && in_array(array('uid' => intval($row[uid])),$service_transactions)) {
				$service_forms[$row_counter]['url'] = $this->cObj->typoLink_URL(array(parameter => $row[url])) . '&tx_civserv_pi1[id]=' . $uid;
			} elseif ($row[checkbox] == 1) {
				$service_forms[$row_counter]['url'] = $this->cObj->typoLink_URL(array(parameter => $row[url]));
			} else {
				$service_forms[$row_counter]['url'] = $folder_forms.$row[file];
			}
			$row_counter++;
		}
		$smartyService->assign('forms',$service_forms);

		$row_counter = 0;
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_orga) ) {
			$service_organisations[$row_counter]['name'] = $row[name];
			$service_organisations[$row_counter]['url'] = htmlspecialchars($this->pi_linkTP_keepPIvars_url(array(mode => 'organisation',id => $row[uid]),1,1));
			$row_counter++;
		}
		$smartyService->assign('organisations',$service_organisations);
		
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_search_word) ) {
			$search_words[] = $row[sword];
		}
		if ($search_words > 0){
			$search_words = implode(", ", $search_words);
			$smartyService->assign('searchwords',$search_words);
		}

		//Query for model service
		if ($service_common[sv_model_service] > 0) {
			$res_model_service = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
						'ms_name, ms_descr_short, ms_descr_long, ms_image, ms_image_text, ms_fees, ms_documents, ms_legal_global',
						'tx_civserv_model_service',
						'!deleted AND !hidden AND uid = ' . intval($service_common[sv_model_service]) . '');

			$model_service = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_model_service);
		}

		//Check for external service flag
		if ($service_common[sv_region_checkbox] > 0) {
			$smartyService->assign('ext_link',$service_common[sv_region_link]);
		}

		//Service name
		if ($service_common[sv_name] != "") {
			$name = trim($service_common[sv_name]);
			// ATTENTION: Field-list is hardcoded, because there are problems with the image because of the upload folder
			$name = $this->pi_getEditIcon($name,'fe_admin_fieldList" => "hidden, starttime, endtime, fe_group, sv_name, sv_synonym1, sv_synonym2, sv_synonym3, sv_descr_short, sv_descr_long, sv_fees, sv_documents, sv_legal_local, sv_legal_global, sv_model_service, sv_similar_services, sv_service_version, sv_form, sv_searchword, sv_position, sv_organisation, sv_navigation, sv_region_checkbox, sv_region_link, sv_region_name',$this->pi_getLL('tx_civserv_pi1_service.name','Service name'),$service_common,'tx_civserv_service');
		} else {
			$name = trim($model_service[ms_name]);
		}
		$smartyService->assign('name',$name);

		//Short description
		if ($service_common[sv_descr_short] != "") {
			$descr_short = trim($service_common[sv_descr_short]);
			$descr_short = $this->pi_getEditIcon($descr_short,'sv_descr_short',$this->pi_getLL('tx_civserv_pi1_service.description_short','short description'),$service_common,'tx_civserv_service');
		} else {
			$descr_short = trim($model_service[ms_descr_short]);
		}
		$smartyService->assign('descr_short',$this->formatStr($this->local_cObj->stdWrap($descr_short,$this->conf['sv_descr_short_stdWrap.'])));

		//Long description
		$descr_long_ms = '';
		if ($model_service[ms_descr_long] != "") {
			$descr_long_ms = trim($model_service[ms_descr_long]) . '<br />';
		}
		$descr_long = trim($service_common[sv_descr_long]);
		$descr_long = $this->pi_getEditIcon($descr_long,'sv_descr_long',$this->pi_getLL('tx_civserv_pi1_service.description_long','Long description'),$service_common,'tx_civserv_service');
		$descr_long = $descr_long_ms . $descr_long;
		$smartyService->assign('descr_long',$this->formatStr($this->local_cObj->stdWrap($descr_long,$this->conf['sv_descr_long_stdWrap.'])));

		//Image text
		if ($service_common[sv_image_text] != "") {
			$image_text = trim($service_common[sv_image_text]);
		} else {
			$image_text = trim($model_service[ms_image_text]);
		}
		$image_descr = $this->pi_getEditIcon($image_text,'image_text',$this->pi_getLL('tx_civserv_pi1_service.image_text','Image description'),$service_common,'tx_civserv_service');
		$smartyService->assign('image_text',$image_descr);

		//Image
		if ($service_common[sv_image] != "") {
			$image = $service_common[sv_image];
			$imagepath = $this->conf['folder_organisations'] . $service_community . '/images/';
		} else {
			$image = $model_service[ms_image];
			$imagepath = $this->conf['folder_organisations'] . 'model_services/images/';
		}
		if ($image) {
			$imageCode = $this->getImageCode($image,$imagepath,$this->conf['service-image.'],$image_text);
		}
		$smartyService->assign('image',$imageCode);

		//Fees
		if ($service_common[sv_fees] != "") {
			$fees = trim($service_common[sv_fees]);
			$fees = $this->pi_getEditIcon($fees,'sv_fees',$this->pi_getLL('tx_civserv_pi1_service.description_fees','Fees'),$service_common,'tx_civserv_service');
		} else {
			$fees = trim($model_service[ms_fees]);
		}
		$smartyService->assign('fees',$this->formatStr($this->local_cObj->stdWrap($fees,$this->conf['sv_fees_stdWrap.'])));

		//Documents
		if ($service_common[sv_documents] != "") {
			$documents = trim($service_common[sv_documents]);
			$documents = $this->pi_getEditIcon($documents,'sv_documents',$this->pi_getLL('tx_civserv_pi1_service.description_documents','Necessary Documents'),$service_common,'tx_civserv_service');
		} else {
			$documents = trim($model_service[ms_documents]);
		}
		$smartyService->assign('documents',$this->formatStr($this->local_cObj->stdWrap($documents,$this->conf['sv_documents_general_stdWrap.'])));

		//Legal local
		$legal_local = $this->pi_getEditIcon($service_common[sv_legal_local],'sv_legal_local',$this->pi_getLL('tx_civserv_pi1_service.legal_local','Legal foundation (local)'),$service_common,'tx_civserv_service');
		$smartyService->assign('legal_local',$this->formatStr($this->local_cObj->stdWrap($legal_local,$this->conf['sv_legel_local_general_stdWrap.'])));

		//Legal global
		if ($service_common[sv_legal_global] != "") {
			$legal_global = trim($service_common[sv_legal_global]);
			$legal_global = $this->pi_getEditIcon($legal_global,'sv_legal_global',$this->pi_getLL('tx_civserv_pi1_service.legal_global','Legal foundation (global)'),$service_common,'tx_civserv_service');
		} else {
			$legal_global = trim($model_service[ms_legal_global]);
		}
		$smartyService->assign('legal_global',$this->formatStr($this->local_cObj->stdWrap($legal_global,$this->conf['sv_legal_global_general_stdWrap.'])));

		//Similar services
		if ($this->conf['relatedTopics']) {
			$smartyService->assign('related_topics',$similar);
		} else {
			$smartyService->assign('similar_services',$similar);
		}

		//Assign template labels
		$smartyService->assign('service_label',$this->pi_getLL('tx_civserv_pi1_service.service','Service'));
		$smartyService->assign('ext_service_label',$this->pi_getLL('tx_civserv_pi1_service.ext_service','This is an external service offered by'));
		$smartyService->assign('description_label',$this->pi_getLL('tx_civserv_pi1_service.description','Description'));
		$smartyService->assign('fees_label',$this->pi_getLL('tx_civserv_pi1_service.fees','Fees'));
		$smartyService->assign('documents_label',$this->pi_getLL('tx_civserv_pi1_service.documents','Necessary documents'));
		$smartyService->assign('forms_label',$this->pi_getLL('tx_civserv_pi1_service.forms','Forms'));
		$smartyService->assign('legal_label',$this->pi_getLL('tx_civserv_pi1_service.legal','Legal foundation'));
		$smartyService->assign('legal_local_label',$this->pi_getLL('tx_civserv_pi1_service.legal_local','Legal foundation (local)'));
		$smartyService->assign('legal_global_label',$this->pi_getLL('tx_civserv_pi1_service.legal_global','Legal foundation (general)'));
		$smartyService->assign('organisation_label',$this->pi_getLL('tx_civserv_pi1_service.organisation','Responsible organisational unit(s)'));
		$smartyService->assign('contact_label',$this->pi_getLL('tx_civserv_pi1_service.contact','Contact person'));
		$smartyService->assign('employee_details',$this->pi_getLL('tx_civserv_pi1_organisation.employee_details','Jumps to a page with details of this employee'));
		$smartyService->assign('similar_services_label',$this->pi_getLL('tx_civserv_pi1_service.similar_services','Similar services'));
		$smartyService->assign('phone_label',$this->pi_getLL('tx_civserv_pi1_organisation.phone','Phone'));
		$smartyService->assign('email_label',$this->pi_getLL('tx_civserv_pi1_organisation.email','E-Mail'));
		$smartyService->assign('web_email_label',$this->pi_getLL('tx_civserv_pi1_organisation.web_email','E-Mail-Form'));
		$smartyService->assign('subnavigation_label',$this->pi_getLL('tx_civserv_pi1_service.subnavigation','Sub-navigation'));
		$smartyService->assign('link_to_section',$this->pi_getLL('tx_civserv_pi1_service.link_to_section','Jump label to section'));
		$smartyService->assign('serviceinformation_label',$this->pi_getLL('tx_civserv_pi1_common.serviceinformation','Service information'));
		$smartyService->assign('pages_related_topics_label',$this->pi_getLL('tx_civserv_pi1_service.pages_related_topics','Pages with related topics'));
		$smartyService->assign('top',$this->pi_getLL('tx_civserv_pi1_service.top','Top of page'));
		$smartyService->assign('link_to_top',$this->pi_getLL('tx_civserv_pi1_service.link_to_top','Jump label to the beginning of this page'));

		if ($searchBox) {
			$_SERVER['REQUEST_URI'] = $this->pi_linkTP_keepPIvars_url(array(mode => 'search_result'),0,1);
			$smartyService->assign('searchbox', $this->pi_list_searchBox('',true));
		}

		if ($topList) {
			if (!$this->calculate_top15($smartyService,false,$this->conf['topCount'])) {
				return false;
			}
		}

		//Access log for most frequently requested services
		//Get logging interval from configuration table
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
						'cf_value',
						'tx_civserv_configuration',
						'cf_module = "accesslog" AND cf_key = "log_interval"',
						'',
						'',
						'');
		$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
		$log_interval = intval($row[cf_value]);//warum nicht $row['cf_value']
		$accesslog = t3lib_div::makeInstance('tx_civserv_accesslog');
		$accesslog->update_log($uid,$log_interval, $_SERVER['REMOTE_ADDR']);

		//Title for the Indexed Search Engine
		$GLOBALS['TSFE']->indexedDocTitle = $service_common[sv_name];
		$GLOBALS['TSFE']->page['title']=$this->pi_getLL('tx_civserv_pi1_service.service','Service').": ".$name;
		return true;
	}


	/**
	 * Generates a query for standard service details.
	 *
	 * @param	integer		Service uid
	 * @return	result_set		Result of database query
	 */
	function queryService($uid) {
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
						'uid, pid, sv_name, sv_descr_short, sv_descr_long, sv_image, sv_image_text, sv_fees, sv_documents, sv_legal_local, sv_legal_global, sv_region_checkbox, sv_region_link, sv_model_service',
						'tx_civserv_service',
						'!deleted AND !hidden AND ((UNIX_TIMESTAMP(LOCALTIMESTAMP) BETWEEN starttime AND endtime) OR
												  ((UNIX_TIMESTAMP(LOCALTIMESTAMP) > starttime) AND (endtime=0)) OR
												  (starttime=0 AND endtime=0) ) AND uid = ' . $uid . '',
						'',
						'',
						'');
		return $res;
	}


	/**
	 * Generates information about a specific employee.
	 *
	 * @param	object		Smarty object, the template key/value-pairs should be assigned to
	 * @param	boolean		If true, a searchbox is generated (keyword search)
	 * @return	boolean		True, if the function was executed without any error, otherwise false
	 */
	function employeeDetail(&$smartyEmployee,$searchBox) {
		$uid = $this->piVars[id];
		$pos_id = $this->piVars[pos_id];

		//Standard query for employee details
		$res_common = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
						'uid, em_address, em_title, em_name, em_firstname, em_telephone, em_fax, em_email, em_image, em_datasec',
						'tx_civserv_employee',
						'!deleted AND !hidden AND uid='.$uid.' AND em_datasec=1',
						'',
						'',
						'');

		//Check if data security option is enabled
		if ($GLOBALS['TYPO3_DB']->sql_num_rows($res_common) == 0) {
			$GLOBALS['error_message'] = $this->pi_getLL('tx_civserv_pi1_employee.datasec','Datasec enabled! Employee is not shown.');
			return false;
		}

		//Query for employee office hours
		$res_emp_hours = $GLOBALS['TYPO3_DB']->exec_SELECT_mm_query(
					'oh_start_morning, oh_end_morning, oh_start_afternoon, oh_end_afternoon, oh_freestyle, oh_weekday',
					'tx_civserv_employee',
					'tx_civserv_employee_em_hours_mm',
					'tx_civserv_officehours',
					'AND !tx_civserv_employee.deleted AND !tx_civserv_employee.hidden
					 AND !tx_civserv_officehours.deleted AND !tx_civserv_officehours.hidden
					 AND tx_civserv_employee.uid = ' . $uid,
					'',
					'oh_weekday',
					'');

		//Create additional queries if position uid is set in piVars
		if ($pos_id != '') {

			//Query for employee-position office hours
			$res_emp_pos_hours = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
					'oh_start_morning, oh_end_morning, oh_start_afternoon, oh_end_afternoon, oh_freestyle, oh_weekday',
					'tx_civserv_employee, tx_civserv_position, tx_civserv_officehours, tx_civserv_employee_em_position_mm, tx_civserv_officehours_oep_employee_em_position_mm_mm',
					'!tx_civserv_employee.deleted AND !tx_civserv_employee.hidden
					 AND !tx_civserv_position.deleted AND !tx_civserv_position.hidden
					 AND !tx_civserv_officehours.deleted AND !tx_civserv_officehours.hidden
					 AND tx_civserv_employee.uid = tx_civserv_employee_em_position_mm.uid_local
					 AND tx_civserv_position.uid = tx_civserv_employee_em_position_mm.uid_foreign
					 AND tx_civserv_employee_em_position_mm.uid = tx_civserv_officehours_oep_employee_em_position_mm_mm.uid_local
					 AND tx_civserv_officehours.uid = tx_civserv_officehours_oep_employee_em_position_mm_mm.uid_foreign
					 AND tx_civserv_employee.uid = ' . $uid . ' AND tx_civserv_position.uid = '.$pos_id,
					'',
					'oh_weekday',
					'');

			//Query for employee-organisation office hours
			$res_emp_org_hours = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
					'oh_start_morning, oh_end_morning, oh_start_afternoon, oh_end_afternoon, oh_freestyle, oh_weekday',
					'tx_civserv_employee, tx_civserv_organisation, tx_civserv_position, tx_civserv_officehours, tx_civserv_employee_em_position_mm, tx_civserv_position_po_organisation_mm, tx_civserv_organisation_or_hours_mm',
					'!tx_civserv_organisation.deleted AND !tx_civserv_organisation.hidden
					 AND !tx_civserv_officehours.deleted AND !tx_civserv_officehours.hidden
					 AND !tx_civserv_position.deleted AND !tx_civserv_organisation.hidden
					 AND !tx_civserv_employee.deleted AND !tx_civserv_officehours.hidden
					 AND tx_civserv_employee.uid = tx_civserv_employee_em_position_mm.uid_local
					 AND tx_civserv_position.uid = tx_civserv_employee_em_position_mm.uid_foreign
					 AND tx_civserv_position.uid = tx_civserv_position_po_organisation_mm.uid_local
					 AND tx_civserv_organisation.uid = tx_civserv_position_po_organisation_mm.uid_foreign
					 AND tx_civserv_organisation.uid = tx_civserv_organisation_or_hours_mm.uid_local
					 AND tx_civserv_officehours.uid = tx_civserv_organisation_or_hours_mm.uid_foreign
					 AND tx_civserv_employee.uid = ' . $uid . ' AND em_datasec = 1 AND tx_civserv_position.uid = ' . $pos_id,
					'',
					'oh_weekday',
					'');

			//Query for organisation, building, floor and room (depending on position of employee)
			$res_position = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
					'tx_civserv_position.uid as pos_uid, tx_civserv_organisation.uid as or_uid, tx_civserv_employee.uid as emp_uid, po_name as position, bl_name as building, fl_descr as floor, ro_name as room, ep_telephone as phone, ep_fax as fax, ep_email as email, or_name as organisation',
					'tx_civserv_employee, tx_civserv_position, tx_civserv_room, tx_civserv_floor, tx_civserv_organisation, tx_civserv_building, tx_civserv_employee_em_position_mm, tx_civserv_building_bl_floor_mm, tx_civserv_position_po_organisation_mm',
					'tx_civserv_employee.uid='.$uid.' AND em_datasec=1 AND tx_civserv_position.uid = '.$pos_id.'
					 AND !tx_civserv_organisation.deleted AND !tx_civserv_organisation.hidden
					 AND !tx_civserv_employee.deleted AND !tx_civserv_employee.hidden
					 AND !tx_civserv_position.deleted AND !tx_civserv_position.hidden
					 AND !tx_civserv_room.deleted AND !tx_civserv_room.hidden
					 AND !tx_civserv_floor.deleted AND !tx_civserv_floor.hidden
					 AND !tx_civserv_organisation.deleted AND !tx_civserv_organisation.hidden
					 AND tx_civserv_employee.uid = tx_civserv_employee_em_position_mm.uid_local
					 AND tx_civserv_employee_em_position_mm.uid_foreign = tx_civserv_position.uid
					 AND tx_civserv_employee_em_position_mm.ep_room = tx_civserv_room.uid
					 AND tx_civserv_building.uid = tx_civserv_building_bl_floor_mm.uid_local
					 AND tx_civserv_floor.uid = tx_civserv_building_bl_floor_mm.uid_foreign
					 AND tx_civserv_room.rbf_building_bl_floor = tx_civserv_building_bl_floor_mm.uid
					 AND tx_civserv_position.uid = tx_civserv_position_po_organisation_mm.uid_local
					 AND tx_civserv_organisation.uid = tx_civserv_position_po_organisation_mm.uid_foreign',
					'',
					'',
					'');

		//Assign employee position data
		$employee_position = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_position);
		$employee_position['or_link'] = htmlspecialchars($this->pi_linkTP_keepPIvars_url(array(mode => 'organisation', id => $employee_position['or_uid']),1,1));
		$smartyEmployee->assign('position',$employee_position);

		//Assign employee-position working hours
		$row_counter = 0;
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_emp_pos_hours) )
		{
			if($row[oh_weekday] == 10){ //monday to friday
				unset($emp_pos_hours);
			}
			$emp_pos_hours[$row_counter]['weekday'] = $this->pi_getLL('tx_civserv_pi1_weekday_'.$row[oh_weekday]);
			$emp_pos_hours[$row_counter]['start_morning'] = $row[oh_start_morning];
			$emp_pos_hours[$row_counter]['end_morning'] = $row[oh_end_morning];
			$emp_pos_hours[$row_counter]['start_afternoon'] = $row[oh_start_afternoon];
			$emp_pos_hours[$row_counter]['end_afternoon'] = $row[oh_end_afternoon];
			$emp_pos_hours[$row_counter]['freestyle'] = $row[oh_freestyle];
			$row_counter++;
		}
		$smartyEmployee->assign('emp_pos_hours',$emp_pos_hours);

		//Assign employee-organisation working hours
		$row_counter = 0;
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_emp_org_hours) )
		{
			if($row[oh_weekday] == 10){ //monday to friday
				unset($emp_org_hours);
			}
			$emp_org_hours[$row_counter]['weekday'] = $this->pi_getLL('tx_civserv_pi1_weekday_'.$row[oh_weekday]);
			$emp_org_hours[$row_counter]['start_morning'] = $row[oh_start_morning];
			$emp_org_hours[$row_counter]['end_morning'] = $row[oh_end_morning];
			$emp_org_hours[$row_counter]['start_afternoon'] = $row[oh_start_afternoon];
			$emp_org_hours[$row_counter]['end_afternoon'] = $row[oh_end_afternoon];
			$emp_org_hours[$row_counter]['freestyle'] = $row[oh_freestyle];
			$row_counter++;
		}
		$smartyEmployee->assign('emp_org_hours',$emp_org_hours);

		} //End if additional queries

		$employee_rows = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_common);

		// get Image code
		$imagepath = $this->conf['folder_organisations'] . $this->community[id] . '/images/';
		$description = $employee_rows[em_firstname] . ' ' . $employee_rows[em_name];
		$imageCode = $this->getImageCode($employee_rows[em_image],$imagepath,$this->conf['employee-image.'],$description);

		//Assign employee data
		$smartyEmployee->assign('title',$employee_rows[em_title]);
		if (intval($employee_rows[em_address]) == 2) {
			$smartyEmployee->assign('address',$this->pi_getLL('tx_civserv_pi1_organisation.address_female','Mrs.'));
		} else if (intval($employee_rows[em_address]) == 1) {
			$smartyEmployee->assign('address',$this->pi_getLL('tx_civserv_pi1_organisation.address_male','Mr.'));
		}
		$smartyEmployee->assign('firstname',$employee_rows[em_firstname]);
		$smartyEmployee->assign('name',$employee_rows[em_name]);
		$smartyEmployee->assign('phone',$employee_rows[em_telephone]);
		$smartyEmployee->assign('fax',$employee_rows[em_fax]);
		$smartyEmployee->assign('image',$imageCode);

		// Assign email data
		// use typolink, because of the possibility to use encrypted email-adresses for spam-protection
		if ($employee_position[email] != '') {
			$email_form_url = htmlspecialchars($this->pi_linkTP_keepPIvars_url(array(mode => 'set_email_form',id => $employee_position['emp_uid'],pos_id => $employee_position['pos_uid']),1,1));
			$email_code = $this->cObj->typoLink($employee_position['email'],array(parameter => $employee_position['email'],ATagParams => 'class="email"'));
		} elseif ($employee_rows[em_email] != '') {
			$email_form_url = htmlspecialchars($this->pi_linkTP_keepPIvars_url(array(mode => 'set_email_form',id => $employee_rows['uid']),1,1));
			$email_code = $this->cObj->typoLink($employee_rows[em_email],array(parameter => $employee_rows[em_email],ATagParams => 'class="email"'));
		}
		$smartyEmployee->assign('email_form_url',$email_form_url);
		$smartyEmployee->assign('email_code',$email_code);

		//Assign employee working hours
		$row_counter = 0;
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_emp_hours) )
		{
			if($row[oh_weekday] == 10){ //"monday to friday" only one item to be displayed instead of four or five
				unset($emp_hours);
			}
			$emp_hours[$row_counter]['weekday'] = $this->pi_getLL('tx_civserv_pi1_weekday_'.$row[oh_weekday]);
			$emp_hours[$row_counter]['start_morning'] = $row[oh_start_morning];
			$emp_hours[$row_counter]['end_morning'] = $row[oh_end_morning];
			$emp_hours[$row_counter]['start_afternoon'] = $row[oh_start_afternoon];
			$emp_hours[$row_counter]['end_afternoon'] = $row[oh_end_afternoon];
			$emp_hours[$row_counter]['freestyle'] = $row[oh_freestyle];
			$row_counter++;
		}
		$smartyEmployee->assign('emp_hours',$emp_hours);

		//Assign template labels
		$smartyEmployee->assign('employee_label',$this->pi_getLL('tx_civserv_pi1_employee.employee','Employee'));
		$smartyEmployee->assign('phone_label',$this->pi_getLL('tx_civserv_pi1_organisation.phone','Phone'));
		$smartyEmployee->assign('fax_label',$this->pi_getLL('tx_civserv_pi1_organisation.fax','Fax'));
		$smartyEmployee->assign('email_label',$this->pi_getLL('tx_civserv_pi1_organisation.email','E-Mail'));
		$smartyEmployee->assign('web_email_label',$this->pi_getLL('tx_civserv_pi1_organisation.web_email','E-Mail-Form'));
		$smartyEmployee->assign('working_hours_label',$this->pi_getLL('tx_civserv_pi1_employee.hours','Working hours'));
		$smartyEmployee->assign('office_hours_summary',str_replace('###EMPLOYEE###',$employee_rows[em_firstname] . ' ' . $employee_rows[em_name],$this->pi_getLL('tx_civserv_pi1_employee.officehours','In the table are the office hours of ###EMPLOYEE### shown.')));
		$smartyEmployee->assign('weekday',$this->pi_getLL('tx_civserv_pi1_weekday','Weekday'));
		$smartyEmployee->assign('morning',$this->pi_getLL('tx_civserv_pi1_organisation.morning','mornings'));
		$smartyEmployee->assign('afternoon',$this->pi_getLL('tx_civserv_pi1_organisation.afternoon','in the afternoon'));
		$smartyEmployee->assign('organisation_label',$this->pi_getLL('tx_civserv_pi1_employee.organisation','Organisation'));
		$smartyEmployee->assign('room_label',$this->pi_getLL('tx_civserv_pi1_employee.room','Room'));
		$smartyEmployee->assign('image_employee_label',$this->pi_getLL('tx_civserv_pi1_employee.image','Image of employee'));

		if ($searchBox) {
			$_SERVER['REQUEST_URI'] = $this->pi_linkTP_keepPIvars_url(array(mode => 'search_result'),0,1);
			$smartyTop15->assign('searchbox', $this->pi_list_searchBox('',true));
		}
		$GLOBALS['TSFE']->page['title']=$this->pi_getLL('tx_civserv_pi1_employee.employee','Employee');
		return true;
	}


	/**
	 * Generates information about a specific organisation.
	 *
	 * @param	object		Smarty object, the template key/value-pairs should be assigned to
	 * @return	boolean		True, if the function was executed without any error, otherwise false
	 */
	function organisationDetail(&$smartyOrganisation) {
		$uid = $this->piVars[id];

		//Standard query for organisation details
		$res_common = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
						'uid, or_name,or_telephone,or_fax,or_email,or_image,or_infopage,or_addinfo',
						'tx_civserv_organisation',
						'!deleted AND !hidden AND uid='.$uid,
						'',
						'',
						'');

		//Query for supervisor of organisation
		$res_supervisor = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
						'tx_civserv_employee.uid as uid, em_title, em_name, em_firstname, em_address, em_datasec',
						'tx_civserv_organisation, tx_civserv_employee',
						'!tx_civserv_organisation.deleted AND !tx_civserv_organisation.hidden
						 AND !tx_civserv_employee.deleted AND !tx_civserv_employee.hidden
						 AND tx_civserv_organisation.or_supervisor = tx_civserv_employee.uid
						 AND tx_civserv_organisation.uid='.$uid,
						'',
						'',
						'');

		//Query for supervisor of organisation (depending on position)
		$res_pos_supervisor = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
						'tx_civserv_employee.uid as uid, tx_civserv_position.uid as pos_uid, em_title, em_name, em_firstname, em_address, em_datasec',
						'tx_civserv_organisation, tx_civserv_employee, tx_civserv_position, tx_civserv_employee_em_position_mm, tx_civserv_position_po_organisation_mm',
						'!tx_civserv_organisation.deleted AND !tx_civserv_organisation.hidden
						 AND !tx_civserv_employee.deleted AND !tx_civserv_employee.hidden
						 AND !tx_civserv_position.deleted AND !tx_civserv_position.hidden
						 AND tx_civserv_organisation.or_supervisor = tx_civserv_employee.uid
						 AND tx_civserv_employee.uid = tx_civserv_employee_em_position_mm.uid_local
						 AND tx_civserv_position.uid = tx_civserv_employee_em_position_mm.uid_foreign
						 AND tx_civserv_position.uid = tx_civserv_position_po_organisation_mm.uid_local
						 AND tx_civserv_organisation.uid = tx_civserv_position_po_organisation_mm.uid_foreign
						 AND tx_civserv_organisation.uid='.$uid,
						'',
						'uid',	//GROUP BY
						'');

		//Query for building and postal address
		$res_building = $GLOBALS['TYPO3_DB']->exec_SELECT_mm_query(
						'bl_mail_street, bl_mail_pob, bl_mail_postcode, bl_mail_city, bl_building_street, bl_building_postcode, bl_building_city, bl_pubtrans_stop, bl_pubtrans_url',
						'tx_civserv_organisation',
						'tx_civserv_organisation_or_building_mm',
						'tx_civserv_building',
						'AND !tx_civserv_organisation.deleted AND !tx_civserv_organisation.hidden
						 AND !tx_civserv_building.deleted AND !tx_civserv_building.hidden
						 AND tx_civserv_organisation.uid = ' . $uid,
						'',
						'',
						'');

		//Query for office hours
		$res_hour = $GLOBALS['TYPO3_DB']->exec_SELECT_mm_query(
						'oh_start_morning, oh_end_morning, oh_start_afternoon, oh_end_afternoon, oh_freestyle, oh_weekday',
						'tx_civserv_organisation',
						'tx_civserv_organisation_or_hours_mm',
						'tx_civserv_officehours',
						'AND !tx_civserv_organisation.deleted AND !tx_civserv_organisation.hidden
						 AND !tx_civserv_officehours.deleted AND !tx_civserv_officehours.hidden
						 AND tx_civserv_organisation.uid = ' . $uid,
						'',
						'oh_weekday',
						'');

		$organisation_rows = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_common);
		$organisation_building = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_building);

		$pidListAll = $this->community['pidlist'];				//Get pidlist for current mandant
		$pidListAll = t3lib_div::intExplode(',',$pidListAll);	//Parse pidlist and store int values in array

		$smartyOrganisation->assign('infopage',$this->cObj->typoLink_URL(array(parameter => $organisation_rows['or_infopage'])));

		if ($GLOBALS['TYPO3_DB']->sql_num_rows($res_pos_supervisor) != 0) {
			$organisation_supervisor = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_pos_supervisor);
			$pos_id = $organisation_supervisor[pos_uid];
		} else {
			$organisation_supervisor = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_supervisor);
			$pos_id = '';
		}

		//Assign organisation office hours
		$row_counter = 0;
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_hour) )
		{
			if($row[oh_weekday] == 10){ //monday to friday
				unset($organisation_hours);
			}
			$organisation_hours[$row_counter]['weekday'] = $this->pi_getLL('tx_civserv_pi1_weekday_'.$row[oh_weekday]);
			$organisation_hours[$row_counter]['start_morning'] = $row[oh_start_morning];
			$organisation_hours[$row_counter]['end_morning'] = $row[oh_end_morning];
			$organisation_hours[$row_counter]['start_afternoon'] = $row[oh_start_afternoon];
			$organisation_hours[$row_counter]['end_afternoon'] = $row[oh_end_afternoon];
			$organisation_hours[$row_counter]['freestyle'] = $row[oh_freestyle];
			$row_counter++;
		}
		$smartyOrganisation->assign('office_hours',$organisation_hours);

		// get Image code
		$imagepath = $this->conf['folder_organisations'] . $this->community[id] . '/images/';
		$imageCode = $this->getImageCode($organisation_rows[or_image],$imagepath,$this->conf['organisation-image.'],$this->pi_getLL('tx_civserv_pi1_organisation.image','Image of organisation'));

		//Assign standard data
		$smartyOrganisation->assign('or_name',$organisation_rows[or_name]);
		$smartyOrganisation->assign('phone',$organisation_rows[or_telephone]);
		$smartyOrganisation->assign('fax',$organisation_rows[or_fax]);
		$smartyOrganisation->assign('email_code',$this->cObj->typoLink($organisation_rows[or_email],array(parameter => $organisation_rows[or_email],ATagParams => 'class="email"'))); 	// use typolink, because of the possibility to use encrypted email-adresses for spam-protection
		$smartyOrganisation->assign('email_form_url',htmlspecialchars($this->pi_linkTP_keepPIvars_url(array(mode => 'set_email_form',org_id => $organisation_rows[uid]),1,1)));
		$smartyOrganisation->assign('image',$imageCode);

		//Assign employee data
		$smartyOrganisation->assign('su_title',$organisation_supervisor[em_title]);
		$smartyOrganisation->assign('su_firstname',$organisation_supervisor[em_firstname]);
		$smartyOrganisation->assign('su_name',$organisation_supervisor[em_name]);
		if (intval($organisation_supervisor[em_datasec]) == 1) {
			if ($pos_id != '') {
				$smartyOrganisation->assign('su_link',htmlspecialchars($this->pi_linkTP_keepPIvars_url(array(mode => 'employee',id => $organisation_supervisor[uid],pos_id => $pos_id),1,1)));
			} else {
				$smartyOrganisation->assign('su_link',htmlspecialchars($this->pi_linkTP_keepPIvars_url(array(mode => 'employee',id => $organisation_supervisor[uid]),1,1)));
			}
		}

		//Assign addresses
		$smartyOrganisation->assign('building_street',$organisation_building[bl_building_street]);
		$smartyOrganisation->assign('building_postcode',$organisation_building[bl_building_postcode]);
		$smartyOrganisation->assign('building_city',$organisation_building[bl_building_city]);
		$smartyOrganisation->assign('mail_street',$organisation_building[bl_mail_street]);
		$smartyOrganisation->assign('mail_pob',$organisation_building[bl_mail_pob]);
		$smartyOrganisation->assign('mail_postcode',$organisation_building[bl_mail_postcode]);
		$smartyOrganisation->assign('mail_city',$organisation_building[bl_mail_city]);

		//Assign public transport information
		$smartyOrganisation->assign('pubtrans_stop',$organisation_building[bl_pubtrans_stop]);
		$smartyOrganisation->assign('pubtrans_link',$this->cObj->typoLink_URL(array(parameter => $organisation_building[bl_pubtrans_url])));

		//Assign template labels
		$smartyOrganisation->assign('organisation_label',$this->pi_getLL('tx_civserv_pi1_organisation.organisation','Organisation'));
		$smartyOrganisation->assign('postal_address_label',$this->pi_getLL('tx_civserv_pi1_organisation.postal_address','Postal address'));
		$smartyOrganisation->assign('building_address_label',$this->pi_getLL('tx_civserv_pi1_organisation.building_address','Building address'));
		$smartyOrganisation->assign('phone_label',$this->pi_getLL('tx_civserv_pi1_organisation.phone','Phone'));
		$smartyOrganisation->assign('fax_label',$this->pi_getLL('tx_civserv_pi1_organisation.fax','Fax'));
		$smartyOrganisation->assign('email_label',$this->pi_getLL('tx_civserv_pi1_organisation.email','E-Mail'));
		$smartyOrganisation->assign('web_email_label',$this->pi_getLL('tx_civserv_pi1_organisation.web_email','E-Mail-Form'));
		$smartyOrganisation->assign('office_hours_label',$this->pi_getLL('tx_civserv_pi1_organisation.office_hours','Office hours'));
		$smartyOrganisation->assign('supervisor_label',$this->pi_getLL('tx_civserv_pi1_organisation.supervisor','Supervisor'));
		$smartyOrganisation->assign('employee_details',$this->pi_getLL('tx_civserv_pi1_organisation.employee_details','Jumps to a page with details of this employee'));
		$smartyOrganisation->assign('office_hours_summary',str_replace('###ORGANISATION###',$organisation_rows[or_name],$this->pi_getLL('tx_civserv_pi1_organisation.officehours','In the table are the office hours of ###ORGANISATION### shown.')));
		$smartyOrganisation->assign('weekday',$this->pi_getLL('tx_civserv_pi1_weekday','Weekday'));
		$smartyOrganisation->assign('morning',$this->pi_getLL('tx_civserv_pi1_organisation.morning','in the morning'));
		$smartyOrganisation->assign('afternoon',$this->pi_getLL('tx_civserv_pi1_organisation.afternoon','in the afternoon'));

		if (intval($organisation_supervisor[em_address]) == 2) {
			$smartyOrganisation->assign('su_address_label',$this->pi_getLL('tx_civserv_pi1_organisation.address_female','Mrs.'));
		} else if (intval($organisation_supervisor[em_address]) == 1) {
			$smartyOrganisation->assign('su_address_label',$this->pi_getLL('tx_civserv_pi1_organisation.address_male','Mr.'));
		}
		$smartyOrganisation->assign('postbox_label',$this->pi_getLL('tx_civserv_pi1_organisation.postbox','Postbox'));
		$smartyOrganisation->assign('pub_trans_info_label',$this->pi_getLL('tx_civserv_pi1_organisation.pub_trans_info','Public transport information'));
		$smartyOrganisation->assign('pub_trans_stop_label',$this->pi_getLL('tx_civserv_pi1_organisation.pub_trans_stop','Stop'));
		$smartyOrganisation->assign('available_services_label',$this->pi_getLL('tx_civserv_pi1_organisation.available_services','Here you find the following services'));
		$smartyOrganisation->assign('infopage_label',$this->pi_getLL('tx_civserv_pi1_organisation.infopage','Info Page'));

		return true;
	}









	/******************************
	 *
	 * Functions for choosing and changeing the community :
	 *
	 *******************************/


	/**
	 * Shows a list of available communities to choose.
	 * changes made by bkohorst:
	 * --> parent::pi_linkTP_keepPIvars_url(...) is fed with the pageID of the community in question, so as to be able to switch style sheet.
	 *     pageID must be the 4th argument!! i.e. 3 arguments won't work
	 *
	 * @param	object		Smarty object, the template key/value-pairs should be assigned to
	 * @return	boolean		True, if the function was executed without any error, otherwise false
	 */
	function chooseCommunity(&$smartyCommunity) {
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
						'*',
						'tx_civserv_conf_mandant',
						'NOT deleted AND NOT hidden',
						'',
						'cm_community_name');

		$row_counter = 0;
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res))	{
			$community_data[$row_counter]['name'] = $row['cm_community_name'];
			$community_data[$row_counter]['link'] = htmlspecialchars(parent::pi_linkTP_keepPIvars_url(array(community_id => $row['cm_community_id']),0,1,$row[cm_page_uid]));
			$row_counter++;
		}
		if ($row_counter == 0) {
			$GLOBALS['error_message'] = $this->pi_getLL('tx_civserv_pi1_error.no_community','No community found. The system seems to be missconfigured or not configured yet.');
			return false;
		}
		$smartyCommunity->assign('community_choice_label',$this->pi_getLL('tx_civserv_pi1_community_search_label','You have not choosen a community yet. Please choose your community.'));
		$smartyCommunity->assign('communities',$community_data);
		return true;
	}


	/**
	 * Returns the HTML-Code for displaying a notice with the active community and a link to choose another community,
	 * if the constant "community_choice" in the template is set to 1. Otherwise nothing is returned.
	 * This function is not used at this time.
	 *
	 * @param	string		content
	 * @param	array		configuration array
	 * @return	string		HTML-Code with the notice and the link
	 */
	function linkCommunityChoice($content,$conf) {
		if ($this->conf['community_choice']) {
			$notice = str_replace('###COMMUNITY_NAME###','<span class="community_name">' . $this->community['name'] . '</span>',$this->pi_getLL('tx_civserv_pi1_community_choice.notice','The following information is related to ###COMMUNITY_NAME###.'));
			$link_text = $this->pi_getLL('tx_civserv_pi1_community_choice.link_text','Click here, to choose another community.');
			$link = $this->pi_linkTP_keepPIvars($link_text,array(community_id => 'choose',mode => 'service_list'),1,1);
			return $notice . ' ' . $link;
		}
	}


	/**
	 * Returns the community name of the sctive community.
	 * Normaly used from a template userfunction.
	 *
	 * @param	sting		content
	 * @param	array		configuration array
	 * @return	string		The community name
	 */
	function getCommunityName($content,$conf) {
		if (trim($this->piVars[community_id]) <= '') {
			$community_id = $conf['community_id'];
		} else {
			$community_id = $this->piVars[community_id];
		}
		if ($_SESSION[community_name] > '' ) {
			$content = $_SESSION[community_name];
		} elseif ($community_id > ''  && $community_id != 'choose') {
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
						'cm_community_name',
						'tx_civserv_conf_mandant',
						'NOT deleted AND NOT hidden
						 AND cm_community_id = '.$community_id);
			if ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
				$content = $row['cm_community_name'];
			}
		}else {
			$content = '';
		}
		return $content;
	}

	/**
	 * Returns link to choice the community.
	 * Normaly used from a template userfunction.
	 * changes made by bkohorst --> switch the page-id from the search-page of a mandant to the frontend-page to make sure the choicelink works under all circumstances
	 * The setup in the static template which calls this function looks like:
	 * marks.CHOICE_LINK = USER
	 * marks.CHOICE_LINK.community_id = {$community_id}
	 * marks.CHOICE_LINK.pageid = {$pageid}
	 * marks.CHOICE_LINK.fulltext_search_id ={$fulltext_search_id}
	 * marks.CHOICE_LINK.userFunc = tx_civserv_pi1->getChoiceLink
	 * with the above scenario the id-switching would be as follows:
	 * 		if($pageid == $conf['fulltext_search_id']){
	 * 			$pageid = $conf['pageid'];
	 * 		}
	 * --> this depends on the values being set correctly in the mandant's typoscript-template
	 * --> instead the values are retrieved from the database directly (which depends on the table tx_civserv_conf_mandant to be maintained correctly)
	 *
	 * @param	string		content
	 * @param	array		configuration array
	 * @return	string		The link
	 */
	function getChoiceLink($content, $conf) {
		if ($conf['pageid'] > '') {
			$pageid = $conf['pageid']; //not available :-(
		} else {
			$pageid = $GLOBALS['TSFE']->id;
		}

		//retrieve uid of frontend-page and of fulltext-search-page from db:
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
								'cm_page_uid, cm_search_uid',
								'tx_civserv_conf_mandant',
								'NOT deleted AND NOT hidden');
		while($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)){
			//feed the link with the id of the frontend-page (and never with the id of the search-page)!!
			if ($pageid == $row['cm_search_uid']){
				$pageid = $row['cm_page_uid'];
			}
		}
		return parent::pi_linkTP_keepPIvars_url(array(community_id => 'choose',mode => 'service_list'),1,1,$pageid);
	}






	/******************************
	 *
	 * Functions for the email form:
	 *
	 *******************************/


	/**
	 * Sets up an empty email form.
	 *
	 * @param	object		Smarty object, the template key/value-pairs should be assigned to
	 * @return	boolean		True, if the function was executed without any error, otherwise false
	 */
	function setEmailForm(&$smartyEmailForm) {
		//Check if there is a valid email address in the database for the given combination of employee, service, position and organisation id
		if ($this->getEhoster_email($smartyEmailForm) || $this->piVars[mode]=='set_contact_form') {
			if($this->getEhoster_email($smartyEmailForm)){
				//Assign action url of email form with mode 'check_email_form'
				$smartyEmailForm->assign('action_url',htmlspecialchars($this->pi_linkTP_keepPIvars_url(array(mode => 'check_email_form'),0,0)));
			}else{
				//Assign action url of email form with mode 'check_contact_form'
				$smartyEmailForm->assign('action_url',htmlspecialchars($this->pi_linkTP_keepPIvars_url(array(mode => 'check_contact_form'),0,0)));
			}

			//Assign template labels
			$hoster_email=$this->get_hoster_email();
			$smartyEmailForm->assign('email_form_label',$this->pi_getLL('tx_civserv_pi1_email_form.email_form','E-Mail Form'));
			$smartyEmailForm->assign('contact_form_label',str_replace('###HOSTER###', $hoster_email, $this->pi_getLL('tx_civserv_pi1_email_form.contact_form','Contact '.$hoster_email)));
			$smartyEmailForm->assign('notice_label',$this->pi_getLL('tx_civserv_pi1_email_form.notice','Please enter your postal address oder email address, so that we can send you an answer'));
			$smartyEmailForm->assign('title_label', $this->pi_getLL('tx_civserv_pi1_email_form.title','Title'));
			$smartyEmailForm->assign('chose_option', $this->pi_getLL('tx_civserv_pi1_email_form.chose','Please chose'));
			$smartyEmailForm->assign('female_option', $this->pi_getLL('tx_civserv_pi1_email_form.female','Ms.'));
			$smartyEmailForm->assign('male_option', $this->pi_getLL('tx_civserv_pi1_email_form.male','Mr.'));
			$smartyEmailForm->assign('firstname_label',$this->pi_getLL('tx_civserv_pi1_email_form.firstname','Firstname'));
			$smartyEmailForm->assign('surname_label',$this->pi_getLL('tx_civserv_pi1_email_form.surname','Surname'));
			$smartyEmailForm->assign('street_label',$this->pi_getLL('tx_civserv_pi1_email_form.street','Street, Nr.'));
			$smartyEmailForm->assign('postcode_label',$this->pi_getLL('tx_civserv_pi1_email_form.postcode','Postcode'));
			$smartyEmailForm->assign('city_label',$this->pi_getLL('tx_civserv_pi1_email_form.city','City'));
			$smartyEmailForm->assign('email_label',$this->pi_getLL('tx_civserv_pi1_email_form.email','E-Mail'));
			$smartyEmailForm->assign('phone_label',$this->pi_getLL('tx_civserv_pi1_email_form.phone','Phone'));
			$smartyEmailForm->assign('fax_label',$this->pi_getLL('tx_civserv_pi1_email_form.fax','Fax'));
			$smartyEmailForm->assign('subject_label',$this->pi_getLL('tx_civserv_pi1_email_form.subject','Subject'));
			$smartyEmailForm->assign('bodytext_label',$this->pi_getLL('tx_civserv_pi1_email_form.bodytext','Your text'));
			$smartyEmailForm->assign('submit_label',$this->pi_getLL('tx_civserv_pi1_email_form.submit','Send e-mail'));
			$smartyEmailForm->assign('reset_label',$this->pi_getLL('tx_civserv_pi1_email_form.reset','Reset'));
			$smartyEmailForm->assign('required_label',$this->pi_getLL('tx_civserv_pi1_email_form.required','required'));

			//Set reset button type to reset functionality
			$smartyEmailForm->assign('button_type','reset');

			return true;
		} else {
			return false;
		}
	}

	/**
	 * Checks the submitted email form and sends it via typo3 mail-function, if it is complete.
	 *
	 * @param	object		Smarty object, the template key/value-pairs should be assigned to
	 * @return	boolean		True, if the function was executed without any error, otherwise false
	 */
	function checkEmailForm(&$smartyEmailForm) {
		//Retrieve submitted form fields
		$title =  t3lib_div::_POST('title');
		$firstname = t3lib_div::_POST('firstname');
		$surname = t3lib_div::_POST('surname');
		$phone = t3lib_div::_POST('phone');
		$fax = t3lib_div::_POST('fax');
		$email = t3lib_div::_POST('email');
		$street = t3lib_div::_POST('street');
		$postcode = t3lib_div::_POST('postcode');
		$city = t3lib_div::_POST('city');
		$subject = t3lib_div::_POST('subject');
		$bodytext = t3lib_div::_POST('bodytext');

		// Bool variable that indicates whether filled email form is valid or not
		$is_valid = true;

		// Get Email-Adress or otherwise false
		$email_address = $this->getEhoster_email($smartyEmailForm);

		// Check if there is a valid email address in the database for the given combination of employee, service, position and organisation id
		if ($email_address) {

			// Check submitted form fields
			if (empty($surname)) {
				$smartyEmailForm->assign('error_surname',$this->pi_getLL('tx_civserv_pi1_email_form.error_surname','Please enter your surname!'));
				$is_valid = false;
			}

			if (empty($firstname)) {
				$smartyEmailForm->assign('error_firstname',$this->pi_getLL('tx_civserv_pi1_email_form.error_firstname','Please enter your firstname!'));
				$is_valid = false;
			}

			if (!empty($postcode) && !is_numeric($postcode)) {
				$smartyEmailForm->assign('error_postcode',$this->pi_getLL('tx_civserv_pi1_email_form.error_postcode','Please enter a valid postcode!'));
				$is_valid = false;
			}

			if (!empty($email) && !t3lib_div::validEmail($email)) {
				$smartyEmailForm->assign('error_email',$this->pi_getLL('tx_civserv_pi1_debit_form.error_email','Please enter a valid email address!'));
				$is_valid = false;
			}

			if (empty($subject)) {
				$smartyEmailForm->assign('error_subject',$this->pi_getLL('tx_civserv_pi1_email_form.error_subject','Please enter a subject!'));
				$is_valid = false;
			}

			if (empty($bodytext)) {
				$smartyEmailForm->assign('error_bodytext',$this->pi_getLL('tx_civserv_pi1_email_form.error_bodytext','Please enter your text!'));
				$is_valid = false;
			}

			if ($is_valid) {

				// Format body of email message
				$body = $this->pi_getLL('tx_civserv_pi1_email_form.title','Title') . ': ' . $title.
				   "\n" . $this->pi_getLL('tx_civserv_pi1_email_form.firstname','Firstname') . ': ' . $firstname.
				   "\n" . $this->pi_getLL('tx_civserv_pi1_email_form.surname','Surname') . ': ' . $surname.
				   "\n" . $this->pi_getLL('tx_civserv_pi1_email_form.phone','Phone') . ': ' . $phone.
				   "\n" . $this->pi_getLL('tx_civserv_pi1_email_form.fax','Fax') . ': ' . $fax.
				   "\n" . $this->pi_getLL('tx_civserv_pi1_email_form.email','E-Mail') . ': ' . $email.
				   "\n" . $this->pi_getLL('tx_civserv_pi1_email_form.street','Street, Nr.') . ': ' .$street.
				   "\n" . $this->pi_getLL('tx_civserv_pi1_email_form.postcode','Postcode') . ': ' . $postcode.
				   "\n" . $this->pi_getLL('tx_civserv_pi1_email_form.city','City') . ': ' . $city.
				   "\n" .
				   "\n" . $bodytext;

				t3lib_div::plainMailEncoded($email_address, $subject, $body);
				$smartyEmailForm->assign('complete',$this->pi_getLL('tx_civserv_pi1_email_form.complete','Thank you! Your message has been sent successfully.'));
				return true;
			} else { //Return email form template with error markers
				if($this->piVars[mode]=="check_contact_form"){
					// Assign action url of email form
					$smartyEmailForm->assign('action_url',htmlspecialchars($this->pi_linkTP_keepPIvars_url(array(mode => 'check_contact_form'),0,0)));
				} else {
					// Assign action url of email form
					$smartyEmailForm->assign('action_url',htmlspecialchars($this->pi_linkTP_keepPIvars_url(array(mode => 'check_email_form'),0,0)));
				}

				// Set form fields to previously entered values
				$smartyEmailForm->assign('firstname',$firstname);
				$smartyEmailForm->assign('surname',$surname);
				$smartyEmailForm->assign('phone',$phone);
				$smartyEmailForm->assign('fax',$fax);
				$smartyEmailForm->assign('email',$email);
				$smartyEmailForm->assign('street',$street);
				$smartyEmailForm->assign('postcode',$postcode);
				$smartyEmailForm->assign('city',$city);
				$smartyEmailForm->assign('subject',$subject);
				$smartyEmailForm->assign('bodytext',$bodytext);

				// Assign template labels
				$hoster_email=$this->get_hoster_email();
				$smartyEmailForm->assign('email_form_label',$this->pi_getLL('tx_civserv_pi1_email_form.email_form','E-Mail Form'));
				$smartyEmailForm->assign('contact_form_label',str_replace('###HOSTER###', $hoster_email, $this->pi_getLL('tx_civserv_pi1_email_form.contact_form','Contact '.$hoster_email)));
				$smartyEmailForm->assign('notice_label',$this->pi_getLL('tx_civserv_pi1_email_form.notice','Please enter your postal address oder email address, so that we can send you an answer'));
				$smartyEmailForm->assign('title_label', $this->pi_getLL('tx_civserv_pi1_email_form.title','Title'));
				$smartyEmailForm->assign('chose_option', $this->pi_getLL('tx_civserv_pi1_email_form.chose','Please chose'));
				$smartyEmailForm->assign('female_option', $this->pi_getLL('tx_civserv_pi1_email_form.female','Ms.'));
				$smartyEmailForm->assign('male_option', $this->pi_getLL('tx_civserv_pi1_email_form.male','Mr.'));
				$smartyEmailForm->assign('firstname_label',$this->pi_getLL('tx_civserv_pi1_email_form.firstname','Firstname'));
				$smartyEmailForm->assign('surname_label',$this->pi_getLL('tx_civserv_pi1_email_form.surname','Surname'));
				$smartyEmailForm->assign('street_label',$this->pi_getLL('tx_civserv_pi1_email_form.street','Street, Nr.'));
				$smartyEmailForm->assign('postcode_label',$this->pi_getLL('tx_civserv_pi1_email_form.postcode','Postcode'));
				$smartyEmailForm->assign('city_label',$this->pi_getLL('tx_civserv_pi1_email_form.city','City'));
				$smartyEmailForm->assign('email_label',$this->pi_getLL('tx_civserv_pi1_email_form.email','E-Mail'));
				$smartyEmailForm->assign('phone_label',$this->pi_getLL('tx_civserv_pi1_email_form.phone','Phone'));
				$smartyEmailForm->assign('fax_label',$this->pi_getLL('tx_civserv_pi1_email_form.fax','Fax'));
				$smartyEmailForm->assign('subject_label',$this->pi_getLL('tx_civserv_pi1_email_form.subject','Subject'));
				$smartyEmailForm->assign('bodytext_label',$this->pi_getLL('tx_civserv_pi1_email_form.bodytext','Your text'));
				$smartyEmailForm->assign('submit_label',$this->pi_getLL('tx_civserv_pi1_email_form.submit','Send e-mail'));
				$smartyEmailForm->assign('reset_label',$this->pi_getLL('tx_civserv_pi1_email_form.reset','Reset'));
				$smartyEmailForm->assign('required_label',$this->pi_getLL('tx_civserv_pi1_email_form.required','required'));

				// Set reset button type to submit functionality (necessary for resetting email form in 'check_email_form'-mode)
				$smartyEmailForm->assign('button_type','submit');

				return true;
			} // End return email form template with error markers
		} else {
			return false;
		}
	}


	/**
	 * Checks if there is a valid email address in the database for the given combination of employee, service, position and organisation id.
	 *
	 * @return	string		The Email-Adress, if found. Otherwise false (and a error-message is assigned to the smartyObject).
	 */
	function getEhoster_email() {
		//Retrieve submitted id parameters
		$org_id = intval($this->piVars[org_id]);
		$emp_id = intval($this->piVars[id]);
		$pos_id = intval($this->piVars[pos_id]);
		$sv_id = intval($this->piVars[sv_id]);

		if (!empty($org_id)) {	//Email form is called from organisation detail page (organisation email)
			//Standard query for organisation details
			$res_organisation = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
					'or_name, or_email',
					'tx_civserv_organisation',
					'!deleted AND !hidden AND uid = ' . $this->piVars[org_id]);

			//Check if query returned a result
			if ($GLOBALS['TYPO3_DB']->sql_num_rows($res_organisation) == 1) {
				$organisation = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_organisation);
				$email_address = $organisation[or_email];
				return $email_address;
			} else {
				$GLOBALS['error_message'] = $this->pi_getLL('tx_civserv_pi1_email_form.error_org_id','Wrong organisation id or organisation does not exist!');
				return false;
			}

		} elseif (!empty($emp_id) && !empty($pos_id) && !empty($sv_id)) {	//Email form is called from service detail page
			$result = $this->makeEmailQuery($emp_id,$pos_id,$sv_id);

			//Check if query returned a result
			if ($GLOBALS['TYPO3_DB']->sql_num_rows($result) == 1) {
				$employee = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($result);
				//Set correct email address (priority is employee-position email address)
				empty($employee[ep_email]) ? $email_address = $employee[em_email] : $email_address = $employee[ep_email];
				return $email_address;
			} else {
				$GLOBALS['error_message'] = $this->pi_getLL('tx_civserv_pi1_email_form.error_sv_id','Wrong service id, employee id oder position id. No email address found!');
				return false;
			}
		} elseif (!empty($emp_id) || (!empty($pos_id) && !empty($emp_id)) ) {  //Email form is called from organisation detail page (supervisor email)
			$result = $this->makeEmailQuery($emp_id,$pos_id,$sv_id);

			//Check if query returned a result
			if ($GLOBALS['TYPO3_DB']->sql_num_rows($result) == 1) {
				$employee = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($result);
				empty($employee[ep_email]) ? $email_address = $employee[em_email] : $email_address = $employee[ep_email];
				return $email_address;
			} else {
				$GLOBALS['error_message'] = $this->pi_getLL('tx_civserv_pi1_email_form.error_pos_id','Wrong employee id oder position id. No email address found!');
				return false;
			}
		} elseif ($this->piVars[mode]=='check_contact_form') {	//Email form ist called by the contact_link in the main Navigation
			//todo: add database field for hoster from which the address below should be retrieved
			$hoster_email =$this->get_hoster_email();
			return $hoster_email;
		} else {
			$GLOBALS['error_message'] = $this->pi_getLL('tx_civserv_pi1_email_form.error_general','Organisation id, employee id, position id and service id wrong or not set. No email address found!');
			return false;
		}
	}


	/**
	 * Generates a query to retrieve the email address of an employee.
	 *
	 * @param	integer		Employee uid
	 * @param	integer		Position uid
	 * @param	integer		Service uid
	 * @return	result_set		Result of database query
	 */
	function makeEmailQuery($emp_id,$pos_id,$sv_id) {
		$querypart_select = '';
		$querypart_from = '';
		$querypart_where = '';

		if (!empty($emp_id) && !empty($pos_id) && !empty($sv_id)) {	//Email form is called from service detail page
			$querypart_select = ', ep_email';
			$querypart_from = ', tx_civserv_service, tx_civserv_service_sv_position_mm, tx_civserv_position, tx_civserv_employee_em_position_mm';
			$querypart_where = 'AND tx_civserv_service.uid = ' . $sv_id . ' AND tx_civserv_employee.uid = ' . $emp_id . ' AND tx_civserv_position.uid = ' . $pos_id . '
								AND !tx_civserv_service.deleted AND !tx_civserv_service.hidden
								AND !tx_civserv_position.deleted AND !tx_civserv_position.hidden
								AND !tx_civserv_employee_em_position_mm.deleted AND !tx_civserv_employee_em_position_mm.hidden
								AND ((UNIX_TIMESTAMP(LOCALTIMESTAMP) BETWEEN tx_civserv_service.starttime AND tx_civserv_service.endtime)
						 	 		OR ((UNIX_TIMESTAMP(LOCALTIMESTAMP) > tx_civserv_service.starttime) AND (tx_civserv_service.endtime=0))
						 	  		OR (tx_civserv_service.starttime=0 AND tx_civserv_service.endtime=0) )
						 	  	AND tx_civserv_service.uid = tx_civserv_service_sv_position_mm.uid_local
								AND tx_civserv_service_sv_position_mm.uid_foreign = tx_civserv_position.uid
								AND tx_civserv_employee.uid = tx_civserv_employee_em_position_mm.uid_local
						 		AND tx_civserv_employee_em_position_mm.uid_foreign = tx_civserv_position.uid';
		}

		if (!empty($emp_id) && empty($pos_id) && empty($sv_id)) {  //Email form is called from organisation detail page (supervisor email)
			$querypart_where = 'AND tx_civserv_employee.uid = ' . $emp_id;
		}

		if ((!empty($pos_id) && !empty($emp_id)) && empty($sv_id)) {  //Email form is called from organisation detail page (supervisor email)
			$querypart_select = ', ep_email';
			$querypart_from = ', tx_civserv_position, tx_civserv_employee_em_position_mm';
			$querypart_where = 'AND tx_civserv_employee.uid = ' . $emp_id . ' AND tx_civserv_position.uid = ' . $pos_id . '
								AND !tx_civserv_position.deleted AND !tx_civserv_position.hidden
								AND !tx_civserv_employee_em_position_mm.deleted AND !tx_civserv_employee_em_position_mm.hidden
								AND tx_civserv_employee.uid = tx_civserv_employee_em_position_mm.uid_local
						 		AND tx_civserv_employee_em_position_mm.uid_foreign = tx_civserv_position.uid';
		}

		$res_employee = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
						'em_email, em_datasec as datasec' . $querypart_select,
						'tx_civserv_employee' . $querypart_from,
						'tx_civserv_employee.em_datasec = 1
						 AND !tx_civserv_employee.deleted AND !tx_civserv_employee.hidden ' . $querypart_where,
						'',
						'',
						'');

		return $res_employee;
	}









	/******************************
	 *
	 * Functions for the debit form:
	 *
	 *******************************/

	/**
	 * Sets up an empty debit authorisation form.
	 *
	 * @param	object		Smarty object, the template key/value-pairs should be assigned to
	 * @return	boolean		True, if the function was executed without any error, otherwise false
	 */
	function setDebitForm(&$smartyDebitForm) {
		//Check if debit form was called from a specific service (id = service id)
		if ($this->piVars[id] > '') {
			//Query for standard service details
			$result = $this->queryService(intval($this->piVars[id]));

			//Check if query returned a result
			if ($GLOBALS['TYPO3_DB']->sql_num_rows($result) == 1) {
				$service = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($result);
			} else {
				$GLOBALS['error_message'] = $this->pi_getLL('tx_civserv_pi1_debit_form.error_service','No debit form found for this service!');
				return false;
			}

			$smartyDebitForm->assign('service_uid',$service[uid]);
			$smartyDebitForm->assign('service_name',$service[sv_name]);

		} else {  //Debit form was called from servie list
			$community_id = $this->community['id'];
			$transaction_key = 'debit_authorisation';

			//Retrieve uid of debit authorisation form from transaction configuration table
			$result = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
						'ct_transaction_uid as uid',
						'tx_civserv_conf_transaction',
						'tx_civserv_conf_transaction.ct_community_id  = ' . $community_id . '
						 AND tx_civserv_conf_transaction.ct_transaction_key  = "' . $transaction_key . '" ');

			//Check if query returned a result
			if ($GLOBALS['TYPO3_DB']->sql_num_rows($result) == 1) {
				$result = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($result);
				$debit_form_uid = intval($result[uid]);

				//Retrieve all services associated with the debit authorisation form from database
				$res_forms = $GLOBALS['TYPO3_DB']->exec_SELECT_mm_query(
							'tx_civserv_service.uid as uid, tx_civserv_service.sv_name as name',
							'tx_civserv_service',
							'tx_civserv_service_sv_form_mm',
							'tx_civserv_form',
							'AND tx_civserv_form.uid = ' . $debit_form_uid . '
							 AND !tx_civserv_service.deleted AND !tx_civserv_service.hidden
							 AND !tx_civserv_form.deleted AND !tx_civserv_form.hidden
							 AND ((UNIX_TIMESTAMP(LOCALTIMESTAMP) BETWEEN tx_civserv_service.starttime AND tx_civserv_service.endtime)
							 	  OR ((UNIX_TIMESTAMP(LOCALTIMESTAMP) > tx_civserv_service.starttime) AND (tx_civserv_service.endtime=0))
							 	  OR (tx_civserv_service.starttime=0 AND tx_civserv_service.endtime=0) )
							 AND ((UNIX_TIMESTAMP(LOCALTIMESTAMP) BETWEEN tx_civserv_form.starttime AND tx_civserv_form.endtime)
							 	  OR ((UNIX_TIMESTAMP(LOCALTIMESTAMP) > tx_civserv_form.starttime) AND (tx_civserv_form.endtime=0))
							 	  OR (tx_civserv_form.starttime=0 AND tx_civserv_form.endtime=0) )',
							'',
							'name',	//ORDER BY
							'');

				$serviceList = $this->sql_fetch_array_r($res_forms);
				$smartyDebitForm->assign('serviceList',$serviceList);

			} else {
				$GLOBALS['error_message'] = $this->pi_getLL('tx_civserv_pi1_debit_form.no_results','Debit form uid not set in transaction configuration table!');
				return false;
			}
		}

		$action_url = htmlspecialchars($this->pi_linkTP_keepPIvars_url(array(mode => 'check_debit_form'),0,0));
		$smartyDebitForm->assign('action_url',$action_url);

		//Set reset button type to reset functionality
		$smartyDebitForm->assign('button_type','reset');

		//Assign template labels
		$smartyDebitForm->assign('debit_form_label',$this->pi_getLL('tx_civserv_pi1_debit_form.debit_form','Authorisation form for debit entries from bank accounts'));
		$smartyDebitForm->assign('serviceName_label',$this->pi_getLL('tx_civserv_pi1_debit_form.serviceName','Name of service, for which the debit authorisation is granted'));
		$smartyDebitForm->assign('serviceSelect_label',$this->pi_getLL('tx_civserv_pi1_debit_form.serviceSelect','Please select the service, for which the debit authorisation shall be granted'));
		$smartyDebitForm->assign('cashNumber_label',$this->pi_getLL('tx_civserv_pi1_debit_form.cashNumber','Cash number / personal key'));
		$smartyDebitForm->assign('bankName_label',$this->pi_getLL('tx_civserv_pi1_debit_form.bankName','Name of bank'));
		$smartyDebitForm->assign('bankCode_label',$this->pi_getLL('tx_civserv_pi1_debit_form.bankCode','Bank code'));
		$smartyDebitForm->assign('accountNumber_label',$this->pi_getLL('tx_civserv_pi1_debit_form.accountNumber','Account number'));
		$smartyDebitForm->assign('accountHolder_label',$this->pi_getLL('tx_civserv_pi1_debit_form.accountHolder','Name of account holder'));
		$smartyDebitForm->assign('firstname_label',$this->pi_getLL('tx_civserv_pi1_debit_form.firstname','First name'));
		$smartyDebitForm->assign('surname_label',$this->pi_getLL('tx_civserv_pi1_debit_form.surname','Surname'));
		$smartyDebitForm->assign('agreement_label',$this->pi_getLL('tx_civserv_pi1_debit_form.agreement','I agree, that all unsolicited data provided by me are stored for the purpose of fulfilling the duties of the city treasury.'));
		$smartyDebitForm->assign('optional_data_label',$this->pi_getLL('tx_civserv_pi1_debit_form.optional_data','Optional information'));
		$smartyDebitForm->assign('phone_label',$this->pi_getLL('tx_civserv_pi1_debit_form.phone','Phone'));
		$smartyDebitForm->assign('email_label',$this->pi_getLL('tx_civserv_pi1_debit_form.email','E-Mail'));
		$smartyDebitForm->assign('button_send_label',$this->pi_getLL('tx_civserv_pi1_debit_form.button_send','Send'));
		$smartyDebitForm->assign('button_reset_label',$this->pi_getLL('tx_civserv_pi1_debit_form.button_reset','Cancel'));

		return true;
	}


	/**
	 * Checks the submitted debit authorisation form and stores the entry in the database.
	 *
	 * @param	object		Smarty object, the template key/value-pairs should be assigned to
	 * @return	boolean		True, if the function was executed without any error, otherwise false
	 */
	function checkDebitForm(&$smartyDebitForm) {
		//Retrieve passed parameters
		$service = t3lib_div::_POST('service');		//service = service.uid|service.name
		$cashNumber = t3lib_div::_POST('cashNumber');
		$bankName = t3lib_div::_POST('bankName');
		$bankCode = t3lib_div::_POST('bankCode');
		$accountNumber = t3lib_div::_POST('accountNumber');
		$firstname = t3lib_div::_POST('firstname');
		$surname = t3lib_div::_POST('surname');
		$phone = t3lib_div::_POST('phone');
		$email = t3lib_div::_POST('email');

		if (empty($service)) {
			$serviceUID = t3lib_div::_POST('serviceUID');
			$serviceName = t3lib_div::_POST('serviceName');
		} else {
			//Split passed service string to separate service uid and service name
			$pieces = explode("|", $service, 2);
			$serviceUID = $pieces[0];
			$serviceName = $pieces[1];
		}

		//Bool variable that indicates whether filled debit authorisation form is valid or not
		$is_valid = true;

		//Check filled form fields
		if (empty($cashNumber) || !is_numeric($cashNumber) || strlen($cashNumber) > 8) {
			$smartyDebitForm->assign('error_cashNumber',$this->pi_getLL('tx_civserv_pi1_debit_form.error_cashNumber','Please enter a valid cash number!'));
			$is_valid = false;
		}

		if (empty($bankName)) {
			$smartyDebitForm->assign('error_bankName',$this->pi_getLL('tx_civserv_pi1_debit_form.error_bankName','Please enter the name of your bank!'));
			$is_valid = false;
		}

		if (empty($bankCode) || !is_numeric($bankCode) || strlen($bankCode) > 9) {
			$smartyDebitForm->assign('error_bankCode',$this->pi_getLL('tx_civserv_pi1_debit_form.error_bankCode','Please enter a valid bank code!'));
			$is_valid = false;
		}

		if (empty($accountNumber) || !is_numeric($accountNumber) || strlen($accountNumber) > 9) {
			$smartyDebitForm->assign('error_accountNumber',$this->pi_getLL('tx_civserv_pi1_debit_form.error_accountNumber','Please enter a valid account number!'));
			$is_valid = false;
		}

 		if (empty($firstname)) {
			$smartyDebitForm->assign('error_firstname',$this->pi_getLL('tx_civserv_pi1_debit_form.error_firstname','Please enter the first name of the account holder!'));
			$is_valid = false;
		}

		if (empty($surname)) {
			$smartyDebitForm->assign('error_surname',$this->pi_getLL('tx_civserv_pi1_debit_form.error_surname','Please enter the surname of the account holder!'));
			$is_valid = false;
		}

		if (!($email && t3lib_div::validEmail($email))) {
			$smartyDebitForm->assign('error_email',$this->pi_getLL('tx_civserv_pi1_debit_form.error_email','Please enter a valid email address!'));
			$is_valid = false;
		}

		if ($is_valid) {
			//Store entry in database
			$ip = $_SERVER['REMOTE_ADDR'];
			$time = time();
			$GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_civserv_transaction_debit_authorisation',
												   array(	"tstamp" => $time,
												   			"service_uid" => $serviceUID,
												   			"cash_number" => $cashNumber,
												   			"bank_name" => $bankName,
												   			"bank_code" => $bankCode,
												   			"account_number" => $accountNumber,
												   			"firstname" => $firstname,
												   			"surname" => $surname,
												   			"phone" => $phone,
												   			"email" => $email,
												   			"remote_addr" => $ip));

			$smartyDebitForm->assign('complete',$this->pi_getLL('tx_civserv_pi1_debit_form.complete','Thank you! Your data has been successfully stored in our system.'));
			return true;
		} else {	//Return debit authorisation form template with error markers

			//Set reset button type to submit functionality (necessary for resetting debit form in 'check_debit_form'-mode)
			$smartyDebitForm->assign('button_type','submit');

			//Assign template labels
			$smartyDebitForm->assign('debit_form_label',$this->pi_getLL('tx_civserv_pi1_debit_form.debit_form','Authorisation form for debit entries from bank accounts'));
			$smartyDebitForm->assign('serviceName_label',$this->pi_getLL('tx_civserv_pi1_debit_form.serviceName','Name of service, for which the debit authorisation is granted'));
			$smartyDebitForm->assign('serviceSelect_label',$this->pi_getLL('tx_civserv_pi1_debit_form.serviceSelect','Please select the service, for which the debit authorisation shall be granted'));
			$smartyDebitForm->assign('cashNumber_label',$this->pi_getLL('tx_civserv_pi1_debit_form.cashNumber','Cash number / personal key'));
			$smartyDebitForm->assign('bankName_label',$this->pi_getLL('tx_civserv_pi1_debit_form.bankName','Name of bank'));
			$smartyDebitForm->assign('bankCode_label',$this->pi_getLL('tx_civserv_pi1_debit_form.bankCode','Bank code'));
			$smartyDebitForm->assign('accountNumber_label',$this->pi_getLL('tx_civserv_pi1_debit_form.accountNumber','Account number'));
			$smartyDebitForm->assign('accountHolder_label',$this->pi_getLL('tx_civserv_pi1_debit_form.accountHolder','Name of account holder'));
			$smartyDebitForm->assign('firstname_label',$this->pi_getLL('tx_civserv_pi1_debit_form.firstname','First name'));
			$smartyDebitForm->assign('surname_label',$this->pi_getLL('tx_civserv_pi1_debit_form.surname','Surname'));
			$smartyDebitForm->assign('agreement_label',$this->pi_getLL('tx_civserv_pi1_debit_form.agreement','I agree, that all unsolicited data provided by me are stored for the purpose of fulfilling the duties of the city treasury.'));
			$smartyDebitForm->assign('optional_data_label',$this->pi_getLL('tx_civserv_pi1_debit_form.optional_data','Optional information'));
			$smartyDebitForm->assign('phone_label',$this->pi_getLL('tx_civserv_pi1_debit_form.phone','Phone'));
			$smartyDebitForm->assign('email_label',$this->pi_getLL('tx_civserv_pi1_debit_form.email','E-Mail'));
			$smartyDebitForm->assign('button_send_label',$this->pi_getLL('tx_civserv_pi1_debit_form.button_send','Send'));
			$smartyDebitForm->assign('button_reset_label',$this->pi_getLL('tx_civserv_pi1_debit_form.button_reset','Cancel'));
			$smartyDebitForm->assign('service_uid',$serviceUID);
			$smartyDebitForm->assign('service_name',$serviceName);
			$smartyDebitForm->assign('cashNumber',$cashNumber);
			$smartyDebitForm->assign('bankName',$bankName);
			$smartyDebitForm->assign('bankCode',$bankCode);
			$smartyDebitForm->assign('accountNumber',$accountNumber);
			$smartyDebitForm->assign('firstname',$firstname);
			$smartyDebitForm->assign('surname',$surname);
			$smartyDebitForm->assign('phone',$phone);
			$smartyDebitForm->assign('email',$email);
			return true;
		}
	}




	/**
	 * Displays just any plain text but with the correct styles
	 *
	 * @param	object		Smarty object, the template key/value-pairs should be assigned to
	 * @return	boolean		True, if the function was executed without any error, otherwise false
	 */
	function showPlainText(&$plaintext) {

		//yet to be implemented
		return true;
	}




	/******************************
	 *
	 * Various helper functions:
	 *
	 *******************************/


	/**
	 * Format string with nl2br and htmlspecialchars().
	 *
	 * @param	string		string
	 * @return	string		formatted string
	 */
	function formatStr($str)	{
		if (is_array($this->conf["general_stdWrap."]))	{
			$str = $this->local_cObj->stdWrap($str,$this->conf["general_stdWrap."]);
		}
		return $str;
	}


	/**
	 * Builds the HTML-code for including an image in a page, including a link to enlarge the image.
	 * Depends on cObj->IMAGE.
	 *
	 * @param	string		Image name
	 * @param	string		Image path
	 * @param	string		TS configuration
	 * @param	string		alternativley text for the image
	 * @return	string		HTML-Code for including the image in a page
	 */
	function getImageCode($image,$path,$conf,$altText)	{
		$conf['file'] = $path . $image;
		$conf['altText'] = $altText;
		return $this->cObj->IMAGE($conf);
	}


	/**
	 * Fetches result of db query with multiple rows and stores them in an array
	 *
	 * @param	result_set		Result of database query
	 * @return	array		Array with results from database query
	 */
	function sql_fetch_array_r($result)	{
		$row_counter = 0;
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($result) ) {
			$whole_result[$row_counter] = $row;
			$row_counter++;
		}
		return $whole_result;
	}

	
	
	
	/**
	 * Fetches result of db query with multiple rows and stores them in an array
	 *
	 * @param	result_set		Result of database query
	 * @return	array		Array with results from database query
	 */
	function get_hoster_email()	{
		$hoster_email="";
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'cf_value',			 							// SELECT ...
			'tx_civserv_configuration',						// FROM ...    
			'cf_key = "mail_to"',		// AND title LIKE "%blabla%"', // WHERE...
			'', 											// GROUP BY...
			'',   											// ORDER BY...
			'' 												// LIMIT to 10 rows, starting with number 5 (MySQL compat.)
		);
		if($res){
			$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res); 
			$hoster_email = $row['cf_value'];//oder $row[cf_value]
		}else{
			$hoster_email = "info@some_hoster.de";
		}
		if ($hoster_email == ''){
			$hoster_email = "info@some_hoster.de";
		}
		return $hoster_email;
	}


	/**
	 * Overwrites the same function from the parent class tslib_pibase. Does the same,
	 * but keeps always piVars[community_id]. If no community_id could be determined, caching is disabled.
	 * Get URL to the current page while keeping currently set values in piVars.
	 * Returns only the URL from the link.
	 *
	 * @param	array		Array of values to override in the current piVars. Contrary to pi_linkTP the keys in this array must correspond to the real piVars array and therefore NOT be prefixed with the $this->prefixId string. Further, if a value is a blank string it means the piVar key will not be a part of the link (unset)
	 * @param	boolean		If $cache is set, the page is asked to be cached by a &cHash value (unless the current plugin using this class is a USER_INT). Otherwise the no_cache-parameter will be a part of the link.
	 * @param	boolean		If set, then the current values of piVars will NOT be preserved anyways... (except for piVars[community_id])
	 * @param	integer		Alternative page ID for the link. (By default this function links to the SAME page!)
	 * @return	string		The URL ($this->cObj->lastTypoLinkUrl)
	 * @see tslib_pibase::pi_linkTP_keepPIvars()
	 */
	 function pi_linkTP_keepPIvars_url($overrulePIvars=array(),$cache=0,$clearAnyway=0,$altPageId=0) {
	 	if ($this->piVars[community_id] > '') {
	 		$overrulePIvars = t3lib_div::array_merge($overrulePIvars,array(community_id => $this->piVars[community_id]));
	 	}
	 	return parent::pi_linkTP_keepPIvars_url($overrulePIvars,$cache,$clearAnyway,$altPageId);
	 }


	/**
	 * Overwrites the same function from the parent class tslib_pibase. Does the same, but uses no tables and is optimized for accessibility.
	 * Returns a Search box, sending search words to piVars "sword" and setting the "no_cache" parameter as well in the form.
	 * Submits the search request to the current REQUEST_URI
	 *
	 * @param	string		Attributes for the div tag which is wrapped around the table cells containing the search box
	 * @param	boolean		If true, a heading for the search box is printed
	 * @return	string		Output HTML, wrapped in <div>-tags with a class attribute
	 */
	function pi_list_searchBox($divParams='',$header=false) {
		// Search box design:
		if ($this->piVars[sword] <= '') {
			 $this->piVars[sword] = $this->pi_getLL('pi_list_searchBox_defaultValue','search item');
		}
		$sBox = '

		<!--
			List search box:
		-->

		<div' . $this->pi_classParam('searchbox') . '>
			<form method="post" action="'.htmlspecialchars(t3lib_div::getIndpEnv('REQUEST_URI')).'" style="margin: 0 0 0 0;" >
				<fieldset>
        				<legend>' . $this->pi_getLL('pi_list_searchBox_searchform','Search form') . '</legend>
          				<div class="searchform" ' . trim($divParams) . '>
            				<p><label for="query" title="' . $this->pi_getLL('pi_list_searchBox_searchkey','Please enter here your search key') . '">' .
            					($header?'<strong>' . $this->pi_getLL('pi_list_searchBox_header','Keyword search') . ':</strong><br />':'') .
            				'</label></p>
           					<input type="text" name="' . $this->prefixId . '[sword]" id="query" class="searchkey" size="16" maxlength="60" value="' . htmlspecialchars($this->piVars['sword']) . '"' . $this->pi_classParam('searchbox-sword') . ' onblur="if(this.value==\'\') this.value=\'' . htmlspecialchars($this->piVars['sword']) . '\';" onfocus="if(this.value==\'' . $this->pi_getLL('pi_list_searchBox_defaultValue','search item') . '\') this.value=\'\';" />
            				<input type="submit" value="' . $this->pi_getLL('pi_list_searchBox_search','Search',TRUE) . '"' . $this->pi_classParam('searchbox-button') . ' accesskey="S" title="' . $this->pi_getLL('pi_list_searchBox_submit','Klick here, to submit the search query') . '"/>
            				<input type="hidden" name="no_cache" value="1" />
            				<input type="hidden" name="'.$this->prefixId.'[pointer]" value="" />
          				</div>
       			</fieldset>
     		 </form>
		</div>';

		return $sBox;
	}


	/**
	 * Returns a results browser. This means a bar of page numbers plus a "previous" and "next" link. For each entry in the bar the piVars "pointer" will be pointing to the "result page" to show.
	 * Using $this->piVars['pointer'] as pointer to the page to display
	 * Using $this->internal['res_count'], $this->internal['results_at_a_time'] and $this->internal['maxPages'] for count number, how many results to show and the max number of pages to include in the browse bar.
	 *
	 * @param	boolean		If set (default) the text "Displaying results..." will be show, otherwise not.
	 * @param	string		Attributes for the div tag which is wrapped around the table cells containing the browse links
	 * @param	string		If given, the passed string is used to seperate the links
	 * @return	string		Output HTML, wrapped in <div>-tags with a class attribute
	 */
	function pi_list_browseresults($showResultCount=1,$divParams='',$spacer=false)      {

			// Initializing variables:
		$pointer=$this->piVars['pointer'];
		$count=$this->internal['res_count'];
		$results_at_a_time = t3lib_div::intInRange($this->internal['results_at_a_time'],1,1000);
		$maxPages = t3lib_div::intInRange($this->internal['maxPages'],1,100);
		$max = t3lib_div::intInRange(ceil($count/$results_at_a_time),1,$maxPages);
		$pointer=intval($pointer);
		$links=array();

			// Make browse-table/links:
		if ($this->pi_alwaysPrev>=0)    {
			if ($pointer>0) {
				$links[]=$this->pi_linkTP_keepPIvars($this->pi_getLL('pi_list_browseresults_prev','< Previous',TRUE),array('pointer'=>($pointer-1?$pointer-1:'')),1);
			} elseif ($this->pi_alwaysPrev) {
				$links[]=$this->pi_getLL('pi_list_browseresults_prev','< Previous',TRUE);
			}
		}

		if ($max > 1) {
			if ($pointer >= $maxPages - 2) {
				$a = (integer) ($pointer - ($maxPages/2));
			} else {
				$a = 0;
			}
			if ($a < 0) {
				$a = 0;
			}
			for($i;$i<$max;$i++)  {
				$links[]=sprintf('%s'.$this->pi_linkTP_keepPIvars(trim($this->pi_getLL('pi_list_browseresults_page','Page',TRUE).' '.($a+1)),array('pointer'=>($a?$a:'')),1).'%s',
								($pointer==$a?'<span '.$this->pi_classParam('browsebox-SCell').'><strong>':''),
								($pointer==$a?'</strong></span>':''));
				$a++;
			}
		}
		if ($pointer<ceil($count/$results_at_a_time)-1) {
			$links[]=$this->pi_linkTP_keepPIvars($this->pi_getLL('pi_list_browseresults_next','Next >',TRUE),array('pointer'=>$pointer+1),1);
		}

		$pR1 = $pointer*$results_at_a_time+1;
		$pR2 = $pointer*$results_at_a_time+$results_at_a_time;
		$sBox = '

                <!--
                        List browsing box:
                -->
                <div'.$this->pi_classParam('browsebox').'>'.
                        ($showResultCount ? '
                        <p>'.
                                ($this->internal['res_count'] ?
                        sprintf(
                                        str_replace('###SPAN_BEGIN###','<span'.$this->pi_classParam('browsebox-strong').'>',$this->pi_getLL('pi_list_browseresults_displays','Displaying results ###SPAN_BEGIN###%s to %s</span> out of ###SPAN_BEGIN###%s</span>')),
                                        $this->internal['res_count'] > 0 ? $pR1 : 0,
                                        min(array($this->internal['res_count'],$pR2)),
                                        $this->internal['res_count']
                                ) :
                                $this->pi_getLL('pi_list_browseresults_noResults','Sorry, no items were found.')).'</p>':''
                        ).
                '

                        <'.trim('p '.$divParams).'>
                                        '.implode($spacer,$links).'
                        </p>
                </div>';

                return $sBox;
        }




	/******************************
	 *
	 * Function for generating a menu array:
	 *
	 *******************************/


	/**
	 * Builds an array, wich could be included with a user function in a menu.
	 * The menuarray contains the items 'Services A-Z', 'Circumstances', 'Usergroups', 'Organisation', 'TOP 15' and optional 'Fulltext search'.
	 * The setup in a template could look like:
	 *   menu= HMENU
	 *   menu.special = userfunction
	 *   menu.special.pageid = {$pageid}
	 *   menu.special.fulltext_search_id = {$fulltext_search_id}
	 *   menu.special.userFunc = tx_civserv_pi1->makeMenuArray
	 *   menu.stdWrap.wrap  = <ul> | </ul>
	 *   menu.1 = TMENU
	 *   menu.1.NO {
	 *     allWrap =<li> | </li>
	 *   }
	 *
	 * @param	sting		content
	 * @param	array		configuration array
	 * @return	array		the menuarray
	 */
	function makeMenuArray($content,$conf)    {
		// Get language for the frontend, necessary for pi_getLL-functions
		$this->pi_loadLL();

		if ($conf['pageid'] > '') {
			$pageid = $conf['pageid'];
		} else {
			$pageid = $GLOBALS['TSFE']->id;
		}

		// Start or resume session
		session_name($this->extKey);
		session_start();
		// Save community id in session, to ensure that the id is also saved when vititing sites without the civserv extension (e.g. fulltext search)
		if ($_SESSION['community_id'] <= '') {
			$_SESSION['community_id'] = $this->piVars[community_id];
		}
		// Set piVars[community_id], if not given from the URL. Necessary for the function pi_linkTP_keepPIvars_url.
		if ($this->piVars[community_id] <= '') {
			$this->piVars[community_id] = $_SESSION['community_id'];
		}

		if ($conf['menuServiceList']) {
			$menuArray[] = array(
								'title' => $this->pi_getLL('tx_civserv_pi1_menuarray.service_list','Services A - Z'),
								'_OVERRIDE_HREF' => $this->pi_linkTP_keepPIvars_url(array(mode => 'service_list'),1,1,$pageid),
								'ITEM_STATE' => ($this->piVars[mode]=='service_list')?'ACT':'NO');
		}
		if ($conf['menuCircumstanceTree']) {
			$menuArray[] = array(
								'title' => $this->pi_getLL('tx_civserv_pi1_menuarray.circumstance_tree','Circumstances'),
								'_OVERRIDE_HREF' => $this->pi_linkTP_keepPIvars_url(array(mode => 'circumstance_tree'),1,1,$pageid),
								'ITEM_STATE' => (($this->piVars[mode]=='circumstance_tree') || ($this->piVars[mode]=='circumstance'))?'ACT':'NO');
		}
		if ($conf['menuUsergroupTree']) {
			$menuArray[] = array(
								'title' => $this->pi_getLL('tx_civserv_pi1_menuarray.usergroup_tree','Usergroups'),
								'_OVERRIDE_HREF' => $this->pi_linkTP_keepPIvars_url(array(mode => 'usergroup_tree'),1,1,$pageid),
								'ITEM_STATE' => (($this->piVars[mode]=='usergroup_tree') || ($this->piVars[mode]=='usergroup'))?'ACT':'NO');
		}
		if ($conf['menuOrganisationTree']) {
			$menuArray[] = array(
								'title' => $this->pi_getLL('tx_civserv_pi1_menuarray.organisation_tree','Organisation'),
								'_OVERRIDE_HREF' => $this->pi_linkTP_keepPIvars_url(array(mode => 'organisation_tree'),1,1,$pageid),
								'ITEM_STATE' => (($this->piVars[mode]=='organisation_tree') || ($this->piVars[mode]=='organisation'))?'ACT':'NO');
		}
		if ($conf['menuEmployeeList']) {
			$menuArray[] = array(
								'title' => $this->pi_getLL('tx_civserv_pi1_menuarray.employee_list','Employees A - Z'),
								'_OVERRIDE_HREF' => $this->pi_linkTP_keepPIvars_url(array(mode => 'employee_list'),1,1,$pageid),
								'ITEM_STATE' => ($this->piVars[mode]=='employee_list')?'ACT':'NO');
		}
		if ($conf['menuFormList']) {
			$menuArray[] = array(
								'title' => $this->pi_getLL('tx_civserv_pi1_menuarray.form_list','Forms'),
								'_OVERRIDE_HREF' => $this->pi_linkTP_keepPIvars_url(array(mode => 'form_list'),1,1,$pageid),
								'ITEM_STATE' => ($this->piVars[mode]=='form_list')?'ACT':'NO');
		}
		if ($conf['menuTop15']) {
			$menuArray[] = array(
								'title' => $this->pi_getLL('tx_civserv_pi1_menuarray.top15','Top 15'),
								'_OVERRIDE_HREF' => $this->pi_linkTP_keepPIvars_url(array(mode => 'top15'),0,1,$pageid),
								'ITEM_STATE' => ($this->piVars[mode]=='top15')?'ACT':'NO');
		}

		// get full text search id from TSconfig
		if ($conf['fulltext_search_id'] > '') {
			$menuArray[] = array(
							'title' => $this->pi_getLL('tx_civserv_pi1_menuarray.fulltext_search','Fulltext Search'),
							'_OVERRIDE_HREF' => $this->pi_linkTP_keepPIvars_url(array(),0,1,$conf['fulltext_search_id']),
							'ITEM_STATE' => ($GLOBALS['TSFE']->id==$conf['fulltext_search_id'])?'ACT':'NO');
		}
		return $menuArray;
	}



	/******************************
	 *
	 * Functions for the legal_notice_link in den main navigation
	 *
	 *******************************/

	/**
	 * Returns link to legal notice of the hoster (imprint, web credits)
	 * Normaly used from a template userfunction.
	 *
	 * @param	string		content
	 * @param	array		configuration array
	 * @return	string		The link
	 */
	function getLegalNoticeLink($content, $conf) {
		if ($conf['pageid'] > '') {
			$pageid = $conf['pageid'];
		} else {
			$pageid = $GLOBALS['TSFE']->id;
		}
		return parent::pi_linkTP_keepPIvars_url(array(mode => 'legal_notice'),1,1,$pageid);
	}



	/**
	 * Displays the hoster's legal notice.
	 *
	 * @param	object		Smarty object, the template key/value-pairs should be assigned to
	 * @return	boolean		True, if the function was executed without any error, otherwise false
	 */
	function showLegalNotice(&$smartyLegalNotice) {

		$smartyLegalNotice->assign('contactLink', $this->getContactLink($content,$conf));
		$smartyLegalNotice->assign('imgPath', $this->conf['folder_global_images']);
		return true;
	}







	/******************************
	 *
	 * Functions for the contact_link in den main navigation
	 *
	 *******************************/


	/**
	 * Returns link to contact the hoster
	 * Normaly used from a template userfunction.
	 *
	 * @param	string		content
	 * @param	array		configuration array
	 * @return	string		The link
	 */
	function getContactLink($content, $conf) {
		if ($conf['pageid'] > '') {
			$pageid = $conf['pageid'];
		} else {
			$pageid = $GLOBALS['TSFE']->id;
		}
		return parent::pi_linkTP_keepPIvars_url(array(mode => 'set_contact_form'),1,1,$pageid);
	}


	/******************************
	 *
	 * Functions for the startpage-link in left navigation
	 *
	 *******************************/


	/**
	 * Returns link to set startpage in servicenavigation
	 * Normaly used from a template userfunction.
	 *
	 * @param	string		content
	 * @param	array		configuration array
	 * @return	string		The link
	 */
	function getHomepage($content, $conf) {
	
		if ($conf['pageid'] > '') {
			$pageid = $conf['pageid'];
		} else {
			$pageid = $GLOBALS['TSFE']->id;
		}
		return  str_replace("http://", "", t3lib_div::getIndpEnv(TYPO3_SITE_URL).parent::pi_getPageLink($pageid));
		#return 'www.die-maus.de';
	}










	/******************************
	 *
	 * Functions for mere debugging
	 *
	 *******************************/

	/**
	 * only for testing the value of certain parameters
	 * --> attention: triggers xhtml-error!
	 * gives out the value of a given param in a js_alert-box...
	 *
	 *
	 *
	 */
	function js_alert($msg) {
			echo "<script type=\"text/javascript\">alert('".$msg."');</script>";
	}


}



if (defined("TYPO3_MODE") && $TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/civserv/pi1/class.tx_civserv_pi1.php"])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/civserv/pi1/class.tx_civserv_pi1.php"]);
}

?>
