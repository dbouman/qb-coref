<?php
/**
 * QBCoref package
 * 
 * @package QBCoref
 * @author Danny Bouman <dannybb@gmail.com>
 */

require dirname(__FILE__) . '/config.php';
require dirname(__FILE__) . '/adodb5/adodb.inc.php';
require dirname(__FILE__) . '/legacy/password.php';
require dirname(__FILE__) . '/phpmailer/PHPMailerAutoload.php';

class QBCoref
{
	/**
     * Database object
     */
	public $db;
	
	/**
	 * Session id
	 */
	private $sid;
	
	/*
	 * Tracks first login
	 */
	public $first_login = false;
	
	/**
	 * Constructor
	 */
	public function __construct()
	{
		global $config;
		
		$this->sid = session_id();
		if (empty($this->sid)) {
			session_start();
		} 
		
		$this->db = NewADOConnection($config['dbtype']);
		$this->db->Connect($config['dbhost'], $config['dbuser'], $config['dbpass'], $config['dbname']);
	}
	
	public function isUser() {
		return (!empty($_SESSION['username']));
	}
	
	public function isAdmin() {
		if (!empty($_SESSION['username'])) {
			$result = $this->db->GetOne("SELECT is_admin FROM users WHERE username = '".$_SESSION['username']."'");
			if ($result == 1) {
				return true;
			}
		}
		
		return false;
	}
	
	
	public function getUserID($username = "") {
		if (empty($username)){
			$username = $_SESSION['username'];
		}
		$uid = $this->db->GetOne("SELECT uid FROM users WHERE username = '".$username."'");
		
		return $uid;
	}
	
