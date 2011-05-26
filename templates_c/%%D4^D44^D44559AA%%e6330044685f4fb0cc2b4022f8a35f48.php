<?php /* Smarty version 2.6.26, created on 2011-03-02 15:03:14
         compiled from typo3conf/ext/civserv/templates/organisation.tpl.html */ ?>
<!--TYPO3SEARCH_begin-->
<!--<?php if ($this->_tpl_vars['abcbarOrganisationList_continued'] != ""): ?>-->
	<?php echo $this->_tpl_vars['abcbarOrganisationList_continued']; ?>

<!--<?php endif; ?>-->



<div class="orgadetail">
	<!--<?php if ($this->_tpl_vars['or_image'] != ""): ?>-->
		<span style="float:right;padding:10px">
			<?php echo $this->_tpl_vars['or_image']; ?>

		</span>
	<!--<?php endif; ?>-->
	<!--<?php if ($this->_tpl_vars['bl_available'] == 1): ?>-->
		<strong><?php echo $this->_tpl_vars['building_address_label']; ?>
:</strong><br/>
		<!--<?php $_from = $this->_tpl_vars['buildings']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['building']):
?>-->
			<!--<?php if ($this->_tpl_vars['building']['bl_building_street'] != "" || $this->_tpl_vars['building']['bl_building_postcode'] != "" || $this->_tpl_vars['building']['bl_building_city'] != ""): ?>-->
				<!--<?php if ($this->_tpl_vars['building']['bl_name_to_show'] != ""): ?>-->
					<?php echo $this->_tpl_vars['building']['bl_name_to_show']; ?>
<br />
				<!--<?php endif; ?>-->
				<?php echo $this->_tpl_vars['building']['bl_building_street']; ?>
<br/>
				<?php echo $this->_tpl_vars['building']['bl_building_postcode']; ?>
&nbsp;<?php echo $this->_tpl_vars['building']['bl_building_city']; ?>
<br />
			<!--<?php endif; ?>-->
			<!--<?php if ($this->_tpl_vars['or_addlocation'] != ""): ?>-->
				<em><?php echo $this->_tpl_vars['or_addlocation']; ?>
</em><br /><br />
			<!--<?php endif; ?>-->	
			<!--<?php if ($this->_tpl_vars['building']['bl_pubtrans_stop'] != ""): ?>-->
				<?php echo $this->_tpl_vars['pub_trans_info_label']; ?>
:
				<!--<?php if ($this->_tpl_vars['building']['bl_pubtrans_link'] != ""): ?>-->
					<?php echo $this->_tpl_vars['pub_trans_stop_label']; ?>
:&nbsp;<a href="<?php echo $this->_tpl_vars['building']['bl_pubtrans_link']; ?>
" target="_blank"><?php echo $this->_tpl_vars['building']['bl_pubtrans_stop']; ?>
</a><br /><br />
				<!--<?php else: ?>-->
					<?php echo $this->_tpl_vars['pub_trans_stop_label']; ?>
:&nbsp;<?php echo $this->_tpl_vars['building']['bl_pubtrans_stop']; ?>
<br /><br />
				<!--<?php endif; ?>-->
			<!--<?php endif; ?>-->		
			<!--<?php if ($this->_tpl_vars['building']['bl_citymap_link'] != ""): ?>-->
				<a href="<?php echo $this->_tpl_vars['building']['bl_citymap_link']; ?>
" target="_blank"><?php echo $this->_tpl_vars['bl_citymap_label']; ?>
</a><br /><br /> 
			<!--<?php endif; ?>-->
		<!--<?php endforeach; endif; unset($_from); ?>-->
		<!--<?php if ($this->_tpl_vars['building']['bl_mail_street'] != "" || $this->_tpl_vars['building']['bl_mail_pob'] != "" || $this->_tpl_vars['building']['bl_mail_postcode'] != "" || $this->_tpl_vars['building']['bl_mail_city'] != ""): ?>-->
			<strong><?php echo $this->_tpl_vars['postal_address_label']; ?>
:</strong><br />
			<!--<?php if ($this->_tpl_vars['building']['bl_mail_street'] != ""): ?>-->
			<?php echo $this->_tpl_vars['building']['bl_mail_street']; ?>
