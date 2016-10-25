<?php defined('IN_ECJIA') or exit('No permission resources.');?>
<!-- {extends file="ecjia.dwt.php"} -->
<!-- {block name="footer"} -->
<script type="text/javascript">
	ecjia.admin.store_edit.init();
</script>
<!-- {/block} -->

<!-- {block name="main_content"} -->
<div>
	<h3 class="heading">
		<!-- {if $ur_here}{$ur_here}{/if} -->
		<!-- {if $action_link} -->
		<a class="data-pjax btn plus_or_reply" id="sticky_a" href="{$action_link.href}"><i class="fontello-icon-reply"></i>{$action_link.text}</a>
		<!-- {/if} -->
	</h3>
</div>

<div class="row-fluid">
	<form method="post" class="form-horizontal" action="{$form_action}" name="theForm" enctype="multipart/form-data">
		<div class="span12">
			<div class="tabbable tabs-left">
				<ul class="nav nav-tabs tab_merchants_nav">
					<li class="active"><a href="#tab1" data-toggle="tab">基本信息</a></li>
					<li><a href='{RC_Uri::url("store/admin_commission/edit","store_id={$smarty.get.store_id}")}' class="pjax" >设置佣金</a></li>
					<li><a href='{RC_Uri::url("commission/admin/init","store_id={$smarty.get.store_id}")}' class="pjax" >结算账单</a></li>
					<li><a href='{RC_Uri::url("store/admin/view_staff","store_id={$smarty.get.store_id}")}' class="pjax" >查看员工</a></li>
				</ul>
				<div class="tab-content tab_merchants">
					<div class="tab-pane active" id="tab1">
					<div class="control-group">
					{if $store.status eq 1}<a class="data-pjax btn f_r" href='{RC_Uri::url("store/admin/status","&status=1&store_id={$smarty.get.store_id}")}'><i class="fontello-icon-lock"></i>锁定</a>{/if}
					{if $store.status eq 2}<a class="data-pjax btn f_r" href='{RC_Uri::url("store/admin/status","&status=2&store_id={$smarty.get.store_id}")}'><i class="fontello-icon-lock-open"></i>解锁</a>{/if}
					<a class="data-pjax btn f_r m_r10" href='{RC_Uri::url("store/admin/edit","store_id={$smarty.get.store_id}")}'><i class="fontello-icon-edit"></i>编辑</a>
					
					</div>
					<form class="form-horizontal" id="form-privilege" name="theForm" action="{$form_action}" method="post" enctype="multipart/form-data" >
						<div class="foldable-list move-mod-group">
            			<div class="accordion-group">
            				<div class="accordion-heading">
            					<a class="accordion-toggle collapsed move-mod-head" data-toggle="collapse" data-target="#goods_info_area_submit">
            						<strong>店铺信息</strong>
            					</a>
            				</div>
            				<div class="accordion-body in collapse" id="goods_info_area_submit">
            					<table class="table table-oddtd m_b0">
            						<tbody class="first-td-no-leftbd">
            						<tr>
            							<td><div align="right"><strong>{lang key='store::store.store_title_lable'}</strong></div></td>
            							<td><strong>{$store.merchants_name}</strong></td>
            							<td><div align="right"><strong>{lang key='store::store.store_keywords_lable'}</strong></div></td>
            							<td>{$store.shop_keyword}</td>
            						</tr>
            						<tr>
            							<td><div align="right"><strong>{lang key='store::store.store_cat_lable'}</strong></div></td>
            							<td>{if $store.cat_name eq ''}未分类{else}{$store.cat_name}{/if}</td>
            							<td><div align="right"><strong>开店时间：</strong></div></td>
            							<td>{$store.confirm_time}</td>
            						</tr>
            						<tr>
            						    <td><div align="right"><strong>{lang key='store::store.contact_lable'}</strong></div></td>
            							<td>{$store.contact_mobile}</td>
            							<td><div align="right"><strong>{lang key='store::store.email_lable'}</strong></div></td>
            							<td>{$store.email}</td>
            						</tr>
            						<tr>
            							<td><div align="right"><strong>所在地区：</strong></div></td>
            							<td>{$store.province}&nbsp;&nbsp;{$store.city}</td>
            							<td><div align="right"><strong>经纬度：</strong></div></td>
            							<td>{$store.longitude}&nbsp;&nbsp;{$store.latitude}</td>
            						</tr>
            						<tr>
            							<td><div align="right"><strong>{lang key='store::store.address_lable'}</strong></div></td>
            							<td colspan="3">{$store.address}{if $store.longitude && $store.latitude}&nbsp;&nbsp;<a href="http://api.map.baidu.com/marker?location={$store.latitude},{$store.longitude}&title={$store.merchants_name}&content={$store.merchants_name}&output=html" title="查看地图" target="_blank">[查看地图]</a>{/if}</td>
            						</tr>
            						</tbody>
            					</table>
            				</div>
            			</div>
            			
            			<div class="accordion-group">
            				<div class="accordion-heading">
            					<a class="accordion-toggle collapsed move-mod-head" data-toggle="collapse" data-target="#info2">
            						<strong>经营主体信息</strong>
            					</a>
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
            						</tbody>
            					</table>
            				</div>
            			</div>
            			
            			<div class="accordion-group">
            				<div class="accordion-heading">
            					<a class="accordion-toggle collapsed move-mod-head" data-toggle="collapse" data-target="#merchant_bank">
            						<strong>银行账户信息</strong>
            					</a>
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
		                </div>
            			<fieldset>
            				{if $store.validate_type eq 1}
            				<input type="hidden"  name="identity_type" value="{$store.validate_type}" />
            				{elseif $store.validate_type eq 2}
            				<div class="control-group formSep">
            					<label class="control-label">{lang key='store::store.business_licence_pic_lable'}</label>
            					<div class="controls">
            						<div class="fileupload fileupload-new" data-provides="fileupload">
            							{if $store.business_licence_pic neq ''}
            							<img class="w120 h120 thumbnail"  class="img-polaroid" src="{RC_Upload::upload_url()}/{$store.business_licence_pic}"><br>
            							{lang key='store::store.file_address'}{$store.business_licence_pic}<br>
            							{else}
            							<div class="l_h30">
            								{lang key='store::store.no_upload'}
            							</div>
            							{/if}
            						</div>
            					</div>
            				</div>
            				{/if}
            
            				<div class="control-group formSep">
            					<label class="control-label">{lang key='store::store.identity_pic_front_lable'}</label>
            					<div class="controls">
            						<div class="fileupload fileupload-new" data-provides="fileupload">
            							{if $store.identity_pic_front neq ''}
            							<img class="w120 h120 thumbnail"  class="img-polaroid" src="{RC_Upload::upload_url()}/{$store.identity_pic_front}"><br>
            							{lang key='store::store.file_address'}{$store.identity_pic_front}<br>
            							{else}
            							<div class="l_h30">
            								{lang key='store::store.no_upload'}
            							</div>
            							{/if}
            						</div>
            					</div>
            				</div>
            
            				<div class="control-group formSep">
            					<label class="control-label">{lang key='store::store.identity_pic_back_lable'}</label>
            					<div class="controls">
            						<div class="fileupload fileupload-new" data-provides="fileupload">
            							{if $store.identity_pic_back neq ''}
            							<img class="w120 h120 thumbnail"  class="img-polaroid" src="{RC_Upload::upload_url()}/{$store.identity_pic_back}"><br>
            							{lang key='store::store.file_address'}{$store.identity_pic_back}<br>
            							{else}
            							<div class="l_h30">
            								{lang key='store::store.no_upload'}
            							</div>
            							{/if}
            						</div>
            					</div>
            				</div>
            
            				<div class="control-group">
            					<label class="control-label">{lang key='store::store.personhand_identity_pic_lable'}</label>
            					<div class="controls">
            						<div class="fileupload fileupload-new" data-provides="fileupload">
            							{if $store.personhand_identity_pic neq ''}
            							<img class="w120 h120 thumbnail"  class="img-polaroid" src="{RC_Upload::upload_url()}/{$store.personhand_identity_pic}"><br>
            							{lang key='store::store.file_address'}{$store.personhand_identity_pic}<br>
            							{else}
            							<div class="l_h30">
            								{lang key='store::store.no_upload'}
            							</div>
            							{/if}
            						</div>
            					</div>
            				</div>
            			</fieldset>
            		</form>
							
					</div>
				</div>
			</div>
		</div>
	</form>
</div>

<!-- {/block} -->