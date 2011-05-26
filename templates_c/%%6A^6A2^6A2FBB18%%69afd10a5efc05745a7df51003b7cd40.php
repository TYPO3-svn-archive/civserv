<?php /* Smarty version 2.6.26, created on 2010-07-22 14:25:52
         compiled from typo3conf/ext/civserv/templates/form_list.tpl.html */ ?>
<!--TYPO3SEARCH_end-->
<div id="right">
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['right_searchbox_template'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>	
		
	<!--<?php if ($this->_tpl_vars['organisations'] != ""): ?>-->		
		<h3 class="invisible"><?php echo $this->_tpl_vars['subnavigation_label']; ?>
:</h3>
		<div id="rightmenu">
			<ul class="level1">
				<!--<?php $_from = $this->_tpl_vars['organisations']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['organisation']):
?>-->
					<li><a href="<?php echo $this->_tpl_vars['organisation']['link']; ?>
"><?php echo $this->_tpl_vars['organisation']['name']; ?>
</a></li>
				<!--<?php endforeach; endif; unset($_from); ?>-->			
			</ul><!-- level1 end -->
		</div><!-- rightmenu end -->	
	<!--<?php endif; ?>-->
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['right_top_template'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</div><!-- right end -->
<div id="centrecontent">	
	<!--<?php if ($this->_tpl_vars['heading'] != ""): ?>-->		
		<h1><?php echo $this->_tpl_vars['heading']; ?>
</h1>	
	<!--<?php endif; ?>-->	
	<!--<?php if ($this->_tpl_vars['abcbar'] != ""): ?>-->		
		<?php echo $this->_tpl_vars['abcbar']; ?>
	
	<!--<?php endif; ?>-->		
	<h2><?php echo $this->_tpl_vars['subheading']; ?>
:</h2>		
	<!--<?php if ($this->_tpl_vars['form_list'] != ""): ?>-->
		<!--<?php $this->assign('category', ""); ?>-->
		<!--<?php if ($this->_tpl_vars['category_count'] == 0): ?>-->	
			<ul class="formlist">
		<!--<?php endif; ?>-->
		<!--<?php $_from = $this->_tpl_vars['form_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['form']):
?>-->
			<!--<?php if ($this->_tpl_vars['form']['ca_name'] != "" && $this->_tpl_vars['form']['ca_name'] != $this->_tpl_vars['category']): ?>-->		
				<!--<?php if ($this->_tpl_vars['category'] != ""): ?>-->
					</ul>
				<!--<?php endif; ?>-->
				<h2 class="category"><?php echo $this->_tpl_vars['form']['ca_name']; ?>
</h2>
				<ul class="formlist">
			<!--<?php endif; ?>-->
			<li>
				<img src="<?php echo $this->_tpl_vars['form']['icon']; ?>
" class="fileicon"/>
				<a href="<?php echo $this->_tpl_vars['form']['url']; ?>
" target="_blank"><?php echo $this->_tpl_vars['form']['name']; ?>
&nbsp;</a><br />		
				<!--<?php if ($this->_tpl_vars['form']['descr'] != ""): ?>-->					
					<?php echo $this->_tpl_vars['form']['descr']; ?>
				
				<!--<?php endif; ?>-->									
				<!--<?php if ($this->_tpl_vars['form']['services'] != ""): ?>-->					
					<?php echo $this->_tpl_vars['assigned_services']; ?>
:<br />					
					<ul>					
					<!--<?php $_from = $this->_tpl_vars['form']['services']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['service']):
?>-->						
						<li><a href="<?php echo $this->_tpl_vars['service']['link']; ?>
"><?php echo $this->_tpl_vars['service']['name']; ?>
</a></li>					
					<!--<?php endforeach; endif; unset($_from); ?>-->						
					</ul>				
				<!--<?php endif; ?>--><br />			
			</li>
			<!--<?php $this->assign('category', $this->_tpl_vars['form']['ca_name']); ?>-->
		<!--<?php endforeach; endif; unset($_from); ?>-->		
		<!--<?php if ($this->_tpl_vars['category_count'] == 0): ?>-->
			</ul>
		<!--<?php endif; ?>-->
	<!--<?php endif; ?>-->	
	<?php echo $this->_tpl_vars['pagebar']; ?>

</div><!-- centrecontent end -->