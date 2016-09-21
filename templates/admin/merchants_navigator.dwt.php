<!-- {if $full_page}-->
{insert_scripts files="../js/utils.js,listtable.js"}
<form method="post" action="" name="listForm">
<div class="list-div" id="listDiv">
<!-- {/if} -->
<table cellspacing='0' cellpadding='0' id='list-table'>
<tr>
	<th>{$lang.item_name}</th>
    <!-- {if $priv_ru eq 1} -->
    <th>{t}商家名称{/t}</th>
    <!-- {/if} -->
    <th>{$lang.item_ifshow}</th>
    <th>{$lang.item_opennew}</th>
    <th>{$lang.item_vieworder}</th>
    <th>{$lang.item_type}</th>
    <th width="60px">{$lang.handler}</th>
</tr>
<!-- {foreach from=$navdb item=val} -->
<tr>
	<td align="center"><!-- {if $val.id} -->{$val.name}<!-- {else} -->&nbsp;<!-- {/if} --></td>
    <!-- {if $priv_ru eq 1} -->
    <td align="center">{if $val.user_name}<font style="color:#F00;">{$val.user_name}</font>{else}<font style="color:#0e92d0;">{t}自营{/t}</font>{/if}</td>
    <!-- {/if} -->
  <td align="center">
   <!-- {if $val.id} -->
   <img src="images/{if $val.ifshow eq '1'}yes{else}no{/if}.gif" onclick="listTable.toggle(this, 'toggle_ifshow', {$val.id})" />
   <!-- {/if} --></td>
  <td align="center">
   <!-- {if $val.id} -->
    <img src="images/{if $val.opennew eq '1'}yes{else}no{/if}.gif" onclick="listTable.toggle(this, 'toggle_opennew', {$val.id})" />
   <!-- {/if} --></td>
  <td align="center"><!-- {if $val.id} --><span onclick="listTable.edit(this, 'edit_sort_order', {$val.id})">{$val.vieworder}</span><!-- {/if} --></td>
  <td align="center"><!-- {if $val.id} -->{$lang[$val.type]}<!-- {/if} --></td>
  <td align="center"><!-- {if $val.id} --><a href="merchants_navigator.php?act=edit&id={$val.id}" title="{$lang.edit}"><img src="images/icon_edit.gif" width="21" height="21" border="0" /></a>
  <a href="merchants_navigator.php?act=del&id={$val.id}" onclick="return confirm('{$lang.ckdel}');" title="{$lang.ckdel}"><img src="images/no.gif" width="21" height="21" border="0" /><!-- {/if} --></a>
  </td>
</tr>
<!-- {foreachelse} -->
<tr><td class="no-records" colspan="10">{$lang.no_records}</td></tr>
<!-- {/foreach} -->
</table>

  <table cellpadding="4" cellspacing="0" style="margin-top:10px;">
    <tr>
      <td align="right">{include file="page.htm"}</td>
    </tr>
  </table>

</div>
</form>
<script type="Text/Javascript" language="JavaScript">
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