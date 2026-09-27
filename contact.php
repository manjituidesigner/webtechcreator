<?php
	$autoloadPath = __DIR__ . '/vendor/autoload.php';
	if (file_exists($autoloadPath)) {
		require_once $autoloadPath;
	}

	if (isset($_POST["submit"])) {
		$name = isset($_POST['name']) ? trim($_POST['name']) : '';
		$mobile = isset($_POST['mobile']) ? trim($_POST['mobile']) : '';
		$email = isset($_POST['email']) ? trim($_POST['email']) : '';
		$city_country = isset($_POST['city_country']) ? trim($_POST['city_country']) : '';
		$interest = isset($_POST['interest']) ? trim($_POST['interest']) : '';
		$description = isset($_POST['description']) ? trim($_POST['description']) : '';

		if ($name === '' || $mobile === '' || $email === '' || $city_country === '' || $interest === '' || $description === '') {
			die('Error! Missing required fields.');
		}

		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			die('Error! Invalid email address.');
		}

		$to = 'info@webtechcreators.com';
		$subject = 'New Website Enquiry - ' . $interest;

		$body = "Name: $name\n";
		$body .= "Mobile: $mobile\n";
		$body .= "Email: $email\n";
		$body .= "City/Country: $city_country\n";
		$body .= "Interest In: $interest\n\n";
		$body .= "Description:\n$description\n";

		$smtpHost = getenv('SMTP_HOST') ?: 'smtpout.secureserver.net';
		$smtpPort = (int)(getenv('SMTP_PORT') ?: 465);
		$smtpUser = getenv('SMTP_USER') ?: 'info@webtechcreators.com';
		$smtpPass = getenv('SMTP_PASS') ?: '';
		$smtpFrom = getenv('SMTP_FROM') ?: 'info@webtechcreators.com';
		$smtpFromName = getenv('SMTP_FROM_NAME') ?: 'WebTech Creators';

		if (!class_exists('PHPMailer\\PHPMailer\\PHPMailer')) {
			die('Error! Mail library not installed.');
		}

		if ($smtpPass === '') {
			die('Error! SMTP password not configured.');
		}

		$mailer = new PHPMailer\PHPMailer\PHPMailer(true);
		try {
			$mailer->isSMTP();
			$mailer->Host = $smtpHost;
			$mailer->SMTPAuth = true;
			$mailer->Username = $smtpUser;
			$mailer->Password = $smtpPass;
			$mailer->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
			$mailer->Port = $smtpPort;

			$mailer->CharSet = 'UTF-8';
			$mailer->setFrom($smtpFrom, $smtpFromName);
			$mailer->addAddress($to);
			$mailer->addReplyTo($email, $name);
			$mailer->Subject = $subject;
			$mailer->Body = $body;
			$mailer->send();
		} catch (Exception $e) {
			die('Error!');
		}

		header('Location: thank-you.html');
		exit;
	}

?>