<br />
			<!--<?php endif; ?>-->
			<!--<?php if ($this->_tpl_vars['building']['bl_mail_pob'] != ""): ?>-->
			<?php echo $this->_tpl_vars['postbox_label']; ?>
&nbsp;<?php echo $this->_tpl_vars['building']['bl_mail_pob']; ?>
<br />
			<!--<?php endif; ?>-->
			<?php echo $this->_tpl_vars['building']['bl_mail_postcode']; ?>
&nbsp;<?php echo $this->_tpl_vars['building']['bl_mail_city']; ?>
<br /><br />
		<!--<?php endif; ?>-->
	<!--<?php endif; ?>-->
	
		
	<!--<?php if ($this->_tpl_vars['or_phone'] != ""): ?>-->
		<strong><?php echo $this->_tpl_vars['phone_label']; ?>
:</strong> <?php echo $this->_tpl_vars['or_phone']; ?>
<br />
	<!--<?php endif; ?>-->
	<!--<?php if ($this->_tpl_vars['or_fax'] != ""): ?>-->
		<strong><?php echo $this->_tpl_vars['fax_label']; ?>
:</strong> <?php echo $this->_tpl_vars['or_fax']; ?>
<br />
	<!--<?php endif; ?>-->
	<br />
	<!--<?php if ($this->_tpl_vars['or_email_code'] != ""): ?>-->
		<strong><?php echo $this->_tpl_vars['email_label']; ?>
:</strong> <?php echo $this->_tpl_vars['or_email_code']; ?>
<br/>
		<a href="<?php echo $this->_tpl_vars['or_email_form_url']; ?>
" class="link"><?php echo $this->_tpl_vars['web_email_label']; ?>
</a><br /><br />
	<!--<?php endif; ?>-->
	<!--<?php if ($this->_tpl_vars['or_addinfo'] != ""): ?>-->
		<div><?php echo $this->_tpl_vars['or_addinfo']; ?>
</div>
	<!--<?php endif; ?>-->	
	<!--<?php if ($this->_tpl_vars['or_infopage'] != ""): ?>-->
		<strong><?php echo $this->_tpl_vars['infopage_label']; ?>
:</strong>
		<a href="<?php echo $this->_tpl_vars['or_infopage']; ?>
" class="link"><?php echo $this->_tpl_vars['or_infopage']; ?>
&nbsp;</a><br /><br />
	<!--<?php endif; ?>-->
	<!--<?php if ($this->_tpl_vars['su_name'] != ""): ?>-->
		<strong><?php echo $this->_tpl_vars['supervisor_label']; ?>
:</strong>
		<!--<?php if ($this->_tpl_vars['su_link'] != ""): ?>-->
			<a href="<?php echo $this->_tpl_vars['su_link']; ?>
" title="<?php echo $this->_tpl_vars['employee_details']; ?>
">
			<!--<?php if ($this->_tpl_vars['su_address_label'] != ""): ?>-->
				<?php echo $this->_tpl_vars['su_address_label']; ?>

			<!--<?php endif; ?>-->
			<!--<?php if ($this->_tpl_vars['su_title'] != ""): ?>-->
				<?php echo $this->_tpl_vars['su_title']; ?>

			<!--<?php endif; ?>-->
			<?php echo $this->_tpl_vars['su_firstname']; ?>
&nbsp;<?php echo $this->_tpl_vars['su_name']; ?>
</a><br/>
		<!--<?php else: ?>-->
			<!--<?php if ($this->_tpl_vars['su_address_label'] != ""): ?>-->
				<?php echo $this->_tpl_vars['su_address_label']; ?>

			<!--<?php endif; ?>-->
			<!--<?php if ($this->_tpl_vars['su_title'] != ""): ?>-->
				<?php echo $this->_tpl_vars['su_title']; ?>

			<!--<?php endif; ?>-->
			<?php echo $this->_tpl_vars['su_firstname']; ?>
&nbsp;<?php echo $this->_tpl_vars['su_name']; ?>
<br/>
		<!--<?php endif; ?>-->
		<br />
	<!--<?php endif; ?>-->
	<!--<?php if ($this->_tpl_vars['bl_available'] == 1): ?>-->
		<!--<?php if ($this->_tpl_vars['office_hours'] != ""): ?>-->
			<table summary="<?php echo $this->_tpl_vars['office_hours_summary']; ?>
