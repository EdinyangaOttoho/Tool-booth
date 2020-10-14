<?php
	class ToolBooth {
		function insertSQL($db, $table, $method, $exclusions=[]) {
			if (strtolower($method) == "post") {
				$str = 'INSERT INTO '.$table." (";
				$array_keys = array();
				$array_values = array();
				foreach ($_POST as $k=>$v) {
					if (!in_array($k, $exclusions)) {
						$key = htmlspecialchars(mysqli_real_escape_string($db, $k));
						$value = htmlspecialchars(mysqli_real_escape_string($db, $v));
						array_push($array_keys, $key);
						array_push($array_values, "'".$value."'");	
					}					
				}
				$str.= implode(",", $array_keys).') VALUES ('.implode(",", $array_values).')';
				return $str;
			}
			else {
				$str = 'INSERT INTO '.$table." (";
				$array_keys = array();
				$array_values = array();
				foreach ($_GET as $k=>$v) {
					if (!in_array($k, $exclusions)) {
						$key = htmlspecialchars(mysqli_real_escape_string($db, $k));
						$value = htmlspecialchars(mysqli_real_escape_string($db, $v));
						array_push($array_keys, $key);
						array_push($array_values, "'".$value."'");
					}					
				}
				$str.= implode(",", $array_keys).') VALUES ('.implode(",", $array_values).')';
				return $str;
			}
		}
		function updateSQL($db, $table, $method, $exclusions=[], $clause = "0") {
			if (strtolower($method) == "post") {
				$str = 'UPDATE '.$table." SET ";
				$variables = array();
				foreach ($_POST as $k=>$v) {
					if (!in_array($k, $exclusions)) {
						$key = htmlspecialchars(mysqli_real_escape_string($db, $k));
						$value = htmlspecialchars(mysqli_real_escape_string($db, $v));
						array_push($variables, $key." = '".$value."'");
					}
				}
				if ($clause == "0") {
					$str .= implode(",", $variables)." WHERE 0";
				}				
				else {
					$str .= implode(",", $variables)." ".$clause;
				}
				return $str;
			}
			else {				
				$str = 'UPDATE '.$table." SET ";
				$variables = array();
				foreach ($_POST as $k=>$v) {
					if (!in_array($k, $exclusions)) {
						$key = htmlspecialchars(mysqli_real_escape_string($db, $k));
						$value = htmlspecialchars(mysqli_real_escape_string($db, $v));
						array_push($variables, $key." = '".$value."'");
					}
				}
				if ($clause == "0") {
					$str .= implode(",", $variables)." WHERE 0";
				}				
				else {
					$str .= implode(",", $variables)." ".$clause;
				}
				return $str;	
			}
		}
		function validator($values, $method, $exclusions=[], $empty=0) {
			if (strtolower($method) == "post") {
				$errors = [];
				if ($empty == 0) {
					$cnt_empty = 0;
					foreach ($values as $k=>$v) {
						if (!in_array($k, $exclusions)) {
							if (str_replace(" ", "", $_POST[$k]) == "") {
								$cnt_empty++;
							}
							if ($cnt_empty >= 1) {
								if (!in_array("empty", $errors)) {
									array_push($errors, "empty");
								}
							}
							if (strtolower($v) == "phone") {
								if (!preg_match("/^[0-9\-\(\)\/\+\s]*$/", $_POST[$k]) || strlen($_POST[$k] > 14) || strlen($_POST[$k] < 9)) {
									if (!in_array("phone", $errors)) {
										array_push($errors, "phone");
									}
								}
							}
							if (strtolower($v) == "email") {
								if (!filter_var($_POST[$k], FILTER_VALIDATE_EMAIL)) {
									if (!in_array("email", $errors)) {
										array_push($errors, "email");
									}
								}
							}
						}
					}
					return json_encode($errors);
				}
				else {
					$cnt_empty = 0;
					foreach ($values as $k=>$v) {
						if (!in_array($k, $exclusions)) {
							if (strtolower($v) == "phone") {
								if (!preg_match("/^[0-9\-\(\)\/\+\s]*$/", $_POST[$k]) || strlen($_POST[$k] > 14) || strlen($_POST[$k] < 9)) {
									if (!in_array("phone", $errors)) {
										array_push($errors, "phone");
									}
								}
							}
							if (strtolower($v) == "email") {
								if (!filter_var($_POST[$k], FILTER_VALIDATE_EMAIL)) {
									if (!in_array("email", $errors)) {
										array_push($errors, "email");
									}
								}
							}
						}
					}
					return json_encode($errors);	
				}
			}
			else {
				if ($empty == 0) {
					$cnt_empty = 0;
					foreach ($values as $k=>$v) {
						if (!in_array($k, $exclusions)) {
							if (str_replace(" ", "", $_GET[$k]) == "") {
								$cnt_empty++;
							}
							if ($cnt_empty >= 1) {
								if (!in_array("empty", $errors)) {
									array_push($errors, "empty");
								}
							}
							if (strtolower($v) == "phone") {
								if (!preg_match("/^[0-9\-\(\)\/\+\s]*$/", $_GET[$k]) || strlen($_GET[$k] > 14) || strlen($_GET[$k] < 9)) {
									if (!in_array("phone", $errors)) {
										array_push($errors, "phone");
									}
								}
							}
							if (strtolower($v) == "email") {
								if (!filter_var($_GET[$k], FILTER_VALIDATE_EMAIL)) {
									if (!in_array("email", $errors)) {
										array_push($errors, "email");
									}
								}
							}
						}
					}
					return json_encode($errors);
				}
				else {
					$cnt_empty = 0;
					foreach ($values as $k=>$v) {
						if (!in_array($k, $exclusions)) {
							if (strtolower($v) == "phone") {
								if (!preg_match("/^[0-9\-\(\)\/\+\s]*$/", $_GET[$k]) || strlen($_GET[$k] > 14) || strlen($_GET[$k] < 9)) {
									if (!in_array("phone", $errors)) {
										array_push($errors, "phone");
									}
								}
							}
							if (strtolower($v) == "email") {
								if (!filter_var($_GET[$k], FILTER_VALIDATE_EMAIL)) {
									if (!in_array("email", $errors)) {
										array_push($errors, "email");
									}
								}
							}
						}
					}
					return json_encode($errors);	
				}
			}
		}
		function sanitizeVal($db, $value) {
			return trim(htmlspecialchars(mysqli_real_escape_string($db, $string)));
		}
	}
?>