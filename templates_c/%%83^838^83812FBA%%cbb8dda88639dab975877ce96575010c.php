<?php /* Smarty version 2.6.26, created on 2011-03-02 15:03:09
         compiled from typo3conf/ext/civserv/templates/service.tpl.html */ ?>
<div id="right">
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['right_searchbox_template'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	
		<!--<?php if ($this->_tpl_vars['fees'] != "" || $this->_tpl_vars['documents'] != "" || $this->_tpl_vars['forms'] != "" || $this->_tpl_vars['legal_local'] != "" || $this->_tpl_vars['legal_global'] != "" || $this->_tpl_vars['employees'] != ""): ?>-->
		<h3 class="invisible"><?php echo $this->_tpl_vars['subnavigation_label']; ?>
:</h3>
		<div id="rightmenu">
			<ul class="level1">
				<!--<?php if ($this->_tpl_vars['fees'] != ""): ?>-->
					<li><a href="#fees" class="fees" title="<?php echo $this->_tpl_vars['link_to_section']; ?>
 <?php echo $this->_tpl_vars['fees_label']; ?>
"><?php echo $this->_tpl_vars['fees_label']; ?>
</a></li>
				<!--<?php endif; ?>-->
				<!--<?php if ($this->_tpl_vars['documents'] != ""): ?>-->
					<li><a href="#necessarydocuments" class="necessarydocuments" title="<?php echo $this->_tpl_vars['link_to_section']; ?>
 <?php echo $this->_tpl_vars['documents_label']; ?>
"><?php echo $this->_tpl_vars['documents_label']; ?>
</a></li>
				<!--<?php endif; ?>-->
				<!--<?php if ($this->_tpl_vars['forms'] != ""): ?>-->
					<li><a href="#forms" class="forms" title="<?php echo $this->_tpl_vars['link_to_section']; ?>
 <?php echo $this->_tpl_vars['forms_label']; ?>
"><?php echo $this->_tpl_vars['forms_label']; ?>
</a></li>
				<!--<?php endif; ?>-->
				<!--<?php if ($this->_tpl_vars['legal_local'] != "" || $this->_tpl_vars['legal_global'] != ""): ?>-->
					<li><a href="#legal" class="legals" title="<?php echo $this->_tpl_vars['link_to_section']; ?>
 <?php echo $this->_tpl_vars['legal_label']; ?>
"><?php echo $this->_tpl_vars['legal_label']; ?>
</a></li>
				<!--<?php endif; ?>-->
				<!--<?php if ($this->_tpl_vars['employees'] != ""): ?>-->
					<li><a href="#contactperson" class="contactperson" title="<?php echo $this->_tpl_vars['link_to_section']; ?>
 <?php echo $this->_tpl_vars['contact_label']; ?>
"><?php echo $this->_tpl_vars['contact_label']; ?>
</a></li>
				<!--<?php endif; ?>-->
			</ul><!-- level1 end -->
		</div><!-- rightmenu end -->
	<!--<?php endif; ?>-->

		<!--<?php if (! empty ( $this->_tpl_vars['related_topics'] )): ?>-->
		<h3 class="invisible"><?php echo $this->_tpl_vars['serviceinformation_label']; ?>
:</h3>
		<div id="serviceinformation">
			<strong><?php echo $this->_tpl_vars['pages_related_topics_label']; ?>
:</strong><br /><br />
			<ul>
				<!--<?php $_from = $this->_tpl_vars['related_topics']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['service']):
?>-->				
					<!--<?php if (( $this->_tpl_vars['service']['preview'] == 1 ) && ( $this->_tpl_vars['service']['fe_group'] > 0 )): ?>-->	
						<li class="intern_preview">
							<a class="intern_preview" href="<?php echo $this->_tpl_vars['service']['link']; ?>
"><?php echo $this->_tpl_vars['service']['name']; ?>
</a>
						</li>
					<!--<?php elseif (( $this->_tpl_vars['service']['preview'] == 1 ) && ( $this->_tpl_vars['service']['fe_group'] <= 0 )): ?>-->
						<li class="preview">
							<a class="preview" href="<?php echo $this->_tpl_vars['service']['link']; ?>
"><?php echo $this->_tpl_vars['service']['name']; ?>
</a>
						</li>
					<!--<?php elseif (( $this->_tpl_vars['service']['preview'] !== 1 ) && ( $this->_tpl_vars['service']['fe_group'] > 0 )): ?>-->
						<li class="intern">
							<a class="link" href="<?php echo $this->_tpl_vars['service']['link']; ?>