	public function login($username,$password) {
		$userpass = $this->db->GetOne("SELECT password FROM users WHERE username = '".$username."'");
		if (!empty($userpass) && password_verify($password,$userpass)) {
			$ip = 	getenv('HTTP_CLIENT_IP')?:
					getenv('HTTP_X_FORWARDED_FOR')?:
					getenv('HTTP_X_FORWARDED')?:
					getenv('HTTP_FORWARDED_FOR')?:
					getenv('HTTP_FORWARDED')?:
					getenv('REMOTE_ADDR');
			$this->db->Execute("UPDATE users SET `last_login` = CURRENT_TIMESTAMP, `last_ip` = '".$ip."'
								WHERE username = '".$username."'");
			$_SESSION['username'] = $username;
			return true;
		}
	
		return false;
	}
	
	public function updatePassword($password) {
		$this->db->Execute("UPDATE users SET `password` = '".password_hash($password,PASSWORD_DEFAULT)."'
				WHERE username = '".$_SESSION['username']."'");
	}
	
	public function verifyPassword($password) {
		$userpass = $this->db->GetOne("SELECT password FROM users WHERE username = '".$_SESSION['username']."'");
		if (!empty($userpass) && password_verify($password,$userpass)) {
			return true;
		}
		
		return false;
	}
	
	public function logout() {
		if (!empty($_SESSION['username'])) {
			unset($_SESSION['username']);
		}
	}
	
	public function register($username, $password, $firstname, $lastname, $email) {
		$this->db->Execute("INSERT INTO users
				(`username`,`password`,`firstname`,`lastname`,`email`)
				VALUES
				('$username','".password_hash($password,PASSWORD_DEFAULT)."','$firstname','$lastname','$email')
				");
	}
	
	public function isUsernameUnique($username) {
		$count = $this->db->GetOne("SELECT count(uid) FROM users WHERE username = '".$username."'");
		
		if ($count > 0) {
			return false;
		}
		
		return true;
	}
	
	public function getLeaderboard($limit) {
		global $config;
		
		$results = $this->db->SelectLimit("SELECT author, count(cid) FROM coreferences
											WHERE author != '" . $config['gold_user'] . "'
											GROUP by author
											ORDER by count(cid) DESC",$limit);
		
		return $results;
	}
	
	public function getPoints() {
		$count = $this->db->GetOne("SELECT count(cid) FROM coreferences WHERE author = '".$_SESSION['username']."'");
		
		return $count;
	}
	
	public function saveCoref($qid, $author, $pos_start, $pos_end, $description, $coref_group) {
		$translated_group = $this->translateCorefGroup($coref_group);
		$this->db->Execute("INSERT INTO coreferences 
							(`qid`,`pos_start`,`pos_end`,`description`,`coref_group`,`author`) 
							VALUES 
							('$qid',$pos_start,$pos_end,'$description',$translated_group,'$author')
							");
	}
	
	// delete all coreferences for specific question and user
	public function deleteCoreferences($qid, $username) {
		$this->db->Execute("DELETE FROM coreferences WHERE qid = $qid and author = '".$username."'");
	}
	
	
	// Used by export function
	public function getAllCorefs() {
		global $config;
		
		$this->db->SetFetchMode(ADODB_FETCH_ASSOC);
		$results = $this->db->Execute("SELECT * FROM coreferences WHERE author != '".$config['gold_user']."'");
		
		return $results;
	}
	
	public function getCorefsAsJSON($qid,$author) {
		$json_output = "";
		if (is_numeric($qid)) {
			$results = $this->db->Execute("SELECT * FROM coreferences WHERE qid = '".$qid."' and author = '".$author."' ORDER BY cid");
			
			if ($results->RowCount() > 0) {
				$i = 0;
				foreach ($results as $coref) {
					$tags[$i]['id'] = $i;
					$tags[$i]['annotation'] = $coref['description'];
					$tags[$i]['pos'] = array('start' => $coref['pos_start'], 'end' => $coref['pos_end']);
					$translated_group = $this->translateCorefGroup($coref['coref_group']);
					$tags[$i]['type'] = $translated_group;
					$i++;
				}
				
				$json_output = json_encode($tags);
			}
		}
	
		return $json_output;
	}
	
	public function translateCorefGroup($group) {
		$translations = array();
		$translations['q'] = '10';
		$translations['w'] = '11';
		$translations['e'] = '12';
		$translations['r'] = '13';
		$translations['t'] = '14';
		$translations['y'] = '15';
		$translations['u'] = '16';
		$translations['i'] = '17';
		$translations['o'] = '18';
		$result = $group;
		if (array_key_exists($group,$translations)) {
			$result = $translations[$group];
			
		}
		else {
			$inverse = array_flip($translations);
			if (array_key_exists($group,$inverse)) {
				$result = $inverse[$group];
			}
		}
		
		return $result;
	}
	
	public function getAccuracy($qid, $description, $pos_start, $pos_end) {
		global $config;
		
		$result = $this->db->GetOne("SELECT count(cid) FROM coreferences 
									WHERE qid = '". $qid . "' 
										AND author = '" . $config['gold_user'] . "' 
										AND pos_start = '" . $pos_start . "'
										AND pos_end = '" . $pos_end . "'");
		
		if ($result) {
			return $result;
		}
		
		return false;
	}
	
	/*
	 * Check if gold standard user has done this question, will display option to check accuracy
	 */
	public function isQuestionGoldStandard($qid) {
		global $config;
		
		$result = $this->db->GetOne("SELECT count(cid) FROM coreferences
				WHERE qid = '". $qid . "'
				AND author = '" . $config['gold_user'] . "' ");
		
		if ($result) {
			return true;
		}
		
		return false;
	}
	
	public function getCountGoldStandardCorefs($qid) {
		global $config;
	
		$result = $this->db->GetOne("SELECT count(cid) FROM coreferences
				WHERE qid = '". $qid . "'
				AND author = '" . $config['gold_user'] . "' ");
	
		if ($result) {
			return $result;
		}
	
		return 0;
	}

    public function getQuestionStatistics($qid) {
        $results = $this->db->Execute("SELECT author, count(cid) as num_annotations
            FROM coreferences
            WHERE qid = '".$qid."'
            GROUP BY author
            ORDER BY author");

        $output = '<table class="table table-striped">';
        $output .= '<thead><th>Username</th><th># of Annotations</th></thead>';

        if ($results->RowCount() > 0) {
            foreach($results as $row) {
                $output .= '<tr>';
                $output .= '<td>' . $row['author'] . '</td>';
                $output .= '<td>' . $row['num_annotations'] . '</td>';
                $output .= '</tr>';
            }
        }
        else {
            $output .= '<tr><td colspan="2">No users have annotated this question.</td></tr>';
        }

        $output .= '</table>';

        return $output;
    }
	
	public function getQuestion($qid) {
		$question = "";
		if (is_numeric($qid)) {
			$question = $this->db->GetOne("SELECT `question` FROM questions WHERE qid = '".$qid."'");
		}
		
		return $question;
	}
	
	public function getAnswer($qid) {
		$answer = "";
		if (is_numeric($qid)) {
			$answer = $this->db->GetOne("SELECT `answer` FROM answers WHERE qid = '".$qid."'");
		}
	
		return $answer;
	}

    public function getOrderedQuestion() {
        $uid = $this->getUserID();
        $old_questions = $this->db->GetCol("SELECT `qid` FROM user_history WHERE uid = '$uid'");

        $query = "SELECT qid FROM `question_order` ";
        $query .= "WHERE qid NOT IN (". implode(',',$old_questions) .") ";
        $query .= "ORDER BY weight";
        $qid = $this->db->GetOne($query);
        if (empty($qid)) {
            // If this is empty it means every question has been completed
            return PHP_INT_MAX;
        }

        return $qid;
    }
	
	public function getRandomQuestion() {
		$uid = $this->getUserID();
		$old_questions = $this->db->GetCol("SELECT `qid` FROM user_history WHERE uid = '$uid'");
		$condition = "";
		$condition_and = "";
		if (!empty($old_questions)) {
			$condition = " WHERE qid NOT IN (". implode(',',$old_questions) .") ";
			$condition_and = " and qid NOT IN (". implode(',',$old_questions) .") "; 
		}
		$possible_questions = $this->db->GetCol("SELECT qid
											FROM (
												SELECT qid, author FROM `coreferences`
												GROUP BY author, qid
											) s
											".$condition."
											GROUP BY qid
											HAVING count(author) > 0 and count(author) <= 4");
		if (empty($possible_questions)) {
			$excluded_questions = $this->db->GetCol("SELECT qid
											FROM (
												SELECT qid, author FROM `coreferences`
												GROUP BY author, qid
											) s
											".$condition."
											GROUP BY qid
											HAVING count(author) > 4");
			$possible_questions = $this->db->GetCol("SELECT qid FROM `questions`
													WHERE qid NOT IN (". implode(',',$excluded_questions) .")
													".$condition_and);
			if (empty($possible_questions)) {
				// If this is still empty, start going through questions with more than 4 authors 
				$possible_questions = $this->db->GetCol("SELECT qid FROM `questions`".$condition);
				if (empty($possible_questions)) {
					// If this is STILL empty, they did every question
					return PHP_INT_MAX;
				}
			}
		}
		$rand_key = array_rand($possible_questions);
		return $possible_questions[$rand_key];
	}
	
	public function getNextQuestion($qid) {
		$uid = $this->getUserID();
		if (is_numeric($qid)) {
			$pos = $this->db->GetOne("SELECT `position` FROM user_history WHERE uid = '$uid' and qid = '$qid'");
			$next_qid = $this->db->GetOne("SELECT `qid` FROM user_history WHERE uid = '$uid' and `position` = '". ($pos+1)."'");
			if (empty($next_qid)) {
				// Get next question
				$next_qid = $this->getOrderedQuestion();
				$this->updateHistory($next_qid, ($pos+1));
			}
		}
		
		return $next_qid;
	}
	
	public function getPrevQuestion($qid) {
		$uid = $this->getUserID();
		if (is_numeric($qid)) {
			$pos = $this->db->GetOne("SELECT `position` FROM user_history WHERE uid = '$uid' and qid = '$qid'");
			if(empty($pos) || $pos == 1) {
				// Return max int if no previous exists
				return PHP_INT_MAX; 
			}
			else {
				$pos--;
				$prev_qid = $this->db->GetOne("SELECT `qid` FROM user_history WHERE uid = '$uid' and `position` = '". ($pos)."'");
			}
		}
		
		return $prev_qid;
	}
	
	public function updateHistory($qid,$position) {
		$uid = $this->getUserID();
		if (is_numeric($qid)) {
			$this->db->Execute("INSERT INTO user_history (`uid`,`qid`,`position`) VALUES (".$uid.",".$qid.",".$position.")");
		}
	}
	
	public function getLastQuestion() {
		$last_qid = $this->db->GetOne("SELECT `last_qid` FROM users WHERE username = '".$_SESSION['username']."'");
		if (!empty($last_qid)) {
			$qid = $last_qid;
		}
		else {
			$qid = $this->getOrderedQuestion();
			$this->updateHistory($qid, 1);
			$this->first_login = true;
		}
	
		return $qid;
	}
	
	public function updateLastQuestion($qid) {
		if (is_numeric($qid)) {
			$this->db->Execute("UPDATE users SET last_qid = '".$qid."' WHERE username = '".$_SESSION['username']."'");
		}
	}
	
	public function getTotalQuestions() {
		return $this->db->GetOne("SELECT count(*) FROM questions");
	}
	
	public function loadQuestions() {
		$counter = 0;
		
		$handle = fopen("../data/questions.txt", "r");
		if ($handle) {
			while (($line = fgets($handle)) !== false) {
				$question = $this->db->qstr($line);
				$this->db->Execute("INSERT INTO questions (`question`) VALUES (".$question.")");
				$counter++;
			}
		}
		
		return $counter;
	}
	
	public function loadAnswers() {
		$counter = 0;
		$handle = fopen("../data/answers.txt", "r");
		if ($handle) {
			$qid = 14553;
			while (($line = fgets($handle)) !== false) {
				$answer = $this->db->qstr($line);
				$this->db->Execute("INSERT INTO answers (`qid`,`answer`) VALUES (".$qid.",".$answer.")");
				$qid++;
				$counter++;
			}
		}
		
		return $counter;
	}
	
	public function sendEmail($fromName, $fromEmail, $message) {
		global $config;
		
		$mail = new PHPMailer;
		
		$mail->isSMTP();
		$mail->Host = $config['smtp_host'];
		$mail->Port = $config['smtp_port'];
		$mail->SMTPAuth = true;
		$mail->Username = $config['smtp_user'];
		$mail->Password = $config['smtp_pass']; 
		$mail->SMTPSecure = $config['smtp_secure'];
		
		$mail->From = $fromEmail;
		$mail->FromName = $fromName;
		$mail->addAddress($config['contact_email']);
		
		$mail->isHTML(false);
		
		$mail->Subject = 'QB-Coref Contact Us Submission';
		$mail->Body    = "From Name: " . $fromName. "\n" .
						 "From Email: " . $fromEmail. "\n\n" .
						  $message;
		
		if(!$mail->send()) {
			// Debug messages
			//echo 'Message could not be sent.';
			//echo 'Mailer Error: ' . $mail->ErrorInfo;
			return false;
		}
		
		return true;
	}	
	
	public function isUsingOldIEBrowser() {
		preg_match('/MSIE (.*?);/', $_SERVER['HTTP_USER_AGENT'], $matches);
		
		if (count($matches)>1){
			//Then we're using IE
			$version = $matches[1];
		
			if ($version<=9)
				return true;
		}
		
		return false;
	}
	
}
