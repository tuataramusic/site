	
		<div class='content'>
			<h2>Аккаунт администратора</h2>
			<ul class='admin-buttons'>
				<li><a href='<?=$selfurl?>showAddPackage'>Добавить новую посылку</a><br /><a href='<?=$selfurl?>editPricelist'>Изменение тарифов на доставку</a></li>
				<li><a href='<?=$selfurl?>showEditServicesPrice'>Изменить цены за услуги</a><br /><a href='<?=$selfurl?>showEditNews'>Редактировать новости</a></li>
				<li><a href='<?=$selfurl?>showEditFAQ'>Редактировать F.A.Q.</a><br /><a href='<?=$selfurl?>showOrderToOut'>Заявки на вывод</a></li>
			</ul>
			<h3>Главная</h3>
			<form class='admin-inside' action='#'>
				<div class='sorting'>
					<span class='first-title'>Сортировать по партнеру:</span>
					<div class='select first-input'><div class='inner-bg'><div class='inner-inner-bg'>
						<div class='option active'>Выбрать партнера</div>
						<div class='hidden-option'>
							<div class='option'>eBay.com</div>
							<div class='option'>Molotok.ru</div>
							<div class='option'>Pokupki.ru</div>
						</div>
					</div></div></div>
					<span>за:</span>
					<div class='select'><div class='inner-bg'><div class='inner-inner-bg'>
						<div class='option active'>Весь день</div>
						<div class='hidden-option'>
							<div class='option'>Неделя</div>
							<div class='option'>Месяц</div>
							<div class='option'>Год</div>
						</div>
					</div></div></div>
				</div>
				<div class='sorting'>
					<span class='first-title'>Поиск заказа:</span>
					<div class='text-field first-input'><div><input type='text' value='Введите текст поиска' /></div></div>
					<span>по:</span>
					<div class='select'><div class='inner-bg'><div class='inner-inner-bg'>
						<div class='option active'>Номеру пользователя</div>
						<div class='hidden-option'>
							<div class='option'>Заказу</div>
							<div class='option'>Количеству покупок</div>
						</div>
					</div></div></div>
				</div>
				
				
				<ul class='tabs'>
					<li class='active'><div><a href='#'>Новые</a></div></li>
					<li><div><a href='#'>Оплаченные</a></div></li>
					<li><div><a href='#'>Отправленные</a></div></li>
					<li><div><a href='#'>Заказы “Помощь в покупке”</a></div></li>
					<li><div><a href='#'>Клиенты</a></div></li>
					<li><div><a href='#'>Партнеры</a></div></li>
				</ul>
				
				<div class='table'>
					<div class='angle angle-lt'></div>
					<div class='angle angle-rt'></div>
					<div class='angle angle-lb'></div>
					<div class='angle angle-rb'></div>
					<table>
						<col width='auto' />
						<col width='auto' />
						<col width='auto' />
						<col width='auto' />
						<col width='200' />
						<col width='auto' />
						<col width='80' />
						<col width='120' />
						<tr>
							<th>№</th>
							<th>Партнер,<br />страна</th>
							<th>Номер<br />клиента</th>
							<th>Номер посылки,<br />заказ</th>
							<th>Ф.И.О.,<br />адрес доставки</th>
							<th>Цена<br />доставки</th>
							<th>Статус</th>
							<th>Комментарии</th>
							<th>Декларация</th>
							<th class='last-child'></th>
						</tr>
						<tr>
							<td>1</td>
							<td>NIC, Корея</td>
							<td>105</td>
							<td>11000400055038, March 12, 2010 25 kg</td>
							<td>Сидоровой Светлане Петровне. 110555, ул. Ленина, 15, кв. 30, г. Москва, Россия <a href='#'>Подробнее</a></td>
							<td>2200 р. <a href='#'>Подробнее</a> 1200 р. + *400 р. + **50 р.</td>
							<td>Не оплачен <a href='#'>Изменить</a></td>
							<td>Добавлен новый комментарий <a href='#'>Посмотреть</a></td>
							<td>Заполнить самостоятельно <input class='check' type='checkbox' /></td>
							<td><a href='#' class='delete'>Удалить</a></td>
						</tr>
						<tr>
							<td>1</td>
							<td>NIC, Корея</td>
							<td>105</td>
							<td>11000400055038, March 12, 2010 25 kg</td>
							<td>Сидоровой Светлане Петровне. 110555, ул. Ленина, 15, кв. 30, г. Москва, Россия <a href='#'>Подробнее</a></td>
							<td>2200 р. <a href='#'>Подробнее</a> 1200 р. + *400 р. + **50 р.</td>
							<td>Не оплачен <a href='#'>Изменить</a></td>
							<td>Добавлен новый комментарий <a href='#'>Посмотреть</a></td>
							<td>Заполнить самостоятельно <input class='check' type='checkbox' /></td>
							<td><a href='#' class='delete'>Удалить</a></td>
						</tr>
						<tr>
							<td>1</td>
							<td>NIC, Корея</td>
							<td>105</td>
							<td>11000400055038, March 12, 2010 25 kg</td>
							<td>Сидоровой Светлане Петровне. 110555, ул. Ленина, 15, кв. 30, г. Москва, Россия <a href='#'>Подробнее</a></td>
							<td>2200 р. <a href='#'>Подробнее</a> 1200 р. + *400 р. + **50 р.</td>
							<td>Не оплачен <a href='#'>Изменить</a></td>
							<td>Добавлен новый комментарий <a href='#'>Посмотреть</a></td>
							<td>Заполнить самостоятельно <input class='check' type='checkbox' /></td>
							<td><a href='#' class='delete'>Удалить</a></td>
						</tr>
						<tr class='last-row'>
							<td colspan='9'>
								<div class='float'>	
									<div class='submit'><div><input type='submit' value='Сохранить' /></div></div>
									<span>Выбрать статус декларации:</span>
									<div class='select'><div class='inner-bg'><div class='inner-inner-bg'>
										<div class='option active'>Не заполнена</div>
										<div class='hidden-option'>
											<div class='option'>Заполнена</div>
										</div>
									</div></div></div>
								</div>
							</td>
							<td></td>
						</tr>
					</table>
				</div>
			</form>
			<?php if (isset($pager)) echo $pager ?>
		</div>
