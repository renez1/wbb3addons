{* $Id$ *}
{if $this->user->getPermission('user.board.canViewStickyTopicsBox')}
    {if STICKYTOPICSBOX_SBCOLOR}
        {assign var='secondBoxColor' value=STICKYTOPICSBOX_SBCOLOR}
    {else}
        {assign var='secondBoxColor' value=2}
    {/if}
    {cycle name='stickyTopicsBoxCycle' values="1,$secondBoxColor" advance=false print=false reset=true}
    <div class="border titleBarPanel" id="box{$box->boxID}">
        <div class="containerHead">
            {if !STICKYTOPICSBOX_BOXOPENED}
                <div class="containerIcon">
                    <a href="javascript: void(0)" onclick="openList('{@$box->getStatusVariable()}', { save:true })">
                        <img src="{icon}minusS.png{/icon}" id="{@$box->getStatusVariable()}Image" alt="" />
                    </a>
                </div>
            {/if}
            <div class="containerContent">
                <a href="index.php?page=Index{@SID_ARG_2ND}">{lang}wbb.portal.box.stickyTopics.title{/lang}</a>
            </div>
        </div>
        <div class="container-1" id="{@$box->getStatusVariable()}">
            <div class="containerContent">
                {if $box->data.topics|isset && $box->data.topics|count > 0}
                    {foreach from=$box->data.topics item=$topic}
                        {if STICKYTOPICSBOX_TITLE_LENGTH > 0 && $topic.topic|strlen > STICKYTOPICSBOX_TITLE_LENGTH}
                            {assign var="fTitle" value=$topic.topic|truncate:STICKYTOPICSBOX_TITLE_LENGTH}
                        {else}
                            {assign var="fTitle" value=$topic.topic}
                        {/if}
                        <div class="container-{cycle name='stickyTopicsBoxCycle'} smallFont" style="float:none; margin-right:6px;">
                            {if STICKYTOPICSBOX_SHOWICON}
                                <img src="{@RELATIVE_WBB_DIR}icon/stickyTopicsBoxS.png" alt="" />
                            {/if}
                            <a href="index.php?page=Thread&threadID={$topic.threadID}{@SID_ARG_2ND}" title="{lang}wbb.portal.box.stickyTopics.toolTip{/lang}">{lang}{$fTitle}{/lang}</a>
                        </div>
                    {/foreach}
                {/if}
            </div>
        </div>
    </div>
{/if}
