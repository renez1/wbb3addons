  								<th colspan="2" class="columnFilename"><div><a href="#">{lang}wcf.acp.admintools.lostandfound.filename{/lang}</a></div></th>
                                <th class="columnFilesize"><div><a href="#">{lang}wcf.acp.admintools.lostandfound.filesize{/lang}</a></div></th>
								<th class="columnTime"><div><a href="#">{lang}wcf.acp.admintools.lostandfound.time{/lang}</a></div></th>
								{if $activeSubTabMenuItem == 'database'}<th class="columnUsername"><div><a href="#">{lang}wcf.acp.admintools.lostandfound.username{/lang}</a></div></th>{/if}
                            </tr>
                          </thead>
                          <tbody>
                		{foreach from=$itemData key=key item=item}								
                              	<tr class="{cycle}" id="attachmentsRow{$key}">                              	  
                              	<td class="columnMarkItems">
									<label><input id="attachmentsMark{$key}" type="checkbox" /></label>
								</td>								
								<td class="columnIcon">
									<img id="attachmentsEdit{$key}" src="{@RELATIVE_WCF_DIR}icon/lostAndFoundAttachmentItemM.png" alt="" />									
									{cycle print=false}
										<script type="text/javascript">
									//<![CDATA[
									itemData[{@$key}] = new Object();
									{if $markedItemsData.$key|isset}																																				
										itemData[{@$key}]['isMarked'] = 1;
									{/if}
									itemData[{@$key}]['class'] = '{cycle}';									
									itemData[{@$key}]['filename'] = '{@$item.filename}';
									//]]>									
								</script>
								</td>     								               
								<td class="columnFilename">																
									{@$item.filename}
								</td>
								<td class="columnFilesize">
									{$item.filesize}
								</td>
								<td class="columnTime">
									{$item.time|date}
								</td>
								{if $activeSubTabMenuItem == 'database'}
								<td class="columnUsername">
									{$item.username}
								</td>
								{/if}
                                </tr>
                                {/foreach}