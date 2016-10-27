<?php defined('IN_ECJIA') or exit('No permission resources.');?>
<!-- {extends file="ecjia.dwt.php"} -->
<!-- {block name="footer"} -->
<script type="text/javascript">
	ecjia.admin.store_edit.init();
</script>
<!-- {/block} -->

<!-- {block name="main_content"} -->
<style>
.table thead th{ background-color:#F5F5F5}
</style>
<div>
	<h3 class="heading">
		<!-- {if $ur_here}{$ur_here}{/if} -->
		<!-- {if $action_link} -->
		<a class="data-pjax btn plus_or_reply" id="sticky_a" href="{$action_link.href}"><i class="fontello-icon-reply"></i>{$action_link.text}</a>
		<!-- {/if} -->
	</h3>
</div>

<div class="row-fluid">
	<div class="span12">
		<div class="foldable-list move-mod-group">		
		<div class="accordion-group">
			<div class="accordion-heading accordion-heading-url">
				<div class="accordion-toggle acc-in" data-toggle="collapse" data-target="#info">
					<strong>店铺信息</strong>
				</div>
			</div>
			<div class="accordion-body in collapse" id="info">
				<table class="table table-oddtd m_b0">
					<tbody class="first-td-no-leftbd">
					<tr>
						<td><div align="right"><strong>{lang key='store::store.store_title_lable'}</strong></div></td>
						<td><strong>{$store.merchants_name}</strong>
						{if $store.identity_status eq 2}<span class="label label-success m_l10">已认证</span>{else}<span class="label m_l10">未认证</span>{/if}
						{if $store.status eq 2}<span class="label label-important m_l10">锁定</span>{/if}</td>
						<td><div align="right"><strong>{lang key='store::store.store_cat_lable'}</strong></div></td>
						<td>{if $store.cat_name eq ''}未分类{else}{$store.cat_name}{/if}</td>
					</tr>
					<tr>
						<td><div align="right"><strong>{lang key='store::store.store_keywords_lable'}</strong></div></td>
						<td>{$store.shop_keyword}</td>
						<td><div align="right"><strong>{lang key='store::store.apply_time_lable'}</strong></div></td>
				        <td>{$store.apply_time}</td>
					</tr>
					<tr>
					    <td><div align="right"><strong>{lang key='store::store.contact_lable'}</strong></div></td>
						<td>{$store.contact_mobile}</td>
						<td><div align="right"><strong>{lang key='store::store.email_lable'}</strong></div></td>
						<td>{$store.email}</td>
					</tr>
					<tr>
						<td><div align="right"><strong>所在地区：</strong></div></td>
						<td>{$store.province}&nbsp;{$store.city}&nbsp;{$store.district}</td>
						<td><div align="right"><strong>经纬度：</strong></div></td>
						<td>{$store.longitude}&nbsp;&nbsp;{$store.latitude}</td>
					</tr>
					<tr>
						<td><div align="right"><strong>{lang key='store::store.address_lable'}</strong></div></td>
						<td colspan="3">{$store.address}{if $store.longitude && $store.latitude}&nbsp;&nbsp;<a href="http://api.map.baidu.com/marker?location={$store.latitude},{$store.longitude}&title=我的位置&content={$store.merchants_name}&output=html" title="查看地图" target="_blank">[查看地图]</a>{/if}</td>
					</tr>
					</tbody>
				</table>
			</div>
		</div>
		
		<div class="accordion-group">
			<div class="accordion-heading">
				<div class="accordion-heading accordion-heading-url">
					<div class="accordion-toggle acc-in" data-toggle="collapse" data-target="#info2">
						<strong>经营主体信息</strong>
					</div>
				</div>
			</div>
			<div class="accordion-body in collapse" id="info2">
				<table class="table table-oddtd m_b0">
					<tbody class="first-td-no-leftbd">
					{if $store.validate_type eq 1}
					<tr>
						<td><div align="right"><strong>{lang key='store::store.validate_type'}</strong></div></td>
						<td>{if $store.validate_type eq 1}{lang key='store::store.personal'}{else}{lang key='store::store.company'}{/if}</td>
						<td><div align="right"><strong>负责人:</strong></div></td>
						<td>{$store.responsible_person}</td>
					</tr>

					<tr>
						<td ><div align="right"><strong>{lang key='store::store.identity_type_lable'}</strong></div></td>
						{if $store.identity_type eq 1}
						<td>{lang key='store::store.people_id'}</td>
						{elseif $store.identity_type eq 2}
						<td>{lang key='store::store.passport'}</td>
						{elseif $store.identity_type eq 3}
						<td>{lang key='store::store.hong_kong_and_macao_pass'}</td>
						{else}
						<td></td>
						{/if}
						<td><div align="right"><strong>{lang key='store::store.identity_number_lable'}</strong></div></td>
						<td>{$store.identity_number}</td>
					</tr>
					{elseif $store.validate_type eq 2}
					<tr>
						<td><div align="right"><strong>{lang key='store::store.validate_type'}</strong></div></td>
						<td>{if $store.validate_type eq 1}{lang key='store::store.personal'}{else}{lang key='store::store.company'}{/if}</td>
						<td><div align="right"><strong>{lang key='store::store.person_lable'}</strong></div></td>
						<td>{$store.responsible_person}</td>
					</tr>

					<tr>
					    <td><div align="right"><strong>{lang key='store::store.companyname_lable'}</strong></div></td>
						<td>{$store.company_name}</td>
						<td><div align="right"><strong>{lang key='store::store.business_licence_lable'}</strong></div></td>
						<td >{$store.business_licence}</td>
					</tr>

					<tr>
						<td><div align="right"><strong>{lang key='store::store.identity_type_lable'}</strong></div></td>
						{if $store.identity_type eq 1}
						<td>{lang key='store::store.people_id'}</td>
						{elseif $store.identity_type eq 2}
						<td>{lang key='store::store.passport'}</td>
						{elseif $store.identity_type eq 3}
						<td>{lang key='store::store.hong_kong_and_macao_pass'}</td>
						{else}
						<td></td>
						{/if}
						<td><div align="right"><strong>{lang key='store::store.identity_number_lable'}</strong></div></td>
						<td>{$store.identity_number}</td>
					</tr>
					{/if}
					<tr>
					    <td><div align="right"><strong>认证状态：</strong></div></td>
						<td colspan="3">{if $store.identity_status eq 0}待认证
						{else if $store.identity_status eq 1}认证中
						{else if $store.identity_status eq 2}认证通过
						{else if $store.identity_status eq 3}<span class="ecjiafc_red m_l10">不通过</span>{/if}
						</td>
					</tr>
					</tbody>
				</table>
			</div>
		</div>
		
		{if !$store.store_id}
		<div class="accordion-group">
			<div class="accordion-heading">
				<div class="accordion-heading accordion-heading-url">
					<div class="accordion-toggle acc-in" data-toggle="collapse" data-target="#merchant_bank">
						<strong>银行账户信息</strong>
					</div>
				</div>
			</div>

			<div class="accordion-body in collapse" id="merchant_bank">
				<table class="table table-oddtd m_b0">
					<tbody class="first-td-no-leftbd">
					<tr>
						<td><div align="right"><strong>{lang key='store::store.bank_name_lable'}</strong></div></td>
						<td>{$store.bank_name}</td>
						<td><div align="right"><strong>{lang key='store::store.bank_branch_name_lable'}</strong></div></td>
						<td>{$store.bank_branch_name}</td>
					</tr>
					<tr>
						<td><div align="right"><strong>{lang key='store::store.bank_account_number_lable'}</strong></div></td>
						<td>{$store.bank_account_number}</td>
						<td><div align="right"><strong>{lang key='store::store.bank_account_name_label'}</strong></div></td>
						<td>{$store.bank_account_name}</td>
					</tr>
					<tr>
						<td><div align="right"><strong>{lang key='store::store.bank_address_lable'}</strong></div></td>
						<td colspan="3">{$store.bank_address}</td>
					</tr>
					</tbody>
				</table>
			</div>
		</div>
		{/if}
		
		<div class="accordion-group">
			<div class="accordion-heading">
				<div class="accordion-heading accordion-heading-url">
					<div class="accordion-toggle acc-in" data-toggle="collapse" data-target="#identity_pic">
						<strong>证件电子版</strong>
					</div>
				</div>
			</div>

			<div class="accordion-body in collapse" id="identity_pic">
				<table class="table table-oddtd m_b0">
					<tbody class="first-td-no-leftbd">
					<tr>
						<td><div align="right"><strong>{lang key='store::store.identity_pic_front_lable'}</strong></div></td>
						<td>
							{if $store.identity_pic_front neq ''}
							<a href="{RC_Upload::upload_url({$store.identity_pic_front})}" title="点击查看大图" target="_blank"><img class="w200 h120 thumbnail"  class="img-polaroid" src="{RC_Upload::upload_url({$store.identity_pic_front})}"></a>
							{else}
							<div class="l_h30">
								{lang key='store::store.no_upload'}
							</div>
							{/if}
						</td>
					</tr>
					<tr>
						<td><div align="right"><strong>{lang key='store::store.identity_pic_back_lable'}</strong></div></td>
						<td>
							{if $store.identity_pic_back neq ''}
							<a href="{RC_Upload::upload_url({$store.identity_pic_back})}" title="点击查看大图" target="_blank"><img class="w200 h120 thumbnail"  class="img-polaroid" src="{RC_Upload::upload_url({$store.identity_pic_back})}"></a>
							{else}
							<div class="l_h30">
								{lang key='store::store.no_upload'}
							</div>
							{/if}
						</td>
					</tr>
					<tr>
						<td><div align="right"><strong>{lang key='store::store.personhand_identity_pic_lable'}</strong></div></td>
						<td>
							{if $store.personhand_identity_pic neq ''}
							<a href="{RC_Upload::upload_url({$store.personhand_identity_pic})}" title="点击查看大图" target="_blank"><img class="w200 h120 thumbnail"  class="img-polaroid" src="{RC_Upload::upload_url({$store.personhand_identity_pic})}"></a>
							{else}
							<div class="l_h30">
								{lang key='store::store.no_upload'}
							</div>
							{/if}
						</td>
					</tr>
					<!-- {if $store.validate_type eq 1} -->
					<input type="hidden"  name="identity_type" value="{$store.validate_type}" />
					<!-- {elseif $store.validate_type eq 2} -->
					<tr>
						<td><div align="right"><strong>{lang key='store::store.business_licence_pic_lable'}</strong></div></td>
						<td>
							{if $store.personhand_identity_pic neq ''}
							<a href="{RC_Upload::upload_url({$store.business_licence_pic})}" title="点击查看大图" target="_blank"><img class="w200 h120 thumbnail"  class="img-polaroid" src="{RC_Upload::upload_url({$store.business_licence_pic})}"></a>
							{else}
							<div class="l_h30">
								{lang key='store::store.no_upload'}
							</div>
							{/if}
						</td>
					</tr>
					<!-- {/if} -->
					</tbody>
				</table>
			</div>
		</div>
		
		<div class="accordion-group">
			<div class="accordion-heading">
				<div class="accordion-heading accordion-heading-url">
					<div class="accordion-toggle acc-in" data-toggle="collapse" data-target="#operate">
						<strong>可执行操作</strong>
					</div>
				</div>
			</div>

			<div class="accordion-body in collapse" id="operate">
				<form class="form-horizontal" id="form-privilege" name="theForm" action="{$form_action}" method="post" enctype="multipart/form-data" >
				<table class="table table-oddtd m_b0">
					<tbody class="first-td-no-leftbd">
					<tr>
						<td><div align="right"><strong>{lang key='store::store.remark_lable'}</strong></div></td>
						<td>
							<textarea class="span12" name="remark" cols="40" rows="2">{$store.remark}</textarea>
						    <input type="hidden"  name="original" value="{$store.remark}" />
						</td>
					</tr>
					<tr>
						<td><div align="right"><strong>{lang key='store::store.check_lable'}</strong></div></td>
						<td>
							<label class="ecjiaf-ib"><input type="radio"  name="check_status" value="1" {if $store.check_status eq 1}checked{/if}><span>{lang key='store::store.check_no'}</span></label>
						    <label class="ecjiaf-ib"><input type="radio"  name="check_status" value="2" {if $store.check_status eq 2}checked{/if}><span>{lang key='store::store.check_yes'}</span></label>
						</td>
					</tr>
					<tr>
						<td><div align="right"><strong>认证：</strong></div></td>
						<td>
							<label class="ecjiaf-ib"><input type="radio"  name="identity_status" value="0" {if $store.identity_status eq 0}checked{/if}><span>待审核</span></label>
    						<label class="ecjiaf-ib"><input type="radio"  name="identity_status" value="3" {if $store.identity_status eq 3}checked{/if}><span>{lang key='store::store.check_no'}</span></label>
    						<label class="ecjiaf-ib"><input type="radio"  name="identity_status" value="2" {if $store.identity_status eq 2}checked{/if}><span>{lang key='store::store.check_yes'}</span></label>
						</td>
					</tr>
					<tr>
						<td><div align="right"><strong>操作：</strong></div></td>
						<td>
        					<input type="hidden"  name="id" value="{$store.id}" />
        					<input type="hidden"  name="store_id" value="{$store.store_id}" />
        					<button class="btn btn-gebo" type="submit">{lang key='store::store.sub_check'}</button>
						</td>
					</tr>
					</tbody>
				</table>
				</form>
			</div>
		</div>
		
	</div>


	<div class="control-group control-group-small">
		<table class="table">
			<thead>
			  	<tr>
				    <th colspan="2" style="font-size:13px;background-color:#fff;">日志记录<a class="f_r data-pjax" href='{RC_Uri::url("store/admin_preaudit/view_log","id={$store.id}")}' style="font-weight:normal">更多</a></th>
			  	</tr>
		  	</thead>
		  	<tbody>
		  	{foreach from=$log_list item=list}
		  		<tr align="center">
			    <td style="padding:8px 0; width:5px; overflow:hidden;"><i class=" fontello-icon-right-dir"></i></td>
			    <td class="center-td" style="border-top:1px solid #e5e5e5; padding-left:0;"><span>{$list.formate_time}，</span><span style="line-height: 170%">{$list.name} {$list.info}</span>
			    {if $list.log}
    			    <table class="table">
                        <thead>
                            <tr>
                            <th>字段</th>
                            <th>旧值</th>
                            <th>新值</th>
                            </tr>
                        </thead>
                        <tbody>
                        <!-- {foreach from=$list.log item=log} -->
                        <tr>
                            <td>{$log.name}</td>
                            <td>{if $log.is_img}{$log.original_data}{else}<code>{$log.original_data}</code>{/if}</td>
                            <td>{if $log.is_img}{$log.new_data}{else}<code>{$log.new_data}</code>{/if}</td>
                        </tr>
                        <!-- {/foreach} -->
                        </tbody>
                    </table>
                    {/if}
			    </td>
			    </tr>
		    {/foreach}
			</tbody>
		</table>
      </div>
	</div>
</div>
<!-- {/block} -->
