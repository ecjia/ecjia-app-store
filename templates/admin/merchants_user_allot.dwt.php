<?php defined('IN_ECJIA') or exit('No permission resources.');?>
<!-- {extends file="ecjia.dwt.php"} -->
<!-- {block name="footer"} -->
<script type="text/javascript">
	ecjia.admin.seller_list.init();
</script>
<!-- {/block} -->
<!-- {block name="main_content"} -->
	<div>
		<h3 class="heading">
			{if $ur_here}{$ur_here}{/if}
			{if $action_link}
			<a href="{$action_link.href}" class="btn data-pjax plus_or_reply"><i class="fontello-icon-reply"></i>{$action_link.text}</a>
			{/if}
		</h3>
	</div>
	<form class="form-horizontal"  name="theForm" action="{$form_action}" method="post"  enctype="multipart/form-data" >
		<fieldset>
			<div class="control-group formSep">
				<label class="control-label">登录名称：</label>
				<div class="controls">
					<input type="text" name="login_name" value="{$user_name}" autocomplete="off"/>
					<span class="input-must">*</span>
				</div>
			</div>
			
			<div class="control-group formSep">
				<label class="control-label">密码：</label>
				<div class="controls">
					<input type="password" name="login_password" value="{$password}" autocomplete="off"/>
				</div>
			</div>
			
			<div class="row-fluid priv_list">
				<div class="control-group formSep">
					<label class="check">
						<input class="" data-toggle="selectall" data-children=".checkbox" type="checkbox" name="checkall" value="checkbox" autocomplete="off" />{t}全选{/t}
					</label>
				</div>
				<!-- {foreach from=$priv_group item=group} -->
				<div class="control-group formSep">
					<div class="check">
						<label><input class="checkbox" name="chkGroup" type="checkbox" value="checkbox" data-toggle="selectall" data-children=".{$group.group_code} .checkbox" autocomplete="off">{$group.group_name}</label>
					</div>
					<div class="controls {$group.group_code}">
						<!-- {foreach from=$group.group_purview item=list} -->
						<div class="choose">
							<label><input class="checkbox" id="{$list.action_code}" name="action_code[]" type="checkbox" value="{$list.action_code}" {if $list.cando eq 1} checked="checked"{/if} title="{$list.relevance}" autocomplete="off" />{$list.action_name}</label>
						</div>
						<!-- {/foreach} -->
					</div>
				</div>
				<!-- {/foreach} -->
				<div class="control-group">
					<label class="check">
						<input class="" data-toggle="selectall" data-children=".checkbox" type="checkbox" name="checkall" value="checkbox" autocomplete="off" />{t}全选{/t}
					</label>
					<div class="controls">
						<input class="btn btn-info" type="submit" name="Submit" value="{t}保存 {/t}" />
						<input type="hidden" name="admin_id" value="{$admin_id}" />
	      				<input type="hidden" name="user_id" value="{$user_id}" />
	      				<input type="hidden" name="seller_id" value="{$seller_id}" />
	      				<input type="hidden" name="shop_id" value="{$shop_id}" />
					</div>
				</div>
			</div>
		</fieldset>
	</form>        
<!-- {/block} -->