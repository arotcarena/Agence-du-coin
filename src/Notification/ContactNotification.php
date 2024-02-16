<?php 
namespace App\Notification;
use App\Entity\Contact;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class ContactNotification
{
    /**
     * @var MailerInterface
     */
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function notify(Contact $contact)
    {   

        $email = new TemplatedEmail();
        $email
        ->subject('Message au sujet de l\'annonce : ' . $contact->getHouse()->getTitle())
        ->from('noreply@agence-du-coin.fr')
        ->replyTo($contact->getEmail())
        ->to('contact@agence-du-coin.fr')
        ->htmlTemplate('emails/contact.html.twig')
        ->textTemplate('emails/contact.txt.twig')
        ->attachFromPath('images/house/'.$contact->getHouse()->getImageName(), 'Photo du bien')
        ->context([
            'contact' => $contact,
            'house' => $contact->getHouse()
        ]);
        
        $this->mailer->send($email);
    }
        

        // $email = (new Email())
            // ->from('hello@example.com')
            // ->to('you@example.com')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            // ->subject('Time for Symfony Mailer!')
            // ->text('Sending emails is fun again!')
            // ->html('<p>See Twig integration for better HTML integration!</p>');
    
}


