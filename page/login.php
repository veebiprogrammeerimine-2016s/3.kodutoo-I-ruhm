<!DOCTYPE html>

<!--
	mvp - Lehekülg, kus omades kasutajat on võimalik salvestada oma 3x3x3 või 2x2x2 ruubiku kuubiku lahenduste aegu (iga aja kohta ka segamise valem). Kasutajanime järgi otsides võimalik näha teiste lahenduste aegu. Enda infost lähtuvalt saada statistikat nagu:
			* kiireim aeg
			* kiireim 5 lahenduse keskmine
			* kiireim 12 lahenduse keskmine
			* 100 lahenduse keskmine
			* viimase 5 lahenduse keskmine
			* viimase 12 lahenduse keskmine
			* lahenduste arv kokku
 -->

<?php

	require_once('../functions.php');
		
	if (isset($_SESSION['username']) ) {
		header('Location: data.php');
		exit();
	}
	
	$login_username = '';
	$signup_email = '';
	$signup_name = '';
	$signup_username = '';
	$date_of_birth = '';
	
	$login_error = '';
	$login_username_Error = '';
	$login_password_Error = '';
	$signup_username_Error = '';
	$signup_password_Error = '';
	$signup_name_Error = '';
	$signup_email_Error = '';
	$date_of_birth_Error = '';
	
	$not_31_day_months = array('month02', 'month04', 'month06', 'month09', 'month11');
	$days_more_than_28 = array('day29', 'day30', 'day31');
	$days_more_than_29 = array('day30', 'day31');
	
	
	if (isset ($_POST['signup_username'])) {
		$signup_username_Error = checkField($_POST['signup_username']);
		$signup_username = $_POST['signup_username'];
	}
	
	if (isset ($_POST['signup_name'])) {
		$signup_name_Error = checkField($_POST['signup_name']);
		$signup_name = $_POST['signup_name'];
	}
	
	if (isset ($_POST['signup_email'])) {
		$signup_email_Error = checkField($_POST['signup_email']);
		$signup_email = $_POST['signup_email'];
	}
	
	if (isset ($_POST['login_username'])) {
		$login_username_Error = checkField($_POST['login_username']);
		$login_username = $_POST['login_username'];
	}
	
	if (isset ($_POST['login_password'])) {
		$login_password_Error = checkField($_POST['login_password']);
		$login_password = $_POST['login_password'];
	}
			
	if ( isset ( $_POST['signup_password'] ) ) {
		if ( empty ( $_POST['signup_password'] ) ) {
			$signup_password_Error = 'Väli salasõna on kohustuslik!';
		} elseif ( strlen( $_POST['signup_password'] ) < 2 ) {
			$signup_password_Error = 'Salasõna peab olema vähemalt 8 tähemärki pikk!';
		} else {
			$signup_password_hash = hash('sha512', $_POST['signup_password']);
		}
	}
	
	if (isset($_POST['day_of_birth']) &&
		isset($_POST['month_of_birth']) &&
		isset($_POST['year_of_birth'])) {
			$date_of_birth = substr( $_POST['year_of_birth'], -4) .'-'. substr( $_POST['month_of_birth'], -2).'-'. substr( $_POST['day_of_birth'], -2);
			$day_of_birth = $_POST['day_of_birth'];
			$month_of_birth = $_POST['month_of_birth'];
			$year_of_birth = $_POST['year_of_birth'];
	} else {
		$year_of_birth = 'year2016';
	}
	
	if ( isset ( $_POST['year_of_birth'] ) ) {
		if ( ( $_POST['month_of_birth'] == 'month02' ) AND ( ( substr( $_POST['year_of_birth'], -4 ) % 4 != 0 ) OR ( ( substr( $_POST['year_of_birth'], -4 ) % 400 != 0 ) AND ( substr( $_POST['year_of_birth'], -4 ) % 100 == 0 ) ) ) AND (  in_array ( $_POST['day_of_birth'], $days_more_than_28 ) ) ) {
			$date_of_birth_Error = 'Valitud kuus ei ole antud aastal niipalju päevi!';
		} elseif ( ( $_POST['month_of_birth'] == 'month02' ) AND ( substr( $_POST['year_of_birth'], -4 ) % 4 == 0 ) AND (  in_array ( $_POST['day_of_birth'], $days_more_than_29 ) ) ) {
			$date_of_birth_Error = 'Valitud kuus ei ole antud aastal niipalju päevi!';
		} elseif ( ( substr( $_POST['year_of_birth'], -4 ) == date('Y') ) AND ( ( substr( $_POST['month_of_birth'], -2 ) > date('m') ) OR ( ( substr( $_POST['month_of_birth'], -2 ) == date('m') ) AND ( substr( $_POST['day_of_birth'], -2 ) > date('d') ) ) ) ) {
			$date_of_birth_Error = 'Valitud kuupäev pole veel kätte jõudnud.';
		}
	}
	
	if ( isset ( $_POST['month_of_birth'] ) ) {
		if ( in_array( $_POST['month_of_birth'], $not_31_day_months ) AND ( $_POST['day_of_birth'] == 'day31' ) ) {
			$date_of_birth_Error = 'Valitud kuus ei ole niipalju päevi!';
		} elseif ($_POST['month_of_birth'] == 'month02' AND $_POST['day_of_birth'] == 'day30') {
			$date_of_birth_Error = 'Valitud kuus ei ole niipalju päevi!';
		}
	}
	
	if ($date_of_birth_Error == "Valitud kuus ei ole niipalju päevi!" OR
		$date_of_birth_Error == 'Valitud kuus ei ole antud aastal niipalju päevi!' OR
		$date_of_birth_Error == 'Valitud kuupäev pole veel kätte jõudnud.') {
		$day_of_birth = "day01";
	}
	
	if (isset($_POST['day_of_birth']) &&
		isset($_POST['month_of_birth']) &&
		isset($_POST['year_of_birth']) &&
		isset($_POST['signup_password']) &&
		isset($_POST['signup_email']) &&
		isset($_POST['signup_name']) &&
		isset($_POST['signup_username']) &&
		empty($signup_username_Error) &&
		empty($signup_password_Error) &&
		empty($signup_name_Error) &&
		empty($signup_email_Error) &&
		empty($date_of_birth_Error)) {
			
			$signup_email = clean_input($signup_email);
			$signup_password_hash = clean_input($signup_password_hash);
			$signup_username = clean_input($signup_username);
			$signup_name = clean_input($signup_name);
			$date_of_birth = clean_input($date_of_birth);
			$now = clean_input($now);
	
			$User->signup($signup_email, $signup_password_hash, $signup_username, $signup_name, $date_of_birth, $now);
			$signup_name = '';
			$signup_username = '';
			$signup_email = '';
			$date_of_birth_Error = '';
	}

	if (isset($_POST['login_username']) &&
		isset($_POST['login_password']) &&
		!empty($_POST['login_username']) &&
		!empty($_POST['login_password'])) {
			$login_username = clean_input($login_username);
			$login_password = clean_input($login_password);
			$login_error = $User->login($login_username, $login_password);
	}
	
