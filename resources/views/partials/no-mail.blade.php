@if (config('mail.driver') == 'log')
<div class="bg-warning py-2">
    <div class="container">
        <strong>Email is currently turned off for this system. <button v-b-toggle.no-mail-collapse class="btn btn-light btn-xs">More Info</button></strong>
        <b-collapse id="no-mail-collapse" class="mt-2 pl-2">
            <p>
                Email notifications, emails for password resets, and other emails will not be sent from the system.
                <br>
                If you need to test email functionality by sending real emails or need your password reset please contact the administrator.
            </p>
        </b-collapse>        
    </div>
</div>
@endif