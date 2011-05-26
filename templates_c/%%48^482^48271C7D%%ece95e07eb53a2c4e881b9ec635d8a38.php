<?php /* Smarty version 2.6.26, created on 2010-07-22 16:20:26
         compiled from typo3conf/ext/civserv/templates/search_result.tpl.html */ ?>
<div id="right">
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['right_top_template'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</div><!-- right end -->

<div id="centrecontent">
	<h1><?php echo $this->_tpl_vars['service_label']; ?>
:&nbsp;<?php echo $this->_tpl_vars['number']; ?>
</h1>

	<!--<?php if ($this->_tpl_vars['searchbox'] != ""): ?>-->
		<div class="content">
			<?php echo $this->_tpl_vars['searchbox']; ?>

		</div>
	<!--<?php endif; ?>-->

	<ul>
	<!--<?php $_from = $this->_tpl_vars['service']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['row']):
?>-->
		<li><a href="<?php echo $this->_tpl_vars['row']['link']; ?>
"><?php echo $this->_tpl_vars['row']['name']; ?>
</a></li>
	<!--<?php endforeach; endif; unset($_from); ?>-->
	</ul>
</div>
	