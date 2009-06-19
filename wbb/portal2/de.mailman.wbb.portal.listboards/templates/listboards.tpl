{* $Id$ *}
{if $lbSBColor}
    {assign var='lbSecondBoxColor' value=$lbSBColor}
{else}
    {assign var='lbSecondBoxColor' value=2}
{/if}
{assign var='lbFirstBoxColor' value=1}
{cycle values="$lbFirstBoxColor,$lbSecondBoxColor" print=false advance=false reset=true}
		<div class="border" id="box{$box->boxID}">
		    <div class="containerHead">
        		<div class="containerIcon">
            		<a href="javascript: void(0)" onclick="openList('{@$box->getStatusVariable()}', { save:true })">
        				<img src="{icon}minusS.png{/icon}" id="{@$box->getStatusVariable()}Image" alt="" />
        			</a>
                </div>
                <div class="containerContent">
                    {if $this->user->userID}
                        <a href="index.php?page=Index{@SID_ARG_2ND}">{lang}wbb.portal.box.listboards.title{/lang}</a>
                    {else}
                        {lang}wbb.portal.box.listboards.title{/lang}
                    {/if}
                </div>
            </div>

            <div class="container-1" id="{@$box->getStatusVariable()}">
            	<div class="containerContent">
                    {if $boards|isset && $boards|count > 0}
                        <div class="containerContent smallFont"{if $lbMaxHeight > 0} style="max-height:{$lbMaxHeight}px; overflow:auto;"{/if}>
                            <div id="dummy" style="padding:0; margin:0;">
                            {foreach from=$boards item=child}
                                {assign var="board" value=$child.board}
                                {assign var="boardID" value=$board->boardID}
                                {assign var="lbIndentRepeat" value=$child.depth-1}
                                {if $lbShowNewPosts && !$unreadThreadsCount.$boardID|empty}
                                    {assign var="utcLength" value=$unreadThreadsCount.$boardID|strlen}
                                {else}
                                    {assign var="utcLength" value=0}
                                {/if}
                                {if $lbLength > 0 && $lbIndentRepeat > 0}
                                    {if $lbShowNewPosts && $utcLength}
                                        {assign var="lbMaxLength" value=$lbLength-$lbIndentRepeat-$utcLength-2*$lbLevelCut}
                                    {else}
                                        {assign var="lbMaxLength" value=$lbLength-$lbIndentRepeat*$lbLevelCut}
                                    {/if}
                                {else}
                                    {assign var="lbMaxLength" value=$lbLength}
                                {/if}
                                {if $lbMaxLength > 0 && $board->title|strlen > $lbMaxLength}
                                    {if USE_MBSTRING}{assign var="bTitle" value=$board->title|mb_substr:0:$lbMaxLength-3|concat:'...'}
                                    {else}{assign var="bTitle" value=$board->title|substr:0:$lbMaxLength-3|concat:'...'}
                                    {/if}
                                {else}
                                    {assign var="bTitle" value=$board->title}
                                {/if}
                                {if $board->isCategory() && $child.depth < 2}
                                    </div>
                                    <div class="container-{cycle values="$lbFirstBoxColor,$lbSecondBoxColor"}" style="margin-bottom:{$lbSpacer}px;">
                                        <a href="index.php?page=Board&amp;boardID={@$boardID}{@SID_ARG_2ND}" title="{lang}{$board->title|strip_tags}{/lang}{if $board->description} &raquo; {lang}{$board->description|strip_tags}{/lang}{/if}" style="font-weight:bold; font-size:{$lbFontsize};">{lang}{$bTitle}{/lang}</a>
                                {else}
                                    <br />
                                    {if $lbShowNewPosts && !$unreadThreadsCount.$boardID|empty}
                                        {@$lbIndentNewPosts|str_repeat:$lbIndentRepeat}
                                        <a href="index.php?page=Board&amp;boardID={@$boardID}{@SID_ARG_2ND}" title="{lang}{$board->title|strip_tags}{/lang}{if $board->description} &raquo; {lang}{$board->description|strip_tags}{/lang}{/if}" class="new">{lang}{$bTitle}{/lang} ({#$unreadThreadsCount.$boardID})</a>
                                    {else}
                                        {@$lbIndent|str_repeat:$lbIndentRepeat}
                                        <a href="index.php?page=Board&amp;boardID={@$boardID}{@SID_ARG_2ND}" title="{lang}{$board->title|strip_tags}{/lang}{if $board->description} &raquo; {lang}{$board->description|strip_tags}{/lang}{/if}"{if $child.hasChildren > 0} style="font-weight:bold;"{/if}>{lang}{$bTitle}{/lang}</a>
                                    {/if}
                                {/if}
                            {/foreach}
                            </div>
                        </div>
                    {else}
                    	<p class="smallFont">
                        	{lang}wbb.portal.box.listboards.noboards{/lang}
                        </p>
                    {/if}
                </div>
            </div>
        </div>
