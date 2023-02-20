<?php

require_once "db.config.php";
require_once 'env.class.php';
$__DotEnvironment = new DotEnvironment(realpath("./.env"));

//reset the timezone default
date_default_timezone_set('Africa/Lagos');

session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

class User
{

  private $mail;
  protected $db;

  public function __construct()
  {
    $this->db = new Database();
    $this->db = $this->db->connect();
    $this->mail = new PHPMailer(true);
    $this->mail->isSMTP();
    $this->mail->Host = 'lunikdata.com'; 
    $this->mail->SMTPAuth = true;
    $this->mail->Username = $_ENV['USERNAME'];
    $this->mail->Password = $_ENV['PASSWORD'];
    $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $this->mail->Port = $_ENV['PORT'];
  }

  public function uploadEmail($emails, $from_email, $name, $message, $subject, $code) {
    foreach ($emails as $email) {
      $sql = "INSERT INTO emails (email_address, from_email, name, message, subject, unique_ids, date)" . "VALUES (?,?,?,?,?,?,?)";
      $stmt = $this->db->prepare($sql);
      $stmt->execute([$email, $from_email, $name, $message, $subject, $code, date('Y-m-d H:i:s')]);
  
      return true;
    }
  }

  public function sendEmail($code) {
    $rows = $this->getEmails($code)->fetch(PDO::FETCH_ASSOC);
    $emails = explode(",", $rows['email_address']);
    // print_r($emails); exit;

    // Loop through the emails and send the email to each recipient
    foreach ($emails as $emailAddress) {
      try {
        $this->mail->setFrom($rows['from_email'], $rows['name']);
        $this->mail->addAddress($emailAddress);
        $this->mail->isHTML(true);
        $this->mail->Subject = $rows['subject'];
        $this->mail->Body = $rows['message'];

        $this->mail->send();
      } catch (Exception $e) {
        // Log the error or handle it in some other way
        return false;
      }
    }

    return true;
  }

  public function getEmails($code)
  {
    $sql = "SELECT * from emails WHERE unique_ids = ?";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([$code]);

    $count_row = $stmt->rowCount();

    if ($count_row == 1) {
      return $stmt;
    }
  }

}