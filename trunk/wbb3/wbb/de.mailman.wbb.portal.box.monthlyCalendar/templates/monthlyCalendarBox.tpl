{* $Id$ *}
{if $this->user->getPermission('user.board.canViewMonthlyCalendarBox')}
    {if $mcbShowCW}
        {assign var='mcbColDayH' value='mcbColDayCWH'}
    {else}
        {assign var='mcbColDayH' value='mcbColDayH'}
    {/if}
    <link rel="stylesheet" type="text/css" media="screen" href="./style/monthlyCalendarBox.css" />
    <div class="border" id="box{$boxID}">
        <div class="containerHead">
            <div class="containerIcon">
                <a href="javascript: void(0)" onclick="openList('monthlyCalendarBox', true)"><img src="{@RELATIVE_WCF_DIR}icon/minusS.png" id="monthlyCalendarBoxImage" alt="" /></a>
            </div>
            <div class="containerContent">
                <a name="monthlyCalendarBox"></a>
                <a href="index.php?page=Portal&amp;mcM={$mcCurM}&amp;mcY={$mcCurY}{@SID_ARG_2ND}#monthlyCalendarBox"><img src="{@RELATIVE_WBB_DIR}icon/mcbCurMonthS.png" alt="" title="{lang}wbb.portal.box.monthlyCalendar.curMonth{/lang}" /></a>
                {if $this->user->userID}
                    <a href="index.php?form=UserProfileEdit&amp;category=settings.display{@SID_ARG_2ND}">{@$mcbTitle}</a>
                {else}
                    {@$mcbTitle}
                {/if}
            </div>
        </div>
        <div class="container-1" id="monthlyCalendarBox">
            <div class="containerContent" style="margin:0; padding:0;">
                <table class="tableList border">
                    <thead>
                        <tr class="tableHead">
                            {if $mcbShowCW}<th class="container-3 {$mcbColDayH}">{lang}wbb.portal.box.monthlyCalendar.cw{/lang}</th>{/if}
                            <th class="{$mcbColDayH}">{lang}wbb.portal.box.monthlyCalendar.monday{/lang}</th>
                            <th class="{$mcbColDayH}">{lang}wbb.portal.box.monthlyCalendar.tuesday{/lang}</th>
                            <th class="{$mcbColDayH}">{lang}wbb.portal.box.monthlyCalendar.wednesday{/lang}</th>
                            <th class="{$mcbColDayH}">{lang}wbb.portal.box.monthlyCalendar.thursday{/lang}</th>
                            <th class="{$mcbColDayH}">{lang}wbb.portal.box.monthlyCalendar.friday{/lang}</th>
                            <th class="{$mcbColDayH}">{lang}wbb.portal.box.monthlyCalendar.saturday{/lang}</th>
                            <th class="{$mcbColDayH}">{lang}wbb.portal.box.monthlyCalendar.sunday{/lang}</th>
                        </tr>
                    </thead>
                    <tbody>
                        {counter name="i" assign=i start=0 print=false}
                        {foreach from=$mcDays item=day}
                            {counter name="i" print=false}
                            {if $i%7 == 1}
                                <tr class="{cycle values="container-1,container-2"}">
                                {if $mcbShowCW}
                                    <td class="container-3 mcbColCW">{@$mcbHelper->getCW($mcY, $mcM, $day.day)}</td>
                                {/if}
                            {/if}
                            {if $i == 1 && $daysBefore|count}
                                {foreach from=$daysBefore item=dummy}
                                    <td class="mcbColEmpty"></td>
                                    {counter name="i" print=false}
                                {/foreach}
                            {/if}
                            <td class="{if $day.day == $curDay}container-3 mcbColCurDay {/if}{if $day.birthday || $day.date || $day.holiday}mcbAppointment {/if}mcbColDay" title="{@$day.title}">
                                {if $this->user->getPermission('user.calendar.canUseCalendar')}
                                    <a href="index.php?page=CalendarMonth&amp;jumpToDay={@$day.day}&amp;jumpToMonth={$mcM}&amp;jumpToYear={$mcY}{@SID_ARG_2ND}">{@$day.day}</a>
                                {else if $this->user->getPermission('user.calendar.canEnter')}
                                    <a href="index.php?page=Calendar&amp;view=month&amp;month={$mcM}&amp;year={$mcY}{@SID_ARG_2ND}">{@$day.day}</a>
                                {else}
                                    {@$day.day}
                                {/if}
                            </td>
                            {if $i%7 == 0}
                                </tr>
                            {/if}
                        {/foreach}
                        {if $i%7 != 0 && $daysAfter|count}
                            {foreach from=$daysAfter item=dummy}
                                <td class="mcbColEmpty"></td>
                            {/foreach}
                            </tr>
                        {/if}
                    </tbody>
                </table>
                <div class="border" style="padding: 0 0 1px; margin: 1px 0; text-align: center; vertical-align: middle;">
                    <form method="get" action="index.php#monthlyCalendarBox">
                        <a href="index.php?page=Portal&amp;mcM={$mcM - 1}&amp;mcY={$mcY}{@SID_ARG_2ND}#monthlyCalendarBox"><img src="{@RELATIVE_WBB_DIR}icon/mcbPreviousS.png" alt="" title="{lang}wbb.portal.box.monthlyCalendar.prevMonth{/lang}" style="vertical-align: middle; margin: 0; padding: 0;" /></a>
                        <a href="index.php?page=Portal&amp;mcM={$mcM + 1}&amp;mcY={$mcY}{@SID_ARG_2ND}#monthlyCalendarBox"><img src="{@RELATIVE_WBB_DIR}icon/mcbNextS.png" alt="" title="{lang}wbb.portal.box.monthlyCalendar.nextMonth{/lang}" style="vertical-align: middle; margin: 0; padding: 0;" /></a>
                        <select name="mcM" style="margin: 1px; 0; padding:0; vertical-align: middle; border: none;">
                        {foreach from=$months item=month key=k}
                            <option value="{$k}"{if $mcM == $k} selected="selected"{/if}>{@$month}</option>
                        {/foreach}
                        </select>
                        <input type="text" name="mcY" value="{$mcY}" maxlength="4" class="smallFont" style="width: 30px; margin: 0; padding:0; vertical-align: middle; border: none;" />
                        <input type="image" class="inputImage" src="{@RELATIVE_WCF_DIR}icon/submitS.png" style="vertical-align: middle; margin: 0; padding:0;" />
                        <input type="hidden" name="page" value="Portal" />
                        {@SID_INPUT_TAG}
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
    //<![CDATA[
    if('{@$item.Status}' != '') initList('monthlyCalendarBox', {@$item.Status});
    //]]>
    </script>
{/if}
