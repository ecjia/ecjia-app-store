<?php defined('IN_ECJIA') or exit('No permission resources.');?>
<!-- {extends file="ecjia.dwt.php"} -->
<!-- {block name="footer"} -->
<script type="text/javascript">
ecjia.admin.commission.init();
</script>
<!-- {/block} -->
<!-- {block name="main_content"} -->
<div>
	<h3 class="heading">
		<!-- {if $ur_here}{$ur_here}{/if} -->
		<!-- {if $action_link}  -->
		<a class="btn plus_or_reply data-pjax" href="{$action_link.href}" id="sticky_a"><i class="fontello-icon-reply"></i>{$action_link.text}</a>
		<!-- {/if} -->
	</h3>
</div>


<div class="row-fluid">
	<div class="span12">
		<div class="tabbable tabs-left">
		
			<ul class="nav nav-tabs tab_merchants_nav">
				<li><a href='{RC_Uri::url("store/admin/preview","store_id={$smarty.get.store_id}")}' class="pjax" >基本信息</a></li>
				<li class="active"><a href='{RC_Uri::url("store/admin_commission/edit","store_id={$smarty.get.store_id}")}' class="pjax" >设置佣金</a></li>
				<li><a href='{RC_Uri::url("commission/admin/init","store_id={$smarty.get.store_id}")}' class="pjax" >结算账单</a></li>
				<li><a href='{RC_Uri::url("store/admin/view_staff","store_id={$smarty.get.store_id}")}' class="pjax" >查看员工</a></li>
			</ul>
			
			<div class="tab-content tab_merchants">
				<div class="tab-pane active" style="min-height:300px;">
				<form class="form-horizontal" method="post" action='{$form_action}' name="theForm">
					<fieldset>
        				<div class="control-group formSep">
        					<label class="control-label">{t}商家名称：{/t}</label>
        					<div class="controls l_h30">
        						<span>{t}{$merchants_name}{/t}</span>
        					</div>
        				</div>
        				
        				<!-- 显示商家店铺名称 -->
        				
        				<div class="control-group formSep">
        					<label class="control-label">{t}佣金比例：{/t}</label>
        					<div class="controls">
        						<select name="percent_id">
        							<option value="0">{t}请选择{/t}</option>						
        					        <!-- {foreach from=$store_percent item=percent} -->
        					        <option value="{$percent.percent_id}" {if $store_commission.percent_id eq $percent.percent_id} selected="selected" {/if}>{$percent.percent_value}%</option>
        							<!-- {/foreach} -->
        	    				</select>
        	    				<span class="input-must">*</span>
        					</div>				
        				</div>
        				
        				<div class="control-group " >
        					<div class="controls">
        						<input type="submit" value="{t}确定{/t}" class="btn btn-gebo" />
        						<input type="hidden" name="store_id" value="{$store_id}">
        						<input type="hidden" name="id" value="{$id}">
        					</div>
        				</div>		
        			</fieldset>
        		</form>
						
				</div>
			</div>
			
		</div>
	</div>
</div>

<!-- {/block} -->