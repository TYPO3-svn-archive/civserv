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
* Language labels for wizards
* 
* This file is detected by the translation tool.
*
* @author Tobias Müller (mullerto@uni-muenster.de),
* @author Maurits Hinzen (mhinzen@uni-muenster.de),
* @package TYPO3
* @subpackage tx_civserv
* @version 1.0
*
*/


$LOCAL_LANG = Array (
    'default' => Array (
				// LOCAL_LANG for all abc-wizards
            'all_abc_wizards.other' => 'Other',
			'all_abc_wizards.search' => 'Search',
			'all_category_wizards.search' => 'Search',
			'all_wizards.search_warning' => 'Please enter a search item !',
			// LOCAL_LANG for class.tx_civserv_wizard_employee_em_position.php
            'tx_civserv_wizard_employee_em_position.title' => 'Select positions of employee',
            'tx_civserv_wizard_employee_em_position.select_letter_text' => 'Select beginning letter of position',
			'tx_civserv_wizard_employee_em_position.select_positions_text' => 'Select positions beginning with ',
			'tx_civserv_wizard_employee_em_position.select_positions_text_no_abc' => 'Select positions',
            'tx_civserv_wizard_employee_em_position.letter' => 'Letter',
            'tx_civserv_wizard_employee_em_position.positions' => 'Positions',
            'tx_civserv_wizard_employee_em_position.OK_Button' => 'OK',
            'tx_civserv_wizard_employee_em_position.Cancel_Button' => 'Cancel',
            'tx_civserv_wizard_employee_em_position.warning_msg_1' => 'Please choose at least one position!',
            'tx_civserv_wizard_employee_em_position.warning_msg_2' => 'Error - reference to main window is not set properly!',
				// LOCAL_LANG for class.tx_civserv_wizard_modelservice.php
            'tx_civserv_wizard_modelservice.title' => 'Select Model Service',
            'tx_civserv_wizard_modelservice.select_category_text' => 'Select category',
            'tx_civserv_wizard_modelservice.select_model_service_text' => 'Select model service',
            'tx_civserv_wizard_modelservice.model_service_category' => 'Category',
            'tx_civserv_wizard_modelservice.model_service' => 'Model service',
            'tx_civserv_wizard_modelservice.model_service_category_dummy' => 'Select category',
            'tx_civserv_wizard_modelservice.model_service_dummy' => 'Select model service',
            'tx_civserv_wizard_modelservice.OK_Button' => 'OK',
            'tx_civserv_wizard_modelservice.Cancel_Button' => 'Cancel',
            'tx_civserv_wizard_modelservice.warning_msg_1' => 'Please choose a model service!',
            'tx_civserv_wizard_modelservice.warning_msg_2' => 'Error - reference to main window is not set properly!',
				// LOCAL_LANG for class.tx_civserv_wizard_organisation_supervisor.php
            'tx_civserv_wizard_organisation_supervisor.title' => 'Select Supervisor',
            'tx_civserv_wizard_organisation_supervisor.select_letter_text' => 'Select beginning letter of name',
            'tx_civserv_wizard_organisation_supervisor.select_supervisor_text' => 'Select supervisor beginning with ',
			'tx_civserv_wizard_organisation_supervisor.select_supervisor_text_no_abc' => 'Select supervisor',
            'tx_civserv_wizard_organisation_supervisor.letter' => 'Letter',
            'tx_civserv_wizard_organisation_supervisor.supervisor' => 'Supervisor',
            'tx_civserv_wizard_organisation_supervisor.supervisor_dummy' => 'Select supervisor',
            'tx_civserv_wizard_organisation_supervisor.OK_Button' => 'OK',
            'tx_civserv_wizard_organisation_supervisor.Cancel_Button' => 'Cancel',
            'tx_civserv_wizard_organisation_supervisor.warning_msg_1' => 'Please choose a supervisor!',
            'tx_civserv_wizard_organisation_supervisor.warning_msg_2' => 'Error - reference to main window is not set properly!',
				// LOCAL_LANG for class.tx_civserv_wizard_service_form.php
            'tx_civserv_wizard_service_form.title' => 'Select formulars',
            'tx_civserv_wizard_service_form.select_letter_text' => 'Select beginning letter of formular',
			'tx_civserv_wizard_service_form.select_formulars_text' => 'Select formulars beginning with ',
			'tx_civserv_wizard_service_form.select_formulars_text_no_abc' => 'Select formulars',
            'tx_civserv_wizard_service_form.letter' => 'Letter',
            'tx_civserv_wizard_service_form.formulars' => 'Formulars',
            'tx_civserv_wizard_service_form.OK_Button' => 'OK',
            'tx_civserv_wizard_service_form.Cancel_Button' => 'Cancel',
            'tx_civserv_wizard_service_form.warning_msg_1' => 'Please choose at least one formular!',
            'tx_civserv_wizard_service_form.warning_msg_2' => 'Error - reference to main window is not set properly!',
				// LOCAL_LANG for class.tx_civserv_wizard_service_position.php
            'tx_civserv_wizard_service_position.title' => 'Select positions',
            'tx_civserv_wizard_service_position.select_letter_text' => 'Select beginning letter of position',
			'tx_civserv_wizard_service_position.select_positions_text' => 'Select positions beginning with ',
			'tx_civserv_wizard_service_position.select_positions_text_no_abc' => 'Select positions',
            'tx_civserv_wizard_service_position.letter' => 'Letter',
            'tx_civserv_wizard_service_position.positions' => 'Positions',
            'tx_civserv_wizard_service_position.OK_Button' => 'OK',
            'tx_civserv_wizard_service_position.Cancel_Button' => 'Cancel',
            'tx_civserv_wizard_service_position.warning_msg_1' => 'Please choose at least one position!',
            'tx_civserv_wizard_service_position.warning_msg_2' => 'Error - reference to main window is not set properly!',
				// LOCAL_LANG for class.tx_civserv_wizard_service_position_em_name.php
            'tx_civserv_wizard_service_position_em_name.title' => 'Select positions by employee-name',
			'tx_civserv_wizard_service_position_em_name.select_letter_text' => 'Select first letter of the employee',
			'tx_civserv_wizard_service_position_em_name.select_positions_text' => 'Select employees beginning with ',
			'tx_civserv_wizard_service_position_em_name.select_positions_text_no_abc' => 'Select positions by employee-names',
			'tx_civserv_wizard_service_position_em_name.letter' => 'Letter',
			'tx_civserv_wizard_service_position_em_name.positions' => 'Positions',
			'tx_civserv_wizard_service_position_em_name.OK_Button' => 'OK',
			'tx_civserv_wizard_service_position_em_name.Cancel_Button' => 'Cancel',
			'tx_civserv_wizard_service_position_em_name.warning_msg_1' => 'Please choose at least one employee occupying position!',
			'tx_civserv_wizard_service_position_em_name.warning_msg_2' => 'Error - reference to main window is not set properly!',
				// LOCAL_LANG for class.tx_civserv_wizard_service_position_limited_sv_organisation.php
            'tx_civserv_wizard_service_position_limited_sv_organisation.title' => 'Select among the positions belonging to the organisation(s) associated for this service',
			'tx_civserv_wizard_service_position_limited_sv_organisation.select_letter_text' => 'Select first letter of the position',
			'tx_civserv_wizard_service_position_limited_sv_organisation.select_positions_text' => 'Select positions beginning with ',
			'tx_civserv_wizard_service_position_limited_sv_organisation.select_positions_text_no_abc' => 'Select positions',
			'tx_civserv_wizard_service_position_limited_sv_organisation.letter' => 'Letter',
			'tx_civserv_wizard_service_position_limited_sv_organisation.positions' => 'Positions',
			'tx_civserv_wizard_service_position_limited_sv_organisation.OK_Button' => 'OK',
			'tx_civserv_wizard_service_position_limited_sv_organisation.warning_org' => 'please associatethis service with an organisation first!',
			'tx_civserv_wizard_service_position_limited_sv_organisation.Cancel_Button' => 'Close Window',
			'tx_civserv_wizard_service_position_limited_sv_organisation.warning_msg_1' => 'Please enter position for which you whish to find the employee!',
			'tx_civserv_wizard_service_position_limited_sv_organisation.warning_msg_2' => 'Error - reference to main window is not set properly!',
				// LOCAL_LANG for class.tx_civserv_wizard_service_position_information.php
            'tx_civserv_wizard_service_position_information.title' => 'Find employee occupying a given position',
			'tx_civserv_wizard_service_position_information.select_letter_text' => 'Enter the position to which you whish to find the employee (abbr. possible)',
			'tx_civserv_wizard_service_position_information.select_positions_text_no_abc' => 'Select positions',
			'tx_civserv_wizard_service_position_information.positions' => 'Positions',
			'tx_civserv_wizard_service_position_information.Cancel_Button' => 'Close Window',
			'tx_civserv_wizard_service_position_information.warning_msg_1' => 'Please enter position for which you whish to find the employee!',
			'tx_civserv_wizard_service_position_information.warning_msg_2' => 'Error - reference to main window is not set properly!',
				// LOCAL_LANG for class.tx_civserv_wizard_service_organisation.php
            'tx_civserv_wizard_service_organisation.title' => 'Select organisations',
            'tx_civserv_wizard_service_organisation.select_letter_text' => 'Select beginning letter of organisation',
			'tx_civserv_wizard_service_organisation.select_organisations_text' => 'Select organisations beginning with ',
			'tx_civserv_wizard_service_organisation.select_organisations_text_no_abc' => 'Select organisations',
            'tx_civserv_wizard_service_organisation.letter' => 'Letter',
            'tx_civserv_wizard_service_organisation.organisations' => 'organisations',
            'tx_civserv_wizard_service_organisation.OK_Button' => 'OK',
			'tx_civserv_wizard_service_organisation.other' => ' | Organisation Code',
            'tx_civserv_wizard_service_organisation.Cancel_Button' => 'Cancel',
            'tx_civserv_wizard_service_organisation.warning_msg_1' => 'Please choose at least one organisation!',
            'tx_civserv_wizard_service_organisation.warning_msg_2' => 'Error - reference to main window is not set properly!',
				// LOCAL_LANG for class.tx_civserv_wizard_service_searchword.php
            'tx_civserv_wizard_service_searchword.title' => 'Select searchwords',
            'tx_civserv_wizard_service_searchword.select_letter_text' => 'Select beginning letter of searchwords',
			'tx_civserv_wizard_service_searchword.select_searchword_text' => 'Select searchwords beginning with ',
			'tx_civserv_wizard_service_searchword.select_searchword_text_no_abc' => 'Select searchwords',
            'tx_civserv_wizard_service_searchword.letter' => 'Letter',
            'tx_civserv_wizard_service_searchword.searchwords' => 'Searchwords',
            'tx_civserv_wizard_service_searchword.OK_Button' => 'OK',
            'tx_civserv_wizard_service_searchword.Cancel_Button' => 'Cancel',
            'tx_civserv_wizard_service_searchword.warning_msg_1' => 'Please choose at least one searchword!',
            'tx_civserv_wizard_service_searchword.warning_msg_2' => 'Error - reference to main window is not set properly!',
				// LOCAL_LANG for class.tx_civserv_wizard_service_similar_services.php
            'tx_civserv_wizard_service_similar_services.title' => 'Select similar services',
            'tx_civserv_wizard_service_similar_services.select_category_text' => 'Select a service category',
			'tx_civserv_wizard_service_similar_services.select_service_text' => 'Select services',
            'tx_civserv_wizard_service_similar_services.service_category_dummy' => 'Select category',
            'tx_civserv_wizard_service_similar_services.category' => 'Category',
            'tx_civserv_wizard_service_similar_services.services' => 'Services',
            'tx_civserv_wizard_service_similar_services.OK_Button' => 'OK',
            'tx_civserv_wizard_service_similar_services.Cancel_Button' => 'Cancel',
            'tx_civserv_wizard_service_similar_services.warning_msg_1' => 'Please choose at least one service!',
            'tx_civserv_wizard_service_similar_services.warning_msg_2' => 'Error - reference to main window is not set properly!',
				// LOCAL_LANG for class.tx_civserv_wizard_employee_position_room.php
			'tx_civserv_wizard_employee_position_room.title' => 'Select rooms',
			'tx_civserv_wizard_employee_position_room.select_letter_text' => 'Select first letter of building',
            'tx_civserv_wizard_employee_position_room.select_building_text' => 'Select a building',
            'tx_civserv_wizard_employee_position_room.select_floor_text' => 'Select the floor',
			'tx_civserv_wizard_employee_position_room.select_room_text' => 'Select the room',
            'tx_civserv_wizard_employee_position_room.building_dummy' => 'Select a building',
			'tx_civserv_wizard_employee_position_room.floor_dummy' => 'Select a floor',
			'tx_civserv_wizard_employee_position_room.room_dummy' => 'Select a room',
            #'tx_civserv_wizard_employee_position_room.category' => 'Category',
            #'tx_civserv_wizard_employee_position_room.services' => 'Services',
            'tx_civserv_wizard_employee_position_room.OK_Button' => 'OK',
            'tx_civserv_wizard_employee_position_room.Cancel_Button' => 'Cancel',
            'tx_civserv_wizard_employee_position_room.warning_msg_1' => 'Please choose a room',
            'tx_civserv_wizard_employee_position_room.warning_msg_2' => 'Error - reference to main window is not set properly!',
 ),    
        'de' => Array (
				// LOCAL_LANG for all abc-wizards
            'all_abc_wizards.other' => 'Sonstige',
			'all_abc_wizards.search' => 'Suche',
			'all_category_wizards.search' => 'Suche',
			'all_wizards.search_warning' => 'Bitte geben Sie einen Suchbegriff ein !',
				// LOCAL_LANG for class.tx_civserv_wizard_employee_em_position.php
            'tx_civserv_wizard_employee_em_position.title' => 'Wählen Sie die Stellen des Mitarbeiters',
            'tx_civserv_wizard_employee_em_position.select_letter_text' => 'Anfangsbuchstaben der Stellenbezeichnung wählen',
            'tx_civserv_wizard_employee_em_position.select_positions_text' => 'Stellen wählen mit Anfangsbuchstaben ',
			'tx_civserv_wizard_employee_em_position.select_positions_text_no_abc' => 'Stellen wählen',
            'tx_civserv_wizard_employee_em_position.letter' => 'Buchstabe',
            'tx_civserv_wizard_employee_em_position.positions' => 'Stellen',
            'tx_civserv_wizard_employee_em_position.OK_Button' => 'OK',
            'tx_civserv_wizard_employee_em_position.Cancel_Button' => 'Abbrechen',
            'tx_civserv_wizard_employee_em_position.warning_msg_1' => 'Bitte wählen Sie mindestens eine Position aus!',
            'tx_civserv_wizard_employee_em_position.warning_msg_2' => 'FEHLER - Referenz auf das Hauptfenster nicht richtig gesetzt!',
				// LOCAL_LANG for class.tx_civserv_wizard_modelservice.php
            'tx_civserv_wizard_modelservice.title' => 'Wählen Sie ein Musteranliegen',
            'tx_civserv_wizard_modelservice.select_category_text' => 'Kategorie wählen',
            'tx_civserv_wizard_modelservice.select_model_service_text' => 'Musteranliegen wählen',
            'tx_civserv_wizard_modelservice.model_service_category' => 'Kategorie',
            'tx_civserv_wizard_modelservice.model_service' => 'Musteranliegen',
            'tx_civserv_wizard_modelservice.model_service_category_dummy' => 'Kategorie wählen',
            'tx_civserv_wizard_modelservice.model_service_dummy' => 'Musteranliegen wählen',
            'tx_civserv_wizard_modelservice.OK_Button' => 'OK',
            'tx_civserv_wizard_modelservice.Cancel_Button' => 'Abbrechen',
            'tx_civserv_wizard_modelservice.warning_msg_1' => 'Bitte wählen Sie ein Musteranliegen aus!',
            'tx_civserv_wizard_modelservice.warning_msg_2' => 'FEHLER - Referenz auf das Hauptfenster nicht richtig gesetzt!',
				// LOCAL_LANG for class.tx_civserv_wizard_organisation_supervisor.php
            'tx_civserv_wizard_organisation_supervisor.title' => 'Wählen Sie einen Leiter',
            'tx_civserv_wizard_organisation_supervisor.select_letter_text' => 'Anfangsbuchstaben des Nachnamens wählen',
            'tx_civserv_wizard_organisation_supervisor.select_supervisor_text' => 'Leiter wählen mit Anfangsbuchstaben ',
			'tx_civserv_wizard_organisation_supervisor.select_supervisor_text_no_abc' => 'Leiter wählen',
            'tx_civserv_wizard_organisation_supervisor.letter' => 'Buchstabe',
            'tx_civserv_wizard_organisation_supervisor.supervisor' => 'Leiter',
            'tx_civserv_wizard_organisation_supervisor.supervisor_dummy' => 'Leiter wählen',
            'tx_civserv_wizard_organisation_supervisor.OK_Button' => 'OK',
            'tx_civserv_wizard_organisation_supervisor.Cancel_Button' => 'Abbrechen',
            'tx_civserv_wizard_organisation_supervisor.warning_msg_1' => 'Bitte wählen Sie einen Leiter aus!',
            'tx_civserv_wizard_organisation_supervisor.warning_msg_2' => 'FEHLER - Referenz auf das Hauptfenster nicht richtig gesetzt!',
				// LOCAL_LANG for class.tx_civserv_wizard_service_form.php
            'tx_civserv_wizard_service_form.title' => 'Wählen Sie die Formulare',
            'tx_civserv_wizard_service_form.select_letter_text' => 'Anfangsbuchstaben der Formularbezeichnung wählen',
            'tx_civserv_wizard_service_form.select_formulars_text' => 'Formulare wählen mit Anfangsbuchstaben ',
			'tx_civserv_wizard_service_form.select_formulars_text_no_abc' => 'Formulare wählen',
            'tx_civserv_wizard_service_form.letter' => 'Buchstabe',
            'tx_civserv_wizard_service_form.formulars' => 'Formulare',
            'tx_civserv_wizard_service_form.OK_Button' => 'OK',
            'tx_civserv_wizard_service_form.Cancel_Button' => 'Abbrechen',
            'tx_civserv_wizard_service_form.warning_msg_1' => 'Bitte wählen Sie mindestens ein Formular aus!',
            'tx_civserv_wizard_service_form.warning_msg_2' => 'FEHLER - Referenz auf das Hauptfenster nicht richtig gesetzt!',
				// LOCAL_LANG for class.tx_civserv_wizard_service_position.php
            'tx_civserv_wizard_service_position.title' => 'Wählen Sie aus allen verfügbaren Positionen über die Stellenbezeichnung',
            'tx_civserv_wizard_service_position.select_letter_text' => 'Anfangsbuchstaben der Stellenbezeichnung wählen',
            'tx_civserv_wizard_service_position.select_positions_text' => 'Stellen wählen mit Anfangsbuchstaben ',
			'tx_civserv_wizard_service_position.select_positions_text_no_abc' => 'Stellen wählen',
            'tx_civserv_wizard_service_position.letter' => 'Buchstabe',
            'tx_civserv_wizard_service_position.positions' => 'Stellen',
            'tx_civserv_wizard_service_position.OK_Button' => 'OK',
            'tx_civserv_wizard_service_position.Cancel_Button' => 'Abbrechen',
            'tx_civserv_wizard_service_position.warning_msg_1' => 'Bitte wählen Sie mindestens eine Stelle aus!',
            'tx_civserv_wizard_service_position.warning_msg_2' => 'FEHLER - Referenz auf das Hauptfenster nicht richtig gesetzt!',
				// LOCAL_LANG for class.tx_civserv_wizard_service_position_em_name.php
			'tx_civserv_wizard_service_position_em_name.title' => 'Wählen Sie aus allen verfügbaren Position über den Namen des Mitarbeiters',
			'tx_civserv_wizard_service_position_em_name.select_letter_text' => 'Anfangsbuchstaben des Mitarbeiters wählen',
			'tx_civserv_wizard_service_position_em_name.select_positions_text' => 'Mitarbeiter wählen mit Anfangsbuchstaben ',
			'tx_civserv_wizard_service_position_em_name.select_positions_text_no_abc' => 'Mitarbeiter wählen',
			'tx_civserv_wizard_service_position_em_name.letter' => 'Buchstabe',
			'tx_civserv_wizard_service_position_em_name.positions' => 'Stellen',
			'tx_civserv_wizard_service_position_em_name.OK_Button' => 'OK',
			'tx_civserv_wizard_service_position_em_name.Cancel_Button' => 'Abbrechen',
			'tx_civserv_wizard_service_position_em_name.warning_msg_1' => 'Bitte wählen Sie mindestens einen Mitarbeiter aus!',
			'tx_civserv_wizard_service_position_em_name.warning_msg_2' => 'FEHLER - Referenz auf das Hauptfenster nicht richtig gesetzt!',
				// LOCAL_LANG for class.tx_civserv_wizard_service_position_limited_sv_organisation.php
            'tx_civserv_wizard_service_position_limited_sv_organisation.title' => 'Wählen Sie aus den Positionen, die zu den in diese Maske eingetragenen Organistionen gehören',
            'tx_civserv_wizard_service_position_limited_sv_organisation.select_letter_text' => 'Anfangsbuchstaben der Stellenbezeichnung wählen',
            'tx_civserv_wizard_service_position_limited_sv_organisation.select_positions_text' => 'Stellen wählen mit Anfangsbuchstaben ',
			'tx_civserv_wizard_service_position_limited_sv_organisation.select_positions_text_no_abc' => 'Stellen wählen',
            'tx_civserv_wizard_service_position_limited_sv_organisation.letter' => 'Buchstabe',
            'tx_civserv_wizard_service_position_limited_sv_organisation.positions' => 'Stellen',
            'tx_civserv_wizard_service_position_limited_sv_organisation.OK_Button' => 'OK',
			'tx_civserv_wizard_service_position_limited_sv_organisation.warning_org' => 'Bitte verknüpfen Sie diese Dienstleistung zunächst mit einer Organisationseinheit!',
            'tx_civserv_wizard_service_position_limited_sv_organisation.Cancel_Button' => 'Abbrechen',
            'tx_civserv_wizard_service_position_limited_sv_organisation.warning_msg_1' => 'Bitte wählen Sie mindestens eine Stelle aus!',
            'tx_civserv_wizard_service_position_limited_sv_organisation.warning_msg_2' => 'FEHLER - Referenz auf das Hauptfenster nicht richtig gesetzt!',
				// LOCAL_LANG for class.tx_civserv_wizard_service_position_information.php
			'tx_civserv_wizard_service_position_information.title' => 'Informieren Sie sich, welcher Mitarbeiter die Stelle besetzt',
			'tx_civserv_wizard_service_position_information.select_letter_text' => 'Geben Sie eine Stellenbezeichnung als Suchbegriff ein (Wortteil genügt)',
			'tx_civserv_wizard_service_position_information.select_positions_text_no_abc' => 'Stellen wählen',
			'tx_civserv_wizard_service_position_information.positions' => 'Stellen',
			'tx_civserv_wizard_service_position_information.Cancel_Button' => 'Fenster schließen',
			'tx_civserv_wizard_service_position_information.warning_msg_1' => 'Bitte wählen Sie mindestens eine Position aus!',
			'tx_civserv_wizard_service_position_information.warning_msg_2' => 'FEHLER - Referenz auf das Hauptfenster nicht richtig gesetzt!',
				// LOCAL_LANG for class.tx_civserv_wizard_service_organisation.php
            'tx_civserv_wizard_service_organisation.title' => 'Wählen Sie die Organisationen',
            'tx_civserv_wizard_service_organisation.select_letter_text' => 'Anfangsbuchstaben der Organisation wählen',
            'tx_civserv_wizard_service_organisation.select_organisations_text' => 'Organisationen wählen mit Anfangsbuchstaben ',
			'tx_civserv_wizard_service_organisation.select_organisations_text_no_abc' => 'Organisationen wählen',
            'tx_civserv_wizard_service_organisation.letter' => 'Buchstabe',
            'tx_civserv_wizard_service_organisation.organisations' => 'Organisationen',
            'tx_civserv_wizard_service_organisation.OK_Button' => 'OK',
			'tx_civserv_wizard_service_organisation.other' => ' | Ämterkürzel',
            'tx_civserv_wizard_service_organisation.Cancel_Button' => 'Abbrechen',
            'tx_civserv_wizard_service_organisation.warning_msg_1' => 'Bitte wählen Sie mindestens eine Organisation aus!',
            'tx_civserv_wizard_service_organisation.warning_msg_2' => 'FEHLER - Referenz auf das Hauptfenster nicht richtig gesetzt!',
				// LOCAL_LANG for class.tx_civserv_wizard_service_searchword.php
            'tx_civserv_wizard_service_searchword.title' => 'Wählen Sie die Suchworte',
            'tx_civserv_wizard_service_searchword.select_letter_text' => 'Anfangsbuchstaben der Suchworte wählen',
            'tx_civserv_wizard_service_searchword.select_searchword_text' => 'Suchworte wählen mit Anfangsbuchstaben ',
			'tx_civserv_wizard_service_searchword.select_searchword_text_no_abc' => 'Suchworte wählen',
            'tx_civserv_wizard_service_searchword.letter' => 'Buchstabe',
            'tx_civserv_wizard_service_searchword.searchwords' => 'Suchworte',
            'tx_civserv_wizard_service_searchword.OK_Button' => 'OK',
            'tx_civserv_wizard_service_searchword.Cancel_Button' => 'Abbrechen',
            'tx_civserv_wizard_service_searchword.warning_msg_1' => 'Bitte wählen Sie mindestens ein Suchwort aus!',
            'tx_civserv_wizard_service_searchword.warning_msg_2' => 'FEHLER - Referenz auf das Hauptfenster nicht richtig gesetzt!',
				// LOCAL_LANG for class.tx_civserv_wizard_service_similar_services.php
            'tx_civserv_wizard_service_similar_services.title' => 'Wählen Sie die ähnlichen Dienstleistungen',
            'tx_civserv_wizard_service_similar_services.select_category_text' => 'Dienstleistungskategorie wählen',
            'tx_civserv_wizard_service_similar_services.select_service_text' => 'Dienstleistungen wählen',
            'tx_civserv_wizard_service_similar_services.service_category_dummy' => 'Kategorie wählen',
            'tx_civserv_wizard_service_similar_services.category' => 'Kategorie',
            'tx_civserv_wizard_service_similar_services.services' => 'Dienstleistungen',
            'tx_civserv_wizard_service_similar_services.OK_Button' => 'OK',
            'tx_civserv_wizard_service_similar_services.Cancel_Button' => 'Abbrechen',
            'tx_civserv_wizard_service_similar_services.warning_msg_1' => 'Bitte wählen Sie mindestens eine Dienstleistung aus!',
            'tx_civserv_wizard_service_similar_services.warning_msg_2' => 'FEHLER - Referenz auf das Hauptfenster nicht richtig gesetzt!',
				// LOCAL_LANG for class.tx_civserv_wizard_employee_position_room.php
			'tx_civserv_wizard_employee_position_room.title' => 'Wählen Sie den Raum in dem der Mitarbeiter sitzt',
			'tx_civserv_wizard_employee_position_room.select_letter_text' => 'Wählen Sie den Anfangsbuchstaben des Gebäudes',
            'tx_civserv_wizard_employee_position_room.select_building_text' => 'Wählen Sie ein Gebäude',
            'tx_civserv_wizard_employee_position_room.select_floor_text' => 'Wählen Sie eine Etage',
			'tx_civserv_wizard_employee_position_room.select_room_text' => 'Wählen Sie den Raum',
            'tx_civserv_wizard_employee_position_room.building_dummy' => 'Select a building',
			'tx_civserv_wizard_employee_position_room.floor_dummy' => 'Select a floor',
			'tx_civserv_wizard_employee_position_room.room_dummy' => 'Select a room',
            'tx_civserv_wizard_employee_position_room.OK_Button' => 'OK',
            'tx_civserv_wizard_employee_position_room.Cancel_Button' => 'Abbrechen',
            'tx_civserv_wizard_employee_position_room.warning_msg_1' => 'Bitte wählen Sie einen Raum aus!',
            'tx_civserv_wizard_employee_position_room.warning_msg_2' => 'FEHLER - Referenz auf das Hauptfenster nicht richtig gesetzt!',
 ),  
);
?>