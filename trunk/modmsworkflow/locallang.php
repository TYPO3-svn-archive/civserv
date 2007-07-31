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
* Language labels for module "web_txmzminiworkflowM1" - header, description
* 
* This file is detected by the translation tool.
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


$LOCAL_LANG = Array (
	"default" => Array (
		"modmsworkflow.title" => "Model service release",	
		"modmsworkflow.function1" => "Approve menue",	
		"modmsworkflow.function2" => "Administration/configuration",
		"modmsworkflow.date" => "Date",	
		"modmsworkflow.action" => "Actions",
		"modmsworkflow.descriptionFunctionA" => "Within this module you can approve content and new pages!",
		"modmsworkflow.notAsAdmin" => "You can't use this modul as admin",
		"modmsworkflow.colAPage" => "Page",
		"modmsworkflow.colAContent" => "Content",
		"modmsworkflow.ModulA" => "Approvement",
		"modmsworkflow.descriptionFunctionB" => "Select a authorized approver and assign BE-Users or BE-Groups:",
		"modmsworkflow.chooseBEUserAdmin" => "Choose the approver:",
		"modmsworkflow.chooseBEUser" => "Choose one or more BE-User(s):",
		"modmsworkflow.chooseBEGroup" => "Choose one or more BE-Usergroup(s):",
		"modmsworkflow.titleColA" => "Title",
		"modmsworkflow.titleColB" => "Authorized approver",
		"modmsworkflow.titleColC" => "Assigned BE-Users",
		"modmsworkflow.titleColD" => "Assigned BE-Groups",
		"modmsworkflow.titleColE" => "Actions",
		"modmsworkflow.labelTitle" => "the title",
		"modmsworkflow.disabled" => "Modul is disabled",
//ab hier unsere neuen
		"modmsworkflow.email_subject_commit" => "Release of model service: ###model_service_name###",		
		"modmsworkflow.email_text_commit" => "This E-Mail is automaticly created by the TYPO3-Model-Service-Workflow! Please do NOT reply to this E-Mail!\n\nCongratulations Mr/Mrs ###editor###,\nyour change at the model service -- ###model_service_name### -- was accepted by an approver!\n\nThis E-Mail is automaticly created by TYPO3-Model-Service-Workflow! Please do NOT reply to this E-Mail!",
		"modmsworkflow.email_subject_revise" => "Change of model service: ###model_service_name###",
		"modmsworkflow.email_subject_inititate" => "Änderung am Musteranliegen: ###model_service_name###",		
		"modmsworkflow.email_text_revise" => "This E-Mail is automaticly created by the TYPO3-Model-Service-Workflow! Please do NOT reply to this E-Mail!\n\nHello Mr/Mrs ###editor###,\nyour change at the model service -- ###model_service_name### -- was revised!\nThe approver revised with the following comment:\n\n###comment###\n\nThis E-Mail is automaticly created by TYPO3-Model-Service-Workflow! Please do NOT reply to this E-Mail!",
		"modmsworkflow.email_text_initiate" => "This E-Mail is automaticly created by the TYPO3-Model-Service-Workflow! Please do NOT reply to this E-Mail!\n\nHello,\nthere were changes at the model service -- ###model_service_name### --!\nPlease approve them.\n\nThis E-Mail is automaticly created by TYPO3-Model-Service-Workflow! Please do NOT reply to this E-Mail!",
		"modmsworkflow.rec_view" => "View model service ",
		"modmsworkflow.rec_approve" => "Approve model service",	
		"modmsworkflow.rec_revise" => "Revise model service",	
		"modmsworkflow.back_button" => "Back",	
		"modmsworkflow.ok_button" => "OK",	
		"modmsworkflow.cancel_button" => "Cancel",	
		"modmsworkflow.reset_button" => "Reset",	
		"modmsworkflow.revise_button" => "Revise",	
		"modmsworkflow.send_button" => "Send",
		"modmsworkflow.community" => "Responsible community:",
		"modmsworkflow.approve" => "Do you realy want to approve the changes?",
		"modmsworkflow.revise" => "Do you realy want to revise the changes? To revise you have to give a short description of the problems for the sender!",
		"modmsworkflow.module_description" => "Within this module you can view, approve or revice model services in a workflow",
		"modmsworkflow.viewContent" => "View model service",	
		"modmsworkflow.ms_name" => "Name",	
		"modmsworkflow.ms_synonym1" => "1st Synonym",	
		"modmsworkflow.ms_synonym2" => "2nd Synonym",	
		"modmsworkflow.ms_synonym3" => "3rd Synonym",	
		"modmsworkflow.ms_descr_short" => "Short description",	
		"modmsworkflow.ms_descr_long" => "Detailed description",	
		"modmsworkflow.ms_image" => "Image",	
		"modmsworkflow.no_image" => "No image available",	
		"modmsworkflow.ms_image_text" => "Image text",	
		"modmsworkflow.ms_fees" => "Fees, rates and tolls",	
		"modmsworkflow.ms_documents" => "Necessary documents",	
		"modmsworkflow.ms_legal_global" => "Legal basis (Global issues)",	
		"modmsworkflow.ms_searchword" => "Modelservice-Searchword-Relationship (Searchword)",	
		"modmsworkflow.old_comment_beginning" => "Beginning of old comment",
		"modmsworkflow.old_comment_end" => "End of old comment",
		"modmsworkflow.label_revised" => "(REVISED)",
		"modmsworkflow.label_approved" => "(approved)",
		"modmsworkflow.label_monitoring" => "(MONITORING)",
		"modmsworkflow.no_work" => "- no model services to approve -",
	),
	"de" => Array (
		"modmsworkflow.title" => "Musteranliegen Freigabe",	
		"modmsworkflow.function1" => "Freigabemenü",	
		"modmsworkflow.function2" => "Adminstration/Konfiguration",
		"modmsworkflow.date" => "Datum",	
		"modmsworkflow.action" => "Aktionen",
		"modmsworkflow.descriptionFunctionA" => "Dieses Modul ermöglicht Ihnen die Freischaltung von angelegten Seiten und Contentelementen die durch einen Redakteur angelegt wurden!",
		"modmsworkflow.colAPage" => "Seite",
		"modmsworkflow.colAContent" => "Content",
		"modmsworkflow.ModulA" => "Freischaltung",
		"modmsworkflow.descriptionFunctionB" => "Dieser Bereich ermöglicht Ihnen das Erstellen von freigabeberechtigten Admins und die Zuweisung von Redakteuren:",	
		"modmsworkflow.chooseBEUserAdmin" => "Wählen Sie aus dem Auswahlfeld einen freigabeberechtigten Redakteur:",
		"modmsworkflow.chooseBEUser" => "Wählen Sie aus dem Auswahlfeld einen freigabeberechtigten Redakteur:",
		"modmsworkflow.chooseBEGroup" => "Wählen Sie aus dem linken Auswahlfeld die dem freigabeberechtigten Admin zuzuweisende Benutzergruppe zu:",
		"modmsworkflow.titleColA" => "Titel",
		"modmsworkflow.titleColB" => "freigabeberechtigter Redakteur",
		"modmsworkflow.titleColC" => "angehängte Redakteur",
		"modmsworkflow.titleColD" => "angehängte User-Gruppe",
		"modmsworkflow.titleColE" => "Aktionen",
		"modmsworkflow.labelTitle" => "der Titel",
		"modmsworkflow.disabled" => "Modul steht nicht zur Verfügung",

//ab hier unsere neuen
		"modmsworkflow.email_subject_commit" => "Freigabe des Musteranliegens: ###model_service_name###",		
		"modmsworkflow.email_text_commit" => "Diese E-Mail wurde automatisch durch den TYPO3-Musteranliegen-Workflow generiert!\nBitte antworten Sie NICHT auf diese E-Mail!\n***********************************\n\nHerzlichen Glückwunsch Frau(Herr) ###editor###!\nIhre Änderungen am Musteranliegen -- ###model_service_name### -- wurden von einer Kontrollinstanz freigegeben!\n\n***********************************\nDiese E-Mail wurde automatisch durch den TYPO3-Musteranliegen-Workflow generiert!\nBitte antworten Sie NICHT auf diese E-Mail!",
		"modmsworkflow.email_subject_revise" => "Änderung am Musteranliegen: ###model_service_name###",		
		"modmsworkflow.email_subject_inititate" => "Änderung am Musteranliegen: ###model_service_name###",
		"modmsworkflow.email_text_revise" => "Diese E-Mail wurde automatisch durch den TYPO3-Musteranliegen-Workflow generiert!\nBitte antworten Sie NICHT auf diese E-Mail!\n***********************************\n\nSehr geehrte(r) Frau(Herr) ###editor###,\nIhre Änderungen am Musteranliegen -- ###model_service_name### -- wurden zurückgewiesen!\nDie Kontrollinstanz wies die Änderung am Musteranliegen mit folgendem Kommentar zurück:\n\n###comment###\n\n***********************************\nDiese E-Mail wurde automatisch durch den TYPO3-Musteranliegen-Workflow generiert!\nBitte antworten Sie NICHT auf diese E-Mail!",
		"modmsworkflow.email_text_initiate" => "Diese E-Mail wurde automatisch durch den TYPO3-Musteranliegen-Workflow generiert!\nBitte antworten Sie NICHT auf diese E-Mail!\n***********************************\n\nSehr geehrte Damen und Herren,\nDas Musteranliegen -- ###model_service_name### -- wurde geändert!\nBitte prüfen Sie die Änderungen und geben Sie diesen ggfs. statt bzw. lehnen Sie diese ab.\n\n***********************************\nDiese E-Mail wurde automatisch durch den TYPO3-Musteranliegen-Workflow generiert!\nBitte antworten Sie NICHT auf diese E-Mail!",
		"modmsworkflow.rec_view" => "Datensatz ansehen",
		"modmsworkflow.rec_approve" => "Datensatz freigeben",	
		"modmsworkflow.rec_revise" => "Datensatz ablehnen",		
		"modmsworkflow.ok_button" => "OK",	
		"modmsworkflow.back_button" => "Zurück",	
		"modmsworkflow.cancel_button" => "Abbrechen",	
		"modmsworkflow.reset_button" => "Reset",	
		"modmsworkflow.revise_button" => "Ablehnen",	
		"modmsworkflow.send_button" => "Absenden",	
		"modmsworkflow.community" => "Bearbeitende Verwaltung:",
		"modmsworkflow.approve" => "Wollen Sie das geänderte Musteranliegen wirklich akzeptieren?",
		"modmsworkflow.revise" => "Wollen Sie das geänderte Musteranliegen wirklich ablehnen? Um ablehnen zu können, ist eine kurze Beschreibung der Gründe als Kommentar für den Bearbeiter einzugeben!",
		"modmsworkflow.module_description" => "Innerhalb dieses Modules können Sie Musteranliegen aus dem Workflow anschauen, freigeben oder abweisen ",
		"modmsworkflow.notAsAdmin" => "Diese Modul kann nur genutzt werden, wenn Ihrem Benutzerkonto ein eindeutiger DB-Mount zugeordnet ist. Setzen Sie sich bitte mit Ihrem Administrator in Verbindung!",
		"modmsworkflow.viewContent" => "Musteranliegen anzeigen",
		"modmsworkflow.ms_name" => "Name",	
		"modmsworkflow.ms_synonym1" => "1. Synonym",	
		"modmsworkflow.ms_synonym2" => "2. Synonym",	
		"modmsworkflow.ms_synonym3" => "3. Synonym",	
		"modmsworkflow.ms_descr_short" => "Kurzbeschreibung",	
		"modmsworkflow.ms_descr_long" => "Ausführliche Beschreibung",	
		"modmsworkflow.ms_image" => "Bild",	
		"modmsworkflow.no_image" => "Kein Bild vorhanden",	
		"modmsworkflow.ms_image_text" => "Bild Text (Barrierefreiheit)",	
		"modmsworkflow.ms_fees" => "Gebühren",	
		"modmsworkflow.ms_documents" => "Benötigte Unterlagen",	
		"modmsworkflow.ms_legal_global" => "Rechtsgrundlagen (Allgemein)",	
		"modmsworkflow.ms_searchword" => "Musteranliegen-Suchwort-Zuordnung (Suchwort)",	
		"modmsworkflow.old_comment_beginning" => "Anfang des alten Kommentars",
		"modmsworkflow.old_comment_end" => "Ende des alten Kommentars",
		"modmsworkflow.label_revised" => "(ABGELEHNT)",
		"modmsworkflow.label_approved" => "(freigegeben)",
		"modmsworkflow.label_monitoring" => "(ZUR KONTROLLE)",
		"modmsworkflow.no_work" => " - keine Musteranliegen zur Freigabe vorhanden -",
	),
);
?>