<?php /* Smarty version 2.6.26, created on 2010-04-28 12:07:20
         compiled from typo3conf/ext/civserv/templates/email_form.tpl.html */ ?>
<div id="right_dummy">
		&nbsp;
</div><!-- right end -->


<div id="centrecontent">
	<!--<?php if ($this->_tpl_vars['complete'] != ""): ?>-->
		<br />
		<p><?php echo $this->_tpl_vars['complete']; ?>
</p>
	<!--<?php else: ?>-->
		<h1><?php echo $this->_tpl_vars['email_form_label']; ?>
</h1>
		<p><?php echo $this->_tpl_vars['notice_label']; ?>
</p>
		<form name="email" method="post" action="<?php echo $this->_tpl_vars['action_url']; ?>
">
			<fieldset>
				<legend>Kontaktdaten</legend><br />
				<label for="title" class="left"><?php echo $this->_tpl_vars['title_label']; ?>
</label>
				<select id="title" name="title" title="Hier bitte die Anrede ausw&auml;hlen">
					<option selected="selected"><?php echo $this->_tpl_vars['chose_option']; ?>
</option>
					<option><?php echo $this->_tpl_vars['female_option']; ?>
</option>
					<option><?php echo $this->_tpl_vars['male_option']; ?>
</option>
				</select><br />
				<br />
				<label for="surname" class="left"><?php echo $this->_tpl_vars['surname_label']; ?>
&nbsp;*:</label>
				<input id="surname" name="surname" type="bodytext" title="Hier bitte den Nachnamen eingeben" value="<?php echo $this->_tpl_vars['surname']; ?>
"/>
				<br />&nbsp;<span class="error"><?php echo $this->_tpl_vars['error_surname']; ?>
</span>
				<br />
				<label for="firstname" class="left"><?php echo $this->_tpl_vars['firstname_label']; ?>
&nbsp;*:</label>
				<input id="firstname" name="firstname" type="bodytext" title="Hier bitte den Vorname eingeben" value="<?php echo $this->_tpl_vars['firstname']; ?>
" />
				<br />&nbsp;<span class="error"><?php echo $this->_tpl_vars['error_firstname']; ?>
</span>
				<br />
				<label for="street" class="left"><?php echo $this->_tpl_vars['street_label']; ?>
&nbsp;:</label>
				<input id="street" name="street" type="bodytext" title="Hier bitte die Straße und die Hausnummer eingeben" value="<?php echo $this->_tpl_vars['street']; ?>
" />
				<br />
				<br />
				<label for="postcode" class="left"><?php echo $this->_tpl_vars['postcode_label']; ?>
&nbsp;:</label>
				<input id="postcode" name="postcode" type="bodytext" title="Hier bitte die PLZ eingeben" value="<?php echo $this->_tpl_vars['postcode']; ?>
"/>
				<br />&nbsp;<span class="error"><?php echo $this->_tpl_vars['error_postcode']; ?>
</span>
				<br />			
				<label for="city" class="left"><?php echo $this->_tpl_vars['city_label']; ?>
&nbsp;:</label>
				<input id="city" name="city" type="bodytext" title="Hier bitte den Ort eingeben" value="<?php echo $this->_tpl_vars['city']; ?>
" />
				<br />
				<br />			
				<label for="email" class="left"><?php echo $this->_tpl_vars['email_label']; ?>
&nbsp;:</label>
				<input id="email" name="email" type="bodytext" title="Hier bitte die E-Mailadresse eingeben" value="<?php echo $this->_tpl_vars['email']; ?>
" />
				<br />&nbsp;<span class="error"><?php echo $this->_tpl_vars['error_email']; ?>
</span>
				<br />			
				<label for="phone" class="left"><?php echo $this->_tpl_vars['phone_label']; ?>
:</label>
				<input id="phone" name="phone" type="bodytext" title="Hier bitte die Telefonnummer eingeben" value="<?php echo $this->_tpl_vars['phone']; ?>
" />
				<br />&nbsp;<span class="error"><?php echo $this->_tpl_vars['error_phone']; ?>
</span>
				<br />
				<label for="fax" class="left"><?php echo $this->_tpl_vars['fax_label']; ?>
:</label>
				<input id="fax" name="fax" type="bodytext" title="Hier bitte die Faxnummer eingeben" value="<?php echo $this->_tpl_vars['fax']; ?>
" />
				<br />
				<br />
			</fieldset><br />
		
			<fieldset>
				<legend>bodytextmitteilung</legend><br />
				<label for="subject" class="left"><?php echo $this->_tpl_vars['subject_label']; ?>
*:</label> 
				<input id="subject" name="subject" type="bodytext" title="Hier bitte den Betreff der Mitteilung eingeben" value="<?php echo $this->_tpl_vars['subject']; ?>
" />
				<br />&nbsp;<span class="error"><?php echo $this->_tpl_vars['error_subject']; ?>
</span>
				<br />
				<label for="bodytext" class="left"><?php echo $this->_tpl_vars['bodytext_label']; ?>
&nbsp;*:</label>
				<textarea id="bodytext" name="bodytext" title="Hier bitte den Text eingeben" rows="20" cols="20" ><?php echo $this->_tpl_vars['bodytext']; ?>
</textarea>
				<br />&nbsp;<span class="error"><?php echo $this->_tpl_vars['error_bodytext']; ?>
</span>
			</fieldset>
			<br />
			<p>
			<input accesskey="9" id="formularabsenden" type="submit" class="button" name="submit" value="<?php echo $this->_tpl_vars['submit_label']; ?>
"  title="Senden Sie hier das ausgef&uuml;llte Formular ab" />&nbsp;<input accesskey="9" id="formularloeschen" type="reset" name="reset" value="<?php echo $this->_tpl_vars['reset_label']; ?>
" title="L&ouml;schen Sie die eingetragenen Daten" />
		</p>
		<br />
		<p>*&nbsp;<?php echo $this->_tpl_vars['required_label']; ?>
</p>
		</form>
		<!--<?php endif; ?>-->			
	</div><!-- centrecontent end -->