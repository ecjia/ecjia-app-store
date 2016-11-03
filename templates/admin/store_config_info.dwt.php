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
				<input name="merchant_admin_cpname" id="merchant_admin_cpname" type="text" value="{$config_cpname}"/>
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
					<input type="file" name="merchant_admin_login_logo"/>
					</span>
					<a class="btn fileupload-exists" data-toggle="removefile" data-msg="{t}您确定要删除该图片吗？{/t}" data-href="{RC_Uri::url('store/admin_config/del')}&type=logo" {if $config_logo}data-removefile="true"{/if}>{t}删除{/t}</a>
					<span class="help-block">推荐图片的尺寸为：230x50px</span>
				</div>
			</div>
		</div>
		
		<div class="control-group formSep">
			<label class="control-label">{t}新浪微博：{/t}</label>
			<div class="controls ">
				<input name="merchant_admin_weibo" type="text" value="{$config_weibo}"/>
			</div>
		</div>
		
		<div class="control-group formSep">
			<label class="control-label">{t}QQ：{/t}</label>
			<div class="controls ">
				<input name="merchant_admin_qq" type="text" value="{$config_qq}"/>
			</div>
		</div>
		
		<div class="control-group formSep">
			<label class="control-label">{t}微信：{/t}</label>
			<div class="controls">
				<div class="fileupload {if $config_weixin}fileupload-exists{else}fileupload-new{/if}" data-provides="fileupload">
					<div class="fileupload-preview thumbnail fileupload-exists" style="width: 50px; height: 50px; line-height: 50px;">
						<img src="{$config_weixin_logo}" alt="{t}预览图片{/t}" />
					</div>
					<span class="btn btn-file">
					<span class="fileupload-new">{t}浏览{/t}</span>
					<span class="fileupload-exists">{t}修改{/t}</span>
					<input type="file" name="merchant_admin_weixin"/>
					</span>
					<a class="btn fileupload-exists" data-toggle="removefile" data-msg="{t}您确定要删除该图片吗？{/t}" data-href="{RC_Uri::url('store/admin_config/del')}&type=weixin" {if $config_iphone}data-removefile="true"{/if}>{t}删除{/t}</a>
					<span class="help-block">请上传微信公众号二维码</span>
				</div>
			</div>
		</div>
		
		<div class="control-group formSep">
			<label class="control-label">{t}Skype：{/t}</label>
			<div class="controls ">
				<input name="merchant_admin_skype" type="text" value="{$config_skype}"/>
			</div>
		</div>
		
		<div class="control-group formSep">
			<label class="control-label">{t}Html5 App：{/t}</label>
			<div class="controls ">
				<input name="merchant_admin_html5" type="text" value="{$config_html5}"/>
			</div>
		</div>
		
		<div class="control-group formSep">
			<label class="control-label">{t}iPhone App：{/t}</label>
			<div class="controls">
				<div class="fileupload {if $config_iphone}fileupload-exists{else}fileupload-new{/if}" data-provides="fileupload">
					<div class="fileupload-preview thumbnail fileupload-exists" style="width: 50px; height: 50px; line-height: 50px;">
						<img src="{$config_iphone_logo}" alt="{t}预览图片{/t}" />
					</div>
					<span class="btn btn-file">
					<span class="fileupload-new">{t}浏览{/t}</span>
					<span class="fileupload-exists">{t}修改{/t}</span>
					<input type="file" name="merchant_admin_iphone"/>
					</span>
					<a class="btn fileupload-exists" data-toggle="removefile" data-msg="{t}您确定要删除该图片吗？{/t}" data-href="{RC_Uri::url('store/admin_config/del')}&type=iphone" {if $config_iphone}data-removefile="true"{/if}>{t}删除{/t}</a>
					<span class="help-block">请上传iPhone客户端二维码</span>
				</div>
			</div>
		</div>
		
		<div class="control-group formSep">
			<label class="control-label">{t}Android App：{/t}</label>
			<div class="controls">
				<div class="fileupload {if $config_android}fileupload-exists{else}fileupload-new{/if}" data-provides="fileupload">
					<div class="fileupload-preview thumbnail fileupload-exists" style="width: 50px; height: 50px; line-height: 50px;">
						<img src="{$config_android_logo}" alt="{t}预览图片{/t}" />
					</div>
					<span class="btn btn-file">
					<span class="fileupload-new">{t}浏览{/t}</span>
					<span class="fileupload-exists">{t}修改{/t}</span>
					<input type="file" name="merchant_admin_android"/>
					</span>
					<a class="btn fileupload-exists" data-toggle="removefile" data-msg="{t}您确定要删除该图片吗？{/t}" data-href="{RC_Uri::url('store/admin_config/del')}&type=android" {if $config_android}data-removefile="true"{/if}>{t}删除{/t}</a>
					<span class="help-block">请上传Android客户端二维码</span>
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