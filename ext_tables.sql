#
# Table structure for table 'tx_civserv_transaction_debit_authorisation'
# 
#
CREATE TABLE tx_civserv_transaction_debit_authorisation (
	uid int(11) unsigned DEFAULT '0' NOT NULL auto_increment,
	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	service_uid int(11) unsigned DEFAULT '0' NOT NULL,
	cash_number int(8) unsigned DEFAULT '0' NOT NULL,
	bank_name tinytext NOT NULL,
	bank_code int(9) unsigned DEFAULT '0' NOT NULL,
	account_number int(9) unsigned DEFAULT '0' NOT NULL,
	firstname tinytext NOT NULL,
	surname tinytext NOT NULL,
	phone tinytext,
	email tinytext,
	remote_addr tinytext,
	PRIMARY KEY (uid)
);

#
# Table structure for table 'tx_civserv_conf_transaction'
# 
#
CREATE TABLE tx_civserv_conf_transaction (
  ct_community_id int(11) unsigned DEFAULT '0' NOT NULL,
  ct_transaction_key tinytext NOT NULL,
  ct_transaction_uid int(11) unsigned DEFAULT '0' NOT NULL
);

#
# Table structure for table 'tx_civserv_conf_mandant_cm_region_mm'
# 
#
CREATE TABLE tx_civserv_conf_mandant_cm_region_mm (
  uid_local int(11) unsigned DEFAULT '0' NOT NULL,
  uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
  tablenames varchar(30) DEFAULT '' NOT NULL,
  sorting int(11) unsigned DEFAULT '0' NOT NULL,
  KEY uid_local (uid_local),
  KEY uid_foreign (uid_foreign)
);

#
# Table structure for table 'tx_civserv_external_service'
# 
CREATE TABLE tx_civserv_external_service (
	uid int(11) unsigned DEFAULT '0' NOT NULL auto_increment,
	pid int(11) unsigned DEFAULT '0' NOT NULL,
	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	fe_group int(11) DEFAULT '0' NOT NULL,
	es_external_service int(11) unsigned DEFAULT '0' NOT NULL,
	es_name tinytext NOT NULL,
	es_navigation int(11) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid)
);

#
# Table structure for table 'tx_civserv_region'
# 
CREATE TABLE tx_civserv_region (
	uid int(11) unsigned DEFAULT '0' NOT NULL auto_increment,
	pid int(11) unsigned DEFAULT '0' NOT NULL,
	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	fe_group int(11) DEFAULT '0' NOT NULL,
	re_name tinytext NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid)
);

#
# Table structure for table 'tx_civserv_service_sv_region_mm'
# 
#
CREATE TABLE tx_civserv_service_sv_region_mm (
  uid_local int(11) unsigned DEFAULT '0' NOT NULL,
  uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
  tablenames varchar(30) DEFAULT '' NOT NULL,
  sorting int(11) unsigned DEFAULT '0' NOT NULL,
  KEY uid_local (uid_local),
  KEY uid_foreign (uid_foreign)
);


#
# Table structure for table 'tx_civserv_service_sv_similar_services_mm'
# 
#
CREATE TABLE tx_civserv_service_sv_similar_services_mm (
  uid_local int(11) unsigned DEFAULT '0' NOT NULL,
  uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
  tablenames varchar(30) DEFAULT '' NOT NULL,
  sorting int(11) unsigned DEFAULT '0' NOT NULL,
  KEY uid_local (uid_local),
  KEY uid_foreign (uid_foreign)
);



#
# Table structure for table 'tx_civserv_service_sv_form_mm'
# 
#
CREATE TABLE tx_civserv_service_sv_form_mm (
  uid_local int(11) unsigned DEFAULT '0' NOT NULL,
  uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
  tablenames varchar(30) DEFAULT '' NOT NULL,
  sorting int(11) unsigned DEFAULT '0' NOT NULL,
  KEY uid_local (uid_local),
  KEY uid_foreign (uid_foreign)
);