">
				<caption><?php echo $this->_tpl_vars['office_hours_label']; ?>
:</caption>
				<thead>
					<tr>
					<!--<?php if ($this->_tpl_vars['supress_labels'] > ''): ?>-->
						<th class="invisible"><?php echo $this->_tpl_vars['weekday']; ?>
</th>
						<th class="invisible"><?php echo $this->_tpl_vars['morning']; ?>
</th>
						<th class="invisible"><?php echo $this->_tpl_vars['afternoon']; ?>
</th>
					<!--<?php else: ?>-->
						<th><?php echo $this->_tpl_vars['weekday']; ?>
</th>
						<th><?php echo $this->_tpl_vars['morning']; ?>
</th>
						<th><?php echo $this->_tpl_vars['afternoon']; ?>
</th>
					<!--<?php endif; ?>-->	
					</tr>
				</thead>
				<tbody>
					<!--<?php $_from = $this->_tpl_vars['office_hours']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['hours']):
?>-->
						<tr>
							<td><?php echo $this->_tpl_vars['hours']['weekday']; ?>
</td>
							<!--<?php if ($this->_tpl_vars['hours']['freestyle'] > ''): ?>-->
								<td colspan="2"><?php echo $this->_tpl_vars['hours']['freestyle']; ?>
</td>
							<!--<?php else: ?>-->
								<!--<?php if ($this->_tpl_vars['hours']['end_morning'] == '0' && $this->_tpl_vars['hours']['start_afternoon'] == '0'): ?>-->
									<td><?php echo $this->_tpl_vars['hours']['start_morning']; ?>
&nbsp;-&nbsp;<?php echo $this->_tpl_vars['hours']['end_afternoon']; ?>
</td>
								<!--<?php else: ?>-->
									<!--<?php if ($this->_tpl_vars['hours']['start_morning'] != '0' && $this->_tpl_vars['hours']['end_morning'] != '0'): ?>-->
										<td><?php echo $this->_tpl_vars['hours']['start_morning']; ?>
&nbsp;-&nbsp;<?php echo $this->_tpl_vars['hours']['end_morning']; ?>
</td>
									<!--<?php endif; ?>-->
									<!--<?php if ($this->_tpl_vars['hours']['start_afternoon'] != '0' && $this->_tpl_vars['hours']['end_afternoon'] != '0'): ?>-->
										<td><?php echo $this->_tpl_vars['hours']['start_afternoon']; ?>
&nbsp;-&nbsp;<?php echo $this->_tpl_vars['hours']['end_afternoon']; ?>
</td>
									<!--<?php else: ?>-->	
										<td>&nbsp;</td>
									<!--<?php endif; ?>-->
								<!--<?php endif; ?>-->
							<!--<?php endif; ?>-->	
						</tr>
					<!--<?php endforeach; endif; unset($_from); ?>-->
				</tbody>
			</table>
		<!--<?php endif; ?>-->
	<!--<?php endif; ?>-->
	<!--<?php if ($this->_tpl_vars['super_organisation'] != ""): ?>-->
		<strong><br /><br /><br /><?php echo $this->_tpl_vars['super_org_label']; ?>
</strong>
		<a href="<?php echo $this->_tpl_vars['super_organisation']['link']; ?>
"><?php echo $this->_tpl_vars['super_organisation']['name']; ?>
</a>
	<!--<?php endif; ?>-->
	<!--<?php if ($this->_tpl_vars['sub_organisations'] != ""): ?>-->
		<div id="linklist">
		<strong><br /><br /><br /><?php echo $this->_tpl_vars['sub_org_label']; ?>
</strong><br /><br />
			<ul class="level1">
				<!--<?php $_from = $this->_tpl_vars['sub_organisations']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['organisation']):
?>-->
					<li><a href="<?php echo $this->_tpl_vars['organisation']['link']; ?>
"><?php echo $this->_tpl_vars['organisation']['name']; ?>
</a></li>
				<!--<?php endforeach; endif; unset($_from); ?>-->			
			</ul><!-- level1 end -->
		</div>
	<!--<?php endif; ?>-->
</div>
<!--TYPO3SEARCH_end-->