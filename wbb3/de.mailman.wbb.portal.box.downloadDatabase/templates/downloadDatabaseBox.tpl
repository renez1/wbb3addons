{if $this->user->getPermission('user.board.canViewDownloadDatabaseBox') && $this->user->getPermission('user.dldb.canUseDownloadDB')}
    {if DOWNLOADDATABASEBOX_SBCOLOR}
        {assign var='secondBoxColor' value=DOWNLOADDATABASEBOX_SBCOLOR}
    {else}
        {assign var='secondBoxColor' value=2}
    {/if}
    <div class="border" id="box{$boxID}">
        <div class="containerHead">
            {if !DOWNLOADDATABASEBOX_BOXOPENED}
    	    	<div class="containerIcon">
    	    		<a href="javascript: void(0)" onclick="openList('downloadDatabaseBox', true)"><img src="{@RELATIVE_WCF_DIR}icon/minusS.png" id="downloadDatabaseBoxImage" alt="" /></a>
                </div>
            {/if}
            <div class="containerContent"><a href="index.php?page=DownloadDB{@SID_ARG_2ND}">{lang}wbb.portal.box.downloadDatabase.title{/lang}</a></div>
        </div>
        <div class="container-1" id="downloadDatabaseBox">
            <div class="containerContent">
                <div class="smallFont" style="text-align:center; font-weight:bold;">{lang}wbb.portal.box.downloadDatabase.recentTitle{/lang}</div>
                {cycle name='downloadDatabaseBoxCycle' values="1,$secondBoxColor" advance=false print=false reset=true}
                {if $dldbBoxRecentFiles|count > 0}
                    {foreach from=$dldbBoxRecentFiles item=$file}
                        {if DOWNLOADDATABASEBOX_TITLE_LENGTH > 0 && $file.name|strlen > DOWNLOADDATABASEBOX_TITLE_LENGTH}
                            {assign var="fTitle" value=$file.name|substr:0:DOWNLOADDATABASEBOX_TITLE_LENGTH-3|concat:'...'}
                        {else}
                            {assign var="fTitle" value=$file.name}
                        {/if}
                        <div class="container-{cycle name='downloadDatabaseBoxCycle'} smallFont" style="float:none;">
                            <div style="float:right;">{@$file.datum|time:"%d.%m.%Y"}</div>
                            <div>
                                <a href="index.php?page=DownloadDBData&dataID={$file.dataID}{@SID_ARG_2ND}" title="{lang}wbb.portal.box.downloadDatabase.toolTip{/lang}">{lang}{$fTitle}{/lang}</a>
                            </div>
                        </div>
                    {/foreach}
                {/if}
    
                <div class="smallFont" style="text-align:center; font-weight:bold;">{lang}wbb.portal.box.downloadDatabase.topTitle{/lang}</div>
                {cycle name='downloadDatabaseBoxCycle' values="1,$secondBoxColor" advance=false print=false reset=true}
                {if $dldbBoxTopFiles|count > 0}
                    {foreach from=$dldbBoxTopFiles item=$file}
                        {if DOWNLOADDATABASEBOX_TITLE_LENGTH > 0 && $file.name|strlen > DOWNLOADDATABASEBOX_TITLE_LENGTH}
                            {assign var="fTitle" value=$file.name|substr:0:DOWNLOADDATABASEBOX_TITLE_LENGTH-3|concat:'...'}
                        {else}
                            {assign var="fTitle" value=$file.name}
                        {/if}
                        <div class="container-{cycle name='downloadDatabaseBoxCycle'} smallFont" style="float:none;">
                            <div style="float:right;">{#$file.downloads}</div>
                            <div>
                                <a href="index.php?page=DownloadDBData&dataID={$file.dataID}{@SID_ARG_2ND}" title="{lang}wbb.portal.box.downloadDatabase.toolTip{/lang}">{lang}{$fTitle}{/lang}</a>
                            </div>
                        </div>
                    {/foreach}
                {/if}
            </div>
        </div>
    </div>
    <script type="text/javascript">
    //<![CDATA[
    if('{@$item.Status}' != '') initList('downloadDatabaseBox', {@$item.Status});
    //]]>
    </script>
{/if}
