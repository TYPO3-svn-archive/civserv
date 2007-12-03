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
* This class holds a part of the logic for the model service workflow
*
* $Id$
*
* @author Georg Niemeyer (niemeyer@uni-muenster.de),
* @author Tobias Müller (mullerto@uni-muenster.de),
* @author Maurits Hinzen (mhinzen@uni-muenster.de),
* @package TYPO3
* @subpackage tx_civserv
* @version 1.0
*
*
*/
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   78: class tx_civserv_ms_workflow extends t3lib_SCbase
 *   86:     function init()
 *   99:     function main()
 *  116:     function jumpToUrl(URL)
 *  182:     function approveContent($uid, $responsible)
 *  275:     function reviseContent($uid, $responsible)
 *  357:     function mainContent()
 *  435:     function viewContent($uid)
 *  716:     function printContent()
 *
 * TOTAL FUNCTIONS: 8
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */



	// DEFAULT initialization of a module [BEGIN]
unset($MCONF);
require_once ("conf.php");
require_once ($BACK_PATH."init.php");
require_once ($BACK_PATH."template.php");

$LANG->includeLLFile("EXT:civserv/modmsworkflow/locallang.php");
require_once (PATH_t3lib."class.t3lib_scbase.php");
require_once (PATH_t3lib."class.t3lib_tceforms.php");
include_once ($TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/civserv/res/class.tx_civserv_mandant.php"]);
$BE_USER->modAccess($MCONF,1);	// This checks permissions and exits if the users has no permission for entry.
	// DEFAULT initialization of a module [END]


class tx_civserv_ms_workflow extends t3lib_SCbase {


   /**
    * Default initialization of the needed global variables, for example the Language-variable $LANG
    * @return   [type]      ...
    */
	function init()	{
		global $BE_USER,$LANG,$BACK_PATH,$TCA_DESCR,$TCA,$CLIENT,$TYPO3_CONF_VARS;
		parent::init();
	}


	/**
    * Main function of the workflow module. Setup of the HTML-Header-Information, JavaScript Code etc.
    * Creates the actuell content of the workflow module (main window, display window, commit window or revise window)
    * to display in addiction of parameters given in the URL
    *
    * @return   module content
    */
	function main()	{
		global $BE_USER,$LANG,$BACK_PATH,$TCA_DESCR,$TCA,$CLIENT,$TYPO3_CONF_VARS;

			// Draw the header.
		$this->doc = t3lib_div::makeInstance("mediumDoc");
		$this->doc->backPath = $BACK_PATH;
		$this->doc->form='<form action="" method="POST" name="msworkflow">';

		$this->tceform = t3lib_div::makeInstance("t3lib_TCEforms");
		$this->tceform->backPath='/typo3/';
		$this->tceform->formName = "msworkflow";

			// JavaScript
		$this->doc->JScode = '
			<script language="javascript" type="text/javascript">
				var T3_THIS_LOCATION = \'/typo3/ext/civserv/modmsworkflow/\';
				script_ended = 0;
				function jumpToUrl(URL)	{
					document.location = URL;
				}
			</script>
		';
		$this->doc->postCode='
			<script language="javascript" type="text/javascript">
				script_ended = 1;
				if (top.fsMod) top.fsMod.recentIds["web"] = '.intval($this->id).';
			</script>
		';

		$headerSection = $this->doc->getHeader("pages",$this->pageinfo,$this->pageinfo["_thePath"])."<br>".$LANG->sL("LLL:EXT:lang/locallang_core.php:labels.path").": ".t3lib_div::fixed_lgd_pre($this->pageinfo["_thePath"],50);

		$this->content.=$this->doc->startPage($LANG->getLL("modmsworkflow.title"));
		$this->content.=$this->doc->header($LANG->getLL("modmsworkflow.title"));
		$this->content.=$this->doc->spacer(5);

		// Render content:
		// in order to prevent SQL-Injection from BE the values get white-listed:
		// todo: control all GB-values!
		$arrCases=array("view", "approve", "revise");
		$arrResponsible=array("one", "two", "both");
		$case = ""; //default
		if(in_array(t3lib_div::_GP('case'), $arrCases)){
			$case = t3lib_div::_GP('case');
		}
		$uid = intval(t3lib_div::_GP('uid'));
		$responsible = "one";
		if(in_array(t3lib_div::_GP('responsible'), $arrResponsible)){
			$responsible = t3lib_div::_GP('responsible');
		}
		$abort = t3lib_div::_GP('abort');

		if (isset($abort)){
			$this->mainContent();
		} else
		switch ($case) {
			case "view":
				$this->content.='
						<div align=center>
						'.$LANG->getLL("modmsworkflow.viewContent").'
						</div><br>
						<input type="submit" name="abort" value="'.$LANG->getLL("modmsworkflow.back_button").'">
						<br><br>
						';
				$this->viewContent($uid);
				$this->content.='
						<br>
						<input type="submit" name="abort" value="'.$LANG->getLL("modmsworkflow.back_button").'">
						<br>
						';
				break;
			case "approve":
				$this->approveContent($uid, $responsible);

				break;
			case "revise":
				$this->reviseContent($uid, $responsible);
				break;
			default:
				$this->mainContent();
				break;
		}
	}

	/**
    * Generates the area for committing a model service in the workflow. Different buttons are generated to give a commit or to abort.
    * In addiction of the pressed button, some update queries in the db for model_services_temp are made.
    *
    * @param   [int]      $uid: the uid from the model service, which should be approved
    * @param   [string]   $responsible: describes, if the actuall mandant is the only responsible for this model service, or if
    *						two mandants are involved in the workflow and have to give a commit, to complete the workflow for this model service
    * @return   module content
    */
	function approveContent($uid, $responsible)	{
		global $LANG;

		$submit = t3lib_div::_GP('submit');
		$abort = t3lib_div::_GP('abort');
			//if neither commit nor abort was pressed, this is the initial display mode
		if (empty($submit) && empty($abort)){
				//Qustion: Should edited model services really be committed?
			$content.='<div align=center>'.$LANG->getLL("modmsworkflow.approve").'</div><BR>';
				// Display title and date of change from the model service
			$model_service_temp = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				'*',			 			// SELECT ...
				'tx_civserv_model_service_temp',		// FROM ...
				'uid='.$uid,					// AND title LIKE "%blabla%"', // WHERE...
				'', 						// GROUP BY...
				'',   						// ORDER BY...
				'' 						// LIMIT to 10 rows, starting with number 5 (MySQL compat.)
			);
			$model_service_temp = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($model_service_temp);
				//start table for display the actual model service
			$bgcolor = "bgColor4-20";
			$tableStart = "<table border=\"0\" cellpadding=\"1\" cellspacing=\"2\" width=\"480\" id=\"typo3-page-stdlist\">";
			$tableStart .= "<tr><td class=\"absmiddle\"><b>&nbsp;</b></td>";
			$tableStart .= "<td class=\"absmiddle\" nowrap=\"nowrap\"><b>".$LANG->getLL("modmsworkflow.title")."</b></td>";
			$tableStart .= "<td class=\"absmiddle\" nowrap=\"nowrap\"><b>".$LANG->getLL("modmsworkflow.date")."</b></td>";
			$tableEnd = "</table>";
			$tableStart .= "<tr><td class=\"".$bgcolor."\"><b>".$LANG->getLL("modmsworkflow.ColAPage")."</b></td>";
			$tableStart .= "<td class=\"".$bgcolor."\" nowrap=\"nowrap\">".$model_service_temp["ms_name"]."</td>";
			$tableStart .= "<td class=\"".$bgcolor."\" nowrap=\"nowrap\">".date("d.m.Y",$model_service_temp["tstamp"])."</td></tr>";
			$content .= $tableStart.$tableEnd."<br><br>";
			$content .= '<form name="approveform" action="" method="post">';
			$content .= '
				<input type="submit" name="submit" value="'.$LANG->getLL("modmsworkflow.ok_button").'">
				<input type="submit" name="abort" value="'.$LANG->getLL("modmsworkflow.cancel_button").'">
			';
			$this->content .=$content;
			//if submit was pressed, some updates in the db are made
		} else if (isset($submit))	{
			$model_service_temp = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				'*',			 			// SELECT ...
				'tx_civserv_model_service_temp',		// FROM ...
				'uid='.$uid,					// AND title LIKE "%blabla%"', // WHERE...
				'', 						// GROUP BY...
				'',   						// ORDER BY...
				'' 						// LIMIT to 10 rows, starting with number 5 (MySQL compat.)
			);
			$model_service_temp_row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($model_service_temp);
			if ($responsible == "one") {
				$other_mandant_commit = $model_service_temp_row['ms_commit_approver_two'];
			} else if ($responsible == "two") {
				$other_mandant_commit = $model_service_temp_row['ms_commit_approver_one'];
			}

			$model_service_temp_row['hidden']=0;
			unset($model_service_temp_row['uid']);
			unset($model_service_temp_row['pid']);
			unset($model_service_temp_row['ms_additional_label']);
			unset($model_service_temp_row['ms_has_changed']);
			unset($model_service_temp_row['ms_checksum']);
			unset($model_service_temp_row['ms_comment_editor']);
			unset($model_service_temp_row['ms_commit_approver_one']);
			unset($model_service_temp_row['ms_comment_approver_one']);
			unset($model_service_temp_row['ms_commit_approver_two']);
			unset($model_service_temp_row['ms_comment_approver_two']);
			unset($model_service_temp_row['ms_revised_approver_one']);
			unset($model_service_temp_row['ms_revised_approver_two']);
			unset($model_service_temp_row['ms_uid_editor']);
				// is the other approve field already set?
			if (($responsible == "both") || ($other_mandant_commit == 1))	{
					// overwrite model service entry with data from the model_service_temp entry
				$GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_civserv_model_service', 'uid = '.$uid, $model_service_temp_row);
					// set model_service_temp ms_has_changed=0, approved1=0, approved2=0 (end of the workflow)
				$GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_civserv_model_service_temp', 'uid = '.$uid, array ("ms_additional_label"=>$LANG->getLL("modmsworkflow.label_approved"), "ms_has_changed" => 0, "ms_commit_approver_one" => 0, "ms_commit_approver_two" => 0, "ms_comment_approver_one" => '', "ms_comment_approver_two" => '', "ms_comment_editor" => ''));
			} else {
					// set approve-field
				$GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_civserv_model_service_temp', 'uid = '.$uid, array ("ms_commit_approver_".$responsible => 1, "ms_comment_approver_".$responsible => ''));
				// set label for the service
			}
				//Create email for commit. all content is get from the locallang file. the locallang file contains markers, which have to be replaced here!

						//get the user, who starts the workflow
			$creator_res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('ms_uid_editor, ms_name','tx_civserv_model_service_temp','uid = '.$uid,'','','');
			$creator_row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($creator_res);
			$beUser = t3lib_BEfunc::getRecord('be_users',$creator_row['ms_uid_editor'],'*','');
						//get the FROM email adress from tx_civserv_configuration
			$to = $beUser['email'];
			if (t3lib_div::validEmail($to)){
				$fr_res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('cf_value','tx_civserv_configuration','cf_key = "email_from"','','','');
				$from_res = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($fr_res);

				$subject = str_replace("###model_service_name###", $model_service_temp_row['ms_name'], $LANG->getLL("modmsworkflow.email_subject_commit"));
				$from = "From: ".$from_res['cf_value'];
					$search = array("editor" => "###editor###","model_service_name" => "###model_service_name###","comment" => "###comment###");
					$replace = array("editor" => $beUser['realName'],"model_service_name" => $model_service_temp_row['ms_name'],"comment" => $comment);
				$text = str_replace($search, $replace, $LANG->getLL("modmsworkflow.email_text_commit"));
				t3lib_div::plainMailEncoded($to,$subject,$text,$from);
			}

			$content .= $this->mainContent();
		} else if (isset($abort))	{
			$content .= $this->mainContent();
		}
	}


	/**
    * Generates a little textarea to enter comments (why model service should be revised). The textarea also contains old
    * comments. Underneath the textarea the model service is displayed, so it is easier to comment, whats wrong with the service ;)
    *
    * @param   [int]      $uid: the uid from the model service, which should be revised
    * @param   [string]   $responsible: describes, if the actuall mandant is the only responsible for this model service, or if
    *						two mandants are involved in the workflow and have to give a commit, to complete the workflow for this model service
    * @return   module content
    */
	function reviseContent($uid, $responsible){
		global $LANG;

		$submit = t3lib_div::_GP('submit');
		$comment = t3lib_div::_GP('comment');
		$reset = t3lib_div::_GP('reset');
		$abort = t3lib_div::_GP('abort');

		$checked = true;
		if (isset($submit)){
			if (empty($comment)) $checked = false;
		}

#		$GLOBALS['TYPO3_DB']->debugOutput = TRUE; //debugging - only in test-sites!

		if ((empty($submit) || (!$checked)) && empty($abort)){
			if ($responsible=="both") $responsible="one";

			$comment_res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
					'ms_comment_approver_'.$responsible.' AS comment',			 							// SELECT ...
					'tx_civserv_model_service_temp',		// FROM ...
					'uid = '.$uid,					// AND title LIKE "%blabla%"', // WHERE...
					'', 										// GROUP BY...
					'',   										// ORDER BY...
					'' 											// LIMIT to 10 rows, starting with number 5 (MySQL compat.)
				);
			$comment_row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($comment_res);
			$comment = $comment_row['comment'];
				//Attention please, the following Layout should not be changed
			if (strlen($comment)>0) $comment = "<---".$LANG->getLL("modmsworkflow.old_comment_beginning")."--->\n".$comment."\n<---".$LANG->getLL("modmsworkflow.old_comment_end")."--->";

			$content ='<div align=center>'.$LANG->getLL("modmsworkflow.revise").'</div>';
			$content .= '
				<tr>
                	<td valign="top" width="230"> <textarea rows=10 cols=100 name="comment">'.$comment.'</textarea></td>
              	</tr>
				';

			$content .= '
			<tr>
				<input type="submit" name="submit" value="'.$LANG->getLL("modmsworkflow.send_button").'">
				<input type="reset" name="reset" value="'.$LANG->getLL("modmsworkflow.reset_button").'">
				<input type="submit" name="abort" value="'.$LANG->getLL("modmsworkflow.cancel_button").'">
			</tr>';

			$this->content .=$content.'<BR/><BR/><BR/><BR/>';
			$this->viewContent($uid);


		} else {
			if ($checked){
				$creator_res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
					'ms_uid_editor, ms_name',			 							// SELECT ...
					'tx_civserv_model_service_temp',		// FROM ...
					'uid = '.$uid,					// AND title LIKE "%blabla%"', // WHERE...
					'', 										// GROUP BY...
					'',   										// ORDER BY...
					'' 											// LIMIT to 10 rows, starting with number 5 (MySQL compat.)
				);
				$creator_row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($creator_res);
				$beUser = t3lib_BEfunc::getRecord('be_users',$creator_row['ms_uid_editor'],'*','');
				if ($responsible=="both") {
					$fields_values = array("ms_additional_label"=>$LANG->getLL("modmsworkflow.label_revised"), "hidden"=>1, "ms_comment_approver_one" => $comment, "ms_revised_approver_one" => 1, "ms_revised_approver_two" => 1);
				} else 	$fields_values = array("ms_additional_label"=>$LANG->getLL("modmsworkflow.label_revised"), "hidden"=>1, "ms_comment_approver_".$responsible => $comment, "ms_revised_approver_".$responsible => 1);
				$GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_civserv_model_service_temp','uid = '.$uid,$fields_values);

					//Create email for revise. all content is get from the locallang file. the locallang file contains markers, which have to be replaced here!

						//get the FROM email adress from tx_civserv_configuration
				$fr_res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('cf_value','tx_civserv_configuration','cf_key = "email_from"','','','');
				$from_res = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($fr_res);
				$to = $beUser['email'];
				if (t3lib_div::validEmail($to)){
					$subject = str_replace("###model_service_name###", $creator_row['ms_name'], $LANG->getLL("modmsworkflow.email_subject_revise"));
					$from = "From: ".$from_res['cf_value'];
					$search = array("editor" => "###editor###","model_service_name" => "###model_service_name###","comment" => "###comment###");
					$replace = array("editor" => $beUser['realName'],"model_service_name" => $creator_row['ms_name'],"comment" => $comment);
					$text = str_replace($search, $replace, $LANG->getLL("modmsworkflow.email_text_revise"));
					t3lib_div::plainMailEncoded($to,$subject,$text,$from);
				}
			}
			$content .= $this->mainContent();
		}
	}


	/**
    * Generates the content for the main window. This contains a HTML table with all model services, which are
    * actual in the workflow. For each model service you have 3 options to choose: view, commit or revise
    *
    * @return   module content
    */
	function mainContent()	{
		global $LANG,$BE_USER;

		$mandant_obj = t3lib_div::makeInstance('tx_civserv_mandant');
		#citeq: make it possible for BE_user to have several mountpoints (so long as one of them can be identified with a community)
		$community_id=0;
		$possibleMPs=explode(",", $BE_USER->user["db_mountpoints"]);
		foreach($possibleMPs as $mp){
			$community_id=$mandant_obj->get_mandant($mp);
			if($community_id>0){
				break;
			}
		}
		// to do: abfragen, ob flag "mount from groups" gesetzt ist........
		if($community_id == 0){ //user hat keinen db_mountpoint - hat eine seiner Gruppen einen?
			foreach($BE_USER->userGroupsUID as $groupid){
				if($BE_USER->userGroups[$groupid]['db_mountpoints'] > 0){
					$community_id=$mandant_obj->get_mandant($BE_USER->userGroups[$groupid]['db_mountpoints']);
					if($community_id > 0){
						break;
					}
				}
			}
		}

		// which approver am I? show only model services, which are currently not revised
		$resp_res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'org.ms_mandant, org.ms_approver_one, org.ms_approver_two, cm_community_name, temp.*',			 							// SELECT ...
			'tx_civserv_model_service AS org, tx_civserv_model_service_temp AS temp, tx_civserv_conf_mandant',		// FROM ...
			'org.uid = temp.uid AND ((org.ms_approver_one = '.$community_id.' AND !temp.ms_commit_approver_one AND !temp.ms_revised_approver_one) OR (org.ms_approver_two = '.$community_id.' AND !temp.ms_commit_approver_two AND !temp.ms_revised_approver_two)) AND ms_mandant = cm_community_id AND temp.ms_has_changed AND !temp.hidden AND !org.deleted',					// AND title LIKE "%blabla%"', // WHERE...
			'', 										// GROUP BY...
			'cm_community_name, temp.ms_name',   										// ORDER BY...
			'' 											// LIMIT to 10 rows, starting with number 5 (MySQL compat.)
		);

		$exists = $GLOBALS['TYPO3_DB']->sql_num_rows($resp_res);

		if ($exists && ($community_id > 0)) {
			$tableStart = "<table border=\"0\" cellpadding=\"1\" cellspacing=\"2\" width=\"480\" id=\"typo3-page-stdlist\">";
			$tableStart .= "<tr><td class=\"absmiddle\"><b>&nbsp;</b></td>";
			$tableStart .= "<td class=\"absmiddle\" nowrap=\"nowrap\"><b>".$LANG->getLL("modmsworkflow.title")."</b></td>";
			$tableStart .= "<td class=\"absmiddle\" nowrap=\"nowrap\"><b>".$LANG->getLL("modmsworkflow.date")."</b></td>";
			$tableStart .= "<td class=\"absmiddle\" nowrap=\"nowrap\"><b>".$LANG->getLL("modmsworkflow.action")."</b></td></tr>";
			$tableEnd = "</table>";

			$content.='
				<div align=center>
				'.$LANG->getLL("modmsworkflow.module_description").'
				</div><BR>
			';

			$community_name="";

			while ($resp_services_row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($resp_res)){
				$x = 0;
				$bgcolor = ($x % 2)? "bgColor4-20" : "bgColor4";

					//ms_commit_approver_two, ms_comment_approver_two
				if ($resp_services_row['ms_approver_one']==$community_id && $resp_services_row['ms_approver_two']!=$community_id){
					$responsible = "one";
				} elseif ($resp_services_row['ms_approver_one']!=$community_id && $resp_services_row['ms_approver_two']==$community_id){
					$responsible = "two";
				} else $responsible = "both";


					//if the responsible community changes, than the header should be displayed
				if ($community_name != $resp_services_row['cm_community_name']){
						//Print edited Pages
					$community_name = $resp_services_row['cm_community_name'];
					$tableStart .= "<tr><td class=\"absmiddle\" colspan=\"4\"><hr></td></tr>";
					$tableStart .= "<tr><td class=\"".$bgcolor."\"><b>&nbsp;</b></td><td class=\"".$bgcolor."\" colspan=\"3\"><b>".$LANG->getLL("modmsworkflow.community").' '.$community_name."</b></td></tr>";
				}
				$params = '&edit[tx_civserv_model_service_temp]['.$resp_services_row["uid"].']=edit';
				if (!$resp_services_row['ms_revised_approver_'.$responsible]) {
					$tableStart .= "<tr><td class=\"".$bgcolor."\"><b>".$LANG->getLL("modmsworkflow.ColAPage")."</b></td>";
					$tableStart .= "<td class=\"".$bgcolor."\" nowrap=\"nowrap\">".$resp_services_row["ms_name"]."</td>";
					$tableStart .= "<td class=\"".$bgcolor."\" nowrap=\"nowrap\">".date("d.m.Y",$resp_services_row["tstamp"])."</td>";
					$tableStart .= "<td class=\"".$bgcolor."\" nowrap=\"nowrap\">
									<a href=\"#\" onclick=\"jumpToUrl('?case=view&uid=".$resp_services_row['uid']."',this)\"><img src=\"view.gif\" width=\"11\" height=\"10\" title=\"".$LANG->getLL("modmsworkflow.rec_view")."\" alt=\"\" /></a>
									<a href=\"#\" onclick=\"jumpToUrl('?case=approve&uid=".$resp_services_row['uid']."&responsible=".$responsible."',this)\"><img src=\"commit.gif\" width=\"12\" height=\"12\" title=\"".$LANG->getLL("modmsworkflow.rec_approve")."\" alt=\"\" /></a>
									<a href=\"#\" onclick=\"jumpToUrl('?case=revise&uid=".$resp_services_row['uid']."&responsible=".$responsible."',this)\"><img src=\"revise.gif\" width=\"12\" height=\"12\" title=\"".$LANG->getLL("modmsworkflow.rec_revise")."\" alt=\"\" /></a>
									</td></tr>";

				}
			}
			$content .= $tableStart.$tableEnd."<br><br>";
		} elseif ($community_id > 0) $content .= $LANG->getLL("modmsworkflow.no_work");
		else $content .= $LANG->getLL("modmsworkflow.notAsAdmin");
		$this->content.= $content;
	}


	/**
    * Will get the data from the model_service_temp tabel and displays it in a complex table in the backend
    *
    * @param   [int]      $uid: the uid from the model service, which should be dislayed
    * @return   HTML-table for backend
    */
	function viewContent($uid)	{
		global $LANG,$BE_USER;
		$resp_res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'uid, pid, ms_name, ms_synonym1, ms_synonym2, ms_synonym3, ms_descr_short, ms_descr_long, ms_image, ms_image_text, ms_fees, ms_documents, ms_legal_global, ms_searchword',			 							// SELECT ...
			'tx_civserv_model_service_temp',		// FROM ...
			'uid='.$uid,					// AND title LIKE "%blabla%"', // WHERE...
			'', 										// GROUP BY...
			'',   										// ORDER BY...
			'' 											// LIMIT to 10 rows, starting with number 5 (MySQL compat.)
		);

		$resp_services_row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($resp_res);
		$light=0;
		$tableStart ='
			<table border="0" cellspacing="0" cellpadding="0" width="100%" style="border:solid 1px black;">
			';
		$tableStart .='
			<!--
			 	Name:
			-->
				<tr  bgcolor="#CBC7C3">
					<td>&nbsp;</td>
					<td width="99%"><font color="black"><b>'.$LANG->getLL("modmsworkflow.ms_name").'</b></font></td>
					<td>&nbsp;</td>
				</tr>
				<tr  bgcolor="#E4E0DB">
					<td nowrap="nowrap"></td>
					<td valign="top">
						<input type="" size="96" name="" value="'.$resp_services_row["ms_name"].'" readonly/>
					</td>
					<td>&nbsp;</td>
				</tr>
		';
		$tableStart .='
			<!--
			 	Synonym1:
			-->
				<tr  bgcolor="#CBC7C3">
					<td>&nbsp;</td>
					<td width="99%"><font color="black"><b>'.$LANG->getLL("modmsworkflow.ms_synonym1").'</b></font></td>
					<td>&nbsp;</td>
				</tr>
				<tr  bgcolor="#E4E0DB">
					<td nowrap="nowrap"></td>
					<td valign="top">
						<input type="" size="96" name="" value="'.$resp_services_row["ms_synonym1"].'" readonly/>
					</td>
					<td>&nbsp;</td>
				</tr>
		';
		$tableStart .='
			<!--
			 	Synonym2:
			-->
				<tr  bgcolor="#CBC7C3">
					<td>&nbsp;</td>
					<td width="99%"><font color="black"><b>'.$LANG->getLL("modmsworkflow.ms_synonym2").'</b></font></td>
					<td>&nbsp;</td>
				</tr>
				<tr  bgcolor="#E4E0DB">
					<td nowrap="nowrap"></td>
					<td valign="top">
						<input type="" size="96" name="" value="'.$resp_services_row["ms_synonym2"].'" readonly/>
					</td>
					<td>&nbsp;</td>
				</tr>
		';
		$tableStart .='
			<!--
			 	Synonym3:
			-->
				<tr  bgcolor="#CBC7C3">
					<td>&nbsp;</td>
					<td width="99%"><font color="black"><b>'.$LANG->getLL("modmsworkflow.ms_synonym3").'</b></font></td>
					<td>&nbsp;</td>
				</tr>
				<tr  bgcolor="#E4E0DB">
					<td nowrap="nowrap"></td>
					<td valign="top">
						<input type="" size="96" name="" value="'.$resp_services_row["ms_synonym3"].'" readonly/>
					</td>
					<td>&nbsp;</td>
				</tr>
		';
		$tableStart .='
			<!--
			 	Short description:
			-->
				<tr  bgcolor="#CBC7C3">
					<td>&nbsp;</td>
					<td width="99%"><font color="black"><b>'.$LANG->getLL("modmsworkflow.ms_descr_short").'</b></font></td>
					<td>&nbsp;</td>
				</tr>
				<tr  bgcolor="#E4E0DB" height="100">
						<td nowrap="nowrap"></td>
						<td valign="top" bgcolor="white">
						    <div style="height:280px; width:460px; overflow:auto">'.$resp_services_row["ms_descr_short"].'
							</div>
						</td>
						<td>&nbsp;</td>
				</tr>
		';
		$tableStart .='
			<!--
			 	Long description:
			-->
				<tr  bgcolor="#CBC7C3">
					<td>&nbsp;</td>
					<td width="99%"><font color="black"><b>'.$LANG->getLL("modmsworkflow.ms_descr_long").'</b></font></td>
					<td>&nbsp;</td>
				</tr>
				<tr  bgcolor="#E4E0DB" height="300">
						<td nowrap="nowrap"></td>
						<td valign="top" bgcolor="white">
						    <div style="height:280px; width:460px; overflow:auto">'.$resp_services_row["ms_descr_long"].'
							</div>
						</td>
						<td>&nbsp;</td>
				</tr>
		';

			//Query to get image_folder for model srevices
