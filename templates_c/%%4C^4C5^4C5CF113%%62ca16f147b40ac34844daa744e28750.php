<?php /* Smarty version 2.6.26, created on 2010-04-22 15:53:15
         compiled from typo3conf/ext/civserv/templates/top15.tpl.html */ ?>
<!--TYPO3SEARCH_begin-->
<!--TYPO3SEARCH_end-->
	
<div id="right">
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['right_searchbox_template'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</div><!-- right end -->

<div id="centrecontent">
	<h1><?php echo $this->_tpl_vars['top15_label']; ?>
:</h1>
		<div class="list">
		<ul>
			<!--<?php $_from = $this->_tpl_vars['top15']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['service']):
?>-->				
				<!--<?php if (( $this->_tpl_vars['service']['preview'] == 1 ) && ( $this->_tpl_vars['service']['fe_group'] > 0 )): ?>-->	
					<li class="intern_preview">
						<a class="intern_preview" href="<?php echo $this->_tpl_vars['service']['link']; ?>
"><?php echo $this->_tpl_vars['service']['name']; ?>
</a>
						<!--<?php if ($this->_tpl_vars['service']['number'] != ""): ?>-->(<?php echo $this->_tpl_vars['service']['number']; ?>
)<!--<?php endif; ?>-->
					</li>
				<!--<?php elseif (( $this->_tpl_vars['service']['preview'] == 1 ) && ( $this->_tpl_vars['service']['fe_group'] <= 0 )): ?>-->
					<li class="preview">
						<a class="preview" href="<?php echo $this->_tpl_vars['service']['link']; ?>
"><?php echo $this->_tpl_vars['service']['name']; ?>
</a>
						<!--<?php if ($this->_tpl_vars['service']['number'] != ""): ?>-->(<?php echo $this->_tpl_vars['service']['number']; ?>
)<!--<?php endif; ?>-->
					</li>
				<!--<?php elseif (( $this->_tpl_vars['service']['preview'] !== 1 ) && ( $this->_tpl_vars['service']['fe_group'] > 0 )): ?>-->
					<li class="intern">
						<a class="intern" href="<?php echo $this->_tpl_vars['service']['link']; ?>
"><?php echo $this->_tpl_vars['service']['name']; ?>
</a>
						<!--<?php if ($this->_tpl_vars['service']['number'] != ""): ?>-->(<?php echo $this->_tpl_vars['service']['number']; ?>
)<!--<?php endif; ?>-->
					</li>
				<!--<?php else: ?>-->
					<li>
						<a href="<?php echo $this->_tpl_vars['service']['link']; ?>
"><?php echo $this->_tpl_vars['service']['name']; ?>
</a>
						<!--<?php if ($this->_tpl_vars['service']['number'] != ""): ?>-->(<?php echo $this->_tpl_vars['service']['number']; ?>
)<!--<?php endif; ?>-->
					</li>
				<!--<?php endif; ?>-->				
			<!--<?php endforeach; endif; unset($_from); ?>-->		
		</ul>
	</div>
	<?php echo $this->_tpl_vars['pagebar']; ?>

</div><!-- centrecontent end -->