<?php defined('IN_ECJIA') or exit('No permission resources.');?>
<!-- {extends file="ecjia.dwt.php"} -->
<!-- {block name="footer"} -->
<script type="text/javascript">
	ecjia.admin.seller_list.init();
	ecjia.admin.seller.toggle();
</script>
<!-- {/block} -->
<!-- {block name="main_content"} -->
<div>
	<h3 class="heading">
		{if $ur_here}{$ur_here}{/if} {if $action_link}
		<a class="btn plus_or_reply data-pjax" href="{$action_link.href}" id="sticky_a"><i class="fontello-icon-reply"></i>{$action_link.text}</a>
		{/if}
	</h3>
</div>

<div class="row-fluid">
	<form class="form-horizontal" name="theForm" action="{$form_action}" method="post" enctype="multipart/form-data">
		<div class="span12">
			<div class="tabbable tabs-left">
				<ul class="nav nav-tabs {if $shop_id neq 0}tab_merchants_nav{/if}">
					<!-- {if $shop_id neq 0} -->
						<li class="{if !$link}active{/if}"><a href="#tab1" data-toggle="tab">{t}基本信息{/t}</a></li>
						<li><a href="#tab-identity" data-toggle="tab">{t}认证信息{/t}</a></li>
						<!-- {foreach from=$shopInfo_list key=key item=shopInfo} -->
							<!-- {foreach from=$shopInfo.steps_title item=title}-->
								<!-- {if $title.steps_style eq 0}-->
									<li><a href="#{$title.fields_titles}" data-toggle="tab">{$title.fields_titles}</a></li>
								<!-- {elseif $title.steps_style eq 1}-->
									<li><a href="#{$title.fields_titles}" data-toggle="tab">{$title.fields_titles}</a></li>
								<!-- {elseif $title.steps_style eq 2}-->
									<li><a href="#{$title.fields_titles}" data-toggle="tab">{$title.fields_titles}</a></li>
								<!-- {elseif $title.steps_style eq 3} -->
									<li class="{if $link eq $title.fields_titles}active{/if}"><a href="#{$title.fields_titles}" data-toggle="tab">{$title.fields_titles}</a></li>
								<!-- {elseif $title.steps_style eq 4} -->
									<!-- {if $title.cententFields} -->
									<li><a href="#{$title.fields_titles}" data-toggle="tab">{$title.fields_titles}</a></li>
									<!-- {/if} -->
								<!-- {/if} -->
							<!-- {/foreach} -->
						<!-- {/foreach} -->
					<!-- {/if} -->
				</ul>
				<div class="tab-content {if $shop_id neq 0}tab_merchants{/if}">
					<div class="tab-pane {if !$link}active{/if}" id="tab1">
						
						<!-- {foreach from=$shopInfo_list key=key item=shopInfo} -->
							<!-- {foreach from=$shopInfo.steps_title item=title}-->
								<!-- {if $title.steps_style eq 4} -->
								<!-- {if $shop_id neq 0 } -->
								<div>
									<h3 class="heading">
										{t}基本信息{/t}
									</h3>
								</div>
								<!-- {/if} -->
								<div class="control-group formSep">
									<label class="control-label">期望店铺名称：</label>
									<div class="controls">
										<input type="text" name="ec_rz_shopName" id="rz_shopName" size="30" value="{$title.parentType.rz_shopName}" class="input">
										<span class="input-must">*</span>
										<div class="help-block"> 品牌名|类目描述|旗舰店/官方旗舰店 (也可自定义，如：EcJia官方旗舰店)<br/>
											{$title.titles_annotation}
										</div>
									</div>
								</div>
		
								<div class="control-group formSep">
									<label class="control-label">期望店铺登陆用户名：</label>
									<div class="controls">
										<input type="text" name="ec_hopeLoginName" size="30"
											value="{$title.parentType.hopeLoginName}" class="input">
										<span class="input-must">*</span>
									</div>
								</div>
								<!-- {if $title.parentType.shoprz_type} -->
								<div class="control-group formSep">
									<label class="control-label">期望店铺类型：</label>
									<div class="controls l_h30">
										<!-- {if $title.parentType.shoprz_type eq 1} -->
										旗舰店
										<!-- {elseif $title.parentType.shoprz_type eq 2} -->
										专卖店
										<!-- {elseif $title.parentType.shoprz_type eq 3} -->
										专营店
										<!-- {/if} -->
									</div>
								</div>
								<!-- {/if} -->
		                        <div class="control-group formSep">
                                    <label class="control-label">店铺分类：</label>
                                    <div class="controls">
                                        <select name="store_cat_id">
                                            <option value="0">顶级分类</option>
                                            <!-- {$cat_select} -->
                                        </select>
                                    </div>
                                </div>
		
								<!-- {if $shop_id neq 0} -->
								<div class="control-group formSep">
									<label class="control-label">选择品牌：</label>
									<div class="controls">
										<select name="ec_shoprz_brandName" onChange="get_shoprz_brandName(this.value);">
											<option value="0">请选择品牌</option>
											<!-- {foreach from=$title.brand_list item=brand} -->
											<option value="{$brand.brandName}" {if $title.parentType.shoprz_brandName eq $brand.brandName}selected{/if}>{$brand.brandName}</option>
											<!-- {/foreach} -->
										</select>
										<div class="clear"></div>
										<div class="help-block">{t}请先添加品牌{/t}</div>
									</div>
								</div>
								<!-- {/if} -->
								
								<div class="control-group formSep">
									<label class="control-label">选择店铺后缀：</label>
									<div class="controls">
										<select name="ec_shopNameSuffix">
											<option selected="selected" value="0">请选择..</option>
											<option {if $title.parentType.shopNameSuffix eq '旗舰店'}selected{/if} value="旗舰店">旗舰店</option>
											<option {if $title.parentType.shopNameSuffix eq '专卖店'}selected{/if} value="专卖店">专卖店</option>
											<option {if $title.parentType.shopNameSuffix eq '专营店'}selected{/if} value="专营店">专营店</option>
											<option {if $title.parentType.shopNameSuffix eq '馆'}selected{/if} value="馆">馆</option>
										</select>
									</div>
								</div>
								
								<div class="control-group formSep">
									<label class="control-label">类目描述关键词：</label>
									<div class="controls">
										<input type="text" name="ec_shop_class_keyWords" size="30" value="{$title.parentType.shop_class_keyWords}" class="input">
									</div>
								</div>
								<!-- {/if} -->
							<!-- {/foreach} -->
						<!-- {/foreach} -->

						<!-- {if $shop_id > 0 } -->
						<div class="control-group formSep">
							<label class="control-label">用户提交状态：</label>
							<div class="controls l_h30">{if $merchants.steps_audit eq 1}已提交{else}未提交{/if}</div>
						</div>
						<!-- {/if} -->

						<div class="control-group formSep">
							<label class="control-label">设置是否审核其商品：</label>
							<div class="controls chk_radio">
								<input type="radio" name="review_goods" {if $merchants.review_goods eq '1' || !$merchants.review_goods}checked{/if} value="1">
								<span>是</span>
								<input type="radio" name="review_goods" {if $merchants.review_goods eq '0'}checked{/if} value="0">
								<span>否</span>
							</div>
						</div>

						<!-- {if $merchants.steps_audit eq 1} -->
						<div class="control-group formSep">
							<label class="control-label">商家重新申请：</label>
							<div class="controls chk_radio">
								<input name="merchants_allow" type="radio" value="0" checked="checked" />
								<span>不允许</span>
								<input name="merchants_allow" type="radio" value="1" />
								<span>允许</span>
							</div>
						</div>
						<!-- {/if} -->

						<div class="control-group {if $merchants.merchants_audit eq 2}formSep{/if}">
							<label class="control-label">商家信息审核：</label>
							<div class="controls chk_radio l_h30">
								<!-- {if $shop_id > 0} -->
									<!-- {if $merchants.steps_audit eq 1} -->
										<input name="merchants_audit" type="radio" value="0" {if $merchants.merchants_audit eq 0}checked="checked" {/if}/>
										<span>未审核</span>
										<input name="merchants_audit" type="radio" value="1" {if $merchants.merchants_audit eq 1}checked="checked" {/if}/>
										<span>通过</span>
										<input name="merchants_audit" type="radio" value="2" {if $merchants.merchants_audit eq 2}checked="checked" {/if}/>
										<span>未通过</span>
									<!-- {else} -->
										<span>暂时不能审核</span>
									<!-- {/if} -->
								<!-- {else} -->
									<input name="merchants_audit" type="radio" value="0" checked="checked" />
									<span>未审核</span>
									<input name="merchants_audit" type="radio" value="1" />
									<span>通过</span>
									<input name="merchants_audit" type="radio" value="2" />
									<span>未通过</span>
								<!-- {/if} -->
							</div>
						</div>

						<!-- {if $merchants.steps_audit eq 1} -->
						<div class="control-group {if $merchants.merchants_audit eq 2}ecjiaf-db{else}ecjiaf-dn{/if}" id="tr_merchantsAudit">
							<label class="control-label">回复商家：</label>
							<div class="controls">
								<textarea name="merchants_message" rows="8" cols="45">{$merchants.merchants_message}</textarea>
							</div>
						</div>
						<!-- {/if} -->
					</div>
					{if $shop_id neq 0}
					<div class="tab-pane" id="tab-identity">
						<div>
							<h3 class="heading">
								{t}认证信息{/t}
							</h3>
						</div>
						<div class="control-group formSep">
							<label class="control-label">认证类型：</label>
							<div class="controls l_h30">{if $merchants.authentication_type eq 1}个人认证{elseif $merchant.authentication_type eq 2}企业认证{/if}</div>
						</div>
						<div class="control-group formSep">
							<label class="control-label">责任人：</label>
							<div class="controls l_h30">{$merchants.responsible_person}</div>
						</div>
						<div class="control-group formSep">
							<label class="control-label">公司名称：</label>
							<div class="controls l_h30">{$merchants.company_name}</div>
						</div>
						<div class="control-group formSep">
							<label class="control-label">联系方式：</label>
							<div class="controls l_h30">{$merchants.contact_mobile}</div>
						</div>
						<div class="control-group formSep">
							<label class="control-label">证件类型：</label>
							<div class="controls l_h30">{if $merchants.identity_type eq 1}身份证{elseif $merchants.identity_type eq 2}护照{else}军人证{/if}</div>
						</div>
						<div class="control-group formSep">
							<label class="control-label">证件号码：</label>
							<div class="controls l_h30">{$merchants.identity_number}</div>
						</div>
						<div class="control-group formSep">
							<label class="control-label">证件照片：</label>
							<div class="controls">
								<div class="fileupload-preview thumbnail fileupload-exists" style="width: 50px; height: 50px; line-height: 50px;">
									<a href="{$merchants.identity_pic}" target="_blank"><img src="{$merchants.identity_pic}" alt="{t}点击预览{/t}" /></a>
								</div>
							</div>
						</div>
						<div class="control-group formSep">
							<label class="control-label">证件正面：</label>
							<div class="controls">
								<div class="fileupload-preview thumbnail fileupload-exists" style="width: 50px; height: 50px; line-height: 50px;">
									<a href="{$merchants.identity_pic_front}" target="_blank"><img src="{$merchants.identity_pic_front}" alt="{t}点击预览{/t}" /></a>
								</div>
							</div>
						</div>
						<div class="control-group formSep">
							<label class="control-label">证件反面：</label>
							<div class="controls">
								<div class="fileupload-preview thumbnail fileupload-exists" style="width: 50px; height: 50px; line-height: 50px;">
									<a href="{$merchants.identity_pic_back}" target="_blank"><img src="{$merchants.identity_pic_back}" alt="{t}点击预览{/t}" /></a>
								</div>
							</div>
						</div>
						<div class="control-group formSep">
							<label class="control-label">营业执照：</label>
							<div class="controls">
								<div class="fileupload-preview thumbnail fileupload-exists" style="width: 50px; height: 50px; line-height: 50px;">
									<a href="{$merchants.business_licence_pic}" target="_blank"><img src="{$merchants.business_licence_pic}" alt="{t}点击预览{/t}" /></a>
								</div>
							</div>
						</div>
						<div class="control-group formSep">
							<label class="control-label">申请时间：</label>
							<div class="controls l_h30">{$merchants.create_time}</div>
						</div>
						<div class="control-group formSep">
							<label class="control-label">审核通过时间：</label>
							<div class="controls l_h30">{$merchants.confirm_time}</div>
						</div>
						<div class="control-group formSep">
							<label class="control-label">审核状态：</label>
							<div class="controls l_h30">{$merchants.confirm_time}</div>
						</div>
						<div class="control-group formSep">
							<label class="control-label">认证审核状态：</label>
							<div class="controls chk_radio l_h30">
								<input name="identity_status" type="radio" value="0" {if $merchants.identity_status eq 0}checked="checked" {/if}/>
								<span>待审核</span>
								<input name="identity_status" type="radio" value="1" {if $merchants.identity_status eq 1}checked="checked" {/if}/>
								<span>审核中</span>
								<input name="identity_status" type="radio" value="2" {if $merchants.identity_status eq 2}checked="checked" {/if}/>
								<span>审核通过</span>
								<input name="identity_status" type="radio" value="3" {if $merchants.identity_status eq 3}checked="checked" {/if}/>
								<span>拒绝通过</span>
							</div>
						</div>
					</div>
					{/if}
					<!-- {if $shop_id neq 0} -->
						<!-- {foreach from=$shopInfo_list key=key item=shopInfo} -->
							<!-- {foreach from=$shopInfo.steps_title item=title}-->
								<!-- {if $title.steps_style eq 0}-->
									<div class="tab-pane" id="{$title.fields_titles}">
										<!-- #BeginLibraryItem "/library/merchants_steps_basic_type.lbi" --><!-- #EndLibraryItem -->
									</div>
								<!-- {elseif $title.steps_style eq 1}-->
									<div class="tab-pane" id="{$title.fields_titles}">
										<!-- #BeginLibraryItem "/library/merchants_steps_shop_type.lbi" --><!-- #EndLibraryItem -->
									</div>
								<!-- {elseif $title.steps_style eq 2}-->
									<div class="tab-pane" id="{$title.fields_titles}">
										<!-- #BeginLibraryItem "/library/merchants_steps_cate_type.lbi" --><!-- #EndLibraryItem -->
									</div>
								<!-- {elseif $title.steps_style eq 3}-->
									<div class="tab-pane {if $link eq $title.fields_titles}active{/if}" id="{$title.fields_titles}">
										<div id="brandList">
											<!-- #BeginLibraryItem "/library/merchants_steps_brank_type.lbi" --><!-- #EndLibraryItem -->
										</div>
									</div>
								<!-- {elseif $title.steps_style eq 4} -->
									<div class="tab-pane" id="{$title.fields_titles}">
										<!-- #BeginLibraryItem "/library/merchants_steps_shop_info.lbi" --><!-- #EndLibraryItem -->
									</div>
								<!-- {/if} -->
							<!-- {/foreach} -->
						<!-- {/foreach} -->
					<!-- {/if} -->
				</div>
			</div>

			<div class="control-group">
				<input name="numAdd" value="1" id="numAdd" type="hidden" />
				<!-- {if $shop_id} -->
				<p class="ecjiaf-tac">
					<input type="submit" value="确定" class="btn btn-gebo" />
				</p>
				<!-- {else} -->
				<div class="controls">
					<input type="submit" value="确定" class="btn btn-gebo" />
				</div>
				<!-- {/if} -->
				<!--  {if $shop_id > 0} -->
				<input type="hidden" name="shop_id" value="{$shop_id}" id="user_id" />
				<!-- {else} -->
				<input type="hidden" name="shop_id" />
				<!-- {/if} -->
			</div>

		</div>
	</form>
</div>
<!-- {/block} -->