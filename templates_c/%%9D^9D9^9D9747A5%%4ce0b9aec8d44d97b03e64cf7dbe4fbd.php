<?php /* Smarty version 2.6.22, created on 2010-02-15 15:08:40
         compiled from typo3conf/ext/civserv/templates/community_choice.tpl.html */ ?>
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
<h1><?php echo $this->_tpl_vars['community_choice_label']; ?>
</h1>
	<ul>
	<!--<?php $_from = $this->_tpl_vars['communities']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['community']):
?>-->
		<li><a href="<?php echo $this->_tpl_vars['community']['link']; ?>
"><?php echo $this->_tpl_vars['community']['name']; ?>
</a></li>
	<!--<?php endforeach; endif; unset($_from); ?>-->
	</ul>
</div><!-- centrecontent end -->
