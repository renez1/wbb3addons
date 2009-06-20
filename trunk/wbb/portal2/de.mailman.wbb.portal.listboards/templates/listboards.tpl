{* $Id$ *}
{if LISTBOARDSBOX_SBCOLOR}
    {assign var='secondBoxColor' value=LISTBOARDSBOX_SBCOLOR}
{else}
    {assign var='secondBoxColor' value=2}
{/if}
{assign var='firstBoxColor' value=1}
{cycle values="$firstBoxColor,$secondBoxColor" print=false advance=false reset=true}
		<div class="border titleBarPanel" id="box{$box->boxID}">
		    <div class="containerHead">
        		<div class="containerIcon">
            		<a href="javascript: void(0)" onclick="openList('{@$box->getStatusVariable()}', { save:true })">
        				<img src="{icon}minusS.png{/icon}" id="{@$box->getStatusVariable()}Image" alt="" />
        			</a>
                </div>
                <div class="containerContent">
					<a href="index.php?page=Index{@SID_ARG_2ND}">{lang}wbb.portal.box.listboards.title{/lang}</a>
                </div>
            </div>

            <div class="container-1" id="{@$box->getStatusVariable()}">
            	<div class="containerContent"{if $box->maxHeight > 0} style="max-height: {@$box->maxHeight}px; overflow:auto;"{/if}>
                    {if $box->data|count > 0}                    		                                                  
                            {foreach from=$box->data.boards item=child}
                                {assign var="board" value=$child.board}
                                {assign var="boardID" value=$board->boardID}                                
								{assign var="openParents" value=$child.openParents}
                                {assign var="indentRepeat" value=$child.depth-1}
                                {if LISTBOARDS_SHOW_NEWPOSTS && !$box->data.unreadThreadsCount.$boardID|empty}
                                    {assign var="unreadThreadsCountLength" value=$box->data.unreadThreadsCount.$boardID|strlen}
                                {else}
                                    {assign var="unreadThreadsCountLength" value=0}
                                {/if}
                                {if LISTBOARDS_LENGTH > 0 && $indentRepeat > 0}
                                    {if LISTBOARDS_SHOW_NEWPOSTS && $unreadThreadsCountLength}
                                        {assign var="maxLength" value=LISTBOARDS_LENGTH-$indentRepeat-$unreadThreadsCountLength-2*LISTBOARDS_LEVELCUT}
                                    {else}
                                        {assign var="maxLength" value=LISTBOARDS_LENGTH-$indentRepeat*LISTBOARDS_LEVELCUT}
                                    {/if}
                                {else}
                                    {assign var="maxLength" value=LISTBOARDS_LENGTH}
                                {/if}
                                {capture assign="boardTitle"}{lang}{$board->title}{/lang}{/capture}
                                {capture assign="boardDescription"}{lang}{if $board->allowDescriptionHtml}{@$board->description}{else}{$board->description}{/if}{/lang}{/capture}
                                {if $maxLength > 0 && $boardTitle|strlen > $maxLength}                                	                                    
                                    {assign var="boardTitle" value=$boardTitle|truncate:$maxLength}                                
                                {/if}                                
                                {if $board->isCategory() && $child.depth < 2}                                	                                	
                                    <div class="container-{cycle values="$firstBoxColor,$secondBoxColor"}" style="margin-bottom: {LISTBOARDS_MAINBOARD_SPACER}px;">
                                        <a href="index.php?page=Board&amp;boardID={@$boardID}{@SID_ARG_2ND}" title="{lang}{$board->title}{/lang}{if $boardDescription} &raquo; {$boardDescription|strip_tags}{/if}" style="font-weight:bold; font-size:{$box->fontsize};">{$boardTitle}</a>
                                {else}                                                                 
                                    {if LISTBOARDS_SHOW_NEWPOSTS && !$box->data.unreadThreadsCount.$boardID|empty}
                                        <p>{@LISTBOARDS_NEWPOST_INDENT|str_repeat:$indentRepeat}
                                        <a href="index.php?page=Board&amp;boardID={@$boardID}{@SID_ARG_2ND}" title="{lang}{$board->title}{/lang}{if $boardDescription} &raquo; {$boardDescription|strip_tags}{/if}" class="new">{$boardTitle} ({#$box->dataunreadThreadsCount.$boardID})</a></p>
                                    {else}
                                        <p>{@LISTBOARDS_SUBBOARD_INDENT|str_repeat:$indentRepeat}
                                        <a href="index.php?page=Board&amp;boardID={@$boardID}{@SID_ARG_2ND}" title="{lang}{$board->title}{/lang}{if $boardDescription} &raquo; {$boardDescription|strip_tags}{/if}"{if $child.hasChildren > 0} style="font-weight:bold;"{/if}>{$boardTitle}</a></p>
                                    {/if}
                                {/if}
                                {if $openParents > 0}{@"</div>"|str_repeat:$openParents}{/if}                                	
                            {/foreach}                                                    
                    {else}
                    	<p class="smallFont">
                        	{lang}wbb.portal.box.listboards.noboards{/lang}
                        </p>
                    {/if}                                
            </div>
        </div>
	</div>