<?php
// Define y genera los arreglos necesarios para la creación de combinaciones posibles.
$lower_case = range('a','z');
$upper_case = range('A','Z');
$numbers = range(0,9,1);
$spec_symbols = array('.','@');
// Merge los arreglos en uno solo para procesar las combinaciones.
$chars = array_merge($lower_case,$upper_case,$numbers,$spec_symbols);
// Crea las combinaciones.
$md5_sample_table = sampling($chars, 2);

$str1 = "helloworld@company.com"; // correo ejemplo
$two_chars = str_split($str1,2); // Particiona en arreglo de dos caracteres
$complete_hash = calc_md5($two_chars,true); // Crea el hash del correo

// Hash generado por los algoritmos para el correo ejemplo
$hash = '1ee7b2c95024bf2a3a5b645676875f72af97c725eca5ea2e7ae4a6b18dd9f608aaf180b66f6282f65168c30c1c5e54660aaa8fb0c5b4fa52c0d06ed55d02fcc95e35b5a6a854db008cb8a677dfff030b35306f43e19f6826a5df7937e71d228cb2917b47913c83618903a4e56482283634e4cf70388e9477677aa3013494ba1c3d116007775d60a0d5781d2e35d747b5dece2e0e3d79d272e40c8c66555f5525';
// Esta es la cadena que necesitamos crackear.
$str_to_crack = '1ee7b2c95024bf2a3a5b645676875f72af97c725eca5ea2e7ae4a6b18dd9f608aaf180b66f6282f65168c30c1c5e54660aaa8fb0c5b4fa52c0d06ed55d02fcc95e35b5a6a854db008cb8a677dfff030b35306f43e19f6826a5df7937e71d228cb2917b47913c83618903a4e56482283634e4cf70388e9477677aa3013494ba1c3d116007775d60a0d5781d2e35d747b5dece2e0e3d79d272e40c8c66555f5525
';
// Segmenta el hash creado en 32 bytes. Aqui se cambia $str_to_crack a $hash para el ejemplo de internet o vice versa.
$hash_segments = str_split($hash,32);
echo "HASH SEGMENTS: <br>";
echo var_dump($hash_segments);

// Genera el hash para la tabla de diccionario del sampleo de 2 caracteres que hicimos
$hash_generated_for_dictionary = calc_md5($md5_sample_table,false);
// Busca los hashes segmentados del correo en el arreglo del diccionario y despliega el resultado.
$result = search_rainbow_table($hash_segments, $hash_generated_for_dictionary);
echo "HACKED KEY: <br>";
echo '<pre>' . var_export($result, true) . '</pre>';

//Generar los hashes MD5 de las posibles combinaciones que se generaron.
// PARÁMETROS	: $array_from_which_we_create_md5 - arreglo que contiene las combinaciones y usaremos para crear los MD5 que almacenaremos
//				  en la tabla de RainBow.
function calc_md5($array_from_which_we_create_md5,$calchash)
{
	$the_hash = '';
	$tot_hash = '';
	$index = 0;
	$rainbow_table = array(
    	'key' => '',
    	'hash' => ''
	);

	foreach($array_from_which_we_create_md5 as $value)
	{
		if(!$calchash){
			$tot_hash = md5(md5($value) . $value . md5($value));
			$rainbow_table[$index]['hash'] = $tot_hash;
			$rainbow_table[$index]['key'] = $value;
			$index++;
		}
		else{
			$tot_hash = $tot_hash . md5(md5($value) . $value . md5($value));
		}
	}
	if (!$calchash){
		return $rainbow_table;
	}
	else{
		return $tot_hash;
	}
	
}

// Busca en el diccionario de datos la clave hash.
// PARÁMETROS	: $hash_array - arreglo que contiene los hashes segmentados de la cadena a descifrar.
//				  $rainbow_table - El arreglo que contiene nuestro diccionario de datos.
function search_rainbow_table($hash_array,$rainbow_table)
{
	$the_key = '';
	$the_hash = '';
    foreach ($hash_array as $data)
    {
		$index = 0;
		foreach($rainbow_table as $val)
		{
			if($index < 4096)
			{
				if ($rainbow_table[$index]['hash'] === $data) 
				{
           			$the_hash = $the_hash . $rainbow_table[$index]['key'];
           		}
           		$index++;
           	}
			
		}
    }
    return $the_hash;
}

// Genera combinaciones de 2 caracteres (de acuerdo al ejercicio) recursivamente.

function sampling($chars_used, $actual_size, $combinations_array = array()) 
{

    if (empty($combinations_array)) {
        $combinations_array = $chars_used;
    }

    if ($actual_size == 1) {
        return $combinations_array;
    }

    $new_combinations_array = array();

    foreach ($combinations_array as $combination) {
        foreach ($chars_used as $char) {
            $new_combinations_array[] = $combination . $char;
        }
    }

    return sampling($chars_used, $actual_size - 1, $new_combinations_array);
}
?>
