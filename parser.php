<?

$url = 'https://loftdesigne.ru/catalog/storage/styelazhi';
$curl = curl_init($url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); //Записать http ответ в переменную, а не выводить в буфер
$page = curl_exec($curl);

print_r($page);