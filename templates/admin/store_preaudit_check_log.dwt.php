<?php defined('IN_ECJIA') or exit('No permission resources.');?>
<!-- {extends file="ecjia.dwt.php"} -->
<!-- {block name="footer"} -->
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

	<div class="control-group control-group-small">
		<table class="table">
		    <thead>
		      <tr>
    		      <th class="w120">时间</th>
    		      <th>操作人</th>
    		      <th>日志</th>
		      </tr>
		      
		    </thead>
		  	<tbody>
		  	{foreach from=$log_list.list item=list}
		  		<tr align="center">
			    <td>{$list.formate_time}</td>
			    <td>{$list.name}</td>
			    <td><span style="line-height: 170%"> {$list.info}</span>
    			    {if $list.log}
        			    <table class="table table-condensed table-hover log">
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
		{$log_list.page}
      </div>
	</div>
</div>
<!-- {/block} -->
