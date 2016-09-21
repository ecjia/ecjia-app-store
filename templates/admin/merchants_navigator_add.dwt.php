{include file="pageheader.htm"}
{insert_scripts files="validator.js"}
<div class="main-div">
<form action="merchants_navigator.php" method="post" name="form" onSubmit="return checkForm();">
<table cellspacing="0" cellpadding="0" width="100%">
<tr>
    <td>{$lang.system_main}</td> <td>
        <select onchange="add_main(this.value);" name="menulist" id="menulist">
            <option value='-'>-</option>
            <!-- {foreach from=$sysmain item=val key=key} -->
                <option value='{$key}'>{if $val.2}{$val.2}{else}{$val.0}{/if}</option>
            <!-- {/foreach} -->
        </select>
    </td>
</tr>
<tr>
    <td>{$lang.item_name}</td> <td><input type="text" name="item_name" value="{$rt.item_name}" id="item_name" size="40" onKeyPress="javascript:key();" /></td>
</tr>
<tr>
    <td><a href="javascript:showNotice('notice_url');" title="{$lang.notice_url}"><img src="images/notice.gif" width="14" height="14" border="0" alt="{$lang.form_notice}">{$lang.item_url}</a></td> <td><input type="text" name="item_url" value="{$rt.item_url}" id="item_url" size="40" onKeyPress="javascript:key();" /></td>
</tr>
  <tr>
    <td></td>
    <td>
<span class="notice-span" {if $help_open}style="display:block" {else} style="display:none" {/if} id="notice_url">{$lang.notice_url}</span></td>
  </tr>
<tr>
    <td>{$lang.item_catId}</td> <td><input type="text" name="item_catId" value="{$rt.item_catId}" size="40" /></td>
</tr>
<tr>
    <td>{$lang.item_vieworder}</td> <td><input type="text" name="item_vieworder" value="{$rt.item_vieworder}" size="40" /></td>
</tr>
<tr>
    <td>{$lang.item_ifshow}</td> <td><select name="item_ifshow">
  <option value='1' {$rt.item_ifshow_1}>{$lang.yes}</option><option value='0' {$rt.item_ifshow_0}>{$lang.no}</option>
  </select></td>
</tr>
<tr>
    <td>{$lang.item_opennew}</td> <td><select name="item_opennew">
  <option value='0' {$rt.item_opennew_0}>{$lang.no}</option><option value='1' {$rt.item_opennew_1}>{$lang.yes}</option>
  </select></td>
</tr>
<tr>
    <td>{$lang.item_type}</td> 
    <td>
    	<select name="item_type">
		<option value='middle' {$rt.item_type_middle}>{$lang.middle}</option>
  		</select>
    </td>
</tr>
<tr align="center">
  <td colspan="2">
    <input type="hidden"  name="id"       value="{$rt.id}" />
    <input type="hidden"  name="step"       value="2" />
    <input type="hidden"  name="act"       value="{$rt.act}" />
    <input type="submit" class="button" name="Submit"       value="{$lang.button_submit}" />
  </td>
</tr>
</table>
</form>
</div>
<script type="Text/Javascript" language="JavaScript">
var last;
function add_main(key)
{
    var sysm = new Object;
    {foreach from=$sysmain item=val key=key}
      sysm[{$key}] = new Array();
        sysm[{$key}][0] = '{$val.0}';
        sysm[{$key}][1] = '{$val.1}';
    {/foreach}
    if (key != '-')
    {
        if(sysm[key][0] != '-')
        {
            document.getElementById('item_name').value = sysm[key][0];
            document.getElementById('item_url').value = sysm[key][1];
            last = document.getElementById('menulist').selectedIndex;
        }
        else
        {
            if(last < document.getElementById('menulist').selectedIndex)
            {
                document.getElementById('menulist').selectedIndex ++;
            }
            else
            {
                document.getElementById('menulist').selectedIndex --;
            }
            last = document.getElementById('menulist').selectedIndex;
            document.getElementById('item_name').value = sysm[last-1][0];
            document.getElementById('item_url').value = sysm[last-1][1];
        }
    }
    else
    {
        last = document.getElementById('menulist').selectedIndex = 1;
        document.getElementById('item_name').value = sysm[last-1][0];
        document.getElementById('item_url').value = sysm[last-1][1];
    }
}
function checkForm()
{
    if(document.getElementById('item_name').value == '')
    {
        alert('{$lang.namecannotnull}');
        return false;
    }
    if(document.getElementById('item_url').value == '')
    {
        alert('{$lang.linkcannotnull}');
        return false;
    }
    return true;
}

function key()
{
    last = document.getElementById('menulist').selectedIndex = 0;
}
<!--
{literal}
onload = function()
{
  // 开始检查订单
  startCheckOrder();
}
//-->
</script>
{include file="pagefooter.htm"}