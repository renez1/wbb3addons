    {if TOPTHREADVIEWS_SBCOLOR_ACP}
        {assign var='secondBox' value=TOPTHREADVIEWS_SBCOLOR_ACP}
    {else}
        {assign var='secondBox' value=2}
    {/if}
    {assign var='firstBox' value=1}
    {cycle values="$firstBox,$secondBox" print=false advance=false reset=true}
		<div class="border" id="box{$boxID}">
		    <div class="containerHead">
		    	<div class="containerIcon">
		    		<a href="javascript: void(0)" onclick="openList('topthreadviews', true)">
                	<img src="{@RELATIVE_WCF_DIR}icon/minusS.png" id="topthreadviewsImage" alt="" /></a>
                </div>
                <div class="containerContent">{lang}wbb.portal.box.topthreadviews.title{/lang}</div>
            </div>
            <div class="container-1" id="topthreadviews">
            	<div class="containerContent">
                    {if $item.threads|isset}
                        {foreach from=$item.threads item=$thread}
                            <div class="container-{cycle values="$firstBox,$secondBox"} smallFont" style="float:none;">
                                <div style="float:right;">&nbsp;{if TOPTHREADVIEWS_HITS}{@$thread.views}{/if}</div>
                                <div><a href="index.php?page=Thread&amp;threadID={$thread.threadID}" title="{@$thread.title}">{@$thread.topic}</a></div>
                            </div>
                        {/foreach}
                    {else}
                        	<p class="smallFont">{lang}wbb.portal.box.topthreadviews.nothreads{/lang}</p>
                    {/if}
                </div>
            </div>
        </div>
        <script type="text/javascript">
		//<![CDATA[
		initList('topthreadviews', {@$item.Status});
		//]]>
		</script>
