<?php defined('IN_ECJIA') or exit('No permission resources.');?>
<!-- {extends file="ecjia.dwt.php"} -->
<!-- {block name="footer"} -->
<script type="text/javascript">
	ecjia.admin.store_list.init();
// 	ecjia.admin.store_edit.init();
</script>
<!-- {/block} -->
<!-- {block name="main_content"} -->
<div>
	<h3 class="heading">
		<!-- {if $ur_here}{$ur_here}{/if} -->
	</h3>
</div>
	
<div class="nav nav-pills">
	<li class="{if !$smarty.get.type}active{/if}">
		<a class="data-pjax" href="{RC_Uri::url('store/admin/init')}{if $store_list.status}&merchant_keywords={$store_list.status}{/if}{if $filter.keywords}&keywords={$filter.keywords}{/if}">{lang key='store::store.preaudit_list'} 
			<span class="badge badge-info">{$filter.count_goods_num}</span>
		</a>
	</li>
	
	<li class="{if $smarty.get.type eq 1}active{/if}">
		<a class="data-pjax" href='{RC_Uri::url("store/admin/init", "type=1{if $store_list.status}&merchant_keywords={$store_list.status}{/if}{if $filter.keywords}&keywords={$filter.keywords}{/if}")}'>{lang key='store::store.unlock'}
			<span class="badge badge-info use-plugins-num">{$filter.count_Unlock}</span>
		</a>
	</li>
	
	<li class="{if $smarty.get.type eq 2}active{/if}">	
		<a class="data-pjax" href='{RC_Uri::url("store/admin/init", "type=2{if $store_list.status}&merchant_keywords={$store_list.status}{/if}{if $filter.keywords}&keywords={$filter.keywords}{/if}")}'>{lang key='store::store.lock'}
			<span class="badge badge-info unuse-plugins-num">{$filter.count_locking}</span>
		</a>
	</li>
	<form class="f_r form-inline" action="{$search_action}{if $smarty.get.type}&type={$smarty.get.type}{/if}" name="searchForm" method="post">
		<input type="text" name="keywords" placeholder="{lang key='store::store.pls_name'}" value="{$store_list.filter.keywords}"/>
		<input class="btn search_store" type="submit" value="{lang key='store::store.serach'}"/>
  	</form>
</div>	

<div class="row-fluid">
	<div class="span12">
		<table class="table table-striped smpl_tbl table-hide-edit">
			<thead>
			  	<tr>
					<th class="w50">{lang key='store::store.id'}</th>
				    <th class="w200">{lang key='store::store.store_title'}</th>
				    <th class="w100">{lang key='store::store.store_cat'}</th>
				    <th class="w200">{lang key='store::store.companyname'}</th>
				    <th class="w150">{lang key='store::store.lable_contact_lable'}</th>
				    <th class="w150">{lang key='store::store.confirm_time'}</th>
				    <th class="w50">{lang key='store::store.sort_order'}</th>
			  	</tr>
			</thead>
			<tbody>
				<!-- {foreach from=$store_list.store_list item=list} -->
				<tr>
					<td>{$list.store_id}</td>
				    <td class="hide-edit-area">
				    	<span>{$list.merchants_name}</span>
				    	<div class="edit-list">
				    		<a class="data-pjax" href='{RC_Uri::url("store/admin/edit", "store_id={$list.store_id}")}' title="{lang key='system::system.edit'}">{lang key='system::system.edit'}</a>&nbsp;|&nbsp;
				    		{if $list.status eq 1}
				    		<a class="data-pjax ecjiafc-red" href='{RC_Uri::url("store/admin/status", "store_id={$list.store_id}&status={$list.status}")}' title="{lang key='store::store.lock'}">{lang key='store::store.lock'}</a>&nbsp;|&nbsp; 
				    		{else}
					      	<a class="data-pjax" href='{RC_Uri::url("store/admin/status", "store_id={$list.store_id}&status={$list.status}")}' title="{lang key='store::store.unlock'}">{lang key='store::store.unlock'}</a>&nbsp;|&nbsp; 
					      	{/if}
					     	<a class="data-pjax " href='{RC_Uri::url("store/admin_commission/edit", "store_id={$list.store_id}&id={$list.id}")}' title="{lang key='store::store.set_commission'}">{lang key='store::store.set_commission'}</a>&nbsp;|&nbsp;
					     	<a class="data-pjax " href='{RC_Uri::url("commission/admin/init", "store_id={$list.store_id}")}' title="结算账单">结算账单</a>&nbsp;|&nbsp;  
					     	<a class="data-pjax" href='{RC_Uri::url("store/admin/preview", "store_id={$list.store_id}")}' title="{lang key='store::store.view'}">{lang key='store::store.view'}</a>
					     </div>
					</td>
					<td>{$list.cat_name}</td>
					<td>{$list.company_name}</td>
					<td>{$list.contact_mobile}</td>
					<td>{$list.confirm_time}</td>
				    <td>{$list.sort_order}</td>
				</tr>
				<!-- {foreachelse} -->
				   <tr><td class="no-records" colspan="10">{lang key='system::system.no_records'}</td></tr>
				<!-- {/foreach} -->
            </tbody>
         </table>
    	<!-- {$store_list.page} -->
	</div>
</div>
<!-- {/block} -->>