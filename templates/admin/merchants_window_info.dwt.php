<?php defined('IN_ECJIA') or exit('No permission resources.');?>
<!-- {extends file="ecjia.dwt.php"} -->


<div class="main-div">
<!-- {block name="footer"} -->
<script type="text/javascript">

</script>
<!-- {/block} -->


<!-- {block name="main_content"} -->
<!-- <link rel="stylesheet" type="text/css" href="styles/spectrum-master/spectrum.css"> -->
<!-- <script type="text/javascript" src="styles/spectrum-master/jquery-1.9.1.js"></script> -->
<!-- <script type="text/javascript" src="styles/spectrum-master/spectrum.js"></script> -->

<!-- <style type="text/css"> -->
/* td.label{ width:10%} */
<!-- </style> -->

<form method="post" action="merchants_window.php" name="theForm" enctype="multipart/form-data" onsubmit="return validate()">
<table cellspacing="1" cellpadding="3" width="100%">
  <tr>
    <td class="label">{t}橱窗名称{/t}</td>
    <td><input type="text" name="winname" maxlength="60" value="{$seller_win.win_name}" />{$lang.require_field}</td>
  </tr>
  <tr>
    <td class="label">{t}橱窗类型{/t}</td>
    <td>
    	<label><input type="radio" name="wintype" value="0" {if $seller_win.win_type eq 0}checked="checked"{/if} onFocus="changecutom(1);" />自定义内容</label>
        <label><input type="radio" name="wintype" value="1" {if $seller_win.win_type eq 1}checked="checked"{/if} onFocus="changecutom(0);" />商品柜</label>
   </td>
  </tr>
  <tr>
    <td class="label">{t}橱窗样式{/t}</td>
    <td>
    	<select name="win_goods_type">
        	<option value="1" {if $seller_win.win_goods_type eq 1} selected="selected"{/if}>样式一</option>
            <option value="2" {if $seller_win.win_goods_type eq 2} selected="selected"{/if}>样式二</option>
            <option value="3" {if $seller_win.win_goods_type eq 3} selected="selected"{/if}>样式三</option>
            <option value="4" {if $seller_win.win_goods_type eq 4} selected="selected"{/if}>样式四</option>
            <option value="5" {if $seller_win.win_goods_type eq 5} selected="selected"{/if}>样式五</option>
        </select>
   </td>
  </tr>
  <tr>
    <td class="label">{t}橱窗色调{/t}</td>
    <td style="position:relative;">
    	<input type="text" name="wincolor" maxlength="40" size="15" value="{$seller_win.win_color}" id="wincolor" />
        <input type="button" value="选色" class="go_color" />
        <input type='text' id="full" style="display:none"/>
   </td>
  </tr>
  <tr>
    <td class="label">{$lang.sort_order}</td>
    <td><input type="text" name="winorder" maxlength="40" size="15" value="{$seller_win.win_order}" /></td>
  </tr>
  <tr>
    <td class="label">是否显示</td>
    <td><input type="radio" name="isshow" value="1" {if $seller_win.is_show eq 1}checked="checked"{/if} /> {$lang.yes}
        <input type="radio" name="isshow" value="0" {if $seller_win.is_show eq 0}checked="checked"{/if} /> {$lang.no}
        <br/><span class="notice-span">决定是否在店铺首页显示该橱窗</span>
    </td>
  </tr>
  <tr id="cutmedit" style="display:{if $seller_win.win_type eq 1}none{/if}">
    <td class="label">自定义内容</td>
    <td>{$FCKeditor}</td>
  </tr>
  <tr>
    <td colspan="2" align="center"><br />
      <input type="submit" class="button" value="{$lang.button_submit}" />
      <input type="reset" class="button" value="{$lang.button_reset}" />
      <input type="hidden" name="act" value="{$form_action}" />
      <input type="hidden" name="id" value="{$seller_win.id}" />
      <input type="hidden" name="old_navimg" value="{$seller_win.win_img}">
    </td>
  </tr>