#
# Table structure for table 'tx_civserv_service_sv_searchword_mm'
# 
#
CREATE TABLE tx_civserv_service_sv_searchword_mm (
  uid_local int(11) unsigned DEFAULT '0' NOT NULL,
  uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
  tablenames varchar(30) DEFAULT '' NOT NULL,
  sorting int(11) unsigned DEFAULT '0' NOT NULL,
  KEY uid_local (uid_local),
  KEY uid_foreign (uid_foreign)
);




#
# Table structure for table 'tx_civserv_service_sv_position_mm'
# 
#
CREATE TABLE tx_civserv_service_sv_position_mm (
	uid int(11) unsigned DEFAULT '0' NOT NULL auto_increment,
	pid int(11) unsigned DEFAULT '0' NOT NULL,
	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	fe_group int(11) DEFAULT '0' NOT NULL,
    uid_local int(11) unsigned DEFAULT '0' NOT NULL,
    uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
    tablenames varchar(30) DEFAULT '' NOT NULL,
    sorting int(11) unsigned DEFAULT '0' NOT NULL,
	sp_descr text NOT NULL,
	sp_label tinytext NOT NULL,
	uid_temp int(11) unsigned DEFAULT '0' NOT NULL,
	
	PRIMARY KEY (uid),
	KEY uid_local (uid_local),
    KEY uid_foreign (uid_foreign)
);




#
# Table structure for table 'tx_civserv_service_sv_organisation_mm'
# 
#
CREATE TABLE tx_civserv_service_sv_organisation_mm (
  uid_local int(11) unsigned DEFAULT '0' NOT NULL,
  uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
  tablenames varchar(30) DEFAULT '' NOT NULL,
  sorting int(11) unsigned DEFAULT '0' NOT NULL,
  KEY uid_local (uid_local),
  KEY uid_foreign (uid_foreign)
);




#
# Table structure for table 'tx_civserv_service_sv_navigation_mm'
# 
#
CREATE TABLE tx_civserv_service_sv_navigation_mm (
  uid_local int(11) unsigned DEFAULT '0' NOT NULL,
  uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
  tablenames varchar(30) DEFAULT '' NOT NULL,
  sorting int(11) unsigned DEFAULT '0' NOT NULL,
  KEY uid_local (uid_local),
  KEY uid_foreign (uid_foreign)
);



#
# Table structure for table 'tx_civserv_ext_service_esv_navigation_mm'
# 
#
CREATE TABLE tx_civserv_ext_service_esv_navigation_mm (
  uid_local int(11) unsigned DEFAULT '0' NOT NULL,
  uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
  tablenames varchar(30) DEFAULT '' NOT NULL,
  sorting int(11) unsigned DEFAULT '0' NOT NULL,
  KEY uid_local (uid_local),
  KEY uid_foreign (uid_foreign)
);


#
# Table structure for table 'tx_civserv_service'
#
CREATE TABLE tx_civserv_service (
	uid int(11) unsigned DEFAULT '0' NOT NULL auto_increment,
	pid int(11) unsigned DEFAULT '0' NOT NULL,
	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	starttime int(11) unsigned DEFAULT '0' NOT NULL,
	endtime int(11) unsigned DEFAULT '0' NOT NULL,
	fe_group int(11) DEFAULT '0' NOT NULL,
	sv_name tinytext NOT NULL,
	sv_synonym1 tinytext NOT NULL,
	sv_synonym2 tinytext NOT NULL,
	sv_synonym3 tinytext NOT NULL,
	sv_descr_short text NOT NULL,
	sv_descr_long text NOT NULL,
	sv_image blob NOT NULL,
	sv_image_text tinytext NOT NULL,
	sv_fees text NOT NULL,
	sv_documents text NOT NULL,
	sv_legal_local text NOT NULL,
	sv_legal_global text NOT NULL,
	sv_model_service int(11) unsigned DEFAULT '0' NOT NULL,
	sv_similar_services int(11) unsigned DEFAULT '0' NOT NULL,
	sv_form int(11) unsigned DEFAULT '0' NOT NULL,
	sv_searchword int(11) unsigned DEFAULT '0' NOT NULL,
	sv_position int(11) unsigned DEFAULT '0' NOT NULL,
	sv_organisation int(11) unsigned DEFAULT '0' NOT NULL,
	sv_navigation int(11) unsigned DEFAULT '0' NOT NULL,
	sv_region_checkbox int(11) unsigned DEFAULT '0' NOT NULL,
	sv_region_link tinytext NOT NULL,
	sv_region_name tinytext NOT NULL,
	sv_region int(11) unsigned DEFAULT '0' NOT NULL,
	PRIMARY KEY (uid),
	KEY parent (pid)
);


