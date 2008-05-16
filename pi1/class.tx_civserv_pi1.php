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
	var $pi_checkCHash = TRUE;
	
	var $versioningEnabled = FALSE;
	var $previewMode = FALSE;
	var $current_ws = 0;
	var $iconDir = 'typo3/gfx/fileicons/';



	/**
	 * @param	string			Content that is to be displayed within the plugin
	 * @param	array			Configuration array
	 * @return	$content		Content that is to be displayed within the plugin
	 */
	function main($content,$conf)	{
#		$GLOBALS['TYPO3_DB']->debugOutput=true;	 // Debugging - only on test-sites!
		if (TYPO3_DLOG)  t3lib_div::devLog('function main of FE class entered', 'civserv');
#		debug('hier spricht die frontend-klasse');

		
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
		
		//$this->default_mode = $this->conf['_DEFAULT_PI_VARS.']['mode'];

		// Start or resume session
		session_name($this->extKey);
		session_start();
		#session_destroy();
		
		if($this->previewMode){
			// nice try, but doesn't work, probably too late.
			// would have to manipulate this value before FE is rendered!!!
			#$this->conf['_DEFAULT_PI_VARS.']['mode']='service_list';
		}
		
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
			$template = $this->conf['tpl_community_choice']; //let them choose! community has to be set in the pivars!!!
			$accurate = $this->chooseCommunity($smartyObject);
			$choose = true;
	 	} elseif (($this->piVars['community_id'] != $_SESSION['community_id']) || ($_SESSION['community_name'] <= '')) {
		#} elseif (1==1){
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
					$_SESSION['page_uid'] = $community_data[0]['cm_page_uid']; //for the breadcrumb_navi!!!
					$_SESSION['alternative_language_folder_uid'] = $community_data[0]['cm_alternative_language_folder_uid'];
					$_SESSION['alternative_page_uid'] = $community_data[0]['cm_alternative_page_uid'];
					$_SESSION['info_folder_uid'] = $community_data[0]['cm_info_folder_uid'];
					$_SESSION['stored_pagelink'] = ''; //for the breadcrumb_navi!!!
					$_SESSION['info_sites'] = ''; //for the breadcrumb navigation of information pages
					$_SESSION['default_mode'] = $this->conf['_DEFAULT_PI_VARS.']['mode']; //Default Mode for the breadcrumb navigation
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
			$this->community['page_uid'] = $_SESSION['page_uid'];
			$this->community['alternative_language_folder_uid'] = $_SESSION['alternative_language_folder_uid'];
			$this->community['info_folder_uid'] = $_SESSION['info_folder_uid'];
			$this->community['alternative_page_uid'] = $_SESSION['alternative_page_uid'];

			// Set piVars['community_id'] because it could only be registered in the session and not in the URL
			$this->piVars['community_id'] = $_SESSION['community_id'];
			
			// for some reason corrupted pages (wrong community_id) accumulate in the typo3 cache
			// we must prevent that they get listed by search engines: strip off all content!
			if(intval($this->community['id']) !== intval($this->conf['_DEFAULT_PI_VARS.']['community_id'])){
				$GLOBALS['TSFE']->tmpl->setup['sitetitle'] = ''; //the less information the corrupted pages bear the better
				$GLOBALS['TSFE']->page['title'] = ''; //the less information the corrupted pages bear the better
				$this->piVars['mode'] = 'error';
			}
			
			switch($this->piVars['mode'])	{
				case 'service_list':
					$GLOBALS['TSFE']->page['title'] = $this->pi_getLL('tx_civserv_pi1_service_list.overview','Service list');
					$template = $this->conf['tpl_service_list'];
					$accurate = $this->serviceList($smartyObject,$this->conf['abcBarAtServiceList'],$this->conf['searchAtServiceList'],$this->conf['topAtServiceList']);
					break;

				case 'circumstance_tree':
					$GLOBALS['TSFE']->page['title'] = $this->pi_getLL('tx_civserv_pi1_circumstance.circumstance_tree','Circumstances');
					$template = $this->conf['tpl_circumstance_tree'];
					$accurate = $this->navigationTree($smartyObject,$this->community['circumstance_uid'],$this->conf['searchAtCircumstanceTree'],$this->conf['topAtCircumstanceTree']);
					break;

				case 'usergroup_tree':
					$GLOBALS['TSFE']->page['title'] = $this->pi_getLL('tx_civserv_pi1_usergroup.usergroup_tree','Usergroups');
					$template = $this->conf['tpl_usergroup_tree'];
					$accurate = $this->navigationTree($smartyObject,$this->community['usergroup_uid'],$this->conf['searchAtUsergroupTree'],$this->conf['topAtUsergroupTree']);
					break;

				case 'organisation_tree':
					$GLOBALS['TSFE']->page['title'] = $this->pi_getLL('tx_civserv_pi1_organisation.organisation_tree','Organisation');
					$template = $this->conf['tpl_organisation_tree'];
					$accurate = $this->navigationTree($smartyObject,$this->community['organisation_uid'],$this->conf['searchAtOrganisationTree'],$this->conf['topAtOrganisationTree']);
					break;
					
				case 'organisation_list':
					$GLOBALS['TSFE']->page['title'] = $this->pi_getLL('tx_civserv_pi1_menuarray.organisation_list','Organisation A - Z');
					$template = $this->conf['tpl_organisation_list'];
					$accurate = $this->organisation_list($smartyObject,$this->conf['abcBarAtOrganisationList'],$this->conf['searchAtOrganisationList'],$this->conf['topAtOrganisationList']);
					break;						
					
				case 'employee_list':
					$GLOBALS['TSFE']->page['title'] = $this->pi_getLL('tx_civserv_pi1_employee_list.employee_list','Employees A - Z');
					$template = $this->conf['tpl_employee_list'];
					$accurate = $this->employee_list($smartyObject,$this->conf['abcBarAtEmployeeList'],$this->conf['searchAtEmployeeList'],$this->conf['topAtEmployeeList']);
					break;					

				case 'form_list':
					$GLOBALS['TSFE']->page['title'] = $this->pi_getLL('tx_civserv_pi1_form_list.form_list','Forms');
					$template = $this->conf['tpl_form_list'];
					$accurate = $this->formList($smartyObject,$this->piVars['id'],$this->piVars['id']?$this->conf['abcBarAtFormList_orga']:$this->conf['abcBarAtFormList_all'],$this->conf['searchAtFormList'],$this->conf['topAtFormList'],$this->conf['orgaList']);
					#$accurate = $this->formList($smartyObject,$this->piVars['id'],$this->piVars['id']?$this->conf['abcBarAtFormList_orga']:$this->conf['abcBarAtFormList_all'],$this->conf['orderFormsByCategory'],$this->conf['searchAtFormList'],$this->conf['topAtFormList'],$this->conf['orgaList']);
					break;

				case 'top15':
					$GLOBALS['TSFE']->page['title'] = "TOP 15";
					$template = $this->conf['tpl_top15'];
					$accurate = $this->calculate_top15($smartyObject,$this->conf['show_counts'],$this->conf['service_count'],$this->conf['searchAtTop15']);
					break;

				case 'online_services':
					$GLOBALS['TSFE']->page['title'] = "Online Services";
					$template = $this->conf['tpl_online_sv_list']; //change this??
					$accurate = $this->serviceList($smartyObject,$this->conf['abcBarAtServiceList'],$this->conf['searchAtServiceList'],$this->conf['topAtServiceList'],1);//1 for onlineservices
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
					#$GLOBALS['TSFE']->page['title'] = $this->pi_getLL('tx_civserv_pi1_service_list.organisation','Organisation');
					$template = $this->conf['tpl_service_list'];
					// test bk: continue the abcBar from the OrganisationList!!!
					$accurate = $this->organisationDetail($smartyObject, $this->conf['continueAbcBarFromOrganisationList']) && $this->serviceList($smartyObject,$this->conf['abcBarAtOrganisation'],$this->conf['searchAtOrganisation'],$this->conf['topAtOrganisation']);
					break;

				case 'service':
					$template = $this->conf['tpl_service'];
					// test bk: continue the abcBar from the ServiceList!!!
					$accurate = $this->serviceDetail($smartyObject,$this->conf['searchAtService'], $this->conf['topAtService'],$this->conf['continueAbcBarFromServiceList']);
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
					
				case 'none':
					$template = $this->conf['tpl_none'];
					$accurate = true;
					break;

				default:
					$accurate = false;
					$GLOBALS['error_message'] = $this->pi_getLL('tx_civserv_pi1_error.invalid_mode','Invalid mode');
			}
		}// !choose
		
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
		//	function makeServiceListQuery($char=all,$limit=true,$count=false) {
		$query = $this->makeServiceListQuery($this->piVars['char']);
		if (!$query) {
			return false;
		}
		$res = $GLOBALS['TYPO3_DB']->sql(TYPO3_db,$query);
		
		$row_counter = 0;
		
		// WS-VERSIONING: collect all new records and place them at the beginning of the array!
		// the new records have no name in live-space and therefore would be delegated 
		// to the very end of the list through the order by clause in function makeServiceListQuery....
		$eleminated_rows=0;
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res))	{
			
			// WS-VERSIONING: get the Preview from the Core!!!!
			// copied from tt_news
			// the function versionOL() in /t3lib/class.t3lib_page.php does 
			// the rendering of the version records for preview.
			// i.e. a new, not yet published record has the name ['PLACEHOLDER'] and is hidden in live-workspace, 
			// class.t3lib_page.php replaces the fields in question with content from its 
			// version-workspace-equivalent for preview purposes!
			// for it to work the result must also contain the records from live-workspace which carry the hidden-flag!!!
			if ($this->versioningEnabled) {
				// remember: versionOL generates field-list and cannot handle aliases!
				// i.e. there must not be any aliases in the query!!!
				$GLOBALS['TSFE']->sys_page->versionOL('tx_civserv_service',$row);
				
				if($this->previewMode){
					$row['realname']=$row['sv_name'];
					$row['name']=$row['realname'];
				}
					
			}
			
			if(is_array($row)){
				$services[$row_counter]['uid']=$row['uid']; //needed for preview-sorting see below
				$services[$row_counter]['t3ver_state']=$row['t3ver_state'];
				// customLinks will only work if there is an according rewrite-rule in action!!!!
				if(!$this->conf['useCustomLinks_Services']){
					$services[$row_counter]['link'] =  htmlspecialchars($this->pi_linkTP_keepPIvars_url(array(mode => 'service',id => $row['uid']),$this->conf['cache_services'],1));
				}else{
					$services[$row_counter]['link'] = strtolower($this->convert_plus_minus(urlencode($this->replace_umlauts($this->strip_extra($row['realname']."_".$row['uid']))))).".html";
				}
				if ($row['name'] == $row['realname']) {
					$services[$row_counter]['name'] = $row['name'];
				} else {
					// this will only happen in LIVE context
					$services[$row_counter]['name'] = $row['name'] . ' (= ' . $row['realname'] . ')';
				}
				// mark the version records for the FE, could be done by colour in template as well!
				if($row['_ORIG_pid']==-1 && $row['t3ver_state']==0){ // VERSION records
					$services[$row_counter]['name'].=" DRAFT: ".$row['uid'];
					$services[$row_counter]['preview']=1;
				}elseif($row['_ORIG_pid']==-1 && $row['t3ver_state']==-1){ // NEW records
					$services[$row_counter]['name'].=" NEW: ".$row['uid'];
					$services[$row_counter]['preview']=1;
				}else{
					// LIVE!!
					#$services[$row_counter]['name'].= " ".$row['uid'];
				}
				
				
				//for the online_service list we want form descr. and  service picture as well.
				if($this->piVars['mode'] == "online_services"){
					$res_online_services = $GLOBALS['TYPO3_DB']->exec_SELECT_mm_query(
						'tx_civserv_form.fo_descr, 
						 tx_civserv_service.sv_image, 
						 tx_civserv_service.sv_image_text',
						'tx_civserv_service',
						'tx_civserv_service_sv_form_mm',
						'tx_civserv_form',
						'AND tx_civserv_service.uid = ' . $row['uid'] . '
		 				 AND tx_civserv_form.hidden = 0 
						 AND tx_civserv_form.deleted = 0
						 AND tx_civserv_form.fo_status >= 2',
						'',
						'');	
						
					
					if ($online_sv_row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_online_services) ) { //we only accept one online-form per service!!!!
						$imagepath = $this->conf['folder_organisations'] . $this->community['id'] . '/images/';
						#function getImageCode($image,$path,$conf,$altText)	{
						$imageCode = $this->getImageCode($online_sv_row['sv_image'],$imagepath,$this->conf['service-image.'],$online_sv_row['sv_image_text']);
						$services[$row_counter]['descr']=$online_sv_row['fo_descr']; 
						$services[$row_counter]['image']=$imageCode;
						$services[$row_counter]['image_text']=$online_sv_row['sv_image_text']; 
					}
				}
				
				// highlight_external services in list view!! (works only if function makeServiceListQuery returns pid-value!)
				$mandant = t3lib_div::makeInstanceClassName('tx_civserv_mandant');
				$mandantInst = new $mandant();
				$service_community_id= $mandantInst->get_mandant($row['pid']);
				$service_community_name = $mandantInst->get_mandant_name($row['pid']);
				if($this->community['id'] != $service_community_id){
					$services[$row_counter]['name'] .= " <i> - ".$service_community_name."</i>";
				}
				$row_counter++;
			}else{
				// NULL-rows (obsolete)
				$eleminated_rows++;
			}
		} //end while
		
		
		if($this->previewMode){
			// we need to re_sort the services_array in order to incorporate the 
			// new services which appear at the very end (due to "order by name" meets "[PLACEHOLDER]")
		
			#http://forum.jswelt.de/serverseitige-programmierung/10285-array_multisort.html
			#http://www.php.net/manual/en/function.array-multisort.php
			#http://de.php.net/array_multisort#AEN9158
			
			//found solution here:
			#http://www.php-resource.de/manual.php?p=function.array-multisort
			
			$lowercase_uids=array();
			//ATTENTION: multisort is case sensitive!!!
			for($i=0; $i<count($services); $i++){
				$name=$services[$i]['name'];
				// service-name starts with lowercase letter
				if(strcmp(substr($name,0,1), strtoupper(substr($name,0,1)))>0){
					// make it uppercase
					$services[$i]['name']=strtoupper(substr($name,0,1)).substr($name,1,strlen($name));
					// remember which one it was you manipulated
					$lowercase_uids[]=$services[$i]['uid'];
				}
				$sortarray[$i] = $services[$i]['name'];
			}
			
			// DON'T! or else multisort won't work! (must be something about keys and indexes??)
			# natcasesort($sortarray);
			
			// $services is sorted by $sortarray as in SQL "ordery by name"
			// $sortarray itself gets sorted by multisort!!! 
			if(is_array($sortarray) && count($sortarray)>0){
				array_multisort($sortarray, SORT_ASC, $services);
			}
			
			for($i=0; $i<count($services); $i++){
				$name=$services[$i]['name'];
				// reset the converted service-names to their initial value (lowercase first letter)
				if(in_array($services[$i]['uid'],$lowercase_uids)){
					$services[$i]['name']=strtolower(substr($name,0,1)).substr($name,1,strlen($name));
				}
			}
		}
		
		
		// Retrieve the service count
		$row_count = 0;
		//	function makeServiceListQuery($char=all,$limit=true,$count=false) {
		$query = $this->makeServiceListQuery($this->piVars['char'],false,true);
		$res = $GLOBALS['TYPO3_DB']->sql(TYPO3_db,$query);
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			$row_count += $row['count(*)'];
		}

		$row_count = $row_count-$eleminated_rows;
		$this->internal['res_count'] = $row_count;
		
		if(!$this->previewMode){
			$this->internal['results_at_a_time']= $this->conf['services_per_page'];
		}else{
			$this->internal['results_at_a_time']=10000; //in Preview Mode we need ALL records - new ones from the end of the alphabethical list are to be included!!!
		}
		
		$this->internal['maxPages'] = $this->conf['max_pages_in_pagebar'];
		
		
		$smartyServiceList->assign('services',$services);
		
		if ($abcBar) {
			$query = $this->makeServiceListQuery(all,false);
			$smartyServiceList->assign('abcbar',$this->makeAbcBar($query));
		}
		$smartyServiceList->assign('heading',$this->getServiceListHeading($this->piVars['mode'],$this->piVars['id']));
		
		
		// if the title is set here it will overwrite the value we want in the organisationDetail-View
		// but we do need it for circumstance and user_groups!
		$GLOBALS['TSFE']->page['title'] = $this->getServiceListHeading($this->piVars['mode'],$this->piVars['id']);
		
		if($this->piVars['char']>''){
			 $GLOBALS['TSFE']->page['title'] .=': '.$this->pi_getLL('tx_civserv_pi1_service_list.abc_letter','Letter').' '.$this->piVars['char'];
		}
		
		if ($searchBox) {
			//$_SERVER['REQUEST_URI'] = $this->pi_linkTP_keepPIvars_url(array(mode => 'search_result'),0,1); //dropped this according to instructions from security review
			$smartyServiceList->assign('searchbox', $this->pi_list_searchBox('',true));
		}

		if ($topList) {
			if (!$this->calculate_top15($smartyServiceList,false,$this->conf['topCount'])) {
				return false;
			}
		}
		//test bk: the service_list template is being used by several modes, change the subheading in accordance with the modes!!!
		if($this->piVars['mode'] == 'organisation')$smartyServiceList->assign('subheading',$this->pi_getLL('tx_civserv_pi1_service_list.available_services','Here you find the following services'));
		$smartyServiceList->assign('pagebar',$this->pi_list_browseresults(true,'', ' '.$this->conf['abcSpacer'].' '));

		return true;
	}
	

	/**
	 * Generates a database query for the function serviceList. The returned query depends on the given parameter (like described below)
	 * and the piVars 'mode', 'char' and 'pointer', additionally the pidlist for the actual community is fetched from the class variable community.
	 * The returned query contains UNIONs.
	 *
	 * @param	string		The beginning character, the list should be limited to. Can also be a sequence of beginning characters.
	 * @param	boolean		If true, the list is limited to 'max_services_per_page' (constant from $this->conf) services per page. The page number is fetched from piVars['pointer'].
	 * @param	boolean		If true, the services are only counted.
	 * @return	string		The database query
	 */
	function makeServiceListQuery($char=all,$limit=true,$count=false) {
		//for versioning we need the hidden records, will be dismissed from live-display in fct. service_list....
		$from  =	'tx_civserv_service';
		$where =	'tx_civserv_service.deleted = 0';
		$where .= 	$this->versioningEnabled? ' AND NOT (tx_civserv_service.t3ver_state=0 AND tx_civserv_service.hidden=1)':' AND tx_civserv_service.hidden = 0';
		$where .=	$this->previewMode? ' AND (tx_civserv_service.t3ver_wsid = '.$this->current_ws.' OR tx_civserv_service.t3ver_wsid=0) ':' AND tx_civserv_service.t3ver_state !=1';
		$where .= 	' AND ((UNIX_TIMESTAMP(LOCALTIMESTAMP) BETWEEN tx_civserv_service.starttime AND tx_civserv_service.endtime)
					 	OR ((UNIX_TIMESTAMP(LOCALTIMESTAMP) > tx_civserv_service.starttime) AND (tx_civserv_service.endtime = 0))
					 	OR (tx_civserv_service.starttime = 0 AND tx_civserv_service.endtime = 0))';
		$namefield_str = $this->previewMode? 'sv_name' : 'name';	//in previewMode we skip the synonyms!	
		$orderby =	$this->piVars['sort']? $namefield_str.' DESC': $namefield_str.' ASC';
		
		switch ($this->piVars['mode']) {
			case 'service_list':
				$where .=	'';
				break;
			case 'circumstance':
			case 'usergroup':
				$from  .=	', tx_civserv_navigation, ###NAVIGATION_MM_TABLE###';
				$where .= 	'AND ###SERVICE_TABLE###.uid = ###NAVIGATION_MM_TABLE###.uid_local
							 AND ###NAVIGATION_MM_TABLE###.uid_foreign = tx_civserv_navigation.uid
							 AND tx_civserv_navigation.uid = ' . intval($this->piVars['id']);	//SQL-Injection!!!
				break;
			case 'organisation':
				$from  .=	', tx_civserv_organisation, tx_civserv_service_sv_organisation_mm';
				$where .= 	'AND tx_civserv_service.uid =  tx_civserv_service_sv_organisation_mm.uid_local
							 AND tx_civserv_service_sv_organisation_mm.uid_foreign = tx_civserv_organisation.uid
							 AND tx_civserv_organisation.uid = ' . intval($this->piVars['id']);	//SQL-Injection!!!
				break;
			// not yet implemented in the main() function
			case 'employee_service_list':
				$from  .=	', tx_civserv_service_sv_position_mm, tx_civserv_position, tx_civserv_employee, tx_civserv_employee_em_position';
				$where .=	'AND tx_civserv_service.uid = tx_civserv_service_sv_position_mm.uid_local
							 AND tx_civserv_service_sv_position_mm.uid_foreign = tx_civserv_position.uid
							 AND tx_civserv_employee_em_position.ep_position = tx_civserv_position.uid
							 AND tx_civserv_employee_em_position.ep_employee = tx_civserv_employee.uid
							 AND tx_civserv_employee.uid = ' . intval($this->piVars['id']);	//SQL-Injection!!!
				break;
			case 'online_services':
				$from  .=	', tx_civserv_service_sv_form_mm, tx_civserv_form';
				$where .=	'AND tx_civserv_service.uid = tx_civserv_service_sv_form_mm.uid_local
							 AND tx_civserv_service_sv_form_mm.uid_foreign = tx_civserv_form.uid
							 AND tx_civserv_form.fo_status > 1';
				break;	
		}

		if ($char != all) {
			$regexp = $this->buildRegexp($char);
		}

		$query = '';
		
		// EXTERNAL Services:
		// The first time the loop is executed, the part of the query for selecting the services
		// which are located directly at the community is build.
		// The second time the loop is executed, the part of the query for selecting the services 
		// located at another community is build.
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
							 AND tx_civserv_external_service.deleted = 0
							 AND tx_civserv_external_service.hidden = 0
							 AND tx_civserv_external_service.pid IN (' . $this->community['pidlist'] . ')';
				$query .= 'UNION ALL ';
			}

			// manipulate pidlist in order to discriminate between services in the common language and services in an alternative language:
			if($GLOBALS["TSFE"]->id == $this->community['alternative_page_uid']){
				$this->community['pidlist']=$this->community['alternative_language_folder_uid'];
			}else{
				$toggled = array_flip(explode(",",$this->community['pidlist']));
				$key = $this->community['alternative_language_folder_uid'];
				unset($toggled[$key]);
				$this->community['pidlist']=implode(",",array_flip($toggled));
			}

			// versioning:
			// we need the hidden records as well.
			// because: for every new record in any version workspace a hidden record in live workspace is created (by versioning sysext)
			
			//  highlight_external services -> select pid as well


			// services by realnames
			if($this->previewMode){ // no aliases! (see above, versionOL can't handle aliases)
				$query .=	'SELECT ' . ($count?'count(*) ':'tx_civserv_service.uid, tx_civserv_service.pid, tx_civserv_service.hidden, tx_civserv_service.sv_name, tx_civserv_service.t3ver_oid, tx_civserv_service.t3ver_wsid, tx_civserv_service.t3ver_state');
			}else{
				$query .=	'SELECT ' . ($count?'count(*) ':'tx_civserv_service.uid, tx_civserv_service.pid, tx_civserv_service.hidden, sv_name AS name, sv_name AS realname, tx_civserv_service.t3ver_oid, tx_civserv_service.t3ver_wsid, tx_civserv_service.t3ver_state');
			}
			$query .=	' FROM ' . str_replace('###NAVIGATION_MM_TABLE###',$navigation_mm_table,$from) . '
						 WHERE ' . str_replace('###SERVICE_TABLE###',$service_table,str_replace('###NAVIGATION_MM_TABLE###',$navigation_mm_table,$where)) . ' ' .
							(($i==1)?'AND tx_civserv_service.pid IN (' . $this->community['pidlist'] . ') ':'') .
							($regexp?'AND sv_name REGEXP "' . $regexp . '"':'') . ' ';

			// services by synonyms
			if(!$this->previewMode && $this->piVars['mode'] != 'online_services'){
				for ($synonymNr = 1; $synonymNr <= 3; $synonymNr++) {
	
					$query .=	'UNION ALL
								 SELECT ' . ($count?'count(*) ':'tx_civserv_service.uid, tx_civserv_service.pid, tx_civserv_service.hidden, sv_synonym' . $synonymNr . ' AS name, sv_name AS realname, tx_civserv_service.t3ver_oid, tx_civserv_service.t3ver_wsid, tx_civserv_service.t3ver_state') . '
								 FROM ' . str_replace('###NAVIGATION_MM_TABLE###',$navigation_mm_table,$from) . '
								 WHERE ' . str_replace('###SERVICE_TABLE###',$service_table,str_replace('###NAVIGATION_MM_TABLE###',$navigation_mm_table,$where)) . ' ' .
									(($i==1)?'AND tx_civserv_service.pid IN (' . $this->community['pidlist'] . ') ':'') .
									($regexp?'AND sv_synonym' . $synonymNr . ' REGEXP "' . $regexp . '"':'') .
									'AND sv_synonym' . $synonymNr . ' != "" ' . ' ';
				}
			}
			//versioning:
			if($char != all && $this->previewMode && $regexp){
				// collect the orig-Ids from all the versions whoes name starts with the letter in question
				$oid_list=array();
				//todo: make this query better according to workspace documentation
				$res_temp=$GLOBALS['TYPO3_DB']->exec_SELECTquery(
							't3ver_oid',
							'tx_civserv_service',
							'pid = -1 and sv_name REGEXP "'.$regexp.'" AND t3ver_state != 2',
							'',
							'',
							'');
				while ($row_temp = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_temp) ) {
					if(in_array($row_temp[t3ver_oid], $oid_list)){
						//we don't want double entries.
					}else{
						$oid_list[]=$row_temp[t3ver_oid];
					}
				}
				// collect all NEW service records (name = [PLACEHOLDER], t3ver_state = 1)
				$query .=	count($oid_list)>0 ?	'UNION ALL
													 SELECT ' . ($count?'count(*) ':'tx_civserv_service.uid, tx_civserv_service.hidden, tx_civserv_service.sv_name, tx_civserv_service.pid, tx_civserv_service.t3ver_oid, tx_civserv_service.t3ver_wsid, tx_civserv_service.t3ver_state').'
													 FROM ' . str_replace('###NAVIGATION_MM_TABLE###',$navigation_mm_table,$from).'
													 WHERE ' . str_replace('###SERVICE_TABLE###',$service_table,str_replace('###NAVIGATION_MM_TABLE###',$navigation_mm_table,$where)).' '.
													 	'AND tx_civserv_service.uid IN ('.implode(',', $oid_list).') AND tx_civserv_service.t3ver_state=1 ' : ''; //t3ver_state=1 means only the new ones!!!!
			}
		}

		if (!$count) {
			$query .= 'ORDER BY ' . $orderby . ' ';
			if ($limit) {
				if ($this->piVars['pointer'] > '' && !$this->previewMode) {
					$start = $this->conf['services_per_page'] * $this->piVars['pointer'];
				} else {
					$start = 0;
				}
				if(!$this->previewMode){
					$count = $this->conf['services_per_page'];
				}else{
					$count =10000;
				}
				$query .= 'LIMIT ' . $start . ',' . $count;
			}
		}
		return $query;
	}


	/**
	 * Builds the heading for service list (used from function serviceList()). The heading depends on the mode and,
	 * if mode ist not 'service_list', the selected organisation id or navigation id.
	 *
	 * @param	string		The mode like given in piVars['mode'].
	 * @param	integer		The uid from the selected organisation or circumstancd/usergroup.
	 * @return	string		The heading
	 */
	function getServiceListHeading($mode,$uid) {
		switch ($mode) {
			case 'service_list':
				$heading = $this->pi_getLL('tx_civserv_pi1_service_list.service_list','Service list');
				#$heading = $this->pi_getLL('tx_civserv_pi1_service_list.overview','Overview');
				if(!$this->conf['includeNameInHeading']){
					// test bk: premature return instruction to prevent that anything is added to the heading
					return $heading;
				}else{
					break;
				}
			case 'online_services':
				$heading = $this->pi_getLL('tx_civserv_pi1_service_list.online_service_list','Online Services');
				if(!$this->conf['includeNameInHeading']){
					// test bk: premature return instruction to prevent that anything is added to the heading
					return $heading;
				}else{
					break;
				}
			case 'service' :// test bk: make it fitting for ms layout, useless: fct getservicelistheading never called from serviceDetail!!!
				$heading = $this->pi_getLL('tx_civserv_pi1_service_list.overview','Overview');
				if(!$this->conf['includeNameInHeading']){
					// test bk: premature return instruction to prevent that anything is added to the heading
					return $heading;
				}else{
					break;
				}
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
				$heading = $this->pi_getLL('tx_civserv_pi1_organisation_list.organisation_list','Organisation');
				// test bk: make it fitting for ms layout
				#$heading = $this->pi_getLL('tx_civserv_pi1_organisation_list.organisation_list.heading','Organisation');
				if($this->conf['includeNameInHeading']){
					$field = 'or_name';
					$table = 'tx_civserv_organisation';
					break;
				}else{
					// test bk: premature return instruction to prevent that anything is added to the heading
					return $heading;
				}
				break;
		}
		if ($mode != 'service_list' && $mode != 'online_services') {
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
		$mode = $this->piVars['mode'];
		$content = $this->makeTree($uid,$content,$mode);
		$smartyTree->assign('content',$content);

		if ($searchBox) {
			//$_SERVER['REQUEST_URI'] = $this->pi_linkTP_keepPIvars_url(array(mode => 'search_result'),0,1); //dropped this according to instructions from security review
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
		
		// test b.k. introduce special Headings for city of Münster
		$smartyTree->assign('circumstance_tree_heading',sprintf($this->pi_getLL('tx_civserv_pi1_circumstance.circumstance_tree.heading','Circumstances'), $this->community['name']));
		$smartyTree->assign('usergroup_tree_heading',sprintf($this->pi_getLL('tx_civserv_pi1_usergroup.usergroup_tree.heading','Usergroups'), $this->community['name']));
		$smartyTree->assign('organisation_tree_heading',sprintf($this->pi_getLL('tx_civserv_pi1_organisation.organisation_tree.heading','Organisation Structure'), $this->community['name']));
		
		#$smartyOrganisationList->assign('subheading',$this->pi_getLL('tx_civserv_pi1_organisation_list.available_organisations','Here you find the following organisations'));
		
		return true;
	}
	
	/**
	 * Generates a list of all organisations
	 *
	 * @param	[type]		$smartyOrganisationList: ...
	 * @param	[type]		$abcBar: ...
	 * @param	[type]		$searchBox: ...
	 * @param	[type]		$topList: ...
	 * @return	[type]		...
	 */
	function organisation_list(&$smartyOrganisationList, $abcBar=false,$searchBox=false,$topList=false){
		$query = $this->makeOrganisationListQuery($this->piVars['char']);
		$res_organisation = $GLOBALS['TYPO3_DB']->sql(TYPO3_db,$query);
		$row_counter = 0;
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_organisation) ) {
			if ($row['name'] == $row['realname']) {
				$organisations[$row_counter]['name'] = $row['name'];
			} else {
				$organisations[$row_counter]['name'] = $row['name'] . ' (= ' . $row['realname'] . ')';
			}
			if(!$this->conf['useCustomLinks_Organisations']){
				$organisations[$row_counter]['or_url'] = htmlspecialchars($this->pi_linkTP_keepPIvars_url(array(mode => 'organisation',id => $row['or_uid']),1,1));
			}else{
				$organisations[$row_counter]['or_url'] = strtolower($this->convert_plus_minus(urlencode($this->replace_umlauts($this->strip_extra($row['realname']."_".$row['or_uid']))))).".html";
			}
			$row_counter++;
		}


		// Retrieve the organisation count
		$row_count = 0;
		$query = $this->makeOrganisationListQuery($this->piVars['char'],false,true);
		$res = $GLOBALS['TYPO3_DB']->sql(TYPO3_db,$query);
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			#$row_count += $row['anzahl'];
			$row_count += $row['count(*)'];
		}

		$this->internal['res_count'] = $row_count;
		$this->internal['results_at_a_time']= $this->conf['organisation_per_page'];
		$this->internal['maxPages'] = $this->conf['max_pages_in_pagebar'];

		$smartyOrganisationList->assign('heading',$this->pi_getLL('tx_civserv_pi1_organisation_list.organisation_list.heading','Organisation'));
		$smartyOrganisationList->assign('subheading',$this->pi_getLL('tx_civserv_pi1_organisation_list.available_organisations','Here you find the following organisations'));
		$smartyOrganisationList->assign('pagebar',$this->pi_list_browseresults(true,'', ' '.$this->conf['abcSpacer'].' '));
		$smartyOrganisationList->assign('organisations',$organisations);

		if ($abcBar) {
			$query = $this->makeOrganisationListQuery(all,false);
			$smartyOrganisationList->assign('abcbar', $this->makeAbcBar($query));
		}
		
		if ($searchBox) {
			//$_SERVER['REQUEST_URI'] = $this->pi_linkTP_keepPIvars_url(array(mode => 'search_result'),0,1); //dropped this according to instructions from security review
			$smartyOrganisationList->assign('searchbox', $this->pi_list_searchBox('',true));
		}
		
		if ($topList) {
			if (!$this->calculate_top15($smartyOrganisationList,false,$this->conf['topCount'])) {
				return false;
			}
		}
		
		return true;
	}
	
	/**
	 * Generates a database query for the function organisation_list. The returned query depends on the given parameter (like described below)
	 * and the piVars 'char' and 'pointer', additionally the pidlist for the actual community is fetched from the class variable community.
	 *
	 * @param	[type]		$char: ...
	 * @param	[type]		$limit: ...
	 * @param	[type]		$count: ...
	 * @return	[type]		...
	 */

	function makeOrganisationListQuery($char=all,$limit=true,$count=false) {
			if ($char != all) {
				$regexp = $this->buildRegexp($char);
			}
			if ($count){
				$query = 'SELECT count(*) 
					FROM 
						tx_civserv_organisation 
					WHERE 
						tx_civserv_organisation.pid IN (' . $this->community['pidlist'] . ') AND 
						tx_civserv_organisation.deleted = 0 AND
						tx_civserv_organisation.hidden = 0 '.
						($regexp?'AND or_name REGEXP "' . $regexp . '"':'') . ' ';		
			} else {
				$query = 'SELECT 
						tx_civserv_organisation.uid as or_uid,';
						if($this->conf['displayOrganisationCode']){
						#if(1==2){	
							$query .= 'CONCAT(tx_civserv_organisation.or_name, \' [\', tx_civserv_organisation.or_code, \']\') AS name,';
							$query .= 'CONCAT(tx_civserv_organisation.or_name, \' [\', tx_civserv_organisation.or_code, \']\') AS realname,';
						}else{
							$query .= 'tx_civserv_organisation.or_name AS name,';			
							$query .= 'tx_civserv_organisation.or_name AS realname,';			
						}
				 		$query .= 'tx_civserv_organisation.or_code,
						 		   tx_civserv_organisation.or_index ';
					$query .= 'FROM 
								tx_civserv_organisation
							WHERE 
								tx_civserv_organisation.pid IN (' . $this->community['pidlist'] . ') '
								. ($regexp?'AND or_name REGEXP "' . $regexp . '"':'') . 'AND 
								tx_civserv_organisation.deleted = 0 AND
								tx_civserv_organisation.hidden = 0';
			} //end else
			
			
			
			for ($synonymNr = 1; $synonymNr <= 3; $synonymNr++) {
				$query .=	"\n";
				$query .=	' UNION ALL ';
				$query .=	"\n";
				$query .=	' SELECT ';
				if($count){
					$query .= 'count(*)';
				}else{
					$query .=  'tx_civserv_organisation.uid as or_uid,
								or_synonym' . $synonymNr . ' AS name, ';
					if($this->conf['displayOrganisationCode']){
					#if(1==2){	
						$query .= 'CONCAT(or_name, \' [\', tx_civserv_organisation.or_code, \']\') AS realname, '; //tut nicht mehr
					}else{
						$query .= 'or_name AS realname,';
					}			
					$query .= 	'tx_civserv_organisation.or_code,
								 tx_civserv_organisation.or_index ';
					$query .=	"\n";			 
							$query .= 	'FROM tx_civserv_organisation
							 WHERE 	tx_civserv_organisation.pid IN (' . $this->community['pidlist'] . ') '
								. ($regexp?'AND or_synonym' . $synonymNr . ' REGEXP "' . $regexp . '"':'') .
								'AND or_synonym' . $synonymNr . ' != "" ' . ' ';
				}
			}//end for

			$orderby =	$this->piVars['sort']? $this->conf['orderOrgalistBy'].' DESC': $this->conf['orderOrgalistBy'].' ASC';
			
			if (!$count) {
			$query .= ' ORDER BY ' . $orderby . ' ';

				if ($limit) {
					if ($this->piVars['pointer'] > '') {
						$start = $this->conf['organisation_per_page'] * $this->piVars['pointer'];
					} else {
						$start = 0;
					}
					$max = $this->conf['organisation_per_page'];
					$query .= 'LIMIT ' . $start . ',' . $max;
					}
	
			}
			return $query;
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
		$query = $this->makeEmployeeListQuery($this->piVars['char']);
		$res_employees = $GLOBALS['TYPO3_DB']->sql(TYPO3_db,$query);
		$row_counter = 0;
		$em_org_kombis=array(); //store all combinations of an employee and his/her employing organisation unit here
		$kills=array(); //will be used to eleminate dublicates from the above list
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_employees) ) {
				$employees[$row_counter]['em_uid']=$row['emp_uid'];
				if($row['em_address']==2){
					$employees[$row_counter]['address_long'] = $this->pi_getLL('tx_civserv_pi1_organisation.address_female', 'Ms.');
				}elseif($row['em_address']==1){
					$employees[$row_counter]['address_long'] = $this->pi_getLL('tx_civserv_pi1_organisation.address_male', 'Mr.');
				}
				$employees[$row_counter]['title'] = $row['em_title'];
				$employees[$row_counter]['name'] = $row['name']; //alias in makeEmployeeListQuery, need for generic makeAbcBar
				$employees[$row_counter]['firstname'] = $row['em_firstname'];
				$employees[$row_counter]['em_datasec'] = $row['em_datasec'];

				//select the organisation assigned to the employee
				$orga_res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
					'tx_civserv_position.uid as pos_uid, 
					 tx_civserv_organisation.uid as or_uid, 
					 tx_civserv_employee.uid as emp_uid, 
					 or_name as organisation',
					'tx_civserv_employee, 
					 tx_civserv_position, 
					 tx_civserv_organisation, 
					 tx_civserv_employee_em_position_mm, 
					 tx_civserv_position_po_organisation_mm',
					'tx_civserv_employee.uid = ' . $row['emp_uid'] . ' 
					 AND tx_civserv_position.uid = '.$row['pos_uid'] .'
					 AND tx_civserv_organisation.deleted = 0 AND tx_civserv_organisation.hidden = 0
					 AND tx_civserv_employee.deleted = 0 AND tx_civserv_employee.hidden = 0
					 AND tx_civserv_position.deleted = 0 AND tx_civserv_position.hidden = 0
					 AND tx_civserv_organisation.deleted = 0 AND tx_civserv_organisation.hidden = 0
					 AND tx_civserv_employee.uid = tx_civserv_employee_em_position_mm.uid_local
					 AND tx_civserv_employee_em_position_mm.uid_foreign = tx_civserv_position.uid
					 AND tx_civserv_position.uid = tx_civserv_position_po_organisation_mm.uid_local
					 AND tx_civserv_organisation.uid = tx_civserv_position_po_organisation_mm.uid_foreign',
					 '',
					 '',
					 '');
					while ($orga_row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($orga_res) ) {
						$test_string=$employees[$row_counter]['em_uid'].'_'.$orga_row['organisation'];
						//only elminate dublicates if there is no data_sec (an employee with several positions within the same 
						//organisational unit might still have different opening hours for each of them
						if($employees[$row_counter]['data_sec']==0 && in_array($test_string, $em_org_kombis)){
							$kills[]=$row_counter;
						}else{
							$employees[$row_counter]['orga_name'] = $orga_row['organisation'];
							$em_org_kombis[]=$employees[$row_counter]['em_uid'].'_'.$employees[$row_counter]['orga_name'];
						}
					}
					$employees[$row_counter]['em_url'] = htmlspecialchars($this->pi_linkTP_keepPIvars_url(array(mode => 'employee',id => $row['emp_uid'],pos_id => $row['pos_uid']),1,1));
					$row_counter++;
		}
		foreach($kills as $kill){
			unset($employees[$kill]);
		}


		// Retrieve the employee count
		$row_count = 0;
		$query = $this->makeEmployeeListQuery($this->piVars['char'],false,true);
		$res = $GLOBALS['TYPO3_DB']->sql(TYPO3_db,$query);
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			$row_count += $row['count(*)'];
		}

		$this->internal['res_count'] = $row_count;
		$this->internal['results_at_a_time']= $this->conf['employee_per_page'];
		$this->internal['maxPages'] = $this->conf['max_pages_in_pagebar'];

		$smartyEmployeeList->assign('heading',$this->pi_getLL('tx_civserv_pi1_employee_list.employee_list.heading','Employees'));
		$smartyEmployeeList->assign('subheading',$this->pi_getLL('tx_civserv_pi1_employee_list.available_employees','Here you find the following employees'));
		$smartyEmployeeList->assign('pagebar',$this->pi_list_browseresults(true,'',' '.$this->conf['abcSpacer'].' '));
		$smartyEmployeeList->assign('employees',$employees);
		

		if ($abcBar) {
			$query = $this->makeEmployeeListQuery(all,false);
			$smartyEmployeeList->assign('abcbar',$this->makeAbcBar($query));
		}
		
		if ($searchBox) {
			//$_SERVER['REQUEST_URI'] = $this->pi_linkTP_keepPIvars_url(array(mode => 'search_result'),0,1); //dropped this according to instructions from security review
			$smartyEmployeeList->assign('searchbox', $this->pi_list_searchBox('',true));
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
	function makeEmployeeListQuery($char=all,$limit=true,$count=false) {
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
						tx_civserv_employee.deleted = 0 AND
						tx_civserv_employee.hidden = 0 AND
						tx_civserv_employee.em_pseudo = 0'.
						($regexp?'AND em_name REGEXP "' . $regexp . '"':'') . ' ';
			} else {
				$query = 'Select 
						tx_civserv_employee.em_address, 
						tx_civserv_employee.em_title, 
						tx_civserv_employee.em_name as name, 
						tx_civserv_employee.em_firstname, 
						tx_civserv_employee.em_datasec,
						tx_civserv_employee.uid as emp_uid, 
						tx_civserv_position.uid as pos_uid 
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
						tx_civserv_employee.em_pseudo = 0';
			}

			$orderby =	$this->piVars['sort']?'name, em_firstname DESC':'name, em_firstname ASC';


			if (!$count) {
			$orderby =	$this->piVars['sort']?'name, em_firstname DESC':'name, em_firstname ASC';
			$query .= ' ORDER BY ' . $orderby . ' ';


				if ($limit) {
					if ($this->piVars['pointer'] > '') {
						$start = $this->conf['employee_per_page'] * $this->piVars['pointer'];
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
		
		
		//query to check, if any categories are being used at all at the mandant's:
		$cat_count=0;
		$query_cat = $this->makeFormListQuery('all',$organisation_id,1,false,true); // 1: do consider categories for query
		$res_cat = $GLOBALS['TYPO3_DB']->sql(TYPO3_db,$query_cat);
		// only taking first row of resultset means only the mandants own forms are being counted for this.
		// external forms would be in the second row
		if ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_cat)) {
			$cat_count = $row['count(DISTINCT tx_civserv_form.uid)'];
		}

		$query = $this->makeFormListQuery($this->piVars['char'],$organisation_id,0); // do not consider categories for query!
		$forms_res = $GLOBALS['TYPO3_DB']->sql(TYPO3_db,$query);

		$form_row_counter = 0;
		$actual_category=0;
		$all_forms=array();
		$category_names=array();
		$form_names=array();
		
		while ($form_row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($forms_res) ) {
			//test b.k.: get the categories indiviually (because we do not want to lose those forms having no category
			$res_cats = $GLOBALS['TYPO3_DB']->exec_SELECT_mm_query(
						'tx_civserv_category.ca_name',
						'tx_civserv_form',
						'tx_civserv_form_fo_category_mm',
						'tx_civserv_category',
						'AND tx_civserv_category.deleted = 0 AND tx_civserv_category.hidden = 0
						 AND tx_civserv_form.deleted = 0 AND tx_civserv_form.hidden = 0
						 AND tx_civserv_form.uid = ' . $form_row['uid'],
						'',
						'',
						'');
			//only once of course!!!!			
			if ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_cats))	{
				$form_row['cat']=$row['ca_name'];
			}else{
				if($form_row['typ']== 'e'){ // extra Category for Forms-without-a-category donated by the County, bigger Community, etc....
					// todo: get locallang......
					$form_row['cat']='externe Formulare';
				}
			}
			if($form_row['typ']== 'e'){ // highlighting for all Forms donated by the County, bigger Community, etc....
				// todo: get locallang......
				// not useful until we can name the donator!
				// $form_row['name'].=' (externes Formular)';
			}
			$all_forms[]=$form_row;
		}
		
		
		//check on cases!!!!
		$lowercase_cat_uids=array(); // not needed
		$lowercase_foname_uids=array(); // not needed
		//ATTENTION: multisort is case sensitive!!!
		for($i=0; $i<count($all_forms); $i++){
			if($cat_count > 0){ // mandant uses categories
				// todo: get text from locallang properly!!!
				// i.e. only if we want to have an extra categorie for unclassified forms (forms without a category)
				#$all_forms[$i]['cat']=!$all_forms[$i]['cat']?'nix Zuordnung':$all_forms[$i]['cat'];
			}else{ // not one category used at the mandant's
				$all_forms[$i]['cat']='0';
			}
			$cat_name=$all_forms[$i]['cat'];
			$fo_name=$all_forms[$i]['name'];
			// service-name starts with lowercase letter
			if(strcmp(substr($cat_name,0,1), strtoupper(substr($cat_name,0,1)))>0){
				// make it uppercase
				$all_forms[$i]['cat']=strtoupper(substr($cat_name,0,1)).substr($cat_name,1,strlen($cat_name));
				// remember which one it was you manipulated
				$lowercase_cat_uids[]=$all_forms[$i]['uid'];
			}
			if(strcmp(substr($fo_name,0,1), strtoupper(substr($fo_name,0,1)))>0){
				// make it uppercase
				$all_forms[$i]['name']=strtoupper(substr($fo_name,0,1)).substr($fo_name,1,strlen($fo_name));
				// remember which one it was you manipulated
				$lowercase_foname_uids[]=$all_forms[$i]['uid'];
			}
			$category_names[$i] = $all_forms[$i]['cat'];
			$form_names[$i] = $all_forms[$i]['name'];
		}
		
		//apply multisort() to $all_forms......
		if(is_array($all_forms) && count($all_forms)>0){
			array_multisort($category_names, SORT_ASC, $form_names, SORT_ASC, $all_forms);
		}
		//reinstate lower_case category_names.....? there shouldn't be any!!!
		
		
		
		foreach ($all_forms as $single_form) {
			//test b.k.: get the categories indiviually (because we do not want to lose those forms 
			// having no category)
			//cast value of actual_category into string!
			if($single_form['cat'] != "".$actual_category){
				$actual_category =$single_form['cat'];
			}
			$forms[$actual_category][$form_row_counter]['name'] = $this->pi_getEditIcon(
																				$single_form['name'],
																				'fo_name',
																				$this->pi_getLL('tx_civserv_pi1_form_list.name','form name'),
																				$single_form,
																				'tx_civserv_form');
			$forms[$actual_category][$form_row_counter]['descr'] = $this->formatStr($this->local_cObj->stdWrap($this->pi_getEditIcon(trim($single_form['descr']),'fo_descr',$this->pi_getLL('tx_civserv_pi1_form_list.description','form description'),$single_form,'tx_civserv_form'),$this->conf['fo_name_stdWrap.']));


			$from='tx_civserv_service, 
				   tx_civserv_form,
				   tx_civserv_service_sv_form_mm';
				   
			$where='tx_civserv_service.hidden = 0 AND 
					 tx_civserv_service.deleted = 0 AND
					 ((UNIX_TIMESTAMP(LOCALTIMESTAMP) BETWEEN tx_civserv_service.starttime AND tx_civserv_service.endtime) OR
					 ((UNIX_TIMESTAMP(LOCALTIMESTAMP) > tx_civserv_service.starttime) AND (tx_civserv_service.endtime = 0)) OR
					 (tx_civserv_service.starttime = 0 AND tx_civserv_service.endtime = 0)) AND
					 tx_civserv_service.uid = tx_civserv_service_sv_form_mm.uid_local AND
					 tx_civserv_form.uid = tx_civserv_service_sv_form_mm.uid_foreign AND
					 tx_civserv_form.uid = '.$single_form['uid'];
						 
			if ($single_form['typ'] == 'e'){
				$from .= ',tx_civserv_external_service';
				$where .= ' AND 
						 tx_civserv_external_service.hidden = 0 AND 
						 tx_civserv_external_service.deleted = 0 AND
						 tx_civserv_external_service.es_external_service = tx_civserv_service.uid AND 
						 tx_civserv_external_service.pid IN ('.$this->community['pidlist'].')';
			}

			// select the services assigned to the form
			$services_res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
						'tx_civserv_service.uid, 
						 tx_civserv_service.pid, 
						 tx_civserv_service.sv_name AS name',
						$from,
						$where,
						'',
						'',
						'');
						
			$service_row_counter = 0;
			while ($service_row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($services_res)) {
				$forms[$actual_category][$form_row_counter]['services'][$service_row_counter]['name'] = $service_row['name'];
				$forms[$actual_category][$form_row_counter]['services'][$service_row_counter]['link'] = htmlspecialchars($this->pi_linkTP_keepPIvars_url(array(mode => 'service',id => $service_row['uid']),$this->conf['cache_services'],1));
				if (!array_key_exists($service_row['pid'],array_flip(explode(',',$this->community['pidlist'])))) {
						$mandant = t3lib_div::makeInstanceClassName('tx_civserv_mandant');
						$mandantInst = new $mandant();
						$service_community = $mandantInst->get_mandant($service_row['pid']);
						$folder_forms = $this->conf['folder_services'];
						$folder_forms .= $service_community . '/forms/';
				}else {
			         $folder_forms = $this->conf['folder_services'];
			         $folder_forms .= $this->community['id'] . '/forms/';
		       }
			   $service_row_counter++;
			}
			if ($single_form['checkbox'] == 1) {
				$forms[$actual_category][$form_row_counter]['url'] = $this->cObj->typoLink_URL(array(parameter => $single_form['url']));
			} else {
				$forms[$actual_category][$form_row_counter]['url'] = $folder_forms . $single_form['file'];
			}
			
			
			if(preg_match('/http/',$forms[$actual_category][$form_row_counter]['url'])){
#				debug('its an Alien!!!!');
			}else{
#				debug('its one of us!!!!');
			}
			if(preg_match('/.pdf$/',$forms[$actual_category][$form_row_counter]['url']) || preg_match('/pdf.form-solutions.net/',$forms[$actual_category][$form_row_counter]['url'])){
				$forms[$actual_category][$form_row_counter]['icon'] = $this->iconDir."pdf.gif";
			}elseif(preg_match('/.doc$/',$forms[$actual_category][$form_row_counter]['url'])){
				$forms[$actual_category][$form_row_counter]['icon'] = $this->iconDir."doc.gif";
			}elseif(preg_match('/.odt$/',$forms[$actual_category][$form_row_counter]['url'])){
				$forms[$actual_category][$form_row_counter]['icon'] = $this->iconDir."sxw.gif";	
			}elseif(preg_match('/.sxw$/',$forms[$actual_category][$form_row_counter]['url'])){
				$forms[$actual_category][$form_row_counter]['icon'] = $this->iconDir."sxw.gif";			
			}elseif(preg_match('/.html$/',$forms[$actual_category][$form_row_counter]['url'])){
				$forms[$actual_category][$form_row_counter]['icon'] = $this->iconDir."html.gif";			
			}elseif(preg_match('/.gif$/',$forms[$actual_category][$form_row_counter]['url'])){
				$forms[$actual_category][$form_row_counter]['icon'] = $this->iconDir."gif.gif";			
			}elseif(preg_match('/.php$/',$forms[$actual_category][$form_row_counter]['url'])){
				$forms[$actual_category][$form_row_counter]['icon'] = $this->iconDir."php3.gif";			
			}elseif(preg_match('/.zip$/',$forms[$actual_category][$form_row_counter]['url'])){
				$forms[$actual_category][$form_row_counter]['icon'] = $this->iconDir."zip.gif";			
			}elseif(preg_match('/.txt$/',$forms[$actual_category][$form_row_counter]['url'])){
				$forms[$actual_category][$form_row_counter]['icon'] = $this->iconDir."txt.gif";			
			}else{
				$forms[$actual_category][$form_row_counter]['icon'] = $this->iconDir."default.gif";
			}

			$form_row_counter++;
		}

		// getting the form count
		$row_count = 0;
		$query = $this->makeFormListQuery($this->piVars['char'],$organisation_id,0,false,true); // 0: do not consider categories for query
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
			$query = $this->makeFormListQuery(all,$organisation_id,0,false); // 0: do not consider categories for query
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
				$heading .= $row['name'];
			} else {
				$GLOBALS['error_message'] = $this->pi_getLL('tx_civserv_pi1_error.unvalid_organisation','An invalid organisation id was given.');
				return false;
			}
		} else {
			$heading .= $this->pi_getLL('tx_civserv_pi1_form_list.overview','Overview');
		}
		
		// test bk: ??? Münster???
		$GLOBALS['TSFE']->page['title'] = $heading;
		$smartyFormList->assign('heading',$heading);
		$smartyFormList->assign('subheading',$this->pi_getLL('tx_civserv_pi1_form_list.available_forms','Here you find the following forms'));
		$smartyFormList->assign('assigned_services',$this->pi_getLL('tx_civserv_pi1_form_list.assigned_services','The following services are assigned with this form'));
		$smartyFormList->assign('pagebar',$this->pi_list_browseresults(true,'', ' '.$this->conf['abcSpacer'].' '));

		if ($searchBox) {
			//$_SERVER['REQUEST_URI'] = $this->pi_linkTP_keepPIvars_url(array(mode => 'search_result'),0,1); //dropped this according to instructions from security review
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
						'tx_civserv_organisation.pid IN (' . $this->community['pidlist'] . ')
					 	 AND tx_civserv_form.hidden = 0 AND tx_civserv_form.deleted = 0
						 AND ((UNIX_TIMESTAMP(LOCALTIMESTAMP) BETWEEN tx_civserv_form.starttime AND tx_civserv_form.endtime)
					 	 OR ((UNIX_TIMESTAMP(LOCALTIMESTAMP) > tx_civserv_form.starttime) AND (tx_civserv_form.endtime = 0))
					 	 OR (tx_civserv_form.starttime = 0 AND tx_civserv_form.endtime = 0))
					 	 AND tx_civserv_service.hidden = 0 AND tx_civserv_service.deleted = 0
						 AND ((UNIX_TIMESTAMP(LOCALTIMESTAMP) BETWEEN tx_civserv_service.starttime AND tx_civserv_service.endtime)
					 	 OR ((UNIX_TIMESTAMP(LOCALTIMESTAMP) > tx_civserv_service.starttime) AND (tx_civserv_service.endtime = 0))
					 	 OR (tx_civserv_service.starttime = 0 AND tx_civserv_service.endtime = 0))
					 	 AND tx_civserv_organisation.hidden = 0 AND tx_civserv_organisation.deleted = 0
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
	 * @param	boolean		If true, the list is limited to 'forms_per_page' (constant from $this->conf) services per page. The page number is fetched from piVars['pointer'].
	 * @param	boolean		If true, the services are only counted.
	 * @return	string		The database query.
	 */
	function makeFormListQuery($char=all,$organisation_id=0,$orderByCategory=0,$limit=true,$count=false) {
		if ($count) {
			//$select = 'count(*)';
			//change proposed by kreis warendorf to eliminate duplicates
			$select = 'count(DISTINCT tx_civserv_form.uid)';
		} else {
			$select = 	'tx_civserv_form.uid,
						 tx_civserv_form.fo_name AS name, 
						 tx_civserv_form.fo_descr AS descr, 
						 tx_civserv_form.fo_external_checkbox AS checkbox, 
						 tx_civserv_form.fo_url AS url, 
						 tx_civserv_form.fo_formular_file AS file,
						 "i" as typ'; //pseudo-column for internal services
		}
		$from  =	'tx_civserv_form, tx_civserv_service, tx_civserv_service_sv_form_mm';
		$where =	'tx_civserv_form.deleted = 0 AND tx_civserv_form.hidden = 0
					 AND ((UNIX_TIMESTAMP(LOCALTIMESTAMP) BETWEEN tx_civserv_form.starttime AND tx_civserv_form.endtime)
					 OR ((UNIX_TIMESTAMP(LOCALTIMESTAMP) > tx_civserv_form.starttime) AND (tx_civserv_form.endtime = 0))
					 OR (tx_civserv_form.starttime = 0 AND tx_civserv_form.endtime = 0))
				 	 AND tx_civserv_service.hidden = 0 AND tx_civserv_service.deleted = 0
					 AND ((UNIX_TIMESTAMP(LOCALTIMESTAMP) BETWEEN tx_civserv_service.starttime AND tx_civserv_service.endtime)
				 	 OR ((UNIX_TIMESTAMP(LOCALTIMESTAMP) > tx_civserv_service.starttime) AND (tx_civserv_service.endtime = 0))
				 	 OR (tx_civserv_service.starttime = 0 AND tx_civserv_service.endtime = 0))
				 	 AND tx_civserv_service.uid = tx_civserv_service_sv_form_mm.uid_local
				 	 AND tx_civserv_form.uid = tx_civserv_service_sv_form_mm.uid_foreign';
		if ($organisation_id != 0) {
			$from  .=	', tx_civserv_organisation, tx_civserv_service_sv_organisation_mm';
			$where .=	' AND tx_civserv_organisation.hidden = 0 AND tx_civserv_organisation.deleted = 0
					 	 AND tx_civserv_service.uid = tx_civserv_service_sv_organisation_mm.uid_local
					 	 AND tx_civserv_organisation.uid = tx_civserv_service_sv_organisation_mm.uid_foreign
						 AND tx_civserv_organisation.uid = ' . $organisation_id;
		}
		if ($orderByCategory) { // only used for counting categories.......
			$from	.=	', tx_civserv_category, tx_civserv_form_fo_category_mm';
			$where	.=	' AND tx_civserv_category.uid =  tx_civserv_form_fo_category_mm.uid_foreign AND 
						 tx_civserv_form.uid = tx_civserv_form_fo_category_mm.uid_local';
		}

		if ($char != all) {
			$regexp = $this->buildRegexp($char);
		}

		$orderby = $this->piVars['sort']?'name DESC':'name ASC';
		

		// The first time the loop is executed, the part of the query for selecting the services which are located directly at the community is build.
		// The second time the loop is executed, the part of the query for selecting the services located at another community is build.
		$query = '';
		for ($i = 1; $i <= 2; $i++) {
			if ($i == 2) {
				if (!$count){
					$select = 'tx_civserv_form.uid,
	   						   tx_civserv_form.fo_name AS name, 
						 	   tx_civserv_form.fo_descr AS descr, 
							   tx_civserv_form.fo_external_checkbox AS checkbox, 
							   tx_civserv_form.fo_url AS url, 
							   tx_civserv_form.fo_formular_file AS file,
							   "e" as typ'; // pseudo-column for external services (same select-statement but different 'typ'!
				}
				$from  .=	', tx_civserv_external_service';
				$where .=	' ' .
							'AND tx_civserv_external_service.es_external_service = tx_civserv_service.uid
							 AND tx_civserv_external_service.deleted = 0
							 AND tx_civserv_external_service.hidden = 0
							 AND tx_civserv_external_service.pid IN (' . $this->community['pidlist'] . ')';
				$query .= 'UNION ALL ';
			}
			//change proposed by Kreis Warendorf 24.01.05: we don't want double entries, so we go for DISTINCT
			$query .=	'SELECT DISTINCT ' . $select . '
						 FROM ' . $from . '
						 WHERE ' . $where . ' ' .
							(($i==1)?'AND tx_civserv_form.pid IN (' . $this->community['pidlist'] . ') ':' ') .
							($regexp?'AND fo_name REGEXP "' . $regexp . '" ':' ');
		}
		if (!$count) {
			$query .= 'ORDER BY ' . $orderby . ' ';

			if ($limit) {
				if ($this->piVars['pointer'] > '') {
					$start = $this->conf['forms_per_page'] * $this->piVars['pointer'];
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
		#$searchString = ereg_replace('"', '', $searchString);	//Delete quotation marks from search value
		$searchString=$this->check_searchword(strip_tags($searchString)); //strip and check to avoid xss-exploits
		$sword = preg_split('/[\s,.\"]+/',$searchString);		//Split search string into multiple keywords and store them in an array

		//Set initial where clauses
		$querypart_where = ' pid IN (' . $this->community['pidlist'] . ')';
		$querypart_where2 = ' sw.uid = tx_civserv_service_sv_searchword_mm.uid_foreign
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
						$querypart_where3 .= ' ms_name LIKE "%' . $sword[$i] . '%"
											  OR ms_synonym1 LIKE "%' . $sword[$i] . '%"
											  OR ms_synonym2 LIKE "%' . $sword[$i] . '%"
											  OR ms_synonym3 LIKE "%' . $sword[$i] . '%"';
						$querypart_where4 .= ' sw_search_word LIKE "%' . $sword[$i] . '%" ';
						$querypart_where5 = ' AND (sv_name LIKE "%' . $sword[$i] . '%"
											  OR sv_synonym1 LIKE "%' . $sword[$i] . '%"
											  OR sv_synonym2 LIKE "%' . $sword[$i] . '%"
											  OR sv_synonym3 LIKE "%' . $sword[$i] . '%"';	
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
					$querypart_where5 .= ' OR sv_name LIKE "%' . $sword[$i] . '%"
										  OR sv_synonym1 LIKE "%' . $sword[$i] . '%"
										  OR sv_synonym2 LIKE "%' . $sword[$i] . '%"
										  OR sv_synonym3 LIKE "%' . $sword[$i] . '%"';
				}
			}

			//Query for getting uid list of matching search words
			$res_searchword = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
						'uid',
						'tx_civserv_search_word',
						$querypart_where4 . ' AND deleted = 0 AND hidden = 0',
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
					$searchword_uid_array = explode(",", $row['ms_searchword']);
					for ($i = 0 ; $i < count($searchword_uid_array) ; $i++) {
						if ($uidlist_searchwords != NULL && in_array(array('uid' => $searchword_uid_array[$i]),$uidlist_searchwords)) {
							$searchword_uid_list .=  $list_start ? $row['uid'] : ',' . $row['uid'];    //Add model service uid to match list
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
					 WHERE sv.deleted = 0 AND sv.hidden = 0 AND ((UNIX_TIMESTAMP(LOCALTIMESTAMP) BETWEEN sv.starttime AND sv.endtime) OR
														   ((UNIX_TIMESTAMP(LOCALTIMESTAMP) > sv.starttime) AND (sv.endtime=0)) OR
														   (sv.starttime=0 AND sv.endtime=0) ) AND ' . $querypart_where . ')
					 UNION
					 SELECT sv.uid as uid, sv.sv_name as name
					 FROM tx_civserv_service as sv, tx_civserv_search_word as sw, tx_civserv_service_sv_searchword_mm
					 WHERE sv.deleted = 0 AND sv.hidden = 0 AND sw.deleted = 0 AND sw.hidden = 0
					 								  AND ((UNIX_TIMESTAMP(LOCALTIMESTAMP) BETWEEN sv.starttime AND sv.endtime) OR
														   ((UNIX_TIMESTAMP(LOCALTIMESTAMP) > sv.starttime) AND (sv.endtime=0)) OR
														   (sv.starttime=0 AND sv.endtime=0) )
													  AND sv.pid IN (' . $this->community['pidlist'] . ') AND ' . $querypart_where2 . ')
					 UNION
					 SELECT sv.uid as uid, sv.sv_name as name
					 FROM tx_civserv_service as sv, tx_civserv_model_service as ms
					 WHERE sv.deleted = 0 AND sv.hidden = 0 AND ms.deleted = 0 AND ms.hidden = 0
													  AND ((UNIX_TIMESTAMP(LOCALTIMESTAMP) BETWEEN sv.starttime AND sv.endtime) OR
														   ((UNIX_TIMESTAMP(LOCALTIMESTAMP) > sv.starttime) AND (sv.endtime=0)) OR
														   (sv.starttime=0 AND sv.endtime=0) )
													  AND sv.sv_model_service = ms.uid AND sv.pid IN (' . $this->community['pidlist'] . ')
													  AND (' . $querypart_where3 . ')
					UNION
					SELECT sv.uid as uid, sv.sv_name as name
					FROM tx_civserv_service as sv, tx_civserv_external_service
					WHERE sv.deleted = 0 AND sv.hidden = 0 AND
  						  tx_civserv_external_service.hidden = 0 AND tx_civserv_external_service.deleted = 0 
														AND ((UNIX_TIMESTAMP(LOCALTIMESTAMP) BETWEEN sv.starttime AND sv.endtime) OR
														   ((UNIX_TIMESTAMP(LOCALTIMESTAMP) > sv.starttime) AND (sv.endtime=0)) OR
														   (sv.starttime=0 AND sv.endtime=0) ) 
													   AND tx_civserv_external_service.pid  in (' . $this->community['pidlist'] . ')
													   AND tx_civserv_external_service.es_external_service = sv.uid ' . $querypart_where5 . ')
					UNION
					SELECT sv.uid as uid, sv.sv_name as name 
					FROM tx_civserv_service as sv, tx_civserv_search_word as sw, tx_civserv_service_sv_searchword_mm, tx_civserv_external_service
					WHERE sv.deleted = 0 AND sv.hidden = 0 AND sw.deleted = 0 AND sw.hidden = 0 AND
						  tx_civserv_external_service.hidden = 0 AND tx_civserv_external_service.deleted = 0  
													  AND ((UNIX_TIMESTAMP(LOCALTIMESTAMP) BETWEEN sv.starttime AND sv.endtime) OR
														  ((UNIX_TIMESTAMP(LOCALTIMESTAMP) > sv.starttime) AND (sv.endtime=0)) OR
														  (sv.starttime=0 AND sv.endtime=0) )
														  AND tx_civserv_external_service.pid IN (' . $this->community['pidlist'] . ') 
														  AND ' . $querypart_where2 . ')
														  AND tx_civserv_external_service.es_external_service = sv.uid			
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
					//$_SERVER['REQUEST_URI'] = $this->pi_linkTP_keepPIvars_url(array(mode => 'search_result'),0,1); //dropped this according to instructions from security review
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
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('sv.uid as uid,
													   sv.sv_name as name,
													   SUM(al.al_number) as number',	// SELECT
													  'tx_civserv_accesslog as al,
													   tx_civserv_service as sv',		// FROM
													  'sv.deleted = 0 
													   AND sv.hidden = 0 
													   AND sv.uid = al.al_service_uid 
													   AND sv.pid IN (' . $this->community['pidlist'] . ')',		// WHERE
														 'al.al_service_uid',									// GROUP BY
														 'number DESC',											// ORDER BY
														 $topN); 												// LIMIT
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
			//$_SERVER['REQUEST_URI'] = $this->pi_linkTP_keepPIvars_url(array(mode => 'search_result'),0,1); //dropped this according to instructions from security review
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
	 * is determined from the result set of the given query. The link for each character is build by adding piVars['char'] to the actual url.
	 * Used by the functions 'serviceList' and 'formList'.
	 *
	 * @param	string		A query which gets all items.
	 * @return	string		HTML-Code for abc-bar.
	 */
	 
	// test bk: add local_mode 
	// this is default function for rendering of ABC-bar
	function makeAbcBar($query, $local_mode="") {
		$correctMode=$this->piVars['mode'];
		// getting all accouring initial from the DB
		$res = $GLOBALS['TYPO3_DB']->sql(TYPO3_db,$query);
		//test bk: swapp mode to organisation_list for correct abcbar in orga-Detail
		if($local_mode != ""){
			$this->piVars['mode']=$local_mode;
		}

		$row_counter = 0;
		

		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res))	{
			$namefield_arr = ($this->previewMode && ($this->piVars['mode'] == 'service_list' || $this->piVars['mode'] == 'service'))? $row['sv_name'] : $row['name'];	//in previewMode we skip the synonyms of services! because the overlay-function can't handle aliases
			$initial = str_replace(array('Ä','Ö','Ü'),array('A','O','U'),strtoupper($namefield_arr{0}));
			$occuringInitials[] = $initial;
			$row_counter++;
		}
		if ($occuringInitials ) $occuringInitials = array_unique($occuringInitials);
		

		$alphabet = array(A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z);

		// build a string with the links to the character-sites
		$abcBar =  '<p id="abcbar">' . "\n\t";
		for($i = 0; $i < sizeof($alphabet); $i++)	{
			$actual = (strtoupper($this->piVars['char']) == $alphabet[$i]);
			if($occuringInitials && in_array($alphabet[$i],$occuringInitials))	{
			// Warning: sprintf(): Too few arguments in /html/typo3conf/ext/civserv/pi1/class.tx_civserv_pi1.php on line 1941

				$test = sprintf('%s' . $this->pi_linkTP_keepPIvars($alphabet[$i],array(char => $alphabet[$i],pointer => 0),1,0) . '%s '.$this->conf['abcSpacer'].' ',
						$actual?'<strong>':'',
						$actual?'</strong>':'');

				$abcBar .= sprintf('%s' . $this->pi_linkTP_keepPIvars($alphabet[$i],array(char => $alphabet[$i],pointer => 0),1,0) . '%s '.$this->conf['abcSpacer'].' ',
						$actual?'<strong>':'',
						$actual?'</strong>':'');
			}
			else{
				$abcBar .= '<span class="nomatch">'.$alphabet[$i].'</span> '.$this->conf['abcSpacer'].' ';
			}
		}

		// adding the link 'A-Z'
		$actual = ($this->piVars['char'] <= '');
		
		$linkconf = array();
		$name = 'hase';
		$url = $this->pi_linkTP_keepPIvars_url(array(char => '', pointer => 0), 1, 0);
