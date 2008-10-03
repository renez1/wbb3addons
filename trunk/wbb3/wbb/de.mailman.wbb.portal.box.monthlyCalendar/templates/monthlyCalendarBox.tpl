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
                <a href="index.php?page={@$redirTo}&amp;mcM={$mcCurM}&amp;mcY={$mcCurY}{@SID_ARG_2ND}#monthlyCalendarBox"><img src="{@RELATIVE_WBB_DIR}icon/mcbCalendarS.png" alt="" title="{lang}wbb.portal.box.monthlyCalendar.curMonth{/lang}" /></a>
                {if $mcbShowAppointments}
                    <a href="javascript: void(0)" onclick="switchMcbView()"><img src="{@RELATIVE_WBB_DIR}icon/mcbAppointmentS.png" alt="" title="{lang}wbb.portal.box.monthlyCalendar.switchCalendar{/lang}" /></a>
                {/if}
                {if $mcbTitleLinkTo == 'UserProfileEdit'}
                    <a href="index.php?form=UserProfileEdit&amp;category=settings.display{@SID_ARG_2ND}">{@$mcbTitle}</a>
                {else}
                    {if $this->user->getPermission('user.calendar.canUseCalendar')}
                        <a href="index.php?page=CalendarMonth&amp;jumpToMonth={$mcM}&amp;jumpToYear={$mcY}{@SID_ARG_2ND}">{@$mcbTitle}</a>
                    {else if $this->user->getPermission('user.calendar.canEnter')}
                        <a href="index.php?page=Calendar&amp;month={$mcM}&amp;year={$mcY}{@SID_ARG_2ND}">{@$mcbTitle}</a>
                    {else}
                        {@$mcbTitle}
                    {/if}
                {/if}
            </div>
        </div>
        <div class="container-1" id="monthlyCalendarBox">
            <div class="containerContent" style="margin:0; padding:0;{if $mcbShowAppointments && $mcbShowAppointmentsAsDefault} display:none;{/if}" id="mcbViewCalendar">
                {if !MONTHLYCALENDARBOX_NAV_BOTTOM}
                    {include file=monthlyCalendarNavBox}
                {/if}
                <table class="tableList border">
                    <thead>
                        <tr class="tableHead">
                            {if $mcbShowCW}<th class="container-3 {$mcbColDayH}">{lang}wbb.portal.box.monthlyCalendar.cw{/lang}</th>{/if}
                            <th class="{$mcbColDayH}">{lang}wbb.portal.box.monthlyCalendar.monday{/lang}</th>
                            <th class="{$mcbColDayH}">{lang}wbb.portal.box.monthlyCalendar.tuesday{/lang}</th>
                            <th class="{$mcbColDayH}">{lang}wbb.portal.box.monthlyCalendar.wednesday{/lang}</th>
                            <th class="{$mcbColDayH}">{lang}wbb.portal.box.monthlyCalendar.thursday{/lang}</th>
                            <th class="{$mcbColDayH}">{lang}wbb.portal.box.monthlyCalendar.friday{/lang}</th>
                            <th class="{$mcbColDayH} container-3">{lang}wbb.portal.box.monthlyCalendar.saturday{/lang}</th>
                            <th class="{$mcbColDayH} container-3">{lang}wbb.portal.box.monthlyCalendar.sunday{/lang}</th>
                        </tr>
                    </thead>
                    <tbody>
                        {counter name="i" assign=i start=0 print=false}
                        {foreach from=$mcDays item=day}
                            {counter name="i" print=false}
                            {if $i%7 == 1}
                                <tr class="{cycle values="container-1,container-2"}">
                                {if $mcbShowCW}
                                    <td class="container-3 mcbColCW mcbColDay{MONTHLYCALENDARBOX_COL_ALIGN}">{@$mcbHelper->getCW($mcY, $mcM, $day.day)}</td>
                                {/if}
                            {/if}
                            {if $i == 1 && $daysBefore|count}
                                {foreach from=$daysBefore item=db}
                                    <td class="{if $db.weekday == 1 || $db.weekday == 7}container-3 {/if}light mcbColEmpty mcbColDay{MONTHLYCALENDARBOX_COL_ALIGN}">{$db.day}</td>
                                    {counter name="i" print=false}
                                {/foreach}
                            {/if}
                            {if $day.birthday || $day.appointment || $day.holiday}
                                {if $day.birthday && $day.appointment && $day.holiday} {assign var='mcbIS' value='BHA'}
                                {else if $day.birthday && $day.appointment} {assign var='mcbIS' value='BA'}
                                {else if $day.birthday && $day.holiday} {assign var='mcbIS' value='BH'}
                                {else if $day.holiday && $day.appointment} {assign var='mcbIS' value='HA'}
                                {else if $day.appointment} {assign var='mcbIS' value='A'}
                                {else if $day.holiday} {assign var='mcbIS' value='H'}
                                {else if $day.birthday} {assign var='mcbIS' value='B'}
                                {else} {assign var='mcbIS' value=''}
                                {/if}
                            {else} {assign var='mcbIS' value=''}
                            {/if}
                            {if !$mcbIS|empty} {assign var='mcbIMG' value='mcbDay'|concat:$mcbIS}
                            {else} {assign var='mcbIMG' value=''}
                            {/if}
                                
                            <td class="{if $day.day == $curDay}container-3 mcbColCurDay {else if $day.weekday == 1 || $day.weekday == 7}container-3 {/if}{if !$mcbIMG|empty}mcbEvent{MONTHLYCALENDARBOX_COL_ALIGN} {/if}mcbColDay mcbColDay{MONTHLYCALENDARBOX_COL_ALIGN}"{if !$mcbIMG|empty} style="background-image: url('{@RELATIVE_WBB_DIR}icon/{@$mcbIMG}.png');"{/if} title="{@$day.title}">
                                {if $this->user->getPermission('user.calendar.canUseCalendar')}
                                    <a href="index.php?page=CalendarMonth&amp;jumpToDay={@$day.day}&amp;jumpToMonth={$mcM}&amp;jumpToYear={$mcY}{@SID_ARG_2ND}">{@$day.day}</a>
                                {else if $this->user->getPermission('user.calendar.canEnter')}
                                    <a href="index.php?page=Calendar&amp;view=day&amp;day={@$day.day}&amp;month={$mcM}&amp;year={$mcY}{@SID_ARG_2ND}">{@$day.day}</a>
                                {else}
                                    {@$day.day}
                                {/if}
                            </td>
                            {if $i%7 == 0}
                                </tr>
                            {/if}
                        {/foreach}
                        {if $i%7 != 0 && $daysAfter|count}
                            {foreach from=$daysAfter item=da}
                                <td class="{if $da.weekday == 1 || $da.weekday == 7}container-3 {/if}light mcbColEmpty mcbColDay{MONTHLYCALENDARBOX_COL_ALIGN}">{$da.day}</td>
                            {/foreach}
                            </tr>
                        {/if}
                    </tbody>
                </table>
                {if MONTHLYCALENDARBOX_NAV_BOTTOM}
                    {include file=monthlyCalendarNavBox}
                {/if}
            </div>
            {if $mcbShowAppointments}
                <div class="containerContent smallFont" id="mcbViewAppointment"{if !$mcbShowAppointmentsAsDefault} style="display:none;"{/if}>
                    {if $mcbAppointments|count}
                        {foreach from=$mcbAppointments item=app}
                            {if $app.today}
                                {assign var='dTime' value=$app.startTime|time:"%H.%M"}
                                {assign var='aDate' value='<span style="font-weight:bold;">'|concat:$dTime:'</span>'}
                                {assign var='sCut' value=MONTHLYCALENDARBOX_MAXLEN+5}
                            {else}
                                {assign var='aDate' value=$app.startTime|time:"%d.%m.%Y"}
                                {assign var='sCut' value=MONTHLYCALENDARBOX_MAXLEN}
                            {/if}

                            {if MONTHLYCALENDARBOX_MAXLEN > 0 && $app.subject|strlen > MONTHLYCALENDARBOX_MAXLEN}
                                {if USE_MBSTRING}{assign var="mcbSubject" value=$app.subject|mb_substr:0:$sCut-3|concat:'...'}
                                {else}{assign var="mcbSubject" value=$app.subject|substr:0:$sCut-3|concat:'...'}
                                {/if}
                            {else}
                                {assign var="mcbSubject" value=$app.subject}
                            {/if}
                            {if $this->user->getPermission('user.calendar.canUseCalendar')}
                                <p><a href="index.php?page=CalendarEvent&eventID={$app.eventID}{@SID_ARG_2ND}" title="{@$app.title}">{@$aDate}: {@$mcbSubject}</a></p>
                            {else if $this->user->getPermission('user.calendar.canEnter')}
                                <p><a href="index.php?page=CalendarViewEvent&eventID={$app.eventID}{@SID_ARG_2ND}" title="{@$app.title}">{@$aDate}: {@$mcbSubject}</a></p>
                            {else}
                                {lang}wbb.portal.box.monthlyCalendar.noAppointments{/lang}
                            {/if}
                        {/foreach}
                    {else}
                        {lang}wbb.portal.box.monthlyCalendar.noAppointments{/lang}
                    {/if}
                </div>
            {/if}
        </div>
    </div>
    <script type="text/javascript">
    //<![CDATA[
    if('{@$item.Status}' != '') initList('monthlyCalendarBox', {@$item.Status});

    {if $mcbShowAppointments}
        function switchMcbView() {
            if(document.getElementById('mcbViewCalendar').style.display == 'none') {
                document.getElementById('mcbViewCalendar').style.display = '';
                document.getElementById('mcbViewAppointment').style.display = 'none';
            } else {
                document.getElementById('mcbViewCalendar').style.display = 'none';
                document.getElementById('mcbViewAppointment').style.display = '';
            }
        }
    {/if}
    //]]>
    </script>
{/if}
