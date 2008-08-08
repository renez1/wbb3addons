{if $this->user->getPermission('user.board.canViewSimplePieNewsreaderBox')}
    {if SPNRBOX_SBCOLOR}
        {assign var='secondBoxColor' value=SPNRBOX_SBCOLOR}
    {else}
        {assign var='secondBoxColor' value=2}
    {/if}
    {cycle name='simplePieNewsreaderBoxCycle' values="1,$secondBoxColor" advance=false print=false reset=true}
    {assign var="spnrboxWysiwygEditorMode" value=$this->user->wysiwygEditorMode}
    {assign var="spnrboxWysiwygEditorHeight" value=$this->user->wysiwygEditorHeight}
    {assign var="spnrboxMessageParseURL" value=$this->user->messageParseURL}
    {assign var="spnrboxMessageEnableSmilies" value=$this->user->messageEnableSmilies}
    {assign var="spnrboxMessageEnableHtml" value=$this->user->messageEnableHtml}
    {assign var="spnrboxMessageEnableBBCodes" value=$this->user->messageEnableBBCodes}
    {assign var="spnrboxMessageShowSignature" value=$this->user->messageShowSignature}
    <div class="border" id="box{$boxID}">
        <div class="containerHead">
            {if !SPNRBOX_BOXOPENED}
                <div class="containerIcon">
                    <a href="javascript: void(0)" onclick="openList('simplePieNewsreaderBox', true)"><img src="{@RELATIVE_WCF_DIR}icon/minusS.png" id="simplePieNewsreaderBoxImage" alt="" /></a>
                </div>
            {/if}
            <div class="containerContent">{lang}wbb.portal.box.simplePieNewsreader.title{/lang}</div>
        </div>
        <div class="container-1" id="simplePieNewsreaderBox">
            <div class="containerContent"{if SPNRBOX_BOXMAXHEIGHT > 0} style="max-height:{SPNRBOX_BOXMAXHEIGHT}px; overflow:auto;"{/if}>
                <ul id="feedReader" style="list-style-type: none;list-style-position: outside !important;padding: 0;margin: 5px 5px 5px 12px;{if $secondBoxColor == 1}background:url({@RELATIVE_WBB_DIR}icon/feedBG.png) no-repeat 100% 0;{/if}">
                    {if $item.spnrFeeds|isset && $item.spnrFeeds|count > 0}
                        {foreach from=$item.spnrFeeds item=feed}
                            <li{if $secondBoxColor != 1} class="container-{cycle name='simplePieNewsreaderBoxCycle'}" style="float:none;"{/if}>
                                <h4 style="font-weight: bold;height: 1.5em;line-height: 1.5em;vertical-align: middle;">
                                    {if !SPNRBOX_FEEDOPENED}
                                        <a href="javascript:void(0)" onclick="openList('feedReaderSubTitle{$feed.id}', true)"><img src="{@RELATIVE_WCF_DIR}icon/minusS.png" style="margin: 0 0 2px;" id="feedReaderSubTitle{$feed.id}Image" alt="" /></a>
                                    {/if}
                                    <img src="{$feed.favicon}" style="width: 16px;height: 16px;border: 0 none;text-decoration: none;" alt=" " /> <a href="{@$feed.link}" onclick="window.open(this.href); return false;"><span>{@$feed.title}</span></a>
                                </h4>
                                <ul id="feedReaderSubTitle{$feed.id}" style="list-style-type: none;list-style-position: outside !important;{if !SPNRBOX_FEEDOPENED && !SPNRBOX_FEEDNEWSOPENED} display:none;{/if}">
                                    {if $feed.iFeed|isset && $feed.iFeed|count > 0}
                                        {foreach from=$feed.iFeed item=iFeed}
                                            <li>
                                                <h5 id="feedreaderh5_{$feed.id}_{$iFeed.id}" style="font-weight: bold;">
                                                    {if !SPNRBOX_FEEDNEWSOPENED}
                                                        <a href="javascript: void(0)" onclick="document.getElementById('feedReaderContent{$feed.id}_{$iFeed.id}').className = 'smallFont';document.getElementById('feedreaderh5_{$feed.id}_{$iFeed.id}').style.fontWeight = 'normal';openList('feedReaderContent{$feed.id}_{$iFeed.id}', true)"><img src="{@RELATIVE_WCF_DIR}icon/minusS.png" style="margin: 0 0 2px;" id="feedReaderContent{$feed.id}_{$iFeed.id}Image" alt="" /></a>
                                                    {/if}
                                                    {if SPNRBOX_MAXLENFEEDTITLE > 0 && $iFeed.title|strlen > SPNRBOX_MAXLENFEEDTITLE}
                                                        {assign var="fTitle" value=$iFeed.title|substr:0:SPNRBOX_MAXLENFEEDTITLE-3|concat:'…'}
                                                    {else}
                                                        {assign var="fTitle" value=$iFeed.title}
                                                    {/if}
                                                    <a href="{@$iFeed.link}" onclick="window.open(this.href); return false;" style="background: transparent url({@RELATIVE_WBB_DIR}icon/feed.png) no-repeat 0 50%;padding-left: 20px;">{@$fTitle}</a>
                                                    {if !$iFeed.date|empty && SPNRBOX_SHOWDATENEXTTITLE}
                                                        <img src="{@RELATIVE_WCF_DIR}icon/dateS.png" alt="" /> <span class="smallFont">{@$iFeed.date}</span>
                                                    {/if}
                                                </h5>
                                                <div class="smallFont" style="margin: 0 0 15px 20px;{if !SPNRBOX_FEEDNEWSOPENED}display: none;{/if}" id="feedReaderContent{$feed.id}_{$iFeed.id}">
                                                    <div style="overflow: auto; padding: 5px 0;">
                                                        {@$iFeed.content}
                                                        {if $iFeed.enclosure|isset}
                                                            {@$iFeed.enclosure}
                                                        {/if}
                                                    </div>
                                                    {if SPNRBOX_FEEDTOTHREAD && $this->user->getPermission('user.board.canViewThreadToFeed') && $item.boardsForm|isset}
                                                        {assign var='boardsForm' value=$item.boardsForm}
                                                    {/if}
                                                    {if !SPNRBOX_SHOWDATENEXTTITLE || $boardsForm|isset || SPNRBOX_FEEDABO}
                                                        <div style="border-top: 1px dotted #777;padding: 5px 0;">
                                                            <ul style="list-style-type: none;background-color: inherit;margin: 0;padding: 0;">
                                                                {if !$iFeed.date|empty && !SPNRBOX_SHOWDATENEXTTITLE}
                                                                    <li style="display:inline;margin: 0 5px 0 0;padding: 0;">
                                                                        <img src="{@RELATIVE_WCF_DIR}icon/dateS.png" alt="" /> {@$iFeed.date}
                                                                    </li>
                                                                {/if}
                                                                {if $boardsForm|isset}
                                                                    <li style="display:inline;position: relative;margin: 0 5px 0 0;padding: 0;">
                                                                        {if $boardsForm == "button"}
                                                                            <form action="index.php?form=ThreadAdd&amp;boardID={$item.boards.0.id}{@SID_ARG_2ND}" method="post" accept-charset="{@CHARSET}" style="display: inline;margin: 0;padding: 0;">
                                                                                <img src="icon/threadNewS.png" alt="{lang}wbb.threadAdd.title{/lang}" /> <input type="submit" name="senden" value="{lang}wbb.threadAdd.title{/lang}" style="cursor: pointer; margin: 0;padding: 0;font: inherit;background: transparent; border: 0 none;color: #666;" onmouseover="this.style.color = '#000';" onmouseout="this.style.color = '#666';" />
                                                                                <input type="hidden" name="subject" value="Feed: {@$iFeed.title|htmlspecialchars}" />
                                                                                <input type="hidden" name="text" value="[b][url='{@$iFeed.link|htmlspecialchars}']{@$iFeed.title|htmlspecialchars}[/url][/b]" />
                                                                                <input type="hidden" name="wysiwygEditorMode" value="{$spnrboxWysiwygEditorMode}" />
                                                                                <input type="hidden" name="wysiwygEditorHeight" value="{$spnrboxWysiwygEditorHeight}" />
                                                                                <input type="hidden" name="parseURL" value="{$spnrboxMessageParseURL}" />
                                                                                <input type="hidden" name="enableSmilies" value="{$spnrboxMessageEnableSmilies}" />
                                                                                <input type="hidden" name="enableHtml" value="{$spnrboxMessageEnableHtml}" />
                                                                                <input type="hidden" name="enableBBCodes" value="{$spnrboxMessageEnableBBCodes}" />
                                                                                <input type="hidden" name="showSignature" value="{$spnrboxMessageShowSignature}" />
                                                                            </form>
                                                                        {elseif $boardsForm == "list"}
                                                                            <span id="feedReaderBoardOption{$feed.id}_{$iFeed.id}" style="cursor: pointer;padding-right: 10px;background: transparent url({@RELATIVE_WBB_DIR}icon/feedReaderBoardOptionS.png) no-repeat 100% 50%;" onmouseover="this.style.color = '#000';" onmouseout="this.style.color = '#666';"><img src="icon/threadNewS.png" alt="{lang}wbb.threadAdd.title{/lang}" /> <span>{lang}wbb.threadAdd.title{/lang}</span></span>
                                                                            <div id="feedReaderBoardOption{$feed.id}_{$iFeed.id}Menu" style="padding: 5px;margin: 0;" class="hidden">
                                                                                <ul style="list-style=type: none;margin: 0;padding: 0;">
                                                                                    {foreach from=$item.boards item=board}
                                                                                        {if $board.type == 1}
                                                                                            <li style="font-weight: bold;color: #000;letter-spacing: .1em;margin: 0;padding: 0;">
                                                                                                <span>{$board.title}</span>
                                                                                            </li>
                                                                                        {elseif $board.type == 0}
                                                                                            <li style="margin: 0;padding: 0;">
                                                                                                <form action="index.php?form=ThreadAdd&amp;boardID={$board.id}{@SID_ARG_2ND}" method="post" accept-charset="{@CHARSET}" style="margin: 0;padding: 0;">
                                                                                                    <input type="submit" name="senden" value="{$board.title}" style="display: block;cursor: pointer; margin: 0;padding: 0;font: inherit;font-size: 1em;background: transparent; border: 0 none;color: #666;" onmouseover="this.style.color = '#000';" onmouseout="this.style.color = '#666';" />
                                                                                                    <input type="hidden" name="subject" value="Feed: {@$iFeed.title|htmlspecialchars}" />
																									<input type="hidden" name="text" value="[b][url='{@$iFeed.link|htmlspecialchars}']{@$iFeed.title|htmlspecialchars}[/url][/b]" />
                                                                                                    <input type="hidden" name="wysiwygEditorMode" value="{$spnrboxWysiwygEditorMode}" />
                                                                                                    <input type="hidden" name="wysiwygEditorHeight" value="{$spnrboxWysiwygEditorHeight}" />
                                                                                                    <input type="hidden" name="parseURL" value="{$spnrboxMessageParseURL}" />
                                                                                                    <input type="hidden" name="enableSmilies" value="{$spnrboxMessageEnableSmilies}" />
                                                                                                    <input type="hidden" name="enableHtml" value="{$spnrboxMessageEnableHtml}" />
                                                                                                    <input type="hidden" name="enableBBCodes" value="{$spnrboxMessageEnableBBCodes}" />
                                                                                                    <input type="hidden" name="showSignature" value="{$spnrboxMessageShowSignature}" />
                                                                                                </form>
                                                                                            </li>
                                                                                        {/if}
                                                                                    {/foreach}
                                                                                </ul>
                                                                            </div>
                                                                        {/if}
                                                                    </li>
                                                                {/if}
                                                                {if SPNRBOX_FEEDABO}
                                                                    <li style="display:inline;margin: 0 5px 0 0;padding: 0;">
                                                                        <a href="{@$feed.xml}" style="text-decoration:none;"><img src="icon/feedAddS.png" alt="" /> <span>{@$feed.title} {lang}wbb.portal.box.simplePieNewsreader.feedAdd{/lang}</span></a> 
                                                                    </li>
                                                                {/if}
                                                            </ul>
                                                            {if $boardsForm|isset && $boardsForm == "list"}
                                                                <script type="text/javascript">
                                                                    popupMenuList.register('feedReaderBoardOption{$feed.id}_{$iFeed.id}');
                                                                    onloadEvents.push(function(){
                                                                        var showElement = document.getElementById('feedReaderBoardOption{$feed.id}_{$iFeed.id}Menu');
                                                                        showElement.style.fontSize = '1em';
                                                                        showElement.style.left = 0;
                                                                    });
                                                                </script>
                                                            {/if}
                                                        </div>
                                                    {/if}
                                                    {if SPNRBOX_SHOWSOCIALBOOKMARKS && $iFeed.bookmarks|isset && $iFeed.bookmarks|count > 0}
                                                        <ul id="feedReaderBookmarks{$feed.id}_{$iFeed.id}" style="list-style-type: none;border-top: 1px dotted #777;margin: 0 auto;padding: 5px 0;text-align: center;line-height: 20px;">
                                                            {foreach from=$iFeed.bookmarks item=bookmarks}
                                                                <li style="display: inline;margin: 0;padding: 0;"><a href="{$bookmarks.bookmarkUrl}" onclick="window.open(this.href); return false;" style="margin: 2px;padding: 2px;text-decoration: none;" title="Social Bookmark: {@$bookmarks.bookmarkTitle}" rel="nofollow"><img src="{@$bookmarks.bookmarkImg}" style="height: 16px;width: 16px;border: 0 none;" alt="Social Bookmark: {@$bookmarks.bookmarkTitle}" /></a></li>
                                                            {/foreach}
                                                        </ul>
                                                    {/if}
                                                    <hr style="display: block;height: 5px;border: dotted #777;border-width: 0 0 1px;margin: 0 0 20px;" />
                                                </div>
                                                {if !SPNRBOX_FEEDNEWSOPENED}
                                                    <script type="text/javascript">
                                                        //<![CDATA[
                                                        initList('feedReaderContent{$feed.id}_{$iFeed.id}', 0);
                                                        //]]>
                                                    </script>
                                                {/if}
                                            </li>
                                        {/foreach}
                                    {/if}
                                </ul>
                                {if !SPNRBOX_FEEDOPENED}
                                    <script type="text/javascript">
                                        //<![CDATA[
                                        initList('feedReaderSubTitle{$feed.id}', 0);
                                        //]]>
                                    </script>
                                {/if}
                            </li>
                        {/foreach}
                    {/if}
                </ul>
            </div>
        </div>
    </div>
    {if !SPNRBOX_BOXOPENED}
        <script type="text/javascript">
            //<![CDATA[
            if('{@$item.Status}' != '') initList('simplePieNewsreaderBox', {@$item.Status});
            //]]>
        </script>
    {/if}
{/if}
