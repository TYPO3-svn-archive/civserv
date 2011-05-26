<?php /* Smarty version 2.6.26, created on 2010-04-28 12:26:11
         compiled from typo3conf/ext/civserv/templates/employee.tpl.html */ ?>
<!--TYPO3SEARCH_begin-->
<!--<?php if ($this->_tpl_vars['employee_search'] == '0'): ?>-->
	<!--TYPO3SEARCH_end-->
	<div class="MAA"></div>
<!--<?php endif; ?>-->
<div id="right_dummy">
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['right_searchbox_template'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['right_top_template'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</div><!-- right end -->

<div id="centrecontent">
		
	<h1><?php echo $this->_tpl_vars['employee_label']; ?>
:
	<!--<?php if ($this->_tpl_vars['address'] != ""): ?>--><?php echo $this->_tpl_vars['address']; ?>
<!--<?php endif; ?>-->
	<!--<?php if ($this->_tpl_vars['title'] != ""): ?>--><?php echo $this->_tpl_vars['title']; ?>
<!--<?php endif; ?>-->
	<!--<?php if ($this->_tpl_vars['firstname'] != ""): ?>--><?php echo $this->_tpl_vars['firstname']; ?>
<!--<?php endif; ?>-->
	<!--<?php if ($this->_tpl_vars['name'] != ""): ?>--><?php echo $this->_tpl_vars['name']; ?>
<!--<?php endif; ?>-->
	</h1>
	
	<div class="employeedetail">
		<!--<?php if ($this->_tpl_vars['backlink'] != ""): ?>-->		
			<h4><?php echo $this->_tpl_vars['backlink']; ?>
</h4>	
		<!--<?php endif; ?>-->	
		<!--<?php if ($this->_tpl_vars['image'] != ""): ?>-->
			<span style="float:right;padding:10px">
				<?php echo $this->_tpl_vars['image']; ?>

			</span>
		<!--<?php endif; ?>-->

		<!--<?php if ($this->_tpl_vars['position']['organisation'] != ""): ?>-->
			<?php echo $this->_tpl_vars['organisation_label']; ?>
:&nbsp;<a href="<?php echo $this->_tpl_vars['position']['or_link']; ?>
"><?php echo $this->_tpl_vars['position']['organisation']; ?>
</a><br /><br />
		<!--<?php endif; ?>-->
		
		<!--<?php if ($this->_tpl_vars['position']['room'] != ""): ?>-->
			<?php echo $this->_tpl_vars['position']['building']; ?>
&nbsp;(<?php echo $this->_tpl_vars['position']['floor']; ?>
,&nbsp;<?php echo $this->_tpl_vars['room_label']; ?>
&nbsp;<?php echo $this->_tpl_vars['position']['room']; ?>
)<br /><br />
		<!--<?php endif; ?>-->
		
		<!--<?php if ($this->_tpl_vars['position']['phone'] != ""): ?>-->
			<strong><?php echo $this->_tpl_vars['phone_label']; ?>
:</strong>
			<?php echo $this->_tpl_vars['position']['phone']; ?>
<br />
		<!--<?php elseif ($this->_tpl_vars['phone'] != ""): ?>-->
			<strong><?php echo $this->_tpl_vars['phone_label']; ?>
:</strong>
			<?php echo $this->_tpl_vars['phone']; ?>
<br />
		<!--<?php endif; ?>-->
		
		<!--<?php if ($this->_tpl_vars['position']['fax'] != ""): ?>-->
			<strong><?php echo $this->_tpl_vars['fax_label']; ?>
:</strong>
			<?php echo $this->_tpl_vars['position']['fax']; ?>
<br />
		<!--<?php elseif ($this->_tpl_vars['fax'] != ""): ?>-->
			<strong><?php echo $this->_tpl_vars['fax_label']; ?>
:</strong>
			<?php echo $this->_tpl_vars['fax']; ?>
<br />
		<!--<?php endif; ?>-->
		<br />
		
		<!--<?php if ($this->_tpl_vars['email_form_url'] != ""): ?>-->
			<strong><?php echo $this->_tpl_vars['email_label']; ?>
:</strong>
			<?php echo $this->_tpl_vars['email_code']; ?>
<br />
			<a href="<?php echo $this->_tpl_vars['email_form_url']; ?>
" class="link"><?php echo $this->_tpl_vars['web_email_label']; ?>
</a><br /><br />
		<!--<?php endif; ?>-->
		
		<!--<?php if ($this->_tpl_vars['emp_pos_hours'] != ""): ?>-->
			<table summary="<?php echo $this->_tpl_vars['office_hours_summary']; ?>
">
				<caption><?php echo $this->_tpl_vars['working_hours_label']; ?>
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
					<!--<?php $_from = $this->_tpl_vars['emp_pos_hours']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
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
		<!--<?php elseif ($this->_tpl_vars['emp_hours'] != ""): ?>-->
			<table summary="<?php echo $this->_tpl_vars['office_hours_summary']; ?>
">
				<caption><?php echo $this->_tpl_vars['working_hours_label']; ?>
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
					<!--<?php $_from = $this->_tpl_vars['emp_hours']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
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
		<!--<?php elseif ($this->_tpl_vars['emp_org_hours'] != ""): ?>-->
			<table summary="<?php echo $this->_tpl_vars['office_hours_summary']; ?>
">
				<caption><?php echo $this->_tpl_vars['working_hours_label']; ?>
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
					<!--<?php $_from = $this->_tpl_vars['emp_org_hours']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
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
		</div>
		<!--<?php if ($this->_tpl_vars['employee_search'] == '0'): ?>-->
			<div class="MAE"></div>
		<!--<?php endif; ?>-->	
		<!--<?php if ($this->_tpl_vars['employee_search'] == '1'): ?>-->
			<!--TYPO3SEARCH_end-->
		<!--<?php endif; ?>-->		
</div>