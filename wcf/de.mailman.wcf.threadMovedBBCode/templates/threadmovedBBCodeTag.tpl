{* $Id$ *}
<div style="border: 1px solid red;background-color: #fff;text-align: center;">
        <span style="font-size: 1.2em;font-weight: bold;color: red;">{lang}wcf.bbcode.threadmoved.notice{/lang}</span><br />
		<img src="{@RELATIVE_WCF_DIR}images/threadMovedNotice.png" alt="{lang}wcf.bbcode.threadmoved.notice{/lang}" /><br />
        {if $content}<span style="color: red;"><strong>{$this->user->username}:</strong> {@$content}</span>{/if}
</div>
