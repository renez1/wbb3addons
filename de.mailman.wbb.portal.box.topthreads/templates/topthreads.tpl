    {if TOPTHREADS_SBCOLOR_ACP}
        {assign var='ttSecondBoxColor' value=TOPTHREADS_SBCOLOR_ACP}
    {else}
        {assign var='ttSecondBoxColor' value=2}
    {/if}
    {assign var='ttFirstBoxColor' value=1}
    {cycle values="$ttFirstBoxColor,$ttSecondBoxColor" print=false advance=false reset=true}
		<div class="border" id="box{$boxID}">
		    <div class="containerHead">
		    	<div class="containerIcon">
		    		<a href="javascript: void(0)" onclick="openList('topthreads', true)">
                	<img src="{@RELATIVE_WCF_DIR}icon/minusS.png" id="topthreadsImage" alt="" /></a>
                </div>
                <div class="containerContent">{lang}wbb.portal.box.topthreads.title{/lang}</div>
            </div>
            <div class="container-1" id="topthreads">
            	<div class="containerContent">
                    {if $item.threads|isset}
                        {foreach from=$item.threads item=$thread}
                            <div class="container-{cycle values="$ttFirstBoxColor,$ttSecondBoxColor"} smallFont" style="float:none;">
                                <div style="float:right;">&nbsp;{if TOPTHREADS_HITS}{@$thread.replies}{/if}</div>
                                <div><a href="index.php?page=Thread&amp;threadID={$thread.threadID}" title="{@$thread.title}">{@$thread.topic}</a></div>
                            </div>
                        {/foreach}
                    {else}
                        <p class="smallFont">{lang}wbb.portal.box.topthreads.nothreads{/lang}</p>
                    {/if}
                </div>
            </div>
        </div>
        <script type="text/javascript">
		//<![CDATA[
		initList('topthreads', {@$item.Status});
		//]]>
		</script>
