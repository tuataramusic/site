<div class='content'>
	<h3>����������� � ������ �<?=$order->order_id?></h3>
	<div class='back'>
		<a class='back' href='javascript:history.back();'><span>�����</span></a>
	</div><br />
	
	<form class='partner-inside-1' action='#'>
		
		<div class='table'>
			<div class='angle angle-lt'></div>
			<div class='angle angle-rt'></div>
			<div class='angle angle-lb'></div>
			<div class='angle angle-rb'></div>
			<table>
				<tr>
					<th>������ �</th>
					<th>�.�.�., ����� ��������</th>
					<th>����� ���� ������ <br />� ������ ������� ��������</th>
					<th>������</th>
				</tr>
				<tr>
					<td><?=$order->order_client?></td>
					<td><?=$order->order_address?></td>
					<td>
						����� �������� ���������� �������: <?=$order->order_cost?> �.<br />
						���� ��������: <?=$order->order_delivery_cost?> �.<br />
						����� ��� �������: <?=$order->order_weight?> ��
					</td>
					<td>
						<?if (	$order->order_status == 'not_available'):?>
							��� � �������
						<?elseif ($order->order_status == 'payed'):?>
							��������
						<?elseif ($order->order_status == 'not_payed'):?>
							�� ��������
						<?elseif ($order->order_status == 'sended' || $order->order_status == 'sent'):?>
							����������
						<?elseif ($order->order_status == 'proccessing'):?>
							��������������
						<?elseif ($order->order_status == 'deleted'):?>
							�������
						<?endif;?>
					</td>
				</tr>
				
			</table>
		</div>
	</form>
	
	<h3>����������� � ������</h3>
	<form class='comments' action='<?=$selfurl?>addOrderComment/<?=$order->order_id?>' method='POST'>
		<?if (!$comments):?>
			<div class='comment'>
				���� ��� ������������<br/>
			</div>
		<?else:?>
			<? foreach ($comments as $comment):?>
				<div class='comment'>
					<div class='question'>
					<?if ($comment->ocomment_user == $order->order_manager):?>
						<span class="name">��:</span>
					<?else:?>
						<span class="name">������:</span>
					<?endif;?>
						<p><?=$comment->ocomment_comment?></p>
					</div>
				</div>
			<? endforeach; ?>
		<?endif;?>
	
		<div class='add-comment'>
			<div class='textarea'><textarea name='comment'></textarea></div>
			<div class='submit'><div><input type='submit' name="add" value="��������" /></div></div>
		</div>
	</form>
</div>



<?/*
<br />
<div class='back'><a href=''javascript:history.back();''><span>�����</span></a></div>
<center>
<b>������� �<?=$order->order_id?></b><br/>
<table>
	<tr><td style="color: #aaa;">������ �</td><td><?=$order->order_client?></td></tr>
	<tr><td style="color: #aaa;">���</td><td><?=$order->order_weight?>��</td></tr>
	<tr><td style="color: #aaa;">���������</td><td><?=$order->order_cost?>�</td></tr>
</table>
</center>
<br/>�����������<br/><br/>
<?if (!$comments):?>
���� ��� ������������<br/>
<?else:?>
	<? foreach ($comments as $comment):?>
	<i><b><?if ($comment->ocomment_user == $order->order_client):?>������:<?else:?>��:<?endif;?></b>&nbsp;<?=$comment->ocomment_comment?></i><br/><br/>
	<? endforeach; ?>
<?endif;?>

<br/><b>�������� �����������</b><br/>
<form action='<?=$selfurl?>addOrderComment/<?=$order->order_id?>' method='POST'>
<textarea name='comment' cols="40" rows="5"></textarea><br/>
<input type="submit" name="add" value="��������"/>
</form>
*/?>