#		$GLOBALS['TYPO3_DB']->debugOutput=true; //debugging only in test-sites
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'cf_value',			 							// SELECT ...
			'tx_civserv_configuration',		// FROM ...
			'cf_key = "model_service_image_folder"',			// AND title LIKE "%blabla%"', // WHERE...
			'', 										// GROUP BY...
			'',   										// ORDER BY...
			'' 											// LIMIT to 10 rows, starting with number 5 (MySQL compat.)
		);
		$model_service_image_folder = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
			//is a picture available? if not, table will look different
		if ($resp_services_row["ms_image"]=='') {
			$tableStart .='
				<!--
				 	Picture:
				-->
				<tr  bgcolor="#CBC7C3">
					<td>&nbsp;</td>
					<td width="99%"><font color="black"><b>'.$LANG->getLL("modmsworkflow.ms_image").'</b></font></td>
						<td>&nbsp;</td>
				</tr>
				<tr  bgcolor="#E4E0DB">
					<td nowrap="nowrap"></td>
					<td valign="top">
						<table border="0" cellpadding="0" cellspacing="0">
							<td valign="top">
								<tr>
									<input type="" size="96" name="" value="'.$LANG->getLL("modmsworkflow.no_image").'" readonly/>
								</tr>
							</td>
						</table>
					</td>
					<td>&nbsp;</td>
				</tr>
			';
		} else {
			$tableStart .='
				<!--
				 	Picture:
				-->
				<tr  bgcolor="#CBC7C3">
					<td>&nbsp;</td>
					<td width="99%"><font color="black"><b>'.$LANG->getLL("modmsworkflow.ms_image").'</b></font></td>
						<td>&nbsp;</td>
				</tr>
				<tr  bgcolor="#E4E0DB">
					<td nowrap="nowrap"></td>
					<td valign="top">
						<table border="0" cellpadding="0" cellspacing="0">
							<td valign="top">
								<tr>
									<input type="" size="96" name="" value="'.$model_service_image_folder["cf_value"].$resp_services_row["ms_image"].'" readonly/>
								</tr>
								<tr>
									<img src="../../../../'.$model_service_image_folder["cf_value"].$resp_services_row["ms_image"].'" width="40" height="36" />
								</tr>
							</td>
						</table>
					</td>
					<td>&nbsp;</td>
				</tr>
			';
		}
		$tableStart .='
			<!--
			 	Picture description:
			-->
				<tr  bgcolor="#CBC7C3">
					<td>&nbsp;</td>
					<td width="99%"><font color="black"><b>'.$LANG->getLL("modmsworkflow.ms_image_text").'</b></font></td>
					<td>&nbsp;</td>
				</tr>
				<tr  bgcolor="#E4E0DB">
					<td nowrap="nowrap"></td>
					<td valign="top">
						<input type="" size="96" name="" value="'.$resp_services_row["ms_image_text"].'" readonly/>
					</td>
					<td>&nbsp;</td>
				</tr>
		';
		$tableStart .='
			<!--
			 	Fees:
			-->
				<tr  bgcolor="#CBC7C3">
					<td>&nbsp;</td>
					<td width="99%"><font color="black"><b>'.$LANG->getLL("modmsworkflow.ms_fees").'</b></font></td>
					<td>&nbsp;</td>
				</tr>
				<tr  bgcolor="#E4E0DB" height="300">
						<td nowrap="nowrap"></td>
						<td valign="top" bgcolor="white">
						    <div style="height:180px; width:460px; overflow:auto">'.$resp_services_row["ms_fees"].'
							</div>
						</td>
						<td>&nbsp;</td>
				</tr>
		';
		$tableStart .='
			<!--
			 	Required Documents:
			-->
				<tr  bgcolor="#CBC7C3">
					<td>&nbsp;</td>
					<td width="99%"><font color="black"><b>'.$LANG->getLL("modmsworkflow.ms_documents").'</b></font></td>
					<td>&nbsp;</td>
				</tr>
				<tr  bgcolor="#E4E0DB" height="300">
						<td nowrap="nowrap"></td>
						<td valign="top" bgcolor="white">
						    <div style="height:180px; width:460px; overflow:auto">'.$resp_services_row["ms_documents"].'
							</div>
						</td>
						<td>&nbsp;</td>
				</tr>
		';
		$tableStart .='
			<!--
			 	legal global:
			-->
				<tr  bgcolor="#CBC7C3">
					<td>&nbsp;</td>
					<td width="99%"><font color="black"><b>'.$LANG->getLL("modmsworkflow.ms_legal_global").'</b></font></td>
					<td>&nbsp;</td>
				</tr>
				<tr  bgcolor="#E4E0DB" height="300">
						<td nowrap="nowrap"></td>
						<td valign="top" bgcolor="white">
						    <div style="height:180px; width:460px; overflow:auto">'.$resp_services_row["ms_legal_global"].'
							</div>
						</td>
						<td>&nbsp;</td>
				</tr>
		';
		//Query to get all single searchwords for the model service and display them
