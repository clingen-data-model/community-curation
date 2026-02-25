@if (config('app.env') == 'production' && config('mail.default') == 'log')
    <div class="bg-danger py-2">
        <div class="container">
            <strong>You are in a production system but email is currently turned off. <button data-toggle="collapse" data-target="#no-mail-collapse" class="btn btn-light btn-xs">More Info</button></strong>
            <div class="collapse mt-2 pl-2" id="no-mail-collapse">
                <p>
                    Email notifications, emails for password resets, and other emails will not be sent from the system.
                    <br>
                    Please notify the administrator.
                </p>
            </div>
        </div>
    </div>
@endif
@if (config('app.env') != 'production' && config('mail.default') != 'log')
<div class="bg-danger text-white py-2">
    <div class="container">
        <strong>You are not in the production but <u>Email is currently turned ON</u> for this system. <button data-toggle="collapse" data-target="#no-mail-collapse" class="btn btn-light btn-xs">More Info</button></strong>
        <div class="collapse mt-2 pl-2" id="no-mail-collapse">
            <p>
                Email notifications, emails for password resets, and other emails WILL be sent from the system.
                <br>
                Please contact the administrator to have email turned off.
            </p>
        </div>
    </div>
</div>
@endif
@if (config('app.env') != 'production' && config('mail.default') == 'log')
<div class="bg-success py-2">
    <div class="container">
        <strong>You are in a non-production system and Email is currently turned off for this system. <button data-toggle="collapse" data-target="#no-mail-collapse" class="btn btn-light btn-xs">More Info</button></strong>
        <div class="collapse mt-2 pl-2" id="no-mail-collapse">
            <p>
                Email notifications, emails for password resets, and other emails will not be sent from the system.
                <br>
                If you need to test email functionality by sending real emails or need your password reset please contact the administrator.
            </p>
        </div>
    </div>
</div>
@endif
