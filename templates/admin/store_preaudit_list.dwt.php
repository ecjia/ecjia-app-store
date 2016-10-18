<?php defined('IN_ECJIA') or exit('No permission resources.');?>
<!-- {extends file="ecjia.dwt.php"} -->
<!-- {block name="footer"} -->
<script type="text/javascript">
	ecjia.admin.store_list.init();
</script>
<!-- {/block} -->
<!-- {block name="main_content"} -->
<div>
	<h3 class="heading">
		<!-- {if $ur_here}{$ur_here}{/if} -->
	</h3>
</div>

<!-- <div class="row-fluid" > -->
	<ul class="nav nav-pills">
		<li class="{if !$smarty.get.type}active{/if}">
			<a class="data-pjax" href="{RC_Uri::url('goods/admin/init')}{if $filter.cat_id}&cat_id={$filter.cat_id}{/if}{if $filter.brand_id}&brand_id={$filter.brand_id}{/if}{if $filter.intro_type}&intro_type={$filter.intro_type}{/if}{if $filter.merchant_keywords}&merchant_keywords={$filter.merchant_keywords}{/if}{if $filter.keywords}&keywords={$filter.keywords}{/if}">{lang key='store::store.preaudit_list'} 
				<span class="badge badge-info">{$goods_list.filter.count_goods_num}</span>
			</a>
		</li>
		
		<li class="{if $smarty.get.type eq 1}active{/if}">
			<a class="data-pjax" href='{RC_Uri::url("goods/admin/init", "type=1{if $filter.cat_id}&cat_id={$filter.cat_id}{/if}{if $filter.brand_id}&brand_id={$filter.brand_id}{/if}{if $filter.intro_type}&intro_type={$filter.intro_type}{/if}{if $filter.merchant_keywords}&merchant_keywords={$filter.merchant_keywords}{/if}{if $filter.keywords}&keywords={$filter.keywords}{/if}")}'>{lang key='store::store.check'}
				<span class="badge badge-info use-plugins-num">{$goods_list.filter.count_on_sale}</span>
			</a>
		</li>
		
		<li class="{if $smarty.get.type eq 2}active{/if}">	
			<a class="data-pjax" href='{RC_Uri::url("goods/admin/init", "type=2{if $filter.cat_id}&cat_id={$filter.cat_id}{/if}{if $filter.brand_id}&brand_id={$filter.brand_id}{/if}{if $filter.intro_type}&intro_type={$filter.intro_type}{/if}{if $filter.merchant_keywords}&merchant_keywords={$filter.merchant_keywords}{/if}{if $filter.keywords}&keywords={$filter.keywords}{/if}")}'>{lang key='store::store.not_check'}
				<span class="badge badge-info unuse-plugins-num">{$goods_list.filter.count_not_sale}</span>
			</a>
		</li>
  	</ul>
		
	<div class="row-fluid batch">
		<form class="f_r form-inline" action="{$search_action}" name="searchForm" method="post">
			<input type="text" name="keywords" placeholder="{lang key='store::store.pls_name'}" value="{$store_list.filter.keywords}"/>
			<input class="btn search_store" type="submit" value="{lang key='store::store.serach'}"/>
	  	</form>
		<div class="btn-group f_l m_r5">
			<form class="f_r form-inline" action="{RC_Uri::url('goods/admin/init')}{if $smarty.get.type}&type={$smarty.get.type}{/if}" method="post" name="filterForm">
				<div class="screen f_r">
					<!-- 审核 -->
					<select class="w100" name="cat_id">
						<option value="0">{lang key='store::store.preaudit_list'}</option>
						<!-- {$cat_list} -->
					</select>
					<button class="btn screen-btn" type="button">{lang key='store::store.filter'}</button>
				</div>
			</form> 
	 	</div>
  	</div>
<!-- </div> -->
  	
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
				    <th class="w150">{lang key='store::store.apply_time'}</th>
			  	</tr>
			</thead>
			<tbody>
				<!-- {foreach from=$store_list.store_list item=list} -->
				<tr>
					<td>{$list.id}</td>
				    <td class="hide-edit-area">
				    	<span>{$list.merchants_name}</span>
				    	<div class="edit-list">
				    		<a class="data-pjax" href='{RC_Uri::url("store/admin_preaudit/edit", "id={$list.id}")}' title="{lang key='system::system.edit'}">{lang key='system::system.edit'}</a>&nbsp;|&nbsp;
					      	<a class="data-pjax" href='{RC_Uri::url("store/admin_preaudit/check", "id={$list.id}")}' title="{lang key='store::store.check'}">{lang key='store::store.check'}</a>
					     </div>
					</td>
					<td>{$list.cat_name}</td>
					<td>{$list.responsible_person}</td>
					<td>{$list.company_name}</td>
					<td>{$list.apply_time}</td>
				</tr>
				<!-- {foreachelse} -->
				   <tr><td class="no-records" colspan="10">{lang key='system::system.no_records'}</td></tr>
				<!-- {/foreach} -->
            </tbody>
         </table>
    	<!-- {$store_list.page} -->
	</div>
</div>
<!-- {/block} -->