#		$GLOBALS['TYPO3_DB']->debugOutput=true;
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'sw_search_word',			 							// SELECT ...
			'tx_civserv_search_word',		// FROM ...
			((strlen($resp_services_row["ms_searchword"]) > 0)?'tx_civserv_search_word.uid in ('.$resp_services_row["ms_searchword"].')':''),			// AND title LIKE "%blabla%"', // WHERE...
			'', 										// GROUP BY...
			'',   										// ORDER BY...
			'' 											// LIMIT to 10 rows, starting with number 5 (MySQL compat.)
		);
		$searchwords = '';
		while ($single_searchword = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res) and $resp_services_row["ms_searchword"] > 0) $searchwords .= $single_searchword["sw_search_word"].'<br/>';
		$tableStart .='
			<!--
			 	Searchwords:
			-->
				<tr  bgcolor="#CBC7C3">
					<td>&nbsp;</td>
					<td width="99%"><font color="black"><b>'.$LANG->getLL("modmsworkflow.ms_searchword").'</b></font></td>
					<td>&nbsp;</td>
				</tr>
				<tr  bgcolor="#E4E0DB" height="300">
						<td nowrap="nowrap"></td>
						<td valign="top" bgcolor="white">
						    <div style="height:180px; width:460px; overflow:auto">'.$searchwords.'
							</div>
						</td>
						<td>&nbsp;</td>
				</tr>
		';

		$tableEnd = "</table>";

		$content .= $tableStart.$tableEnd."<br><br>";
		$this->content.= $content;
	}


	/**
    * Prints out the module HTML in the backend
    *
    * @return   HTML content
    */
	function printContent()	{

		$this->content.=$this->doc->endPage();
		echo $this->content;
	}
}

if (defined("TYPO3_MODE") && $TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/civserv/modmsworkflow/index.php"])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/civserv/modmsworkflow/index.php"]);
}


// Make instance:
$SOBE = t3lib_div::makeInstance("tx_civserv_ms_workflow");
$SOBE->init();

// Include files?
foreach($SOBE->include_once as $INC_FILE)	include_once($INC_FILE);

$SOBE->main();
$SOBE->printContent();
?>