<iframe srcdoc="{{ view('expiring-email::expiring_email_header', compact('email'))->render() }}" width="100%" height="80" frameborder="0"></iframe>

<div style="border-bottom: 4px solid rgb(31, 41, 55);"></div>

{!!$email->body !!}
