{include file='header'}

<script type="text/javascript">
//<![CDATA[
function chkDel() {
	var setTo=false;
	var fLen=document.forms['fLostAndFound'].length;
	for(i=0;i<fLen;i++) {
		if(document.forms['fLostAndFound'].elements[i].name == 'lostAndFoundDel[]' && document.forms['fLostAndFound'].elements[i].checked) {
			setTo = true;
			break;
		}
	}
	if(setTo) return confirm('{lang}wcf.acp.adminTools.confirm.delete{/lang}');
	else return false;
}

function sellAll() {
	var setTo=false;
	var fLen=document.forms['fLostAndFound'].length;
	for(i=0;i<fLen;i++) {
		if(document.forms['fLostAndFound'].elements[i].name == 'lostAndFoundDel[]' && !document.forms['fLostAndFound'].elements[i].checked) {
			setTo = true;
			break;
		}
	}
	for(i=0;i<fLen;i++) {
		if(document.forms['fLostAndFound'].elements[i].name == 'lostAndFoundDel[]') {
			document.forms['fLostAndFound'].elements[i].checked = setTo;
		}
	}
}
//]]>
</script>

<div class="mainHeadline">
	<img src="{@RELATIVE_WCF_DIR}icon/searchL.png" alt="" />
	<div class="headlineContainer">
		<h2>{lang}wcf.acp.adminTools.lostAndFound{/lang}</h2>
	</div>
</div>

<div class="contentHeader">
	<div class="largeButtons">
		<ul>
            {if $show == 'lostAndFoundWbbF'}
                <li><a href="index.php?form=AdminToolsLostAndFound&amp;show=lostAndFoundWbbD{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/searchM.png" alt="" /> <span>{lang}wcf.acp.adminTools.laf.legend.db{/lang}</span></a></li>
            {else}
                <li><a href="index.php?form=AdminToolsLostAndFound&amp;show=lostAndFoundWbbF{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/searchM.png" alt="" /> <span>{lang}wcf.acp.adminTools.laf.legend.fs{/lang}</span></a></li>
            {/if}
		</ul>
	</div>
</div>

<form method="post" name="fLostAndFound" action="index.php?form=AdminToolsLostAndFound" onSubmit="return chkDel()">
	<div class="border content">
		<div class="container-1">
            <fieldset>
                <legend style="font-weight:bold;">
                    {if $show == 'lostAndFoundWbbF'}
                        {lang}wcf.acp.adminTools.laf.legend.fs{/lang}
                    {else}
                        {lang}wcf.acp.adminTools.laf.legend.db{/lang}
                    {/if}
                </legend>
                <p class="description">
                    {if $show == 'lostAndFoundWbbF'}
                        {lang}wcf.acp.adminTools.laf.legend.fs.description{/lang}
                    {else}
                        {lang}wcf.acp.adminTools.laf.legend.db.description{/lang}
                    {/if}
                </p>
                <br/>
                {if $lostAndFoundDel|count > 0}
                    <table class="tableList">
                        <thead>
                            <tr class="tableHead">
                                <th class="columnIcon" style="width:80px;"><div><a href="javascript:sellAll()"><img src="{@RELATIVE_WCF_DIR}icon/defaultS.png" alt="" /> ({#$lostAndFoundDel|count})</a></div></th>
                                <th class="columnText"><div><a name="file">{lang}wcf.acp.adminTools.laf.fileName{/lang}</a></div></th>
                                <th class="columnText"><div><a name="size">{lang}wcf.acp.adminTools.laf.fileSize{/lang}</a></div></th>
                                <th class="columnText"><div><a name="date">{lang}wcf.acp.adminTools.laf.fileDate{/lang}</a></div></th>
                                <th class="columnText"><div><a name="user">{lang}wcf.acp.adminTools.laf.fileUser{/lang}</a></div></th>
                            </tr>
                        </thead>
                        <tbody>
                            {cycle values="1,2" print=false advance=false reset=true}
                            {foreach from=$lostAndFoundDel item=del}
                        	    <tr class="container-{cycle values="1,2"}">
                        	        <td class="columnText" style="text-align:center;"><input type="checkbox" name="lostAndFoundDel[]" value="{$del.DELVAL}"></td>
                        	        <td class="columnText smallFont">{@$del.FILE}</td>
                        	        <td class="columnNumbers smallFont">{$del.SIZE}</td>
                        	        <td class="columnDate smallFont">{@$del.TIME|shorttime}</td>
                        	        <td class="columnUsername smallFont">{@$del.USER}</td>
                        	    </tr>
                        	{/foreach}
                        </tbody>
                    </table>
                {else}
                    <p class="info">{lang}wcf.acp.adminTools.laf.empty{/lang}</p>
                {/if}
            </fieldset>
        </div>
    </div>
    <div class="formSubmit">
        <input type="hidden" name="show" value="{$show}" />
        <input type="hidden" name="fDo" value="delete" />
    	<input type="submit" name="send" accesskey="s" value="{lang}wcf.global.button.submit{/lang}" />
    	<input type="reset" name="reset" accesskey="r" value="{lang}wcf.global.button.reset{/lang}" />
    	<input type="hidden" name="packageID" value="{@PACKAGE_ID}" />
    	{@SID_INPUT_TAG}
    </div>
</form>
{include file='footer'}