#
# Table structure for table 'tx_civserv_model_service'
# ms_searchword int(11) unsigned DEFAULT '0' NOT NULL,
CREATE TABLE tx_civserv_model_service (
	uid int(11) unsigned DEFAULT '0' NOT NULL auto_increment,
	pid int(11) unsigned DEFAULT '0' NOT NULL,
	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	fe_group int(11) DEFAULT '0' NOT NULL,
	ms_name tinytext NOT NULL,
	ms_stored_name tinytext NOT NULL,
	ms_synonym1 tinytext NOT NULL,
	ms_synonym2 tinytext NOT NULL,
	ms_synonym3 tinytext NOT NULL,
	ms_descr_short text NOT NULL,
	ms_descr_long text NOT NULL,
	ms_image blob NOT NULL,
	ms_image_text tinytext NOT NULL,
	ms_fees text NOT NULL,
	ms_documents text NOT NULL,
	ms_legal_global text NOT NULL,
	ms_searchword text NOT NULL,
	ms_mandant int(11) unsigned DEFAULT '0' NOT NULL,
	ms_approver_one int(11) unsigned DEFAULT '0' NOT NULL,
	ms_approver_two int(11) unsigned DEFAULT '0' NOT NULL,
	

	
	PRIMARY KEY (uid),
	KEY parent (pid)
);



#
# Table structure for table 'tx_civserv_form'
#
CREATE TABLE tx_civserv_form (
	uid int(11) unsigned DEFAULT '0' NOT NULL auto_increment,
	pid int(11) unsigned DEFAULT '0' NOT NULL,
	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	starttime int(11) unsigned DEFAULT '0' NOT NULL,
	endtime int(11) unsigned DEFAULT '0' NOT NULL,
	fe_group int(11) DEFAULT '0' NOT NULL,
	fo_number tinytext NOT NULL,
	fo_name tinytext NOT NULL,
	fo_descr text NOT NULL,
	fo_external_checkbox int(11) unsigned DEFAULT '0' NOT NULL,
	fo_url tinytext NOT NULL,
	fo_formular_file blob NOT NULL,
	fo_created_date int(11) DEFAULT '0' NOT NULL,
	fo_status int(11) unsigned DEFAULT '0' NOT NULL,
	
	PRIMARY KEY (uid),
	KEY parent (pid)
);



#
# Table structure for table 'tx_civserv_building_bl_floor_mm'
#
CREATE TABLE tx_civserv_building_bl_floor_mm (
	uid int(11) unsigned DEFAULT '0' NOT NULL auto_increment,
	pid int(11) unsigned DEFAULT '0' NOT NULL,
	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	fe_group int(11) DEFAULT '0' NOT NULL,
	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	tablenames varchar(30) DEFAULT '' NOT NULL,
	uid_temp int(11) unsigned DEFAULT '0' NOT NULL,
	
	PRIMARY KEY (uid),
	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);