#		$linkconf['ATagParams'] =' title="'.$name.'" alt="'.$name.'" class="all"';
		$linkconf['ATagParams'] =' class="all"';
		$linkconf['parameter'] = $url;
		$abcBar .= 	sprintf(	'%s' .	
								$this->local_cObj->typoLink('A-Z', $linkconf) .
								'%s' . "\n",
								$actual?'<strong>':'',
								$actual?'</strong>':''
							);
		
		/*
		$abcBar .= 	sprintf(	'%s' .
								$this->pi_linkTP_keepPIvars('A-Z',array(char => '', pointer => 0),1,0) .
								'%s' . "\n",
								$actual?'<strong>':'',
								$actual?'</strong>':''
						);
		*/				
		$abcBar .= "</p>\n";

		$this->piVars['mode']=$correctMode;
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
	function makeTree($uid, $add_content, $mode) {
		global $add_content;
		//Execute query depending on mode
		if ($mode == 'circumstance_tree' || $mode == 'usergroup_tree') {
			$result = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
								'nv1.uid as uid, 
								 nv1.nv_name as name',
								'tx_civserv_navigation as nv1,
								 tx_civserv_navigation_nv_structure_mm as nvmm,
								 tx_civserv_navigation as nv2',
								'nv1.deleted = 0 
								 AND nv1.hidden = 0 
								 AND nv2.deleted = 0 
								 AND nv2.hidden = 0
								 AND nv1.uid = nvmm.uid_local 
								 AND nv2.uid = nvmm.uid_foreign
								 AND nv2.uid = ' . $uid ,
								'',
								'name',
								'');
		}
		// test bk: add organisational code
		if($this->conf['displayOrganisationCode']){
			$orderby='or1.or_code, or1.sorting';
		}else{
			$orderby='or1.sorting, or2.sorting';
		}
		if ($mode == 'organisation_tree') {
			$result = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
								'or1.uid as uid, 
								 or1.or_code as code, 
								 or1.or_name as name',
								'tx_civserv_organisation as or1,
								 tx_civserv_organisation_or_structure_mm as ormm,
								 tx_civserv_organisation as or2',
								'or1.deleted = 0 
								 AND or1.hidden = 0 
								 AND or2.deleted = 0 
								 AND or2.hidden = 0
								 AND or1.uid = ormm.uid_local 
								 AND or2.uid = ormm.uid_foreign
								 AND or2.uid = ' . $uid ,
								'',
								$orderby,
								'');
		}
		//Check if query returned any results
		if ($GLOBALS['TYPO3_DB']->sql_num_rows($result) > 0) {
			$add_content = $add_content .  '<ul>';
			while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($result)) {
				$uid = $row['uid']; // or1 res. nv1
				$makelink=false;
				if($this->conf['no_link_empty_nv']){
#					debug('no_link_empty_nv gesetzt!');
				}else{
#					debug('no_link_empty_nv NICHT gesetzt!');
				}
				$res_connected_services = $GLOBALS['TYPO3_DB']->exec_SELECT_mm_query(
						'tx_civserv_service.sv_name',
						'tx_civserv_service',
						'tx_civserv_service_sv_navigation_mm',
						'tx_civserv_navigation',
						'AND tx_civserv_service_sv_navigation_mm.uid_foreign = '.$uid,
						'');
				switch ($mode) {
					case 'circumstance_tree':
						$link_mode = 'circumstance';
						if(($GLOBALS['TYPO3_DB']->sql_num_rows($res_connected_services) < 1) && $this->conf['no_link_empty_nv']){
							$makelink = false;
						}else{
							$makelink = true;
						}
						break;
					case 'usergroup_tree':
						$link_mode = 'usergroup';
						if(($GLOBALS['TYPO3_DB']->sql_num_rows($res_connected_services) < 1) && $this->conf['no_link_empty_nv']){
							$makelink = false;
						}else{
							$makelink = true;
						}
						break;
					case 'organisation_tree':
						$link_mode = 'organisation';
						$makelink = true; // we want detail-information to all organisations 
/*						
// no! we want detail-information to all organisations - not only to those who offer services.....
						$res_connected_services = $GLOBALS['TYPO3_DB']->exec_SELECT_mm_query(
								'tx_civserv_service.sv_name',
								'tx_civserv_service',
								'tx_civserv_service_sv_organisation_mm',
								'tx_civserv_organisation',
								'AND tx_civserv_service_sv_organisation_mm.uid_foreign = '.$uid,
								'');
*/								
						break;
				}
				if($this->conf['hide_empty_nv'] && $makelink == false){
					$this->makeTree($uid, $add_content, $mode);
				}else{
					$add_content .= '<li>';
					$add_content .= $makelink ? '<a href="' . htmlspecialchars($this->pi_linkTP_keepPIvars_url(array(mode => $link_mode,id => $row['uid']),1,1)) . '">' : '';
					// test bk: add organisational code
					if($this->conf['displayOrganisationCode'] && !($mode=='usergroup_tree' || $mode=='circumstance_tree')){
						$add_content .= $row['code'].' '.$row['name'];
					}else{
						$add_content .= $row['name'];
					}
					$add_content .= $makelink ? '</a>': '';
					$this->makeTree($uid, $add_content, $mode);
					$add_content .= "</li>\n";
				}
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
	function serviceDetail(&$smartyService,$searchBox=false,$topList=false, $continueAbcBarFromServiceList=false) {
		
		// first the basics:
		// service_pidlist is needed for identifying the Employees: in case of an external service the employes have 
		// to be retrieved from the pidlist of the community providing the service. 
		// that is an excemption to the rule that all data is fetched from within $this->community_pidlist
		$service_pidlist="";
	
		//test bk: repeat the heading from the list:
		$smartyService->assign('heading',$this->pi_getLL('tx_civserv_pi1_service_list.overview','Overview' ));
		if($continueAbcBarFromServiceList){
			$query = $this->makeServiceListQuery(all,false);
			$smartyService->assign('abcbarServiceList_continued', $this->makeAbcBar($query, 'service_list'));
		}
		
		$uid = intval($this->piVars['id']);	//SQL-Injection!!!
		$community_id = $this->community['id'];
		$employee = $this->community['employee_search'];
				
		//search Employee Details
		$smartyService->assign('employee_search',$employee);

		//Set path to forms of services
		$folder_forms = $this->conf['folder_services'];
		$folder_forms .= $this->community['id'] . '/forms/';

		//Query for standard service details
		$res_common = $this->queryService(intval($uid));
		$service_common = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_common);
		#$service_common['typ']='i';
		
		

		if ($this->versioningEnabled) {
			// get workspaces Overlay
			// versionOL can't handle marker-field 'typ' 
			$GLOBALS['TSFE']->sys_page->versionOL('tx_civserv_service',$service_common);
			// fix pid for record from workspace
			$GLOBALS['TSFE']->sys_page->fixVersioningPid('tx_civserv_service',$service_common);
		}

		// versioning:
		if($service_common['_ORIG_pid']== -1 && $service_common['_ORIG_uid']>0){ // this means we are looking at the details of a version-record!!!
			// for the display of associated emplopyee-, organisation-, etc. records we need the 
			// uid of the record in the version-workspace !!!
			$uid=$service_common['_ORIG_uid'];
			$smartyService->assign('preview',1); //through this flag we can identification of workspace-records in preview
		}
		
		
		
		//Check if service is an external service and swap the pid_list if it is! so you can show the right contact persons!!
		if (!array_key_exists($service_common['pid'],array_flip(explode(',',$this->community['pidlist'])))) {
			$article='';
			$mandant = t3lib_div::makeInstanceClassName('tx_civserv_mandant');
			$mandantInst = new $mandant();
			$service_community = $mandantInst->get_mandant($service_common['pid']);
			$folder_forms = $this->conf['folder_services'];
			$folder_forms .= $service_community . '/forms/';
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
											'cm_community_name,
											 cm_uid',
											'tx_civserv_conf_mandant',
											'cm_community_id = ' . $service_community);
			$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
			//todo: get $article from locallang
			$town=explode(' ',$row['cm_community_name']);
			switch($town[0]){
				case 'Stadt':
					$article= 'die ';
				break;
				case 'Gemeinde':
					$article= 'die ';
				break;
				case 'Kreis':
					$article= 'den ';
				break;
				default:
				break;	
			}
			$smartyService->assign('external_service_label',$this->pi_getLL('tx_civserv_pi1_service.external_service','This service is provided and advised by') . ' ' . $article . $row['cm_community_name']);
			$service_pidlist= $this->pi_getPidList($row['cm_uid'],$this->conf['recursive']);
			$service_common['typ']='e';
		} else {
			$service_community = $this->community['id'];
			$service_pidlist= $this->community['pidlist'];
			$service_common['typ']='i';
		}


		//Query for associated forms
		$res_forms = $GLOBALS['TYPO3_DB']->exec_SELECT_mm_query(
						'tx_civserv_form.uid as uid, 
						 fo_name as name, 
						 fo_url as url, 
						 fo_formular_file as file, 
						 fo_external_checkbox as checkbox, 
						 fo_target as target',
						'tx_civserv_service',
						'tx_civserv_service_sv_form_mm',
						'tx_civserv_form',
						'AND tx_civserv_service.uid = ' . $uid . '
		 				 AND tx_civserv_form.hidden = 0 AND tx_civserv_form.deleted = 0
		 				 AND ((UNIX_TIMESTAMP(LOCALTIMESTAMP) BETWEEN tx_civserv_form.starttime AND tx_civserv_form.endtime)
						 	  OR ((UNIX_TIMESTAMP(LOCALTIMESTAMP) > tx_civserv_form.starttime) AND (tx_civserv_form.endtime=0))
						 	  OR (tx_civserv_form.starttime=0 AND tx_civserv_form.endtime=0) )',
						'',
						'tx_civserv_service_sv_form_mm.sorting');	//ORDER BY
						#'name');	//ORDER BY


		//Query for associated organisation units
		$res_orga = $GLOBALS['TYPO3_DB']->exec_SELECT_mm_query(
						'tx_civserv_organisation.uid as uid, or_name as name',
						'tx_civserv_service',
						'tx_civserv_service_sv_organisation_mm',
						'tx_civserv_organisation',
						'AND tx_civserv_service.uid = ' . $uid . '
		 				 AND tx_civserv_organisation.hidden = 0 AND tx_civserv_organisation.deleted = 0',
						'',
						'tx_civserv_service_sv_organisation_mm.sorting');	//ORDER BY
						#'name');	//ORDER BY



		// change b.k.: for the display of contact persons also the field ep_datasac of the employee-position relation is relevant.
		// Query for associated employees
		$res_employees = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
						'tx_civserv_employee.uid as emp_uid, 
						 tx_civserv_position.uid as pos_uid, 
						 tx_civserv_service.uid as sv_uid, 
						 tx_civserv_service_sv_position_mm.sp_descr as description, 
						 em_address as address, 
						 em_title as title, 
						 em_name as name, 
						 em_firstname as firstname, 
						 em_telephone, 
						 ep_telephone, 
						 em_email, 
						 ep_email, 
						 em_datasec as em_datasec,
						 ep_datasec as ep_datasec', // additional
						'tx_civserv_service, 
						 tx_civserv_service_sv_position_mm, 
						 tx_civserv_position, 
						 tx_civserv_employee, 
						 tx_civserv_employee_em_position_mm',
						'tx_civserv_service.uid = ' . $uid . '
						 AND tx_civserv_service.deleted = 0 AND tx_civserv_service.hidden = 0
						 AND tx_civserv_position.deleted = 0 AND tx_civserv_position.hidden = 0
						 AND tx_civserv_employee.deleted = 0 AND tx_civserv_employee.hidden = 0
						 AND tx_civserv_employee_em_position_mm.deleted = 0 AND tx_civserv_employee_em_position_mm.hidden = 0
						 AND tx_civserv_service.uid = tx_civserv_service_sv_position_mm.uid_local
						 AND tx_civserv_service_sv_position_mm.uid_foreign = tx_civserv_position.uid
						 AND tx_civserv_employee.uid = tx_civserv_employee_em_position_mm.uid_local
						 AND tx_civserv_employee_em_position_mm.uid_foreign = tx_civserv_position.uid 
						 AND tx_civserv_employee.pid IN (' . $service_pidlist . ')', // in case of external service this is the pidlist of the community providing the service!!
						'',
						'tx_civserv_service_sv_position_mm.sorting, tx_civserv_employee_em_position_mm.sorting');	//ORDER BY

		//Query for search words
		$res_search_word = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
						'tx_civserv_service.uid as suid, tx_civserv_search_word.uid as wuid, tx_civserv_service.sv_name as sname, tx_civserv_search_word.sw_search_word as sword',
						'tx_civserv_service, tx_civserv_search_word, tx_civserv_service_sv_searchword_mm',
						'tx_civserv_service.uid = ' . $uid . '
						AND tx_civserv_service.deleted = 0 AND tx_civserv_service.hidden = 0
						AND tx_civserv_service.uid = tx_civserv_service_sv_searchword_mm.uid_local
						AND tx_civserv_search_word.uid = tx_civserv_service_sv_searchword_mm.uid_foreign',
						'',
						'tx_civserv_search_word.sw_search_word'); //ORDER BY
						
		//Query for similar services
		
		// in case this is an external service the query must be asked differently!!!
		// this is still a building site
		$from= 'tx_civserv_service AS service,
						 tx_civserv_service_sv_similar_services_mm AS mm,
						 tx_civserv_service AS similar';
		$where= 'service.uid = mm.uid_local 
						 AND mm.uid_foreign = similar.uid 
						 AND service.uid = ' . $uid . ' 
						 AND service.uid != similar.uid 
						 AND similar.deleted = 0 AND similar.hidden = 0';

								  
		if($service_common['typ'] == 'e'){
			$from .= ', tx_civserv_external_service';
			$where .= ' AND tx_civserv_external_service.es_external_service = similar.uid 
					    AND tx_civserv_external_service.hidden =0';
		}
		  
		
		$res_similar = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
						'DISTINCT similar.uid AS uid, 
						 similar.sv_name AS name',
						$from,
						$where,
						'',						// GROUP BY
						'mm.sorting');			// ORDER BY
						#'similar.sv_name');	// ORDER BY

		//Retrieve all uid's of transaction forms from transaction configuration table
		$res_transactions = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
						'ct_transaction_uid as uid',
						'tx_civserv_conf_transaction',
						'tx_civserv_conf_transaction.ct_community_id  = ' . $community_id);

		if ($GLOBALS['TYPO3_DB']->sql_num_rows($res_common) == 0) {
			$GLOBALS['error_message'] = $this->pi_getLL('tx_civserv_pi1_service.error_valid','Service does not exist or is not available.');
			return false;
		}

		$service_employees = $this->sql_fetch_array_r($res_employees);

		$row_counter = 0;
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_similar))	{
			$similar[$row_counter]['link'] =  htmlspecialchars($this->pi_linkTP_keepPIvars_url(array(mode => 'service',id => $row['uid']),$this->conf['cache_services'],1));
			$similar[$row_counter]['name'] = $row['name'];
			$row_counter++;
		}

		//Add coloumns with url for email form and employee page to array $service_employees and format position description string
		for ($i = 0; $i < count($service_employees); $i++) {
			if($service_employees[$i]['address']==2){
				$service_employees[$i]['address_long'] = $this->pi_getLL('tx_civserv_pi1_organisation.address_female', 'Ms.');
			}elseif($service_employees[$i]['address']==1){
				$service_employees[$i]['address_long'] = $this->pi_getLL('tx_civserv_pi1_organisation.address_male', 'Mr.');
			}

			// use typolink, because of the possibility to use encrypted email-adresses for spam-protection
			$service_employees[$i]['email_code'] = $this->cObj->typoLink($service_employees[$i]['ep_email']?$service_employees[$i]['ep_email']:$service_employees[$i]['em_email'],array(parameter => $service_employees[$i]['ep_email']?$service_employees[$i]['ep_email']:$service_employees[$i]['em_email'],ATagParams => 'class="email"'));	// use typolink, because of the possibility to use encrypted email-adresses for spam-protection
			$service_employees[$i]['email_form_url'] = htmlspecialchars($this->pi_linkTP_keepPIvars_url(array(mode => 'set_email_form',id => $service_employees[$i]['emp_uid'],sv_id => $service_employees[$i]['sv_uid'],pos_id => $service_employees[$i]['pos_uid']),1,1));
			$service_employees[$i]['employee_url'] = htmlspecialchars($this->pi_linkTP_keepPIvars_url(array(mode => 'employee',id => $service_employees[$i]['emp_uid'],pos_id => $service_employees[$i]['pos_uid']),1,1));
			// Disabled by design issues
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
			$service_forms[$row_counter]['name'] = $row['name'];
			//Set correct url depending on type of associated form (transaction form, external form oder form file)
			if ($row['checkbox'] == 1 && in_array(array('uid' => intval($row['uid'])),$service_transactions)) {
				$service_forms[$row_counter]['url'] = $this->cObj->typoLink_URL(array(parameter => $row['url'])) . '&tx_civserv_pi1[id]=' . $uid;
			} elseif ($row['checkbox'] == 1) {
				$service_forms[$row_counter]['url'] = $this->cObj->typoLink_URL(array(parameter => $row['url']));
			} else {
				$service_forms[$row_counter]['url'] = $folder_forms.$row['file'];
			}
			$service_forms[$row_counter]['target'] = $row['target'];
			$row_counter++;
		}
		$smartyService->assign('forms',$service_forms);

		$row_counter = 0;
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_orga) ) {
			$service_organisations[$row_counter]['name'] = $row['name'];
			$service_organisations[$row_counter]['url'] = htmlspecialchars($this->pi_linkTP_keepPIvars_url(array(mode => 'organisation',id => $row['uid']),1,1));
			$row_counter++;
		}
		$smartyService->assign('organisations', $service_organisations);
		
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_search_word) ) {
			$search_words[] = $row['sword'];
		}
		if ($search_words > 0){
			$search_words = implode(", ", $search_words);
			$smartyService->assign('searchwords',$search_words);
		}

		//Query for model service
		if ($service_common['sv_model_service'] > 0) {
			$res_model_service = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
						'ms_name, 
						 ms_descr_short, 
						 ms_descr_long, 
						 ms_image, 
						 ms_image_text, 
						 ms_fees, 
						 ms_documents, 
						 ms_legal_global',
						'tx_civserv_model_service',
						'deleted = 0 AND hidden = 0 AND uid = ' . intval($service_common['sv_model_service']) . '');

			$model_service = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_model_service);
		}

		//Check for external service flag
		if ($service_common['sv_3rdparty_checkbox'] > 0) {
			$smartyService->assign('ext_link', $service_common['sv_3rdparty_link']);
			$smartyService->assign('ext_name', $service_common['sv_3rdparty_name']);
		}

		//Service name
		if ($service_common['sv_name'] != "") {
			$name = trim($service_common['sv_name']);
			// ATTENTION: Field-list is hardcoded, because there are problems with the image because of the upload folder
			$name = $this->pi_getEditIcon($name,	'fe_admin_fieldList" => "hidden, 
																			 starttime, 
																			 endtime, 
																			 fe_group, 
																			 sv_name, 
																			 sv_synonym1, 
																			 sv_synonym2, 
																			 sv_synonym3, 
																			 sv_descr_short, 
																			 sv_descr_long, 
																			 sv_fees, 
																			 sv_documents, 
																			 sv_legal_local, 
																			 sv_legal_global, 
																			 sv_model_service, 
																			 sv_similar_services, 
																			 sv_service_version, 
																			 sv_form, 
																			 sv_searchword, 
																			 sv_position, 
																			 sv_organisation, 
																			 sv_navigation, 
																			 sv_3rdparty_checkbox, 
																			 sv_3rdparty_link, 
																			 sv_3rdparty_name',
																			 $this->pi_getLL('tx_civserv_pi1_service.name','Service name'),
																			 $service_common,
																			 'tx_civserv_service'
																	);
		} else {
			$name = trim($model_service['ms_name']);
		}
		$smartyService->assign('name',$name);

		//Short description
		if ($service_common['sv_descr_short'] != "") {
			$descr_short = trim($service_common['sv_descr_short']);
			$descr_short = $this->pi_getEditIcon($descr_short,'sv_descr_short',$this->pi_getLL('tx_civserv_pi1_service.description_short','short description'),$service_common,'tx_civserv_service');
		} else {
			$descr_short = trim($model_service['ms_descr_short']);
		}
		$smartyService->assign('descr_short',$this->formatStr($this->local_cObj->stdWrap($descr_short,$this->conf['sv_descr_short_stdWrap.'])));

		//Long description
		$descr_long_ms = '';
		if ($model_service[ms_descr_long] != "") {
			$descr_long_ms = trim($model_service['ms_descr_long']) . '<br />';
		}
		$descr_long = trim($service_common['sv_descr_long']);
		$descr_long = $this->pi_getEditIcon($descr_long,'sv_descr_long',$this->pi_getLL('tx_civserv_pi1_service.description_long','Long description'),$service_common,'tx_civserv_service');
		$descr_long = $descr_long_ms . $descr_long;
		$smartyService->assign('descr_long',$this->formatStr($this->local_cObj->stdWrap($descr_long,$this->conf['sv_descr_long_stdWrap.'])));

		//Image text
		if ($service_common[sv_image_text] != "") {
			$image_text = trim($service_common['sv_image_text']);
		} else {
			$image_text = trim($model_service['ms_image_text']);
		}
		$image_descr = $this->pi_getEditIcon($image_text,'image_text',$this->pi_getLL('tx_civserv_pi1_service.image_text','Image description'),$service_common,'tx_civserv_service');
		$smartyService->assign('image_text',$image_descr);

		//Image
		if ($service_common['sv_image'] != "") {
			$image = $service_common['sv_image'];
			$imagepath = $this->conf['folder_organisations'] . $service_community . '/images/';
		} else {
			$image = $model_service['ms_image'];
			$imagepath = $this->conf['folder_organisations'] . 'model_services/images/';
		}
		if ($image) {
			$imageCode = $this->getImageCode($image,$imagepath,$this->conf['service-image.'],$image_text);
		}
		$smartyService->assign('image',$imageCode);

		//Fees
		if ($service_common['sv_fees'] != "") {
			$fees = trim($service_common['sv_fees']);
			$fees = $this->pi_getEditIcon($fees,'sv_fees',$this->pi_getLL('tx_civserv_pi1_service.description_fees','Fees'),$service_common,'tx_civserv_service');
		} else {
			$fees = trim($model_service['ms_fees']);
		}
		//test bk: support htmlarea in not desplaying empty labels; todo: transmit this to other sections!!!
		if(strip_tags($fees) > '' && strip_tags($documents) != '&nbsp;'){
			$smartyService->assign('fees',$this->formatStr($this->local_cObj->stdWrap($fees,$this->conf['sv_fees_stdWrap.'])));
		}

		//Documents
		if ($service_common['sv_documents'] != "") {
			$documents = trim($service_common['sv_documents']);
			$documents = $this->pi_getEditIcon($documents,'sv_documents',$this->pi_getLL('tx_civserv_pi1_service.description_documents','Necessary Documents'),$service_common,'tx_civserv_service');
		} else {
			$documents = trim($model_service['ms_documents']);
		}
		if(strip_tags($documents) > '' && strip_tags($documents) != '&nbsp;'){
			$smartyService->assign('documents',$this->formatStr($this->local_cObj->stdWrap($documents,$this->conf['sv_documents_general_stdWrap.'])));
		}

		//Legal local
		$legal_local = $this->pi_getEditIcon($service_common['sv_legal_local'],'sv_legal_local',$this->pi_getLL('tx_civserv_pi1_service.legal_local','Legal foundation (local)'),$service_common,'tx_civserv_service');
		$smartyService->assign('legal_local',$this->formatStr($this->local_cObj->stdWrap($legal_local,$this->conf['sv_legel_local_general_stdWrap.'])));

		//Legal global
		if ($service_common[sv_legal_global] != "") {
			$legal_global = trim($service_common['sv_legal_global']);
			$legal_global = $this->pi_getEditIcon($legal_global,'sv_legal_global',$this->pi_getLL('tx_civserv_pi1_service.legal_global','Legal foundation (global)'),$service_common,'tx_civserv_service');
		} else {
			$legal_global = trim($model_service['ms_legal_global']);
		}
		$smartyService->assign('legal_global',$this->formatStr($this->local_cObj->stdWrap($legal_global,$this->conf['sv_legal_global_general_stdWrap.'])));
		debug($similar, '$similar');
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
		if(count($service_employees)==1){
			if($service_employees[0]['address']==2){
				$smartyService->assign('contact_label',$this->pi_getLL('tx_civserv_pi1_service.contact_female','Contact person'));
			}else{
				$smartyService->assign('contact_label',$this->pi_getLL('tx_civserv_pi1_service.contact_male','Contact person'));
			}
		}else{
			$smartyService->assign('contact_label',$this->pi_getLL('tx_civserv_pi1_service.contact_plural','Contact persons'));
		}
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
			//$_SERVER['REQUEST_URI'] = $this->pi_linkTP_keepPIvars_url(array(mode => 'search_result'),0,1); //dropped this according to instructions from security review
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
		$log_interval = intval($row['cf_value']);
		$accesslog = t3lib_div::makeInstance('tx_civserv_accesslog');
		$accesslog->update_log($uid,$log_interval, long2ip(ip2long($_SERVER['REMOTE_ADDR'])));

		//Title for the Indexed Search Engine
		$GLOBALS['TSFE']->indexedDocTitle = $service_common['sv_name'];
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
		// VERSIONING:
		// we need the hidden records as well!
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
						'uid, 
						 pid, 
						 sv_name, 
						 sv_descr_short, 
						 sv_descr_long, 
						 sv_image, 
						 sv_image_text, 
						 sv_fees, 
						 sv_documents, 
						 sv_legal_local, 
						 sv_legal_global, 
						 sv_3rdparty_checkbox, 
						 sv_3rdparty_link, 
						 sv_3rdparty_name,
						 sv_model_service',
						'tx_civserv_service',
						'deleted = 0 
						 AND hidden = 0 
						 AND uid = ' . intval($uid) . '',
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
		$uid = intval($this->piVars['id']);	//SQL-Injection!!!
		$pos_id = intval($this->piVars['pos_id']); //must come from piVars -> need to know which one of several possible positions is to be displayed....

		// test bk: get position from Database
		// a) employee is linked to position-record, could be > 1 !!!
		// b) employee is linked to organisation-record (leader of the pack)
		/*
		$pos_id = intval($this->piVars[pos_id]);
		$res_emppos = $GLOBALS['TYPO3_DB']->exec_SELECT_mm_query(
					'uid',
					'tx_civserv_employee,
					 tx_civserv_employee_em_position_mm,
					 tx_civserv_position'
		);
		*/
		


		// Standard query for employee details --> table tx_civserv_employee
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
						'deleted = 0 AND hidden = 0 AND uid='.$uid.' AND em_datasec=1',
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
					'oh_start_morning, 
					 oh_end_morning, 
					 oh_start_afternoon, 
					 oh_end_afternoon, 
					 oh_manual_checkbox,
					 oh_freestyle, 
					 oh_weekday',
					'tx_civserv_employee',
					'tx_civserv_employee_em_hours_mm',
					'tx_civserv_officehours',
					'AND tx_civserv_employee.deleted = 0 AND tx_civserv_employee.hidden = 0
					 AND tx_civserv_officehours.deleted = 0 AND tx_civserv_officehours.hidden = 0
					 AND tx_civserv_employee.uid = ' . $uid,
					'',
					'oh_weekday',
					'');

		// Create additional queries if position uid is set in piVars
		if ($pos_id != '') {

			// Query for employee-position office hours
			$res_emp_pos_hours = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
					'oh_start_morning, 
					 oh_end_morning, 
					 oh_start_afternoon, 
					 oh_end_afternoon, 
					 oh_manual_checkbox,
					 oh_freestyle, 
					 oh_weekday',
					'tx_civserv_employee, 
					 tx_civserv_position, 
					 tx_civserv_officehours, 
					 tx_civserv_employee_em_position_mm, 
					 tx_civserv_officehours_oep_employee_em_position_mm_mm',
					'tx_civserv_employee.deleted = 0 AND tx_civserv_employee.hidden = 0
					 AND tx_civserv_position.deleted = 0 AND tx_civserv_position.hidden = 0
					 AND tx_civserv_officehours.deleted = 0 AND tx_civserv_officehours.hidden = 0
					 AND tx_civserv_employee.uid = tx_civserv_employee_em_position_mm.uid_local
					 AND tx_civserv_position.uid = tx_civserv_employee_em_position_mm.uid_foreign
					 AND tx_civserv_employee_em_position_mm.uid = tx_civserv_officehours_oep_employee_em_position_mm_mm.uid_local
					 AND tx_civserv_officehours.uid = tx_civserv_officehours_oep_employee_em_position_mm_mm.uid_foreign
					 AND tx_civserv_employee.uid = ' . $uid . ' AND tx_civserv_position.uid = '.$pos_id,
					'',
					'oh_weekday',
					'');

			// Query for employee-organisation office hours
			// 1. will only return result if position occupied by employee has relation to organisation
			// 2. ignores direct employee-organisation-relations (leader of the pack....)
			$res_emp_org_hours = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
					'oh_start_morning, 
					 oh_end_morning, 
					 oh_start_afternoon, 
					 oh_end_afternoon,
					 oh_manual_checkbox, 
					 oh_freestyle, 
					 oh_weekday',
					'tx_civserv_employee, 
					 tx_civserv_organisation, 
					 tx_civserv_position, 
					 tx_civserv_officehours, 
					 tx_civserv_employee_em_position_mm, 
					 tx_civserv_position_po_organisation_mm, 
					 tx_civserv_organisation_or_hours_mm',
					'tx_civserv_organisation.deleted = 0 AND tx_civserv_organisation.hidden = 0
					 AND tx_civserv_officehours.deleted = 0 AND tx_civserv_officehours.hidden = 0
					 AND tx_civserv_position.deleted = 0 AND tx_civserv_organisation.hidden = 0
					 AND tx_civserv_employee.deleted = 0 AND tx_civserv_officehours.hidden = 0
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


			// not all employee-position-records have a relation to a room (building, floor)

			$res_position = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				'tx_civserv_position.uid as pos_uid, 
				 tx_civserv_organisation.uid as or_uid, 
				 tx_civserv_employee.uid as emp_uid, 
				 po_name as position, 
				 ep_telephone as phone, 
				 ep_fax as fax, 
				 ep_email as email, 
				 or_name as organisation',
				'tx_civserv_employee, 
				 tx_civserv_position, 
				 tx_civserv_organisation, 
				 tx_civserv_employee_em_position_mm, 
				 tx_civserv_position_po_organisation_mm',
				'tx_civserv_employee.uid='.$uid.' 
				 AND em_datasec=1 
				 AND tx_civserv_position.uid = '.$pos_id.'
				 AND tx_civserv_organisation.deleted = 0 AND tx_civserv_organisation.hidden = 0
				 AND tx_civserv_employee.deleted = 0 AND tx_civserv_employee.hidden = 0
				 AND tx_civserv_position.deleted = 0 AND tx_civserv_position.hidden = 0
				 AND tx_civserv_employee.uid = tx_civserv_employee_em_position_mm.uid_local
				 AND tx_civserv_employee_em_position_mm.uid_foreign = tx_civserv_position.uid
				 AND tx_civserv_position.uid = tx_civserv_position_po_organisation_mm.uid_local
				 AND tx_civserv_organisation.uid = tx_civserv_position_po_organisation_mm.uid_foreign',
				'',
				'',
				'');

			$res_room = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
					'bl_name as building, 
					 bl_name_to_show as building_to_show,
					 fl_descr as floor, 
					 ro_name as room', 
					'tx_civserv_employee, 
					 tx_civserv_position, 
					 tx_civserv_room, 
					 tx_civserv_floor, 
					 tx_civserv_building, 
					 tx_civserv_employee_em_position_mm, 
					 tx_civserv_building_bl_floor_mm', 
					'tx_civserv_employee.uid='.$uid.' 
					 AND em_datasec=1 
					 AND tx_civserv_position.uid = '.$pos_id.'
					 AND tx_civserv_employee.deleted = 0 AND tx_civserv_employee.hidden = 0
					 AND tx_civserv_position.deleted = 0 AND tx_civserv_position.hidden = 0
					 AND tx_civserv_room.deleted = 0 AND tx_civserv_room.hidden = 0
					 AND tx_civserv_floor.deleted = 0 AND tx_civserv_floor.hidden = 0
					 AND tx_civserv_employee.uid = tx_civserv_employee_em_position_mm.uid_local
					 AND tx_civserv_employee_em_position_mm.uid_foreign = tx_civserv_position.uid
					 AND tx_civserv_employee_em_position_mm.ep_room = tx_civserv_room.uid
					 AND tx_civserv_building.uid = tx_civserv_building_bl_floor_mm.uid_local
					 AND tx_civserv_floor.uid = tx_civserv_building_bl_floor_mm.uid_foreign
					 AND tx_civserv_room.rbf_building_bl_floor = tx_civserv_building_bl_floor_mm.uid',
					'',
					'',
					'');

			//Assign employee position data
			$employee_position = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_position);
			$employee_position['or_link'] = htmlspecialchars($this->pi_linkTP_keepPIvars_url(array(mode => 'organisation', id => $employee_position['or_uid']),1,1));
			if($employee_position['building_to_show'] > ''){
				$employee_position['building'] = $employee_position['building_to_show'];
			}
			$employee_room = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_room);
			if(count($employee_room) > 0){
				$employee_position = array_merge($employee_position, (array)$employee_room);
			}
			$smartyEmployee->assign('position',$employee_position);
	
			//Assign employee-position working hours
			$row_counter = 0;
			while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_emp_pos_hours) ){	
				$emp_pos_hours[$row_counter]['weekday'] = $this->pi_getLL('tx_civserv_pi1_weekday_'.$row[oh_weekday]);
				$emp_pos_hours[$row_counter]['start_morning'] = $row['oh_start_morning'];
				$emp_pos_hours[$row_counter]['end_morning'] = $row['oh_end_morning'];
				$emp_pos_hours[$row_counter]['start_afternoon'] = $row['oh_start_afternoon'];
				$emp_pos_hours[$row_counter]['end_afternoon'] = $row['oh_end_afternoon'];
				if($row['oh_manual_checkbox'] == 1){
					$emp_pos_hours[$row_counter]['freestyle'] = $row['oh_freestyle'];
				}else{
					$emp_pos_hours[$row_counter]['freestyle'] = '';
				}
				$row_counter++;
			}
			$smartyEmployee->assign('emp_pos_hours',$emp_pos_hours);
	
			//Assign employee-organisation working hours
			$row_counter = 0;
			while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_emp_org_hours) ){	
				$emp_org_hours[$row_counter]['weekday'] = $this->pi_getLL('tx_civserv_pi1_weekday_'.$row[oh_weekday]);
				$emp_org_hours[$row_counter]['start_morning'] = $row['oh_start_morning'];
				$emp_org_hours[$row_counter]['end_morning'] = $row['oh_end_morning'];
				$emp_org_hours[$row_counter]['start_afternoon'] = $row['oh_start_afternoon'];
				$emp_org_hours[$row_counter]['end_afternoon'] = $row['oh_end_afternoon'];
#				$emp_org_hours[$row_counter]['freestyle'] = $row['oh_freestyle'];
				if($row['oh_manual_checkbox'] == 1){
					$emp_org_hours[$row_counter]['freestyle'] = $row['oh_freestyle'];
				}else{
					$emp_org_hours[$row_counter]['freestyle'] = '';
				}
				$row_counter++;
			}
			$smartyEmployee->assign('emp_org_hours',$emp_org_hours);
		} //End if additional queries

		$employee_rows = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_common);

		// get Image code
		$imagepath = $this->conf['folder_organisations'] . $this->community['id'] . '/images/';
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
		if ($employee_position['email'] != '') {
			$email_form_url = htmlspecialchars($this->pi_linkTP_keepPIvars_url(array(mode => 'set_email_form',id => $employee_position['emp_uid'],pos_id => $employee_position['pos_uid']),1,1));
			$email_code = $this->cObj->typoLink($employee_position['email'],array(parameter => $employee_position['email'],ATagParams => 'class="email"'));
		} elseif ($employee_rows['em_email'] != '') {
			$email_form_url = htmlspecialchars($this->pi_linkTP_keepPIvars_url(array(mode => 'set_email_form',id => $employee_rows['uid']),1,1));
			$email_code = $this->cObj->typoLink($employee_rows['em_email'],array(parameter => $employee_rows['em_email'],ATagParams => 'class="email"'));
		}
		$smartyEmployee->assign('email_form_url',$email_form_url);
		$smartyEmployee->assign('email_code',$email_code);

		//Assign employee working hours
		$row_counter = 0;
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_emp_hours) ){
			$emp_hours[$row_counter]['weekday'] = $this->pi_getLL('tx_civserv_pi1_weekday_'.$row[oh_weekday]);
			$emp_hours[$row_counter]['start_morning'] = $row['oh_start_morning'];
			$emp_hours[$row_counter]['end_morning'] = $row['oh_end_morning'];
			$emp_hours[$row_counter]['start_afternoon'] = $row['oh_start_afternoon'];
			$emp_hours[$row_counter]['end_afternoon'] = $row['oh_end_afternoon'];
#			$emp_hours[$row_counter]['freestyle'] = $row['oh_freestyle'];
			if($row['oh_manual_checkbox'] == 1){
				$emp_hours[$row_counter]['freestyle'] = $row['oh_freestyle'];
			}else{
				$emp_hours[$row_counter]['freestyle'] = '';
			}
			$row_counter++;
		}
		$smartyEmployee->assign('emp_hours',$emp_hours);

		//Assign template labels
		if (intval($employee_rows[em_address]) == 2) {
			$smartyEmployee->assign('employee_label',$this->pi_getLL('tx_civserv_pi1_employee.employee_female','Employee'));
		} else{ //1 for male or nothing
			$smartyEmployee->assign('employee_label',$this->pi_getLL('tx_civserv_pi1_employee.employee_male','Employee'));
		}	
		$smartyEmployee->assign('phone_label',$this->pi_getLL('tx_civserv_pi1_organisation.phone','Phone'));
		$smartyEmployee->assign('fax_label',$this->pi_getLL('tx_civserv_pi1_organisation.fax','Fax'));
		$smartyEmployee->assign('email_label',$this->pi_getLL('tx_civserv_pi1_organisation.email','E-Mail'));
		$smartyEmployee->assign('web_email_label',$this->pi_getLL('tx_civserv_pi1_organisation.web_email','E-Mail-Form'));
		$smartyEmployee->assign('working_hours_label',$this->pi_getLL('tx_civserv_pi1_employee.hours','Working hours'));
		$smartyEmployee->assign('office_hours_summary',str_replace('###EMPLOYEE###',$employee_rows[em_firstname] . ' ' . $employee_rows[em_name],$this->pi_getLL('tx_civserv_pi1_employee.officehours','In the table are the office hours of ###EMPLOYEE### shown.')));
		if($this->conf['showOhLabels']){
			//default
		}else{
			$smartyEmployee->assign('supress_labels', 'invisible');
		}
		$smartyEmployee->assign('weekday',$this->pi_getLL('tx_civserv_pi1_weekday','Weekday'));
		$smartyEmployee->assign('morning',$this->pi_getLL('tx_civserv_pi1_organisation.morning','mornings'));
		$smartyEmployee->assign('afternoon',$this->pi_getLL('tx_civserv_pi1_organisation.afternoon','in the afternoon'));

		$smartyEmployee->assign('organisation_label',$this->pi_getLL('tx_civserv_pi1_employee.organisation','Organisation'));
		$smartyEmployee->assign('room_label',$this->pi_getLL('tx_civserv_pi1_employee.room','Room'));
		//the image_employee_label is not being used yet
		if (intval($employee_rows[em_address]) == 2) {
			$smartyEmployee->assign('image_employee_label',$this->pi_getLL('tx_civserv_pi1_employee_female.image','Image of employee'));
		} else if (intval($employee_rows[em_address]) == 1) {
			$smartyEmployee->assign('image_employee_label',$this->pi_getLL('tx_civserv_pi1_employee_male.image','Image of employee'));
		}

		if ($searchBox) {
			//$_SERVER['REQUEST_URI'] = $this->pi_linkTP_keepPIvars_url(array(mode => 'search_result'),0,1); //dropped this according to instructions from security review
			$smartyTop15->assign('searchbox', $this->pi_list_searchBox('',true));
		}
		$GLOBALS['TSFE']->page['title']=$this->pi_getLL('tx_civserv_pi1_employee.employee_plural','Employees');
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
		$uid = intval($this->piVars['id']);	//SQL-Injection!!!

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
						'deleted = 0 AND hidden = 0 AND uid='.$uid,
						'',
						'',
						'');

		//Query for supervisor of organisation
		$res_supervisor = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
						'tx_civserv_employee.uid as uid, em_title, em_name, em_firstname, em_address, em_datasec',
						'tx_civserv_organisation, tx_civserv_employee',
						'tx_civserv_organisation.deleted = 0 AND tx_civserv_organisation.hidden = 0
						 AND tx_civserv_employee.deleted = 0 AND tx_civserv_employee.hidden = 0
						 AND tx_civserv_organisation.or_supervisor = tx_civserv_employee.uid
						 AND tx_civserv_organisation.uid='.$uid,
						'',
						'',
						'');

		//Query for supervisor of organisation (depending on position)
		$res_pos_supervisor = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
						'tx_civserv_employee.uid as uid, tx_civserv_position.uid as pos_uid, em_title, em_name, em_firstname, em_address, em_datasec',
						'tx_civserv_organisation, tx_civserv_employee, tx_civserv_position, tx_civserv_employee_em_position_mm, tx_civserv_position_po_organisation_mm',
						'tx_civserv_organisation.deleted = 0 AND tx_civserv_organisation.hidden = 0
						 AND tx_civserv_employee.deleted = 0 AND tx_civserv_employee.hidden = 0
						 AND tx_civserv_position.deleted = 0 AND tx_civserv_position.hidden = 0
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
						'AND tx_civserv_organisation.deleted = 0 AND tx_civserv_organisation.hidden = 0
						 AND tx_civserv_building.deleted = 0 AND tx_civserv_building.hidden = 0
						 AND tx_civserv_organisation.uid = ' . $uid,
						'',
						'',
						'');

		//Query for office hours
		$res_hour = $GLOBALS['TYPO3_DB']->exec_SELECT_mm_query(
						'oh_start_morning, 
						 oh_end_morning, 
						 oh_start_afternoon, 
						 oh_end_afternoon,
						 oh_manual_checkbox, 
						 oh_freestyle, 
						 oh_weekday',
						'tx_civserv_organisation',
						'tx_civserv_organisation_or_hours_mm',
						'tx_civserv_officehours',
						'AND tx_civserv_organisation.deleted = 0 AND tx_civserv_organisation.hidden = 0
						 AND tx_civserv_officehours.deleted = 0 AND tx_civserv_officehours.hidden = 0
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
						 tx_civserv_organisation.deleted = 0 and
						 tx_civserv_organisation.hidden = 0',
						'',
						'tx_civserv_organisation.sorting', //Order by
						'');				

		$row_count_sub_orgs = 0;
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_sub_org))	{
			$sub_organisations[$row_count_sub_orgs]['link'] =  htmlspecialchars($this->pi_linkTP_keepPIvars_url(array(mode => 'organisation',id => $row['uid']),1,1));
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
						 tx_civserv_organisation.deleted = 0 and
						 tx_civserv_organisation.hidden = 0',
						'',
						'tx_civserv_organisation.sorting', //Order by
						'');				

		if ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_super_org))	{
			$super_organisation['link'] =  htmlspecialchars($this->pi_linkTP_keepPIvars_url(array(mode => 'organisation',id => $row['uid']),1,1));
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
			$pos_id = $organisation_supervisor[pos_uid];
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
						tx_civserv_organisation.deleted = 0 AND 
						tx_civserv_organisation.hidden = 0 AND 
						tx_civserv_building.deleted = 0 AND 
						tx_civserv_building.hidden = 0 AND 
						tx_civserv_employee_em_position_mm.deleted = 0 AND 
						tx_civserv_employee_em_position_mm.hidden = 0 AND 
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
						'AND tx_civserv_organisation.deleted = 0 AND tx_civserv_organisation.hidden = 0
						 AND tx_civserv_building.deleted = 0 AND tx_civserv_building.hidden = 0
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
			$organisation_hours[$row_counter]['weekday'] = $this->pi_getLL('tx_civserv_pi1_weekday_'.$row[oh_weekday]);
			$organisation_hours[$row_counter]['start_morning'] = $row['oh_start_morning'];
			$organisation_hours[$row_counter]['end_morning'] = $row['oh_end_morning'];
			$organisation_hours[$row_counter]['start_afternoon'] = $row['oh_start_afternoon'];
			$organisation_hours[$row_counter]['end_afternoon'] = $row['oh_end_afternoon'];
#			$organisation_hours[$row_counter]['freestyle'] = $row['oh_freestyle'];
			if($row['oh_manual_checkbox'] == 1){
				$organisation_hours[$row_counter]['freestyle'] = $row['oh_freestyle'];
			}else{
				$organisation_hours[$row_counter]['freestyle'] = '';
			}
			$row_counter++;
		}
		$smartyOrganisation->assign('office_hours',$organisation_hours);

		// get Image code
		$imagepath = $this->conf['folder_organisations'] . $this->community['id'] . '/images/';
		$imageCode = $this->getImageCode($organisation_rows['or_image'],$imagepath,$this->conf['organisation-image.'],$this->pi_getLL('tx_civserv_pi1_organisation.image','Image of organisation'));

		//Assign standard data
		// test bk: include or_addinfo
		// test bk: include or_title 
		
		$GLOBALS['TSFE']->page['title']=$organisation_rows['or_name'];
		// test bk: münster - generate or_title from or_name (is only displayed in münster)
		$or_title = $organisation_rows['or_name'];
		if($organisation_rows[or_addlocation]>'')$or_title.=' ('.$organisation_rows[or_addlocation].')';
		$smartyOrganisation->assign('or_title',$or_title);
		$smartyOrganisation->assign('or_addlocation',$organisation_rows['or_addlocation']);
		$smartyOrganisation->assign('or_name',$organisation_rows['or_name']);
		$smartyOrganisation->assign('or_addinfo',$organisation_rows['or_addinfo']);
		$smartyOrganisation->assign('or_phone',$organisation_rows['or_telephone']);
		$smartyOrganisation->assign('or_fax',$organisation_rows['or_fax']);
		$smartyOrganisation->assign('or_email_code',$this->cObj->typoLink($organisation_rows['or_email'],array(parameter => $organisation_rows['or_email'],ATagParams => 'class="email"'))); 	// use typolink, because of the possibility to use encrypted email-adresses for spam-protection
		$smartyOrganisation->assign('or_email_form_url',htmlspecialchars($this->pi_linkTP_keepPIvars_url(array(mode => 'set_email_form',org_id => $organisation_rows['uid']),1,1)));
		$smartyOrganisation->assign('or_image',$imageCode);

		//Assign employee data
		// test bk: do not show the organisationSupervisor at all - depending on a flag in the organisation-table
		if ($organisation_rows['or_show_supervisor']) {
			$smartyOrganisation->assign('su_title',$organisation_supervisor['em_title']);
			$smartyOrganisation->assign('su_firstname',$organisation_supervisor['em_firstname']);
			$smartyOrganisation->assign('su_name',$organisation_supervisor['em_name']);
			if (intval($organisation_supervisor['em_datasec']) == 1) {
				if ($pos_id != '') {
					$smartyOrganisation->assign('su_link',htmlspecialchars($this->pi_linkTP_keepPIvars_url(array(mode => 'employee',id => $organisation_supervisor['uid'],pos_id => $pos_id),1,1)));
				} else {
					$smartyOrganisation->assign('su_link',htmlspecialchars($this->pi_linkTP_keepPIvars_url(array(mode => 'employee',id => $organisation_supervisor['uid']),1,1)));
				}
			}
		}

		//Assign addresses
		// test bk: include bl_name
		$smartyOrganisation->assign('bl_available', $bl_available = $orga_bl_count > 0 ? 1 : 0);
		$smartyOrganisation->assign('buildings', $organisation_buildings);
		
		//Assign template labels
		$smartyOrganisation->assign('organisation_label',$this->pi_getLL('tx_civserv_pi1_organisation.organisation','Organisation'));
		$smartyOrganisation->assign('sub_org_label',$this->pi_getLL('tx_civserv_pi1_organisation.sub_org_label','You can also visit us here:'));
		$smartyOrganisation->assign('super_org_label',$this->pi_getLL('tx_civserv_pi1_organisation.super_org_label','next higher organisation level:'));
		$smartyOrganisation->assign('postal_address_label',$this->pi_getLL('tx_civserv_pi1_organisation.postal_address','Postal address'));
		$smartyOrganisation->assign('building_address_label',$this->pi_getLL('tx_civserv_pi1_organisation.building_address','Building address'));
		$smartyOrganisation->assign('phone_label',$this->pi_getLL('tx_civserv_pi1_organisation.phone','Phone'));
		$smartyOrganisation->assign('fax_label',$this->pi_getLL('tx_civserv_pi1_organisation.fax','Fax'));
		$smartyOrganisation->assign('email_label',$this->pi_getLL('tx_civserv_pi1_organisation.email','E-Mail'));
		$smartyOrganisation->assign('web_email_label',$this->pi_getLL('tx_civserv_pi1_organisation.web_email','E-Mail-Form'));
		$smartyOrganisation->assign('office_hours_label',$this->pi_getLL('tx_civserv_pi1_organisation.office_hours','Office hours'));
		$smartyOrganisation->assign('supervisor_label',$this->pi_getLL('tx_civserv_pi1_organisation.supervisor','Supervisor'));
		$smartyOrganisation->assign('employee_details',$this->pi_getLL('tx_civserv_pi1_organisation.employee_details','Jumps to a page with details of this employee'));
		$smartyOrganisation->assign('office_hours_summary',str_replace('###ORGANISATION###',$organisation_rows['or_name'],$this->pi_getLL('tx_civserv_pi1_organisation.officehours','In the table are the office hours of ###ORGANISATION### shown.')));
		if($this->conf['showOhLabels']){
			//default
		}else{
			$smartyOrganisation->assign('supress_labels', 'invisible');
		}			
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
		$smartyOrganisation->assign('bl_citymap_label',$this->pi_getLL('tx_civserv_pi1_organisation.citymap_label','City Map Link'));

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
						'deleted = 0 AND hidden = 0',
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
						'deleted = 0 AND hidden = 0
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
								'deleted = 0 AND hidden = 0');
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
		if ($this->getEmailAddress($smartyEmailForm) || $this->piVars['mode'] == 'set_contact_form') {
			if($this->getEmailAddress($smartyEmailForm)){
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
		$email_address = $this->getEmailAddress($smartyEmailForm);

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
				   "\n" . $this->pi_getLL('tx_civserv_pi1_email_form.subject','Subject') . ': ' . $subject.
				   "\n" .
				   "\n" . $this->pi_getLL('tx_civserv_pi1_email_form.bodytext','Your text') . ': ' .
				   "\n" . $bodytext;
				//todo: check possibilities of header injection
				if(!empty($email)){		// email given in contact-form is correct
					$headers = "From: ".$email."\r\nReply-To: ".$email."\r\n";
				}else{ // set email retrieved via hoster_get_email
					$headers = "From: ".$email_address."\r\nReply-To: ".$email_address."\r\n";
				}

				t3lib_div::plainMailEncoded($email_address, $subject, $body, $headers);
				$reply = $this->pi_getLL('tx_civserv_pi1_email_form.complete','Thank you! Your message has been sent successfully ');
				$reply .= $this->pi_getLL('tx_civserv_pi1_email_form.to','to ');
				$reply .= $email_address.".";
				$smartyEmailForm->assign('complete',$reply);

				return true;
			} else { //Return email form template with error markers
				if($this->piVars['mode'] == "check_contact_form"){
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
	function getEmailAddress() {
		//Retrieve submitted id parameters
		$org_id = intval($this->piVars[org_id]);
		$emp_id = intval($this->piVars['id']);
		$pos_id = intval($this->piVars[pos_id]);
		$sv_id = intval($this->piVars[sv_id]);

		if (!empty($org_id)) {	//Email form is called from organisation detail page (organisation email)
			//Standard query for organisation details
			$res_organisation = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
					'or_name, or_email',
					'tx_civserv_organisation',
					'deleted = 0 AND hidden = 0 AND uid = ' . $this->piVars[org_id]);

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
		} elseif ($this->piVars['mode'] == 'check_contact_form') {	//Email form ist called by the contact_link in the main Navigation
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
			$querypart_where = ' AND tx_civserv_service.uid = ' . $sv_id . ' AND tx_civserv_employee.uid = ' . $emp_id . ' AND tx_civserv_position.uid = ' . $pos_id . '
								AND tx_civserv_service.deleted = 0 AND tx_civserv_service.hidden = 0
								AND tx_civserv_position.deleted = 0 AND tx_civserv_position.hidden = 0
								AND tx_civserv_employee_em_position_mm.deleted = 0 AND tx_civserv_employee_em_position_mm.hidden = 0
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
								AND tx_civserv_position.deleted = 0 AND tx_civserv_position.hidden = 0
								AND tx_civserv_employee_em_position_mm.deleted = 0 AND tx_civserv_employee_em_position_mm.hidden = 0
								AND tx_civserv_employee.uid = tx_civserv_employee_em_position_mm.uid_local
						 		AND tx_civserv_employee_em_position_mm.uid_foreign = tx_civserv_position.uid';
		}

		$res_employee = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
						'em_email, em_datasec as datasec' . $querypart_select,
						'tx_civserv_employee' . $querypart_from,
						'tx_civserv_employee.deleted = 0 AND tx_civserv_employee.hidden = 0 '.$querypart_where,
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
		if ($this->piVars['id'] > '') {
			//Query for standard service details
			$result = $this->queryService(intval($this->piVars['id']));

			//Check if query returned a result
			if ($GLOBALS['TYPO3_DB']->sql_num_rows($result) == 1) {
				$service = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($result);
			} else {
				$GLOBALS['error_message'] = $this->pi_getLL('tx_civserv_pi1_debit_form.error_service','No debit form found for this service!');
				return false;
			}

			$smartyDebitForm->assign('service_uid', $service['uid']);
			$smartyDebitForm->assign('service_name', $service['sv_name']);

		} else {  //Debit form was called from service list
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
				$debit_form_uid = intval($result['uid']);

				//Retrieve all services associated with the debit authorisation form from database
				$res_forms = $GLOBALS['TYPO3_DB']->exec_SELECT_mm_query(
							'tx_civserv_service.uid as uid, tx_civserv_service.sv_name as name',
							'tx_civserv_service',
							'tx_civserv_service_sv_form_mm',
							'tx_civserv_form',
							'AND tx_civserv_form.uid = ' . $debit_form_uid . '
							 AND tx_civserv_service.deleted = 0 AND tx_civserv_service.hidden = 0
							 AND tx_civserv_form.deleted = 0 AND tx_civserv_form.hidden = 0
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
			$ip = long2ip(ip2long($_SERVER['REMOTE_ADDR']));
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
		// for the online_service_list we want to display thumbnails of the service images!
		if($this->piVars['mode'] == 'online_services'){
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
		if ($this->piVars['sword'] <= '') {
			 $this->piVars['sword'] = $this->pi_getLL('pi_list_searchBox_defaultValue','search item');
		}
		// changed action tag according to instructions from security review:
		// dropped:		<form method="post" action="'.htmlspecialchars(t3lib_div::getIndpEnv('REQUEST_URI')).'" style="margin: 0 0 0 0;" >
		// introduced:	<form method="post" action="'.htmlspecialchars($this->pi_linkTP_keepPIvars_url(array(mode => 'search_result'),0,1)).'" style="margin: 0 0 0 0;" >
		
		
		
		//  $this->pi_classParam('searchbox-sword') contains the markup for css: 'class="tx-civserv-pi1-searchbox-sword"'
		$search_word=$this->check_searchword(strip_tags($this->piVars['sword']));  //strip and check to avoid xss-exploits

		$sBox = '

		<!--
			List search box:
		-->

		<div' . $this->pi_classParam('searchbox') . '>
			<form method="post" action="'.htmlspecialchars($this->pi_linkTP_keepPIvars_url(array(mode => 'search_result'),0,1)).'" style="margin: 0 0 0 0;" >
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
	function pi_list_browseresults($showResultCount=1,$divParams='',$spacer=false)      {
			// Initializing variables:
		$pointer=intval($this->piVars['pointer']);
		$count=$this->internal['res_count'];
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
		#session_destroy();
		// Save community id in session, to ensure that the id is also saved when vititing sites without the civserv extension (e.g. fulltext search)
		if ($_SESSION['community_id'] <= '' || intval($_SESSION['community_id']) !== intval($this->conf['_DEFAULT_PI_VARS.']['community_id'])) {
#			$_SESSION['community_id'] = $this->piVars['community_id'];
			//we only really trust the value from the TS-Template:
			$_SESSION['community_id'] = $this->conf['_DEFAULT_PI_VARS.']['community_id'];
			
		}
		// Set piVars['community_id'], if not given from the URL. Necessary for the function pi_linkTP_keepPIvars_url.
		if ($this->piVars['community_id'] <= '' || intval($this->piVars['community_id']) !== intval($this->conf['_DEFAULT_PI_VARS.']['community_id'])) {
#			$this->piVars['community_id'] = $_SESSION['community_id'];
			//we only really trust the value from the TS-Template:
			$this->piVars['community_id'] = $this->conf['_DEFAULT_PI_VARS.']['community_id'];
		}
		
		//test bk: you might want to control the display-order of menu (via $conf). Name them so you can sort them!
		if ($conf['menuServiceList']) {
			$menuArray['menuServiceList'] = array(
								'title' => $this->pi_getLL('tx_civserv_pi1_menuarray.service_list','Services A - Z'),
								'_OVERRIDE_HREF' => $this->pi_linkTP_keepPIvars_url(array(mode => 'service_list'),1,1,$pageid),
								'ITEM_STATE' => ($this->piVars['mode'] == 'service_list')?'ACT':'NO');
		}
		if ($conf['menuCircumstanceTree']) {
			$menuArray['menuCircumstanceTree'] = array(
								'title' => $this->pi_getLL('tx_civserv_pi1_menuarray.circumstance_tree','Circumstances'),
								'_OVERRIDE_HREF' => $this->pi_linkTP_keepPIvars_url(array(mode => 'circumstance_tree'),1,1,$pageid),
								'ITEM_STATE' => (($this->piVars['mode'] == 'circumstance_tree') || ($this->piVars['mode'] == 'circumstance'))?'ACT':'NO');
		}
		if ($conf['menuUsergroupTree']) {
			$menuArray['menuUsergroupTree'] = array(
								'title' => $this->pi_getLL('tx_civserv_pi1_menuarray.usergroup_tree','Usergroups'),
								'_OVERRIDE_HREF' => $this->pi_linkTP_keepPIvars_url(array(mode => 'usergroup_tree'),1,1,$pageid),
								'ITEM_STATE' => (($this->piVars['mode'] == 'usergroup_tree') || ($this->piVars['mode'] == 'usergroup'))?'ACT':'NO');
		}
		if ($conf['menuOrganisationTree']) {
			$menuArray['menuOrganisationTree'] = array(
								'title' => $this->pi_getLL('tx_civserv_pi1_menuarray.organisation_tree','Organisation'),
								'_OVERRIDE_HREF' => $this->pi_linkTP_keepPIvars_url(array(mode => 'organisation_tree'),1,1,$pageid),
								'ITEM_STATE' => (($this->piVars['mode'] == 'organisation_tree') || ($this->piVars['mode'] == 'organisation'))?'ACT':'NO');
		}
		if ($conf['menuOrganisationList']) {
			$menuArray['menuOrganisationList'] = array(
								'title' => $this->pi_getLL('tx_civserv_pi1_menuarray.organisation_list','Organisation A - Z'),
								'_OVERRIDE_HREF' => $this->pi_linkTP_keepPIvars_url(array(mode => 'organisation_list'),1,1,$pageid),
								'ITEM_STATE' => (($this->piVars['mode'] == 'organisation_list') || ($this->piVars['mode'] == 'organisation_list'))?'ACT':'NO');
		}
		if ($conf['menuEmployeeList']) {
			$menuArray['menuEmployeeList'] = array(
								'title' => $this->pi_getLL('tx_civserv_pi1_menuarray.employee_list','Employees A - Z'),
								'_OVERRIDE_HREF' => $this->pi_linkTP_keepPIvars_url(array(mode => 'employee_list'),1,1,$pageid),
								'ITEM_STATE' => ($this->piVars['mode'] == 'employee_list')?'ACT':'NO');
		}
		if ($conf['menuFormList']) {
			$menuArray['menuFormList'] = array(
								'title' => $this->pi_getLL('tx_civserv_pi1_menuarray.form_list','Forms'),
								'_OVERRIDE_HREF' => $this->pi_linkTP_keepPIvars_url(array(mode => 'form_list'),1,1,$pageid),
								'ITEM_STATE' => ($this->piVars['mode'] == 'form_list')?'ACT':'NO');
		}
		if ($conf['menuTop15']) {
			$menuArray['menuTop15'] = array(
								'title' => $this->pi_getLL('tx_civserv_pi1_menuarray.top15','Top 15'),
								'_OVERRIDE_HREF' => $this->pi_linkTP_keepPIvars_url(array(mode => 'top15'),0,1,$pageid),
								'ITEM_STATE' => ($this->piVars['mode'] == 'top15')?'ACT':'NO');
		}
		// online services....
		if ($conf['menuOnlineServices']) {
			$menuArray['menuOnlineServices'] = array(
								'title' => $this->pi_getLL('tx_civserv_pi1_menuarray.online_services','Online Services'),
								'_OVERRIDE_HREF' => $this->pi_linkTP_keepPIvars_url(array(mode => 'online_services'),0,1,$pageid),
								'ITEM_STATE' => ($this->piVars['mode'] == 'online_services')?'ACT':'NO');
		}

		// get full text search id from TSconfig
		if ($conf['fulltext_search_id'] > '') {
			$menuArray['menuFulltextSearch'] = array(
							'title' => $this->pi_getLL('tx_civserv_pi1_menuarray.fulltext_search','Fulltext Search'),
							'_OVERRIDE_HREF' => $this->pi_linkTP_keepPIvars_url(array(),0,1,$conf['fulltext_search_id']),
							'ITEM_STATE' => ($GLOBALS['TSFE']->id == $conf['fulltext_search_id'])?'ACT':'NO');
		}
		// get id for alternative language from TSconfig
		if (intval($conf['alternative_page_id']) > 0) {
			$menuArray[] = array(
							'title' => $this->pi_getLL('tx_civserv_pi1_menuarray.alternative_language','Deutsche Inhalte'),
							'_OVERRIDE_HREF' => $this->pi_linkTP_keepPIvars_url(array(mode => 'service_list'),0,1,$conf['alternative_page_id']),
							'ITEM_STATE' => ($GLOBALS['TSFE']->id == $conf['alternative_page_id'])?'ACT':'NO');
		}
		
		
		//test bk: city of Münster: define first menu-item via $conf!
		if ($conf['menuItems_01'] > '' && $conf[$conf['menuItems_01']]) {
			$first = $menuArray[$conf['menuItems_01']];
			unset($menuArray[$conf['menuItems_01']]);
			array_unshift ($menuArray, $first);
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
			'uid = '.$pageid.' AND deleted = 0 AND hidden = 0',	// AND title LIKE "%blabla%"', // WHERE...
			'', 										// GROUP BY...
			'',   										// ORDER BY...
			'' 											// LIMIT to 10 rows, starting with number 5 (MySQL compat.)
			);
		
		$parent_list = $this->get_parent_list($this->res, $parent_list);

		$linkText=$GLOBALS['TSFE']->page['title']; //default
		if($this->piVars['mode'] == "organisation"){
			$pageLink= parent::pi_linkTP_keepPIvars_url(array(mode => 'organisation_list'),1,1,$pageid);
			$linkText=$this->pi_getLL('tx_civserv_pi1_menuarray.organisation_list','Organisation A - Z'); 
		}elseif($this->piVars['mode'] == "circumstance"){
			$pageLink= parent::pi_linkTP_keepPIvars_url(array(mode => 'circumstance_tree'),1,1,$pageid);
			$linkText=$this->pi_getLL('tx_civserv_pi1_menuarray.circumstance_tree','Circumstances'); 
		}elseif($this->piVars['mode'] == "usergroup"){
			$pageLink= parent::pi_linkTP_keepPIvars_url(array(mode => 'usergroup_tree'),1,1,$pageid);
			$linkText=$this->pi_getLL('tx_civserv_pi1_menuarray.usergroup_tree','Usergroups'); 
		}elseif($this->piVars['mode'] == "service"){
			$_SESSION['stored_pagelink']=$this->getActualPage($content, $conf);
			$pageLink= parent::pi_linkTP_keepPIvars_url(array(mode => 'service_list'),1,1,$pageid);
			$linkText=$this->pi_getLL('tx_civserv_pi1_service_list.service_list','Services A - Z');
			$_SESSION['info_sites'] = $this->getCompletePageLink($pageLink, $linkText); //Variablen namen ändern?
		}elseif($this->piVars['mode'] == "employee"){
			return $_SESSION['stored_pagelink'];
		}elseif($this->piVars['mode'] == ""){
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
		 
		 //tx_civserv_pi1_organisation_list.organisation_list.heading

		if($this->piVars['mode'] == 'service'){
			$textArr=explode(":", $linkText);
			if(count($textArr)>1)unset($textArr[0]);
			$linkText= implode(":", $textArr);//default
			#$linkText.=':'.strlen($linkText);
		}
		//return link-to-actual-page
		//second parameter is for cache and it also does the md5-thing about the parameterlist, if cache is not set the parameters are rendered in the human-readable way
		//third parameter ist for the elemination of all piVars. must not be set in this case or else link won't work! (id of service goes missing)
		$pageLink= parent::pi_linkTP_keepPIvars_url(array(mode => $this->piVars['mode']),1,0,$pageid);
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
				'uid = '.$row[0].' AND deleted = 0 AND hidden = 0',// AND title LIKE "%blabla%"', // WHERE...
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



if (defined("TYPO3_MODE") && $TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/civserv/pi1/class.tx_civserv_pi1.php"])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/civserv/pi1/class.tx_civserv_pi1.php"]);
}

?>