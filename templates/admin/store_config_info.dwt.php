<?php defined('IN_ECJIA') or exit('No permission resources.');?>
<!-- {extends file="ecjia.dwt.php"} -->

<!-- {block name="footer"} -->
<script type="text/javascript">
	ecjia.admin.admin_config.init();
</script>
<!-- {/block} -->

<!-- {block name="main_content"} -->

<div class="alert alert-info">
	<a class="close" data-dismiss="alert">×</a>
	<strong>温馨提示：</strong>{t}这里的后台设置仅限改善商家后台的显示效果。{/t}
</div>

<div>
	<h3 class="heading">
		{if $ur_here}{$ur_here}{/if}
		{if $action_link}
		<a href="{$action_link.href}" class="btn data-pjax"  style="float:right;margin-top:-3px;"><i class="fontello-icon-reply"></i>{$action_link.text}</a>
		{/if}
	</h3>
</div>

<form class="form-horizontal"  name="theForm" action="{$form_action}" method="post"  enctype="multipart/form-data" >
	<fieldset>
		<div class="control-group formSep">
			<label class="control-label">{t}后台名称：{/t}</label>
			<div class="controls ">
				<input name="merchante_admin_cpname" id="merchante_admin_cpname" type="text" value="{$config_cpname}"/>
			</div>
		</div>

		<div class="control-group formSep">
			<label class="control-label">{t}登录Logo：{/t}</label>
			<div class="controls">
				<div class="fileupload {if $config_logo}fileupload-exists{else}fileupload-new{/if}" data-provides="fileupload">
					<div class="fileupload-preview thumbnail fileupload-exists" style="width: 50px; height: 50px; line-height: 50px;">
						<img src="{$config_logoimg}" alt="{t}预览图片{/t}" />
					</div>
					<span class="btn btn-file">
					<span class="fileupload-new">{t}浏览{/t}</span>
					<span class="fileupload-exists">{t}修改{/t}</span>
					<input type="file" name="merchante_admin_login_logo"/>
					</span>
					<a class="btn fileupload-exists" data-toggle="removefile" data-msg="{t}您确定要删除此文件吗？{/t}" data-href="{RC_Uri::url('seller/admin_config/del')}" {if $config_logo}data-removefile="true"{/if}>{t}删除{/t}</a>
				</div>
			</div>
		</div>
		
		<div class="control-group">
			<div class="controls">
				<input type="submit" value="确定" class="btn btn-gebo" />
			</div>
		</div>	 

	</fieldset>
</form>
	        
<!-- {/block} -->