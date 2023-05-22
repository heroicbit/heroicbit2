<?php defined('BASEPATH') OR exit('No direct script access allowed');

if(! function_exists('starts_with'))
{
	function starts_with($string, $substring)
	{
		if(strpos($string, $substring) === 0)
			return true;

		return false;
	}
}

if(! function_exists('generate_uuid'))
{
	function generate_uuid()
	{
		$factory = new \Ramsey\Uuid\UuidFactory();
		
		$generator = new \Ramsey\Uuid\Generator\CombGenerator(
			$factory->getRandomGenerator(), 
			$factory->getNumberConverter()
		);
		
		$codec = new Ramsey\Uuid\Codec\TimestampFirstCombCodec($factory->getUuidBuilder());
		
		$factory->setRandomGenerator($generator);
		$factory->setCodec($codec);
		
		Ramsey\Uuid\Uuid::setFactory($factory);
		return Ramsey\Uuid\Uuid::uuid4()->toString();
	}
}

if(! function_exists('uuid2bin'))
{
	function uuid2bin($uuidString)
	{
		if(!is_binary($uuidString))
			return pack("H*", str_replace('-', '', $uuidString));

		return $uuidString;
	}
}

// Convert to whatsapp compatible phone.
if(! function_exists('whatsapp'))
{
	function whatsapp($phone)
	{
		$code = substr($phone, 0, 1);

		if ($code == 0) {
			return substr_replace($phone, '62', 0, 1);
		}

		return $phone;
	}
}

if(! function_exists('bin2uuid'))
{
	function bin2uuid($binary)
	{
		if(!is_binary($binary))
			throw new \Exception('Data is not binary type.');

		$string = unpack("H*", $binary);

		$uuidArray = preg_replace(
			"/([0-9a-f]{8})([0-9a-f]{4})([0-9a-f]{4})([0-9a-f]{4})([0-9a-f]{12})/", 
			"$1-$2-$3-$4-$5", 
			$string
		);

		return array_pop($uuidArray);
	}
}

if (!function_exists('is_binary')) 
{
	function is_binary($str)
	{
		return preg_match('~[^\x20-\x7E\t\r\n]~', $str) > 0;
	}
}

function diff($old, $new)
{
	$matrix = array();
	$maxlen = 0;
	foreach ($old as $oindex => $ovalue) {
		$nkeys = array_keys($new, $ovalue);
		foreach ($nkeys as $nindex) {
			$matrix[$oindex][$nindex] = isset($matrix[$oindex - 1][$nindex - 1]) ?
				$matrix[$oindex - 1][$nindex - 1] + 1 : 1;
			if ($matrix[$oindex][$nindex] > $maxlen) {
				$maxlen = $matrix[$oindex][$nindex];
				$omax = $oindex + 1 - $maxlen;
				$nmax = $nindex + 1 - $maxlen;
			}
		}
	}
	if ($maxlen == 0) return array(array('d' => $old, 'i' => $new));
	return array_merge(
		diff(array_slice($old, 0, $omax), array_slice($new, 0, $nmax)),
		array_slice($new, $nmax, $maxlen),
		diff(array_slice($old, $omax + $maxlen), array_slice($new, $nmax + $maxlen))
	);
}

function htmlDiff($old, $new)
{
	$ret = '';
	$diff = diff(preg_split("/[\s]+/", $old), preg_split("/[\s]+/", $new));
	foreach ($diff as $k) {
		if (is_array($k))
			$ret .= (!empty($k['d']) ? "<del>" . implode(' ', $k['d']) . "</del> " : '') .
				(!empty($k['i']) ? "<ins>" . implode(' ', $k['i']) . "</ins> " : '');
		else $ret .= $k . ' ';
	}
	return $ret;
}

/** 
 * minimumRaggedness
 *
 * @param string $input paragraph. Each word separed by 1 space.
 * @param int $LineWidth the max chars per line.
 * @param string $lineBreak wrapped lines separator.
 * 
 * @return string $output the paragraph wrapped.
 */
function minimumRaggedness($input, $LineWidth, $lineBreak = "\n")
{
	$words = explode(" ", $input);
	$wsnum = count($words);
	$wslen = array_map("strlen", $words);
	$inf = 1000000; //PHP_INT_MAX;

	// keep Costs
	$C = array();

	for ($i = 0; $i < $wsnum; ++$i) {
		$C[] = array();
		for ($j = $i; $j < $wsnum; ++$j) {
			$l = 0;
			for ($k = $i; $k <= $j; ++$k)
				$l += $wslen[$k];
			$c = $LineWidth - ($j - $i) - $l;
			if ($c < 0)
				$c = $inf;
			else
				$c = $c * $c;
			$C[$i][$j] = $c;
		}
	}

	// apply recurrence
	$F = array();
	$W = array();
	for ($j = 0; $j < $wsnum; ++$j) {
		$F[$j] = $C[0][$j];
		$W[$j] = 0;
		if ($F[$j] == $inf) {
			for ($k = 0; $k < $j; ++$k) {
				$t = $F[$k] + $C[$k + 1][$j];
				if ($t < $F[$j]) {
					$F[$j] = $t;
					$W[$j] = $k + 1;
				}
			}
		}
	}

	// rebuild wrapped paragraph
	$output = "";
	if ($F[$wsnum - 1] < $inf) {
		$S = array();
		$j = $wsnum - 1;
		for (;;) {
			$S[] = $j;
			$S[] = $W[$j];
			if ($W[$j] == 0)
				break;
			$j = $W[$j] - 1;
		}

		$pS = count($S) - 1;
		do {
			$i = $S[$pS--];
			$j = $S[$pS--];
			for ($k = $i; $k < $j; $k++)
				$output .= $words[$k] . " ";
			$output .= $words[$k] . $lineBreak;
		} while ($j < $wsnum - 1);
	} else
		$output = $input;

	return $output;
}