"><span class="intern"><?php echo $this->_tpl_vars['service']['name']; ?>
</span></a>
						</li>
					<!--<?php else: ?>-->
						<li>
							<a class="link" href="<?php echo $this->_tpl_vars['service']['link']; ?>
"><?php echo $this->_tpl_vars['service']['name']; ?>
</a>
						</li>
					<!--<?php endif; ?>-->				
				<!--<?php endforeach; endif; unset($_from); ?>-->		
			</ul><!-- level1 end -->
		</div><!-- serviceinformation end -->
	<!--<?php endif; ?>-->
	
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['right_top_template'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</div><!-- right end -->

<div id="centrecontent">

	<!--TYPO3SEARCH_begin-->
	
	<!--<?php if (( $this->_tpl_vars['preview'] == 1 ) && ( $this->_tpl_vars['fe_group'] > 0 )): ?>-->	
		<h1 class="intern_preview"><?php echo $this->_tpl_vars['service_label']; ?>
:&nbsp;<?php echo $this->_tpl_vars['name']; ?>
</h1>
	<!--<?php elseif (( $this->_tpl_vars['preview'] == 1 ) && ( $this->_tpl_vars['fe_group'] <= 0 )): ?>-->
		<h1 class="preview"><?php echo $this->_tpl_vars['service_label']; ?>
:&nbsp;<?php echo $this->_tpl_vars['name']; ?>
</h1>
	<!--<?php elseif (( $this->_tpl_vars['preview'] !== 1 ) && ( $this->_tpl_vars['fe_group'] > 0 )): ?>-->
		<h1 class="intern"><?php echo $this->_tpl_vars['service_label']; ?>
:&nbsp;<?php echo $this->_tpl_vars['name']; ?>
</h1>
	<!--<?php else: ?>-->
		<h1><?php echo $this->_tpl_vars['service_label']; ?>
:&nbsp;<?php echo $this->_tpl_vars['name']; ?>
</h1>
	<!--<?php endif; ?>-->

	<!--<?php if ($this->_tpl_vars['external_service_label'] != ""): ?>-->
		<p class="external_service"><?php echo $this->_tpl_vars['external_service_label']; ?>
</p>
	<!--<?php endif; ?>-->
	
	<!--<?php if ($this->_tpl_vars['ext_link'] != ""): ?>-->
		<h2><?php echo $this->_tpl_vars['ext_service_label']; ?>
:</h2>
		<p><a href="<?php echo $this->_tpl_vars['ext_link']; ?>
" class="link"><?php echo $this->_tpl_vars['ext_name']; ?>
</a></p>
	<!--<?php endif; ?>-->

	<!--<?php if (( $this->_tpl_vars['descr_short'] != "" || $this->_tpl_vars['descr_long'] != "" )): ?>-->
		<h2><?php echo $this->_tpl_vars['description_label']; ?>
</h2>
	<!--<?php endif; ?>-->
	
	<!--<?php if ($this->_tpl_vars['image'] != ""): ?>-->
		<table align="right" width="50px" class="right">
			<tr>
				<td>
					<?php echo $this->_tpl_vars['image']; ?>

				</td>
			</tr>
			<tr>
				<td>
					<!--<?php if ($this->_tpl_vars['image_text'] != ""): ?>-->
						<strong><?php echo $this->_tpl_vars['image_text']; ?>
</strong><br />
					<!--<?php endif; ?>-->
					<small></small>
				</td>
			</tr>
		</table>
	<!--<?php endif; ?>-->
	
	<!--<?php if (( $this->_tpl_vars['descr_short'] != "" || $this->_tpl_vars['descr_long'] != "" )): ?>-->
		<div class="content">
			<?php echo $this->_tpl_vars['descr_short']; ?>

			<?php echo $this->_tpl_vars['descr_long']; ?>

		</div>	
		<p><a href="#centrecontent" class="topofpage" title="<?php echo $this->_tpl_vars['link_to_top']; ?>
"><?php echo $this->_tpl_vars['top']; ?>
</a></p><br />
	<!--<?php endif; ?>-->
	
	<!--<?php if ($this->_tpl_vars['fees'] != ""): ?>-->
		<a name="fees" id="fees"></a>
		<h2><?php echo $this->_tpl_vars['fees_label']; ?>
</h2>
		<div class="content">
			<?php echo $this->_tpl_vars['fees']; ?>

		</div>		
		<p><a href="#centrecontent" class="topofpage" title="<?php echo $this->_tpl_vars['link_to_top']; ?>
"><?php echo $this->_tpl_vars['top']; ?>
</a></p><br />
	<!--<?php endif; ?>-->
	
	<!--<?php if ($this->_tpl_vars['documents'] != ""): ?>-->
		<a name="necessarydocuments" id="necessarydocuments"></a>
		<h2><?php echo $this->_tpl_vars['documents_label']; ?>
</h2>
		<div class="content">
			<?php echo $this->_tpl_vars['documents']; ?>

		</div>
		<p><a href="#centrecontent" class="topofpage" title="<?php echo $this->_tpl_vars['link_to_top']; ?>
"><?php echo $this->_tpl_vars['top']; ?>
</a></p><br />
	<!--<?php endif; ?>-->
	
	<!--<?php if ($this->_tpl_vars['forms'] != ""): ?>-->
		<a name="forms" id="forms"></a>
		<h2><?php echo $this->_tpl_vars['forms_label']; ?>
</h2>
		<ul>
		<!--<?php $_from = $this->_tpl_vars['forms']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['form']):
