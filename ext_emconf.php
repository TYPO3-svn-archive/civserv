<?php

########################################################################
# Extension Manager/Repository config file for ext: "civserv"
# 
# Auto generated 04-05-2006 17:01
# 
# Manual updates:
# Only the data in the array - anything else is removed by next write
########################################################################

$EM_CONF[$_EXTKEY] = Array (
	'title' => 'Virtual Civil Services',
	'description' => 'Offering all public services available in the city hall to the citizins via internet. For further information and documentation please see http://www.regio-komm.de (yet only available in german language).',
	'category' => 'plugin',
	'shy' => 1,
	'version' => '4.4.1',	// Don't modify this! Managed automatically during upload to repository.
	'dependencies' => 'smarty',
	'conflicts' => '',
	'priority' => '',
	'loadOrder' => '',
	'TYPO3_version' => '',
	'PHP_version' => '',
	'module' => 'modmsworkflow,modcacheservices,res',
	'state' => 'stable',
	'uploadfolder' => 1,
	'createDirs' => '',
	'modify_tables' => '',
	'clearcacheonload' => 1,
	'lockType' => 'L',
	'author' => 'Projektseminar ProService, WWU',
	'author_email' => 'osiris@citeq.de',
	'author_company' => 'citeq, University Münster, Kreis Warendorf',
	'CGLcompliance' => '',
	'CGLcompliance_note' => '',
	'_md5_values_when_last_written' => 'a:165:{s:11:"civserv.txt";s:4:"6fe2";s:12:"ext_icon.gif";s:4:"c245";s:17:"ext_localconf.php";s:4:"ca8b";s:15:"ext_php_api.dat";s:4:"da36";s:14:"ext_tables.php";s:4:"b5fe";s:14:"ext_tables.sql";s:4:"f986";s:35:"ext_tables_static+adt.sql.ATTENTION";s:4:"56c2";s:28:"ext_typoscript_constants.txt";s:4:"545a";s:24:"ext_typoscript_setup.txt";s:4:"5757";s:28:"icon_tx_civserv_building.gif";s:4:"c739";s:28:"icon_tx_civserv_category.gif";s:4:"ce57";s:28:"icon_tx_civserv_employee.gif";s:4:"7238";s:40:"icon_tx_civserv_employee_em_position.gif";s:4:"448f";s:43:"icon_tx_civserv_employee_em_position_mm.gif";s:4:"1299";s:36:"icon_tx_civserv_external_service.gif";s:4:"f287";s:25:"icon_tx_civserv_floor.gif";s:4:"472a";s:24:"icon_tx_civserv_form.gif";s:4:"dff8";s:27:"icon_tx_civserv_mandant.gif";s:4:"477d";s:33:"icon_tx_civserv_model_service.gif";s:4:"db39";s:38:"icon_tx_civserv_model_service_temp.gif";s:4:"1934";s:30:"icon_tx_civserv_navigation.gif";s:4:"14ec";s:31:"icon_tx_civserv_officehours.gif";s:4:"785e";s:32:"icon_tx_civserv_organisation.gif";s:4:"5d4d";s:28:"icon_tx_civserv_position.gif";s:4:"1801";s:26:"icon_tx_civserv_region.gif";s:4:"e420";s:24:"icon_tx_civserv_room.gif";s:4:"cca0";s:31:"icon_tx_civserv_search_word.gif";s:4:"b6a6";s:27:"icon_tx_civserv_service.gif";s:4:"8762";s:42:"icon_tx_civserv_service_sv_position_mm.gif";s:4:"5220";s:24:"list_employee_framed.gif";s:4:"08f5";s:28:"list_organisation_framed.gif";s:4:"8357";s:24:"list_position_framed.gif";s:4:"05aa";s:16:"locallang_db.php";s:4:"c789";s:7:"tca.php";s:4:"bea6";s:30:"CSH/locallang_csh_building.php";s:4:"f608";s:30:"CSH/locallang_csh_employee.php";s:4:"3444";s:45:"CSH/locallang_csh_employee_em_position_mm.php";s:4:"35e7";s:27:"CSH/locallang_csh_floor.php";s:4:"0db0";s:26:"CSH/locallang_csh_form.php";s:4:"7c7b";s:35:"CSH/locallang_csh_model_service.php";s:4:"1674";s:32:"CSH/locallang_csh_navigation.php";s:4:"287b";s:34:"CSH/locallang_csh_organisation.php";s:4:"e0c7";s:33:"CSH/locallang_csh_search_word.php";s:4:"002a";s:29:"CSH/locallang_csh_service.php";s:4:"b412";s:44:"CSH/locallang_csh_service_sv_position_mm.php";s:4:"4b0b";s:43:"doc/ERM - Datenmodell - Implementierung.VSD";s:4:"f532";s:14:"doc/README.txt";s:4:"af8e";s:13:"doc/Setup.pdf";s:4:"5a1b";s:17:"doc/changelog.txt";s:4:"d85b";s:40:"doc/mandantenbaum_inkl_basis_objekte.t3d";s:4:"3ad3";s:14:"doc/manual.sxw";s:4:"9992";s:30:"doc/osiris_bilingual_howto.txt";s:4:"e2c9";s:30:"doc/seitenbaum_startseiten.t3d";s:4:"04ac";s:38:"doc/stammbaum_virtuelle_verwaltung.t3d";s:4:"2318";s:34:"pi1/class.tx_civserv_accesslog.php";s:4:"d6ab";s:28:"pi1/class.tx_civserv_pi1.php";s:4:"7e58";s:17:"pi1/locallang.php";s:4:"79e0";s:20:"pi1/static/setup.txt";s:4:"ad30";s:31:"res/class.tx_civserv_commit.php";s:4:"49ef";s:35:"res/class.tx_civserv_floorbuild.php";s:4:"b320";s:32:"res/class.tx_civserv_mandant.php";s:4:"bf62";s:39:"res/class.tx_civserv_ms_maintenance.php";s:4:"4374";s:34:"res/class.tx_civserv_oepupdate.php";s:4:"482c";s:44:"res/class.tx_civserv_service_maintenance.php";s:4:"f09f";s:44:"res/class.tx_civserv_weekday_maintenance.php";s:4:"52e9";s:52:"res/class.tx_civserv_wizard_employee_em_position.php";s:4:"2e66";s:54:"res/class.tx_civserv_wizard_employee_position_room.php";s:4:"dfe7";s:44:"res/class.tx_civserv_wizard_modelservice.php";s:4:"32c1";s:55:"res/class.tx_civserv_wizard_organisation_supervisor.php";s:4:"32bd";s:44:"res/class.tx_civserv_wizard_service_form.php";s:4:"1fe2";s:52:"res/class.tx_civserv_wizard_service_organisation.php";s:4:"9b75";s:48:"res/class.tx_civserv_wizard_service_position.php";s:4:"0973";s:56:"res/class.tx_civserv_wizard_service_position_em_name.php";s:4:"c216";s:60:"res/class.tx_civserv_wizard_service_position_information.php";s:4:"bd5c";s:72:"res/class.tx_civserv_wizard_service_position_limited_sv_organisation.php";s:4:"a5de";s:50:"res/class.tx_civserv_wizard_service_searchword.php";s:4:"441c";s:56:"res/class.tx_civserv_wizard_service_similar_services.php";s:4:"3459";s:12:"res/conf.php";s:4:"40aa";s:24:"res/locallang_wizard.php";s:4:"1617";s:25:"res/stylesheet_wizard.css";s:4:"f992";s:60:"modcacheservices/class.tx_civserv_modcacheservices_cache.php";s:4:"4153";s:26:"modcacheservices/clear.gif";s:4:"cc11";s:25:"modcacheservices/conf.php";s:4:"6544";s:26:"modcacheservices/index.php";s:4:"f212";s:30:"modcacheservices/locallang.php";s:4:"4174";s:34:"modcacheservices/locallang_mod.php";s:4:"e8cf";s:31:"modcacheservices/moduleicon.gif";s:4:"6b2e";s:23:"modmsworkflow/clear.gif";s:4:"cc11";s:24:"modmsworkflow/commit.gif";s:4:"d103";s:22:"modmsworkflow/conf.php";s:4:"37f4";s:23:"modmsworkflow/index.php";s:4:"7f49";s:27:"modmsworkflow/locallang.php";s:4:"0735";s:31:"modmsworkflow/locallang_mod.php";s:4:"07b4";s:28:"modmsworkflow/moduleicon.gif";s:4:"2e73";s:24:"modmsworkflow/revise.gif";s:4:"33ec";s:22:"modmsworkflow/view.gif";s:4:"0c38";s:20:"templates/.cvsignore";s:4:"afcf";s:36:"templates/circumstance_tree.tpl.html";s:4:"5c02";s:35:"templates/community_choice.tpl.html";s:4:"b537";s:31:"templates/contact_form.tpl.html";s:4:"a308";s:38:"templates/debit_authorisation.tpl.html";s:4:"20cc";s:29:"templates/email_form.tpl.html";s:4:"534a";s:27:"templates/employee.tpl.html";s:4:"faaf";s:32:"templates/employee_list.tpl.html";s:4:"d982";s:29:"templates/error_page.tpl.html";s:4:"b292";s:28:"templates/form_list.tpl.html";s:4:"e44b";s:20:"templates/index.html";s:4:"3a05";s:28:"templates/index_english.html";s:4:"543b";s:31:"templates/legal_notice.tpl.html";s:4:"8812";s:31:"templates/organisation.tpl.html";s:4:"a085";s:36:"templates/organisation_list.tpl.html";s:4:"df63";s:36:"templates/organisation_tree.tpl.html";s:4:"0a58";s:34:"templates/right_searchbox.tpl.html";s:4:"b44a";s:28:"templates/right_top.tpl.html";s:4:"e576";s:32:"templates/search_result.tpl.html";s:4:"c367";s:26:"templates/service.tpl.html";s:4:"faca";s:31:"templates/service_list.tpl.html";s:4:"01ad";s:24:"templates/top15.tpl.html";s:4:"561a";s:33:"templates/usergroup_tree.tpl.html";s:4:"0985";s:24:"templates/css/screen.css";s:4:"4834";s:26:"templates/images/citeq.gif";s:4:"ec19";s:26:"templates/images/ercis.gif";s:4:"66f3";s:34:"templates/images/externer_link.gif";s:4:"0c15";s:33:"templates/images/headermotive.jpg";s:4:"7788";s:35:"templates/images/kreiswarendorf.gif";s:4:"ae8b";s:31:"templates/images/livingpage.gif";s:4:"14b8";s:27:"templates/images/osiris.jpg";s:4:"bcf2";s:34:"templates/images/valid-xhtml10.png";s:4:"a6dc";s:25:"templates/images/vcss.gif";s:4:"64c1";s:23:"templates/images/wi.jpg";s:4:"a0d2";s:29:"templates/images/bgs/body.gif";s:4:"ddd7";s:26:"templates/images/bgs/d.gif";s:4:"a6d9";s:31:"templates/images/bgs/footer.gif";s:4:"3a8a";s:27:"templates/images/bgs/gb.gif";s:4:"4317";s:42:"templates/images/bgs/globalenavigation.gif";s:4:"e400";s:47:"templates/images/bgs/gobalenavigation_right.gif";s:4:"fa11";s:34:"templates/images/menu/leftmenu.gif";s:4:"4124";s:41:"templates/images/menu/leftmenu_active.gif";s:4:"7c19";s:49:"templates/images/menu/rightmenu/cantactperson.gif";s:4:"3a86";s:52:"templates/images/menu/rightmenu/cantactperson_f2.gif";s:4:"c74a";s:40:"templates/images/menu/rightmenu/fees.gif";s:4:"1227";s:43:"templates/images/menu/rightmenu/fees_f2.gif";s:4:"c7e2";s:41:"templates/images/menu/rightmenu/forms.gif";s:4:"fef5";s:44:"templates/images/menu/rightmenu/forms_f2.gif";s:4:"ca89";s:42:"templates/images/menu/rightmenu/legals.gif";s:4:"c337";s:45:"templates/images/menu/rightmenu/legals_f2.gif";s:4:"a775";s:54:"templates/images/menu/rightmenu/necessarydocuments.gif";s:4:"0b31";s:57:"templates/images/menu/rightmenu/necessarydocuments_f2.gif";s:4:"49b7";s:33:"templates/images/icons/anchor.gif";s:4:"4b82";s:31:"templates/images/icons/back.gif";s:4:"b6ec";s:34:"templates/images/icons/back_f2.gif";s:4:"891d";s:35:"templates/images/icons/bookmark.gif";s:4:"a674";s:38:"templates/images/icons/bookmark_f2.gif";s:4:"8e42";s:32:"templates/images/icons/email.gif";s:4:"dd10";s:31:"templates/images/icons/file.gif";s:4:"b367";s:31:"templates/images/icons/home.gif";s:4:"e256";s:34:"templates/images/icons/home_f2.gif";s:4:"848d";s:31:"templates/images/icons/link.gif";s:4:"1bb2";s:36:"templates/images/icons/newwindow.gif";s:4:"d623";s:30:"templates/images/icons/pdf.gif";s:4:"943f";s:32:"templates/images/icons/print.gif";s:4:"5a3c";s:39:"templates/images/icons/printversion.gif";s:4:"eba1";s:42:"templates/images/icons/printversion_f2.gif";s:4:"fb3d";s:32:"templates/images/icons/start.gif";s:4:"339b";s:36:"templates/images/icons/topofpage.gif";s:4:"f46e";}',
);

?>