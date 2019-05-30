<?php

namespace Application\Bundle\DefaultBundle\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class MailerService.
 */
class MailerService
{
    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var array
     */
    private $options = [];

    /**
     * @param \Swift_Mailer     $mailer
     * @param \Twig_Environment $twig
     * @param array             $options
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $twig, array $options)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
        if (empty($options)) {
            throw new \InvalidArgumentException('Options array can not be empty');
        }
        $this->options = $options;
    }

    /**
     * @param string|array $to
     * @param string       $subject
     * @param string       $template
     * @param array        $templateParams
     * @param array        $attachments
     *
     * @return bool|int
     */
    public function send($to, $subject, $template, array $templateParams = [], array $attachments = [])
    {
        $body = $this->getBody($template, $templateParams);

        return $this->sendMessage($subject, $body, $to, $attachments);
    }

    /**
     * send vacancy form to our mail.
     *
     * @param array  $params
     * @param string $jobTitle
     *
     * @return int
     */
    public function sendCandidateProfile(array $params, $jobTitle)
    {
        $attachments = [];
        if ($params['attach']) {
            /** @var UploadedFile $attach */
            $attach = $params['attach'];
            $attachFile = $attach->move(realpath($this->options['kernelRootDir'].'/../attachments/'), $attach->getClientOriginalName());
            $attachments[] = $attachFile;
        }

        $subject = sprintf('Анкета кандидата на вакансію %s від %s', $jobTitle, $params['email']);

        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($this->options['fromEmail'])
            ->setReplyTo($params['email'])
            ->setTo('hr@stfalcon.com');

        foreach ($attachments as $file) {
            $message->attach(\Swift_Attachment::fromPath($file->getRealPath())->setFilename($file->getFilename()));
        }

        $message->setBody(
            $this->getBody('@ApplicationDefault/emails/vacancy.html.twig', $params),
            'text/html'
        );

        return $this->mailer->send($message);
    }

    /**
     * @param string $email
     * @param string $pdf
     *
     * @return int
     */
    public function sendOrderPdf($email, $pdf)
    {
        $subject = sprintf('Order for %s', $email);

        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($this->options['fromEmail'])
            ->setReplyTo($email)
            ->setTo('hr@stfalcon.com');

        $message->attach(\Swift_Attachment::newInstance($pdf, 'order.pdf'));

        return $this->mailer->send($message);
    }

    /**
     * @param array  $params
     * @param string $subject
     *
     * @return mixed
     */
    public function sendPostDownloadedInfo(array $params, $subject)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($this->options['fromEmail'])
            ->setTo($this->options['fromEmail'])
            ->setBody(
                $this->getBody('@ApplicationDefault/emails/lead_form.html.twig', $params),
                'text/html'
            );

        return $this->mailer->send($message);
    }

    /**
     * @param string $template
     * @param array  $templateParams
     *
     * @return string
     */
    private function getBody($template, array $templateParams = [])
    {
        $templateContent = $this->twig->loadTemplate($template);

        return $templateContent->render($templateParams);
    }

    /**
     * @param string       $subject
     * @param string       $body
     * @param string|array $to
     * @param array        $attachments
     *
     * @return bool|int
     */
    private function sendMessage($subject, $body, $to, array $attachments = [])
    {
        if (is_array($to)) {
            list($toEmail, $toName) = $to;
        } else {
            $toEmail = $to;
            $toName = null;
        }
        try {
            /** @var \Swift_Message $message */
            $message = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom($this->options['fromEmail'], $this->options['fromName'])
                ->setTo($toEmail, $toName)
                ->setBody($body, 'text/html');

            if (!empty($attachments)) {
                /** @var UploadedFile $file */
                foreach ($attachments as $file) {
                    $message->attach(\Swift_Attachment::fromPath($file->getRealPath())->setFilename($file->getFilename()));
                }
            }

            return $this->mailer->send($message);
        } catch (\Exception $e) {
            return false;
        }
    }
}
