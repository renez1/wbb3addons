{* $Id$ *}
{include file='header'}

{if $target == '_iframe'}
    <style type="text/css">
    .iFrame {
        {if $iFrame.width}width: {@$iFrame.width}{/if}
        {if $iFrame.height}height: {@$iFrame.height}{/if}
        {if $iFrame.borderWidth}
            border-width: {@$iFrame.borderWidth}
            {if $iFrame.borderStyle}border-style: {@$iFrame.borderStyle}{/if}
            {if $iFrame.borderColor}border-color: {@$iFrame.borderColor}{/if}
        {/if}
        overflow: auto;
    }
    </style>
    <iframe src="{@$url}" class="iFrame" name="iFrame" frameborder="0"></iframe>
{else if $target == '_blank'}
    <script type="text/javascript">
    //<![CDATA[
        window.open('{@$url}');
        history.back();
    //]]>
    </script>
{else if $target == '_self'}
    <script type="text/javascript">
    //<![CDATA[
        window.location.href = '{@$url}';
    //]]>
    </script>
{else}
    <p class="error">{lang}wcf.global.form.error{/lang}</p>
{/if}
{include file='footer'}