#
# Table structure for table 'tx_civserv_building'
#
CREATE TABLE tx_civserv_building (
	uid int(11) unsigned DEFAULT '0' NOT NULL auto_increment,
	pid int(11) unsigned DEFAULT '0' NOT NULL,
	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	fe_group int(11) DEFAULT '0' NOT NULL,
	bl_number tinytext NOT NULL,
	bl_name tinytext NOT NULL,
	bl_descr text NOT NULL,
	bl_mail_street tinytext NOT NULL,
	bl_mail_pob tinytext NOT NULL,
	bl_mail_postcode tinytext NOT NULL,
	bl_mail_city tinytext NOT NULL,
	bl_building_street tinytext NOT NULL,
	bl_building_postcode tinytext NOT NULL,
	bl_building_city tinytext NOT NULL,
	bl_pubtrans_stop tinytext NOT NULL,
	bl_pubtrans_url tinytext NOT NULL,
	bl_image blob NOT NULL,
	bl_telephone tinytext NOT NULL,
	bl_fax tinytext NOT NULL,
	bl_email tinytext NOT NULL,
	bl_floor int(11) unsigned DEFAULT '0' NOT NULL,
	bl_exid tinytext NOT NULL,
	
	PRIMARY KEY (uid),
	KEY parent (pid)
);



#
# Table structure for table 'tx_civserv_room'
#
CREATE TABLE tx_civserv_room (
	uid int(11) unsigned DEFAULT '0' NOT NULL auto_increment,
	pid int(11) unsigned DEFAULT '0' NOT NULL,
	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	fe_group int(11) DEFAULT '0' NOT NULL,
	ro_number tinytext NOT NULL,
	ro_name tinytext NOT NULL,
	ro_descr text NOT NULL,
	ro_telephone tinytext NOT NULL,
	ro_fax tinytext NOT NULL,
	rbf_building_bl_floor int(11) unsigned DEFAULT '0' NOT NULL,
	ro_exid tinytext NOT NULL,
	
	PRIMARY KEY (uid),
	KEY parent (pid)
);



#
# Table structure for table 'tx_civserv_floor'
#
CREATE TABLE tx_civserv_floor (
	uid int(11) unsigned DEFAULT '0' NOT NULL auto_increment,
	pid int(11) unsigned DEFAULT '0' NOT NULL,
	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	fe_group int(11) DEFAULT '0' NOT NULL,
	fl_number tinytext NOT NULL,
	fl_descr tinytext NOT NULL,
	
	PRIMARY KEY (uid),
	KEY parent (pid)
);




#
# Table structure for table 'tx_civserv_employee_em_hours_mm'
# 
#
CREATE TABLE tx_civserv_employee_em_hours_mm (
  uid_local int(11) unsigned DEFAULT '0' NOT NULL,
  uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
  tablenames varchar(30) DEFAULT '' NOT NULL,
  sorting int(11) unsigned DEFAULT '0' NOT NULL,
  KEY uid_local (uid_local),
  KEY uid_foreign (uid_foreign)
);




#
# Table structure for table 'tx_civserv_employee_em_position_mm'
#
# Part II (Content type)
#
CREATE TABLE tx_civserv_employee_em_position_mm (
	uid int(11) unsigned DEFAULT '0' NOT NULL auto_increment,
	pid int(11) unsigned DEFAULT '0' NOT NULL,
	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	fe_group int(11) DEFAULT '0' NOT NULL,
	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,
	tablenames varchar(30) DEFAULT '' NOT NULL,
	ep_officehours int(11) unsigned DEFAULT '0' NOT NULL,
	ep_room int(11) unsigned DEFAULT '0' NOT NULL,
	ep_telephone tinytext NOT NULL,
	ep_fax tinytext NOT NULL,
	ep_mobile tinytext NOT NULL,
	ep_email tinytext NOT NULL,
	ep_label tinytext NOT NULL,
	uid_temp int(11) unsigned DEFAULT '0' NOT NULL,
	
	PRIMARY KEY (uid),
	KEY uid_local (uid_local),
    KEY uid_foreign (uid_foreign)
);



