<?php defined('IN_ECJIA') or exit('No permission resources.');?>
<!-- {extends file="ecjia.dwt.php"} -->
<!-- {block name="footer"} -->
<script type="text/javascript">
	ecjia.admin.store_list.init();
	ecjia.admin.store_edit.init();
</script>
<!-- {/block} -->
<!-- {block name="main_content"} -->
<div>
	<h3 class="heading">
		<!-- {if $ur_here}{$ur_here}{/if} -->
	</h3>
</div>
	
<div class="row-fluid" >
	<div class="choose_list">
		<form class="f_r form-inline" action="{$search_action}" name="searchForm" method="post">
			<input type="text" name="keywords" placeholder="{lang key='store::store.pls_name'}" value="{$store_list.filter.keywords}"/>
			<input class="btn search_store" type="submit" value="{lang key='store::store.serach'}"/>
	  	</form>
  	</div>
</div>
	
<div class="row-fluid">
	<div class="span12">
		<table class="table table-striped smpl_tbl table-hide-edit">
			<thead>
			  	<tr>
					<th class="w50">{lang key='store::store.id'}</th>
				    <th class="w100">{lang key='store::store.store_title'}</th>
				    <th class="w100">{lang key='store::store.store_cat'}</th>
				    <th class="w100">{lang key='store::store.person'}</th>
				    <th class="w200">{lang key='store::store.companyname'}</th>
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
					      	<a class="data-pjax" href='{if $list.status eq 1}{RC_Uri::url("store/admin/lock", "store_id={$list.store_id}&status={$list.status}")} {else} {RC_Uri::url("store/admin/unlock", "store_id={$list.store_id}&status={$list.status}")} {/if}' title="{if $list.status eq 1}{lang key='store::store.lock'}{else}{lang key='store::store.unlock'}{/if}">{if $list.status eq 1}{lang key='store::store.lock'}{else}{lang key='store::store.unlock'}{/if}</a>&nbsp;|&nbsp; 
					     	<a class="data-pjax" href='{RC_Uri::url("store/admin/commission", "store_id={$list.store_id}")}' title="{lang key='store::store.commission'}">{lang key='store::store.commission'}</a>&nbsp;|&nbsp;  
					     	<a class="data-pjax" href='{RC_Uri::url("store/admin/preview", "store_id={$list.store_id}")}' title="{lang key='store::store.view'}">{lang key='store::store.view'}</a>
					     </div>
					</td>
					<td>{$list.cat_name}</td>
					<td>{$list.responsible_person}</td>
					<td>{$list.company_name}</td>
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