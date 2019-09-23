<?php
Class Lottery {

	protected $format = 'Y-m-d';
	protected $lottoDays = ['Tuesday','Sunday'];
	protected $drawTime = '09:30 pm';

	public function getNextLottoDate($date='', $format='') {

		// The Canadian National Lottery draw takes place twice per week,
		// on Tuesday and Sunday at 9.30 pm.
		
		if($format === '') {
			$format = $this->format;
		}

		if($date && $date !== '') {

			if( $this->validateDateTime($date, $format) ) {

				return $this->getDate($date, $format);
			} 
			else {
				return "Invalid Date, Correct format is: ". date($format, time());
			}
		}

		return $this->getNextWeekDay($this->lottoDays);

	}

	public function getNextWeekDay($nextDay='monday') {

		if( is_array($nextDay) ) {

			foreach ($nextDay as $d) {

				$nextLotto[] = date("D, {$this->format}", strtotime("next {$d}"));
			}

			return $nextLotto;
		}

		return date("D, d-M-Y", strtotime("next {$nextDay}"));
	}

	private function getDate($date, $format='') {

		$d = 0;
		if($format === '') {
			$format = $this->format;
		}

		$dateTime = new DateTime($date);

		for ($i=0; $i < 7; $i++) {

			$dateTime->modify("+{$d} day");
			$newDate = $dateTime->format( $format );
			$day = date("l", strtotime($newDate));

			if( in_array($day, $this->lottoDays) ) {

				return [$day => $newDate];
			}

			$d = 1;
		}
	}

	public function getDate2($date, $format='') {

		// procedural php
		
		$d = 0;
		if($format === '') {
			$format = $this->format;
		}

		$date = date_create($date);

		for ($i=0; $i < 7; $i++) {

			$date = date_modify($date, "+{$d} day");
			$newDate = date_format($date, $format);
			$day = date("l", strtotime($newDate));
		
			if( $day === 'Tuesday' || $day === 'Sunday' ) {

				return [$day => $newDate];
			}

			$d = 1;
		}
	}

	public function validateDateTime($date, $format='') {

		if($format === '') {
			$format = $this->format;
		}

		$d = DateTime::createFromFormat($format, $date);

	    return $d && $d->format($format) === $date;
	}

	public function checkIsAValidDate($dateStr) {
    	
    	return (bool)strtotime($dateStr);
	}

}

// default date format is: yyyy-mm-dd
// Pass date as parameter to get the next lotto date.

$date = '2019-04-30';

$lotto = new Lottery();

$nextLottoDay = $lotto->getNextLottoDate();
print_r($nextLottoDay);
