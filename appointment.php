<?php
require_once __DIR__ . '/vendor/autoload.php';
include 'db.php';
include 'config.php';
if (isset($_POST['doctor']) && isset($_POST['date']) && isset($_POST['time'])) {
    preg_match('/^\d{4}-\d{2}-\d{2}$/', $_POST['date'], $matches);
    $dateNow = date('Y-m-d H:i:s');
    $date = $_POST['date'].' '.$_POST['time'];
    if ($matches && $dateNow<$date) {
        $db = new db($dbhost, $dbuser, $dbpass, $dbname);
        $doctor_id = $_POST['doctor'];
        
        $select = $db->query('SELECT * FROM appointments WHERE doctor_id = ? AND date = ?', $doctor_id, $date)->fetchArray();
        if (empty($select)) {
            $insert = $db->query('INSERT INTO appointments (doctor_id, date) VALUES (?,?)', $doctor_id, $date);
            if ($insert->affectedRows()) {
                $result = 'Вы записаны';                

                // Create the Transport
                $transport = (new Swift_SmtpTransport($emailServer, $emailPort, 'ssl'))
                ->setUsername($emailFrom)
                ->setPassword($emailPassword);

                // Create the Mailer using your created Transport
                $mailer = new Swift_Mailer($transport);

                // Create a message
                $message = (new Swift_Message('Запись на приём'))
                ->setFrom([$emailFrom => 'appointment'])
                ->setTo($emailTo)
                ->setBody('Дата приёма: '.$date);
                // Send the message
                $mailer->send($message);
            }
            else
                $result = 'Произошла ошибка';
        }
        else
            $result = 'Время занято';
        $db->close();
    }
    else
        $result = 'Введите допустимую дату';
}
else
    $result = 'Заполните все поля';
echo json_encode($result);