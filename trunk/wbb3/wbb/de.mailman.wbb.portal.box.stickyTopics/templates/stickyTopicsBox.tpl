{* $Id$ *}
{if $this->user->getPermission('user.board.canViewStickyTopicsBox')}
    {if STICKYTOPICSBOX_SBCOLOR}
        {assign var='secondBoxColor' value=STICKYTOPICSBOX_SBCOLOR}
    {else}
        {assign var='secondBoxColor' value=2}
    {/if}
    {cycle name='stickyTopicsBoxCycle' values="1,$secondBoxColor" advance=false print=false reset=true}
    <div class="border" id="box{$boxID}">
        <div class="containerHead">
            {if !STICKYTOPICSBOX_BOXOPENED}
    	    	<div class="containerIcon">
    	    		<a href="javascript: void(0)" onclick="openList('stickyTopicsBox', true)"><img src="{@RELATIVE_WCF_DIR}icon/minusS.png" id="stickyTopicsBoxImage" alt="" /></a>
                </div>
            {/if}
            <div class="containerContent">{lang}wbb.portal.box.stickyTopics.title{/lang}</div>
        </div>
        <div class="container-1" id="stickyTopicsBox">
            <div class="containerContent">
                {if $item.topics|isset && $item.topics|count > 0}
                    {foreach from=$item.topics item=$topic}
                        {if STICKYTOPICSBOX_TITLE_LENGTH > 0 && $topic.topic|strlen > STICKYTOPICSBOX_TITLE_LENGTH}
                            {assign var="fTitle" value=$topic.topic|substr:0:STICKYTOPICSBOX_TITLE_LENGTH-3|concat:'...'}
                        {else}
                            {assign var="fTitle" value=$topic.topic}
                        {/if}
                        <div class="container-{cycle name='stickyTopicsBoxCycle'} smallFont" style="float:none;">
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
    <script type="text/javascript">
    //<![CDATA[
    if('{@$item.Status}' != '') initList('stickyTopicsBox', {@$item.Status});
    //]]>
    </script>
{/if}
