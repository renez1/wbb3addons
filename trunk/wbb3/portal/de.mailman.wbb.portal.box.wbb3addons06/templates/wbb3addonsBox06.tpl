    {if $this->user->getPermission('user.board.canViewWbb3addonsBox06')}
		<div class="border" id="box{$boxID}">
		    <div class="containerHead">
                {if !$wbb3addonsBox06Opened}
    		    	<div class="containerIcon">
    		    		<a href="javascript: void(0)" onclick="openList('wbb3addonsBox06', true)"><img src="{@RELATIVE_WCF_DIR}icon/minusS.png" id="wbb3addonsBox06Image" alt="" /></a>
                    </div>
                {/if}
                <div class="containerContent">{lang}{@$wbb3addonsBox06Title}{/lang}</div>
            </div>
            <div id="wbb3addonsBox06">{@$wbb3addonsBox06Value}</div>
        </div>
        <script type="text/javascript">
		//<![CDATA[
		if('{@$item.Status}' != '') initList('wbb3addonsBox06', {@$item.Status});
		//]]>
		</script>
    {/if}