?>-->
			<!--<?php if ($this->_tpl_vars['form']['target'] == 0): ?>-->
					<li><a href="<?php echo $this->_tpl_vars['form']['url']; ?>
" target="_blank"><strong><?php echo $this->_tpl_vars['form']['name']; ?>
</strong></a></li>
			<!--<?php else: ?>-->
					<li><a href="<?php echo $this->_tpl_vars['form']['url']; ?>
"><strong><?php echo $this->_tpl_vars['form']['name']; ?>
</strong></a></li>
			<!--<?php endif; ?>-->
		<!--<?php endforeach; endif; unset($_from); ?>-->
		</ul>
		<p><a href="#centrecontent" class="topofpage" title="<?php echo $this->_tpl_vars['link_to_top']; ?>
"><?php echo $this->_tpl_vars['top']; ?>
</a></p><br />
	<!--<?php endif; ?>-->

	<!--<?php if ($this->_tpl_vars['legal_local'] != "" || $this->_tpl_vars['legal_global'] != ""): ?>-->
		<a name="legal" id="legal"></a>
	<!--<?php endif; ?>-->
	<!--<?php if ($this->_tpl_vars['legal_local'] != ""): ?>-->
		<h2><?php echo $this->_tpl_vars['legal_local_label']; ?>
</h2>
		<div class="content">
			<?php echo $this->_tpl_vars['legal_local']; ?>

		</div>
		<p><a href="#centrecontent" class="topofpage" title="<?php echo $this->_tpl_vars['link_to_top']; ?>
"><?php echo $this->_tpl_vars['top']; ?>
</a></p><br />
	<!--<?php endif; ?>-->
	<!--<?php if ($this->_tpl_vars['legal_global'] != ""): ?>-->
		<h2><?php echo $this->_tpl_vars['legal_global_label']; ?>
</h2>
		<div class="content">
			<?php echo $this->_tpl_vars['legal_global']; ?>

		</div>
		<p><a href="#centrecontent" class="topofpage" title="<?php echo $this->_tpl_vars['link_to_top']; ?>
"><?php echo $this->_tpl_vars['top']; ?>
</a></p><br />
	<!--<?php endif; ?>-->

	<!--<?php if ($this->_tpl_vars['similar_services'] != ""): ?>-->
		<h2><?php echo $this->_tpl_vars['similar_services_label']; ?>
</h2>
		<ul id="forms">
			<!--<?php $_from = $this->_tpl_vars['similar_services']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['service']):
?>-->
				<li><a href="<?php echo $this->_tpl_vars['service']['url']; ?>
"><?php echo $this->_tpl_vars['service']['name']; ?>
</a></li>
			<!--<?php endforeach; endif; unset($_from); ?>-->
		</ul>
		<p><a href="#centrecontent" class="topofpage" title="<?php echo $this->_tpl_vars['link_to_top']; ?>
"><?php echo $this->_tpl_vars['top']; ?>
</a></p><br />
	<!--<?php endif; ?>-->
	<!--<?php if ($this->_tpl_vars['searchwords'] != ""): ?>-->
		<div class="searchwords"><?php echo $this->_tpl_vars['searchwords']; ?>
</div>
	<!--<?php endif; ?>-->
<!--<?php if ($this->_tpl_vars['employee_search'] == '0'): ?>-->
	<!--TYPO3SEARCH_end-->
	<div class="MAA"></div>
<!--<?php endif; ?>-->	

	<!--<?php if ($this->_tpl_vars['organisations'] != ""): ?>-->
		<h2><?php echo $this->_tpl_vars['organisation_label']; ?>
</h2>
		<ul id="organisations">
			<!--<?php $_from = $this->_tpl_vars['organisations']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['organisation']):
?>-->
				<li><a href="<?php echo $this->_tpl_vars['organisation']['url']; ?>
