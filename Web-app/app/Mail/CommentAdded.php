<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

use App\Models\PostComments;

class CommentAdded extends Mailable
{
    public $comment;

    public function __construct(PostComments $comment)
    {
        $this->comment = $comment;
    }

    public function build()
    {
        return $this->view('emails.commentAdded')->with(['comment' => $this->comment]);
    }
}
