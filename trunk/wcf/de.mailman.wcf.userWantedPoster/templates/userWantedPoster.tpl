{include file="documentHeader"}
{* $Id$ *}
<head>
	<title>{lang}wcf.user.wantedPoster.title{/lang} - {lang}wcf.user.profile.members{/lang} - {PAGE_TITLE}</title>
	{include file='headInclude' sandbox=false}
	<script language="javascript">
        function confirmDel(url) {
			if(confirm('{lang}wcf.user.wantedPoster.confirmDelete{/lang}')) window.location=url;
		}
	</script>
</head>
<body>
{* --- quick search controls --- *}
{assign var='searchFieldTitle' value='{lang}wcf.user.profile.search.query{/lang}'}
{capture assign=searchHiddenFields}
	<input type="hidden" name="userID" value="{@$user->userID}" />
{/capture}
{* --- end --- *}
{include file='header' sandbox=false}

<div id="main">

    <ul class="breadCrumbs">
    	<li><a href="index.php?page=Index{@SID_ARG_2ND}"><img src="icon/indexS.png" alt="" /> <span>{PAGE_TITLE}</span></a> &raquo;</li>
    	<li><img src="{@RELATIVE_WCF_DIR}icon/membersS.png" alt="" /> <span>{lang}wcf.user.profile.members{/lang}</span> &raquo;</li>
    </ul>
    
    <div class="mainHeadline">
    	<img src="{@RELATIVE_WCF_DIR}icon/userWantedPosterL.png" alt="" />
    	<div class="headlineContainer">
    		<h2> {lang}wcf.user.wantedPoster.title{/lang}</h2>
    		{if $user->getOldUsername()}
    			<p>{lang}wcf.user.profile.oldUsername{/lang}</p>
    		{/if}
    	</div>
    </div>
    
    {if $userMessages|isset}{@$userMessages}{/if}
    
    {if $this|method_exists:'getUserProfileMenu'}
    	{if $this->getUserProfileMenu()->getMenuItems('')|count > 1}
    		<div class="tabMenu">
    			<ul>
    				{foreach from=$this->getUserProfileMenu()->getMenuItems('') item=item}
    					<li{if $item.menuItem|in_array:$this->getUserProfileMenu()->getActiveMenuItems()} class="activeTabMenu"{/if}><a href="{$item.menuItemLink}">{if $item.menuItemIcon}<img src="{$item.menuItemIcon}" alt="" /> {/if}<span>{lang}{@$item.menuItem}{/lang}</span></a></li>
    				{/foreach}
    			</ul>
    		</div>
    		
    		<div class="subTabMenu">
    			<div class="containerHead">
    				{assign var=activeMenuItem value=$this->getUserProfileMenu()->getActiveMenuItem()}
    				{if $activeMenuItem && $this->getUserProfileMenu()->getMenuItems($activeMenuItem)|count}
    					<ul>
    						{foreach from=$this->getUserProfileMenu()->getMenuItems($activeMenuItem) item=item}
    							<li{if $item.menuItem|in_array:$this->getUserProfileMenu()->getActiveMenuItems()} class="activeSubTabMenu"{/if}><a href="{$item.menuItemLink}">{if $item.menuItemIcon}<img src="{$item.menuItemIcon}" alt="" /> {/if}<span>{lang}{@$item.menuItem}{/lang}</span></a></li>
    						{/foreach}
    					</ul>
    				{else}
    					<div> </div>
    				{/if}
    			</div>
    		</div>
    	{/if}
    {/if}

    <div class="border {if $this|method_exists:'getUserProfileMenu' && $this->getUserProfileMenu()->getMenuItems('')|count > 1}tabMenuContent{else}content{/if}">
        {if $uwpData.insertDate|isset}
            <div class="message content">
            	<div class="messageInner container-1">
            		<div class="messageHeader">
            			<p class="light smallFont">
            			    {lang}wcf.user.wantedPoster.inserted{/lang} {@$uwpData.insertDate|time}
            			    {if $uwpData.updateDate} &bull; {lang}wcf.user.wantedPoster.updated{/lang} {@$uwpData.updateDate|time}{/if}
                            &bull; {lang}wcf.attachment.attachments{/lang}: {@$uwpData.aCnt}
                            &bull; Bytes: {@$uwpData.size}
                            &bull; {lang}wcf.user.wantedPoster.views{/lang} {@$uwpData.views}
            			</p>
            		</div>
            		<div class="messageBody">
            		    {if $uwpData.locked == 1 && $uwpData.lockDate|isset}
                		    <p class="error">{lang}wcf.user.wantedPoster.lockedMessage{/lang}</p>
            		    {/if}
            		    {if $uwpData.locked != 1 || $user->userID == $this->getUser()->userID || $this->user->getPermission('mod.wantedPoster.canLockEntries')}
                			{@$uwpData.text}
                	    {/if}
            			<br />
            		</div>
            		<div class="messageFooter">
                        <div class="smallButtons">
                            <ul>
                                {if $user->userID == $this->getUser()->userID && $this->user->getPermission('user.wantedPoster.canUseWantedPoster')}
            				        <li><a href="javascript:void(0);" onclick="confirmDel('index.php?page=UserWantedPoster&action=delete&userID={$user->userID}{@SID_ARG_2ND}');"><img src="{@RELATIVE_WCF_DIR}icon/deleteS.png" alt="" /> <span>{lang}wcf.user.wantedPoster.delete{/lang}</span></a></li>
            				        {if $this->user->getPermission('mod.wantedPoster.canLockEntries')}
            				            {if $uwpData.locked == 1}
                				            <li><a href="index.php?page=UserWantedPoster&action=unlock&userID={$user->userID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/disabledS.png" alt="" /> <span>{lang}wcf.user.wantedPoster.unlock{/lang}</span></a></li>
                                        {else}
                				            <li><a href="index.php?page=UserWantedPoster&action=lock&userID={$user->userID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/enabledS.png" alt="" /> <span>{lang}wcf.user.wantedPoster.lock{/lang}</span></a></li>
                				        {/if}
            				        {/if}
                                    <li><a href="index.php?form=UserWantedPosterEdit&userID={$user->userID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/editS.png" alt="" /> <span>{lang}wcf.user.wantedPoster.edit{/lang}</span></a></li>
                                {else if $this->user->getPermission('mod.wantedPoster.canLockEntries') || $this->user->getPermission('mod.wantedPoster.canDeleteEntries') || $this->user->getPermission('mod.wantedPoster.canModifyEntries')}
                                    {if $this->user->getPermission('mod.wantedPoster.canDeleteEntries')}<li><a href="javascript:void(0);" onclick="confirmDel('index.php?page=UserWantedPoster&action=delete&userID={$user->userID}{@SID_ARG_2ND}');"><img src="{@RELATIVE_WCF_DIR}icon/deleteS.png" alt="" /> <span>{lang}wcf.user.wantedPoster.delete{/lang}</span></a></li>{/if}
                                    {if $this->user->getPermission('mod.wantedPoster.canModifyEntries')}
                                        <li><a href="index.php?form=UserWantedPosterEdit&userID={$user->userID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/editS.png" alt="" /> <span>{lang}wcf.user.wantedPoster.edit{/lang}</span></a></li>
                                    {/if}
            				        {if $this->user->getPermission('mod.wantedPoster.canLockEntries')}
            				            {if $uwpData.locked == 1}
                				            <li><a href="index.php?page=UserWantedPoster&action=unlock&userID={$user->userID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/disabledS.png" alt="" /> <span>{lang}wcf.user.wantedPoster.unlock{/lang}</span></a></li>
                                        {else}
                				            <li><a href="index.php?page=UserWantedPoster&action=lock&userID={$user->userID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/enabledS.png" alt="" /> <span>{lang}wcf.user.wantedPoster.lock{/lang}</span></a></li>
                				        {/if}
                				    {/if}
                                {/if}
                                {if $this->user->getPermission('user.wantedPoster.canViewMembersListTab')}
                                    <li><a href="index.php?page=UserWantedPosterListMembers{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/userWantedPosterListS.png" alt="" /> <span>{lang}wcf.user.wantedPoster.list.title{/lang}</span></a></li>
                                {else}
                                    <li><a href="index.php?page=UserWantedPosterList{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/userWantedPosterListS.png" alt="" /> <span>{lang}wcf.user.wantedPoster.list.title{/lang}</span></a></li>
                                {/if}
                            </ul>
                        </div>
            		</div>
            	</div>
            </div>
        {else}
        	<div class="message content">
        		<div class="messageInner container-1">
            		<div class="messageBody">
        				{lang}wcf.user.wantedPoster.noEntry{/lang}
        		        <br />
        		    </div>
                    <div class="messageFooter">
                        <div class="smallButtons">
                            <ul>
                    	        {if $this->user->getPermission('mod.wantedPoster.canModifyEntries') || $this->user->getPermission('user.wantedPoster.canUseWantedPoster') && $user->userID == $this->getUser()->userID}
                                    <li><a href="index.php?form=UserWantedPosterEdit&userID={$user->userID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/editS.png" alt="" /> <span>{lang}wcf.user.wantedPoster.create{/lang}</span></a></li>
                                {/if}
                                {if $this->user->getPermission('user.wantedPoster.canViewMembersListTab')}
                                    <li><a href="index.php?page=UserWantedPosterListMembers{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/userWantedPosterListS.png" alt="" /> <span>{lang}wcf.user.wantedPoster.list.title{/lang}</span></a></li>
                                {else}
                                    <li><a href="index.php?page=UserWantedPosterList{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/userWantedPosterListS.png" alt="" /> <span>{lang}wcf.user.wantedPoster.list.title{/lang}</span></a></li>
                                {/if}
                			</ul>
                  	    </div>
                    </div>
        		</div>
        	</div>
        {/if}
    </div>
</div>
{include file='footer' sandbox=false}
</body>
</html>
