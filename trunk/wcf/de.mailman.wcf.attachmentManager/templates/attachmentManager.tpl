{include file="documentHeader"}
{* $Id$ *}
<head>
	<title>{lang}wcf.user.attachmentManager.title{/lang} - {lang}wcf.user.usercp{/lang} - {PAGE_TITLE}</title>
	{include file='headInclude' sandbox=false}
	<script type="text/javascript">
    //<![CDATA[
    function selAll(fName) {
    	var setTo=false;
    	var fLen=document.forms[fName].length;
    	for(i=0;i<fLen;i++) {
    		if(document.forms[fName].elements[i].type == 'checkbox' && !document.forms[fName].elements[i].checked) {
    			setTo = true;
    			break;
    		}
    	}
    	for(i=0;i<fLen;i++) {
    		if(document.forms[fName].elements[i].type == 'checkbox') {
    			document.forms[fName].elements[i].checked = setTo;
    		}
    	}
    }

    function checkDelAttachments(fName) {
    	var setTo=false;
    	var fLen=document.forms[fName].length;
    	for(i=0;i<fLen;i++) {
    		if(document.forms[fName].elements[i].type == 'checkbox' && document.forms[fName].elements[i].checked) {
    			setTo = true;
    			break;
    		}
    	}
    	if(setTo) {
    	    return confirm('{lang}wcf.user.attachmentManager.confirm.delete{/lang}');
    	} else {
    	    alert('{lang}wcf.user.attachmentManager.alert.nothingSelected{/lang}');
    	    return false;
    	}
    }

    //]]>
	</script>
	<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/MultiPagesLinks.class.js"></script>
