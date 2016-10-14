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
		<form class="form-horizontal" id="form-privilege" name="theForm" action="{$form_action}" method="post" enctype="multipart/form-data" >
			<fieldset>
				<div class="control-group formSep">
					<label class="control-label">{lang key='store::store.companyname_lable'}</label>
					<div class="controls l_h30">
						{$store.company_name}
					</div>
				</div>
				
				<div class="control-group formSep">
					<label class="control-label">{lang key='store::store.person_lable'}</label>
					<div class="controls l_h30">
						{$store.responsible_person}
					</div>
				</div>
			
				<div class="control-group formSep" >
					<label class="control-label">{lang key='store::store.identity_type_lable'}</label>
					<div class="controls l_h30">
						{$store.identity_type}
					</div>
				</div>
				
				<div class="control-group formSep">
					<label class="control-label">{lang key='store::store.business_licence_pic_lable'}</label>
					<div class="controls">
						<div class="fileupload fileupload-new" data-provides="fileupload">
							{if $store.business_licence_pic neq ''}
								<img class="w120 h120"  class="img-polaroid" src="{RC_Upload::upload_url()}/{$store.business_licence_pic}"><br>
								{lang key='store::store.file_address'}{$store.business_licence_pic}<br>
							{else}
								<div class="l_h30">
									{lang key='store::store.no_upload'}
								</div>
							{/if}
						</div>
					</div>
				</div>
				
				<div class="control-group formSep">
					<label class="control-label">{lang key='store::store.business_licence_lable'}</label>
					<div class="controls l_h30">
						{$store.business_licence}
					</div>
				</div>
		
				<div class="control-group formSep">
					<label class="control-label">{lang key='store::store.identity_pic_front_lable'}</label>
					<div class="controls">
						<div class="fileupload fileupload-new" data-provides="fileupload">
							{if $store.business_licence_pic neq ''}
								<img class="w120 h120"  class="img-polaroid" src="{RC_Upload::upload_url()}/{$store.identity_pic_front}"><br>
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
							{if $store.business_licence_pic neq ''}
								<img class="w120 h120"  class="img-polaroid" src="{RC_Upload::upload_url()}/{$store.identity_pic_back}"><br>
								{lang key='store::store.file_address'}{$store.identity_pic_back}<br>
							{else}
								<div class="l_h30">
									{lang key='store::store.no_upload'}
								</div>
							{/if}
						</div>
					</div>
				</div>
				
				<div class="control-group formSep">
					<label class="control-label">{lang key='store::store.personhand_identity_pic_lable'}</label>
					<div class="controls">
						<div class="fileupload fileupload-new" data-provides="fileupload">
							{if $store.business_licence_pic neq ''}
								<img class="w120 h120"  class="img-polaroid" src="{RC_Upload::upload_url()}/{$store.personhand_identity_pic}"><br>
								{lang key='store::store.file_address'}{$store.personhand_identity_pic}<br>
							{else}
								<div class="l_h30">
									{lang key='store::store.no_upload'}
								</div>
							{/if}
						</div>
					</div>
				</div>
				
				<div class="control-group formSep">
					<label class="control-label">{lang key='store::store.identity_number_lable'}</label>
					<div class="controls l_h30">
						{$store.identity_number}
					</div>
				</div>
				
				<div class="control-group formSep">
					<label class="control-label">{lang key='store::store.bank_name_lable'}</label>
					<div class="controls l_h30">
						{$store.bank_name}
					</div>
				</div>
				
				<div class="control-group formSep">
					<label class="control-label">{lang key='store::store.bank_branch_name_lable'}</label>
					<div class="controls l_h30">
						{$store.bank_branch_name}
					</div>
				</div>
				
				<div class="control-group formSep">
					<label class="control-label">{lang key='store::store.bank_account_number_lable'}</label>
					<div class="controls l_h30">
						{$store.bank_account_number}
					</div>
				</div>
				
				<div class="control-group formSep">
					<label class="control-label">{lang key='store::store.bank_address_lable'}</label>
					<div class="controls l_h30">
						{$store.bank_address}
					</div>
				</div>
				
				
				<div class="control-group formSep">
					<label class="control-label">{lang key='store::store.email_lable'}</label>
					<div class="controls l_h30">
						{$store.email}
					</div>
				</div>
				
				<div class="control-group formSep">
					<label class="control-label">{lang key='store::store.contact_lable'}</label>
					<div class="controls l_h30">
						{$store.contact_mobile}
					</div>
				</div>
				
				<div class="control-group formSep">
					<label class="control-label">{lang key='store::store.address_lable'}</label>
					<div class="controls l_h30">
						{$store.address}
					</div>
				</div>
			
				<div class="control-group formSep" >
					<label class="control-label">{lang key='store::store.store_cat_lable'}</label>
					<div class="controls l_h30">
						{$store.store_cat}
					</div>
				</div>
				
				<div class="control-group formSep">
					<label class="control-label">{lang key='store::store.store_title_lable'}</label>
					<div class="controls l_h30">
						{$store.merchants_name}
					</div>
				</div>
				
				<div class="control-group formSep">
					<label class="control-label">{lang key='store::store.store_keywords_lable'}</label>
					<div class="controls l_h30">
						{$store.shop_keyword}
					</div>
				</div>
				
				<div class="control-group formSep">
					<label class="control-label">{lang key='store::store.longitude_lable'}</label>
				 	<div class="controls l_h30">
						{$store.longitude}
					</div>
				</div>
				
				<div class="control-group formSep">
					<label class="control-label">{lang key='store::store.latitude_lable'}</label>
				 	<div class="controls l_h30">
						{$store.latitude}
					</div>
				</div>
				
				<div class="control-group formSep">
					<label class="control-label">{lang key='store::store.apply_time_lable'}</label>
				 	<div class="controls l_h30">
						{$store.apply_time}
					</div>
				</div>
				
				<div class="control-group formSep">
					<label class="control-label">{lang key='store::store.check_lable'}</label>
				 	<div class="controls">
						<input type="radio"  name="check_status" value="1" {if $store.check_status eq 1}checked{/if}><span>{lang key='store::store.check_no'}</span>
						<input type="radio"  name="check_status" value="2" {if $store.check_status eq 2}checked{/if}><span>{lang key='store::store.check_yes'}</span>
					</div>
				</div>
				
				<div class="control-group formSep" >
					<label class="control-label">{lang key='store::store.remark_lable'}</label>
					<div class="controls">
						<textarea class="span6" name="remark" cols="40" rows="3">{$store.remark}</textarea>
						<input type="hidden"  name="original" value="{$store.remark}" />
					</div>
				</div>
				
				<div class="control-group">
					<div class="controls">
						<input type="hidden"  name="id" value="{$store.id}" />
						<input type="hidden"  name="store_id" value="{$store.store_id}" />
						<button class="btn btn-gebo" type="submit">{lang key='store::store.sub_check'}</button>
					</div>
				</div>
			</fieldset>
		</form>
	</div>
</div>
<!-- {/block} -->