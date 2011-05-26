<?php /* Smarty version 2.6.22, created on 2010-02-15 15:08:40
         compiled from typo3conf/ext/civserv/templates/right_top.tpl.html */ ?>
<!--<?php if ($this->_tpl_vars['top15'] != ""): ?>-->
	<h3 class="invisible"><?php echo $this->_tpl_vars['serviceinformation_label']; ?>
:</h3>
	<div id="serviceinformation">
				<strong><?php echo $this->_tpl_vars['frequently_visited_label']; ?>
:</strong><br /><br />
		<ul>
			<!--<?php $_from = $this->_tpl_vars['top15']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
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