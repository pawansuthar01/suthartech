document.querySelectorAll('[data-form-bot-check]').forEach((indicator) => {
    const form = indicator.closest('form');
    const submitButton = form?.querySelector('[type="submit"]');
    const checkbox = indicator.querySelector('[data-bot-trigger]');
    const message = indicator.querySelector('[data-bot-message]');
    let checking = false;
    let submissionApproved = false;
    const loadedAt = Date.now();

    if (!form || !submitButton || !checkbox) return;

    const setState = (state, detail) => {
        indicator.dataset.state = state;
        message.textContent = detail;
    };

    const checkSubmission = async (beforeSubmit = false, submitter = null) => {
        if (checking) return;

        checking = true;
        const checkStartedAt = Date.now();
        const originalDisabled = submitButton.disabled;
        submitButton.disabled = true;
        checkbox.disabled = true;
        setState('checking', 'Checking…');

        try {
            if (beforeSubmit) {
                await new Promise((resolve) => setTimeout(resolve, Math.max(0, 2100 - (Date.now() - loadedAt))));
            }

            const formData = new FormData();
            formData.append('_token', form.querySelector('[name="_token"]')?.value || '');
            formData.append('bot_token', form.querySelector('[name="bot_token"]')?.value || '');
            formData.append('website', form.querySelector('[name="website"]')?.value || '');
            formData.append('before_submit', beforeSubmit ? '1' : '0');

            const response = await fetch(indicator.dataset.checkUrl, {
                method: 'POST',
                headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                body: formData,
            });
            const result = await response.json();
            if (!response.ok || !result.success) throw new Error(result.message || 'Please wait a moment and try again.');

            await new Promise((resolve) => setTimeout(resolve, Math.max(0, 800 - (Date.now() - checkStartedAt))));
            checkbox.checked = true;
            setState('verified', 'Verified');
            if (beforeSubmit) await new Promise((resolve) => setTimeout(resolve, 250));
        } catch (error) {
            await new Promise((resolve) => setTimeout(resolve, Math.max(0, 800 - (Date.now() - checkStartedAt))));
            checkbox.checked = false;
            setState('error', error.message);
        } finally {
            checking = false;
            checkbox.disabled = true;
            submitButton.disabled = originalDisabled;
        }

        if (beforeSubmit && checkbox.checked) {
            submissionApproved = true;
            form.requestSubmit(submitter || undefined);
        }
    };

    form.addEventListener('submit', (event) => {
        if (submissionApproved) {
            submissionApproved = false;
            return;
        }

        event.preventDefault();
        event.stopImmediatePropagation();
        checkSubmission(true, event.submitter);
    }, true);

    checkSubmission();
});