#
# Table structure for table 'tx_civserv_employee'
#
CREATE TABLE tx_civserv_employee (
	uid int(11) unsigned DEFAULT '0' NOT NULL auto_increment,
	pid int(11) unsigned DEFAULT '0' NOT NULL,
	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	fe_group int(11) DEFAULT '0' NOT NULL,
	em_number tinytext NOT NULL,
	em_address int(11) unsigned DEFAULT '0' NOT NULL,
	em_title tinytext NOT NULL,
	em_name tinytext NOT NULL,
	em_firstname tinytext NOT NULL,
	em_telephone tinytext NOT NULL,
	em_fax tinytext NOT NULL,
	em_mobile tinytext NOT NULL,
	em_email tinytext NOT NULL,
	em_image blob NOT NULL,
	em_datasec tinyint(3) unsigned DEFAULT '0' NOT NULL,
	em_hours int(11) unsigned DEFAULT '0' NOT NULL,
	em_position int(11) unsigned DEFAULT '0' NOT NULL,
	em_exid tinytext NOT NULL,
	
	PRIMARY KEY (uid),
	KEY parent (pid)
);




#
# Table structure for table 'tx_civserv_organisation_or_hours_mm'
# 
#
CREATE TABLE tx_civserv_organisation_or_hours_mm (
  uid_local int(11) unsigned DEFAULT '0' NOT NULL,
  uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
  tablenames varchar(30) DEFAULT '' NOT NULL,
  sorting int(11) unsigned DEFAULT '0' NOT NULL,
  KEY uid_local (uid_local),
  KEY uid_foreign (uid_foreign)
);




#
# Table structure for table 'tx_civserv_organisation_or_structure_mm'
# 
#
CREATE TABLE tx_civserv_organisation_or_structure_mm (
  uid_local int(11) unsigned DEFAULT '0' NOT NULL,
  uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
  tablenames varchar(30) DEFAULT '' NOT NULL,
  sorting int(11) unsigned DEFAULT '0' NOT NULL,
  KEY uid_local (uid_local),
  KEY uid_foreign (uid_foreign)
);




#
# Table structure for table 'tx_civserv_organisation_or_building_mm'
# 
#
CREATE TABLE tx_civserv_organisation_or_building_mm (
  uid_local int(11) unsigned DEFAULT '0' NOT NULL,
  uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
  tablenames varchar(30) DEFAULT '' NOT NULL,
  sorting int(11) unsigned DEFAULT '0' NOT NULL,
  KEY uid_local (uid_local),
  KEY uid_foreign (uid_foreign)
);



#
# Table structure for table 'tx_civserv_organisation'
#
CREATE TABLE tx_civserv_organisation (
	uid int(11) unsigned DEFAULT '0' NOT NULL auto_increment,
	pid int(11) unsigned DEFAULT '0' NOT NULL,
	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	sorting int(10) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	fe_group int(11) DEFAULT '0' NOT NULL,
	or_number tinytext NOT NULL,
	or_code tinytext NOT NULL,
	or_name tinytext NOT NULL,
	or_synonym1 tinytext NOT NULL,
	or_synonym2 tinytext NOT NULL,
	or_synonym3 tinytext NOT NULL,	
	or_supervisor int(11) unsigned DEFAULT '0' NOT NULL,
	or_hours int(11) unsigned DEFAULT '0' NOT NULL,
	or_telephone tinytext NOT NULL,
	or_fax tinytext NOT NULL,
	or_email tinytext NOT NULL,
	or_image blob NOT NULL,
	or_infopage tinytext NOT NULL,
	or_addinfo text NOT NULL,
	or_structure int(11) unsigned DEFAULT '0' NOT NULL,
	or_building int(11) unsigned DEFAULT '0' NOT NULL,
	or_exid tinytext NOT NULL,
	
	PRIMARY KEY (uid),
	KEY parent (pid)
);



