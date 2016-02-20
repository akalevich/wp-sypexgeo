<div class="wrap">
	<h2>Настройки WP-Sypexgeo</h2>

	<?php
		if ($_POST['sgeo_language']) {
			// set language
			update_option('sgeo_language', $_POST['sgeo_language']);
			echo '<div class="updated"><p>Настройки обновлены.</p></div>';
		}
		if ($_POST['sgeo_dbase']) {
			// set language
			update_option('sgeo_dbase', $_POST['sgeo_dbase']);
			echo '<div class="updated"><p>Настройки обновлены.</p></div>';
		}
	?>

	<form method="post">
		<fieldset class="options">
			<legend>Язык, на котором будете вводит названия (стран, регионов, городов):</legend>
			<?php
				$sgeo_language = get_option('sgeo_language');
			?>
			<select name="sgeo_language">
				<option value="en"<?php if ($sgeo_language == 'en') {
					echo(' selected="selected"');
				} ?>>English
				</option>
				<option value="ru"<?php if ($sgeo_language == 'ru') {
					echo(' selected="selected"');
				} ?>>Русский
				</option>
			</select>

			<input type="submit" value="Изменить"/>

		</fieldset>
	</form>
	</br>
	<form method="post">
		<fieldset class="options">
			<legend>База данных:</legend>
			<?php
				$sgeo_dbase = get_option('sgeo_dbase');
			?>
			<select name="sgeo_dbase">
				<option value="loc"<?php if ($sgeo_dbase == 'loc') {
					echo(' selected="selected"');
				} ?>>Локальная
				</option>
				<option value="rm"<?php if ($sgeo_dbase == 'rm') {
					echo(' selected="selected"');
				} ?>>Удалённая*
				</option>
                <option value="query"<?php if ($sgeo_dbase == 'query') {
                    echo(' selected="selected"');
                } ?>>Парамеры (?GET or COOKIE)
                </option>
			</select>

			<input type="submit" value="Изменить"/>

		</fieldset>
	</form>
	</br>
	<p>*каждому пользователю без регистрации предоставляется 10 000 запросов (идентификация по IP). При регистрации на
		сайте выдается уникальный ключ для учета запросов, а также на счет добавляются бонусные запросы.
		(http://sypexgeo.net/ru/api/)</p>
</div>
<div>
	</br></br>
	<h3>Примеры использования:</h3>
	<p>
		<i>Для указания списка стран:</i> <code>[GeoCountry in=Belarus,Russia]Привет Belarus,Russia![/GeoCountry]</code></br>
		<i>Для указания списка регионов:</i> <code>[GeoRegion in=Moscow]Привет Moscow Region![/GeoRegion]</code></br>
		<i>Для указания списка городов:</i> <code>[GeoCity in=Минск,Брест]Привет Минск,Брест![/GeoCity]</code> или <code>[GeoCity in=Moscow text="Привет Москва"]</code></br>
		<i>Если вы хотите выбрасть страны (регионы, города) за исключением указанных, используйте "out":</i> <code>[GeoRegion out=Minsk,Brest]Привет всем, кроме Minsk,Brest![/GeoRegion]</code>
	</p>
</div>
