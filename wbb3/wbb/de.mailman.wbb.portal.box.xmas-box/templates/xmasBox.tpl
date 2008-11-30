{* $Id$ *}
		<div class="border" id="box{$boxID}" style="background: url({@RELATIVE_WBB_DIR}images/{XMASBOX_BACKGROUND}) no-repeat 50% 50%;padding: 0;">
            {if XMASBOX_HEADER}
		        <div class="containerHead">
                    {if !XMASBOX_BOXOPENED}
        		    	<div class="containerIcon">
        		    		<a href="javascript: void(0)" onclick="openList('xmasbox', true)"><img src="{@RELATIVE_WCF_DIR}icon/minusS.png" id="xmasBoxImage" alt="" /></a>
                        </div>
                    {/if}
                    <div class="containerContent">{lang}{XMASBOX_TITLE}{/lang}</div>
                </div>
            {/if}
            <div id="xmasbox" style="height: 256px; margin: 0;padding: 5px 0;overflow: hidden;position: relative;">
                <img src="{@RELATIVE_WBB_DIR}images/{XMASBOX_ANSAGER}" alt="{XMASBOX_ANSAGER}" style="margin: 0;" />
                <div id="bubble1" style="margin: 0; padding: 0;position: absolute; top: 25px; left: 180px;background: transparent url({@RELATIVE_WBB_DIR}images/bubble.png) no-repeat 0 30px;opacity: .8;-moz-opacity: .8;filter:Alpha(opacity=80);">
                    <div id="bubble2" style="margin: 0 0 0 40px;color: #000;padding: 30px 15px 40px;background-color: #fff;border: 1px solid #fff;border-radius: 25px;-moz-border-radius: 25px;">
                        <img src="{@RELATIVE_WBB_DIR}images/languages/{XMASBOX_VALUE}.png" alt="{lang}wbb.portal.box.xmasbox.lang{/lang} {XMASBOX_LANG}" />
                    </div>
                </div>
                <div id="lang" style="margin: 0; padding: 3px;position: absolute; bottom: 0; right: 0;background-color: #fff;opacity: .8;-moz-opacity: .8;filter:Alpha(opacity=80);color: #000;" class="smallFont">
                    {lang}wbb.portal.box.xmasbox.lang{/lang} {XMASBOX_LANG}
                </div>
            </div>
        </div>
        {if !XMASBOX_BOXOPENED}
            <script type="text/javascript" >
	        //<![CDATA[
	        if('{@$item.Status}' != '') initList('xmasbox', {@$item.Status});
	        //]]>
	        </script>
	    {/if}
