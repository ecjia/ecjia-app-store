<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>{$lang.cp_home}{if $ur_here} - {$ur_here} {/if}</title>
<meta name="robots" content="noindex, nofollow">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="styles/general.css" rel="stylesheet" type="text/css" />
<link href="styles/main.css" rel="stylesheet" type="text/css" />
{insert_scripts files="common.js"}
<script language="JavaScript">
<!--
// 这里把JS用到的所有语言都赋值到这里
{foreach from=$lang.js_languages key=key item=item}
var {$key} = "{$item}";
{/foreach}
//-->
</script>
</head>
<body>

<h1>
<!-- {if $action_link} -->
<span class="action-span"><a href="{$action_link.href}">{$action_link.text}</a></span>
<!-- {/if} -->
<!-- {if $action_link2} -->
<span class="action-span"><a href="{$action_link2.href}">{$action_link2.text}</a>&nbsp;&nbsp;</span>
<!-- {/if} -->
<!-- wang 商家入驻-->
<!-- {if $check_failure} -->
<span class="action-span"><a href="{$check_failure.href}" style=" color:  #ff6600">{$check_failure.text}</a></span>
<!-- {/if} -->
<!-- {if $already_check} -->
<span class="action-span"><a href="{$already_check.href}" style=" color: #009999">{$already_check.text}</a></span>
<!-- {/if} -->
<!-- {if $not_check} -->
<span class="action-span"><a href="{$not_check.href}" style=" color:#ff3300">{$not_check.text}</a></span>
<!-- {/if} -->
<!-- wang 商家入驻-->
<span class="action-span1"><a href="index.php?act=main">{$lang.cp_home}</a> </span><span id="search_id" class="action-span1"><!-- {if $ur_here} --> - {$ur_here} <!-- {/if} --></span>
<div style="clear:both"></div>
</h1>