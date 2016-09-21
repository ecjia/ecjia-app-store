<?php defined('IN_ECJIA') or exit('No permission resources.');?>
<!-- {extends file="ecjia.dwt.php"} -->

<!-- {block name="footer"} -->
<script type="text/javascript">

</script>
<!-- {/block} -->

<!-- {block name="main_content"} -->

  <table cellpadding="3" cellspacing="1">
    <tr>
      <th>{t}编号{/t}</th>
      <th>{t}橱窗名称{/t}</th>
      <th>{t}设置模板{/t}</th>
      <th>{t}橱窗类型{/t}</th>
      <th>{t}橱窗色调{/t}</th>
      <th>{$lang.sort_order}</th>
      <th>{t}显示{/t}</th>
      <th>{$lang.handler}</th>
    </tr>
    <!-- {foreach from=$win_list item=window} -->
    <tr>
      <td><label><input type="checkbox" name="checkboxes[]" value="{$window.id}" />{$window.id}</label></td>
      <td align="center"><span onclick="javascript:listTable.edit(this, 'edit_win_name', {$window.id})">{$window.win_name|escape:html}</span>
      </td>
      <td align="center">{$window.seller_theme}</td>
      <td align="center">{$window.win_type_name}</td>
      <td align="center"><div style="width:50px; height:30px; background-color:{$window.win_color};"></div></td>
      <td align="center"><span onclick="javascript:listTable.edit(this, 'edit_sort_order', {$window.id})">{$window.win_order}</span></td>
      <td align="center"><img src="images/{if $window.is_show}yes{else}no{/if}.gif" onclick="listTable.toggle(this, 'toggle_show', {$window.id})" /></td>
      <td align="center">
        <a href="merchants_custom.php?act=edit&id={$window.id}" title="{$lang.edit}">{$lang.edit}</a> |
        <!-- {if $window.win_type} -->
        <a href="merchants_custom.php?act=add_win_goods&id={$window.id}" title="{$lang.edit}">{t}添加商品{/t}</a> |
        <!-- {/if} -->
        <a href="javascript:;" onclick="listTable.remove({$window.id}, '{$lang.drop_confirm}')" >{$lang.remove}</a> 
      </td>
    </tr>
    <!-- {foreachelse} -->
    <tr><td class="no-records" colspan="10">{$lang.no_records}</td></tr>
    <!-- {/foreach} -->
  </table>
</div>

<div style="margin-left:18px; margin-top:10px;">
  <input type="hidden" name="act" value="batch" />
  
  <select name="seltype"  onchange="selectAll(this.value);">
    <option value="0" selected="selected">{t}请选择{/t}</option>
  	<option value="1">{t}全选{/t}</option>
    <option value="2">{t}反选{/t}</option>
  </select>

  <select name="type" id="controlAll">
  	<option value="1">{t}删除{/t}</option>
  </select>
  <input type="submit" value="{$lang.button_submit}" id="btnSubmit" name="btnSubmit" class="button" />
</div>
</form>

<script type="text/javascript" language="javascript">
  <!--

  {literal}
  onload = function()
  {
      // 开始检查订单
      startCheckOrder();
  }
  {/literal}
  //-->

function selectAll(val){
	var checklist = document.getElementsByName ("checkboxes[]");
	if(val == 1){
		for(var i=0;i<checklist.length;i++)
		{
			checklist[i].checked = true;
		}
	}else if(val == 2){
		for(var i=0;i<checklist.length;i++)
		{
		  if(checklist[i].checked == false){
			  checklist[i].checked = true;
		  }else{
			  checklist[i].checked = false;
		  }
		}
	}
}
</script>
<!-- {/block} -->