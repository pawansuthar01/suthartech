<input type="hidden" name="bot_token" value="{{ \App\Services\FormBotProtection::issueToken() }}">
<div aria-hidden="true" style="position:absolute;left:-10000px;width:1px;height:1px;overflow:hidden;">
    <label>Leave this field empty <input type="text" name="website" value="" tabindex="-1" autocomplete="off"></label>
</div>
@if (\App\Services\FormBotProtection::enabled())
    <div class="form-bot-status" data-form-bot-check data-check-url="{{ route('form-bot.check') }}">
        <label class="form-bot-control">
            <input type="checkbox" data-bot-trigger aria-label="I’m not a robot" disabled>
            <span class="form-bot-indicator" aria-hidden="true"><span class="material-symbols-outlined">check</span></span>
            <strong>I’m not a robot</strong>
        </label>
        <span class="form-bot-message" data-bot-message role="status" aria-live="polite"></span>
    </div>
@endif
