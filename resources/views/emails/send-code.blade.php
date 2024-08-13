<!doctype html>
<html lang="en-US">

<head>
  <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
  <title>Password Email Verification</title>
  <meta name="description" content="Email Verification code.">
  <style type="text/css">
    a:hover {
      text-decoration: underline !important;
    }
  </style>
</head>

<body marginheight="0" topmargin="0" marginwidth="0" style="margin: 0px; background-color: #f2f3f8;" leftmargin="0">
  @component('mail::message')
<h1>We have received your request to verify your account</h1>
<p>You can use the following code to verify your account:</p>

@component('mail::panel')
{{ $code }}
@endcomponent

<p>The allowed duration of the code is one minute from the time the message was sent</p>
@endcomponent
</body>

</html>
