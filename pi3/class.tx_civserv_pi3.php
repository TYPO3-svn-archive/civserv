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
 * Additional Plugin 'Civil Services employeelist extended' for the 'civserv' extension.
 * could be used in an intranet environment
 *
 * $Id: class.tx_civserv_pi3.php 5671 2007-06-12 13:10:15Z bkohorst $
 *
 * @author	Britta Kohorst <kohorst@citeq.de>
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
 *  106: class tx_civserv_pi3 extends tslib_pibase
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
 * 2125:     function getEmailAddress()
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
#require_once(t3lib_extMgm::extPath('civserv') . 'pi3/class.tx_civserv_accesslog.php');
require_once(t3lib_extMgm::extPath('civserv') . 'res/class.tx_civserv_mandant.php');

/**
 * Class for plugin 'Civil Services'
 *
 */
class tx_civserv_pi3 extends tslib_pibase {
	var $prefixId = 'tx_civserv_pi3';						// Same as class name
	var $scriptRelPath = 'pi3/class.tx_civserv_pi3.php';	// Path to this script relative to the extension dir
	var $extKey = 'civserv';								// The extension key
	var $pi_checkCHash = TRUE;
	
	var $versioningEnabled = FALSE;
	var $previewMode = FALSE;
	var $current_ws = 0;


