<?php defined('IN_ECJIA') or exit('No permission resources.');?>
<!-- {extends file="ecjia.dwt.php"} -->
<!-- {block name="footer"} -->
<script type="text/javascript">
	ecjia.admin.seller_list.edit();
	ecjia.admin.seller_list.clone();
</script>
<!-- {/block} -->
<!-- {block name="main_content"} -->
<div>
	<h3 class="heading">
		{t}编辑品牌{/t}
		{if $action_link}
		<a class="btn plus_or_reply data-pjax" href="{$action_link.href}" id="sticky_a"><i class="fontello-icon-reply"></i>{$action_link.text}</a>
		{/if}
	</h3>
</div>
<div class="row-fluid">
	<div class="span12">
	
		<div class="tabbable">
			<form class="form-horizontal" method="post" action="{$form_action}" name="theForm">
				<div class="row-fluid edit-page editpage-rightbar">
					
					<!-- 左边内容 start -->
					<div class="left-bar move-mod ui-sortable">
						<div class="control-group formSep">
							<label class="control-label">品牌中文名：</label>
							<div class="controls">
								<input type="text" name="ec_brandName" value="{$brand_info.brandName}"/>
								<span class="input-must">*</span>
							</div>
						</div>
						
						<div class="control-group formSep">
							<label class="control-label">品牌英文名：</label>
							<div class="controls">
								<input type="text" name="ec_bank_name_letter" value="{$brand_info.bank_name_letter}"/>
							</div>
						</div>
						
						<div class="control-group  formSep">
							<label class="control-label">品牌首字母：</label>
							<div class="controls">
								<input type="text" name="ec_brandFirstChar" value="{$brand_info.brandFirstChar}"/>
							</div>
						</div>
						
						<div class="control-group formSep">
							<label class="control-label">品牌LOGO：</label>
							<div class="controls fileupload {if $brand_info.brandLogo}fileupload-exists{else}fileupload-new{/if}" data-provides="fileupload">
								<div class="fileupload-preview thumbnail fileupload-exists" style="width: 50px; height: 50px; line-height: 50px;">
									<img src="{$brand_info.brandLogo}" alt="图片预览" />
								</div>
								<span class="btn btn-file">
									<span class="fileupload-new">{t}浏览{/t}</span>
									<span class="fileupload-exists">{t}修改{/t}</span>
									<input type="file" name="ec_brandLogo"/>
								</span>
								<a class="btn fileupload-exists" {if !$brand_info.brandLogo}data-dismiss="fileupload" href="javascript:;"{else}data-toggle="ajaxremove" data-msg="{t}您确定要删除吗？{/t}" href='{url path="seller/admin/remove_brand_logo" args="bid={$bid}&user_id={$user_id}"}' title="{t}移除{/t}"{/if}>删除</a>
							</div>
						</div>
				
						<div class="control-group formSep">
							<label class="control-label">品牌类型：</label>
							<div class="controls">
								<select name="ec_brandType">
				                	<option {if $brand_info.brandType eq 0}selected="selected"{/if} value="0">请选择..</option>
				                	<option {if $brand_info.brandType eq 1}selected="selected"{/if} value="1">国内品牌</option>
				                	<option {if $brand_info.brandType eq 2}selected="selected"{/if} value="2">国际品牌</option>
				                </select>   
							</div>
						</div>
						
						<div class="control-group formSep">
							<label class="control-label">经营类型：</label>
							<div class="controls">
								<select name="ec_brand_operateType">
				                    <option {if $brand_info.brand_operateType eq 0}selected="selected"{/if} value="0">请选择..</option>
			                		<option {if $brand_info.brand_operateType eq 1}selected="selected"{/if} value="1">自有品牌</option>
			                		<option {if $brand_info.brand_operateType eq 2}selected="selected"{/if} value="2">代理品牌</option>
				                </select>
							</div>
						</div>
						
						<div class="control-group formSep">
							<label class="control-label">品牌使用期限：</label>
							<div class="controls">
								<input type="text" name="ec_brandEndTime" value="{$brand_info.brandEndTime}" size="20" class="jdate" id="ec_brandEndTime" {if $brand_info.brandEndTime_permanent}readonly{/if}>
			                	<input type="checkbox" name="ec_brandEndTime_permanent" value="1" id="brandEndTime_permanent" {if $brand_info.brandEndTime_permanent}checked{/if}> 永久
							</div>
						</div>
						
						<div class="control-group">
							<div class="controls">
				                <input class="btn btn-gebo" type="submit" value="确定">
							</div>
						</div>
						
					</div>
					<!-- 左边内容 end -->
					
					<!-- 右边内容 start -->
					<div class="right-bar move-mod ui-sortable">
                		<div class="foldable-list move-mod-group">
                    		<div class="accordion-group">
                        		<div class="accordion-heading">
                                	<a class="accordion-toggle move-mod-head acc-in" data-toggle="collapse" data-target="#submit">
                                    	<strong>{t}品牌资质信息{/t}</strong>
                                    </a>
                                </div>
                                
                                <div class="accordion-body in in_visable collapse" id="submit">
                                	<div class="accordion-inner">
	                                	<div class=" control-group-small">
	                                		<div class="help-block">{t}电子版须加盖彩色企业公章（即纸质版盖章，扫描或拍照上传），文字内容清晰可辨,支持jpg、gif和png图片，大小不超过4M。{/t}</div>
                                        </div>
                                        
                                        {if $brand_file_info}
										<!-- {foreach from=$brand_file_info item=brand_file key=k} -->
                                        <!-- div start -->
                                        <div class="one box_clone">
	                                       	<div class="box_clone_content">
												<div class="control-group control-group-small formSep">
													<label class="label-title">资质名称：</label>
													<div class="controls">
														<input class="w180" type="text" name="ec_qualificationNameInput[{$k}]" value="{$brand_file.qualificationNameInput}">
														<span class="input-must">*</span>
													</div>
												</div>
												
												<div class="control-group control-group-small formSep">
													<label class="label-title">电子版：</label>
													<div class="controls chk_radio">
														<div class="fileupload {if $brand_file.qualificationImg}fileupload-exists{else}fileupload-new{/if}" data-provides="fileupload">
														<div class="fileupload-preview fileupload-exists thumbnail" style="width: 50px; height: 50px; line-height: 50px;">
														{if $brand_file.qualificationImg}
														<img src="{$brand_file.qualificationImg}" alt="{t}图片预览{/t}" />
														{/if}
														</div>
														<span class="btn btn-file">
															<span class="fileupload-new">浏览</span>
															<span class="fileupload-exists">修改</span>
															<input type="file" name="ec_qualificationImg[{$k}]" size="35"/>
														</span>
														<a class="btn fileupload-exists" {if !$brand_file.qualificationImg} data-dismiss="fileupload" href="javascript:;"{else}data-toggle="ajaxremove" data-msg="{t}您确定要删除该资质电子版吗？{/t}" href='{url path="seller/admin/remove_file" args="type=qualificationImg&b_fid={$brand_file.b_fid}&img={$brand_file.qualificationImg}"}' title="{t}删除{/t}"{/if}>删除</a>
														</div>
													</div>
												</div>
												
												<div class="control-group control-group-small">
													<label class="label-title">到期日：</label>
													<div class="controls">
														<input type="text" name="ec_expiredDateInput[{$k}]" class="w130 jdate" value="{$brand_file.expiredDateInput}"> 
										                <input type="checkbox" name="ec_expiredDate_permanent[{$k}]" value="1" {if $brand_file.expiredDate_permanent}checked{/if}>永久
										                <input type="hidden" name="b_fid[{$k}]" value="{$brand_file.b_fid}">
										                <div class="box_delete">
										                	<a class="ecjiafc-red ajax-remove" data-toggle="ajaxremove" data-msg="{t}您确定要删除该品牌资质吗？{/t}" href='{url path="seller/admin/remove_qualification" args="b_fid={$brand_file.b_fid}"}' title="{t}删除{/t}">移除</a> 
										                </div>
													</div>
												</div>
											</div>
										</div>
										<!-- div end -->
										<!-- {/foreach} -->
										
										{else}
										
										<!-- div start -->
                                        <div class="one box">
	                                       	<div class="box_content">
												<div class="control-group control-group-small formSep">
													<label class="label-title">资质名称：</label>
													<div class="controls">
														<input class="w180" type="text" name="ec_qualificationNameInput[0]">
														<span class="input-must">*</span>
													</div>
												</div>
												
												<div class="control-group control-group-small formSep">
													<label class="label-title">电子版：</label>
													<div class="controls chk_radio">
														<div class="fileupload fileupload-new" data-provides="fileupload">	
														<div class="fileupload-preview fileupload-exists thumbnail" style="width: 50px; height: 50px; line-height: 50px;">
														</div>
														<span class="btn btn-file">
															<span  class="fileupload-new">浏览</span>
															<span  class="fileupload-exists">修改</span>
															<input type="file" name='ec_qualificationImg[0]' size="35"/>
														</span>
														<a class="btn fileupload-exists" data-dismiss="fileupload" href="javascript:;">删除</a>
														</div>
													</div>
												</div>
												
												<div class="control-group control-group-small">
													<label class="label-title">到期日：</label>
													<div class="controls">
														<input type="text" name="ec_expiredDateInput[0]" class="w130 jdate narrow dateTime">
										                <input type="checkbox" name="ec_expiredDate_permanent[0]" value="1" class="ec_expiredDate_permanent">永久
													</div>
												</div>
											</div>
										</div>
										<!-- div end -->
										{/if}
										
										<!-- 隐藏div用于克隆 start -->
                                        <div class="clone hide box_clone">
	                                       	<div class="box_clone_content">
												<div class="control-group control-group-small formSep">
													<label class="label-title">资质名称：</label>
													<div class="controls">
														<input class="w180 ec_qualificationNameInput" type="text" name="ec_qualificationNameInput[]">
														<span class="input-must">*</span>
													</div>
												</div>
												
												<div class="control-group control-group-small formSep">
													<label class="label-title">电子版：</label>
													<div class="controls chk_radio">
														<div class="fileupload fileupload-new" data-provides="fileupload">	
														<div class="fileupload-preview fileupload-exists thumbnail" style="width: 50px; height: 50px; line-height: 50px;">
														</div>
														<span class="btn btn-file">
															<span  class="fileupload-new">浏览</span>
															<span  class="fileupload-exists">修改</span>
															<input class="ec_qualificationImg" type="file" name='ec_qualificationImg[]' size="35"/>
														</span>
														<a class="btn fileupload-exists" data-dismiss="fileupload" href="javascript:;">删除</a>
														</div>
													</div>
												</div>
												
												<div class="control-group control-group-small">
													<label class="label-title">到期日：</label>
													<div class="controls">
														<input type="text" name="ec_expiredDateInput[]" class="w130 jdate ec_expiredDateInput dateTime">
										                <input type="checkbox" name="ec_expiredDate_permanent[]" value="1" class="ec_expiredDate_permanent">永久
										                <input type="hidden" name="b_fid[]" value="{$brand_file.b_fid}" class="b_fid">
										                <div class="box_delete">
										                	<a class="ecjiafc-red" data-toggle="clone-node" data-parent=".clone" href="javascript:;">移除</a>
										                </div>
													</div>
												</div>
											</div>
										</div>
										<!-- div end -->
										
										<a class="no-underline" data-toggle="clone-node" data-parent=".clone" href="javascript:;">还要上传</a>
										
									</div>
								</div>
													
                        	</div>
                      	</div> 
                	</div>
                	<!-- 右边内容 end -->
                	
				</div>
			</form>
		</div>
		
	</div>
</div>
	
<!-- {/block} -->
