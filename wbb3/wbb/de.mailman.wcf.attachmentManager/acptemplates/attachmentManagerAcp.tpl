{include file='header'}
<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/MultiPagesLinks.class.js"></script>
<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/Suggestion.class.js"></script>
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

<div class="mainHeadline">
	<img src="{@RELATIVE_WCF_DIR}icon/attachmentManagerL.png" alt="" />
	<div class="headlineContainer">
		<h2>{lang}wcf.user.attachmentManager.title{/lang}</h2>
        {lang}wcf.user.attachmentManager.info.totalAttachments{/lang} {#$attachmentTotalInfo.cnt}
        &bull; {lang}wcf.user.attachmentManager.info.totalDownloads{/lang} {#$attachmentTotalInfo.downloads}
        &bull; {lang}wcf.user.attachmentManager.info.totalMemory{/lang} {@$attachmentTotalInfo.attachmentSize}
	</div>
</div>

{if !$tplError|empty}{@$tplError}{/if}
{if !$tplWarning|empty}{@$tplWarning}{/if}
{if !$tplInfo|empty}{@$tplInfo}{/if}

<div class="border content">
    <div class="container-1">
        <form method="post" action="index.php?page=AttachmentManagerAcp">
            <fieldset>
            	<legend><img src="{@RELATIVE_WCF_DIR}icon/attachmentManagerFilterM.png" alt="" /> {lang}wcf.acp.attachmentManager.filterTitle{/lang}</legend>
            	<div class="formElement">
            		<div class="formFieldLabel">
            			<label for="username">{lang}wcf.acp.attachmentManager.filterUser{/lang}</label>
            		</div>
            		<div class="formField">
            			<input type="text" class="inputText" id="username" name="username" value="{if !$username|empty}{$username}{/if}" />
            			<script type="text/javascript">
            				//<![CDATA[
            				suggestion.enableMultiple(false);
            				suggestion.init('username');
            				//]]>
            			</script>
            		</div>
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
                	<input type="hidden" name="packageID" value="{@PACKAGE_ID}" />
                    {@SID_INPUT_TAG}
                	<input type="submit" accesskey="s" value="{lang}wcf.global.button.submit{/lang}" />
                	<input type="reset" accesskey="r" value="{lang}wcf.global.button.reset{/lang}" />
                </div>
            </fieldset>
        </form>
        <form method="post" name="fThumbnail" action="index.php?page=AttachmentManagerAcp">
            <img src="{@RELATIVE_WCF_DIR}icon/fileTypeIconPictureM.png" alt="" />
            <input type="checkbox" name="showThumbnails" value="1"{if !$showThumbnails|empty} checked="checked"{/if} onClick="this.form.submit()" />
            {lang}wcf.user.attachmentManager.list.showThumbnails{/lang}
            <input type="hidden" name="fDo" value="switchThumbnails" />
            <input type="hidden" name="sortField" value="{$sortField}" />
            <input type="hidden" name="sortOrder" value="{$sortOrder}" />
            <input type="hidden" name="pageNo" value="{$pageNo}" />
            <input type="hidden" name="userID" value="{$userID}" />
            <input type="hidden" name="packageID" value="{@PACKAGE_ID}" />
            {@SID_INPUT_TAG}
        </form>
    </div>
</div>

{if $attachments|count}
    <div class="contentHeader">
    	{pages print=true assign=pagesLinks link="index.php?page=AttachmentManagerAcp&pageNo=%d&sortField=$sortField&sortOrder=$sortOrder&userID=$userID&packageID="|concat:PACKAGE_ID:SID_ARG_2ND_NOT_ENCODED}
    </div>
    <div class="border">
    	<div class="containerHead"><h3>
    	    {if $attachmentInfo|isset}
    	        {if !$username|empty}
    	            <img src="{@RELATIVE_WCF_DIR}icon/userS.png" alt="" />
    	            {@$username}
    	        {/if}
    	        <img src="{@RELATIVE_WCF_DIR}icon/attachmentManagerS.png" alt="" />
                {lang}wcf.user.attachmentManager.info.cntAttachments{/lang} {#$attachmentInfo.cnt}
                &bull; {lang}wcf.user.attachmentManager.info.cntDownloads{/lang} {#$attachmentInfo.downloads}
                &bull; {lang}wcf.user.attachmentManager.info.cntMemory{/lang} {@$attachmentInfo.attachmentSize}
            {else}
                {lang}wcf.acp.menu.link.content.attachmentManager{/lang}
            {/if}
        </h3></div>
    </div>
    <div class="border borderMarginRemove">
        <form method="post" name="deleteAttachments" action="index.php?page=AttachmentManagerAcp" onSubmit="{if $this->user->getPermission('admin.general.attachmentManager.canDelete')}return checkDelAttachments(this.name){else}return false{/if}">
        	<table class="tableList">
        		<thead>
                    <tr class="tableHead">
                    	{if $this->user->getPermission('admin.general.attachmentManager.canDelete')}
                    	    <th class="columnIcon"><div><a href="javascript:selAll('deleteAttachments')"><img src="{@RELATIVE_WCF_DIR}icon/defaultS.png" alt="" /></a></div></th>
                    	{/if}
                        <th class="columnIcon{if $sortField == 'fileType'} active{/if}">
                        	<div><a href="{$thisPage}&amp;sortField=fileType&amp;sortOrder={if $sortField == 'fileType' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{if $additionalParameters|isset}{@$additionalParameters}{/if}{@SID_ARG_2ND}">
                        		<img src="{@RELATIVE_WCF_DIR}icon/attachmentManagerS.png" alt="{lang}wcf.user.attachmentManager.list.fileType{/lang}" title="{lang}wcf.user.attachmentManager.list.fileType{/lang}" />
                        		{if $sortField == 'fileType'}<img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}
                        	</a></div>
                        </th>
                        {if $username|empty}
                            <th class="columnUsername{if $sortField == 'username'} active{/if}">
                            	<div><a href="{$thisPage}&amp;sortField=username&amp;sortOrder={if $sortField == 'username' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{if $additionalParameters|isset}{@$additionalParameters}{/if}{@SID_ARG_2ND}">
                            		{lang}wcf.user.username{/lang}
                            		{if $sortField == 'username'}<img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}
                            	</a></div>
                            </th>
                        {/if}

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
                			{if $this->user->getPermission('admin.general.attachmentManager.canDelete')}
                    			<td class="columnIcon"><input type="checkbox" name="delAttachment[]" value="{$attachment.attachmentID}"></td>
                    	    {/if}
                            <td class="columnIcon">{@$attachment.mimeIcon}</td>
                			{if $username|empty}
                			    <td class="columnUsername smallFont">{@$attachment.username}</td>
                			{/if}
                            <td class="columnText smallFont">{@$attachment.messageTypeUrl}</td>
                            <td class="columnText smallFont">{@$attachment.attachmentUrl}</td>
                            <td class="columnNumbers smallFont">{$attachment.attachmentSize}</td>
                            <td class="columnNumbers smallFont">{#$attachment.downloads}</td>
                            <td class="columnDate smallFont">{if $attachment.lastDownloadTime|empty}&nbsp;{else}{@$attachment.lastDownloadTime|time:"%d.%m.%Y"}{/if}</td>
                            <td class="columnDate smallFont">{@$attachment.uploadTime|time:"%d.%m.%Y"}</td>
                		</tr>
                	{/foreach}
                </tbody>
        	</table>

            {if $this->user->getPermission('admin.general.attachmentManager.canDelete')}
                <div class="formSubmit">
                	<input type="hidden" name="fDo" value="delete" />
                	<input type="hidden" name="sortField" value="{$sortField}" />
                	<input type="hidden" name="sortOrder" value="{$sortOrder}" />
                	<input type="hidden" name="packageID" value="{@PACKAGE_ID}" />
                	<input type="hidden" name="userID" value="{$userID}" />
                    {@SID_INPUT_TAG}
                	<input type="submit" accesskey="s" value="{lang}wcf.global.button.submit{/lang}" />
                	<input type="reset" accesskey="r" value="{lang}wcf.global.button.reset{/lang}" />
                </div>
            {/if}
        </form>
    </div>
    <div class="contentFooter">
    	{@$pagesLinks}
    </div>
{else}
    <div class="border">
        <div class="containerHead"><h3>
    	    {if !$username|empty}
    	        <img src="{@RELATIVE_WCF_DIR}icon/userS.png" alt="" />
    	        {@$username}
    	    {else}
    	        &nbsp;
    	    {/if}
        </h3></div>
    </div>
    <div class="border borderMarginRemove">
        {lang}wcf.user.attachmentManager.empty{/lang}
    </div>
{/if}
{include file='footer'}