"><?php echo $this->_tpl_vars['organisation']['name']; ?>
</a></li>
			<!--<?php endforeach; endif; unset($_from); ?>-->
		</ul>
		<p><a href="#centrecontent" class="topofpage" title="<?php echo $this->_tpl_vars['link_to_top']; ?>
"><?php echo $this->_tpl_vars['top']; ?>
</a></p><br />
	<!--<?php endif; ?>-->

	<!--<?php if ($this->_tpl_vars['employees'] != ""): ?>-->
		<a name="contactperson" id="contactperson"></a><h2><?php echo $this->_tpl_vars['contact_label']; ?>
</h2>
		<!--<?php $_from = $this->_tpl_vars['employees']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['employee']):
?>-->
			<p>
			<!--<?php if ($this->_tpl_vars['employee']['ep_datasec'] == '1'): ?>-->
				<!--<?php if ($this->_tpl_vars['employee']['em_datasec'] == '1'): ?>-->
					<a href="<?php echo $this->_tpl_vars['employee']['employee_url']; ?>
" title="<?php echo $this->_tpl_vars['employee_details']; ?>
"><strong><?php echo $this->_tpl_vars['employee']['address_long']; ?>

					<!--<?php if ($this->_tpl_vars['employee']['title'] > ''): ?>--><?php echo $this->_tpl_vars['employee']['title']; ?>
<!--<?php endif; ?>-->	
					<?php echo $this->_tpl_vars['employee']['firstname']; ?>
&nbsp;<?php echo $this->_tpl_vars['employee']['name']; ?>
</strong></a><br />
				<!--<?php else: ?>-->
					<strong><?php echo $this->_tpl_vars['employee']['address_long']; ?>

					<!--<?php if ($this->_tpl_vars['employee']['title'] > ''): ?>--><?php echo $this->_tpl_vars['employee']['title']; ?>
<!--<?php endif; ?>-->	
					<?php echo $this->_tpl_vars['employee']['firstname']; ?>
&nbsp;<?php echo $this->_tpl_vars['employee']['name']; ?>
</strong><br />
				<!--<?php endif; ?>-->
				<!--<?php if ($this->_tpl_vars['employee']['email_code'] != ""): ?>-->
					<?php echo $this->_tpl_vars['email_label']; ?>
: <?php echo $this->_tpl_vars['employee']['email_code']; ?>
<br />	
				<!--<?php endif; ?>-->		
				<!--<?php if ($this->_tpl_vars['employee']['ep_telephone'] != ""): ?>-->
					<?php echo $this->_tpl_vars['phone_label']; ?>
: <?php echo $this->_tpl_vars['employee']['ep_telephone']; ?>
<br />
				<!--<?php elseif ($this->_tpl_vars['employee']['em_telephone'] != ""): ?>-->
					<?php echo $this->_tpl_vars['phone_label']; ?>
: <?php echo $this->_tpl_vars['employee']['em_telephone']; ?>
<br />
				<!--<?php endif; ?>-->		
				<!--<?php if ($this->_tpl_vars['employee']['description'] != ""): ?>-->
					<?php echo $this->_tpl_vars['employee']['description']; ?>
<br />
				<!--<?php endif; ?>-->
				<!--<?php if ($this->_tpl_vars['employee']['ep_email'] != ""): ?>-->
					<a href="<?php echo $this->_tpl_vars['employee']['email_form_url']; ?>
" class="link"><?php echo $this->_tpl_vars['web_email_label']; ?>
</a><br /><br />
				<!--<?php elseif ($this->_tpl_vars['employee']['em_email'] != ""): ?>-->
					<a href="<?php echo $this->_tpl_vars['employee']['email_form_url']; ?>
" class="link"><?php echo $this->_tpl_vars['web_email_label']; ?>
</a><br /><br />
				<!--<?php endif; ?>-->
			<!--<?php endif; ?>-->
			</p>
		<!--<?php endforeach; endif; unset($_from); ?>-->
		<p><a href="#centrecontent" class="topofpage" title="<?php echo $this->_tpl_vars['link_to_top']; ?>
"><?php echo $this->_tpl_vars['top']; ?>
</a></p>
		<!--<?php if ($this->_tpl_vars['employee_search'] == '0'): ?>-->
			<div class="MAE"></div>
		<!--<?php endif; ?>-->	
		<!--<?php if ($this->_tpl_vars['employee_search'] == '1'): ?>-->
			<!--TYPO3SEARCH_end-->
		<!--<?php endif; ?>-->	
	<!--<?php endif; ?>-->
</div><!-- centrecontent end -->