</head>
<body>
{include file='header' sandbox=false}
<div id="main">
    {if !$tplError|empty}{@$tplError}{/if}
    {if !$tplWarning|empty}{@$tplWarning}{/if}
    {if !$tplInfo|empty}{@$tplInfo}{/if}

	{include file="userCPHeader"}

    <div class="border tabMenuContent">
    	<div class="container-1">
    		<h3 class="subHeadline"><img src="{@RELATIVE_WCF_DIR}icon/attachmentManagerM.png" alt="" /> {lang}wcf.user.attachmentManager.title{/lang}</h3>
    		
            {if $attachments|count}
                {lang}wcf.user.attachmentManager.info.totalAttachments{/lang} {#$attachmentTotalInfo.cnt}
                &bull; {lang}wcf.user.attachmentManager.info.totalDownloads{/lang} {#$attachmentTotalInfo.downloads}
                &bull; {lang}wcf.user.attachmentManager.info.totalMemory{/lang} {@$attachmentTotalInfo.attachmentSize}
    		    &bull; {lang}wcf.pm.title{/lang}: {#$this->user->pmTotalCount}{if $usage|isset} ({@$usage*100|floor}%){/if}
                <form method="post" action="index.php?page=AttachmentManager">
                    <fieldset>
                    	<legend><img src="{@RELATIVE_WCF_DIR}icon/attachmentManagerFilterM.png" alt="" /> {lang}wcf.acp.attachmentManager.filterTitle{/lang}</legend>
                    	<div class="formElement">
                    		<div class="formFieldLabel">
                    			<label for="showOnlyMessageType">{lang}wcf.acp.attachmentManager.filterMessageType{/lang}</label>
                    		</div>
                    		<div class="formField">
                    			<select name="showOnlyMessageType" id="showOnlyMessageType" style="min-width:200px;" onChange="this.form.submit()">
                    			    <option value=""> </option>
                                    {foreach from=$messageTypes item=value}
                                        <option value="{$value.messageType}"{if $showOnlyMessageType == $value.messageType} selected="selected"{/if}> {$value.messageType} ({#$value.cnt})</option>
                                    {/foreach}
                                </select>
                    		</div>
                    		<div class="formFieldLabel">
                    			<label for="showOnlyFileType">{lang}wcf.acp.attachmentManager.filterFileType{/lang}</label>
                    		</div>
                    		<div class="formField">
                    			<select name="showOnlyFileType" id="showOnlyFileType" style="min-width:200px;" onChange="this.form.submit()">
                    			    <option value=""> </option>
                                    {foreach from=$fileTypes item=value}
                                        <option value="{$value.fileType}"{if $showOnlyFileType == $value.fileType} selected="selected"{/if}> {$value.fileType} ({#$value.cnt})</option>
                                    {/foreach}
                                </select>
                    			<label>
                        			<img src="{@RELATIVE_WCF_DIR}icon/fileTypeIconPictureM.png" alt="" />
                    			    <input type="checkbox" name="showOnlyImages" value="1" onClick="this.form.submit()" {if !$showOnlyImages|empty}checked="checked" {/if} /> {lang}wcf.acp.attachmentManager.filterShowOnlyImages{/lang}
                    			</label>
                    		</div>
                    	</div>
                        <div class="formSubmit">
                        	<input type="hidden" name="fDo" value="setFilter" />
                        	<input type="hidden" name="sortField" value="{$sortField}" />
                        	<input type="hidden" name="sortOrder" value="{$sortOrder}" />
                        	<input type="submit" accesskey="s" value="{lang}wcf.global.button.submit{/lang}" />
                        	<input type="reset" accesskey="r" value="{lang}wcf.global.button.reset{/lang}" />
                        </div>
                    </fieldset>
                </form>
                {if $wbbExists}
                    <form method="post" name="fThumbnail" action="index.php?page=AttachmentManager">
                        <img src="{@RELATIVE_WCF_DIR}icon/fileTypeIconPictureM.png" alt="" />
                        <input type="checkbox" name="showThumbnails" value="1"{if !$showThumbnails|empty} checked="checked"{/if} onClick="this.form.submit()" />
                        {lang}wcf.user.attachmentManager.list.showThumbnails{/lang}
                        <input type="hidden" name="fDo" value="switchThumbnails" />
                        <input type="hidden" name="sortField" value="{$sortField}" />
                        <input type="hidden" name="sortOrder" value="{$sortOrder}" />
                        <input type="hidden" name="pageNo" value="{$pageNo}" />
                    </form>
                {/if}
            {else}
                {lang}wcf.user.attachmentManager.empty{/lang}
            {/if}
    	</div>
    </div>

    {if $attachments|count}
        <div class="contentHeader">
        	{pages print=true assign=pagesLinks link="index.php?page=AttachmentManager&pageNo=%d&sortField=$sortField&sortOrder=$sortOrder"}
        </div>
    
        <div class="border">
        	<div class="containerHead"><h3>
                <img src="{@RELATIVE_WCF_DIR}icon/userS.png" alt="" />
                {$this->user->username}
                <img src="{@RELATIVE_WCF_DIR}icon/attachmentManagerS.png" alt="" />
    		    {lang}wcf.user.attachmentManager.info.cntAttachments{/lang} {#$attachmentInfo.cnt}
    		    &bull; {lang}wcf.user.attachmentManager.info.cntDownloads{/lang} {#$attachmentInfo.downloads}
    		    &bull; {lang}wcf.user.attachmentManager.info.cntMemory{/lang} {@$attachmentInfo.attachmentSize}
            </h3></div>
        </div>
        <div class="border borderMarginRemove">
            <form method="post" name="deleteAttachments" action="index.php?page=AttachmentManager" onSubmit="{if $this->user->getPermission('user.profile.attachmentManager.canDelete')}return checkDelAttachments(this.name){else}return false{/if}">
            	<table class="tableList">
            		<thead>
                        <tr class="tableHead">
                        	{if $this->user->getPermission('user.profile.attachmentManager.canDelete')}
                        	    <th class="columnIcon"><div><a href="javascript:selAll('deleteAttachments')"><img src="{@RELATIVE_WCF_DIR}icon/defaultS.png" alt="" /></a></div></th>
                        	{/if}
                            <th class="columnIcon{if $sortField == 'fileType'} active{/if}">
                            	<div><a href="{$thisPage}&amp;sortField=fileType&amp;sortOrder={if $sortField == 'fileType' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{if $additionalParameters|isset}{@$additionalParameters}{/if}{@SID_ARG_2ND}">
                            		<img src="{@RELATIVE_WCF_DIR}icon/attachmentManagerS.png" alt="{lang}wcf.user.attachmentManager.list.fileType{/lang}" title="{lang}wcf.user.attachmentManager.list.fileType{/lang}" />
                            		{if $sortField == 'fileType'}<img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}
                            	</a></div>
                            </th>
                            <th class="columnText{if $sortField == 'messageType'} active{/if}">
                            	<div><a href="{$thisPage}&amp;sortField=messageType&amp;sortOrder={if $sortField == 'messageType' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{if $additionalParameters|isset}{@$additionalParameters}{/if}{@SID_ARG_2ND}">
                            		{lang}wcf.user.attachmentManager.list.messageType{/lang}
                            		{if $sortField == 'messageType'}<img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}
                            	</a></div>
                            </th>
                            <th class="columnText{if $sortField == 'attachmentName'} active{/if}">
                            	<div><a href="{$thisPage}&amp;sortField=attachmentName&amp;sortOrder={if $sortField == 'attachmentName' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{if $additionalParameters|isset}{@$additionalParameters}{/if}{@SID_ARG_2ND}">
                            		{lang}wcf.user.attachmentManager.list.fileName{/lang}
                            		{if $sortField == 'attachmentName'}<img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}
                            	</a></div>
                            </th>
                            <th class="columnNumbers{if $sortField == 'attachmentSize'} active{/if}">
                            	<div><a href="{$thisPage}&amp;sortField=attachmentSize&amp;sortOrder={if $sortField == 'attachmentSize' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{if $additionalParameters|isset}{@$additionalParameters}{/if}{@SID_ARG_2ND}">
                            		{lang}wcf.user.attachmentManager.list.size{/lang}
                            		{if $sortField == 'attachmentSize'}<img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}
                            	</a></div>
                            </th>
                            <th class="columnNumbers{if $sortField == 'downloads'} active{/if}">
                            	<div><a href="{$thisPage}&amp;sortField=downloads&amp;sortOrder={if $sortField == 'downloads' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{if $additionalParameters|isset}{@$additionalParameters}{/if}{@SID_ARG_2ND}">
                            		{lang}wcf.user.attachmentManager.list.downloads{/lang}
                            		{if $sortField == 'downloads'}<img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}
                            	</a></div>
                            </th>
                            <th class="columnDate{if $sortField == 'lastDownloadTime'} active{/if}">
                            	<div><a href="{$thisPage}&amp;sortField=lastDownloadTime&amp;sortOrder={if $sortField == 'lastDownloadTime' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{if $additionalParameters|isset}{@$additionalParameters}{/if}{@SID_ARG_2ND}">
                            		{lang}wcf.user.attachmentManager.list.lastDownload{/lang}
                            		{if $sortField == 'lastDownloadTime'}<img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}
                            	</a></div>
                            </th>
                            <th class="columnDate{if $sortField == 'uploadTime'} active{/if}">
                            	<div><a href="{$thisPage}&amp;sortField=uploadTime&amp;sortOrder={if $sortField == 'uploadTime' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{if $additionalParameters|isset}{@$additionalParameters}{/if}{@SID_ARG_2ND}">
                            		{lang}wcf.user.attachmentManager.list.upload{/lang}
                            		{if $sortField == 'uploadTime'}<img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}
                            	</a></div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        {cycle values='container-1,container-2' print=false advance=false}
                    	{foreach from=$attachments item=attachment}
                    		<tr class="{cycle}">
                    			{if $this->user->getPermission('user.profile.attachmentManager.canDelete')}
                    			    <td class="columnIcon"><input type="checkbox" name="delAttachment[]" value="{$attachment.attachmentID}"></td>
                    			{/if}
                                <td class="columnIcon">{@$attachment.mimeIcon}</td>
                                <td class="columnText">{@$attachment.messageTypeUrl}</td>
                                <td class="columnText">{@$attachment.attachmentUrl}</td>
                                <td class="columnNumbers">{$attachment.attachmentSize}</td>
                                <td class="columnNumbers">{#$attachment.downloads}</td>
                                <td class="columnDate smallFont">{if $attachment.lastDownloadTime|empty}&nbsp;{else}{@$attachment.lastDownloadTime|shorttime}{/if}</td>
                                <td class="columnDate smallFont">{@$attachment.uploadTime|shorttime}</td>
                    		</tr>
                    	{/foreach}
                    </tbody>
            	</table>
    
                {if $this->user->getPermission('user.profile.attachmentManager.canDelete')}
                    <div class="formSubmit">
                    	<input type="hidden" name="fDo" value="delete" />
                    	<input type="hidden" name="sortField" value="{$sortField}" />
                    	<input type="hidden" name="sortOrder" value="{$sortOrder}" />
                    	<input type="submit" accesskey="s" value="{lang}wcf.global.button.submit{/lang}" />
                    	<input type="reset" accesskey="r" value="{lang}wcf.global.button.reset{/lang}" />
                    </div>
                {/if}
            </form>
        </div>
        <div class="contentFooter">
        	{@$pagesLinks}
        </div>
    {/if}
</div>
{include file='footer' sandbox=false}
</body>
</html>