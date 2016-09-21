<?php defined('IN_ECJIA') or exit('No permission resources.');?>
<!-- {extends file="ecjia.dwt.php"} -->


<!-- {block name="footer"} -->
<script type="text/javascript">

</script>
<!-- {/block} -->


<!-- {block name="main_content"} -->
<form method="post" action="" name="listForm">
<!-- start brand list -->
<div class="list-div" id="listDiv">

  <table border="0" cellpadding="0" cellspacing="0">
    <tr>
      <th width="20%">{$lang.brand_name}</th>
      {if $priv_ru eq 1}
      <th>{$lang.goods_steps_name}</th>
      {/if}
      <th>{$lang.site_url}</th>
      <th>{$lang.brand_desc}</th>
      <th>{$lang.sort_order}</th>
      <th>{$lang.is_show}</th>
      <th>{$lang.handler}</th>
    </tr>
    {foreach from=$brand_list item=brand}
    <tr>
      <td class="first-cell">
        <span style="float:right">{$brand.brand_logo}</span>
        <span onclick="javascript:listTable.edit(this, 'edit_brand_name', {$brand.brand_id})">{$brand.brand_name|escape:html}</span>
      </td>
      {if $priv_ru eq 1}
      <td align="center"><font style="color:#F00;">{$brand.user_name}</font></td>
      {/if}
      <td>{$brand.site_url}</td>
      <td align="left">{$brand.brand_desc|truncate:36}</td>
      <td align="center">
      <span onclick="javascript:listTable.edit(this, 'edit_sort_order', {$brand.brand_id})">{$brand.sort_order}</span>
      </td>
      <td align="center">
      <img src="images/{if $brand.is_show}yes{else}no{/if}.gif" onclick="listTable.toggle(this, 'toggle_show', {$brand.brand_id})" />
      </td>
      <td align="center">
        <a href="merchants_brand.php?act=edit&id={$brand.brand_id}" title="{$lang.edit}">{$lang.edit}</a> |
        <a href="javascript:;" onclick="listTable.remove({$brand.brand_id}, '{$lang.drop_confirm}')" title="{$lang.edit}">{$lang.remove}</a> 
      </td>
    </tr>
    {foreachelse}
    <tr><td class="no-records" colspan="11">{$lang.no_records}</td></tr>
    {/foreach}
  </table>

{if $full_page}
<!-- end brand list -->
</div>
</form>

<script type="text/javascript" language="javascript">
  <!--
  listTable.recordCount = {$record_count};
  listTable.pageCount = {$page_count};

  {foreach from=$filter item=item key=key}
  listTable.filter.{$key} = '{$item}';
  {/foreach}

  {literal}
  onload = function()
  {
      // 开始检查订单
      startCheckOrder();
  }
  {/literal}
  //-->
</script>
<!-- {/block} -->