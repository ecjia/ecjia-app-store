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
		<table class="table table-hover">
		    <thead>
		      <tr>
		      <th>时间</th>
		      <th>操作人</th>
		      <th>日志</th>
		      </tr>
		      
		    </thead>
		  	<tbody>
		  	{foreach from=$log_list.list item=list}
		  		<tr align="center">
			    <td>{$list.formate_time}</td>
			    <td>{$list.name}</td>
			    <td><span style="line-height: 170%"> {$list.info}{$list.log}</span></td>
			    </tr>
		    {/foreach}
			</tbody>
		</table>
		{$log_list.page}
      </div>
	</div>
</div>
<!-- {/block} -->
