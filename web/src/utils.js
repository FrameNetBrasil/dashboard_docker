export function notify (type, message) {
    const variant = {
        "success": "success",
        "error": "danger",
        "info": "primary",
        "warning": "warning"
    };
    const icon = {
        "success": "task_alt",
        "error": "error",
        "info": "info",
        "warning": "warning"
    };
    const alert = Object.assign(document.createElement('sl-alert'), {
        variant: variant[type],
        closable: true,
        duration: 3000,
        innerHTML: `
        <div class="wt-toast">
<i class="material-icons p-1 ${type}" aria-hidden="true" role="img">${icon[type]}</i>
${message}</div>
      `
    });

    document.body.append(alert);
    alert.toast();
}