	/**
	 * @param	string			Content that is to be displayed within the plugin
	 * @param	array			Configuration array
	 * @return	$content		Content that is to be displayed within the plugin
	 */
	function main($content,$conf)	{
#		$GLOBALS['TYPO3_DB']->debugOutput=true;	 // Debugging - only on test-sites!
		if (TYPO3_DLOG)  t3lib_div::devLog('function main of FE class pi3 entered', 'civserv extended ma list');

		
		// Load configuration array
		$this->conf = $conf;

		// Get default values for piVars from template setup
		$this->pi_setPiVarDefaults();
		// Get language for the frontend, necessary for pi_getLL-functions
		$this->pi_loadLL();
		
		
		// some variables for versioning:
		// is the sysext 'version' loaded?
		if (t3lib_extMgm::isLoaded('version')) {
			$this->versioningEnabled = true;
		}
		if($GLOBALS['TSFE']->sys_page->versioningPreview){
			$this->previewMode = true;
		}
		$this->current_ws=$GLOBALS['BE_USER']->workspace;

		// Necessary for formatStr
		$this->local_cObj = t3lib_div::makeInstance('tslib_cObj');
		
		// Start or resume session
		session_name($this->extKey);
		session_start();
		#session_destroy();
		
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
		if ((($this->piVars['community_id'] <= '') && ($_SESSION['community_id'] <= '')) || ($this->piVars['community_id'] == 'choose')) {
		#if(1==2){
			$template = $this->conf['tpl_community_choice'];
			$accurate = $this->chooseCommunity($smartyObject);
			$choose = true;
	 	} elseif (($this->piVars['community_id'] != $_SESSION['community_id']) || ($_SESSION['community_name'] <= '')) {
		#}elseif(1==1){
			if ($this->piVars['community_id'] > '') {
				$community_id = intval($this->piVars['community_id']);
			} else {
				$community_id = intval($_SESSION['community_id']);
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
					$GLOBALS['error_message'] = $this->pi_getLL('tx_civserv_pi3_error.wrong_community_id','Wrong community-id. The entered community is either invalid, the community is not in the current system or the system is misconfigured.');

				case '1':
					// Set session variables
					$_SESSION['community_id'] = $community_id;
					$_SESSION['community_name'] = $community_data[0]['cm_community_name'];
					$_SESSION['community_pidlist'] = $this->pi_getPidList($community_data[0]['cm_uid'],$this->conf['recursive']);
					$_SESSION['circumstance_uid'] = $community_data[0]['cm_circumstance_uid'];
					$_SESSION['usergroup_uid'] = $community_data[0]['cm_usergroup_uid'];
					$_SESSION['organisation_uid'] = $community_data[0]['cm_organisation_uid'];
					$_SESSION['employee_search'] = $community_data[0]['cm_employeesearch'];
					$_SESSION['page_uid'] = $community_data[0]['cm_page_uid']; //for the breadcrumb_navi!!!
					$_SESSION['alternative_language_folder_uid'] = $community_data[0]['cm_alternative_language_folder_uid'];
					$_SESSION['alternative_page_uid'] = $community_data[0]['cm_alternative_page_uid'];
					$_SESSION['info_folder_uid'] = $community_data[0]['cm_info_folder_uid'];
					$_SESSION['stored_pagelink'] = ''; //for the breadcrumb_navi!!!
					$_SESSION['info_sites'] = ''; //for the breadcrumb navigation of information pages
					$_SESSION['default_mode'] = $this->conf['_DEFAULT_PI_VARS.']['mode']; //Default Mode for the breadcrumb navigation
					$_SESSION['stored_mode'] = ''; //for backlinks in extended employeeview
					$_SESSION['stored_filter_key'] = ''; //for backlinks in extended employeeview
					$_SESSION['stored_filter_val'] = ''; //for backlinks in extended employeeview

					break;
				default:
					$GLOBALS['error_message'] = $this->pi_getLL('tx_civserv_pi3_error.community_id_twice','The current system seems to be misconfigured. The given community-id exists at least twice in the configuration table.');

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
			$this->community['page_uid'] = $_SESSION['page_uid'];
			$this->community['alternative_language_folder_uid'] = $_SESSION['alternative_language_folder_uid'];
			$this->community['info_folder_uid'] = $_SESSION['info_folder_uid'];
			$this->community['alternative_page_uid'] = $_SESSION['alternative_page_uid'];
			$this->community['stored_mode'] = $_SESSION['stored_mode']; //for backlinks in extended employeeview
			$this->community['stored_filter_key'] = $_SESSION['stored_filter_key'];
			$this->community['stored_filter_val'] = $_SESSION['stored_filter_val'];


			// Set piVars['community_id'] because it could only be registered in the session and not in the URL
			$this->piVars['community_id'] = $_SESSION['community_id'];
			
			
			// for some reason corrupted pages (wrong community_id) accumulate in the typo3 cache
			// we must prevent that they get listed by search engines: strip off all content!
			if(intval($this->community['id']) !== intval($this->conf['_DEFAULT_PI_VARS.']['community_id'])){
				$GLOBALS['TSFE']->page['title'] = ''; //the less information the corrupted pages bear the better
				$this->piVars['mode'] = 'error';
			}
			
			
			switch($this->piVars['mode'])	{
				case 'employee_list_az':
					$_SESSION['stored_mode'] = 'employee_list_az';
					$GLOBALS['TSFE']->page['title'] = $this->pi_getLL('tx_civserv_pi3_employee_list.az','Employees A - Z');
					$template = $this->conf['tpl_employee_list_pi3'];
#					$accurate = $this->employee_list_az($smartyObject,$this->conf['abcBarAtEmployeeList'],$this->conf['searchAtEmployeeList'],$this->conf['topAtEmployeeList']);
					$accurate = $this->employee_list($smartyObject,$this->conf['abcBarAtEmployeeList'], false, $this->conf['searchAtEmployeeList'],$this->conf['topAtEmployeeList']);
					break;					
				case 'employee_list_orcode':
					$_SESSION['stored_mode'] = 'employee_list_orcode';
					$GLOBALS['TSFE']->page['title'] = $this->pi_getLL('tx_civserv_pi3_employee_list.orcode','Employees A - Z');
					$template = $this->conf['tpl_employee_list_pi3'];
#					$accurate = $this->employee_list_orcode($smartyObject, $this->conf['orCodeBarAtEmployeeList'], $this->conf['searchAtEmployeeList'], $this->conf['topAtEmployeeList']);
					$accurate = $this->employee_list($smartyObject, false, $this->conf['orCodeBarAtEmployeeList'], $this->conf['searchAtEmployeeList'], $this->conf['topAtEmployeeList']);
					break;			
				case 'organisation':
					#$GLOBALS['TSFE']->page['title'] = $this->pi_getLL('tx_civserv_pi3_service_list.organisation','Organisation');
					$template = $this->conf['tpl_organisation_plus'];
					// test bk: continue the abcBar from the OrganisationList!!!
					#$accurate = $this->organisationDetail($smartyObject, $this->conf['continueAbcBarFromOrganisationList']) && $this->serviceList($smartyObject,$this->conf['abcBarAtOrganisation'],$this->conf['searchAtOrganisation'],$this->conf['topAtOrganisation']);
					$accurate = $this->organisationDetail($smartyObject, $this->conf['continueAbcBarFromOrganisationList']);
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
				default:
					$accurate = false;
					$GLOBALS['error_message'] = $this->pi_getLL('tx_civserv_pi3_error.invalid_mode','Invalid mode');
			}
		}

		if (!$accurate) {
			$template = $this->conf['tpl_error_page'];
			$smartyObject->assign('error_message_label',$this->pi_getLL('tx_civserv_pi3_error.message_label','The following error occured'));
			$smartyObject->assign('error_message',$GLOBALS['error_message']);
		}

		// check if the specified template exists
		if ($smartyObject->template_exists($template)) {
			$content = $smartyObject->fetch($template);
		} else {
			$content = str_replace('###TEMPLATE###',$template,$this->pi_getLL('tx_civserv_pi3_error.smarty','The Smarty template <i>###TEMPLATE###</i> does not exist.'));
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
	 * Generates a list of all employees (who are assigned at least one position)
	 *
	 * @param	[type]		$$smartyEmployeeList: ...
	 * @param	[type]		$abcBar: ...
	 * @param	[type]		$searchBox: ...
	 * @param	[type]		$topList: ...
	 * @return	[type]		...
	 */
	function employee_list(&$smartyEmployeeList, $abcBar=false, $orCodeBar=false, $searchBox=false, $topList=false){
		// Die Funktion makeEmployeeListQueryAZ liefert alle Mitarbeiter, die eine Stelle besetzen
		// die Query enthälten Daten über den Mitarbeiter, die Stelle und die Mitarbeiter_Stellen_zuordnung (raum etc)
		if($this->piVars['mode'] == 'employee_list_az'){
			$query = $this->makeEmployeeListQueryAZ($this->piVars['char']);
			$_SESSION['stored_filter_key'] = 'char';
			$_SESSION['stored_filter_val'] = $this->piVars['char'];
			$mode_text = $this->pi_getLL('tx_civserv_pi3_employee_list.by_name',' by names');
			debug($query, 'makeEmployeeListQueryAZ');
		}elseif($this->piVars['mode'] == 'employee_list_orcode'){
			$query = $this->makeEmployeeListQueryOrCode($this->piVars['orcode']);
			$_SESSION['stored_filter_key'] = 'orcode';
			$_SESSION['stored_filter_val'] = $this->piVars['orcode'];
			$mode_text = $this->pi_getLL('tx_civserv_pi3_employee_list.by_organisation',' by department');
			debug($query, 'makeEmployeeListQueryOrCode');
		}


		//hier ist die musik!
		$res_employees = $GLOBALS['TYPO3_DB']->sql(TYPO3_db, $query);
		$row_counter = 0;
		
		$em_org_kombis=array(); // store all combinations of an employee and his/her employing organisation unit here
		
		$kills=array(); // will be used to eleminate dublicates from the above list
		
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_employees) ) {
			$employees[$row_counter]['em_uid']=$row['em_uid'];
			
			// Anrede bestimmen
			if($row['em_address']==2){
				$employees[$row_counter]['address_long'] = $this->pi_getLL('tx_civserv_pi3_organisation.address_female', 'Ms.');
			}elseif($row['em_address']==1){
				$employees[$row_counter]['address_long'] = $this->pi_getLL('tx_civserv_pi3_organisation.address_male', 'Mr.');
			}
			
			// Mitarbeiter-Daten
			$employees[$row_counter]['title'] = $row['em_title'];
			$employees[$row_counter]['name'] = $row['name']; //alias in makeEmployeeListQueryAZ, need for generic makeAbcBar
			$employees[$row_counter]['firstname'] = $row['em_firstname'];
			$employees[$row_counter]['full_name'] = $row['name'].", ".$row['em_firstname'];
			$employees[$row_counter]['em_telephone'] = $row['em_telephone'];
			$employees[$row_counter]['em_fax'] = $row['em_fax'];
			$employees[$row_counter]['em_mobile'] = $row['em_mobile'];
			$employees[$row_counter]['em_email'] = $row['em_email'];
			$employees[$row_counter]['em_image'] = $row['em_image'];
			$employees[$row_counter]['em_datasec'] = $row['em_datasec'];
			$employees[$row_counter]['em_uid'] = $row['em_uid'];
			
			// Position-Daten
			$employees[$row_counter]['po_uid'] = $row['po_uid'];
			$employees[$row_counter]['po_name'] = $row['po_name'];
			$employees[$row_counter]['po_descr'] = $row['po_descr'];
			
			// Mitarbeiter-Position-Daten
			$employees[$row_counter]['ep_uid'] = $row['ep_uid'];
			$employees[$row_counter]['ep_officehours'] = $row['ep_officehours'];
			$employees[$row_counter]['ep_room'] = $row['ep_room'];
			$employees[$row_counter]['ep_telephone'] = $row['ep_telephone'];
			$employees[$row_counter]['ep_fax'] = $row['ep_fax'];
			$employees[$row_counter]['ep_mobile'] = $row['ep_mobile'];
			$employees[$row_counter]['ep_email'] = $row['ep_email'];
			$employees[$row_counter]['ep_datasec'] = $row['ep_datasec'];
			$employees[$row_counter]['ep_label'] = $row['ep_label'];
			
			
			// Bild	holen
			$employees[$row_counter]['em_imagecode']=""; // leere Initialisierung
			if ($employees[$row_counter]['em_image'] != "") {
				$image = $employees[$row_counter]['em_image'];
				$imagepath = $this->conf['folder_organisations'] . $this->community['id'] . '/images/';
				$image_text = "blabalbal";
				$imageCode = $this->getImageCode($image,$imagepath,$this->conf['service-image.'],$image_text);
				$imageCode = preg_replace('/<img[^>]*>/', '<img src="'.t3lib_div::getIndpEnv(TYPO3_REQUEST_DIR).'typo3conf/ext/civserv/icon_tx_civserv_foto.gif" alt="click it like beckam" />', $imageCode);
				$employees[$row_counter]['em_imagecode'] = $imageCode;
			}


			if($this->piVars['orcode'] == 'hod' && $this->piVars['mode'] == 'employee_list_orcode'){ //display all the head-of-departments
				debug($row, 'row!');
				$employees[$row_counter]['or_code'] = $row['or_code']; //delivered by makeemployeelistQuery....
				$employees[$row_counter]['or_uid'] = $row['or_uid'];
				$employees[$row_counter]['or_name'] = $row['organisation'];
				// spezielle Links zusammenbauen:
				// link zur employee-detail-seite
				$employees[$row_counter]['or_url'] = htmlspecialchars(
													$this->pi_linkTP_keepPIvars_url(array(
														'mode' => 'organisation',
														'id' => $employees[$row_counter]['or_uid'],
														'pos_id' => $employees[$row_counter]['po_uid']
														),
													1,1)
												);
				// get position - if any
				$empos_res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
					'tx_civserv_position.uid as po_uid,
					 tx_civserv_position.po_name,
					 tx_civserv_position.po_descr,
					 tx_civserv_employee_em_position_mm.uid as ep_uid,
					 tx_civserv_employee_em_position_mm.ep_officehours,
					 tx_civserv_employee_em_position_mm.ep_room,
					 tx_civserv_employee_em_position_mm.ep_telephone,
					 tx_civserv_employee_em_position_mm.ep_fax,
					 tx_civserv_employee_em_position_mm.ep_mobile,
					 tx_civserv_employee_em_position_mm.ep_email,
					 tx_civserv_employee_em_position_mm.ep_datasec,
					 tx_civserv_employee_em_position_mm.ep_label',
					'tx_civserv_employee, 
					 tx_civserv_position, 
					 tx_civserv_employee_em_position_mm',
					'tx_civserv_employee.uid = tx_civserv_employee_em_position_mm.uid_local 
					 AND tx_civserv_employee_em_position_mm.uid_foreign = tx_civserv_position.uid 
					 AND tx_civserv_employee.uid = '.$employees[$row_counter]['em_uid'],
					'',
					'',
					'');
				// Schleife über alle Organisationen, bei denen die Stelle angesiedelt ist - das sollte nur einen Durchgang ergeben!!!
				while ($empos_row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($empos_res) ) {
					$employees[$row_counter]['po_uid'] = $empos_row['po_uid'];
					$employees[$row_counter]['po_name'] = $empos_row['po_name'];
					$employees[$row_counter]['po_descr'] = $empos_row['or_descr'];
				}
			}else{
				// Organisation:
				// select the organisation assigned to the position of the employee employee
				$orga_res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
					'tx_civserv_organisation.uid as or_uid, 
					 tx_civserv_organisation.or_name as organisation,
					 tx_civserv_organisation.or_code,
					 tx_civserv_organisation.or_synonym1,
					 tx_civserv_organisation.or_synonym2,
					 tx_civserv_organisation.or_synonym3,
					 tx_civserv_organisation.or_supervisor,
					 tx_civserv_organisation.or_show_supervisor,
					 tx_civserv_organisation.or_hours,
					 tx_civserv_organisation.or_telephone,
					 tx_civserv_organisation.or_fax,
					 tx_civserv_organisation.or_email,
					 tx_civserv_organisation.or_image,
					 tx_civserv_organisation.or_infopage,
					 tx_civserv_organisation.or_addinfo,
					 tx_civserv_organisation.or_addlocation',
					'tx_civserv_organisation,
					 tx_civserv_position_po_organisation_mm',
					'tx_civserv_position_po_organisation_mm.uid_local = ' . $employees[$row_counter]['po_uid'] . ' 
					 AND tx_civserv_organisation.uid = tx_civserv_position_po_organisation_mm.uid_foreign
					 AND tx_civserv_organisation.deleted=0 AND tx_civserv_organisation.hidden=0',
					'',
					'',
					''
				);
					
				// Schleife über alle Organisationen, bei denen die Stelle angesiedelt ist - das sollte nur einen Durchgang ergeben!!!
				while ($orga_row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($orga_res) ) {
					$employees[$row_counter]['or_uid'] = $orga_row['or_uid'];
					$employees[$row_counter]['or_name'] = $orga_row['organisation'];
					$employees[$row_counter]['or_code'] = $orga_row['or_code'];
					
					// spezielle Links zusammenbauen:
					// link zur employee-detail-seite
					$employees[$row_counter]['or_url'] = htmlspecialchars(
														$this->pi_linkTP_keepPIvars_url(array(
															'mode' => 'organisation',
															'id' => $employees[$row_counter]['or_uid'],
															'pos_id' => $employees[$row_counter]['po_uid']
															),
														1,1)
													);
				}
			}// end else

			// Gebäude
			if($employees[$row_counter]['ep_room'] > 0){ 
				// dem Mitarbeiter wurde ein Raum zugewiesen, hol mir das passende Gebäude
				// der Raum weiß über rbf_building_bl_floor zu welchem Gebäude er gehört und
				// zu welcher Etage....
				$res_building = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
					'tx_civserv_building.bl_mail_street, 
					 tx_civserv_building.bl_mail_pob, 
					 tx_civserv_building.bl_mail_postcode, 
					 tx_civserv_building.bl_mail_city, 
					 tx_civserv_building.bl_name, 
					 tx_civserv_building.bl_name_to_show,
					 tx_civserv_building.bl_building_street, 
					 tx_civserv_building.bl_building_postcode, 
					 tx_civserv_building.bl_building_city, 
					 tx_civserv_building.bl_pubtrans_stop, 
					 tx_civserv_building.bl_pubtrans_url,
					 tx_civserv_building.bl_citymap_url,
					 tx_civserv_room.ro_name,
					 tx_civserv_floor.fl_descr',
					'tx_civserv_room,
					 tx_civserv_floor, 
					 tx_civserv_building,
					 tx_civserv_building_bl_floor_mm',
					'tx_civserv_building_bl_floor_mm.uid = tx_civserv_room.rbf_building_bl_floor
					 AND tx_civserv_room.uid = '.$employees[$row_counter]['ep_room'].' 
					 AND tx_civserv_floor.uid = tx_civserv_building_bl_floor_mm.uid_foreign
					 AND tx_civserv_building.uid = tx_civserv_building_bl_floor_mm.uid_local',
					'',
					'',
					'');
				while($row_building = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_building)){
					// todo: check on bl_name_to_show, proper language
					$employees[$row_counter]['or_buildings'] = $row_building['bl_name_to_show'] > ''? $row_building['bl_name_to_show'] : $row_building['bl_name'];
					$employees[$row_counter]['or_buildings'] .= ": Raum ".$row_building['ro_name'].", ".$row_building['fl_descr'];
				}	
			}else{ // der Mitarbeiter-Stellenzuordnung wurde kein Raum zugewiesen
				$organisation_buildings= array();
				$orga_bl_count=0;
				
				// wir können nicht wissen, in welchem Gebäude die Position des Mitarbeiters angesiedelt ist....
				// wir gucken erst mal, ob bestimmte zu der Organisation gehörende Gebäude überhaupt 
				// für die Anzeige ausgewählt wurden (in Tabelle tx_civserv_organisation_or_building_to_show_mm)?
				$res_building = $GLOBALS['TYPO3_DB']->exec_SELECT_mm_query(
					'tx_civserv_building.bl_mail_street, 
					 tx_civserv_building.bl_mail_pob, 
					 tx_civserv_building.bl_mail_postcode, 
					 tx_civserv_building.bl_mail_city, 
					 tx_civserv_building.bl_name, 
					 tx_civserv_building.bl_name_to_show,
					 tx_civserv_building.bl_building_street, 
					 tx_civserv_building.bl_building_postcode, 
					 tx_civserv_building. bl_building_city, 
					 tx_civserv_building.bl_pubtrans_stop, 
					 tx_civserv_building.bl_pubtrans_url,
					 tx_civserv_building.bl_citymap_url',
					'tx_civserv_organisation',
					'tx_civserv_organisation_or_building_to_show_mm', //in this place we check if a 'building-to-show' has been selected
					'tx_civserv_building',
					'AND tx_civserv_organisation.deleted=0 AND tx_civserv_organisation.hidden=0
					 AND tx_civserv_building.deleted=0 AND tx_civserv_building.hidden=0
					 AND tx_civserv_organisation.uid = ' . $employees[$row_counter]['or_uid'],
					'',
					'',
					'');
				// hier können eine ganze Reihe (oder keine) Gebäude bei rauskommen!	
				while($row_building = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_building)){
					//store whole building in array
					$organisation_buildings[$orga_bl_count]=$row_building;
					$orga_bl_count++;
				}	
			
				// wenn bei der obigen Anfrage nichts rausgekommen ist, dann wurde keine 
				// bestimmten Gebäude für die Anzeige im FE vorselektiert
				// in dem Falle holen wir alle Gebäude zusammen, die überhaupt mit der Organisation verknüpft sind....
				if($orga_bl_count == 0){		
					$organisation_buildings= array();
					$res_building = $GLOBALS['TYPO3_DB']->exec_SELECT_mm_query(
						'tx_civserv_building.bl_mail_street, 
						 tx_civserv_building.bl_mail_pob, 
						 tx_civserv_building.bl_mail_postcode, 
						 tx_civserv_building.bl_mail_city, 
						 tx_civserv_building.bl_name, 
						 tx_civserv_building.bl_name_to_show,
						 tx_civserv_building.bl_building_street, 
						 tx_civserv_building.bl_building_postcode, 
						 tx_civserv_building.bl_building_city, 
						 tx_civserv_building.bl_pubtrans_stop, 
						 tx_civserv_building.bl_pubtrans_url,
						 tx_civserv_building.bl_citymap_url',
						'tx_civserv_organisation',
						'tx_civserv_organisation_or_building_mm', //die Original-Zuordnungstabelle!
						'tx_civserv_building',
						'AND tx_civserv_organisation.deleted=0 AND tx_civserv_organisation.hidden=0
						 AND tx_civserv_building.deleted=0 AND tx_civserv_building.hidden=0
						 AND tx_civserv_organisation.uid = ' . $employees[$row_counter]['or_uid'],
						'',
						'',
						'');
					while($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_building)){
						//store whole building in array
						$organisation_buildings[$orga_bl_count]=$row;
					}	
				} // orga_bl_count nach erstem durchlauf auf 0
				// jetzt sollten uns einige Gebäude zur Verfügung stehen....
				$or_bl_names=array();
				foreach($organisation_buildings as $bl_data){
					if ($bl_data['bl_name_to_show'] > ''){
						$or_bl_names[] = $bl_data['bl_name_to_show'];
					}else{
						$or_bl_names[] = $bl_data['bl_name'];
					}
				}
				$employees[$row_counter]['or_buildings'] = implode(', ', $or_bl_names);			
			}
			
			// spezielle Links zusammenbauen:
			// link zur employee-detail-seite
			$employees[$row_counter]['em_url'] = htmlspecialchars(
													$this->pi_linkTP_keepPIvars_url(array(
														'mode' => 'employee',
														'id' => $employees[$row_counter]['em_uid'],
														'pos_id' => $employees[$row_counter]['po_uid']
														),
													1,1)
												);
			
			// link zum email-client
			$employees[$row_counter]['email_code'] = "";
			if($employees[$row_counter]['ep_email'] > ''){
				$employees[$row_counter]['email_code'] = $this->cObj->typoLink($employees[$row_counter]['ep_email'],array(parameter => $employees[$row_counter]['ep_email'],ATagParams => 'class="email"'));
			}else{
				$employees[$row_counter]['email_code'] = $this->cObj->typoLink($employees[$row_counter]['em_email'],array(parameter => $employees[$row_counter]['em_email'],ATagParams => 'class="email"'));
			}
			
			$row_counter++;
		} // ende schleife über alle mitarbeiter
		
		// in pi3 irrelevant!
		foreach($kills as $kill){
			unset($employees[$kill]);
		}


		// Retrieve the employee count, take care of pi_list_browseresult, 
		// 2nd Parameter false for: no_limit
		// 3rd Parameter true for: select count(*)
		$row_count = 0;
		if($this->piVars['mode'] == 'employee_list_az'){
			$query = $this->makeEmployeeListQueryAZ($this->piVars['char'],false,true);
		}elseif($this->piVars['mode'] == 'employee_list_orcode'){
			$query = $this->makeEmployeeListQueryOrCode($this->piVars['orcode'],false,true);
		}


		$res = $GLOBALS['TYPO3_DB']->sql(TYPO3_db,$query);
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			$row_count += $row['count(*)'];
		}

		$this->internal['res_count'] = $row_count;
		$this->internal['results_at_a_time']= $this->conf['employee_per_page'];
		$this->internal['maxPages'] = $this->conf['max_pages_in_pagebar'];
		
		
		

		$smartyEmployeeList->assign('heading', str_replace('###MODE###', $mode_text, $this->pi_getLL('tx_civserv_pi3_employee_list.heading', 'Employees '.$mode_text)));
		$smartyEmployeeList->assign('subheading',$this->pi_getLL('tx_civserv_pi3_employee_list.available_employees','Here you find the following employees'));
		$smartyEmployeeList->assign('pagebar',$this->pi_list_browseresults(true, '', ' '.$this->conf['abcSpacer'].' '));
		$smartyEmployeeList->assign('employees',$employees);


		//take care of abc / orCode - BAR
		if ($this->piVars['mode'] == 'employee_list_az') {
			$query = $this->makeEmployeeListQueryAZ('all', false);
			$smartyEmployeeList->assign('abcbar', $this->makeAbcBar($query, 'nach Ämtern', 'employee_list_orcode'));
		}elseif($this->piVars['mode'] == 'employee_list_orcode') {
			$query = $this->makeEmployeeListQueryOrCode('all', false);
			$smartyEmployeeList->assign('orCodeBar', $this->makeOrCodeBar($query, 'nach Name', 'employee_list_az'));
		}
		
		if ($searchBox) {
			//$_SERVER['REQUEST_URI'] = $this->pi_linkTP_keepPIvars_url(array('mode' => 'search_result'),0,1); //dropped this according to instructions from security review
			$smartyEmployeeList->assign('searchbox', $this->pi_list_searchBox('', true));
		}
		
		if ($topList) {
			if (!$this->calculate_top15($smartyEmployeeList,false,$this->conf['topCount'])) {
				return false;
			}
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
	function makeEmployeeListQueryAZ($char=all,$limit=true,$count=false) {
			if ($char != all) {
				$regexp = $this->buildRegexp($char);
			}
			if ($count){
				$query = 'Select count(*) 
					from 
						tx_civserv_employee, 
						tx_civserv_position, 
						tx_civserv_employee_em_position_mm 
					where 
						tx_civserv_employee.pid IN (' . $this->community['pidlist'] . ') AND 
						tx_civserv_employee.uid = tx_civserv_employee_em_position_mm.uid_local AND 
						tx_civserv_employee_em_position_mm.uid_foreign = tx_civserv_position.uid AND
						tx_civserv_employee.deleted=0 AND
						tx_civserv_employee.hidden=0 '.
						($regexp?'AND em_name REGEXP "' . $regexp . '"':'') . ' ';
			} else {
				$query = 'Select 
						tx_civserv_employee.em_address, 
						tx_civserv_employee.em_title, 
						tx_civserv_employee.em_name as name, 
						tx_civserv_employee.em_firstname, 
						tx_civserv_employee.em_telephone,		 	 	 	 	 	 	 
 						tx_civserv_employee.em_fax, 	 	 	 	 	 	 
 						tx_civserv_employee.em_mobile,		 	 	 	 	 	 	 
 						tx_civserv_employee.em_email,		 	 	 	 	 	 	 
 						tx_civserv_employee.em_image,
						tx_civserv_employee.em_datasec,
						tx_civserv_employee.uid as em_uid, 
						tx_civserv_position.uid as po_uid,
						tx_civserv_position.po_name,
						tx_civserv_position.po_descr,
						tx_civserv_employee_em_position_mm.uid as ep_uid,
						tx_civserv_employee_em_position_mm.ep_officehours,
						tx_civserv_employee_em_position_mm.ep_room,
						tx_civserv_employee_em_position_mm.ep_telephone,
						tx_civserv_employee_em_position_mm.ep_fax,
						tx_civserv_employee_em_position_mm.ep_mobile,
						tx_civserv_employee_em_position_mm.ep_email,
						tx_civserv_employee_em_position_mm.ep_datasec,
						tx_civserv_employee_em_position_mm.ep_label
					from 
						tx_civserv_employee, 
						tx_civserv_position, 
						tx_civserv_employee_em_position_mm 
					where 
						tx_civserv_employee.pid IN (' . $this->community['pidlist'] . ') '
					    . ($regexp?'AND em_name REGEXP "' . $regexp . '"':'') . 'AND 
						tx_civserv_employee.uid = tx_civserv_employee_em_position_mm.uid_local AND 
						tx_civserv_employee_em_position_mm.uid_foreign = tx_civserv_position.uid AND
						tx_civserv_employee.deleted = 0 AND
						tx_civserv_employee.hidden = 0 AND
						tx_civserv_employee.em_pseudo = 0 ';
			}

			$orderby =	$this->piVars[sort]?'name, em_firstname DESC':'name, em_firstname ASC';


			if (!$count) {
				$orderby =	$this->piVars[sort]?'name, em_firstname DESC':'name, em_firstname ASC';
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
	 * Generates a database query for the function employee_list. The returned query depends on the given parameter (like described below)
	 * and the piVars 'char' and 'pointer', additionally the pidlist for the actual community is fetched from the class variable community.
	 *
	 * @param	[type]		$char: ...
	 * @param	[type]		$limit: ...
	 * @param	[type]		$count: ...
	 * @return	[type]		...
	 */
	function makeEmployeeListQueryOrCode($orcode='all', $limit=true, $count=false) { //do not put the orcode in quotation marks!!
			if($orcode == 'hod'){ // head-of-Department
				$fields =  'tx_civserv_organisation.uid as or_uid, 
						 	tx_civserv_organisation.or_name as organisation,
						 	tx_civserv_organisation.or_code,
						 	tx_civserv_organisation.or_synonym1,
						 	tx_civserv_organisation.or_synonym2,
						 	tx_civserv_organisation.or_synonym3,
						 	tx_civserv_organisation.or_supervisor,
						 	tx_civserv_organisation.or_show_supervisor,
						 	tx_civserv_organisation.or_hours,
						 	tx_civserv_organisation.or_telephone,
						 	tx_civserv_organisation.or_fax,
						 	tx_civserv_organisation.or_email,
						 	tx_civserv_organisation.or_image,
						 	tx_civserv_organisation.or_infopage,
						 	tx_civserv_organisation.or_addinfo,
						 	tx_civserv_organisation.or_addlocation,
							tx_civserv_employee.em_address, 
							tx_civserv_employee.em_title, 
							tx_civserv_employee.em_name as name, 
							tx_civserv_employee.em_firstname, 
							tx_civserv_employee.em_telephone,		 	 	 	 	 	 	 
							tx_civserv_employee.em_fax, 	 	 	 	 	 	 
							tx_civserv_employee.em_mobile,		 	 	 	 	 	 	 
							tx_civserv_employee.em_email,		 	 	 	 	 	 	 
							tx_civserv_employee.em_image,
							tx_civserv_employee.em_datasec,
							tx_civserv_employee.uid as em_uid';
				$tables =  'tx_civserv_employee, 
							tx_civserv_organisation';
						
				$conditions =  'tx_civserv_employee.pid IN (' . $this->community['pidlist'] . ') AND 
								tx_civserv_employee.uid = tx_civserv_organisation.or_supervisor AND
								tx_civserv_organisation.deleted = 0 AND
								tx_civserv_organisation.hidden = 0 AND						
								tx_civserv_employee.deleted = 0 AND
								tx_civserv_employee.hidden = 0 AND	
								tx_civserv_employee.em_pseudo = 0 ';
			}else{
				$fields =  'tx_civserv_organisation.uid as or_uid, 
							tx_civserv_organisation.or_code,
							tx_civserv_organisation.or_name,
							tx_civserv_employee.em_address, 
							tx_civserv_employee.em_title, 
							tx_civserv_employee.em_name as name, 
							tx_civserv_employee.em_firstname, 
							tx_civserv_employee.em_telephone,		 	 	 	 	 	 	 
							tx_civserv_employee.em_fax, 	 	 	 	 	 	 
							tx_civserv_employee.em_mobile,		 	 	 	 	 	 	 
							tx_civserv_employee.em_email,		 	 	 	 	 	 	 
							tx_civserv_employee.em_image,
							tx_civserv_employee.em_datasec,
							tx_civserv_employee.uid as em_uid, 
							tx_civserv_position.uid as po_uid,
							tx_civserv_position.po_name,
							tx_civserv_position.po_descr,
							tx_civserv_employee_em_position_mm.uid as ep_uid,
							tx_civserv_employee_em_position_mm.ep_officehours,
							tx_civserv_employee_em_position_mm.ep_room,
							tx_civserv_employee_em_position_mm.ep_telephone,
							tx_civserv_employee_em_position_mm.ep_fax,
							tx_civserv_employee_em_position_mm.ep_mobile,
							tx_civserv_employee_em_position_mm.ep_email,
							tx_civserv_employee_em_position_mm.ep_datasec,
							tx_civserv_employee_em_position_mm.ep_label';
				
				$tables =  'tx_civserv_employee, 
							tx_civserv_position, 
							tx_civserv_employee_em_position_mm,
							tx_civserv_organisation,
							tx_civserv_position_po_organisation_mm';
							
				$conditions =   'tx_civserv_employee.pid IN (' . $this->community['pidlist'] . ') AND 
								tx_civserv_employee.uid = tx_civserv_employee_em_position_mm.uid_local AND 
								tx_civserv_employee_em_position_mm.uid_foreign = tx_civserv_position.uid AND
								tx_civserv_position_po_organisation_mm.uid_local = tx_civserv_position.uid AND
								tx_civserv_position_po_organisation_mm.uid_foreign = tx_civserv_organisation.uid AND '.
								($orcode > '' && $orcode != 'all' ? 'tx_civserv_organisation.or_code in (\''.$orcode.'\', \''.str_replace('_', ' ', $orcode).'\') AND ' : '').
							   'tx_civserv_organisation.deleted = 0 AND
								tx_civserv_organisation.hidden = 0 AND						
								tx_civserv_position.deleted = 0 AND
								tx_civserv_position.hidden = 0 AND
								tx_civserv_employee.deleted = 0 AND
								tx_civserv_employee.hidden = 0';		
				}								
			if ($count){
				$or_code = $GLOBALS['TYPO3_DB']->quoteStr($or_code, 'tx_civserv_organisation');
				$query = 'Select count(*) from '.$tables.' where '.$conditions;
			} else {
				$query = 'SELECT '.$fields.' FROM '.$tables.' WHERE '.$conditions;
			}
#			$orderby =	$this->piVars[sort]?'or_code, name, em_firstname DESC':'or_code, name, em_firstname ASC';

			if (!$count) {
				if(orcode == 'hod'){
					$orderby =	$this->piVars[sort] ? 'name, em_firstname, or_code DESC' : 'name, em_firstname, or_code ASC';
					$query .= ' ORDER BY ' . $orderby . ' ';
				}else{
					$orderby =	$this->piVars[sort] ? 'or_code, name, em_firstname DESC' : 'or_code, name, em_firstname ASC';
					$query .= ' ORDER BY ' . $orderby . ' ';
				}


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




	/******************************
	 *
	 * Heplper functions for the navigation functions:
	 *
	 *******************************/
	 


	/**
	 * Builds a bar with all characters from the alphabet and an last item 'A-Z'. If a special character has to be active (if it contains items),
	 * is determined from the result set of the given query. The link for each character is build by adding piVars['char'] to the actual url.
	 * Used by the functions 'serviceList' and 'formList'.
	 *
	 * @param	string		A query which gets all items.
	 * @return	string		HTML-Code for abc-bar.
	 */
	 
	// test bk: add local_mode 
	// this is default function for rendering of ABC-bar
	function makeAbcBar($query, $text="", $additional_mode="") {
		// getting all accouring initial from the DB
		$res = $GLOBALS['TYPO3_DB']->sql(TYPO3_db,$query);
		
		$row_counter = 0;
		

		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res))	{
			if($this->previewMode && ($this->piVars['mode']=='service_list' || $this->piVars['mode']=='service')) {
				$namefield_arr = $row['sv_name'] ; //in previewMode we skip the synonyms of services! because the overlay-function can't handle aliases
			} else {
				$namefield_arr = $row['name'];
			}
			$initial = str_replace(array('Ä','Ö','Ü'),array('A','O','U'),strtoupper($namefield_arr{0}));
			$occuringInitials[] = $initial;
			$row_counter++;
		}
		if ($occuringInitials ) $occuringInitials = array_unique($occuringInitials);
		

		$alphabet = array(A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z);

		// build a string with the links to the character-sites
		$abcBar =  '<p id="abcbar">' . "\n\t";
		for($i = 0; $i < sizeof($alphabet); $i++)	{
			$thepivar = strtoupper($this->piVars['char']);
			$actual = (strtoupper($this->piVars['char']) == $alphabet[$i]);
			if($occuringInitials && in_array($alphabet[$i], $occuringInitials))	{
				$abcBar .= sprintf(	'%s' . 
									$this->pi_linkTP_keepPIvars(
											$alphabet[$i],	
											array(
												'char' => $alphabet[$i], 
												'pointer' => 0, 
												'mode' => 'employee_list_az'
											),
											1,
											0
									) . 
									'%s ' . 
									$this->conf['abcSpacer'].' ',
									$actual ? '<strong>' : '',
									$actual ? '</strong>' : ''
									);
			}
			else	{
				#$abcBar .= $alphabet[$i].' '.$this->conf['abcSpacer'].' ';
				$abcBar .= '<span class="nomatch">'.$alphabet[$i].'</span> '.$this->conf['abcSpacer'].' ';
			}
		}

		// adding the link 'A-Z'
		$actual = ($this->piVars['char'] <= '');
		$linkconf = array();
		$url = $this->pi_linkTP_keepPIvars_url(array(char => '', pointer => 0, mode => 'employee_list_az'),1,0);
		$linkconf['ATagParams'] =' class="all"';
		$linkconf['parameter'] = $url;
		$abcBar .= 	sprintf(	'%s' .	
								$this->local_cObj->typoLink('A-Z', $linkconf) .
								'%s' . "\n",
								$actual?'<strong>':'',
								$actual?'</strong>':''
							);

		
		/*
		$abcBar .= sprintf('%s' . $this->pi_linkTP_keepPIvars('A-Z',array(char => '', pointer => 0, mode => 'employee_list_az'),1,0) . '%s' . "\n",
						$actual?'<strong>':'',
						$actual?'</strong>':'');
		*/				
		
		$abcBar .= "</p>\n";
		
		// adding additional_mode Link (in this case: link for filtering the employeelist by organisation)
		if($additional_mode > ''){
			$abcBar .= "<p>".$this->make_mode_link($text, $additional_mode)."</p>\n";
		}				

		return $abcBar;
	}



	
	function makeOrCodeBar($query, $text="", $additional_mode = "") {
		
		// get all organisations which have any positions
		$res = $GLOBALS['TYPO3_DB']->sql(TYPO3_db,$query);
		
		$row_counter = 0;
		
		// the resultset contains any organisation, that has a relation to one or more positions
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)){
			if($row['or_code'] > ''){
				$namefield_arr = $row['or_code'];
				
	#			$orcode = str_replace(array('Ä','Ö','Ü'), array('A','O','U'), strtoupper($namefield_arr{0}));
	#			$occuringCodes[] = $orcode;
	
	
	#			$occuringCodes[] = ucfirst($namefield_arr);
				$occuringCodes[] = (string)str_replace(' ', '_', strtoupper($namefield_arr));
	
				$row_counter++;
			}else{
				//do nothing
			}
		}
		if ($occuringCodes ) $occuringCodes = array_unique($occuringCodes);
		

		// get all organisations which exist (no matter if they do or don't harbour any positions)
		$orCodeArray = array();
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'or_code',
			'tx_civserv_organisation',
			'tx_civserv_organisation.pid IN (' . $this->community['pidlist'] . ') 
			 AND tx_civserv_organisation.deleted = 0 
			 AND tx_civserv_organisation.hidden = 0
			 AND tx_civserv_organisation.or_code > \'\'',
			'',
			'or_code',
			'');
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			$orCodeArray[] = str_replace(' ', '_', strtoupper($row['or_code']));
		}

		// build a string with the links to the orcode-sites
		$orcodeBar =  '<p id="orcodebar">' . "\n\t";
		for($i = 0; $i < sizeof($orCodeArray); $i++)	{
			// === !!! or else will take 001 and 01 for equal....
			$actual = (strtoupper($this->piVars['orcode']) === strtoupper($orCodeArray[$i])); //flag, true or false
			if ($occuringCodes && in_array((string)$orCodeArray[$i], $occuringCodes, true)){ //true for check on data-type string! or else will set '001' and '01' equal

				$orcodeBar .= sprintf(	'%s' . 
										$this->pi_linkTP_keepPIvars(
													$this->replace_umlauts($orCodeArray[$i]), 
													array(	'orcode' => $this->replace_umlauts($orCodeArray[$i]), 
															'pointer' => 0, 
															'mode' => 'employee_list_orcode'),
													1,
													0
													) . 
										'%s ' . 
										$this->conf['abcSpacer']. ' ',
										$actual ? '<strong>' : '',
										$actual ? '</strong>' : ''
										);
				
			}
			else {
				$orcodeBar .=  '<span class="empty">'.$orCodeArray[$i].'</span> '.$this->conf['abcSpacer'].' ';
			}
		}

		// adding the link 'all'
		$actual = ($this->piVars['orcode'] <= '' || $this->piVars['orcode'] == 'all' );
		
		$linkconf = array();
		$url = $this->pi_linkTP_keepPIvars_url(array(orcode => 'all', pointer => 0, mode => 'employee_list_orcode'), 1, 0);
		$linkconf['ATagParams'] =' class="all"';
		$linkconf['parameter'] = $url;
		$orcodeBar .= 	sprintf(	'%s' .	
									$this->local_cObj->typoLink($this->pi_getLL('tx_civserv_pi3_employee_list.orcode_all'), $linkconf) .
									'%s' . "\n",
									$actual?'<strong>':'',
									$actual?'</strong>':''
							);
		
		
		
		/*
		$orcodeBar .= sprintf('%s' . $this->pi_linkTP_keepPIvars($this->pi_getLL('tx_civserv_pi3_employee_list.orcode_all', 'all'), array(orcode => 'all', pointer => 0, mode => 'employee_list_orcode'),1,0) . '%s' . "\n",
						$actual ? '<strong>' : '',
						$actual ? '</strong>' : '');
		*/						
						
		// adding link for head of Departmenst
		$actual = ($this->piVars['orcode'] == 'hod' );
		$orcodeBar .= sprintf('%s' . $this->pi_linkTP_keepPIvars($this->pi_getLL('tx_civserv_pi3_employee_list.orcode_hod', 'headofdepartment'), array(orcode => 'hod', pointer => 0, mode => 'employee_list_orcode'),1,0) . '%s' . "\n",
						$actual ? '<strong>' : '',
						$actual ? '</strong>' : '');
						
		$orcodeBar .= "</p>\n";

		
		// adding additional_mode Link (in this case: link for filtering the employeelist by employee-name)
		if($additional_mode > ''){
			$orcodeBar .= "<p>".$this->make_mode_link($text, $additional_mode)."</p>";
		}				
		return $orcodeBar;
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
	 * Build link with a different mode.....
	 * In oder to use this function with the abc-bar, umlauts are treated like the corrospendent vocals.
	 * Used by the functions 'serviceList' and 'formList'.
	 *
	 * @param	string		The charavter or string the alement should begin with
	 * @return	string		The regular expression.
	 */
	 function make_mode_link($text, $mode) {
		$link .=  $this->pi_linkTP_keepPIvars($text, array('mode' => $mode),0,0,0);
		return $link;
	}





	/******************************
	 *
	 * Functions for the detail pages (service, employee, organisation):
	 *
	 *******************************/



	/**
	 * Generates information about a specific employee.
	 *
	 * @param	object		Smarty object, the template key/value-pairs should be assigned to
	 * @param	boolean		If true, a searchbox is generated (keyword search)
	 * @return	boolean		True, if the function was executed without any error, otherwise false
	 */
	function employeeDetail(&$smartyEmployee,$searchBox) {
		$uid = intval($this->piVars[id]);	//SQL-Injection!!!
		$pos_id = intval($this->piVars[pos_id]);

		//Standard query for employee details
		$res_common = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
						'uid, 
						 em_address, 
						 em_title, 
						 em_name, 
						 em_firstname, 
						 em_telephone, 
						 em_fax, 
						 em_email, 
						 em_image, 
						 em_datasec',
						'tx_civserv_employee',
						'deleted=0 AND hidden=0 AND uid='.$uid.' AND em_datasec=1',
						'',
						'',
						'');

		//Check if data security option is enabled
		if ($GLOBALS['TYPO3_DB']->sql_num_rows($res_common) == 0) {
			$GLOBALS['error_message'] = $this->pi_getLL('tx_civserv_pi3_employee.datasec','Datasec enabled! Employee is not shown.');
			return false;
		}

		//Query for employee office hours
		$res_emp_hours = $GLOBALS['TYPO3_DB']->exec_SELECT_mm_query(
					'oh_start_morning, 
					 oh_end_morning, 
					 oh_start_afternoon, 
					 oh_end_afternoon, 
					 oh_freestyle, 
					 oh_weekday',
					'tx_civserv_employee',
					'tx_civserv_employee_em_hours_mm',
					'tx_civserv_officehours',
					'AND tx_civserv_employee.deleted=0 AND tx_civserv_employee.hidden=0
					 AND tx_civserv_officehours.deleted=0 AND tx_civserv_officehours.hidden=0
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
					'tx_civserv_employee.deleted=0 AND tx_civserv_employee.hidden=0
					 AND tx_civserv_position.deleted=0 AND tx_civserv_position.hidden=0
					 AND tx_civserv_officehours.deleted=0 AND tx_civserv_officehours.hidden=0
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
					'tx_civserv_organisation.deleted=0 AND tx_civserv_organisation.hidden=0
					 AND tx_civserv_officehours.deleted=0 AND tx_civserv_officehours.hidden=0
					 AND tx_civserv_position.deleted=0 AND tx_civserv_organisation.hidden=0
					 AND tx_civserv_employee.deleted=0 AND tx_civserv_officehours.hidden=0
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
			
			
			//test bk: single out one building for the organisation / employee
			//todo!


			//Query for organisation, building, floor and room (depending on position of employee)
			$res_position = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
					'tx_civserv_position.uid as po_uid, 
					 tx_civserv_organisation.uid as or_uid, 
					 tx_civserv_employee.uid as em_uid, 
					 po_name as position, 
					 bl_name as building, 
					 bl_name_to_show as building_to_show,
					 fl_descr as floor, 
					 ro_name as room, 
					 ep_telephone as phone, 
					 ep_fax as fax, 
					 ep_email as email, 
					 or_name as organisation',
					'tx_civserv_employee, tx_civserv_position, tx_civserv_room, tx_civserv_floor, tx_civserv_organisation, tx_civserv_building, tx_civserv_employee_em_position_mm, tx_civserv_building_bl_floor_mm, tx_civserv_position_po_organisation_mm',
					'tx_civserv_employee.uid='.$uid.' 
					 AND em_datasec=1 
					 AND tx_civserv_position.uid = '.$pos_id.'
					 AND tx_civserv_organisation.deleted=0 AND tx_civserv_organisation.hidden=0
					 AND tx_civserv_employee.deleted=0 AND tx_civserv_employee.hidden=0
					 AND tx_civserv_position.deleted=0 AND tx_civserv_position.hidden=0
					 AND tx_civserv_room.deleted=0 AND tx_civserv_room.hidden=0
					 AND tx_civserv_floor.deleted=0 AND tx_civserv_floor.hidden=0
					 AND tx_civserv_organisation.deleted=0 AND tx_civserv_organisation.hidden=0
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
			$employee_position['or_link'] = htmlspecialchars($this->pi_linkTP_keepPIvars_url(array('mode' => 'organisation', 'id' => $employee_position['or_uid']),1,1));
			if($employee_position['building_to_show'] > ''){
				$employee_position['building'] = $employee_position['building_to_show'];
			}
			$smartyEmployee->assign('position',$employee_position);
	
			//Assign employee-position working hours
			$row_counter = 0;
			while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_emp_pos_hours) ){	
				$emp_pos_hours[$row_counter]['weekday'] = $this->pi_getLL('tx_civserv_pi3_weekday_'.$row[oh_weekday]);
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
			while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_emp_org_hours) ){	
				$emp_org_hours[$row_counter]['weekday'] = $this->pi_getLL('tx_civserv_pi3_weekday_'.$row[oh_weekday]);
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
			$smartyEmployee->assign('address',$this->pi_getLL('tx_civserv_pi3_organisation.address_female','Mrs.'));
		} else if (intval($employee_rows[em_address]) == 1) {
			$smartyEmployee->assign('address',$this->pi_getLL('tx_civserv_pi3_organisation.address_male','Mr.'));
		}
		$smartyEmployee->assign('firstname',$employee_rows[em_firstname]);
		$smartyEmployee->assign('name',$employee_rows[em_name]);
		$smartyEmployee->assign('phone',$employee_rows[em_telephone]);
		$smartyEmployee->assign('fax',$employee_rows[em_fax]);
		$smartyEmployee->assign('image',$imageCode);

		// Assign email data
		// use typolink, because of the possibility to use encrypted email-adresses for spam-protection
		if ($employee_position[email] != '') {
			$email_form_url = htmlspecialchars($this->pi_linkTP_keepPIvars_url(array('mode' => 'set_email_form',id => $employee_position['em_uid'],pos_id => $employee_position['po_uid']),1,1));
			$email_code = $this->cObj->typoLink($employee_position['email'],array(parameter => $employee_position['email'],ATagParams => 'class="email"'));
		} elseif ($employee_rows[em_email] != '') {
			$email_form_url = htmlspecialchars($this->pi_linkTP_keepPIvars_url(array('mode' => 'set_email_form',id => $employee_rows['uid']),1,1));
			$email_code = $this->cObj->typoLink($employee_rows[em_email],array(parameter => $employee_rows[em_email],ATagParams => 'class="email"'));
		}
		$smartyEmployee->assign('email_form_url',$email_form_url);
		$smartyEmployee->assign('email_code',$email_code);

		//Assign employee working hours
		$row_counter = 0;
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_emp_hours) ){
			$emp_hours[$row_counter]['weekday'] = $this->pi_getLL('tx_civserv_pi3_weekday_'.$row[oh_weekday]);
			$emp_hours[$row_counter]['start_morning'] = $row[oh_start_morning];
			$emp_hours[$row_counter]['end_morning'] = $row[oh_end_morning];
			$emp_hours[$row_counter]['start_afternoon'] = $row[oh_start_afternoon];
			$emp_hours[$row_counter]['end_afternoon'] = $row[oh_end_afternoon];
			$emp_hours[$row_counter]['freestyle'] = $row[oh_freestyle];
			$row_counter++;
		}
		$smartyEmployee->assign('emp_hours',$emp_hours);

		//Assign template labels
		if (intval($employee_rows[em_address]) == 2) {
			$smartyEmployee->assign('employee_label',$this->pi_getLL('tx_civserv_pi3_employee.employee_female','Employee'));
		} else{ //1 for male or nothing
			$smartyEmployee->assign('employee_label',$this->pi_getLL('tx_civserv_pi3_employee.employee_male','Employee'));
		}	
		$smartyEmployee->assign('phone_label',$this->pi_getLL('tx_civserv_pi3_organisation.phone','Phone'));
		$smartyEmployee->assign('fax_label',$this->pi_getLL('tx_civserv_pi3_organisation.fax','Fax'));
		$smartyEmployee->assign('email_label',$this->pi_getLL('tx_civserv_pi3_organisation.email','E-Mail'));
		$smartyEmployee->assign('web_email_label',$this->pi_getLL('tx_civserv_pi3_organisation.web_email','E-Mail-Form'));
		$smartyEmployee->assign('working_hours_label',$this->pi_getLL('tx_civserv_pi3_employee.hours','Working hours'));
		$smartyEmployee->assign('office_hours_summary',str_replace('###EMPLOYEE###',$employee_rows[em_firstname] . ' ' . $employee_rows[em_name],$this->pi_getLL('tx_civserv_pi3_employee.officehours','In the table are the office hours of ###EMPLOYEE### shown.')));
		if($this->conf['showOhLabels']){
			//default
		}else{
			$smartyEmployee->assign('supress_labels', 'invisible');
		}
		$smartyEmployee->assign('weekday',$this->pi_getLL('tx_civserv_pi3_weekday','Weekday'));
		$smartyEmployee->assign('morning',$this->pi_getLL('tx_civserv_pi3_organisation.morning','mornings'));
		$smartyEmployee->assign('afternoon',$this->pi_getLL('tx_civserv_pi3_organisation.afternoon','in the afternoon'));

		$smartyEmployee->assign('organisation_label',$this->pi_getLL('tx_civserv_pi3_employee.organisation','Organisation'));
		$smartyEmployee->assign('room_label',$this->pi_getLL('tx_civserv_pi3_employee.room','Room'));
		//the image_employee_label is not being used yet
		if (intval($employee_rows[em_address]) == 2) {
			$smartyEmployee->assign('image_employee_label',$this->pi_getLL('tx_civserv_pi3_employee_female.image','Image of employee'));
		} else if (intval($employee_rows[em_address]) == 1) {
			$smartyEmployee->assign('image_employee_label',$this->pi_getLL('tx_civserv_pi3_employee_male.image','Image of employee'));
		}
		$smartyEmployee->assign('backlink', $this->pi_linkTP(	$this->pi_getLL('tx_civserv_pi3_organisation.backlink','backlink'), 
																	array(	$this->prefixId . '[mode]' => $_SESSION['stored_mode'], 
																			$this->prefixId . '[community_id]' => $this->community['id'],
																			$this->prefixId . '['.$_SESSION['stored_filter_key'].']' => $_SESSION['stored_filter_val'],
																			$this->prefixId . '[pointer]' => 0
																	),
																	0,
																	0		
												)
									);



		
		if ($searchBox) {
			//$_SERVER['REQUEST_URI'] = $this->pi_linkTP_keepPIvars_url(array('mode' => 'search_result'),0,1); //dropped this according to instructions from security review
			$smartyTop15->assign('searchbox', $this->pi_list_searchBox('',true));
		}
		$GLOBALS['TSFE']->page['title']=$this->pi_getLL('tx_civserv_pi3_employee.employee_plural','Employees');
		return true;
	}


	/**
	 * Generates information about a specific organisation.
	 *
	 * @param	object		Smarty object, the template key/value-pairs should be assigned to
	 * @return	boolean		True, if the function was executed without any error, otherwise false
	 */
	 //test bk: add continueAbcBarFromOrganisationList
	function organisationDetail(&$smartyOrganisation,$continueAbcBarFromOrganisationList=false) {
		// test bk: add mode to parameterlist in function makeAbcBar
		if($continueAbcBarFromOrganisationList){
			$query = $this->makeOrganisationListQuery(all,false);
			$smartyOrganisation->assign('abcbarOrganisationList_continued', $this->makeAbcBar($query, 'organisation_list'));
		}
		$uid = intval($this->piVars[id]);	//SQL-Injection!!!

		// Standard query for organisation details
		// test bk: include or_show_supervisor
		$res_common = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
						'uid, 
						 or_name,
						 or_telephone,
						 or_fax,
						 or_email,
						 or_image,
						 or_infopage,
						 or_addinfo, 
						 or_addlocation,
						 or_show_supervisor',
						'tx_civserv_organisation',
						'deleted=0 AND hidden=0 AND uid='.$uid,
						'',
						'',
						'');

		//Query for supervisor of organisation
		$res_supervisor = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
						'tx_civserv_employee.uid as uid, em_title, em_name, em_firstname, em_address, em_datasec',
						'tx_civserv_organisation, tx_civserv_employee',
						'tx_civserv_organisation.deleted=0 AND tx_civserv_organisation.hidden=0
						 AND tx_civserv_employee.deleted=0 AND tx_civserv_employee.hidden=0
						 AND tx_civserv_organisation.or_supervisor = tx_civserv_employee.uid
						 AND tx_civserv_organisation.uid='.$uid,
						'',
						'',
						'');

		//Query for supervisor of organisation (depending on position)
		$res_pos_supervisor = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
						'tx_civserv_employee.uid as uid, tx_civserv_position.uid as po_uid, em_title, em_name, em_firstname, em_address, em_datasec',
						'tx_civserv_organisation, tx_civserv_employee, tx_civserv_position, tx_civserv_employee_em_position_mm, tx_civserv_position_po_organisation_mm',
						'tx_civserv_organisation.deleted=0 AND tx_civserv_organisation.hidden=0
						 AND tx_civserv_employee.deleted=0 AND tx_civserv_employee.hidden=0
						 AND tx_civserv_position.deleted=0 AND tx_civserv_position.hidden=0
						 AND tx_civserv_organisation.or_supervisor = tx_civserv_employee.uid
						 AND tx_civserv_employee.uid = tx_civserv_employee_em_position_mm.uid_local
						 AND tx_civserv_position.uid = tx_civserv_employee_em_position_mm.uid_foreign
						 AND tx_civserv_position.uid = tx_civserv_position_po_organisation_mm.uid_local
						 AND tx_civserv_organisation.uid = tx_civserv_position_po_organisation_mm.uid_foreign
						 AND tx_civserv_organisation.uid='.$uid,
						'',
						'uid',	//GROUP BY
						'');

		//Query for building(s) and postal address (there shouldn't be more than one building assigned to each organisation)
		// test bk: include bl_name
		$select_building='bl_mail_street, 
						 bl_mail_pob, 
						 bl_mail_postcode, 
						 bl_mail_city, 
						 bl_name, 
						 bl_name_to_show,
						 bl_building_street, 
						 bl_building_postcode, 
						 bl_building_city, 
						 bl_pubtrans_stop, 
						 bl_pubtrans_url,
						 bl_citymap_url';
		$res_building = $GLOBALS['TYPO3_DB']->exec_SELECT_mm_query(
						$select_building,
						'tx_civserv_organisation',
						'tx_civserv_organisation_or_building_mm',
						'tx_civserv_building',
						'AND tx_civserv_organisation.deleted=0 AND tx_civserv_organisation.hidden=0
						 AND tx_civserv_building.deleted=0 AND tx_civserv_building.hidden=0
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
						'AND tx_civserv_organisation.deleted=0 AND tx_civserv_organisation.hidden=0
						 AND tx_civserv_officehours.deleted=0 AND tx_civserv_officehours.hidden=0
						 AND tx_civserv_organisation.uid = ' . $uid,
						'',
						'oh_weekday',
						'');

		$organisation_rows = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_common);
		
		// test bk: query for sub_organisations, 
		// they will be exposed somewhere in the organisation_detail page
		$sub_organisations=array();
		$res_sub_org = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
						'uid, or_name',
						'tx_civserv_organisation, tx_civserv_organisation_or_structure_mm',
						'tx_civserv_organisation_or_structure_mm.uid_local = tx_civserv_organisation.uid and
						 tx_civserv_organisation_or_structure_mm.uid_foreign = '.$organisation_rows['uid'].' and
						 tx_civserv_organisation.deleted=0 and
						 tx_civserv_organisation.hidden=0',
						'',
						'tx_civserv_organisation.sorting', //Order by
						'');				

		$row_count_sub_orgs = 0;
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_sub_org))	{
			$sub_organisations[$row_count_sub_orgs]['link'] =  htmlspecialchars($this->pi_linkTP_keepPIvars_url(array('mode' => 'organisation',id => $row['uid']),1,1));
			$sub_organisations[$row_count_sub_orgs]['name'] = $row['or_name'];
			$row_count_sub_orgs++;
		}
		if($this->conf['showSubOrganisations'] && $row_count_sub_orgs>0)$smartyOrganisation->assign('sub_organisations',$sub_organisations);//!!!!
		
		// test bk: query for super_organisation, 
		// they will be exposed somewhere in the organisation_detail page
		$super_organisation=array();
		$res_super_org = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
						'uid, or_name',
						'tx_civserv_organisation, tx_civserv_organisation_or_structure_mm',
						'tx_civserv_organisation_or_structure_mm.uid_local = '.$organisation_rows['uid'].' and
						 tx_civserv_organisation_or_structure_mm.uid_foreign = tx_civserv_organisation.uid and
						 tx_civserv_organisation.uid!='.$this->community['organisation_uid'].' AND
						 tx_civserv_organisation.deleted=0 and
						 tx_civserv_organisation.hidden=0',
						'',
						'tx_civserv_organisation.sorting', //Order by
						'');				

		if ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_super_org))	{
			$super_organisation['link'] =  htmlspecialchars($this->pi_linkTP_keepPIvars_url(array('mode' => 'organisation',id => $row['uid']),1,1));
			$super_organisation['name'] = $row['or_name'];
		}
		if($this->conf['showSuperOrganisation'] && count($super_organisation)>0)$smartyOrganisation->assign('super_organisation',$super_organisation);//!!!!
		
		
		//test bk: make $organisation_buildings into an array!!!!
		$organisation_buildings= array();
		$orga_bl_count=0;
		while($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_building)){
			//store whole building in array
			$organisation_buildings[$orga_bl_count]=$row;
			//modify some values:
			//in case there are several buildings for the organisation, check if the mail_postcode is identical!
			if($orga_bl_count==0){
				$bl_mail_postcode=intval(trim($row['bl_mail_postcode']));
			}elseif($orga_bl_count>=1){
				$organisation_buildings[$orga_bl_count]['bl_mail_postcode']= $bl_mail_postcode==intval(trim($row['bl_mail_postcode']))?$row['bl_mail_postcode']:"";
			}
			//typofy the Link! this has to be done all over again below for the different cases (show building-of-or_supervisor, show building-selected-via-BE)
			$organisation_buildings[$orga_bl_count]['bl_pubtrans_link'] = $this->cObj->typoLink_URL(array(parameter => $row['bl_pubtrans_url']));
			$organisation_buildings[$orga_bl_count]['bl_citymap_link'] = $this->cObj->typoLink_URL(array(parameter => $row['bl_citymap_url']));
			$orga_bl_count++;
		}

		$pidListAll = $this->community['pidlist'];				//Get pidlist for current mandant
		$pidListAll = t3lib_div::intExplode(',',$pidListAll);	//Parse pidlist and store int values in array
		
		$smartyOrganisation->assign('or_infopage',$this->cObj->typoLink_URL(array(parameter => $organisation_rows['or_infopage'])));

		if ($GLOBALS['TYPO3_DB']->sql_num_rows($res_pos_supervisor) != 0) {
			$organisation_supervisor = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_pos_supervisor);
			$pos_id = $organisation_supervisor[po_uid];
		} else {
			$organisation_supervisor = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_supervisor);
			$pos_id = '';
		}
		
		//test bk:
		//if there is more than one building assigned to the organisation, try to single out the one where the supervisor 'lives'
		//this is the default behaviour with more than one buildings, for the exception see below
		if($pos_id > '' && $orga_bl_count > 1 ){
			$res_bl_supervisor = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
					$select_building,
					'tx_civserv_organisation, 
						tx_civserv_organisation_or_building_mm, 
						tx_civserv_building,
						tx_civserv_employee_em_position_mm,
						tx_civserv_room,
						tx_civserv_building_bl_floor_mm',
					'tx_civserv_organisation_or_building_mm.uid_local = tx_civserv_organisation.uid AND
						tx_civserv_organisation_or_building_mm.uid_foreign = tx_civserv_building.uid AND
						tx_civserv_organisation.deleted=0 AND 
						tx_civserv_organisation.hidden=0 AND 
						tx_civserv_building.deleted=0 AND 
						tx_civserv_building.hidden=0 AND 
						tx_civserv_employee_em_position_mm.deleted=0 AND 
						tx_civserv_employee_em_position_mm.hidden=0 AND 
						tx_civserv_organisation.or_supervisor = '.$organisation_supervisor['uid'].' AND
						tx_civserv_employee_em_position_mm.uid_foreign = '.$pos_id.' AND
						tx_civserv_employee_em_position_mm.ep_room = tx_civserv_room.uid AND
						tx_civserv_room.rbf_building_bl_floor =  tx_civserv_building_bl_floor_mm.uid AND
						tx_civserv_building.uid = tx_civserv_building_bl_floor_mm.uid_local',
					'',
					'',
					'');
			if($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_bl_supervisor)){ //there must not be more than 1 buildings in which the organisation-supervisor resides!
				$organisation_buildings[0] = $row;
				$organisation_buildings[0]['bl_pubtrans_link'] = $this->cObj->typoLink_URL(array(parameter => $row['bl_pubtrans_url']));
				$organisation_buildings[0]['bl_citymap_link'] = $this->cObj->typoLink_URL(array(parameter => $row['bl_citymap_url']));
				$organisation_buildings = array_slice ($organisation_buildings, 0, 1); 
			}
		}
		
		//EXCEPTION (controlled by conf['selectBuildingsToShow']
		//sometimes more than one building has to be published in FE
		//in this case check if any particular buildings have been selected and than reset the buildings for the organisation
		if($orga_bl_count > 1 && $this->conf['selectBuildingsToShow']){
			$res_building_temp = $GLOBALS['TYPO3_DB']->exec_SELECT_mm_query(
						$select_building,
						'tx_civserv_organisation',
						'tx_civserv_organisation_or_building_to_show_mm', //!!!
						'tx_civserv_building',
						'AND tx_civserv_organisation.deleted=0 AND tx_civserv_organisation.hidden=0
						 AND tx_civserv_building.deleted=0 AND tx_civserv_building.hidden=0
						 AND tx_civserv_organisation.uid = ' . $uid,
						'',
						'',
						'');
			$orga_bl_count_temp=0;
			$organisation_buildings_temp=array();
			while($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_building_temp)){
				$organisation_buildings_temp[$orga_bl_count_temp]=$row;
				$organisation_buildings_temp[$orga_bl_count_temp]['bl_pubtrans_link'] = $this->cObj->typoLink_URL(array(parameter => $row['bl_pubtrans_url']));
				$organisation_buildings_temp[$orga_bl_count_temp]['bl_citymap_link'] = $this->cObj->typoLink_URL(array(parameter => $row['bl_citymap_url']));
				$orga_bl_count_temp++;
			}
			if($orga_bl_count_temp > 0){
				$organisation_buildings = array();  //have to reset this!!!
				$organisation_buildings = $organisation_buildings_temp;
				if($orga_bl_count_temp > 1){
					//eleminate pubtrans etc.... 
					//evtl. you'll have to eleminate even more information referring to an individual building 
					//and therefore not valid in case of more than one building.....
					/*
					foreach($organisation_buildings as $building){
						$building[bl_pubtrans_stop]="";
						$buidling[bl_pubtrans_url]="";
					}
					*/
				}
			}else{
				//stick to the one building identified by the default procedure
				$organisation_buildings = array_slice ($organisation_buildings, 0, 1);   
			}
		}
		
		//Assign organisation office hours
		$row_counter = 0;
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_hour) ){	
			$organisation_hours[$row_counter]['weekday'] = $this->pi_getLL('tx_civserv_pi3_weekday_'.$row[oh_weekday]);
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
		$imageCode = $this->getImageCode($organisation_rows[or_image],$imagepath,$this->conf['organisation-image.'],$this->pi_getLL('tx_civserv_pi3_organisation.image','Image of organisation'));

		//Assign standard data
		// test bk: include or_addinfo
		// test bk: include or_title 
		
		$GLOBALS['TSFE']->page['title']=$organisation_rows[or_name];
		// test bk: münster - generate or_title from or_name (is only displayed in münster)
		$or_title=$organisation_rows[or_name];
		if($organisation_rows[or_addlocation]>'')$or_title.=' ('.$organisation_rows[or_addlocation].')';
		$smartyOrganisation->assign('or_title',$or_title);
		$smartyOrganisation->assign('or_name',$organisation_rows[or_name]);
		$smartyOrganisation->assign('or_addinfo',$organisation_rows[or_addinfo]);
		$smartyOrganisation->assign('or_phone',$organisation_rows[or_telephone]);
		$smartyOrganisation->assign('or_fax',$organisation_rows[or_fax]);
		$smartyOrganisation->assign('or_email_code',$this->cObj->typoLink($organisation_rows[or_email],array(parameter => $organisation_rows[or_email],ATagParams => 'class="email"'))); 	// use typolink, because of the possibility to use encrypted email-adresses for spam-protection
		$smartyOrganisation->assign('or_email_form_url',htmlspecialchars($this->pi_linkTP_keepPIvars_url(array('mode' => 'set_email_form',org_id => $organisation_rows[uid]),1,1)));
		$smartyOrganisation->assign('or_image',$imageCode);

		//Assign employee data
		// test bk: do not show the organisationSupervisor at all - depending on a flag in the organisation-table
		if ($organisation_rows[or_show_supervisor]) {
			$smartyOrganisation->assign('su_title',$organisation_supervisor[em_title]);
			$smartyOrganisation->assign('su_firstname',$organisation_supervisor[em_firstname]);
			$smartyOrganisation->assign('su_name',$organisation_supervisor[em_name]);
			if (intval($organisation_supervisor[em_datasec]) == 1) {
				if ($pos_id != '') {
					$smartyOrganisation->assign('su_link',htmlspecialchars($this->pi_linkTP_keepPIvars_url(array('mode' => 'employee',id => $organisation_supervisor[uid],pos_id => $pos_id),1,1)));
				} else {
					$smartyOrganisation->assign('su_link',htmlspecialchars($this->pi_linkTP_keepPIvars_url(array('mode' => 'employee',id => $organisation_supervisor[uid]),1,1)));
				}
			}
		}

		//Assign addresses
		// test bk: include bl_name
		$smartyOrganisation->assign('bl_available',$bl_available=$orga_bl_count>0? 1:0);
		$smartyOrganisation->assign('buildings',$organisation_buildings);
		
		//Assign template labels
		$smartyOrganisation->assign('organisation_label',$this->pi_getLL('tx_civserv_pi3_organisation.organisation','Organisation'));
		$smartyOrganisation->assign('sub_org_label',$this->pi_getLL('tx_civserv_pi3_organisation.sub_org_label','You can also visit us here:'));
		$smartyOrganisation->assign('super_org_label',$this->pi_getLL('tx_civserv_pi3_organisation.super_org_label','next higher organisation level:'));
		$smartyOrganisation->assign('postal_address_label',$this->pi_getLL('tx_civserv_pi3_organisation.postal_address','Postal address'));
		$smartyOrganisation->assign('building_address_label',$this->pi_getLL('tx_civserv_pi3_organisation.building_address','Building address'));
		$smartyOrganisation->assign('phone_label',$this->pi_getLL('tx_civserv_pi3_organisation.phone','Phone'));
		$smartyOrganisation->assign('fax_label',$this->pi_getLL('tx_civserv_pi3_organisation.fax','Fax'));
		$smartyOrganisation->assign('email_label',$this->pi_getLL('tx_civserv_pi3_organisation.email','E-Mail'));
		$smartyOrganisation->assign('web_email_label',$this->pi_getLL('tx_civserv_pi3_organisation.web_email','E-Mail-Form'));
		$smartyOrganisation->assign('office_hours_label',$this->pi_getLL('tx_civserv_pi3_organisation.office_hours','Office hours'));
		$smartyOrganisation->assign('supervisor_label',$this->pi_getLL('tx_civserv_pi3_organisation.supervisor','Supervisor'));
		$smartyOrganisation->assign('employee_details',$this->pi_getLL('tx_civserv_pi3_organisation.employee_details','Jumps to a page with details of this employee'));
		$smartyOrganisation->assign('office_hours_summary',str_replace('###ORGANISATION###',$organisation_rows[or_name],$this->pi_getLL('tx_civserv_pi3_organisation.officehours','In the table are the office hours of ###ORGANISATION### shown.')));
		if($this->conf['showOhLabels']){
			//default
		}else{
			$smartyOrganisation->assign('supress_labels', 'invisible');
		}			
		$smartyOrganisation->assign('weekday',$this->pi_getLL('tx_civserv_pi3_weekday','Weekday'));
		$smartyOrganisation->assign('morning',$this->pi_getLL('tx_civserv_pi3_organisation.morning','in the morning'));
		$smartyOrganisation->assign('afternoon',$this->pi_getLL('tx_civserv_pi3_organisation.afternoon','in the afternoon'));

		if (intval($organisation_supervisor[em_address]) == 2) {
			$smartyOrganisation->assign('su_address_label',$this->pi_getLL('tx_civserv_pi3_organisation.address_female','Mrs.'));
		} else if (intval($organisation_supervisor[em_address]) == 1) {
			$smartyOrganisation->assign('su_address_label',$this->pi_getLL('tx_civserv_pi3_organisation.address_male','Mr.'));
		}
		$smartyOrganisation->assign('postbox_label',$this->pi_getLL('tx_civserv_pi3_organisation.postbox','Postbox'));
		$smartyOrganisation->assign('pub_trans_info_label',$this->pi_getLL('tx_civserv_pi3_organisation.pub_trans_info','Public transport information'));
		$smartyOrganisation->assign('pub_trans_stop_label',$this->pi_getLL('tx_civserv_pi3_organisation.pub_trans_stop','Stop'));
		$smartyOrganisation->assign('available_services_label',$this->pi_getLL('tx_civserv_pi3_organisation.available_services','Here you find the following services'));
		$smartyOrganisation->assign('infopage_label',$this->pi_getLL('tx_civserv_pi3_organisation.infopage','Info Page'));
		$smartyOrganisation->assign('bl_citymap_label',$this->pi_getLL('tx_civserv_pi3_organisation.citymap_label','City Map Link'));
		$which = 'char';
		$what = 'A';
		
		// with pi_linkTP_keepPIvars we can easily overrule existing piVars
		// for overruling an existing piVar we don't even need the prefixId for the extension
		// pi_linkTP_keepPIvars($str,$overrulePIvars=array(),$cache=0,$clearAnyway=0,$altPageId=0)
		
		// need to set new mode 'orcode' on organisation-detail page! The detail-Page does not usually have that mode
		// for setting a new piVar (rather than overruling an existing one) we need the function pi_linkToPage
		// for pi_linkToPage to work we need to add the prefix to the piVars!!!
		// but there is a Problem with cHash!!!
		//pi_linkToPage($str,$id,$target='',$urlParameters=array())



		// and the winner is:
		// pi_linkTP($str,$urlParameters=array(),$cache=0,$altPageId=0)
		$smartyOrganisation->assign('backlink', $this->pi_linkTP(	$this->pi_getLL('tx_civserv_pi3_organisation.backlink','backlink'), 
																	array(	$this->prefixId . '[mode]' => $_SESSION['stored_mode'], 
																			$this->prefixId . '[community_id]' => $this->community['id'],
																			$this->prefixId . '['.$_SESSION['stored_filter_key'].']' => $_SESSION['stored_filter_val'],
																			$this->prefixId . '[pointer]' => 0
																	),
																	0,
																	0		
												)
									);
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
						'deleted=0 AND hidden=0',
						'',
						'cm_community_name');

		$row_counter = 0;
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res))	{
			$community_data[$row_counter]['name'] = $row['cm_community_name'];
			$community_data[$row_counter]['link'] = htmlspecialchars(parent::pi_linkTP_keepPIvars_url(array(community_id => $row['cm_community_id']),0,1,$row[cm_page_uid]));
			$row_counter++;
		}
		if ($row_counter == 0) {
			$GLOBALS['error_message'] = $this->pi_getLL('tx_civserv_pi3_error.no_community','No community found. The system seems to be missconfigured or not configured yet.');
			return false;
		}
		$smartyCommunity->assign('community_choice_label',$this->pi_getLL('tx_civserv_pi3_community_search_label','You have not choosen a community yet. Please choose your community.'));
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
			$notice = str_replace('###COMMUNITY_NAME###','<span class="community_name">' . $this->community['name'] . '</span>',$this->pi_getLL('tx_civserv_pi3_community_choice.notice','The following information is related to ###COMMUNITY_NAME###.'));
			$link_text = $this->pi_getLL('tx_civserv_pi3_community_choice.link_text','Click here, to choose another community.');
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
		if (trim($this->piVars['community_id']) <= '') {
			$community_id = $conf['community_id'];
		} else {
			$community_id = $this->piVars['community_id'];
		}
		if ($_SESSION[community_name] > '' ) {
			$content = $_SESSION[community_name];
		} elseif ($community_id > ''  && $community_id != 'choose') {
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
						'cm_community_name',
						'tx_civserv_conf_mandant',
						'deleted=0 AND hidden=0
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
	 * marks.CHOICE_LINK.userFunc = tx_civserv_pi3->getChoiceLink
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
								'deleted=0 AND hidden=0');
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
		if ($this->getEmailAddress($smartyEmailForm) || $this->piVars['mode']=='set_contact_form') {
			if($this->getEmailAddress($smartyEmailForm)){
				//Assign action url of email form with mode 'check_email_form'
				$smartyEmailForm->assign('action_url',htmlspecialchars($this->pi_linkTP_keepPIvars_url(array('mode' => 'check_email_form'),0,0)));
			}else{
				//Assign action url of email form with mode 'check_contact_form'
				$smartyEmailForm->assign('action_url',htmlspecialchars($this->pi_linkTP_keepPIvars_url(array('mode' => 'check_contact_form'),0,0)));
			}

			//Assign template labels
			$hoster_email=$this->get_hoster_email();
			$smartyEmailForm->assign('email_form_label',$this->pi_getLL('tx_civserv_pi3_email_form.email_form','E-Mail Form'));
			$smartyEmailForm->assign('contact_form_label',str_replace('###HOSTER###', $hoster_email, $this->pi_getLL('tx_civserv_pi3_email_form.contact_form','Contact '.$hoster_email)));
			$smartyEmailForm->assign('notice_label',$this->pi_getLL('tx_civserv_pi3_email_form.notice','Please enter your postal address oder email address, so that we can send you an answer'));
			$smartyEmailForm->assign('title_label', $this->pi_getLL('tx_civserv_pi3_email_form.title','Title'));
			$smartyEmailForm->assign('chose_option', $this->pi_getLL('tx_civserv_pi3_email_form.chose','Please chose'));
			$smartyEmailForm->assign('female_option', $this->pi_getLL('tx_civserv_pi3_email_form.female','Ms.'));
			$smartyEmailForm->assign('male_option', $this->pi_getLL('tx_civserv_pi3_email_form.male','Mr.'));
			$smartyEmailForm->assign('firstname_label',$this->pi_getLL('tx_civserv_pi3_email_form.firstname','Firstname'));
			$smartyEmailForm->assign('surname_label',$this->pi_getLL('tx_civserv_pi3_email_form.surname','Surname'));
			$smartyEmailForm->assign('street_label',$this->pi_getLL('tx_civserv_pi3_email_form.street','Street, Nr.'));
			$smartyEmailForm->assign('postcode_label',$this->pi_getLL('tx_civserv_pi3_email_form.postcode','Postcode'));
			$smartyEmailForm->assign('city_label',$this->pi_getLL('tx_civserv_pi3_email_form.city','City'));
			$smartyEmailForm->assign('email_label',$this->pi_getLL('tx_civserv_pi3_email_form.email','E-Mail'));
			$smartyEmailForm->assign('phone_label',$this->pi_getLL('tx_civserv_pi3_email_form.phone','Phone'));
			$smartyEmailForm->assign('fax_label',$this->pi_getLL('tx_civserv_pi3_email_form.fax','Fax'));
			$smartyEmailForm->assign('subject_label',$this->pi_getLL('tx_civserv_pi3_email_form.subject','Subject'));
			$smartyEmailForm->assign('bodytext_label',$this->pi_getLL('tx_civserv_pi3_email_form.bodytext','Your text'));
			$smartyEmailForm->assign('submit_label',$this->pi_getLL('tx_civserv_pi3_email_form.submit','Send e-mail'));
			$smartyEmailForm->assign('reset_label',$this->pi_getLL('tx_civserv_pi3_email_form.reset','Reset'));
			$smartyEmailForm->assign('required_label',$this->pi_getLL('tx_civserv_pi3_email_form.required','required'));

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
		$email_address = $this->getEmailAddress($smartyEmailForm);

		// Check if there is a valid email address in the database for the given combination of employee, service, position and organisation id
		if ($email_address) {

			// Check submitted form fields
			if (empty($surname)) {
				$smartyEmailForm->assign('error_surname',$this->pi_getLL('tx_civserv_pi3_email_form.error_surname','Please enter your surname!'));
				$is_valid = false;
			}

			if (empty($firstname)) {
				$smartyEmailForm->assign('error_firstname',$this->pi_getLL('tx_civserv_pi3_email_form.error_firstname','Please enter your firstname!'));
				$is_valid = false;
			}

			if (!empty($postcode) && !is_numeric($postcode)) {
				$smartyEmailForm->assign('error_postcode',$this->pi_getLL('tx_civserv_pi3_email_form.error_postcode','Please enter a valid postcode!'));
				$is_valid = false;
			}

			if (!empty($email) && !t3lib_div::validEmail($email)) {
				$smartyEmailForm->assign('error_email',$this->pi_getLL('tx_civserv_pi3_debit_form.error_email','Please enter a valid email address!'));
				$is_valid = false;
			}

			if (empty($subject)) {
				$smartyEmailForm->assign('error_subject',$this->pi_getLL('tx_civserv_pi3_email_form.error_subject','Please enter a subject!'));
				$is_valid = false;
			}

			if (empty($bodytext)) {
				$smartyEmailForm->assign('error_bodytext',$this->pi_getLL('tx_civserv_pi3_email_form.error_bodytext','Please enter your text!'));
				$is_valid = false;
			}

			if ($is_valid) {

				// Format body of email message
				$body = $this->pi_getLL('tx_civserv_pi3_email_form.title','Title') . ': ' . $title.
				   "\n" . $this->pi_getLL('tx_civserv_pi3_email_form.firstname','Firstname') . ': ' . $firstname.
				   "\n" . $this->pi_getLL('tx_civserv_pi3_email_form.surname','Surname') . ': ' . $surname.
				   "\n" . $this->pi_getLL('tx_civserv_pi3_email_form.phone','Phone') . ': ' . $phone.
				   "\n" . $this->pi_getLL('tx_civserv_pi3_email_form.fax','Fax') . ': ' . $fax.
				   "\n" . $this->pi_getLL('tx_civserv_pi3_email_form.email','E-Mail') . ': ' . $email.
				   "\n" . $this->pi_getLL('tx_civserv_pi3_email_form.street','Street, Nr.') . ': ' .$street.
				   "\n" . $this->pi_getLL('tx_civserv_pi3_email_form.postcode','Postcode') . ': ' . $postcode.
				   "\n" . $this->pi_getLL('tx_civserv_pi3_email_form.city','City') . ': ' . $city.
				   "\n" . $this->pi_getLL('tx_civserv_pi3_email_form.subject','Subject') . ': ' . $subject.
				   "\n" .
				   "\n" . $this->pi_getLL('tx_civserv_pi3_email_form.bodytext','Your text') . ': ' .
				   "\n" . $bodytext;
				//todo: check possibilities of header injection
				#$headers = !empty($email)?	"From: ".$email."\r\nReply-To: ".$email."\r\n":"From: ".$email_address."\r\nReply-To: ".$email_address."\r\n";
				$headers = !empty($email)?	"From: ".$email."\r\nReply-To: ".$email."\r\n": !empty($this->conf['contact_email']) && t3lib_div::validEmail($this->conf['contact_email'])? "From: ".$this->conf['contact_email']."\r\n Reply-To: ".$this->conf['contact_email']."\r\n":"From: ".$email_address."\r\nReply-To: ".$email_address."\r\n";


				t3lib_div::plainMailEncoded($email_address, $subject, $body, $headers);
				$reply = $this->pi_getLL('tx_civserv_pi3_email_form.complete','Thank you! Your message has been sent successfully ');
				$reply .= $this->pi_getLL('tx_civserv_pi3_email_form.to','to ');
				$reply .= $email_address.".";
				$smartyEmailForm->assign('complete',$reply);

				return true;
			} else { //Return email form template with error markers
				if($this->piVars['mode']=="check_contact_form"){
					// Assign action url of email form
					$smartyEmailForm->assign('action_url',htmlspecialchars($this->pi_linkTP_keepPIvars_url(array('mode' => 'check_contact_form'),0,0)));
				} else {
					// Assign action url of email form
					$smartyEmailForm->assign('action_url',htmlspecialchars($this->pi_linkTP_keepPIvars_url(array('mode' => 'check_email_form'),0,0)));
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
				$smartyEmailForm->assign('email_form_label',$this->pi_getLL('tx_civserv_pi3_email_form.email_form','E-Mail Form'));
				$smartyEmailForm->assign('contact_form_label',str_replace('###HOSTER###', $hoster_email, $this->pi_getLL('tx_civserv_pi3_email_form.contact_form','Contact '.$hoster_email)));
				$smartyEmailForm->assign('notice_label',$this->pi_getLL('tx_civserv_pi3_email_form.notice','Please enter your postal address oder email address, so that we can send you an answer'));
				$smartyEmailForm->assign('title_label', $this->pi_getLL('tx_civserv_pi3_email_form.title','Title'));
				$smartyEmailForm->assign('chose_option', $this->pi_getLL('tx_civserv_pi3_email_form.chose','Please chose'));
				$smartyEmailForm->assign('female_option', $this->pi_getLL('tx_civserv_pi3_email_form.female','Ms.'));
				$smartyEmailForm->assign('male_option', $this->pi_getLL('tx_civserv_pi3_email_form.male','Mr.'));
				$smartyEmailForm->assign('firstname_label',$this->pi_getLL('tx_civserv_pi3_email_form.firstname','Firstname'));
				$smartyEmailForm->assign('surname_label',$this->pi_getLL('tx_civserv_pi3_email_form.surname','Surname'));
				$smartyEmailForm->assign('street_label',$this->pi_getLL('tx_civserv_pi3_email_form.street','Street, Nr.'));
				$smartyEmailForm->assign('postcode_label',$this->pi_getLL('tx_civserv_pi3_email_form.postcode','Postcode'));
				$smartyEmailForm->assign('city_label',$this->pi_getLL('tx_civserv_pi3_email_form.city','City'));
				$smartyEmailForm->assign('email_label',$this->pi_getLL('tx_civserv_pi3_email_form.email','E-Mail'));
				$smartyEmailForm->assign('phone_label',$this->pi_getLL('tx_civserv_pi3_email_form.phone','Phone'));
				$smartyEmailForm->assign('fax_label',$this->pi_getLL('tx_civserv_pi3_email_form.fax','Fax'));
				$smartyEmailForm->assign('subject_label',$this->pi_getLL('tx_civserv_pi3_email_form.subject','Subject'));
				$smartyEmailForm->assign('bodytext_label',$this->pi_getLL('tx_civserv_pi3_email_form.bodytext','Your text'));
				$smartyEmailForm->assign('submit_label',$this->pi_getLL('tx_civserv_pi3_email_form.submit','Send e-mail'));
				$smartyEmailForm->assign('reset_label',$this->pi_getLL('tx_civserv_pi3_email_form.reset','Reset'));
				$smartyEmailForm->assign('required_label',$this->pi_getLL('tx_civserv_pi3_email_form.required','required'));

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
	function getEmailAddress() {
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
					'deleted=0 AND hidden=0 AND uid = ' . $this->piVars[org_id]);

			//Check if query returned a result
			if ($GLOBALS['TYPO3_DB']->sql_num_rows($res_organisation) == 1) {
				$organisation = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_organisation);
				$email_address = $organisation[or_email];
				return $email_address;
			} else {
				$GLOBALS['error_message'] = $this->pi_getLL('tx_civserv_pi3_email_form.error_org_id','Wrong organisation id or organisation does not exist!');
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
				$GLOBALS['error_message'] = $this->pi_getLL('tx_civserv_pi3_email_form.error_sv_id','Wrong service id, employee id oder position id. No email address found!');
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
				$GLOBALS['error_message'] = $this->pi_getLL('tx_civserv_pi3_email_form.error_pos_id','Wrong employee id oder position id. No email address found!');
				return false;
			}
		} elseif ($this->piVars['mode']=='check_contact_form') {	//Email form ist called by the contact_link in the main Navigation
			//todo: add database field for hoster from which the address below should be retrieved
			$hoster_email =$this->get_hoster_email();
			return $hoster_email;
		} else {
			$GLOBALS['error_message'] = $this->pi_getLL('tx_civserv_pi3_email_form.error_general','Organisation id, employee id, position id and service id wrong or not set. No email address found!');
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
			$querypart_where = ' AND tx_civserv_service.uid = ' . $sv_id . ' AND tx_civserv_employee.uid = ' . $emp_id . ' AND tx_civserv_position.uid = ' . $pos_id . '
								AND tx_civserv_service.deleted=0 AND tx_civserv_service.hidden=0
								AND tx_civserv_position.deleted=0 AND tx_civserv_position.hidden=0
								AND tx_civserv_employee_em_position_mm.deleted=0 AND tx_civserv_employee_em_position_mm.hidden=0
								AND ((UNIX_TIMESTAMP(LOCALTIMESTAMP) BETWEEN tx_civserv_service.starttime AND tx_civserv_service.endtime)
						 	 		OR ((UNIX_TIMESTAMP(LOCALTIMESTAMP) > tx_civserv_service.starttime) AND (tx_civserv_service.endtime=0))
						 	  		OR (tx_civserv_service.starttime=0 AND tx_civserv_service.endtime=0) )
						 	  	AND tx_civserv_service.uid = tx_civserv_service_sv_position_mm.uid_local
								AND tx_civserv_service_sv_position_mm.uid_foreign = tx_civserv_position.uid
								AND tx_civserv_employee.uid = tx_civserv_employee_em_position_mm.uid_local
						 		AND tx_civserv_employee_em_position_mm.uid_foreign = tx_civserv_position.uid';
		}

		if (!empty($emp_id) && empty($pos_id) && empty($sv_id)) {  //Email form is called from organisation detail page (supervisor email)
			$querypart_where = ' AND tx_civserv_employee.uid = ' . $emp_id;
		}

		if ((!empty($pos_id) && !empty($emp_id)) && empty($sv_id)) {  //Email form is called from organisation detail page (supervisor email)
			$querypart_select = ', ep_email';
			$querypart_from = ', tx_civserv_position, tx_civserv_employee_em_position_mm';
			$querypart_where = ' AND tx_civserv_employee.uid = ' . $emp_id . ' AND tx_civserv_position.uid = ' . $pos_id . '
								AND tx_civserv_position.deleted=0 AND tx_civserv_position.hidden=0
								AND tx_civserv_employee_em_position_mm.deleted=0 AND tx_civserv_employee_em_position_mm.hidden=0
								AND tx_civserv_employee.uid = tx_civserv_employee_em_position_mm.uid_local
						 		AND tx_civserv_employee_em_position_mm.uid_foreign = tx_civserv_position.uid';
		}

		$res_employee = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
						'em_email, em_datasec as datasec' . $querypart_select,
						'tx_civserv_employee' . $querypart_from,
						'tx_civserv_employee.deleted=0 AND tx_civserv_employee.hidden=0 '.$querypart_where,
						'',
						'',
						'');

		return $res_employee;
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
		// for the online_service_list we want to display thumbnails of the service images!
		if($this->piVars['mode']=='online_services'){
			$conf['file.'] = array('maxH' => 100, 'maxW' => 100);
		}
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
		// some mandants may have configured an individual hoster_email in their TS-Template
		if(!empty($this->conf['contact_email']) && t3lib_div::validEmail($this->conf['contact_email'])){
			return $this->conf['contact_email'];
		}
		
		// default: take the email-adress given in tx_civserv_configuration
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
			$hoster_email = $row['cf_value'];
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
	 * but keeps always piVars['community_id']. If no community_id could be determined, caching is disabled.
	 * Get URL to the current page while keeping currently set values in piVars.
	 * Returns only the URL from the link.
	 *
	 * @param	array		Array of values to override in the current piVars. Contrary to pi_linkTP the keys in this array must correspond to the real piVars array and therefore NOT be prefixed with the $this->prefixId string. Further, if a value is a blank string it means the piVar key will not be a part of the link (unset)
	 * @param	boolean		If $cache is set, the page is asked to be cached by a &cHash value (unless the current plugin using this class is a USER_INT). Otherwise the no_cache-parameter will be a part of the link.
	 * @param	boolean		If set, then the current values of piVars will NOT be preserved anyways... (except for piVars['community_id'])
	 * @param	integer		Alternative page ID for the link. (By default this function links to the SAME page!)
	 * @return	string		The URL ($this->cObj->lastTypoLinkUrl)
	 * @see tslib_pibase::pi_linkTP_keepPIvars()
	 */
	 function pi_linkTP_keepPIvars_url($overrulePIvars=array(),$cache=0,$clearAnyway=0,$altPageId=0) {
	 	if ($this->piVars['community_id'] > '') {
	 		$overrulePIvars = t3lib_div::array_merge($overrulePIvars,array(community_id => $this->piVars['community_id']));
	 	}
	 	return parent::pi_linkTP_keepPIvars_url($overrulePIvars,$cache,$clearAnyway,$altPageId);
	 }


	/**
	 * Overwrites the same function from the parent class tslib_pibase. Does the same, but uses no tables and is optimized for accessibility.
	 * Returns a Search box, sending search words to piVars "sword" and setting the "no_cache" parameter as well in the form.
	 * no longer Submits the search request to the current REQUEST_URI but to search_mode-enhanced URL
	 *
	 * @param	string		Attributes for the div tag which is wrapped around the table cells containing the search box
	 * @param	boolean		If true, a heading for the search box is printed
	 * @return	string		Output HTML, wrapped in <div>-tags with a class attribute
	 */
	function pi_list_searchBox($divParams='',$header=false) {
		// the $sBox search-form gets displayed on the result-page of the search accomplished by $this->do_search
		// searchword is run against white list (here and in do_search) by $this->check_searchword
	

		// Search box design:
		if ($this->piVars[sword] <= '') {
			 $this->piVars[sword] = $this->pi_getLL('pi_list_searchBox_defaultValue','search item');
		}
		// changed action tag according to instructions from security review:
		// dropped:		<form method="post" action="'.htmlspecialchars(t3lib_div::getIndpEnv('REQUEST_URI')).'" style="margin: 0 0 0 0;" >
		// introduced:	<form method="post" action="'.htmlspecialchars($this->pi_linkTP_keepPIvars_url(array('mode' => 'search_result'),0,1)).'" style="margin: 0 0 0 0;" >
		
		
		
		//  $this->pi_classParam('searchbox-sword') contains the markup for css: 'class="tx-civserv-pi3-searchbox-sword"'
		$search_word=$this->check_searchword(strip_tags($this->piVars['sword']));  //strip and check to avoid xss-exploits

		$sBox = '

		<!--
			List search box:
		-->

		<div' . $this->pi_classParam('searchbox') . '>
			<form method="post" action="'.htmlspecialchars($this->pi_linkTP_keepPIvars_url(array('mode' => 'search_result'),0,1)).'" style="margin: 0 0 0 0;" >
				<fieldset>
        				<legend>' . $this->pi_getLL('pi_list_searchBox_searchform','Search form') . '</legend>
          				<div class="searchform" ' . trim($divParams) . '>
            				<p><label for="query" title="' . $this->pi_getLL('pi_list_searchBox_searchkey','Please enter here your search key') . '">' .
            					($header?'<strong>' . $this->pi_getLL('pi_list_searchBox_header','Keyword search') . ':</strong><br />':'') .
            				'</label></p>
           					<input type="text" name="' . $this->prefixId . '[sword]" id="query" class="searchkey" size="16" maxlength="60" value="' . htmlentities($search_word) . '"' . $this->pi_classParam('searchbox-sword') . ' onblur="if(this.value==\'\') this.value=\'' . htmlentities($search_word) . '\';" onfocus="if(this.value==\'' . $this->pi_getLL('pi_list_searchBox_defaultValue','search item') . '\') this.value=\'\';" />
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
	function pi_list_browseresults($showResultCount=1, $divParams='', $spacer=false)      {
			// Initializing variables:
		$pointer = intval($this->piVars['pointer']);
		$count = $this->internal['res_count'];
		$results_at_a_time = t3lib_div::intInRange($this->internal['results_at_a_time'],1,1000);
		$maxPages = t3lib_div::intInRange($this->internal['maxPages'],1,100);
		
		$pR1 = $pointer*$results_at_a_time+1;
		$pR2 = $pointer*$results_at_a_time+$results_at_a_time;
		
		$max = t3lib_div::intInRange(ceil($count/$results_at_a_time),1,$maxPages);
		
		
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
			// in order to have a correct value for $max we must include the value of the actual $pointer in the calculation
			for($i=0;$i<$max;$i++)  {
				// check that the starting point (equivalent of $pR1) doesn't exceed the total $count
				if($a*$results_at_a_time+1>$count){
					$i=$max; //quitt!!!
				}else{	
					$links[]=sprintf('%s'.$this->pi_linkTP_keepPIvars(trim($this->pi_getLL('pi_list_browseresults_page','Page',TRUE).' '.($a+1)),array('pointer'=>($a?$a:'')),1).'%s',
								($pointer==$a?'<span '.$this->pi_classParam('browsebox-SCell').'><strong>':''),
								($pointer==$a?'</strong></span>':''));
				}
				$a++;
			}
		}
		// neither $pointer nor the number-link ($a) must exceed the result of the calculation below!
		if ($pointer<ceil($count/$results_at_a_time)-1 && $a<=ceil($count/$results_at_a_time)-1) {
			$links[]=$this->pi_linkTP_keepPIvars($this->pi_getLL('pi_list_browseresults_next','Next >',TRUE),array('pointer'=>$pointer+1),1);
		}

		// $pR1 and $pR2 have been moved up!
		
		$sBox = '

                <!--
                        List browsing box:
                -->';
				
		$sBox .= '<div'.$this->pi_classParam('browsebox').'>';
		
		if($showResultCount){
			$sBox .= '<p>';
			if($this->internal['res_count']){
				$sBox .=	sprintf(str_replace(	'###SPAN_BEGIN###',
													'<span'.$this->pi_classParam('browsebox-strong').'>',
													$this->pi_getLL(	'pi_list_browseresults_displays',
																		'Displaying results ###SPAN_BEGIN###%s to %s</span> out of ###SPAN_BEGIN###%s</span>'
																	)
												),
												$this->internal['res_count'] > 0 ? $pR1 : 0,
												min(array($this->internal['res_count'], $pR2)),
												$this->internal['res_count']
                        			);
			}else{
				$sBox .=	$this->pi_getLL('pi_list_browseresults_noResults','Sorry, no items were found.');
			}
			$sBox .= '</p>';
		}else{
			//nix
		}
		$sBox .=	'<'.trim('p '.$divParams).'>'.implode($spacer,$links).'</p>';
        $sBox .=	'</div>';
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
	 *   menu.special.userFunc = tx_civserv_pi3->makeMenuArray
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
		#session_destroy();
		// Save community id in session, to ensure that the id is also saved when vititing sites without the civserv extension (e.g. fulltext search)
		if ($_SESSION['community_id'] <= '') {
			$_SESSION['community_id'] = $this->piVars['community_id'];
		}
		// Set piVars['community_id'], if not given from the URL. Necessary for the function pi_linkTP_keepPIvars_url.
		if ($this->piVars['community_id'] <= '') {
			$this->piVars['community_id'] = $_SESSION['community_id'];
		}
		$menuArray = array();
		
		//test bk: you might want to control the display-order of menu (via $conf). Name them so you can sort them!
	/*	
		if ($conf['menuServiceList']) {
			$menuArray['menuServiceList'] = array(
								'title' => $this->pi_getLL('tx_civserv_pi3_menuarray.service_list','Services A - Z'),
								'_OVERRIDE_HREF' => $this->pi_linkTP_keepPIvars_url(array('mode' => 'service_list'),1,1,$pageid),
								'ITEM_STATE' => ($this->piVars['mode']=='service_list')?'ACT':'NO');
		}
		if ($conf['menuCircumstanceTree']) {
			$menuArray['menuCircumstanceTree'] = array(
								'title' => $this->pi_getLL('tx_civserv_pi3_menuarray.circumstance_tree','Circumstances'),
								'_OVERRIDE_HREF' => $this->pi_linkTP_keepPIvars_url(array('mode' => 'circumstance_tree'),1,1,$pageid),
								'ITEM_STATE' => (($this->piVars['mode']=='circumstance_tree') || ($this->piVars['mode']=='circumstance'))?'ACT':'NO');
		}
		if ($conf['menuUsergroupTree']) {
			$menuArray['menuUsergroupTree'] = array(
								'title' => $this->pi_getLL('tx_civserv_pi3_menuarray.usergroup_tree','Usergroups'),
								'_OVERRIDE_HREF' => $this->pi_linkTP_keepPIvars_url(array('mode' => 'usergroup_tree'),1,1,$pageid),
								'ITEM_STATE' => (($this->piVars['mode']=='usergroup_tree') || ($this->piVars['mode']=='usergroup'))?'ACT':'NO');
		}
		if ($conf['menuOrganisationTree']) {
			$menuArray['menuOrganisationTree'] = array(
								'title' => $this->pi_getLL('tx_civserv_pi3_menuarray.organisation_tree','Organisation'),
								'_OVERRIDE_HREF' => $this->pi_linkTP_keepPIvars_url(array('mode' => 'organisation_tree'),1,1,$pageid),
								'ITEM_STATE' => (($this->piVars['mode']=='organisation_tree') || ($this->piVars['mode']=='organisation'))?'ACT':'NO');
		}
		if ($conf['menuOrganisationList']) {
			$menuArray['menuOrganisationList'] = array(
								'title' => $this->pi_getLL('tx_civserv_pi3_menuarray.organisation_list','Organisation A - Z'),
								'_OVERRIDE_HREF' => $this->pi_linkTP_keepPIvars_url(array('mode' => 'organisation_list'),1,1,$pageid),
								'ITEM_STATE' => (($this->piVars['mode']=='organisation_list') || ($this->piVars['mode']=='organisation_list'))?'ACT':'NO');
		}
		*/
		if ($conf['menuEmployeeList']) {
			$menuArray['menuEmployeeList'] = array(
								'title' => $this->pi_getLL('tx_civserv_pi3_menuarray.employee_list','Employees A - Z'),
								'_OVERRIDE_HREF' => $this->pi_linkTP_keepPIvars_url(array('mode' => 'employee_list_az'),1,1,$pageid),
								'ITEM_STATE' => ($this->piVars['mode']=='employee_list_az') ? 'ACT' : 'NO');
		}
		/*
		if ($conf['menuFormList']) {
			$menuArray['menuFormList'] = array(
								'title' => $this->pi_getLL('tx_civserv_pi3_menuarray.form_list','Forms'),
								'_OVERRIDE_HREF' => $this->pi_linkTP_keepPIvars_url(array('mode' => 'form_list'),1,1,$pageid),
								'ITEM_STATE' => ($this->piVars['mode']=='form_list')?'ACT':'NO');
		}
		if ($conf['menuTop15']) {
			$menuArray['menuTop15'] = array(
								'title' => $this->pi_getLL('tx_civserv_pi3_menuarray.top15','Top 15'),
								'_OVERRIDE_HREF' => $this->pi_linkTP_keepPIvars_url(array('mode' => 'top15'),0,1,$pageid),
								'ITEM_STATE' => ($this->piVars['mode']=='top15')?'ACT':'NO');
		}
		// online services....
		if ($conf['menuOnlineServices']) {
			$menuArray['menuOnlineServices'] = array(
								'title' => $this->pi_getLL('tx_civserv_pi3_menuarray.online_services','Online Services'),
								'_OVERRIDE_HREF' => $this->pi_linkTP_keepPIvars_url(array('mode' => 'online_services'),0,1,$pageid),
								'ITEM_STATE' => ($this->piVars['mode']=='online_services')?'ACT':'NO');
		}

		// get full text search id from TSconfig
		if ($conf['fulltext_search_id'] > '') {
			$menuArray['menuFulltextSearch'] = array(
							'title' => $this->pi_getLL('tx_civserv_pi3_menuarray.fulltext_search','Fulltext Search'),
							'_OVERRIDE_HREF' => $this->pi_linkTP_keepPIvars_url(array(),0,1,$conf['fulltext_search_id']),
							'ITEM_STATE' => ($GLOBALS['TSFE']->id==$conf['fulltext_search_id'])?'ACT':'NO');
		}
		// get id for alternative language from TSconfig
		if (intval($conf['alternative_page_id']) > 0) {
			$menuArray[] = array(
							'title' => $this->pi_getLL('tx_civserv_pi3_menuarray.alternative_language','Deutsche Inhalte'),
							'_OVERRIDE_HREF' => $this->pi_linkTP_keepPIvars_url(array('mode' => 'service_list'),0,1,$conf['alternative_page_id']),
							'ITEM_STATE' => ($GLOBALS['TSFE']->id==$conf['alternative_page_id'])?'ACT':'NO');
		}
		*/
		
		//test bk: city of Münster: define first menu-item via $conf!
		/*
		if ($conf['menuItems_01'] > '' && $conf[$conf['menuItems_01']]) {
			$first = $menuArray[$conf['menuItems_01']];
			unset($menuArray[$conf['menuItems_01']]);
			array_unshift ($menuArray, $first);
		}
		*/
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
		return parent::pi_linkTP_keepPIvars_url(array('mode' => 'legal_notice'),1,1,$pageid);
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
		return parent::pi_linkTP_keepPIvars_url(array('mode' => 'set_contact_form'),1,1,$pageid);
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
	}


	/**
	 * Returns link to actual page or to the page which lists organisation, services....
	 * - useful for breadcrumbs...
	 * Normaly used from a template userfunction.
	 *
	 * @param	string		content
	 * @param	array		configuration array
	 * @return	string		The link
	 */
	function getlistPage($content, $conf) {
		//we want to keep the md5-transformation on the paramenterlist, so we use
		//pi_linkTP_keepPIvars_url instead of pi_linkTP_keepPIvars (which would deliver us the whole link with <a href="...">...</a>)
		//plus: pi_linkTP_keepPIvars_url allows to reset the mode :-))
		
		$this->pi_loadLL(); //or else the pi_getLL won't work!
		if ($conf['pageid'] > '') {
			$pageid = $conf['pageid'];
		} else {
			$pageid = $GLOBALS['TSFE']->id;
		}

		if($this->conf['recursive'] > 0){
			$recursion=$this->conf['recursive'];
		} else {
			$recursion=20;
		}
		// $this->pi_getPidList($pageid, $recursion) does not work for pages inside info-folder (which are outside civserv_Plugin)
		// try home-made getPidList-Funktion instead.
		// identify it actual page is child of info_folder_uid
		$this->res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'pid',			 							// SELECT ...
			'pages',									// FROM ...
			'uid = '.$pageid.' AND deleted=0 AND hidden=0',	// AND title LIKE "%blabla%"', // WHERE...
			'', 										// GROUP BY...
			'',   										// ORDER BY...
			'' 											// LIMIT to 10 rows, starting with number 5 (MySQL compat.)
			);
		
		$parent_list = $this->get_parent_list($this->res, $parent_list);

		$linkText=$GLOBALS['TSFE']->page['title']; //default
		if($this->piVars['mode']=="organisation"){
			$pageLink= parent::pi_linkTP_keepPIvars_url(array('mode' => 'organisation_list'),1,1,$pageid);
			$linkText=$this->pi_getLL('tx_civserv_pi3_menuarray.organisation_list','Organisation A - Z'); 
		}elseif($this->piVars['mode']=="circumstance"){
			$pageLink= parent::pi_linkTP_keepPIvars_url(array('mode' => 'circumstance_tree'),1,1,$pageid);
			$linkText=$this->pi_getLL('tx_civserv_pi3_menuarray.circumstance_tree','Circumstances'); 
		}elseif($this->piVars['mode']=="usergroup"){
			$pageLink= parent::pi_linkTP_keepPIvars_url(array('mode' => 'usergroup_tree'),1,1,$pageid);
			$linkText=$this->pi_getLL('tx_civserv_pi3_menuarray.usergroup_tree','Usergroups'); 
		}elseif($this->piVars['mode']=="service"){
			$_SESSION['stored_pagelink']=$this->getActualPage($content, $conf);
			$pageLink= parent::pi_linkTP_keepPIvars_url(array('mode' => 'service_list'),1,1,$pageid);
			$linkText=$this->pi_getLL('tx_civserv_pi3_service_list.service_list','Services A - Z');
			$_SESSION['info_sites'] = $this->getCompletePageLink($pageLink, $linkText); //Variablen namen ändern?
		}elseif($this->piVars['mode']=="employee"){
			return $_SESSION['stored_pagelink'];
		}elseif($this->piVars['mode']==""){
			// no mode means either it is a page outside the pagetree of civserv --> do not display custom breadcrumb!
			// or else it is an Info-Page belonging to civserv --> do display custom breadcrumb! The pid of info-pages is available from tx_civserv_conf_mandant!
			if(in_array($_SESSION['info_folder_uid'], $parent_list)){ 
				$breadcrumb = $_SESSION['info_sites']; //Organisations A-Z
				$breadcrumb .=  $_SESSION['stored_pagelink']; //Services
				return $breadcrumb;
			 	#return '<span style="border:solid red 1px;">'.$breadcrumb.'</span>';
			 }else{
			 	return '';
			 }
		}else{
			// there is a mode - but none of the above-mentioned (e.g. a mode belonging to another extension)
			return '';
			#return '<span style="border:solid blue 1px;">'.$this->piVars['mode'].'</span>';
		}
		if(!$pageid == $_SESSION['page_uid'] && !$pageid == $_SESSION['alternative_page_uid']){
			return ''; // generally only the civserv display-pages need to have custom-breadcrumb
		}
		return $this->getCompletePageLink($pageLink, $linkText);
		#return '<span style="border:solid green 1px;">'.$this->getCompletePageLink($pageLink, $linkText).'</span>';
	}
	
	function getActualPage($content, $conf) {
		$this->pi_loadLL(); //or else the pi_getLL won't work!
		if ($conf['pageid'] > '') {
			$pageid = $conf['pageid'];
		} else {
			$pageid = $GLOBALS['TSFE']->id;
		}
		$linkText=$GLOBALS['TSFE']->page['title'];
		 // mark: for organisations we need a different linktext
		 
		 //tx_civserv_pi3_organisation_list.organisation_list.heading

		if($this->piVars['mode']=='service'){
			$textArr=explode(":", $linkText);
			if(count($textArr)>1)unset($textArr[0]);
			$linkText= implode(":", $textArr);//default
			#$linkText.=':'.strlen($linkText);
		}
		//return link-to-actual-page
		//second parameter is for cache and it also does the md5-thing about the parameterlist, if cache is not set the parameters are rendered in the human-readable way
		//third parameter ist for the elemination of all piVars. must not be set in this case or else link won't work! (id of service goes missing)
		$pageLink= parent::pi_linkTP_keepPIvars_url(array('mode' => $this->piVars['mode']),1,0,$pageid);
		if(!$pageid == $_SESSION['page_uid'] && !$pageid == $_SESSION['alternative_page_uid']){
			return ''; // generally only the civserv display-pages need to have custom-breadcrumb
		}
		return $this->getCompletePageLink($pageLink, $linkText);
		#return '<span style="border:solid blue 1px;">'.$this->getCompletePageLink($pageLink, $linkText).'</span>';

	}
	
	function getCompletePageLink($pageLink, $linkText){
		$completePageLink = t3lib_div::getIndpEnv(TYPO3_SITE_URL).$pageLink;
		$completePageLink = '<a href="'.$completePageLink.'">'.$linkText.'</a>';
		return $completePageLink;
	}
	
	
	 /*******************************************
	 *
	 * Function for white-listing search words
	 *
	 ********************************************/
	function check_searchword($string){
		//white list
		$searchword_pattern = '/^[A-Za-z0-9ÄäÖöÜüß\- ]*$/';
		if(!preg_match($searchword_pattern, $string)){
			//collect all occurring illegal characters
			#$arr_bad_chars=array();
			foreach (count_chars($string, 1) as $i => $val) {
				if(!preg_match($searchword_pattern, chr($i))){
					#$arr_bad_chars[]=chr($i);
					$string = str_replace(chr($i),"",$string);
				}
			}
		}
		return $string;
	}

	
	 /******************************
	 *
	 * Functions for Custom-Links
	 *
	 *******************************/
	function replace_umlauts($string){
		// remove all kinds of umlauts
		$umlaute = Array("/ä/","/ö/","/ü/","/Ä/","/Ö/","/Ü/","/ß/", "/é/"); //should use hexadecimal-code for é à etc????
		$replace = Array("ae","oe","ue","Ae","Oe","Ue","ss", "e");
		$string = preg_replace($umlaute, $replace, $string);
		
		//eliminate:
		$string=str_replace(".", "", $string);			// 'Bücherei Zweigstelle Wolbecker Str.'				--> buecherei_zweigstelle_wolbecker_str.html
		$string=str_replace(" - ", "-", $string);		// 'La Vie - Begegnungszentrum Gievenbeck'				--> la_vie-begegnungszentrum_gievenbeck.html
		$string=str_replace("- ", "-", $string);		// 'Veterinär- und Lebensmittel...'						--> veterinaer-und_lebensmittel.html
		$string=str_replace("-, ", " ", $string);		// 'Amt für Stadt-, Verkehrs- und Parkplatzplanung'		--> amt_fuer_stadt_verkehrs_und_parkplatzplanung.html
		$string=str_replace(",", "", $string);			// 'Ich, du, Müllers's Kuh'								--> ich_du_muellers_kuh.html
		$string=str_replace(": ", " ", $string);		// 'Gesundheitsamt: Therapie und Hilfe sofort'			--> gesundheitsamt_therapie_und_hilfe_sofort.html

		//make blanks:
		$string=str_replace("+", " ", $string);			// 'Wohn + Stadtbau'
		$string=str_replace("&", " ", $string);			// 'Ich & Ich'
		$string=str_replace("/", " ", $string);			// 'Feuerwehr/Rettungsdienst'
		$string=str_replace("\\", " ", $string);		// 'Eins\Zwei\Drei'
		return $string;
	}


	function strip_extra($string){
		// Mobilé (Zentrum für clevere Verkehrsnutzung) => Mobile.html
		$string=trim(ereg_replace("\([^\)]*\)", "", $string));
		return $string;
	}
	
	function convert_plus_minus($string){
		$string=str_replace("-+", "+", $string);
		$string=str_replace("+-", "+", $string);
		//make them nice to use:
		$string=str_replace("+", "-", $string);
		return $string;
	}
	
	
	
	 /*********************************************************************************
	 *
	 * Function for including external info-pages in custom-breadccrumb navigation
	 *
	 **********************************************************************************/
	//get all the parents!!!! (inversion of pid_list which'll contain all the children)
	function get_Parent_list($result, $parent_list){
		global $parent_list;
		if($result){
			while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
			$parent_list[]=$row[0];
			$res2 = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				'pid',			 							// SELECT ...
				'pages',									// FROM ...
				'uid = '.$row[0].' AND deleted=0 AND hidden=0',// AND title LIKE "%blabla%"', // WHERE...
				'', 										// GROUP BY...
				'',   										// ORDER BY...
				'' 											// LIMIT to 10 rows, starting with number 5 (MySQL compat.)
				);
			$this->get_parent_list($res2, $liste);
			}
		}
		return $parent_list;
	}

}//class end



if (defined("TYPO3_MODE") && $TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/civserv/pi3/class.tx_civserv_pi3.php"])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/civserv/pi3/class.tx_civserv_pi3.php"]);
}

?>