<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendEmail extends Mailable
{
	use Queueable, SerializesModels;

	/**
	 * The demo object instance.
	 *
	 * @var Demo
	 */
	public $demo;

	/**
	 * Create a new message instance.
	 *
	 * @return void
	 */
	public function __construct($demo)
	{
		$this->demo = $demo;
	}

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
    	if(isset($this->demo->path)){

		    return $this->from($this->demo->email)
		                ->subject($this->demo->subject)
		                ->view('mails.bugs')
		                ->text('mails.bugs_plain')
		                ->attach(public_path('/images/email_photo/').$this->demo->path, [
			                //'as' => 'demo.jpg',
			                // 'mime' => 'image/jpeg',
		                ]);

	    } else {


		    return $this->from( $this->demo->email )
		                ->subject( $this->demo->subject )
		                ->view( 'mails.bugs' )
		                ->text( 'mails.bugs_plain' );
	    }
    }
}
