{if $this->user->getPermission('user.board.canViewSimplePieNewsreaderBox')}
    {if SPNRBOX_SBCOLOR}
        {assign var='secondBoxColor' value=SPNRBOX_SBCOLOR}
    {else}
        {assign var='secondBoxColor' value=2}
    {/if}
    {cycle name='simplePieNewsreaderBoxCycle' values="1,$secondBoxColor" advance=false print=false reset=true}
    <link rel="stylesheet" type="text/css" media="screen" href="{@RELATIVE_WBB_DIR}style/SimplePieNewsReader.css" />
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
            <div class="containerContent">
                <ul id="feedReader"{if $secondBoxColor == 1} style="background:url({@RELATIVE_WBB_DIR}icon/feedBG.png) no-repeat 100% 0;"{/if}>
                    {if $item.spnrFeeds|isset && $item.spnrFeeds|count > 0}
                        {foreach from=$item.spnrFeeds item=feed}
                            <li{if $secondBoxColor != 1} class="container-{cycle name='simplePieNewsreaderBoxCycle'}" style="float:none;"{/if}>
                                <h4 class="feedReaderTitle">
                                    <a href="javascript:void(0)" onclick="openList('feedReaderSubTitle{$feed.id}', true)" class="feedReaderListSubTitle"><img src="{@RELATIVE_WCF_DIR}icon/minusS.png" style="margin: 0 0 2px;" id="feedReaderSubTitle{$feed.id}Image" alt="" /></a>
                                    <a href="{@$feed.link}" target="_blank" style="background-image:url({$feed.favicon});" class="feedReaderTitleURL">{@$feed.title}</a>
                                </h4>
                                <ul id="feedReaderSubTitle{$feed.id}" style="display:none;">
                                    {if $feed.iFeed|isset && $feed.iFeed|count > 0}
                                        {foreach from=$feed.iFeed item=iFeed}
                                            <li>
                                                <h5 class="feedReaderSubTitle">
                                                    <a href="javascript: void(0)" onclick="openList('feedReaderContent{$feed.id}_{$iFeed.id}', true)" class="feedReaderListContent"><img src="{@RELATIVE_WCF_DIR}icon/minusS.png" style="margin: 0 0 2px;" id="feedReaderContent{$feed.id}_{$iFeed.id}Image" alt="" /></a>
                                                    {if SPNRBOX_MAXLENFEEDTITLE > 0 && $iFeed.title|strlen > SPNRBOX_MAXLENFEEDTITLE}
                                                        {assign var="fTitle" value=$iFeed.title|substr:0:SPNRBOX_MAXLENFEEDTITLE-3|concat:'...'}
                                                    {else}
                                                        {assign var="fTitle" value=$iFeed.title}
                                                    {/if}
                                                    <a href="{@$iFeed.link}" target="_blank" class="feedReaderSubTitleURL">{@$fTitle}</a>
                                                    {if !$iFeed.date|empty && SPNRBOX_SHOWDATENEXTTITLE}
                                                        <img src="{@RELATIVE_WCF_DIR}icon/dateS.png" alt="" /> <span class="smallFont">{@$iFeed.date}</span>
                                                    {/if}
                                                </h5>
                                                <div class="smallFont" id="feedReaderContent{$feed.id}_{$iFeed.id}" style="display:none;">
                                                    {@$iFeed.content}
                                                    {if $iFeed.enclosure|isset}
                                                        {@$iFeed.enclosure}
                                                    {/if}
                                                    {if !$iFeed.date|empty && !SPNRBOX_SHOWDATENEXTTITLE}
                                                        <p class="feedReaderFooter"><img src="{@RELATIVE_WCF_DIR}icon/dateS.png" alt="" /> {@$iFeed.date}</p>
                                                    {/if}
                                                </div>
                                                <script type="text/javascript">
                                                    //<![CDATA[
                                                    initList('feedReaderContent{$feed.id}_{$iFeed.id}', 0);
                                                    //]]>
                                                </script>
                                            </li>
                                        {/foreach}
                                    {/if}
                                </ul>
                                <script type="text/javascript">
                                    //<![CDATA[
                                    initList('feedReaderSubTitle{$feed.id}', 0);
                                    //]]>
                                </script>
                            </li>
                        {/foreach}
                    {/if}
                </ul>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        //<![CDATA[
        if('{@$item.Status}' != '') initList('simplePieNewsreaderBox', {@$item.Status});
        //]]>
    </script>
{/if}
