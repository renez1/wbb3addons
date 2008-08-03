    {if $this->user->getPermission('user.wantedPoster.canViewWantedPoster') && $uwpData|isset && !$cntEntries|empty}
        <div class="border">
        	{cycle values='container-1,container-2' print=false advance=false}
        	{if $uwpData|count > 0}
                <table class="tableList membersList">
                	<thead>
                		<tr class="tableHead">
                			<th class="columnIcon{if $sortField == 'userID'} active{/if}">
                				<div><a href="index.php?page={$thisPage}&amp;sortField=userID&amp;sortOrder={if $sortField == 'userID' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{if $additionalParameters|isset}{@$additionalParameters}{/if}{@SID_ARG_2ND}">
                					<img src="{@RELATIVE_WCF_DIR}icon/userWantedPosterS.png" alt="" />
                					{if $sortField == 'userID'}<img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}
                				</a></div>
                			</th>
                			<th class="columnUsername{if $sortField == 'username'} active{/if}">
                				<div><a href="index.php?page={$thisPage}&amp;sortField=username&amp;sortOrder={if $sortField == 'username' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{if $additionalParameters|isset}{@$additionalParameters}{/if}{@SID_ARG_2ND}">
                					{lang}wcf.user.wantedPoster.list.username{/lang}
                					{if $sortField == 'username'}<img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}
                				</a></div>
                			</th>
                			{if $this->user->getPermission('user.wantedPoster.listView.showAvatar')}
                				<th class="columnAvatar{if $sortField == 'avatarID'} active{/if}">
                					<div><a href="index.php?page={$thisPage}&amp;sortField=avatarID&amp;sortOrder={if $sortField == 'avatarID' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{if $additionalParameters|isset}{@$additionalParameters}{/if}{@SID_ARG_2ND}">
                						{lang}wcf.user.wantedPoster.list.avatar{/lang}
                						{if $sortField == 'avatarID'}<img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}
                					</a></div>
                				</th>
                		    {/if}
                		    {if $this->user->getPermission('user.wantedPoster.listView.showCreateDate')}
                			    <th class="columnLastActivity{if $sortField == 'insertDate'} active{/if}" style="width:160px;">
                					<div><a href="index.php?page={$thisPage}&amp;sortField=insertDate&amp;sortOrder={if $sortField == 'insertDate' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{if $additionalParameters|isset}{@$additionalParameters}{/if}{@SID_ARG_2ND}">
                						{lang}wcf.user.wantedPoster.list.created{/lang}
                						{if $sortField == 'insertDate'}<img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}
                					</a></div>
                				</th>
                		    {/if}
                		    {if $this->user->getPermission('user.wantedPoster.listView.showModDate')}
                				<th class="columnLastActivity{if $sortField == 'updateDate'} active{/if}" style="width:160px;">
                					<div><a href="index.php?page={$thisPage}&amp;sortField=updateDate&amp;sortOrder={if $sortField == 'updateDate' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{if $additionalParameters|isset}{@$additionalParameters}{/if}{@SID_ARG_2ND}">
                						{lang}wcf.user.wantedPoster.list.updated{/lang}
                						{if $sortField == 'updateDate'}<img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}
                					</a></div>
                				</th>
                			{/if}
                			{if $this->user->getPermission('user.wantedPoster.listView.showBytes')}
                				<th class="columnNumbers{if $sortField == 'size'} active{/if}" style="width:100px;">
                					<div><a href="index.php?page={$thisPage}&amp;sortField=size&amp;sortOrder={if $sortField == 'size' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{if $additionalParameters|isset}{@$additionalParameters}{/if}{@SID_ARG_2ND}">
                						{lang}Bytes{/lang}
                						{if $sortField == 'size'}<img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}
                					</a></div>
                				</th>
                			{/if}
                			{if $this->user->getPermission('user.wantedPoster.listView.showAttachments')}
                				<th class="columnNumbers{if $sortField == 'aCnt'} active{/if}" style="width:100px;">
                					<div><a href="index.php?page={$thisPage}&amp;sortField=aCnt&amp;sortOrder={if $sortField == 'aCnt' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{if $additionalParameters|isset}{@$additionalParameters}{/if}{@SID_ARG_2ND}">
                						{lang}wcf.attachment.attachments{/lang}
                						{if $sortField == 'aCnt'}<img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}
                					</a></div>
                				</th>
                			{/if}
                			{if $this->user->getPermission('user.wantedPoster.listView.showViews')}
                				<th class="columnNumbers{if $sortField == 'views'} active{/if}" style="width:100px;">
                					<div><a href="index.php?page={$thisPage}&amp;sortField=views&amp;sortOrder={if $sortField == 'views' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{if $additionalParameters|isset}{@$additionalParameters}{/if}{@SID_ARG_2ND}">
                						{lang}wcf.user.wantedPoster.list.views{/lang}
                						{if $sortField == 'views'}<img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}
                					</a></div>
                				</th>
                			{/if}
                            {if $this->user->getPermission('mod.wantedPoster.canLockEntries') || $this->user->getPermission('mod.wantedPoster.canDeleteEntries') || $this->user->getPermission('mod.wantedPoster.canModifyEntries')}
                                <th class="columnIcon{if $sortField == 'locked'} active{/if}" style="width:80px;">
                                	<div><a href="index.php?page={$thisPage}&amp;sortField=locked&amp;sortOrder={if $sortField == 'locked' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{if $additionalParameters|isset}{@$additionalParameters}{/if}{@SID_ARG_2ND}">
                                		{lang}wcf.user.wantedPoster.list.action{/lang}
                                		{if $sortField == 'locked'}<img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}
                                	</a></div>
                                </th>
                			{/if}
                
                			{if $additionalColumns|isset}{@$additionalColumns}{/if}
                		</tr>
                	</thead>
                	<tbody>
                		{foreach from=$uwpData item=user}
                			<tr class="{cycle}">
                				<td class="columnIcon"><a href="index.php?page=UserWantedPoster&userID={$user.userID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/userWantedPosterLinkM.png" alt="" /></a></td>
                				<td class="columnUsername">{@$user.username}</td>
                				{if $this->user->getPermission('user.wantedPoster.listView.showAvatar')}
                				    <td class="columnAvatar">{@$user.avatar}</td>
                				{/if}
                				{if $this->user->getPermission('user.wantedPoster.listView.showCreateDate')}
                					<td class="columnLastActivity">{@$user.insertDate|shorttime}</td>
                				{/if}
                				{if $this->user->getPermission('user.wantedPoster.listView.showModDate')}
                					<td class="columnLastActivity">{if $user.updateDate}{@$user.updateDate|shorttime}{else}-{/if}</td>
                				{/if}
                				{if $this->user->getPermission('user.wantedPoster.listView.showBytes')}
                					<td class="columnNumbers">{@$user.size}</td>
                				{/if}
                				{if $this->user->getPermission('user.wantedPoster.listView.showAttachments')}
                					<td class="columnNumbers">{@$user.aCnt}</td>
                				{/if}
                				{if $this->user->getPermission('user.wantedPoster.listView.showViews')}
                					<td class="columnNumbers">{@$user.views}</td>
                				{/if}
                
                                {if $this->user->getPermission('mod.wantedPoster.canLockEntries') || $this->user->getPermission('mod.wantedPoster.canDeleteEntries') || $this->user->getPermission('mod.wantedPoster.canModifyEntries')}
                                    <th class="columnIcon" style="text-align:center;">
                                        {if $this->user->getPermission('mod.wantedPoster.canLockEntries')}
                                            {if $user.locked == 1}
                                                <a href="index.php?page=UserWantedPoster&action=unlock&userID={$user.userID}{@SID_ARG_2ND}" title="{lang}wcf.user.wantedPoster.unlock{/lang}"><img src="{@RELATIVE_WCF_DIR}icon/disabledS.png" alt="" /></a>
                                            {else}
                                                <a href="index.php?page=UserWantedPoster&action=lock&userID={$user.userID}{@SID_ARG_2ND}" title="{lang}wcf.user.wantedPoster.lock{/lang}"><img src="{@RELATIVE_WCF_DIR}icon/enabledS.png" alt="" /></a>
                                            {/if}
                                        {/if}
                                        {if $this->user->getPermission('mod.wantedPoster.canModifyEntries')}
                                            <a href="index.php?form=UserWantedPosterEdit&userID={$user.userID}{@SID_ARG_2ND}" title="{lang}wcf.user.wantedPoster.edit{/lang}"><img src="{@RELATIVE_WCF_DIR}icon/editS.png" alt="" /></a>
                                        {/if}
                                        {if $this->user->getPermission('mod.wantedPoster.canDeleteEntries')}
                                            <a href="javascript:void(0);" onclick="confirmDel('index.php?page=UserWantedPoster&action=delete&userID={$user.userID}{@SID_ARG_2ND}');" title="{lang}wcf.user.wantedPoster.delete{/lang}"><img src="{@RELATIVE_WCF_DIR}icon/deleteS.png" alt="" /></a>
                                        {/if}
                                    </th>
                                {/if}
                			    {if $user.additionalColumns|isset}{@$user.additionalColumns}{/if}
                			</tr>
                		{/foreach}
                	</tbody>
                </table>
        	{/if}
        </div>
    {else if $this->user->getPermission('user.wantedPoster.canViewWantedPoster') && $cntEntries|empty}
		<div class="border tabMenuContent">
			<div class="container-1">
				{lang}wcf.user.wantedPoster.list.empty{/lang}
			</div>
		</div>
    {/if}

    <div class="contentFooter">
        {if !$pagesLinks|isset}
            {pages print=true assign=pagesLinks link="index.php?page=$thisPage&pageNo=%d&sortField=$sortField&sortOrder=$sortOrder"}
        {else}
            {@$pagesLinks}
        {/if}
    	{if $this->user->getPermission('user.wantedPoster.canUseWantedPoster')}
            <div class="largeButtons">
                <ul>
    			    <li><a href="index.php?form=UserWantedPosterEdit&userID={$this->user->userID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/editM.png" alt="" /> <span>{lang}wcf.user.wantedPoster.list.entry{/lang}</span></a></li>
    			</ul>
    	    </div>
    	{/if}
    </div>