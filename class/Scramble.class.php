<?php
class Scramble {

	function __construct() {
		$this->scramble = array();
		$this->scramble_up = array('U', "U'", 'U2');
		$this->scramble_down = array('D', "D'", 'D2');
		$this->scramble_left = array('L', "L'", 'L2');
		$this->scramble_right = array('R', "R'", 'R2');
		$this->scramble_front = array('F', "F'", 'F2');
		$this->scramble_back = array('B', "B'", 'B2');
		$this->scramble_options = array($this->scramble_up, $this->scramble_down, $this->scramble_left, $this->scramble_right, $this->scramble_front, $this->scramble_back);
	}

	function scramble($length) {
		//number, mis ei ole 0 ja 5 vahel.
		$previous_face = 10;
		$scramble_string = '';
		$scramble = $this->scramble;
		$scramble_options = $this->scramble_options;
		while (count($scramble) < $length) {
			$scramble_face = rand(0, 5);
			if ($previous_face != $scramble_face) {
				$previous_face = $scramble_face;
				$scramble_move = rand(0, 2);
				$scramble[count($scramble)] = $scramble_options[$scramble_face][$scramble_move];
				$scramble_string .= $scramble_options[$scramble_face][$scramble_move].' ';
			}
		}
		return $scramble_string;
	}

	function save($length) {
		return $this->scramble($length);
	}

}
?>