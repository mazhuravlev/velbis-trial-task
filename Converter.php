<?php

class Converter
{
	private $calls = [];

	public function __constructor()
	{	
		
	}

	public function normalize($input)
	{
		if ($csv = fopen($input, 'r') === false) {
        	throw new Exception('Неудалось открыть файл'."\n");
        }
        
        $csv = fopen($input, 'r');
        $rowCount = 0;
		while ($row = fgetcsv($csv)){
			$rowCount ++;
			$validData = $this->validate($row, $rowCount);
			if (!empty($validData[0])) {
				$phone = '+7'.$validData[0];
				$date = date('H:i:s d.m.Y', $validData[1]);
				$this->calls[$phone] = $date;
			} else {
				throw new Exception('Неудалось прочитать данные'."\n");
			}
		}
		return $this;
	}

	public function sortCallsByDate($order) 
	{
		$cmp = function($a, $b) use ($order) {
			
			if ($a == $b) { 
				return 0; 
			}

			if ($order === 'ASC') { 
				if ($a < $b) {
					return -1;
				} else {
					return 1;
				}
			}

			if ($order === 'DSC') { 
				if ($a > $b) {
					return -1;
				} else {
					return 1;
				}
			}
		};
		
		if (uasort($this->calls, $cmp)) {
			return $this->calls;
		} else {
			throw new Exception('Ошибка чтения данных'."\n");
		}
	}
	
	private function validate($data, $row)
	{
		if (strlen((string)$data[0]) == 10 && $this->isValidTimeStamp($data[1])) {
			return $data;
		} else { 
			fwrite(STDERR, 'Ошибка в данных в строке: '.$row."\n");
		}
	}
	
	private function isValidTimeStamp($strTimestamp)
	{
	    return ((string) (int) 
	    	$strTimestamp === $strTimestamp) 
	        && ($strTimestamp <= PHP_INT_MAX)
	        && ($strTimestamp >= ~PHP_INT_MAX);
	}
	
	
}