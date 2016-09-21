<?php defined('IN_ECJIA') or exit('No permission resources.');?>
<!-- {extends file="ecjia.dwt.php"} -->

<!-- {block name="footer"} -->
<script type="text/javascript">

</script>
<!-- {/block} -->

<!-- {block name="main_content"} -->
<div class="main-div">
<form method="post" action="merchants_brand.php" name="theForm" enctype="multipart/form-data" onsubmit="return validate()">
<table cellspacing="0" cellpadding="0" width="100%">
  <tr>
    <td class="label">{$lang.brand_name}</td>
    <td><input type="text" name="brand_name" maxlength="60" value="{$brand.brand_name}" />{$lang.require_field}</td>
  </tr>
  <tr>
    <td class="label">{$lang.site_url}</td>
    <td><input type="text" name="site_url" maxlength="60" size="40" value="{$brand.site_url}" /></td>
  </tr>
  <tr>
    <td class="label"><a href="javascript:showNotice('warn_brandlogo');" title="{$lang.form_notice}">
        <img src="images/notice.gif" width="14" height="14" border="0" alt="{$lang.form_notice}"></a>{$lang.brand_logo}</td>
    <td><input type="file" name="brand_logo" id="logo" size="45">{if $brand.brand_logo neq ""}<input type="button" value="{$lang.drop_brand_logo}" onclick="if (confirm('{$lang.confirm_drop_logo}'))location.href='merchants_brand.php?act=drop_logo&id={$brand.brand_id}'">{/if}
    <br /><span class="notice-span" {if $help_open}style="display:block" {else} style="display:none" {/if} id="warn_brandlogo">
    {if $brand.brand_logo eq ''}
    {$lang.up_brandlogo}
    {else}
    {$lang.warn_brandlogo}
    {/if}
    </span>
    </td>
  </tr>
  <tr>
    <td class="label">{$lang.brand_desc}</td>
    <td><textarea  name="brand_desc" cols="60" rows="4"  >{$brand.brand_desc}</textarea></td>
  </tr>
  <tr>
    <td class="label">{$lang.sort_order}</td>
    <td><input type="text" name="sort_order" maxlength="40" size="15" value="{$brand.sort_order}" /></td>
  </tr>
  <tr>
    <td class="label">{$lang.is_show}</td>
    <td><input type="radio" name="is_show" value="1" {if $brand.is_show eq 1}checked="checked"{/if} /> {$lang.yes}
        <input type="radio" name="is_show" value="0" {if $brand.is_show eq 0}checked="checked"{/if} /> {$lang.no}
        ({$lang.visibility_notes})
    </td>
  </tr>
  <tr>
    <td colspan="2" align="center"><br />
      <input type="submit" class="button" value="{$lang.button_submit}" />
      <input type="reset" class="button" value="{$lang.button_reset}" />
      <input type="hidden" name="ubrand" value="{$ubrand}" />
      <input type="hidden" name="act" value="{$form_action}" />
      <input type="hidden" name="old_brandname" value="{$brand.brand_name}" />
      <input type="hidden" name="id" value="{$brand.brand_id}" />
      <input type="hidden" name="old_brandlogo" value="{$brand.brand_logo}">
    </td>
  </tr>
</table>
</form>
</div>
{insert_scripts files="../js/utils.js,validator.js"}
{literal}
<script language="JavaScript">
<!--
document.forms['theForm'].elements['brand_name'].focus();
onload = function()
{
    // 开始检查订单
    startCheckOrder();
}
/**
 * 检查表单输入的数据
 */
function validate()
{
    validator = new Validator("theForm");
    validator.required("brand_name",  no_brandname);
    validator.isNumber("sort_order", require_num, true);
    return validator.passed();
}
//-->
</script>
{/literal}
<!-- {/block} -->