<div class='content'>
	<?if (isset($news) && count($news)>0):?>
		<h2>������� � ����������</h2>
		<div class='forward'><a href='<?=$selfurl?>showNewsList'><span>��� �������</span></a></div>
	
		<div class='news'>
		<?foreach($news as $item):?>
			<div class='this-news'>
				<span class='date'><?=date('d/m/Y', strtotime($item->news_addtime))?></span>
				<a href='<?=$selfurl.'showNewsList/1/0/'.$item->news_id;?>' class='title'><?=preg_replace("/^(.{1,200}).+?$/s","$1...",$item->news_body)?></a>
			</div>
		<?endforeach;?>
		</div>
	<?endif;?>
		
	<?if ($just_registered):?>
		<h2>����� ����������, <?=$user->user_login;?></h2>
		<span>
		������� �� ����������� �� Countrypost.ru<br/>
		��� ������� ����� �� ������ ���������� <a href='/main/showHowItWork'>���</a>.<br/>
		����� ������� ����� �������������� �� ��� ����� ���������� ��� ��������� ������ <a href='/client/showAddresses'>���</a>.<br/>
		����� ��� ��� ������� ����� ����������� ��� ��������� ���� �������. ������� ���������� ����� ����� ���������� <a href='#'>���</a>.<br/> 
		������ ��������� �������.
		</span>
	<?else:?>
		<h2>������ �������</h2>
			
		<div class='status-packet'>
			<a href='<?=$selfurl?>showOpenPackages' class='status-left-block'>
				<span>�������,<br />��������� ��������</span>
				<em><?=$package_open?> �������(�)</em>
			</a>
			<a href='<?=$selfurl?>showSentPackages'>
				<span>������������<br />�������</span>
				<em><?=$package_sent?> �������(�)</em>
			</a>
			<a href='<?=$selfurl?>showOpenOrders' class='status-right-block'>
				<span>������<br />� �������</span>
				<em>�������</em>
			</a>
		</div>
	<?endif;?>
</div>