<?php
// backend/email.php
/*
Copyright © 2024 NA7KR Kevin Roberts. All rights reserved.

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

    http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
 */
require_once '../config.php'; // Include PHPMailerPro library
require_once 'PHPMailerPro/PHPMailer.Pro.php'; // Include PHPMailerPro library
class_alias("codeworxtech\PHPMailerPro\PHPMailerPro", "PHPMailerPro"); // Alias PHPMailerPro class

// Function to send an email notification about the missing file.
function send_email_notification($missingFiles) {
    try {
        // Ensure $missingFiles is an array
        if (!is_array($missingFiles) || empty($missingFiles)) {
            return; // No missing files to send notification for
        }

        // Construct the email body with the list of missing files
        $body = "The following files are missing:<br>";

        foreach ($missingFiles as $file) {
            $body .= "File {$file['fileName']} is missing in folder {$file['folderPath']}<br>";
        }

        $body .= "NA7KR";

        // Create a new PHPMailerPro instance
        $mail = new PHPMailerPro();
        
        // Set sender and recipient
        $sender = ["kevin@na7kr.us" => 'QSL CARD'];
        $recipient = ["kevin@na7kr.us" => 'QSL CARD'];
        $mail->SetSender($sender);
        $mail->AddRecipient($recipient);

        // Set email subject and body
        $mail->subject = "File Missing Notification";
        $mail->messageHTML = $body;

        // Set SMTP configuration
        $mail->smtpHost     = emailserver;
        $mail->smtpPort     = emailpost;
        $mail->smtpUsername = senderemail;
        $mail->smtpPassword = emailpassword;

        // Send email
        if ($mail->Send('smtp')) {
            echo "Email sent successfully." . PHP_EOL;
        } else {
            echo "Mailer Error: " . $mail->ErrorInfo;
        }
    } catch (Exception $e) {
        echo $e->errorMessage();
    }
}