?>

<html>
	<head>
		<title> Kasutaja loomine </title>
	</head>
	<body>
		<h1>Logi sisse</h1>
		
			<!-- atribuudid asuvad algus tagi sees -->	
			<form method = "POST">
				<!-- välja üleval tekstina			<label> E-post </label> <br> -->
				<table>
					<tr> <td> <input name = "login_username" type = "text" placeholder = "Kasutajanimi" value = '<?=$login_username?>'> </td> <td style = 'color:red'> <?=$login_username_Error?> </td> </tr>
					<tr> <td> <input name = "login_password" type = "password" placeholder = "Parool"> </td> <td style = 'color:red'> <?=$login_password_Error?> </td> </tr>
					<tr> <td style = 'text-align:right'> <input type = "submit" value = "Logi sisse"> </td> </tr>
					<tr> <td style = "color:red"> <?=$login_error?> </td> </tr>
				</table>
			</form>
			
		<!-- info saatmine kahel viisil>
		1. urliga GET=url?m=v&m2=v2
		2. varjatud POST
		turvalisuse mõttes pole vahet, aga ebameeldiv, kui urli pealt PWd lugeda saab
		-->
		
		<h1> Loo kasutaja </h1>
			<form method = 'POST'>
				<table>
					<tr>
						<td> <label> Kasutajatunnus: </label> </td>
						<td> <input size = '22' name = 'signup_username' type = 'text' placeholder = 'Kasutajatunnus' value = '<?=$signup_username?>'> </td>
						<td style = 'color:red'> <?php echo $signup_username_Error ?> </td>
					</tr>
					<tr>
						<td> <label> Salasõna: </label> </td>
						<td> <input size = '22' name = 'signup_password' type = 'password' placeholder = 'Salasõna'> </td>
						<td style = 'color:red'> <?php echo $signup_password_Error ?> </td>
					</tr>
					<tr>
						<td> <label> Nimi: </label> </td>
						<td> <input size = '22' name = 'signup_name' type = 'text' placeholder = 'Nimi' value = '<?=$signup_name?>'> </td>
						<td style = 'color:red'> <?php echo $signup_name_Error ?> </td>
					</tr>
					<tr>
						<td> <label> E-post: </label> </td>
						<td> <input size = '22' name = 'signup_email' type = 'email' placeholder = 'E-post' value = '<?=$signup_email?>'> </td>
						<td style = 'color:red'> <?php echo $signup_email_Error ?> </td>
					</tr>
					<tr>
						<td> <label> Sünnikuupäev: </label> </td>
						<td>
							<select name = 'day_of_birth'>
								<option	value =	'day01' <?php if ($day_of_birth == 'day01') {echo 'selected';}?> > 1 </option>
								<option	value =	'day02' <?php if ($day_of_birth == 'day02') {echo 'selected';}?> > 2 </option>
								<option	value =	'day03' <?php if ($day_of_birth == 'day03') {echo 'selected';}?> > 3 </option>
								<option	value =	'day04' <?php if ($day_of_birth == 'day04') {echo 'selected';}?> > 4 </option>
								<option	value =	'day05' <?php if ($day_of_birth == 'day05') {echo 'selected';}?> > 5 </option>
								<option	value =	'day06' <?php if ($day_of_birth == 'day06') {echo 'selected';}?> > 6 </option>
								<option	value =	'day07' <?php if ($day_of_birth == 'day07') {echo 'selected';}?> > 7 </option>
								<option	value =	'day08' <?php if ($day_of_birth == 'day08') {echo 'selected';}?> > 8 </option>
								<option	value =	'day09' <?php if ($day_of_birth == 'day09') {echo 'selected';}?> > 9 </option>
								<option	value =	'day10' <?php if ($day_of_birth == 'day10') {echo 'selected';}?> > 10 </option>
								<option	value =	'day11' <?php if ($day_of_birth == 'day11') {echo 'selected';}?> > 11 </option>
								<option	value =	'day12' <?php if ($day_of_birth == 'day12') {echo 'selected';}?> > 12 </option>
								<option	value =	'day13' <?php if ($day_of_birth == 'day13') {echo 'selected';}?> > 13 </option>
								<option	value =	'day14' <?php if ($day_of_birth == 'day14') {echo 'selected';}?> > 14 </option>
								<option	value =	'day15' <?php if ($day_of_birth == 'day15') {echo 'selected';}?> > 15 </option>
								<option	value =	'day16' <?php if ($day_of_birth == 'day16') {echo 'selected';}?> > 16 </option>
								<option	value =	'day17' <?php if ($day_of_birth == 'day17') {echo 'selected';}?> > 17 </option>
								<option	value =	'day18' <?php if ($day_of_birth == 'day18') {echo 'selected';}?> > 18 </option>
								<option	value =	'day19' <?php if ($day_of_birth == 'day19') {echo 'selected';}?> > 19 </option>
								<option	value =	'day20' <?php if ($day_of_birth == 'day20') {echo 'selected';}?> > 20 </option>
								<option	value =	'day21' <?php if ($day_of_birth == 'day21') {echo 'selected';}?> > 21 </option>
								<option	value =	'day22' <?php if ($day_of_birth == 'day22') {echo 'selected';}?> > 22 </option>
								<option	value =	'day23' <?php if ($day_of_birth == 'day23') {echo 'selected';}?> > 23 </option>
								<option	value =	'day24' <?php if ($day_of_birth == 'day24') {echo 'selected';}?> > 24 </option>
								<option	value =	'day25' <?php if ($day_of_birth == 'day25') {echo 'selected';}?> > 25 </option>
								<option	value =	'day26' <?php if ($day_of_birth == 'day26') {echo 'selected';}?> > 26 </option>
								<option	value =	'day27' <?php if ($day_of_birth == 'day27') {echo 'selected';}?> > 27 </option>
								<option	value =	'day28' <?php if ($day_of_birth == 'day28') {echo 'selected';}?> > 28 </option>
								<option	value =	'day29' <?php if ($day_of_birth == 'day29') {echo 'selected';}?> > 29 </option>
								<option	value =	'day30' <?php if ($day_of_birth == 'day30') {echo 'selected';}?> > 30 </option>
								<option	value =	'day31' <?php if ($day_of_birth == 'day31') {echo 'selected';}?> > 31 </option>
							</select>
							<select name = 'month_of_birth'>
								<option	value = 'month01' <?php if ($month_of_birth == 'month01') {echo 'selected';}?> > Jaanuar </option>
								<option	value = 'month02' <?php if ($month_of_birth == 'month02') {echo 'selected';}?> > Veebruar </option>
								<option	value = 'month03' <?php if ($month_of_birth == 'month03') {echo 'selected';}?> > Märts </option>
								<option	value = 'month04' <?php if ($month_of_birth == 'month04') {echo 'selected';}?> > Aprill </option>
								<option	value = 'month05' <?php if ($month_of_birth == 'month05') {echo 'selected';}?> > Mai </option>
								<option	value = 'month06' <?php if ($month_of_birth == 'month06') {echo 'selected';}?> > Juuni </option>
								<option	value = 'month07' <?php if ($month_of_birth == 'month07') {echo 'selected';}?> > Juuli </option>
								<option	value = 'month08' <?php if ($month_of_birth == 'month08') {echo 'selected';}?> > August </option>
								<option	value = 'month09' <?php if ($month_of_birth == 'month09') {echo 'selected';}?> > September </option>
								<option	value = 'month10' <?php if ($month_of_birth == 'month10') {echo 'selected';}?> > Oktoober </option>
								<option	value = 'month11' <?php if ($month_of_birth == 'month11') {echo 'selected';}?> > November </option>
								<option	value = 'month12' <?php if ($month_of_birth == 'month12') {echo 'selected';}?> > Detsember </option>
							</select>
							<select name = 'year_of_birth'>
								<option value = 'year1901' <?php if ($year_of_birth == 'year1901') {echo 'selected';}?> >	1901 </option>
								<option value = 'year1902' <?php if ($year_of_birth == 'year1902') {echo 'selected';}?> >	1902 </option>
								<option value = 'year1900' <?php if ($year_of_birth == 'year1900') {echo 'selected';}?> >	1900 </option>
								<option value = 'year1903' <?php if ($year_of_birth == 'year1903') {echo 'selected';}?> >	1903 </option>
								<option value = 'year1904' <?php if ($year_of_birth == 'year1904') {echo 'selected';}?> >	1904 </option>
								<option value = 'year1905' <?php if ($year_of_birth == 'year1905') {echo 'selected';}?> >	1905 </option>
								<option value = 'year1906' <?php if ($year_of_birth == 'year1906') {echo 'selected';}?> >	1906 </option>
								<option value = 'year1907' <?php if ($year_of_birth == 'year1907') {echo 'selected';}?> >	1907 </option>
								<option value = 'year1908' <?php if ($year_of_birth == 'year1908') {echo 'selected';}?> >	1908 </option>
								<option value = 'year1909' <?php if ($year_of_birth == 'year1909') {echo 'selected';}?> >	1909 </option>
								<option value = 'year1910' <?php if ($year_of_birth == 'year1910') {echo 'selected';}?> >	1910 </option>
								<option value = 'year1911' <?php if ($year_of_birth == 'year1911') {echo 'selected';}?> >	1911 </option>
								<option value = 'year1912' <?php if ($year_of_birth == 'year1912') {echo 'selected';}?> >	1912 </option>
								<option value = 'year1913' <?php if ($year_of_birth == 'year1913') {echo 'selected';}?> >	1913 </option>
								<option value = 'year1914' <?php if ($year_of_birth == 'year1914') {echo 'selected';}?> >	1914 </option>
								<option value = 'year1915' <?php if ($year_of_birth == 'year1915') {echo 'selected';}?> >	1915 </option>
								<option value = 'year1916' <?php if ($year_of_birth == 'year1916') {echo 'selected';}?> >	1916 </option>
								<option value = 'year1917' <?php if ($year_of_birth == 'year1917') {echo 'selected';}?> >	1917 </option>
								<option value = 'year1918' <?php if ($year_of_birth == 'year1918') {echo 'selected';}?> >	1918 </option>
								<option value = 'year1919' <?php if ($year_of_birth == 'year1919') {echo 'selected';}?> >	1919 </option>
								<option value = 'year1920' <?php if ($year_of_birth == 'year1920') {echo 'selected';}?> >	1920 </option>
								<option value = 'year1921' <?php if ($year_of_birth == 'year1921') {echo 'selected';}?> >	1921 </option>
								<option value = 'year1922' <?php if ($year_of_birth == 'year1922') {echo 'selected';}?> >	1922 </option>
								<option value = 'year1923' <?php if ($year_of_birth == 'year1923') {echo 'selected';}?> >	1923 </option>
								<option value = 'year1924' <?php if ($year_of_birth == 'year1924') {echo 'selected';}?> >	1924 </option>
								<option value = 'year1925' <?php if ($year_of_birth == 'year1925') {echo 'selected';}?> >	1925 </option>
								<option value = 'year1926' <?php if ($year_of_birth == 'year1926') {echo 'selected';}?> >	1926 </option>
								<option value = 'year1927' <?php if ($year_of_birth == 'year1927') {echo 'selected';}?> >	1927 </option>
								<option value = 'year1928' <?php if ($year_of_birth == 'year1928') {echo 'selected';}?> >	1928 </option>
								<option value = 'year1929' <?php if ($year_of_birth == 'year1929') {echo 'selected';}?> >	1929 </option>
								<option value = 'year1930' <?php if ($year_of_birth == 'year1930') {echo 'selected';}?> >	1930 </option>
								<option value = 'year1931' <?php if ($year_of_birth == 'year1931') {echo 'selected';}?> >	1931 </option>
								<option value = 'year1932' <?php if ($year_of_birth == 'year1932') {echo 'selected';}?> >	1932 </option>
								<option value = 'year1933' <?php if ($year_of_birth == 'year1933') {echo 'selected';}?> >	1933 </option>
								<option value = 'year1934' <?php if ($year_of_birth == 'year1934') {echo 'selected';}?> >	1934 </option>
								<option value = 'year1935' <?php if ($year_of_birth == 'year1935') {echo 'selected';}?> >	1935 </option>
								<option value = 'year1936' <?php if ($year_of_birth == 'year1936') {echo 'selected';}?> >	1936 </option>
								<option value = 'year1937' <?php if ($year_of_birth == 'year1937') {echo 'selected';}?> >	1937 </option>
								<option value = 'year1938' <?php if ($year_of_birth == 'year1938') {echo 'selected';}?> >	1938 </option>
								<option value = 'year1939' <?php if ($year_of_birth == 'year1939') {echo 'selected';}?> >	1939 </option>
								<option value = 'year1940' <?php if ($year_of_birth == 'year1940') {echo 'selected';}?> >	1940 </option>
								<option value = 'year1941' <?php if ($year_of_birth == 'year1941') {echo 'selected';}?> >	1941 </option>
								<option value = 'year1942' <?php if ($year_of_birth == 'year1942') {echo 'selected';}?> >	1942 </option>
								<option value = 'year1943' <?php if ($year_of_birth == 'year1943') {echo 'selected';}?> >	1943 </option>
								<option value = 'year1944' <?php if ($year_of_birth == 'year1944') {echo 'selected';}?> >	1944 </option>
								<option value = 'year1945' <?php if ($year_of_birth == 'year1945') {echo 'selected';}?> >	1945 </option>
								<option value = 'year1946' <?php if ($year_of_birth == 'year1946') {echo 'selected';}?> >	1946 </option>
								<option value = 'year1947' <?php if ($year_of_birth == 'year1947') {echo 'selected';}?> >	1947 </option>
								<option value = 'year1948' <?php if ($year_of_birth == 'year1948') {echo 'selected';}?> >	1948 </option>
								<option value = 'year1949' <?php if ($year_of_birth == 'year1949') {echo 'selected';}?> >	1949 </option>
								<option value = 'year1950' <?php if ($year_of_birth == 'year1950') {echo 'selected';}?> >	1950 </option>
								<option value = 'year1951' <?php if ($year_of_birth == 'year1951') {echo 'selected';}?> >	1951 </option>
								<option value = 'year1952' <?php if ($year_of_birth == 'year1952') {echo 'selected';}?> >	1952 </option>
								<option value = 'year1953' <?php if ($year_of_birth == 'year1953') {echo 'selected';}?> >	1953 </option>
								<option value = 'year1954' <?php if ($year_of_birth == 'year1954') {echo 'selected';}?> >	1954 </option>
								<option value = 'year1955' <?php if ($year_of_birth == 'year1955') {echo 'selected';}?> >	1955 </option>
								<option value = 'year1956' <?php if ($year_of_birth == 'year1956') {echo 'selected';}?> >	1956 </option>
								<option value = 'year1957' <?php if ($year_of_birth == 'year1957') {echo 'selected';}?> >	1957 </option>
								<option value = 'year1958' <?php if ($year_of_birth == 'year1958') {echo 'selected';}?> >	1958 </option>
								<option value = 'year1959' <?php if ($year_of_birth == 'year1959') {echo 'selected';}?> >	1959 </option>
								<option value = 'year1960' <?php if ($year_of_birth == 'year1960') {echo 'selected';}?> >	1960 </option>
								<option value = 'year1961' <?php if ($year_of_birth == 'year1961') {echo 'selected';}?> >	1961 </option>
								<option value = 'year1962' <?php if ($year_of_birth == 'year1962') {echo 'selected';}?> >	1962 </option>
								<option value = 'year1963' <?php if ($year_of_birth == 'year1963') {echo 'selected';}?> >	1963 </option>
								<option value = 'year1964' <?php if ($year_of_birth == 'year1964') {echo 'selected';}?> >	1964 </option>
								<option value = 'year1965' <?php if ($year_of_birth == 'year1965') {echo 'selected';}?> >	1965 </option>
								<option value = 'year1966' <?php if ($year_of_birth == 'year1966') {echo 'selected';}?> >	1966 </option>
								<option value = 'year1967' <?php if ($year_of_birth == 'year1967') {echo 'selected';}?> >	1967 </option>
								<option value = 'year1968' <?php if ($year_of_birth == 'year1968') {echo 'selected';}?> >	1968 </option>
								<option value = 'year1969' <?php if ($year_of_birth == 'year1969') {echo 'selected';}?> >	1969 </option>
								<option value = 'year1970' <?php if ($year_of_birth == 'year1970') {echo 'selected';}?> >	1970 </option>
								<option value = 'year1971' <?php if ($year_of_birth == 'year1971') {echo 'selected';}?> >	1971 </option>
								<option value = 'year1972' <?php if ($year_of_birth == 'year1972') {echo 'selected';}?> >	1972 </option>
								<option value = 'year1973' <?php if ($year_of_birth == 'year1973') {echo 'selected';}?> >	1973 </option>
								<option value = 'year1974' <?php if ($year_of_birth == 'year1974') {echo 'selected';}?> >	1974 </option>
								<option value = 'year1975' <?php if ($year_of_birth == 'year1975') {echo 'selected';}?> >	1975 </option>
								<option value = 'year1976' <?php if ($year_of_birth == 'year1976') {echo 'selected';}?> >	1976 </option>
								<option value = 'year1977' <?php if ($year_of_birth == 'year1977') {echo 'selected';}?> >	1977 </option>
								<option value = 'year1978' <?php if ($year_of_birth == 'year1978') {echo 'selected';}?> >	1978 </option>
								<option value = 'year1979' <?php if ($year_of_birth == 'year1979') {echo 'selected';}?> >	1979 </option>
								<option value = 'year1980' <?php if ($year_of_birth == 'year1980') {echo 'selected';}?> >	1980 </option>
								<option value = 'year1981' <?php if ($year_of_birth == 'year1981') {echo 'selected';}?> >	1981 </option>
								<option value = 'year1982' <?php if ($year_of_birth == 'year1982') {echo 'selected';}?> >	1982 </option>
								<option value = 'year1983' <?php if ($year_of_birth == 'year1983') {echo 'selected';}?> >	1983 </option>
								<option value = 'year1984' <?php if ($year_of_birth == 'year1984') {echo 'selected';}?> >	1984 </option>
								<option value = 'year1985' <?php if ($year_of_birth == 'year1985') {echo 'selected';}?> >	1985 </option>
								<option value = 'year1986' <?php if ($year_of_birth == 'year1986') {echo 'selected';}?> >	1986 </option>
								<option value = 'year1987' <?php if ($year_of_birth == 'year1987') {echo 'selected';}?> >	1987 </option>
								<option value = 'year1988' <?php if ($year_of_birth == 'year1988') {echo 'selected';}?> >	1988 </option>
								<option value = 'year1989' <?php if ($year_of_birth == 'year1989') {echo 'selected';}?> >	1989 </option>
								<option value = 'year1990' <?php if ($year_of_birth == 'year1990') {echo 'selected';}?> >	1990 </option>
								<option value = 'year1991' <?php if ($year_of_birth == 'year1991') {echo 'selected';}?> >	1991 </option>
								<option value = 'year1992' <?php if ($year_of_birth == 'year1992') {echo 'selected';}?> >	1992 </option>
								<option value = 'year1993' <?php if ($year_of_birth == 'year1993') {echo 'selected';}?> >	1993 </option>
								<option value = 'year1994' <?php if ($year_of_birth == 'year1994') {echo 'selected';}?> >	1994 </option>
								<option value = 'year1995' <?php if ($year_of_birth == 'year1995') {echo 'selected';}?> >	1995 </option>
								<option value = 'year1996' <?php if ($year_of_birth == 'year1996') {echo 'selected';}?> >	1996 </option>
								<option value = 'year1997' <?php if ($year_of_birth == 'year1997') {echo 'selected';}?> >	1997 </option>
								<option value = 'year1998' <?php if ($year_of_birth == 'year1998') {echo 'selected';}?> >	1998 </option>
								<option value = 'year1999' <?php if ($year_of_birth == 'year1999') {echo 'selected';}?> >	1999 </option>
								<option value = 'year2000' <?php if ($year_of_birth == 'year2000') {echo 'selected';}?> >	2000 </option>
								<option value = 'year2001' <?php if ($year_of_birth == 'year2001') {echo 'selected';}?> >	2001 </option>
								<option value = 'year2002' <?php if ($year_of_birth == 'year2002') {echo 'selected';}?> >	2002 </option>
								<option value = 'year2003' <?php if ($year_of_birth == 'year2003') {echo 'selected';}?> >	2003 </option>
								<option value = 'year2004' <?php if ($year_of_birth == 'year2004') {echo 'selected';}?> >	2004 </option>
								<option value = 'year2005' <?php if ($year_of_birth == 'year2005') {echo 'selected';}?> >	2005 </option>
								<option value = 'year2006' <?php if ($year_of_birth == 'year2006') {echo 'selected';}?> >	2006 </option>
								<option value = 'year2007' <?php if ($year_of_birth == 'year2007') {echo 'selected';}?> >	2007 </option>
								<option value = 'year2008' <?php if ($year_of_birth == 'year2008') {echo 'selected';}?> >	2008 </option>
								<option value = 'year2009' <?php if ($year_of_birth == 'year2009') {echo 'selected';}?> >	2009 </option>
								<option value = 'year2010' <?php if ($year_of_birth == 'year2010') {echo 'selected';}?> >	2010 </option>
								<option value = 'year2011' <?php if ($year_of_birth == 'year2011') {echo 'selected';}?> >	2011 </option>
								<option value = 'year2012' <?php if ($year_of_birth == 'year2012') {echo 'selected';}?> >	2012 </option>
								<option value = 'year2013' <?php if ($year_of_birth == 'year2013') {echo 'selected';}?> >	2013 </option>
								<option value = 'year2014' <?php if ($year_of_birth == 'year2014') {echo 'selected';}?> >	2014 </option>
								<option value = 'year2015' <?php if ($year_of_birth == 'year2015') {echo 'selected';}?> >	2015 </option>
								<option value = 'year2016' <?php if ($year_of_birth == 'year2016') {echo 'selected';}?> > 	2016 </option>
							</select> </td>
						<td style = 'color:red'> <?php echo $date_of_birth_Error ?> </td>
					</tr>
					<tr> <td> </td> <td style = 'text-align:right'> <input type = 'submit' value = 'Loo kasutaja!'> </td> </tr>
				</table>
			</form>
	</body>
</html>