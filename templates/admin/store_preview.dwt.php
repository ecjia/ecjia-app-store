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
	<div class="span12">
		<div class="foldable-list move-mod-group" id="goods_info_sort_submit">
			<div class="accordion-group">
				<div class="accordion-heading">
					<a class="accordion-toggle collapsed move-mod-head" data-toggle="collapse" data-target="#goods_info_area_submit">
						<strong>商家信息</strong>
					</a>
				</div>

				{if $store.identity_type eq 1}
				<div class="accordion-body in collapse" id="goods_info_area_submit">
					<table class="table table-oddtd m_b0">
						<tbody class="first-td-no-leftbd">
						<tr>
							<td><div align="right"><strong>{lang key='store::store.store_title_lable'}</strong></div></td>
							<td>{$store.merchants_name}</td>
							<td><div align="right"><strong>{lang key='store::store.store_keywords_lable'}</strong></div></td>
							<td>{$store.shop_keyword}</td>
						</tr>

						<tr>
							<td><div align="right"><strong>{lang key='store::store.store_cat_lable'}</strong></div></td>
							<td>{$store.cat_name}</td>
							<td><div align="right"><strong>{lang key='store::store.apply_time_lable'}</strong></div></td>
							<td>{$store.apply_time}</td>
						</tr>

						<tr>
							<td><div align="right"><strong>{lang key='store::store.identity_type_lable'}</strong></div></td>
							<td>{if $store.identity_type eq 1}个人{else}企业{/if}</td>
							<td><div align="right"><strong>{lang key='store::store.identity_number_lable'}</strong></div></td>
							<td>{$store.identity_number}</td>
						</tr>

						<tr>
							<td><div align="right"><strong>{t}个人名称：{/t}</strong></div></td>
							<td colspan="3">{$store.responsible_person}</td>
						</tr>

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
							<td><div align="right"><strong>{lang key='store::store.email_lable'}</strong></div></td>
							<td>{$store.email}</td>
							<td><div align="right"><strong>{lang key='store::store.contact_lable'}</strong></div></td>
							<td>{$store.contact_mobile}</td>
						</tr>

						<tr>
							<td><div align="right"><strong>{lang key='store::store.label_province'}</strong></div></td>
							<td>{$store.province}</td>
							<td><div align="right"><strong>{lang key='store::store.label_city'}</strong></div></td>
							<td>{$store.city}</td>
						</tr>

						<tr>
							<td><div align="right"><strong>{lang key='store::store.longitude_lable'}</strong></div></td>
							<td>{$store.longitude}</td>
							<td><div align="right"><strong>{lang key='store::store.latitude_lable'}</strong></div></td>
							<td>{$store.latitude}</td>
						</tr>

						<tr>
							<td><div align="right"><strong>{lang key='store::store.bank_address_lable'}</strong></div></td>
							<td colspan="3">{$store.bank_address}</td>
						</tr>
						<tr>
							<td><div align="right"><strong>{lang key='store::store.address_lable'}</strong></div></td>
							<td colspan="3">{$store.address}</td>
						</tr>
						</tbody>
					</table>
				</div>
				{elseif $store.identity_type eq 2}
				<div class="accordion-body in collapse" id="goods_info_area_submit">
					<table class="table table-oddtd m_b0">
						<tbody class="first-td-no-leftbd">
						<tr>
							<td><div align="right"><strong>{lang key='store::store.store_title_lable'}</strong></div></td>
							<td>{$store.merchants_name}</td>
							<td><div align="right"><strong>{lang key='store::store.store_keywords_lable'}</strong></div></td>
							<td>{$store.shop_keyword}</td>
						</tr>

						<tr>
							<td><div align="right"><strong>{lang key='store::store.store_cat_lable'}</strong></div></td>
							<td>{$store.cat_name}</td>
							<td><div align="right"><strong>{lang key='store::store.apply_time_lable'}</strong></div></td>
							<td>{$store.apply_time}</td>
						</tr>

						<tr>
							<td><div align="right"><strong>{lang key='store::store.companyname_lable'}</strong></div></td>
							<td colspan="3">{$store.company_name}</td>
						</tr>

						<tr>
							<td><div align="right"><strong>{lang key='store::store.identity_type_lable'}</strong></div></td>
							<td>{if $store.identity_type eq 1}个人{else}企业{/if}</td>
							<td><div align="right"><strong>{lang key='store::store.person_lable'}</strong></div></td>
							<td>{$store.responsible_person}</td>
						</tr>

						<tr>
							<td><div align="right"><strong>{lang key='store::store.business_licence_lable'}</strong></div></td>
							<td>{$store.business_licence}</td>
							<td><div align="right"><strong>{lang key='store::store.identity_number_lable'}</strong></div></td>
							<td>{$store.identity_number}</td>
						</tr>

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
							<td><div align="right"><strong>{lang key='store::store.email_lable'}</strong></div></td>
							<td>{$store.email}</td>
							<td><div align="right"><strong>{lang key='store::store.contact_lable'}</strong></div></td>
							<td>{$store.contact_mobile}</td>
						</tr>

						<tr>
							<td><div align="right"><strong>{lang key='store::store.label_province'}</strong></div></td>
							<td>{$store.province}</td>
							<td><div align="right"><strong>{lang key='store::store.label_city'}</strong></div></td>
							<td>{$store.city}</td>
						</tr>

						<tr>
							<td><div align="right"><strong>{lang key='store::store.longitude_lable'}</strong></div></td>
							<td>{$store.longitude}</td>
							<td><div align="right"><strong>{lang key='store::store.latitude_lable'}</strong></div></td>
							<td>{$store.latitude}</td>
						</tr>

						<tr>
							<td><div align="right"><strong>{lang key='store::store.bank_address_lable'}</strong></div></td>
							<td colspan="3">{$store.bank_address}</td>
						</tr>
						<tr>
							<td><div align="right"><strong>{lang key='store::store.address_lable'}</strong></div></td>
							<td colspan="3">{$store.address}</td>
						</tr>
						</tbody>
					</table>
				</div>
				{/if}
			</div>
		</div>

		<form class="form-horizontal" id="form-privilege" name="theForm" action="{$form_action}" method="post" enctype="multipart/form-data" >
			<fieldset>
				{if $store.identity_type eq 1}
				<input type="hidden"  name="identity_type" value="{$store.identity_type}" />
				{elseif $store.identity_type eq 2}
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
<!-- {/block} -->