</table>
</form>
</div>
{insert_scripts files="../js/utils.js,validator.js"}
{literal}
<script language="JavaScript">
<!--
/**
 * 检查表单输入的数据
 */
function validate()
{
    validator = new Validator("theForm");
    validator.required("winname",'橱窗名称不能为空');
    return validator.passed();
}

function changecutom(type)
{
	var typeradio=document.getElementById('cutmedit');
	if(type)
	{
		typeradio.style.display='';	
	}
	else
	{
		typeradio.style.display='none';	
	}
}

//选色 start
$(function(){
	$('.sp-palette-buttons-disabled').hide();
	
	$('.go_color').click(function(){
		$('.sp-palette-buttons-disabled').show();
	});
	
	$('.sp-choose').click(function(){
		$('.sp-palette-buttons-disabled').hide();
		var sp_color = $('.sp-input').val();
		$('#wincolor').val(sp_color);
	});
})

$("#update").click (function() {
	console.log($("#full").spectrum("option", "palette"));
	$("#full").spectrum("option", "palette", [
		["red", "green", "blue"]    
	]);
});

$("#full").spectrum({
	color: "#FFF",
	flat: true,
	showInput: true,
	className: "full-spectrum",
	showInitial: true,
	showPalette: true,
	showSelectionPalette: true,
	maxPaletteSize: 10,
	preferredFormat: "hex",
	localStorageKey: "spectrum.demo",
	move: function (color) {
		
	},
	palette: [
		["rgb(0, 0, 0)", "rgb(67, 67, 67)", "rgb(102, 102, 102)",
		"rgb(204, 204, 204)", "rgb(217, 217, 217)","rgb(255, 255, 255)"],
		["rgb(152, 0, 0)", "rgb(255, 0, 0)", "rgb(255, 153, 0)", "rgb(255, 255, 0)", "rgb(0, 255, 0)",
		"rgb(0, 255, 255)", "rgb(74, 134, 232)", "rgb(0, 0, 255)", "rgb(153, 0, 255)", "rgb(255, 0, 255)"], 
		["rgb(230, 184, 175)", "rgb(244, 204, 204)", "rgb(252, 229, 205)", "rgb(255, 242, 204)", "rgb(217, 234, 211)", 
		"rgb(208, 224, 227)", "rgb(201, 218, 248)", "rgb(207, 226, 243)", "rgb(217, 210, 233)", "rgb(234, 209, 220)", 
		"rgb(221, 126, 107)", "rgb(234, 153, 153)", "rgb(249, 203, 156)", "rgb(255, 229, 153)", "rgb(182, 215, 168)", 
		"rgb(162, 196, 201)", "rgb(164, 194, 244)", "rgb(159, 197, 232)", "rgb(180, 167, 214)", "rgb(213, 166, 189)", 
		"rgb(204, 65, 37)", "rgb(224, 102, 102)", "rgb(246, 178, 107)", "rgb(255, 217, 102)", "rgb(147, 196, 125)", 
		"rgb(118, 165, 175)", "rgb(109, 158, 235)", "rgb(111, 168, 220)", "rgb(142, 124, 195)", "rgb(194, 123, 160)",
		"rgb(166, 28, 0)", "rgb(204, 0, 0)", "rgb(230, 145, 56)", "rgb(241, 194, 50)", "rgb(106, 168, 79)",
		"rgb(69, 129, 142)", "rgb(60, 120, 216)", "rgb(61, 133, 198)", "rgb(103, 78, 167)", "rgb(166, 77, 121)",
		"rgb(91, 15, 0)", "rgb(102, 0, 0)", "rgb(120, 63, 4)", "rgb(127, 96, 0)", "rgb(39, 78, 19)", 
		"rgb(12, 52, 61)", "rgb(28, 69, 135)", "rgb(7, 55, 99)", "rgb(32, 18, 77)", "rgb(76, 17, 48)"]
	]
});
//选色 end
//-->
</script>
{/literal}
<!-- {/block} -->