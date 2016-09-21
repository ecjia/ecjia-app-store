<div class="span12">
	<table class="table table-condensed">
		<thead>
			<tr>
				<th>序号</th>
				<th>一级类目</th>
				<th>二级类目</th>
				<th>操作</th>
			</tr>
		</thead>
		<tbody>
			<!-- {foreach from=$category_info item=category key=k} -->
			<tr>
				<td>
					<span class="index">{$k}</span>
					<input type="hidden" value="{$category.cat_id}" name="cat_id[]" class="cId">
				</td>
				<td>
					<input type="hidden" value="{$category.parent_name}" name="parent_name[]" class="cl1Name">{$category.parent_name}
				</td>
				<td>
					<input type="hidden" value="{$category.cat_name}" name="cat_name[]" class="cl2Name"> {$category.cat_name}
				</td>
				<td align="center">
					<a class="ecjiafc-red removeDetailCategoryBtn" href='{url path="seller/admin/deleteChildCate_checked" args="ct_id={$category.ct_id}&user_id={$user_id}&cat_id={$category.parent_id}"}' data-msg="您确定要删除该类目吗？"><i class="fontello-icon-trash"></i></a>
				</td>
			</tr>
			<!-- {foreachelse} -->
			<tr>
				<td class="dataTables_empty" colspan="4">{t}没有找到任何记录!{/t}</td>
			</tr>
			<!-- {/foreach} -->
		</tbody>
	</table>
</div>
<script type="text/javascript">
	ecjia.admin.seller.toggle();
</script>