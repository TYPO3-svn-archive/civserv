<?php /* Smarty version 2.6.26, created on 2010-04-22 17:40:37
         compiled from typo3conf/ext/civserv/templates/circumstance_tree.tpl.html */ ?>
<!--TYPO3SEARCH_begin-->
<!--TYPO3SEARCH_end-->

<div id="right">
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
	<h1><?php echo $this->_tpl_vars['circumstance_tree_label']; ?>
:</h1>
		<div class="tree">
			<?php echo $this->_tpl_vars['content']; ?>

		</div>
</div><!-- centrecontent end -->