<?php /* Smarty version 2.6.26, created on 2010-07-22 14:25:17
         compiled from typo3conf/ext/civserv/templates/employee_list.tpl.html */ ?>
<!--<?php if ($this->_tpl_vars['employee_search'] == '0'): ?>-->
	<!--TYPO3SEARCH_end-->
	<div class="MAA"></div>
<!--<?php endif; ?>-->	
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
			<div class="list">
			<ul>
		<!--<?php $_from = $this->_tpl_vars['employees']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['employee']):
?>-->				
		<!--<?php if ($this->_tpl_vars['employee']['em_datasec'] == '1'): ?>-->
				<li>
					<a href="<?php echo $this->_tpl_vars['employee']['em_url']; ?>
"><?php echo $this->_tpl_vars['employee']['address_long']; ?>

					<!--<?php if ($this->_tpl_vars['employee']['title'] > ''): ?>--><?php echo $this->_tpl_vars['employee']['title']; ?>
<!--<?php endif; ?>-->	
					<?php echo $this->_tpl_vars['employee']['name']; ?>
<!--<?php if ($this->_tpl_vars['employee']['firstname'] > ''): ?>-->,&nbsp;<?php echo $this->_tpl_vars['employee']['firstname']; ?>
<!--<?php endif; ?>-->	
					</a>
					<!--<?php if ($this->_tpl_vars['employee']['orga_name'] > ''): ?>-->(<?php echo $this->_tpl_vars['employee']['orga_name']; ?>
<!--<?php if ($this->_tpl_vars['employee']['pos_name'] > ''): ?>-->: <?php echo $this->_tpl_vars['employee']['pos_name']; ?>
<!--<?php endif; ?>-->)<!--<?php endif; ?>-->
				</li>
		<!--<?php else: ?>-->
				<li>
					<?php echo $this->_tpl_vars['employee']['address_long']; ?>

					<!--<?php if ($this->_tpl_vars['employee']['title'] > ''): ?>--><?php echo $this->_tpl_vars['employee']['title']; ?>
<!--<?php endif; ?>-->
					<?php echo $this->_tpl_vars['employee']['name']; ?>
<!--<?php if ($this->_tpl_vars['employee']['firstname'] > ''): ?>-->,&nbsp;<?php echo $this->_tpl_vars['employee']['firstname']; ?>
<!--<?php endif; ?>-->
					<!--<?php if ($this->_tpl_vars['employee']['orga_name'] > ''): ?>-->(<?php echo $this->_tpl_vars['employee']['orga_name']; ?>
)<!--<?php endif; ?>-->
				</li>
		<!--<?php endif; ?>-->
		<!--<?php endforeach; endif; unset($_from); ?>-->	
			</ul>
			</div>
		<?php echo $this->_tpl_vars['pagebar']; ?>

		<!--<?php if ($this->_tpl_vars['employee_search'] == '0'): ?>-->
			<div class="MAE"></div>
		<!--<?php endif; ?>-->	
		<!--<?php if ($this->_tpl_vars['employee_search'] == '1'): ?>-->
			<!--TYPO3SEARCH_end-->
		<!--<?php endif; ?>-->
</div><!-- centrecontent end -->