#
# Table structure for table 'tx_civserv_officehours'
#
CREATE TABLE tx_civserv_officehours (
	uid int(11) unsigned DEFAULT '0' NOT NULL auto_increment,
	pid int(11) unsigned DEFAULT '0' NOT NULL,
	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	fe_group int(11) DEFAULT '0' NOT NULL,
	oh_descr tinytext NOT NULL,
	oh_name tinytext NOT NULL,
	oh_start_morning varchar(30) DEFAULT '0' NOT NULL,
	oh_end_morning varchar(30) DEFAULT '0' NOT NULL,
	oh_start_afternoon varchar(30) DEFAULT '0' NOT NULL,
	oh_end_afternoon varchar(30) DEFAULT '0' NOT NULL,
	oh_weekday int(11) DEFAULT '0' NOT NULL,
	oh_manual_checkbox int(11) unsigned DEFAULT '0' NOT NULL,
	oh_freestyle varchar(255) DEFAULT '' NOT NULL,
	
	PRIMARY KEY (uid),
	KEY parent (pid)
);


#
# Table structure for table 'tx_civserv_search_word'
#
CREATE TABLE tx_civserv_search_word (
	uid int(11) unsigned DEFAULT '0' NOT NULL auto_increment,
	pid int(11) unsigned DEFAULT '0' NOT NULL,
	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	fe_group int(11) DEFAULT '0' NOT NULL,
	sw_search_word tinytext NOT NULL,
	
	PRIMARY KEY (uid),
	KEY parent (pid)
);




#
# Table structure for table 'tx_civserv_position_po_organisation_mm'
# 
#
CREATE TABLE tx_civserv_position_po_organisation_mm (
  uid_local int(11) unsigned DEFAULT '0' NOT NULL,
  uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
  tablenames varchar(30) DEFAULT '' NOT NULL,
  sorting int(11) unsigned DEFAULT '0' NOT NULL,
  KEY uid_local (uid_local),
  KEY uid_foreign (uid_foreign)
);



#
# Table structure for table 'tx_civserv_position'
#
CREATE TABLE tx_civserv_position (
	uid int(11) unsigned DEFAULT '0' NOT NULL auto_increment,
	pid int(11) unsigned DEFAULT '0' NOT NULL,
	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	fe_group int(11) DEFAULT '0' NOT NULL,
	po_name tinytext NOT NULL,
	po_number tinytext NOT NULL,
	po_descr text NOT NULL,
	po_organisation int(11) unsigned DEFAULT '0' NOT NULL,
	po_exid tinytext NOT NULL,
	
	PRIMARY KEY (uid),
	KEY parent (pid)
);




#
# Table structure for table 'tx_civserv_navigation_nv_structure_mm'
# 
#
CREATE TABLE tx_civserv_navigation_nv_structure_mm (
  uid_local int(11) unsigned DEFAULT '0' NOT NULL,
  uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
  tablenames varchar(30) DEFAULT '' NOT NULL,
  sorting int(11) unsigned DEFAULT '0' NOT NULL,
  KEY uid_local (uid_local),
  KEY uid_foreign (uid_foreign)
);



#
# Table structure for table 'tx_civserv_navigation'
#
CREATE TABLE tx_civserv_navigation (
	uid int(11) unsigned DEFAULT '0' NOT NULL auto_increment,
	pid int(11) unsigned DEFAULT '0' NOT NULL,
	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	fe_group int(11) DEFAULT '0' NOT NULL,
	nv_name tinytext NOT NULL,
	nv_structure int(11) unsigned DEFAULT '0' NOT NULL,
	
	PRIMARY KEY (uid),
	KEY parent (pid)
);


#
# Table structure for table 'tx_civserv_officehours_oep_employee_em_position_mm_mm'
# 
#
CREATE TABLE tx_civserv_officehours_oep_employee_em_position_mm_mm (
  uid_local int(11) unsigned DEFAULT '0' NOT NULL,
  uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
  tablenames varchar(30) DEFAULT '' NOT NULL,
  sorting int(11) unsigned DEFAULT '0' NOT NULL,
  KEY uid_local (uid_local),
  KEY uid_foreign (uid_foreign)
);


