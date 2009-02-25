{* $Id$ *}
{include file='header'}
<style type="text/css">
    .p { text-align: left; }
    .e { background-color: #ccccff; font-weight: bold; color: #000000; }
    .h { background-color: #9999cc; font-weight: bold; color: #000000; }
    .v { background-color: #cccccc; color: #000000; }
    .vr { background-color: #cccccc; text-align: right; color: #000000; }
    hr { background-color: #cccccc; border: 0px; height: 1px; color: #000000; display:block; }
</style>

<div class="mainHeadline">
	<img src="{@RELATIVE_WCF_DIR}icon/adminToolsPhpL.png" alt="" />
	<div class="headlineContainer">
		<h2>{lang}wcf.acp.adminTools.phpinfo{/lang}</h2>
		{if $diskInfo}{@$diskInfo}{/if}
	</div>
</div>
<div class="border borderMarginRemove" style="overflow:auto;">
{@$atPHP}
</div>
{include file='footer'}
