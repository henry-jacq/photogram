<?php

namespace App\Services;

// use PHPMailer\PHPMailer\SMTP;
use App\Core\View;
use App\Core\Config;
use Exception;
use PHPMailer\PHPMailer\PHPMailer;

/**
 * Mailer - PHPMailer wrapper for sending emails
 */
class MailService
{
    public $mailer;

    public function __construct(
        private readonly Config $config,
        private readonly View $view
    )
    {
        $fromName = $config->get('app.name');
        $fromAddress = $config->get('mail.from');
        $smtpHost = $config->get('mail.host');
        $smtpPort = $config->get('mail.port');
        $smtpAuthUser = $config->get('mail.user');
        $smtpAuthPass = $config->get('mail.pass');
        // Initialize PHPMailer with enabled exceptions.
        $this->mailer = new PHPMailer(true);

        // SMTP Server settings
        // Enable verbose debug output
        // $this->mailer->SMTPDebug = SMTP::DEBUG_SERVER;
        $this->mailer->isSMTP();
        $this->mailer->Host       = $smtpHost;
        $this->mailer->SMTPAuth   = true;
        $this->mailer->Username   = $smtpAuthUser;
        $this->mailer->Password   = $smtpAuthPass;
        $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mailer->Port       = $smtpPort;

        $this->mailer->setFrom($fromAddress, $fromName);
    }

    // HTML body for password reset mail
    // private function passwordResetMailBody($name, $reset_link)
    // {
    //     if (isset($name, $reset_link)) {
    //         $htmlMailBody = "
    //         <div style='line-height:1.5rem; margin-bottom:10px;'>
    //             <b>Hi $name,</b><br>
    //             We heard that you have lost your Photogram password. Sorry about that.<br>
    //             But don't worry, You can click on this link to reset your password: <a href='$reset_link'>Reset password</a><br>
    //             If you don't use the link within 30 minutes, it will expire.<br>
    //             reset_link visit $reset_link
    //         </div>
    //         Thanks,<br>
    //         <b>Photogram Team</b>
    //         ";
    //         return $htmlMailBody;
    //     } else {
    //         return false;
    //     }
    // }

    // /**
    //  * Send password-reset mail to the provided email
    //  */
    // public static function sendPasswordResetMail(string $to)
    // {
    //     try {
    //         $name = ucfirst(User::getUsernameByEmail($to));
    //         if ($name) {
    //             // Initialize mailer instance
    //             $mailer = new Mailer();

    //             $mailer->addRecipient($to);
    //             $mailer->addSubject("[Photogram] Reset your password!");
    //             $reset_link = Auth::createResetPasswordLink($to);
    //             $html = $mailer->passwordResetMailBody($name, $reset_link);
    //             $mailer->isHTML(true);
    //             $mailer->addBody($html);
    //             $mailer->sendMail();
    //             return $reset_link;
    //         }
    //     } catch (\Exception $e) {
    //         echo ("Mailer Error: {$e->getMessage()}");
    //     }
    // }

    /**
     * The Subject of the message.
     */
    public function addSubject(string $subject)
    {
        $this->mailer->Subject = $subject;
    }

    /**
     * Sets message type to HTML or plain.
     */
    public function isHTML(bool $isHTML)
    {
        $this->mailer->isHTML($isHTML);
    }

    /**
     * An HTML or plain text message body.
     */
    public function addBody(mixed $body)
    {
        $this->mailer->Body = $body;
    }

    /**
     * The plain-text message body.
     *
     * This body can be read by mail clients that do not have HTML email capability such as mutt & Eudora. Clients that can read HTML will view the normal Body.
     */
    public function addAltBody(string $altBody)
    {
        $this->mailer->AltBody = $altBody;
    }

    /**
     * Add an attachment from a path on the filesystem.
     */
    public function addAttachment($attachment, $name = null)
    {
        $this->mailer->addAttachment($attachment, $name);
    }

    /**
     * Add a "To" address.
     *
     * true on success, false if address already used or invalid in some way
     */
    public function addRecipient(string $address, $name = null)
    {
        $this->mailer->addAddress($address, $name);
    }

    /**
     * Create a message and send it.
     *
     * @return bool — false on error - See the ErrorInfo property for details of the error
     * @throws Exception
     */
    public function sendMail()
    {
        $this->mailer->send();
    }

    /**
     * Add a "CC" address.
     */
    public function addCC(string $address)
    {
        $this->mailer->addCC($address);
    }

    /**
     * Add a "BCC" address.
     */
    public function addBCC(string $address)
    {
        $this->mailer->addBCC($address);
    }

    /**
     * Add a "Reply-To" address.
     */
    public function addReplyTo(string $address)
    {
        $this->mailer->addReplyTo($address);
    }

    /**
     * Return rendered html mail template
     */
    public function getMailTemplate(string $templateName, array $params = []): string
    {
        try {
            $templatePath = "mails/{$templateName}";
            return $this->view
                ->createPage($templatePath, $params)
                ->getPageContents();
        } catch (Exception $e) {
            throw new Exception("Error: {$e->getMessage()}");
        }
    }
}
