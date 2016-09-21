<!--申请店铺信息-->
		<table class="table table-bordered table_vam" id="dt_gal">
			<thead>
				<tr>
					<th>类目名称</th>
					<th>资质名称</th>
					<th class="w200">电子版</th>
					<th class="w250">到期日</th>
				</tr>
			</thead>
			<tbody>
        	<!-- {foreach from=$permanent_list item=permanent key=k} -->
            <tr>
            	<td>
                	{$permanent.cat_name}<input type="hidden" value="{$permanent.cat_id}" name="permanentCat_id[{$k}]">
                </td>
                <td>
                    {$permanent.dt_title}<input type="hidden" value="{$permanent.dt_id}" name="permanent_title[{$k}]">
                </td>
                <td>
                	<div class="m_l10 fileupload {if $permanent.permanent_file}fileupload-exists{else}fileupload-new{/if}" data-provides="fileupload">
						<div class="fileupload-preview thumbnail fileupload-exists" style="width:50px;height:50px;line-height:50px;">
							{if $permanent.permanent_file}
							<img src="{$permanent.permanent_file}" alt="图片预览">
							{/if}
						</div>
						<span class="btn btn-file m_l5 m_t15">
							<span class="fileupload-new">{t}浏览{/t}</span>
							<span class="fileupload-exists">{t}修改{/t}</span>
							<input type="file" name="permanentFile[{$k}]" />
							</span>
						<a class="btn fileupload-exists m_t15" {if !$permanent.permanent_file} data-dismiss="fileupload" href="javascript:;" {else}data-toggle="ajaxremove" data-msg="{t}您确定要删除该类目资质电子版吗？{/t}" href='{url path="seller/admin/remove_file" args="type=permanent_file&dtf_id={$permanent.dtf_id}&img={$permanent.permanent_file}"}' title="{t}删除{/t}"{/if}>删除</a>
					</div>
                </td>
                <td>
                    {if $permanent.permanent_date}
                	<input id="categoryId_date_{$permanent.dt_id}" class="permanent_date w150 m_l15" type="text" size="17" value="{$permanent.permanent_date}" name="categoryId_date[{$k}]">
                    <input type="checkbox" class="categoryId_permanent" value="1" name="categoryId_permanent[{$k}]" onClick="get_categoryId_permanent(this, '{$permanent.permanent_date}', {$permanent.dt_id})"> 永久
                    {else}
                    <input id="categoryId_date_{$permanent.dt_id}" class="permanent_date w150 m_l15" type="text" size="17" name="categoryId_date[{$k}]" {if $permanent.cate_title_permanent eq 1}readonly="true"{/if}>
                    <input type="checkbox" class="categoryId_permanent" {if $permanent.cate_title_permanent eq 1}checked{/if} value="1" name="categoryId_permanent[{$k}]" onClick="get_categoryId_permanent(this, '', {$permanent.dt_id})">永久
                    {/if}
                </td>
           	</tr>
            <!-- {foreachelse} -->
            <tr>
				<td class="dataTables_empty" colspan="4">{t}没有找到任何记录!{/t}</td>
			</tr>
			<!-- {/foreach} -->
   		</tbody>            	
	</table>
<script type="text/javascript">
	ecjia.admin.permanent.time();
</script>