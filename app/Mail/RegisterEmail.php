<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegisterEmail extends Mailable
{
	use Queueable, SerializesModels;

	/**
	 * Create a new message instance.
	 *
	 * @return void
	 */
	public function __construct($data)
	{
		$this->data = $data;
	}

	public function build()
	{
		return $this->from(env('MAIL_USERNAME'), 'Epic Movie Quotes')
		->subject($this->data['subject'])
		->view($this->data['views'], ['name'=>$this->data['name'], 'url'=>$this->data['verification_code']]);
	}
}
