<!-- 申请流程基本信息 -->
<!-- {if $cate_list} -->
<!-- {foreach from=$cate_list item=cate} -->
<span><label><input type="checkbox" name="cateChild[]" class="check" value="{$cate.cat_id}">&nbsp;{$cate.cat_name}</label></span>
<!-- {/foreach} -->
<input name="oneCat_id" value="{$cat_id}" id="oneCat_id" type="hidden">
<!-- {else} -->
请选择一级类目
<!-- {/if} -->