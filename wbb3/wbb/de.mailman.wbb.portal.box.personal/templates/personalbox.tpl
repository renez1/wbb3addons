{* $Id$ *}
{if $this->user->getPermission('user.profile.personalbox.canView')}
    {if !$pbCatVertOffset}{assign var='pbCatVertOffset' value=8}{/if}
    {if !$pbFirstBoxColor}{assign var='pbFirstBoxColor' value=1}{/if}
    {if !$pbSecondBoxColor}{assign var='pbSecondBoxColor' value=2}{/if}
    {if !$pbFirstColWidth}{assign var='pbFirstColWidth' value=20}{/if}
    {if !$pbTableWidth}{assign var='pbTableWidth' value='99%'}{/if}
    {if !$pbCellPadding}{assign var='pbCellPadding' value=0}{/if}
    {cycle values="$pbFirstBoxColor,$pbSecondBoxColor" print=false advance=false reset=true}
    <div class="border" id="box{$boxID}">
        <div class="containerHead">
        	<div class="containerIcon">
        		<a href="javascript: void(0)" onclick="openList('personalbox', true)">
            	<img src="{@RELATIVE_WCF_DIR}icon/minusS.png" id="personalboxImage" alt="" /></a>
            </div>
            <div class="containerContent">
                {if $this->user->userID}
                    <a href="index.php?form=UserProfileEdit&category=settings.display{@SID_ARG_2ND}">{lang}wbb.portal.box.personalbox.title{/lang}</a>
                {else}
                    {lang}wbb.portal.box.personalbox.offlineTitle{/lang}
                {/if}
            </div>
        </div>
        <div class="container-1" id="personalbox"{if $pbMaxHeight >= 100} style="max-height:{$pbMaxHeight}px; overflow:auto;"{/if}>
        	<div class="containerContent">
                {if $item.user|isset}
                    <div class="container-{cycle values="$pbFirstBoxColor,$pbSecondBoxColor"}" style="float:none;">
                        <div class="containerContent" style="text-align:center; float:none;"><a href="index.php?page=User&amp;userID={@$item.user->userID}{@SID_ARG_2ND}">{@$item.user->username}</a>{if $pbShowProfileHits} <span class="smallFont">({#$item.user->profileHits})</span>{/if}</div>
                        {if $pbShowAvatar == true && $item.user->getAvatar()}
		    				{if $pbAvatarMaxWidth > 0 && $pbAvatarMaxHeight > 0}
	    						{assign var=dummy value=$item.user->getAvatar()->setMaxSize($pbAvatarMaxWidth, $pbAvatarMaxHeight)}
    						{/if}
                            <div class="containerContent" style="text-align:center; float:none; margin:0; padding:0;"><a href="index.php?form=UserProfileEdit{@SID_ARG_2ND}" title="{lang}wcf.user.usercp{/lang}">{@$item.user->getAvatar()}</a></div>
                        {/if}
                    </div>

                    {if $pbShowPersonal == true}
                        <div style="font-size:1px; height:{$pbCatVertOffset}px; float:none;">&nbsp;</div>

                        <div class="container-{cycle values="$pbFirstBoxColor,$pbSecondBoxColor"}" style="float:none;">
                            <table cellpadding="{$pbCellPadding}" cellspacing="0" border="0" style="width:{$pbTableWidth};">
                                <colgroup><col style="width:{$pbFirstColWidth}px;" /></colgroup>

                                {if $pbLargeImages == true}
                                    <tr><td style="text-align:center;" colspan="2">{@$pbRankImage}</td></tr>
                                {else}
                                    <tr><td class="smallFont" style="text-align:center;" colspan="2">{@$pbRankImage}
                                        {if $pbLineFeedRank == true}<br />{/if}{lang}{$item.user->rankTitle}{/lang}
                                    </td></tr>
                                {/if}
                                <tr>
                                    <td><img src="{@RELATIVE_WCF_DIR}icon/dateS.png" alt="" title="{lang}wcf.user.registrationDate{/lang}" /></td>
                                    <td class="smallFont">{$this->user->registrationDate|date}</td>
                                </tr>
                                {if $this->user->getPermission('user.profile.personalbox.cntOwnPosts')}
                                    <tr>
                                        <td><img src="{@RELATIVE_WCF_DIR}icon/codeS.png" alt="" title="{lang}wcf.user.posts{/lang}" /></td>
                                        <td class="smallFont">{@$item.user->posts}</td>
                                    </tr>
                                {/if}
                                {if !$pbShowIP|empty}
                                    <tr>
                                        <td><img src="{@RELATIVE_WBB_DIR}icon/ipAddressS.png" alt="" title="{lang}wcf.usersOnline.ipAddress{/lang}" /></td>
                                        <td class="smallFont">{$pbShowIP}</td>
                                    </tr>
                                {/if}
                            </table>
                        </div>
                    {/if}


                    {if $pbShowSearch == true}
                        <div style="font-size:1px; height:{$pbCatVertOffset}px; float:none;">&nbsp;</div>

                        <div class="container-{cycle values="$pbFirstBoxColor,$pbSecondBoxColor"}" style="float:none;">
                            <table cellpadding="{$pbCellPadding}" cellspacing="0" border="0" style="width:{$pbTableWidth};">
                                <colgroup><col style="width:{$pbFirstColWidth}px;" /></colgroup>
                                <tr>
                                    <td><img src="{@RELATIVE_WBB_DIR}icon/postNewS.png" alt="" title="{lang}wbb.portal.box.personalbox.newPostsTitle{/lang}" /></td>
                                    <td class="smallFont">
                                        {if $item.user->cntNewPosts > 0 || !$this->user->getPermission('user.profile.personalbox.cntCurPosts')}
                                            <a href="index.php?form=Search&amp;action=newPostsSince&amp;since={@$this->user->boardLastActivityTime}{@SID_ARG_2ND}">{lang}wbb.portal.box.personalbox.newPosts{/lang}</a>{if $item.user->cntNewPosts > 0}: <span style="font-weight:bold;">{$item.user->cntNewPosts}</span>{/if}
                                        {else}
                                            {lang}wbb.portal.box.personalbox.newPosts{/lang}
                                        {/if}
                                    </td>
                                </tr>
                                <tr>
                                    <td><img src="{@RELATIVE_WBB_DIR}icon/postS.png" alt="" /></td>
                                    <td class="smallFont">
                                        {if $item.user->cntLastPosts > 0 || !$this->user->getPermission('user.profile.personalbox.cntLastPosts')}
                                            <a href="index.php?form=Search&amp;action=newPostsSince&amp;since={$item.user->searchTime}{@SID_ARG_2ND}">{lang}wbb.portal.box.personalbox.searchDays{/lang} {$item.user->searchTime|time:"%d.%m.%Y"}</a>{if $item.user->cntLastPosts > 0}: <span style="font-weight:bold;">{$item.user->cntLastPosts}</span>{/if}
                                        {else}
                                            {lang}wbb.portal.box.personalbox.searchDays{/lang} {$item.user->searchTime|time:"%d.%m.%Y"}
                                        {/if}
                                    </td>
                                </tr>
                                <tr>
                                    <td><img src="{@RELATIVE_WBB_DIR}icon/subscriptionsS.png" alt="" /></td>
                                    <td class="smallFont">
                                        <a href="index.php?page=SubscriptionsList{@SID_ARG_2ND}">{lang}wbb.user.subscriptions.title{/lang}</a>{if $item.user->cntSub}: <span style="font-weight:bold;">{$item.user->cntSub}</span>{/if}
                                    </td>
                                </tr>
                                {if $this->user->getPermission('user.guestbook.canUseOwn')}
                                    <!-- Guestbook -->
                                    <tr>
                                        <td><img src="{@RELATIVE_WCF_DIR}icon/guestbookS.png" alt="" /></td>
                                        <td class="smallFont"><a href="index.php?page=userGuestbook&userID={@$this->user->userID}{@SID_ARG_2ND}">{lang}wbb.portal.box.personalbox.guestbook{/lang}</a>{if $item.user->cntGB}: {if $item.user->newGB}<span style="font-weight:bold;">{#$item.user->cntGB}</span>{else}{#$item.user->cntGB}{/if}{/if}</td>
                                    </tr>
                                {/if}
                                <tr>
                                    <td><img src="{@RELATIVE_WCF_DIR}icon/userS.png" alt="" /></td>
                                    <td class="smallFont"><a href="index.php?form=Search&userID={@$this->user->userID}{@SID_ARG_2ND}">{lang}wbb.portal.box.personalbox.egoSearch{/lang}</a>{if $this->user->getPermission('user.profile.personalbox.cntOwnPosts')}: {@$item.user->posts}{/if}</td>
                                </tr>
                                <tr>
                                    <td><img src="{@RELATIVE_WBB_DIR}icon/boardMarkAsReadS.png" alt="" /></td>
                                    <td class="smallFont"><a href="index.php?page=Index&action=BoardMarkAllAsRead&page={@$boxCurPage}{@SID_ARG_2ND}">{lang}wbb.portal.box.personalbox.markAsRead{/lang}</a></td>
                                </tr>
                            </table>
                        </div>
                    {/if}


                    {if $this->user->getPermission('user.pm.canUsePm') && $pbShowPM == true}
                        <div style="font-size:1px; height:{$pbCatVertOffset}px; float:none;">&nbsp;</div>

                        <div class="container-{cycle values="$pbFirstBoxColor,$pbSecondBoxColor"}" style="float:none;">
                            <table cellpadding="{$pbCellPadding}" cellspacing="0" border="0" style="width:{$pbTableWidth};">
                                <colgroup><col style="width:{$pbFirstColWidth}px;" /></colgroup>
                                {if $this->user->pmTotalCount >= $this->user->getPermission('user.pm.maxPm')}
                                    <tr>
                                        <td><img src="{@RELATIVE_WCF_DIR}icon/pmFullS.png" alt="" /></td>
                                        <td class="smallFont">{lang}wcf.pm.userMenu.mailboxIsFull{/lang}</td>
                                    </tr>
                                {/if}
                                <tr>
                                    <td><img src="{@RELATIVE_WCF_DIR}icon/pmReadS.png" alt="" /></td>
                                    <td class="smallFont"><a href="index.php?form=PMNew{@SID_ARG_2ND}" title="{lang}wbb.portal.box.personalbox.pmNew{/lang}">{lang}wbb.portal.box.personalbox.pmNew{/lang}</a></td>
                                </tr>
                                <tr>
                                    <td><img src="{@RELATIVE_WCF_DIR}icon/pmUnreadS.png" alt="" /></td>
                                    <td class="smallFont"><a href="index.php?page=PMList{@SID_ARG_2ND}" title="{lang}wcf.pm.title{/lang}">{lang}wbb.portal.box.personalbox.pmUnread{/lang}</a> ({$this->user->pmUnreadCount}/{$this->user->pmTotalCount})</td>
                                </tr>
                            </table>
                        </div>
                    {/if}


                    <!-- Instant Messenger by Tatzelwurm -->
                    {if $pbShowIM}
                        <div style="font-size:1px; height:{$pbCatVertOffset}px; float:none;">&nbsp;</div>
                        <div class="container-{cycle values="$pbFirstBoxColor,$pbSecondBoxColor"}" style="float:none;">
                        	<table cellpadding="{$pbCellPadding}" cellspacing="0" border="0" style="width:{$pbTableWidth};">
                                <colgroup><col style="width:{$pbFirstColWidth}px;" /></colgroup>
                                <tr>
                                    <td><img src="{@RELATIVE_WCF_DIR}icon/imReadS.png" alt="" /></td>
                                    <td class="smallFont"><a href="javascript:void(0);" onclick="window.open('index.php?form=InstantMessenger&action=new{@SID_ARG_2ND}','InstantMessage','width={@INSTANTMESSENGER_SENDWIDTH},height={@INSTANTMESSENGER_SENDHIGHT},toolbar=no,scrollbars=yes,left=50,top=50,resizable=yes');return false;" title="{lang}wbb.portal.box.personalbox.pmNew{/lang}">{lang}wbb.portal.box.personalbox.pmNew{/lang}</a></td>
                                </tr>
                            	{if $imcount|isset && $imcount}
                            	<tr>
                                    <td><img src="{@RELATIVE_WCF_DIR}icon/imUnreadS.png" alt="" /></td>
                                	<td class="smallFont"><a href="javascript:void(0);" onclick="window.open('index.php?page=InstantMessenger&action=read{@SID_ARG_2ND}','InstantMessage','width={@INSTANTMESSENGER_SENDWIDTH},height={@INSTANTMESSENGER_SENDHIGHT},toolbar=no,scrollbars=yes,left=50,top=50,resizable=yes');return false;" title="{lang}wbb.portal.box.personalbox.pmUnread{/lang}">{lang}wbb.portal.box.personalbox.pmUnread{/lang}</a> ({$imcount})</td>
                            	</tr>
                            	{/if}
                            </table>
                        </div>
                    {/if}


                    {if $pbShowUserCP == true}
                        <div style="font-size:1px; height:{$pbCatVertOffset}px; float:none;">&nbsp;</div>

                        <div class="container-{cycle values="$pbFirstBoxColor,$pbSecondBoxColor"}" style="float:none;">
                            <table cellpadding="{$pbCellPadding}" cellspacing="0" border="0" style="width:{$pbTableWidth};">
                                <colgroup><col style="width:{$pbFirstColWidth}px;" /></colgroup>
                                {if $this->user->getPermission('admin.general.canUseAcp')}
                                    <tr>
                                        <td><img src="{@RELATIVE_WBB_DIR}icon/acpS.png" alt="" /></td>
                                        <td class="smallFont"><a href="acp/index.php?packageID={@PACKAGE_ID}" target="_blank">{lang}wbb.header.userMenu.acp{/lang}</a></td>
                                    </tr>
                                {/if}
                                {if $this->user->getPermission('admin.general.canUseAcp') || $this->user->getPermission('mod.board.canDeleteThreadCompletely') || $this->user->getPermission('mod.board.canDeletePostCompletely') || $this->user->getPermission('mod.board.canEnableThread') || $this->user->getPermission('mod.board.canEnableThread')}
                                    <tr>
                                        <td><img src="{@RELATIVE_WBB_DIR}icon/moderatorS.png" alt="" /></td>
                                        <td class="smallFont"><a href="index.php?page=ModerationOverview{@SID_ARG_2ND}">{lang}wcf.user.usercp.menu.link.modcp{/lang}</a> ({$item.user->cntReported}/{$item.user->cntTrash})</td>
                                    </tr>
                                {/if}
                                {if $pbShowProfileLink == true || !$item.user->getAvatar() || $this->user->personalbox_show_avatar == false}
                                    <tr>
                                        <td><img src="{@RELATIVE_WBB_DIR}icon/profileS.png" alt="" /></td>
                                        <td class="smallFont"><a href="index.php?form=UserProfileEdit{@SID_ARG_2ND}">{lang}wcf.user.usercp{/lang}</a></td>
                                    </tr>
                                {/if}
                                {if $pbShowProfileLink == true}
                                    <tr>
                                        <td><img src="{@RELATIVE_WCF_DIR}icon/userProfileDisplayS.png" alt="" /></td>
                                        <td class="smallFont"><a href="index.php?form=UserProfileEdit&category=settings.display{@SID_ARG_2ND}">{lang}wcf.user.option.category.settings.display{/lang}</a></td>
                                    </tr>
                                {/if}

                                {if $this->user->getPermission('user.profile.attachmentManager.canView')}
                                    <tr>
                                        <td><img src="{@RELATIVE_WCF_DIR}icon/attachmentManagerS.png" alt="" /></td>
                                        <td class="smallFont"><a href="index.php?page=AttachmentManager{@SID_ARG_2ND}">{lang}wcf.user.attachmentManager.title{/lang}</a></td>
                                    </tr>
                                {/if}

                                {if $this->user->getPermission('user.wantedPoster.canUseWantedPoster')}
                                    <tr>
                                        <td><img src="{@RELATIVE_WCF_DIR}icon/userWantedPosterS.png" alt="" /></td>
                                        <td class="smallFont"><a href="index.php?form=UserWantedPosterEdit{@SID_ARG_2ND}">{lang}wcf.user.profile.menu.link.wantedPoster{/lang}</a></td>
                                    </tr>
                                {/if}

                                <tr>
                                    <td><img src="{@RELATIVE_WCF_DIR}icon/userProfilePrivacyS.png" alt="" /></td>
                                    <td class="smallFont"><a href="index.php?form=UserProfileEdit&category=settings.privacy{@SID_ARG_2ND}">{lang}wcf.user.option.category.settings.privacy{/lang}</a></td>
                                </tr>
                                {if $pbShowStyles == true}
                                    <form action="index.php" method="get">
                                    <tr>
                                        <td><input class="inputImage" type="image" src="{@RELATIVE_WCF_DIR}icon/submitS.png" alt="" style="width:16px; height:16px;" /></td>
                                        <td class="smallFont">
                                            <input type="hidden" name="page" value="{@$boxCurPage}" />
                                            <select name="styleID" style="width:{$pbStyleWidth}px;" onChange="this.form.submit();">
                                                {if $pbStyles|count}
                                                    {foreach from=$pbStyles item=$style}
                                                        {if $style.DISABLED|empty}
                                                            <option label="{$style.NAME}" value="{$style.ID}"{if $this->style->styleID == $style.ID} selected="selected"{/if}>{$style.NAME} ({#$style.CNT})</option>
                                                        {/if}
                                                    {/foreach}
                                                {else}
                                                    {htmloptions options=$this->style->getAvailableStyles() selected=$this->style->styleID}
                                                {/if}
                                                {@SID_INPUT_TAG}
                                            </select>
                                        </td>
                                    </tr>
                                    </form>
                                {/if}
                            </table>
                        </div>
                    {/if}


                    {if $pbShowMisc == true && $pbLinks|count}
                        <div style="font-size:1px; height:{$pbCatVertOffset}px; float:none;">&nbsp;</div>

                        <div class="container-{cycle values="$pbFirstBoxColor,$pbSecondBoxColor"}" style="float:none;">
                            <table cellpadding="{$pbCellPadding}" cellspacing="0" border="0" style="width:{$pbTableWidth};">
                                <colgroup><col style="width:{$pbFirstColWidth}px;" /></colgroup>
                                {foreach from=$pbLinks item=$link}
                                    {if $link.TYPE == 'SPACER'}
                                        <tr><td colspan="2">{@$link.SPACER}</td></tr>
                                    {else}
                                        {if $link.PERM|empty || $this->user->getPermission($link.PERM)}
                                            <tr>
                                                <td>{if !$link.IMG|empty}<img src="{@$link.IMG}" alt="" />{else}&nbsp;{/if}</td>
                                                <td class="smallFont"><a href="{@$link.URL}"{if !$link.TARGET|empty} target="{@$link.TARGET}"{/if}>{lang}{@$link.TITLE}{/lang}</a></td>
                                            </tr>
                                        {/if}
                                    {/if}
                                {/foreach}
                            </table>
                        </div>
                    {/if}


                    {if $pbShowWeatherCom}
                        <div style="font-size:1px; height:{$pbCatVertOffset}px; float:none;">&nbsp;</div>
                        <div style="float:none; text-align:center;">
                            <table style="border:0; margin-left:auto; margin-right:auto;">
                                {if $pbWeatherComDay == 'Z'}
                                    <tr>
                                        <td class="smallFont">
                                            <a href="http://www.wetter.com" target="_blank"><img src="http://www.wetter.com/home/woys/woys.php?,C,{$pbWeatherComStyle},{@$pbWeatherComZipCode}" alt=""{if $pbWeatherWidth > 0} style="max-width:{$pbWeatherWidth}"{/if} /></a>
                                        </td>
                                    </tr>
                                    {assign var='pbWeatherComDay' value='F'}
                                {/if}
                                <tr>
                                    <td class="smallFont">
                                      <a href="http://www.wetter.com" target="_blank"><img src="http://www.wetter.com/home/woys/woys.php?,{$pbWeatherComDay},{$pbWeatherComStyle},{@$pbWeatherComZipCode}" alt=""{if $pbWeatherWidth > 0} style="max-width:{$pbWeatherWidth}"{/if} /></a>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    {/if}
                    {if $pbShowWeather == true}
                        <div style="font-size:1px; height:{$pbCatVertOffset}px; float:none;">&nbsp;</div>
                        <div style="float:none; text-align:center;">
                            <table style="border:0; margin-left:auto; margin-right:auto;">
                                <tr>
                                    <td class="smallFont">
                                        <script type="text/javascript" language="javascript" src="http://www.donnerwetter.de/wetter/net/boxregio.mv?typ={$pbWeatherStyle}&plz={$pbWeatherZipCode}&color_bg={$item.user->bgColor}&color_hi={$item.user->boColor}&color_txt={$item.user->textColor}{if $pbWeatherWidth > 0}&width={$pbWeatherWidth}{/if}"></script>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    {/if}
                {else}
                    <div class="container-{cycle values="$pbFirstBoxColor,$pbSecondBoxColor"}" style="float:none;">
                        <table cellpadding="{$pbCellPadding}" cellspacing="0" border="0" style="width:{$pbTableWidth};">
                            <colgroup><col style="width:{$pbFirstColWidth}px;" /></colgroup>
                            <tr>
                                <td><img src="{@RELATIVE_WCF_DIR}icon/loginS.png" alt="" /></td>
                                <td class="smallFont"><a href="index.php?form=UserLogin{@SID_ARG_2ND}">{lang}wbb.header.userMenu.login{/lang}</a></td>
                            </tr>
                            <tr>
                                <td><img src="{@RELATIVE_WCF_DIR}icon/registerS.png" alt="" /></td>
                                <td class="smallFont"><a href="index.php?page=Register{@SID_ARG_2ND}">{lang}wbb.header.userMenu.register{/lang}</a></td>
                            </tr>
                        </table>
                    </div>
                {/if}
            </div>
        </div>
    </div>
    <script type="text/javascript">
    //<![CDATA[
    initList('personalbox', {@$item.Status});
    //]]>
    </script>
{/if}
