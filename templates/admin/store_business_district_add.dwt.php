<?php defined('IN_ECJIA') or exit('No permission resources.');?>
<script type="text/javascript">
	ecjia.admin.store_business_city.init();
</script>
<div class="modal-header">
	<button class="close" data-dismiss="modal">×</button>
	<h3>当前操作：<span class="action_title">添加经营地区</span></h3>
</div>
<div class="modal-body">
	<form class="form-horizontal" name="Form"  method="post" action="{url path='store/admin_store_business_city/insert_district'}">
		<div class="control-group formSep">
			<label class="control-label control-label-new">所属经营城市名：</label>
			<div class="controls">
				<span class="parent_name">{$business_city_info.business_city_name}</span>
			</div>
        </div>
		<div class="control-group formSep">
        	<label class="control-label">请选择要添加的地区：</label>
        		<div class="controls controls-new choose_list">
        		<select class="region-summary-district w150" name="district">
        			<option value='0'>{lang key='system::system.select_please'}</option>
        			<!-- {foreach from=$district item=region} -->
        			<option value="{$region.region_id}">{$region.region_name}</option>
        			<!-- {/foreach} -->
        		</select>
        	</div>
        </div>
        <div class="control-group t_c">
			<button class="btn btn-gebo" type="submit">确定</button>
			<input name="city_id" type="hidden" value="{$business_city_info.business_city}" />
		</div>
	</form>
</div>



           
