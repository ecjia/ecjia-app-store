<?php defined('IN_ECJIA') or exit('No permission resources.');?>
<!-- {extends file="ecjia.dwt.php"} -->

<!-- {block name="footer"} -->
<script type="text/javascript">
	ecjia.admin.store_lock.init();
</script>
<!-- {/block} -->

<!-- {block name="main_content"} -->

<style>
	.unlock{
		background:#62c462 -moz-linear-gradient(center bottom , #62c462, #51a351) repeat scroll 0 0;
	}
</style>

<div>
	<h3 class="heading">
		{if $ur_here}{$ur_here}{/if}
		{if $action_link}
		<a href="{$action_link.href}" class="btn data-pjax"  style="float:right;margin-top:-3px;"><i class="fontello-icon-reply"></i>{$action_link.text}</a>
		{/if}
	</h3>
</div>

<form class="form-horizontal"  name="theForm" action="{$form_action}" method="post"  enctype="multipart/form-data">
	<fieldset>
	{if $status eq 1}
	<div class="errorpage alert alert-danger">
		<strong>温馨提示：</strong>{t}锁定入驻商家后，该商家将不能进行以下操作{/t}
		<p>1、</p>
		<p>2、</p>
		<p>3、</p>
		<input type="hidden" name="store_id" value="{$store_id}"/>
		<input type="hidden" name="status" value="{$status}"/>
		<input type="submit" value="！锁定" class="btn"/>
	</div>	
	{/if}
	{if $status eq 2}
	 <div class="alert alert-info ">
		<strong>温馨提示：</strong>{t}解锁入驻商家后，该商家可以进行以下操作{/t}
		<p>1、</p>
		<p>2、</p>
		<p>3、</p>
		<input type="hidden" name="store_id" value="{$store_id}"/>
		<input type="hidden" name="status" value="{$status}"/>
		<input type="submit" value="！解锁" class="btn"/>
	</div>	 
	{/if}
	</fieldset>
</form>   
<!-- {/block} -->