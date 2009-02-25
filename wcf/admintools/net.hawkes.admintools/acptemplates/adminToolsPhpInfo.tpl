{* $Id: adminToolsPhpInfo.tpl 95 2008-08-31 14:25:49Z wbb3addons $ *}
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
	<img src="{@RELATIVE_WCF_DIR}icon/adminToolsPHPInfoL.png" alt="" />
	<div class="headlineContainer">
		<h2>{lang}wcf.acp.admintools.phpinfo{/lang}</h2>
		{if $diskInformation|count}<p>{lang}wcf.acp.admintools.diskinformation{/lang}</p>{/if}
	</div>
</div>
<div class="border borderMarginRemove" style="overflow:auto;">
{@$phpInfoOutput}
</div>
{include file='footer'}
