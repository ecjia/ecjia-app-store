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
			<!-- {if $ur_here}{$ur_here}{/if} -->
		</h3>
	</div>
	
	<div class="row-fluid batch" >
		<div class="choose_list">
			<div class="btn-group f_l m_r5">
				<a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
					<i class="fontello-icon-cog"></i>{t}批量操作{/t}
					<span class="caret"></span>
				</a>
				<ul class="dropdown-menu">
					<li><a data-toggle="ecjiabatch" data-idClass=".checkbox:checked" data-url="{$form_action}" data-msg="删除商家将会删除商家所有相关店铺、商品、订单信息，你确定这么做吗？" data-noSelectMsg="请选择要删除的商家" href="javascript:;"><i class="fontello-icon-trash"></i>{t}删除商家{/t}</a></li>		
				</ul>
			</div>
			<form class="f_r form-inline" action="{$search_action}" name="searchForm">
				<input type="text" name="keywords" placeholder="{t}请输入会员名称或店铺名称关键字{/t}" value="{$users_list.filter.keywords}"/>
				<input class="btn" type="submit" value="搜索"/>
		  	</form>
		  	
	  	</div>
	</div>
	
	<div class="row-fluid list-page">
		<table class="table table-striped table-hide-edit">
			<thead>
				<tr>
					<th class="table_checkbox">
						<input type="checkbox" data-toggle="selectall" data-children=".checkbox"/>
					</th>
				    <th class="w200">{t}登录名称（会员名称）{/t}</th>
				    <th>{t}店铺名称{/t}</th>
				    <th class="w70">{t}店铺类型{/t}</th>
				    <th>{t}主营类目{/t}</th>
				    <th class="w100">{t}审核状态{/t}</th>
				    <th class="w100">{t}是否设置佣金{/t}</th>
				 </tr>
			</thead>
			<tbody>
			<!-- {foreach from=$users_list.users_list item=users} -->
			<tr>
				<td><input class="checkbox" type="checkbox" name="checkboxes[]" value="{$users.user_id}"/></td>	
				<td class="hide-edit-area">
					<span class="ecjiaf-pre">{$users.hopeLoginName}{if $users.user_name}（{$users.user_name}）{/if}</span>
					<div class="edit-list">
						{if $users.merchants_audit eq 1}
	      				<a class="data-pjax" href='{url path="seller/admin/allot" args="shop_id={$users.shop_id}"}' title="{$lang.allot_priv}">{t}分派权限{/t}</a>&nbsp;|&nbsp;
	    				{/if}
	    				
	    				{if !$users.server_id && $users.merchants_audit eq 1}
	      				<a class="data-pjax" href='{url path="seller/admin_commission/add" args="shop_id={$users.shop_id}"}'>设置佣金</a>&nbsp;|&nbsp;
	      				{/if}
	      				<a class="data-pjax" href='{url path="seller/admin/edit" args="shop_id={$users.shop_id}"}' title="{$lang.edit}">{t}编辑{/t}</a>
	      			</div>
				</td>
	    		<td>{$users.rz_shopName}</td>
				<td>
					{if $users.shoprz_type eq 1}
					{t}旗舰店{/t}
					{else if $users.shoprz_type eq 2}
					{t}专卖店{/t}
					{else if $users.shoprz_type eq 3}
					{t}专营店{/t}
					{/if}
				</td>
				<td>{$users.cat_name}</td>
	    		<td>
	    			{if $users.steps_audit eq 1}
	    				{if $users.merchants_audit eq 0}
	    					{t}未审核{/t}
	        			{elseif $users.merchants_audit eq 1}
	      					{t}审核已通过{/t}
	        			{elseif $users.merchants_audit eq 2}
	       					{t}审核未通过{/t}
	        			{/if}
	    			{else}
	    				<font style="color:#F90">{t}尚未提交信息{/t}</font>
	    			{/if}    
	    		</td>
	    		<td>
	    			{if !$users.server_id}
	    			{t}未设置{/t}
	    			{else}
	    			{t}已设置{/t}
	    			{/if}
	    		</td>
			</tr>
			<!-- {foreachelse} -->
			<tr>
				<td class="no-records" colspan="10">{t}没有找到任何记录{/t}</td>
			</tr>
			<!-- {/foreach} -->
			</tbody>
	  	</table>
	</div>
	<!-- {$users_list.page} -->
<!-- {/block} -->