#
# Table structure for table  'tx_civserv_conf_mandant'
#
CREATE TABLE tx_civserv_conf_mandant (
	uid int(11) unsigned DEFAULT '0' NOT NULL auto_increment,
	pid int(11) unsigned DEFAULT '0' NOT NULL,
	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	fe_group int(11) DEFAULT '0' NOT NULL,
    cm_community_name tinytext NOT NULL,
    cm_community_id int(11) unsigned DEFAULT '0' NOT NULL,
    cm_uid int(11) unsigned DEFAULT '0' NOT NULL,
    cm_circumstance_uid int(11) unsigned DEFAULT '0' NOT NULL,
    cm_usergroup_uid int(11) unsigned DEFAULT '0' NOT NULL,
    cm_organisation_uid int(11) unsigned DEFAULT '0' NOT NULL,
    cm_service_folder_uid int(11) unsigned DEFAULT '0' NOT NULL,
    cm_model_service_temp_uid int(11) unsigned DEFAULT '0' NOT NULL,
    cm_external_service_folder_uid int(11) unsigned DEFAULT '0' NOT NULL,
    cm_page_uid int(11) unsigned DEFAULT '0' NOT NULL,
    cm_search_uid int(11) unsigned DEFAULT '0' NOT NULL,
    cm_community_type int(11) unsigned DEFAULT '0' NOT NULL,
    cm_target_email tinytext NOT NULL,
	cm_employeesearch tinyint(3) unsigned DEFAULT '0' NOT NULL,
    
    PRIMARY KEY (uid)
);

#
# Table structure for table  'tx_civserv_configuration'
#
CREATE TABLE tx_civserv_configuration (
  cf_module tinytext NOT NULL,
  cf_key tinytext NOT NULL,
  cf_value tinytext NOT NULL
);

#
# Table structure for table 'tx_civserv_accesslog'
#
CREATE TABLE tx_civserv_accesslog (
	uid int(11) unsigned DEFAULT '0' NOT NULL auto_increment,
	al_service_uid int(11) unsigned DEFAULT '0' NOT NULL,
	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	al_number int(11) DEFAULT '0' NOT NULL,
	remote_addr tinytext,
	PRIMARY KEY (uid)
);

#
# Table structure for table 'tx_civserv_model_service_temp'
# ms_searchword int(11) unsigned DEFAULT '0' NOT NULL,
CREATE TABLE tx_civserv_model_service_temp (
	uid int(11) unsigned DEFAULT '0' NOT NULL,
	pid int(11) unsigned DEFAULT '0' NOT NULL,
	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	fe_group int(11) DEFAULT '0' NOT NULL,
	ms_name tinytext NOT NULL,
	ms_synonym1 tinytext NOT NULL,
	ms_synonym2 tinytext NOT NULL,
	ms_synonym3 tinytext NOT NULL,
	ms_descr_short text NOT NULL,
	ms_descr_long text NOT NULL,
	ms_image blob NOT NULL,
	ms_image_text tinytext NOT NULL,
	ms_fees text NOT NULL,
	ms_documents text NOT NULL,
	ms_legal_global text NOT NULL,
	ms_searchword text NOT NULL,
	
	ms_has_changed tinyint(4) unsigned DEFAULT '0' NOT NULL,
	ms_checksum tinytext NOT NULL,
	ms_comment_editor text NOT NULL,
	ms_uid_editor int(11) unsigned DEFAULT '0' NOT NULL,
	ms_commit_approver_one tinyint(4) unsigned DEFAULT '0' NOT NULL,
	ms_comment_approver_one text NOT NULL,
	ms_revised_approver_one tinyint(4) unsigned DEFAULT '0' NOT NULL,
	ms_commit_approver_two tinyint(4) unsigned DEFAULT '0' NOT NULL,
	ms_comment_approver_two text NOT NULL,
	ms_revised_approver_two tinyint(4) unsigned DEFAULT '0' NOT NULL,
	ms_additional_label tinytext NOT NULL,
	
	PRIMARY KEY (uid),
	KEY parent (pid)
);
